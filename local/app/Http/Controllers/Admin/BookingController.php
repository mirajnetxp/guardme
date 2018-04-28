<?php

namespace Responsive\Http\Controllers\Admin;



use File;
use Image;
use Responsive\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Auth;
use Mail;

use Responsive\Http\Requests;
use Illuminate\Http\Request;
use Responsive\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Show a list of all of the application's users.
     *
     * @return Response
     */
    public function index()
    {
		
		$set_id=1;
		$setting = DB::table('settings')->where('id', $set_id)->get();
		
		
        $booking = DB::table('booking')
		           ->leftJoin('users', 'users.email', '=', 'booking.user_email')
				   ->leftJoin('shop', 'shop.id', '=', 'booking.shop_id')
				   ->orderBy('booking.book_id','desc')
				 ->get();
		
		$data=array('booking' => $booking, 'setting' => $setting);

        return view('admin.booking')->with($data);
    }
	
	
	
	
	
	
	public function destroy($id) {
		
		
	  
      DB::delete('delete from booking where book_id = ?',[$id]);
	   
      return back();
      
   }
   
   
   
   
   
   
   
	
}