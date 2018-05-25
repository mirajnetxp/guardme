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

class FreelancerJobsController extends Controller {

	public function applyedJobList() {
	$user=auth()->user();
	$jobs = DB::table('job_applications')
						->where('applied_by','=',$user->id)
		                 ->leftJoin('security_jobs', 'job_applications.job_id', '=', 'security_jobs.id')
		                 ->select('security_jobs.title','job_applications.created_at')
		                 ->get()->toArray();
		$total=count($jobs);

		return response()->json($jobs,200);
	}
}
