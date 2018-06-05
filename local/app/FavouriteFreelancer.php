<?php

namespace Responsive;

use Illuminate\Database\Eloquent\Model;
use DB;
class FavouriteFreelancer extends Model
{
    //
    protected $table = 'favourite_freelancers';
    public $timestamps = false;

    public function getFavourieFreelacers() {
        $user_id = auth()->user()->id;
       $fav = DB::table($this->table .' as ff')
           ->select('u.*')
           ->join('users as u', 'u.id', '=', 'ff.freelancer_id')
           ->where('employer_id', $user_id)->get();
        return $fav;
    }
}
