<?php

namespace Responsive\Http\Controllers\Admin;


use Responsive\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Responsive\Country;
use Responsive\Address;
use Responsive\Http\Requests;
use Illuminate\Http\Request;
use Responsive\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use File;
use Image;


class EdituserController extends Controller
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
    
	
	// public function showform($id) {
    //     $users = DB::select('select * from users where id = ?',[$id]);
    //     $userid = $id;
    //     return view('admin.edituser',['users'=>$users, 'userid' => $userid]);
    //  }

     public function showform($id) {
    	$userid = $id;
		$editprofile = DB::select('select * from users where id = ?',[$id]);
		$data = array('editprofile' => $editprofile);

        $countries = Country::all();
        $address = Address::where('user_id', $id)->get();

        $data = array('rating_count' => 0);
        return view('admin.edituser', compact( 'userid', 'editprofile', 'countries','address'))->with($data);

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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users'
            
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
	 
	  protected $fillable = ['name', 'email','password','phone'];
	 
    protected function edituserdata(Request $request)
    {
        /*return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);*/
		
		
		
		 $this->validate($request, [

        		'name' => 'required',

        		'email' => 'required|email'

        		
				
				

        	]);
         
		 $data = $request->all();
			
         $id=$data['id'];
        			
		$input['email'] = Input::get('email');
       
		
		$input['name'] = Input::get('name');
		
		
		$rules = array(
        
       
		
        'email'=>'required|email|unique:users,email,'.$id,
		'name' => 'required|regex:/^[\w-]*$/|max:255|unique:users,name,'.$id,
		'photo' => 'max:1024|mimes:jpg,jpeg,png'
		
        );
		
		
		$messages = array(
            
            'email' => 'The :attribute field is already exists',
            'name' => 'The :attribute field must only be letters and numbers (no spaces)'
			
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
		$email=$data['email'];
		$firstname = $data['firstname'];
		$lastname = $data['lastname'];
		$dob = $data['dob'];
		$phone = $data['phone'];
        $gender = $data['gender'];

		
		
		$phone=$data['phone'];
		
		
		$currentphoto=$data['currentphoto'];
		
		
		$image = Input::file('photo');
        if($image!="")
		{	
            $userphoto="/userphoto/";
			$delpath = base_path('images'.$userphoto.$currentphoto);
			File::delete($delpath);	
			$filename  = time() . '.' . $image->getClientOriginalExtension();
            
            $path = base_path('images'.$userphoto.$filename);
      
                Image::make($image->getRealPath())->resize(200, 200)->save($path);
				$savefname=$filename;
		}
        else
		{
			$savefname=$currentphoto;
		}			
		
		
		if(!empty($data['password']))
		{
			$password=bcrypt($data['password']);
			$passtxt=$password;
		}
		else
		{
			$passtxt=$data['savepassword'];
		}
		
		$admin=$data['usertype'];
		
		
        //Address save                
        $address = Address::where('user_id', $id)->first();
        $postcode = isset($data['postcode'])?$data['postcode']:'';
        $houseno = isset($data['houseno'])?$data['houseno']:'';
        $line1 = isset($data['line1'])?$data['line1']:'';
        $line2 = isset($data['line2'])?$data['line2']:'';
        $line3 = isset($data['line3'])?$data['line3']:'';
        $line4 = isset($data['line4'])?$data['line4']:'';
        $locality = isset($data['locality'])?$data['locality']:'';
        $citytown = isset($data['town'])?$data['town']:'';
        $country = isset($data['country'])?$data['country']:'';
        $latitude = isset($data['addresslat'])?$data['addresslat']:'';
        $longitude = isset($data['addresslong'])?$data['addresslong']:'';
        if(!isset($address)){
            $address = new Address();
            $address->user_id = $id;
        }
        $address->postcode = $postcode;
        $address->houseno = $houseno;
        $address->line1 = $line1;
        $address->line2 = $line2;
        $address->line3 = $line3;
        $address->line4 = $line4;
        $address->locality = $locality;
        $address->longitude = $longitude;
        $address->latitude = $latitude;
        $address->citytown = $citytown;
        $address->country = $country;
        $address->save();		
		
		
		DB::update('update users set name="'.$name.'",email="'.$email.'",firstname="'.$firstname.'",lastname="'.$lastname.'",dob="'.$dob.'",gender="'.$gender.'",password="'.$passtxt.'",phone="'.$phone.'",photo="'.$savefname.'",admin="'.$admin.'" where id = ?', [$id]);
		
		
		
			
			DB::update('update shop set seller_email="'.$email.'" where user_id = ?', [$id]);
		
		
		
			return back()->with('success', 'Account has been updated');
        }
		
		
		
		
    }
}
