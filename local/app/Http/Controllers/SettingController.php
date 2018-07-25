<?php

namespace Responsive\Http\Controllers;


use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Responsive\FreelancerSetting;
use Responsive\Newsletter;


class SettingController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */


	public function show() {
		$visibles = array();
		$temparr  = array();
		$userids  = DB::select( 'select id from users' );
		$visibles = DB::select( 'select user_id from freelancer_settings' );
		$query    = '';
		if ( count( $visibles ) > 0 ) {
			foreach ( $visibles as $value ) {
				$temparr[ $value->user_id ] = $value->user_id;
			}
		}
		foreach ( $userids as $userid ) {
			if ( count( $temparr ) > 0 ) {
				if ( ! isset( $temparr[ $userid->id ] ) ) {
					DB::insert( 'insert into freelancer_settings(user_id, visible, created_at, updated_at) values (' . $userid->id . ', 0, "' . date( "Y-m-d H:i:s" ) . '", "' . date( "Y-m-d H:i:s" ) . '");' );
				}
			} else {
				DB::insert( 'insert into freelancer_settings(user_id, visible, created_at, updated_at) values (' . $userid->id . ', 0, "' . date( "Y-m-d H:i:s" ) . '", "' . date( "Y-m-d H:i:s" ) . '");' );
			}
		}
		if ( ! Auth::Check() ) {
			return redirect( '/' );
		}


		$user       = auth()->user();
		$visible    = $user->freelancerSettings->visible;
		$settings   = $user->freelancerSettings;
		$paymethod  = $user->paymentmethod;
		$newsletter = Newsletter::where( 'email', $user->email )->first();

		if ( ! $newsletter ) {
			$newsletter         = new Newsletter();
			$newsletter->email  = $user->email;
			$newsletter->status = 1;
			$newsletter->save();
		}


		return view( 'setting', compact( 'visible', 'paymethod', 'settings', 'newsletter' ) );
	}

	public function visibality() {


		if ( ! Auth::Check() ) {
			return redirect( '/' );
		}
		if ( auth()->user()->freelancerSettings->visible == 1 ) {
			DB::table( 'freelancer_settings' )
			  ->where( 'user_id', auth()->user()->id )
			  ->update( [ 'visible' => 0, 'updated_at' => date( "Y-m-d H:i:s" ) ] );

			return response()->json( '102', 200 );
		} else {
			DB::table( 'freelancer_settings' )
			  ->where( 'user_id', auth()->user()->id )
			  ->update( [ 'visible' => 1, 'updated_at' => date( "Y-m-d H:i:s" ) ] );

			return response()->json( '101', 200 );
		}
	}

	public function gps() {


		if ( ! Auth::Check() ) {
			return redirect( '/' );
		}
		if ( auth()->user()->freelancerSettings->gps == 1 ) {
			DB::table( 'freelancer_settings' )
			  ->where( 'user_id', auth()->user()->id )
			  ->update( [ 'gps' => 0, 'updated_at' => date( "Y-m-d H:i:s" ) ] );

			return response()->json( '102', 200 );
		} else {
			DB::table( 'freelancer_settings' )
			  ->where( 'user_id', auth()->user()->id )
			  ->update( [ 'gps' => 1, 'updated_at' => date( "Y-m-d H:i:s" ) ] );

			return response()->json( '101', 200 );
		}
	}

	public function newsletter() {


		if ( ! Auth::Check() ) {
			return redirect( '/' );
		}

		$news = Newsletter::where( 'email', auth()->user()->email )->first();

		if ( $news->status == 1 ) {

			$news->status = 0;
			$news->save();

			return response()->json( '102', 200 );
		} else {
			$news->status = 1;
			$news->save();

			return response()->json( '101', 200 );
		}
	}

}
