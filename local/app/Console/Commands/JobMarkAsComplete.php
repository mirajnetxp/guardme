<?php

namespace Responsive\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Responsive\Events\JobHiredApplicationMarkedAsComplete;
use Responsive\Job;
use Responsive\JobApplication;
use Responsive\Notifications\JobMarkedComplete;
use Responsive\Notifications\RecivesPayment;
use Responsive\SecurityJobsSchedule;
use Responsive\Transaction;
use Responsive\User;
use Carbon\Carbon;


class JobMarkAsComplete extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'JobMarkAsComplete:markjobascomplete';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'To mark a job as compelete';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {

		$AllJobsObj  = Job::where( 'status', 1 );
		$AllJobsIds  = $AllJobsObj->pluck( 'id' );
		$allSchedule = SecurityJobsSchedule::whereIn( 'job_id', $AllJobsIds )
		                                   ->get()
		                                   ->groupBy( 'job_id' )
		                                   ->all();

		$ExpairJobs  = [];
		$presentTime = Carbon::now();

		foreach ( $allSchedule as $key => $val ) {
			$timArry               = $val->toArray();
			$jobEndTime            = new Carbon( end( $timArry )['end'] );
			$jobEndTimePlus36Hours = $jobEndTime->addHour( 36 );

			if ( $presentTime->gt( $jobEndTimePlus36Hours ) ) {
				$ExpairJobs[] = $key;
			}
		}
		$AllExpJobs = $AllJobsObj->whereIn( 'id', $ExpairJobs )->get();

		foreach ( $AllExpJobs as $key => $val ) {

			$approvedApplications = JobApplication::where( 'job_id', $val->id )
			                                      ->where( 'is_hired', 1 )
			                                      ->where( 'completion_status', 0 )
			                                      ->get();

			foreach ( $approvedApplications as $approvedAppl ) {
				event( new JobHiredApplicationMarkedAsComplete( $approvedAppl ) );
				$userFreelancer = User::find( $approvedAppl->applied_by );
				$userFreelancer->notify( new JobMarkedComplete( $val ) );
				$userFreelancer->notify( new RecivesPayment( $val ) );
			}

			$job         = Job::find( $val->id );
			$jobAmount   = $job->calculateJobAmountWithJobObject( $job );
			$hiredJobApp = JobApplication::where( 'job_id', $job->id )
			                             ->where( 'is_hired', 1 )
			                             ->get();

			$currentHireFreelancer = count( $hiredJobApp );
			if ( $currentHireFreelancer == 0 ) {
				$trans    = new Transaction();
				$returned = $trans->giveRefund( $job );
			} else {
				$transection         = Transaction::where( 'job_id', $job->id )
				                                  ->where( 'type', 'job_fee' )
				                                  ->where( 'debit_credit_type', 'credit' )
				                                  ->whereNull( 'application_id' )
				                                  ->first();

				$vat                 = $transection->amount * ( .2 );
				$admin               = $transection->amount * ( .1499 );
				$transection->amount = $vat + $admin + $transection->amount;
				$transection->type   = 'refund';
				$transection->save();
			}
			$job->status = 0;
			$job->save();

		}
	}
}
