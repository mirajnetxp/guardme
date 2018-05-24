<?php

namespace Responsive\Listeners;

use Responsive\Events\AwardJob;
use Responsive\Events\JobAwarded;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Responsive\Job;
use Responsive\JobApplication;
use Responsive\Transaction;
use DB;
class AwardJobAndAdjustTransactions
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  JobAwarded  $event
     * @return void
     */
    public function handle(AwardJob $event)
    {
        //
        $application = $event->job_application;
        $job_id = $application->job_id;
        $job = Job::find($job_id);
        // find the transaction where we stored combined amount (un-allocated escrow amount) for the job
        // criteria will be debit_credit_type =  'credit' and credit_payment_status = 'funded' and application_id = null and status=1;
        $reserved_amount_transaction = Transaction::where('debit_credit_type', 'credit')
        ->where('credit_payment_status', 'funded')
        ->where('application_id', null)
        ->where('status', 1)
        ->where('job_id', $job_id)->get()->first();
        $total_number_of_freelancers = $job->number_of_freelancers;
        $job_hired_applications = JobApplication::where('is_hired', true)
        ->where('job_id', $job_id);
        $number_of_already_hired_freelancers = count($job_hired_applications);
        $vacant_positions = $total_number_of_freelancers - $number_of_already_hired_freelancers;
        if ($vacant_positions > 0) {
            DB::transaction(function () use ($application, $reserved_amount_transaction, $job){
                $old_amount = $reserved_amount_transaction->amount;
                $job_details = Job::calculateJobAmountWithJobObject($job);
                $application_job_fee_amount = $job_details['single_freelancer_fee'];
                $new_amount = $old_amount - $application_job_fee_amount;
                $employer_id = $reserved_amount_transaction->user_id;
                $reserved_amount_transaction->amount = $new_amount;
                $reserved_amount_transaction->updated_at = date('Y-m-d h:i:s');
                // updating record with new amount
                $reserved_amount_transaction->save();
                // add new transaction with application id
                $trans = new Transaction();
                $trans->amount = $application_job_fee_amount;
                $trans->debit_credit_type = 'credit';
                $trans->credit_payment_status = 'funded';
                $trans->status = 1;
                $trans->application_id = $application->id;
                $trans->type = 'job_fee';
                $trans->job_id = $application->job_id;
                $trans->title = 'Job Fee';
                $trans->user_id = $employer_id;
                $trans->save();
                $application->is_hired = 1;
                $application->save();
            });
        }





        // calculate amount for this job per application.
        
    }
}
