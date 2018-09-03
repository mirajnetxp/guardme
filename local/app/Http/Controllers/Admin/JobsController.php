<?php

namespace Responsive\Http\Controllers\Admin;


use Responsive\Events\JobHiredApplicationMarkedAsComplete;
use Responsive\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Responsive\Http\Requests;
use Illuminate\Http\Request;
use Responsive\Job;
use Responsive\JobApplication;
use Responsive\Transaction;
use Responsive\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use File;
use Image;


class JobsController extends Controller {


	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware( 'admin' );
	}


	public function index() {
		$allJobs = Job::all();

		return view( 'admin.jobs', [ 'allJobs' => $allJobs ] );
	}

	public function MarkJobAsCompelete( Request $request ) {

		$job = Job::find( $request->jobId );


		$user = auth()->user();

		$approvedApplications = JobApplication::where( 'job_id', $job->id )
		                                      ->where( 'is_hired', 1 )
		                                      ->where( 'completion_status', 0 )
		                                      ->get();


		foreach ( $approvedApplications as $approvedAppl ) {
			event( new JobHiredApplicationMarkedAsComplete( $approvedAppl ) );
			$userFreelancer = User::find( $approvedAppl->applied_by );
			$userFreelancer->notify( new JobMarkedComplete( $job ) );
			$userFreelancer->notify( new RecivesPayment( $job ) );
		}


		$jobAmount   = $job->calculateJobAmountWithJobObject( $job );
		$hiredJobApp = JobApplication::where( 'job_id', $job->id )
		                             ->where( 'is_hired', 1 )
		                             ->get();

		$requeredNumberOfFree  = $jobAmount['number_of_freelancers'];
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


		return response()->json( '200', 200 );
	}

	public function Delete( $id ) {
		$job = Job::find( $id );
		//TODO add created by check so that every one can only cancel his/her created job and can not manipulate it by changing job id.
		// check if job is active
		$trans         = new Transaction();
		$returned      = $trans->giveRefund( $job );

		return back();
	}

	public function Stop( $id ) {
		$job = Job::find( $id );
		//TODO add created by check so that every one can only cancel his/her created job and can not manipulate it by changing job id.
		$job->status   = 0;
		$job->is_pause = 1;
		$job->save();

		return back();
	}

	public function Start( $id ) {
		$job = Job::find( $id );
		//TODO add created by check so that every one can only cancel his/her created job and can not manipulate it by changing job id.
		$job->status   = 1;
		$job->is_pause = 0;
		$job->save();


		return back();
	}
}
