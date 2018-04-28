<?php

namespace Responsive\Http\Controllers\Admin;



use File;
use Image;
use Responsive\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Responsive\Http\Requests;
use Illuminate\Http\Request;
use Responsive\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;

class TestimonialsController extends Controller
{
    /**
     * Show a list of all of the application's users.
     *
     * @return Response
     */
    public function index()
    {
        $testimonials = DB::table('testimonials')
		                ->orderBy('id','desc')
					   ->get();

        return view('admin.testimonials', ['testimonials' => $testimonials]);
    }
	
	
	public function destroy($id) {
		
		$image = DB::table('testimonials')->where('id', $id)->first();
		$orginalfile=$image->image;
		$testimonialphoto="/testimonialphoto/";
       $path = base_path('images'.$testimonialphoto.$orginalfile);
	  File::delete($path);
      DB::delete('delete from testimonials where id = ?',[$id]);
	   
      return back();
      
   }
	
}