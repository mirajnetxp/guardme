<?php

namespace Responsive\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Responsive\Http\Controllers\Controller;
use DB;

class MessageController extends Controller
{
    
    // listing of user
    public function index()
    {
    	$users = DB::table( 'users' )
		           ->leftJoin( 'address', 'users.id', '=', 'address.user_id' )
		           ->orderBy( 'users.id', 'desc' )
					->select(
						'users.id',
						'users.name',
						'users.email',
						'users.verified',
						'users.admin',
						'users.gender',
						'users.phone',
						'users.photo',
						'users.created_at',
						'users.firstname',
						'users.lastname',
						'users.dob',
						'users.phone_verified',
						'address.postcode',
						'address.houseno',
						'address.line1',
						'address.line2',
						'address.line3',
						'address.line4',
						'address.locality',
						'address.citytown',
						'address.country'
					)
                   ->get();
        return view('admin.message',compact('users'));           
    }

    public function sendMessage(Request $request)
    {
       dd($request->all());
    }
}
