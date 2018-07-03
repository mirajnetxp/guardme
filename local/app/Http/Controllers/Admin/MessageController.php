<?php

namespace Responsive\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
use Responsive\Http\Controllers\Controller;
use Illuminate\Mail\Mailable;
use Responsive\Mail\AdminMessage;
use DB;
use Mail;

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

    public function sendMessage(Request $request,Mailer $mailer)
    {
       $message = $request->message;
       $users_id = json_decode($request->user);

       $emails = $users = DB::table('users')->get();

       foreach ($users as $user) {

       	   $mailer
       	   		->to($user->email)
       	   		->send(new AdminMessage($user->name,$message));
		    
       }
	   
       return response()->json(array('status' => 1,'msg' => 'Message has been successfully send.'));
    }
}


