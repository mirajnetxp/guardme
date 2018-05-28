<?php

namespace Responsive\Http\Controllers\Api;

use Responsive\Feedback;
use Responsive\Http\Traits\JobsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Responsive\Http\Controllers\Controller;
use Responsive\Job;
use Responsive\JobApplication;
use Responsive\Transaction;
use Responsive\User;
use Responsive\Businesscategory;
use Responsive\SecurityCategory;
use Responsive\Events\JobHiredApplicationMarkedAsComplete;
use Responsive\Events\AwardJob;

class EmployerJobsController extends Controller {


	/**
	 * @param $id
	 *$id= job id
	 *
	 * @return mixed
	 */
	public function JobApplications( $id ) {


		$job = auth()->user()->jobs()->where( 'id', $id )->get();
		if ( count( $job ) == 0 ) {
			return response()->json( 403 );
		}

		$applications = DB::table( 'job_applications' )
		                  ->where( 'job_id', $id )
		                  ->leftJoin( 'users', 'job_applications.applied_by', '=', 'users.id' )
		                  ->select( 'job_applications.id', 'job_applications.applied_by', 'users.name', 'users.photo', 'job_applications.created_at', 'job_applications.is_hired', 'job_applications.application_description' )
		                  ->get();

		return response()->json( $applications, 200 );

	}

	public function awardedJobs() {
		if ( auth()->user()->admin != 0 ) {
			return response()->json( 403 );
		}
		$ID          = auth()->user()->id;
		$awardedJobs = DB::table( 'security_jobs' )
		                 ->where( 'created_by', $ID )
		                 ->Join( 'job_applications', 'security_jobs.id', '=', 'job_applications.job_id' )
		                 ->where( 'is_hired', 1 )
		                 ->rightJoin( 'transactions', 'security_jobs.id', '=', 'transactions.job_id' )
		                 ->where( 'transactions.credit_payment_status', '=', 'funded' )
		                 ->select( 'job_applications.job_id', 'security_jobs.title','transactions.amount', 'job_applications.updated_at' )
		                 ->get();

		return response()->json( $awardedJobs, 200 );
	}
}
