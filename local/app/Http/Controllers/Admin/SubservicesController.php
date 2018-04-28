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

class SubservicesController extends Controller
{
    /**
     * Show a list of all of the application's users.
     *
     * @return Response
     */
    public function index()
    {
        $subservices = DB::table('subservices')
		->leftJoin('services', 'services.id', '=', 'subservices.service')
		 ->orderBy('subservices.subid','desc')
		->get();

        return view('admin.subservices', compact('subservices','services'));
    }
	
	public function getservice()
	{
		 /* $getservice = DB::table('services')->where('id', '?')->first();
		 return view('admin.subservices',$getservice);*/
	}
	
	
	public function destroy($id) {
		
		$image = DB::table('subservices')->where('subid', $id)->first();
		$orginalfile=$image->subimage;
		$userphoto="/subservicephoto/";
       $path = base_path('images'.$userphoto.$orginalfile);
	  File::delete($path);
      DB::delete('delete from subservices where subid = ?',[$id]);
	   
      return back();
      
   }
	
}