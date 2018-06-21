<?php

namespace Responsive;

use Illuminate\Database\Eloquent\Model;
use DB;
class Team extends Model
{
    //
    protected $table = 'teams';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function freelancers() {
        return $this->belongsToMany(User::class, 'team_members', 'team_id', 'freelancer_id');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getMyTeams() {
        $user_id = auth()->user()->id;
        $teams = Team::where('created_by', $user_id)->with('freelancers')->get();
        return $teams;
    }
    
    public function getTeamWithFreelancers($team_id) {
        $user_id = auth()->user()->id;
        $team = Team::where('id', $team_id)->where('created_by', $user_id)->with('freelancers')->get()->first();
        return $team;
    }
}
