<?php

namespace Responsive\Http\Controllers\Admin;


use Responsive\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use Responsive\Http\Requests;
use Illuminate\Http\Request;
use Responsive\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use File;
use Image;


class EditsubserviceController extends Controller
{
    
   

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }
    
	
	
	
	
	
	public function edit($id)

    {
		
		
	 $subservices = DB::select('select * from subservices where subid = ?',[$id]);
     

       $services = DB::table('services')->orderBy('name', 'asc')->get();

        
		
		$data = array('subservices'=>$subservices, 'services'=>$services);
            return view('admin.editsubservice')->with($data);

    }
	
   
  
   
   
   
	
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255'
            
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
	 
	  
	 
    protected function editsubservicedata(Request $request)
    {
        
		
		
		 $this->validate($request, [

        		'name' => 'required'

        		
				
				

        	]);
         
		 $data = $request->all();
			
         $subid=$data['subid'];
        			
		$input['name'] = Input::get('name');
       
		
		
		
		
		 
		
		/*$rules = array('name' => 'unique:subservices,subname,'.$data['subid'].',subid'); */
		
		
		$rules = array(
		
		'name'=>'required|unique:subservices,subname,'.$data['subid'].',subid',
		
		'photo' => 'max:1024|mimes:jpg,jpeg,png'
		);
		
		

		$messages = array(
            
            
			
        );

		
		
		$validator = Validator::make(Input::all(), $rules, $messages);
		

		if ($validator->fails())
		{
			$failedRules = $validator->failed();
			return back()->withErrors($validator);
		}
		else
		{ 
		  

			/*User::create([
            'name' => $data['name'],
            'email' => $data['email'],
			'admin' => '0',
            'password' => bcrypt($data['password']),
			'phone' => $data['phone']
			
        ]);*/
		$name=$data['name'];
		
		
		$currentphoto=$data['currentphoto'];
		
		
		$image = Input::file('photo');
        if($image!="")
		{	
            $subservicephoto="/subservicephoto/";
			$delpath = base_path('images'.$subservicephoto.$currentphoto);
			File::delete($delpath);	
			$filename  = time() . '.' . $image->getClientOriginalExtension();
            
            $path = base_path('images'.$subservicephoto.$filename);
			$destinationPath=base_path('images'.$subservicephoto);
      
                /* Image::make($image->getRealPath())->resize(200, 200)->save($path);*/
				Input::file('photo')->move($destinationPath, $filename);
				$savefname=$filename;
		}
        else
		{
			$savefname=$currentphoto;
		}			
		
		
		$service=$data['service'];
		
		
		
		/* DB::insert('insert into users (name, email,password,phone,admin) values (?, ?,?, ?,?)', [$name,$email,$password,$phone,$admin]);*/
		DB::update('update subservices set subname="'.$name.'", service="'.$service.'",subimage="'.$savefname.'" where subid = ?', [$subid]);
		
			return back()->with('success', 'Sub service has been updated');
        }
		
		
		
		
    }
}
