<?php

namespace Responsive\Listeners;

use Responsive\Events\JobHiredApplicationMarkedAsComplete;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Responsive\Job;
use DB;
use Responsive\Transaction;

class ReleaseJobApplicationFundsToApplicant
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
     * @param  JobHiredApplicationMarkedAsComplete  $event
     * @return void
     */
    public function handle(JobHiredApplicationMarkedAsComplete $event)
    {
        //mark job application as complete and release funds.
        $application = $event->job_application;
        DB::transaction(function () use ($application){
            $transaction = Transaction::where('job_id', $application->job_id)
            ->where('application_id', $application->id)
                ->where('type', 'job_fee')
                ->where('credit_payment_status', 'funded')->get()->first();
            $transaction->credit_payment_status = 'paid';
            $transaction->save();
            $application->completion_status = 1;
            $application->save();

        });
    }
}
