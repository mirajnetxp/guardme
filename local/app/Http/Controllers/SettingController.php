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
		$userids =  DB::select('select id from users');
		$visibles = FreelancerSetting::all();
		if (count($visibles) == 0) {
			$query = "";
			foreach ($userids as $userid) {
				DB::insert('insert into freelancer_settings(user_id, visible) values ('.$userid->id.', 0);');
			}
		}
		if ( ! Auth::Check() ) {
			return redirect( '/' );
		}
		$visible = auth()->user()->freelancerSettings->visible;
		return view( 'setting', compact('visible'));
	}

	public function visibality() {
		if ( ! Auth::Check() ) {
			return redirect( '/' );
		}
		if ( auth()->user()->freelancerSettings->visible == 1 ) {
			DB::table( 'freelancer_settings' )
			  ->where( 'user_id', auth()->user()->id )
			  ->update( [ 'visible' => 0 ] );

			return response()->json( '102', 200 );
		} else {
			DB::table( 'freelancer_settings' )
			  ->where( 'user_id', auth()->user()->id )
			  ->update( [ 'visible' => 1 ] );

			return response()->json( '101', 200 );
		}
	}

}
