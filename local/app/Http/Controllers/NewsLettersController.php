<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Mail;
use Auth;
use Crypt;
use Responsive\Transaction;
use Responsive\User;
use Responsive\NewsLetters;
use URL;

class NewsLettersController extends Controller
{
 
 
    
	public function post_newsletters_subscription(Request $request) {
		
		$e = @NewsLetters::where('email',$request->email)->count();
		
		if($e > 0)
		{
			
			return back()->with('error','You are already subscribed to NewsLetters');
		}
		 
		
		$id = @User::where('email',$request->email)->first(['id'])->id;
		 
		if($id == '' || $id == null)
		{
		 $flight = new \Responsive\NewsLetters;
         $flight->email = $request->email;
		 $flight->user_id = '';
         $flight->save();
		}
		else
			{
		 $flight = new \Responsive\NewsLetters;
         $flight->email = $request->email;
		 $flight->user_id = $id;
         $flight->save();
		}
		
	   return back()->with('success','You are Successfully subscribed to NewsLetters');
	}

 
}
