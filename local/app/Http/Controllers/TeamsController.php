<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Responsive\Team;
use Responsive\User;
use Responsive\Transaction;

class TeamsController extends Controller
{
    //
    public function myTeams() {

        $wallet      = new Transaction();
        $wallet_data['available_balance'] = $wallet->getWalletAvailableBalance();

        $user_id = auth()->user()->id;
        $editprofile = User::where('id', $user_id)->get();
        $team = new Team();
        $teams = $team->getMyTeams();
        return view('teams.teams', ['teams' => $teams, 'editprofile' => $editprofile, 'wallet_data' => $wallet_data]);
    }

    public function createTeam() {
        $user_id = auth()->user()->id;
        $editprofile = User::where('id', $user_id)->get();
        return view('teams.create', ['editprofile' => $editprofile]);
    }
    public function showTeam($team_id) {
        $user_id = auth()->user()->id;
        $editprofile = User::where('id', $user_id)->get();
        $tm = new Team();
        $team = $tm->getTeamWithFreelancers($team_id);
        return view('teams.detail', ['editprofile' => $editprofile, 'team' => $team]);
    }
}
