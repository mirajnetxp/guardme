<?php

namespace Responsive\Http\Controllers\Api;

use Illuminate\Http\Request;
use Responsive\Http\Controllers\Controller;
use Responsive\Team;
use Responsive\TeamMember;

class TeamsController extends Controller {
	//
	public function create( Request $request ) {
		$posted_data = $request->all();
		$this->validate( $request, [
			'name' => 'required'
		] );
		$team              = new Team();
		$team->name        = $posted_data['name'];
		$team->description = $posted_data['description'];
		$team->created_by  = auth()->user()->id;
		$team->created_at  = date( 'Y-m-d H:i:s' );
		$team->save();
		$return_data = [ 'Team Created Successfully' ];

		return response()
			->json( $return_data, 200 );
	}

	/**
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function addMember( Request $request ) {
		$posted_data = $request->all();
		$this->validate( $request, [
			'freelancer_id' => 'required|integer|min:1',
			'team_id'       => 'required|integer|min:1',
		] );
		$already = TeamMember::where( 'freelancer_id', $posted_data['freelancer_id'] )->where( 'team_id', $posted_data['team_id'] )->get();
		if ( count( $already ) > 0 ) {
			$return_data   = [ 'This member is already part of the team' ];
			$return_status = 500;
		} else {
			$team                = new TeamMember();
			$team->freelancer_id = $posted_data['freelancer_id'];
			$team->team_id       = $posted_data['team_id'];
			$team->save();
			$return_data   = [ 'Member added successfully' ];
			$return_status = 200;
		}

		return response()
			->json( $return_data, $return_status );
	}

	public function getAllteam() {
		$allTeam = new Team();
		$allTeam = $allTeam->getMyTeams();

		return response()->json( $allTeam );
	}

	public function totaoTeam() {
		$allTeam = new Team();
		$allTeam = $allTeam->getMyTeams();
		$total   = count( $allTeam );

		return response()->json( [ 'total_team' => $total ] );
	}
}
