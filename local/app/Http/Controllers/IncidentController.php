<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Responsive\IncidentReport;
use Responsive\JobApplication;

class IncidentController extends Controller {
	public function __construct() {
		$this->middleware( 'auth' );
	}

	public function addIncident( Request $request ) {
		$user = auth()->user();

		$JA = JobApplication::where( 'job_id', $request->job_id )
		                    ->where( 'applied_by', $user->id )
		                    ->where( 'is_hired', 1 )
		                    ->get();


		if ( $user->admin != 2 || count($JA)==0 ) {
			return response()->json( [ 'You are not authorized to perform this task' ], 403 );
		}


		$incident                  = new IncidentReport();
		$incident->user_id         = $user->id;
		$incident->job_id          = $request->job_id;
		$incident->incident_report = $request->incident_report;
		$incident->save();

		return response()->json( "Report added successfully", 200 );


	}
}
