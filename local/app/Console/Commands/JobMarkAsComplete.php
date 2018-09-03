<?php

namespace Responsive\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Mail;
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

		$ExpairJobs       = [];
		$ExpairJobsNotify = [];

		$presentTime = Carbon::now();

		foreach ( $allSchedule as $key => $val ) {
			$timArry               = $val->toArray();
			$jobEndTime            = new Carbon( end( $timArry )['end'] );
			$jobEndTimeWithOutPlus = new Carbon( end( $timArry )['end'] );
			$jobEndTimePlus36Hours = $jobEndTime->addHour( 36 );

			if ( $presentTime->gt( $jobEndTimePlus36Hours ) ) {
				$ExpairJobs[] = $key;
			}
			if ( $presentTime->gt( $jobEndTimeWithOutPlus ) && $presentTime->lt( $jobEndTimePlus36Hours ) ) {
				$ExpairJobsNotify[] = $key;
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
				$transection = Transaction::where( 'job_id', $job->id )
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

		$AllNotifiableJob = Job::where( 'status', 1 )->whereIn( 'id', $ExpairJobsNotify )->get();

		foreach ( $AllNotifiableJob as $key => $val ) {
			$creator = User::find( $val->created_by );
			$val->ja = DB::table( 'job_applications as ja' )
			             ->select( 'u.name as user_name',
				             'ja.application_description as description',
				             'ja.id',
				             'ja.is_hired',
				             'u.photo as photo',
				             'u.id as u_id',
				             'u.email as u_email',
				             'ja.created_at as applied_date',
				             'ja.applied_by',
				             'ja.completion_status'
			             )
			             ->join( 'security_jobs as sj', 'sj.id', '=', 'ja.job_id' )
			             ->join( 'users as u', 'u.id', '=', 'ja.applied_by' )
			             ->where( 'ja.job_id', $val->id )
			             ->where( 'sj.created_by', $creator->id )
			             ->where( 'ja.is_hired', 1 )
			             ->get();


			if ( $val->notify == '0' ) {

				$companyName = $creator->company->shop_name;
				$JobTitle    = $val->title;
				$Eemail      = $creator->email;
				$data        = array( 'companyName' => $companyName, 'JobTitle' => $JobTitle );

				Mail::send( 'job-compelete-mail-employer', $data, function ( $message ) use ( $Eemail ) {
					$message->to( $Eemail, 'GuardME' )->subject( 'Job completed' );
				} );

				foreach ( $val->ja as $ja ) {
					$freeName = $ja->user_name;
					$femail   = $ja->u_email;
					$dataF    = array( 'freeName' => $freeName, 'JobTitle' => $JobTitle );
					Mail::send( 'job-compelete-mail-freelancer', $dataF, function ( $message ) use ( $femail ) {
						$message->to( $femail, 'GuardME' )->subject( 'Job completed' );
					} );
				}

				$job         = Job::find( $val->id );
				$job->notify = true;
				$job->save();

			}
		}

	}
}
