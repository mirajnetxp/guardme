<?php

namespace Responsive\Http\Controllers\Api;

use Responsive\Feedback;
use Responsive\FreelancerSetting;
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
		          ->leftJoin( 'transactions', 'job_applications.job_id', '=', 'transactions.job_id' )
		          ->where( 'credit_payment_status', '=', 'funded' )
		          ->select( 'security_jobs.title', 'job_applications.created_at', 'transactions.amount' )
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
		                 ->select( 'job_applications.id as application_id', 'job_applications.job_id', 'security_jobs.title', 'security_jobs.number_of_freelancers', 'job_applications.updated_at' )
		                 ->get();

		foreach ( $awardedJobs as $key => $value ) {
			$Samount = Transaction::
			where( 'job_id', $awardedJobs[ $key ]->job_id )
			                      ->where( 'transactions.credit_payment_status', '=', 'funded' )
			                      ->first();


			$Iamount = $Samount['amount'] / $awardedJobs[ $key ]->number_of_freelancers;


			$awardedJobs[ $key ]->amount = $Iamount;


			$sech                           = DB::table( 'security_jobs_schedule' )
			                                    ->where( 'job_id', $awardedJobs[ $key ]->job_id )
			                                    ->select( 'start as start_time', 'end as end_time' )
			                                    ->get();
			$awardedJobs[ $key ]->schedules = $sech;


		}

		return response()->json( $awardedJobs, 200 );
	}


	public function JobDecline( $application_id ) {

		if ( auth()->user()->admin != 2 ) {
			return response()->json( 403 );
		}
		$ID         = auth()->user()->id;
		$aplication = JobApplication::find( $application_id );
		if ( $aplication->applied_by !== $ID ) {
			return response()->json( 403 );
		}
		$aplication->is_hired = false;
		$aplication->save();

		return response()->json( [ 'decline' => '200' ], 200 );

	}

	public function withdrawApplication( $application_id ) {
		if ( auth()->user()->admin != 2 ) {
			return response()->json( 403 );
		}
		$ID         = auth()->user()->id;
		$aplication = JobApplication::find( $application_id );
		if ( $aplication->applied_by !== $ID ) {
			return response()->json( 403 );
		}
		$aplication->delete();

		return response()->json( [ 'withdraw' => '200' ], 200 );
	}

	public function averageFeedback( $id ) {

		$cats = DB::table( 'job_applications' )
		          ->where( 'applied_by', $id )
		          ->join( 'feedback', 'job_applications.id', '=', 'feedback.application_id' )
		          ->select( DB::raw( '(feedback.appearance + feedback.punctuality + feedback.customer_focused + feedback.security_conscious)/4 as average_rating_per' ) )
		          ->get();

		if ( count( $cats ) > 0 ) {
			$rating = $cats->sum( 'average_rating_per' ) / count( $cats );
		} else {
			$rating = "Not Available";
		}

		return response()->json( [ 'rating' => $rating ], 200 );
	}

	public function openJobApplications() {
		$uID = auth()->user()->id;

		$cats = DB::table( 'job_applications' )
		          ->where( 'applied_by', $uID )
		          ->join( 'security_jobs', 'job_applications.job_id', '=', 'security_jobs.id' )
		          ->where( 'security_jobs.status', 1 )
		          ->where( 'job_applications.completion_status', 0 )
		          ->select( 'security_jobs.id as job_id', 'security_jobs.title as job_title', 'job_applications.completion_status' )
		          ->get();

		return response()->json( $cats );
	}

	public function visibility() {
		$id     = auth()->user()->id;
		$status = FreelancerSetting::where( 'user_id', $id )->first();

		if ( $status->visible ) {
			$res = 'public';
		} else {
			$res = 'private';
		}

		return response()->json( [ 'visibility' => $res ] );

	}

	public function SetVisibility() {
		$id = auth()->user()->id;


		$status = FreelancerSetting::where( 'user_id', $id )->first();

		if ( $status->visible ) {

			DB::table( 'freelancer_settings' )
			  ->where( 'user_id', $id )
			  ->update( [ 'visible' => 0 ] );

			$res = 'private';
		} else {
			DB::table( 'freelancer_settings' )
			  ->where( 'user_id', $id )
			  ->update( [ 'visible' => 1 ] );
			$res = 'public';
		}

		return response()->json( [ 'visibility_set_to' => $res ] );
	}
}
