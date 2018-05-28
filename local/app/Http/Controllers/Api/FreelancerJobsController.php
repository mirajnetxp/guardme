<?php

namespace Responsive\Http\Controllers\Api;

use Responsive\Feedback;
use Responsive\Http\Traits\JobsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Responsive\Http\Controllers\Controller;
use Responsive\Job;
use Responsive\JobApplication;
use Responsive\SavedJob;
use Responsive\Transaction;
use Responsive\User;
use Responsive\Businesscategory;
use Responsive\SecurityCategory;
use Responsive\Events\JobHiredApplicationMarkedAsComplete;
use Responsive\Events\AwardJob;

class FreelancerJobsController extends Controller {

	public function applyedJobList() {
		$user = auth()->user();
		$jobs = DB::table( 'job_applications' )
		          ->where( 'applied_by', '=', $user->id )
		          ->leftJoin( 'security_jobs', 'job_applications.job_id', '=', 'security_jobs.id' )
		          ->select( 'security_jobs.title', 'job_applications.created_at' )
		          ->get()
		          ->toArray();

		return response()->json( $jobs, 200 );
	}

	public function saveJob( Request $request ) {
//		if(Job::find($request->id)){}
		$saveAjob          = new SavedJob();
		$saveAjob->job_id  = $request->id;
		$saveAjob->user_id = auth()->user()->id;
		$saveAjob->save();

		return response()->json( [ "200" => "saved" ], 200 );
	}

	/**
	 * @return mixed
	 */
	public function SaveJobList() {
		$ID            = auth()->user()->id;
		$favouriteJobs = DB::table( 'saved_jobs' )
		                   ->where( 'user_id', $ID )
		                   ->leftJoin( 'security_jobs', 'saved_jobs.job_id', '=', 'security_jobs.id' )
		                   ->select( 'security_jobs.id', 'security_jobs.title' )
		                   ->get();

		return response()->json( $favouriteJobs, 200 );

	}

	public function awardedJobs() {

		if ( auth()->user()->admin != 2 ) {
			return response()->json( 403 );
		}
		$ID          = auth()->user()->id;
		$awardedJobs = DB::table( 'job_applications' )
		                 ->where( 'applied_by', $ID )
		                 ->where( 'is_hired', 1 )
		                 ->Join( 'security_jobs', 'job_applications.job_id', '=', 'security_jobs.id' )
		                 ->Join( 'transactions', 'job_applications.job_id', '=', 'transactions.job_id' )
		                 ->where( 'transactions.credit_payment_status', '=', 'funded' )
		                 ->select( 'job_applications.job_id', 'security_jobs.title', 'transactions.amount', 'job_applications.updated_at' )
		                 ->get();

		return response()->json( $awardedJobs, 200 );
	}

}
