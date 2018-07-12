<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Responsive\IncidentReport;
use Responsive\Job;
use Responsive\JobApplication;
use DB;

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


		if ( $user->admin != 2 || count( $JA ) == 0 ) {
			return response()->json( [ 'You are not authorized to perform this task' ], 403 );
		}


		$incident                  = new IncidentReport();
		$incident->user_id         = $user->id;
		$incident->job_id          = $request->job_id;
		$incident->incident_report = $request->incident_report;
		$incident->save();

		return response()->json( "Report added successfully", 200 );


	}

	public function getIncident( $job_id ) {
		$user = auth()->user();
		$job  = Job::find( $job_id );

		if ( $user->id !== $job->created_by ) {
			return response()->json( "Unauthorized", 403 );
		}

		$incidents = DB::table( 'incident_reports' )
		               ->select( 'incident_reports.job_id',
			               'incident_reports.incident_report',
			               'incident_reports.created_at',
			               'users.name',
			               'users.email'
		               )
		               ->where( 'job_id', $job_id )
		               ->join( 'users', 'incident_reports.user_id', '=', 'users.id' )
		               ->get();

		return response()->json( $incidents );
	}
}
