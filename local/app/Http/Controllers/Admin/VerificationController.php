<?php

namespace Responsive\Http\Controllers\Admin;


use Responsive\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Responsive\Http\Requests;
use Illuminate\Http\Request;
use Responsive\User;
use Illuminate\Support\Facades\Session;
use File;
use Image;


class VerificationController extends Controller {


	/**
	 * This Controller Is for user documents verification
	 */

	public function __construct() {
		$this->middleware( 'admin' );
	}

	public function showUsers() {
		$users = User::where( 'admin', '=', 2 )->get();

		return view( 'admin.verification' )->with( 'users', $users );
	}

	public function userDetail( $id ) {
		$user = User::find( $id );

		return view( 'admin.verification-user-details' )
			->with( 'user', $user );
	}

	public function userDocApproved( $id ) {
		$user               = User::find( $id );
		$user->doc_verified = true;
		$user->save();
		Session::flash( 'success', 'Account :"'.$user->name.'" Verification Approved ' );

		return redirect( '/admin/Verification' );
	}
	public function userDocDecline( $id ) {
		$user               = User::find( $id );
		$user->doc_verified = false;
		$user->save();
		Session::flash( '""'.$user->name.'" has been unverified' );

		return redirect( '/admin/Verification' );
	}

}
