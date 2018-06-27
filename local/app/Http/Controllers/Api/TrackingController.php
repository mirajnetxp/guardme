<?php

namespace Responsive\Http\Controllers\Api;


use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Responsive\Http\Controllers\Controller;


class TrackingController extends Controller {

	public function __construct() {
		$this->middleware( 'auth' );
	}

	public function postTracking( Request $request ) {

		$this->validate( $request, [
			'job_id'             => 'required',
			'user_id'            => 'required',
			'location_longitude' => 'required',
			'location_latitude'  => 'required',
			'address'            => 'required',
			'date_time'          => 'required',
			'schedule_id'          => 'required',
		] );

		DB::table( 'tracking' )->insert(
			[
				'job_id'             => $request->job_id,
				'user_id'            => $request->user_id,
				'location_longitude' => $request->location_longitude,
				'location_latitude'  => $request->location_latitude,
				'address'            => $request->address,
				'date_time'          => $request->date_time,
				'schedule_id'        => $request->schedule_id

			]
		);

		return response()->json( [ 'Tracking add to DB succesufully' ] );
	}


}
