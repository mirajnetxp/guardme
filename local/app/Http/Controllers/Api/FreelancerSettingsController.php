<?php

namespace Responsive\Http\Controllers\Api;

use Responsive\Feedback;
use Responsive\FreelancerSetting;
use Responsive\Http\Traits\JobsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Responsive\Http\Controllers\Controller;


class FreelancerSettingsController extends Controller {

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

	public function gps() {
		$id     = auth()->user()->id;
		$status = FreelancerSetting::where( 'user_id', $id )->first();

		if ( $status->gps ) {
			$res = 'active';
		} else {
			$res = 'inactive';
		}

		return response()->json( [ 'gps' => $res ] );

	}

	public function SetGps() {
		$id = auth()->user()->id;

		$status = FreelancerSetting::where( 'user_id', $id )->first();

		if ( $status->gps ) {

			DB::table( 'freelancer_settings' )
			  ->where( 'user_id', $id )
			  ->update( [ 'gps' => 0 ] );

			$res = 'inactive';
		} else {
			DB::table( 'freelancer_settings' )
			  ->where( 'user_id', $id )
			  ->update( [ 'gps' => 1 ] );
			$res = 'active';
		}

		return response()->json( [ 'gps' => $res ] );
	}
}
