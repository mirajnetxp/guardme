<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Responsive\User;
use Responsive\Notifications\Bulk;
use Responsive\Mail\Bulk as MailBulk;
use Twilio;

class BulkController extends Controller
{
 	public function sendNotify(Request $request)
 	{
 		$user_ids = explode(' ', $request->ids);

 		$message = $request->message;

 		$users = User::whereIn('id', $user_ids)->get();

 		foreach($users as $user)
 		{	
 			\Notification::send($user, new Bulk($user, $message));

 			Twilio::message($user->phone, $message);

		}

		return redirect('/admin/users');

 	}   
}
