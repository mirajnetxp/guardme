<?php

namespace Responsive\Http\Controllers;


use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Responsive\FreelancerSetting;


class NotificationController extends Controller {
	public function all(  ) {
		return view( 'notifications' );
	}

}
