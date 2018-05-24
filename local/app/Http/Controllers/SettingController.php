<?php

namespace Responsive\Http\Controllers;


use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Responsive\FreelancerSetting;


class SettingController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */


	public function show() {
		if ( ! Auth::Check() || auth()->user()->admin != '2' ) {
			return redirect( '/' );
		}

		return view( 'setting' );
	}

	public function visibality() {


		if ( ! Auth::Check() || auth()->user()->admin != '2' ) {
			return redirect( '/' );
		}
		if ( auth()->user()->freelancerSettings->visible == true ) {
			DB::table( 'freelancer_settings' )
			  ->where( 'user_id', auth()->user()->id )
			  ->update( [ 'visible' => false ] );

			return response()->json( '102', 200 );
		} else {
			DB::table( 'freelancer_settings' )
			  ->where( 'user_id', auth()->user()->id )
			  ->update( [ 'visible' => true ] );

			return response()->json( '101', 200 );
		}
	}

}
