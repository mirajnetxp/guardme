<?php

namespace Responsive\Http\Controllers\Admin;


use Responsive\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Responsive\Http\Requests;
use Illuminate\Http\Request;
use Responsive\Job;
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

		return view( 'admin.jobs',['allJobs'=>$allJobs] );
	}
}
