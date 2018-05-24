<?php

namespace Responsive\Http\Controllers;

use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use File;
use Image;
use Responsive\User;
use Responsive\Address;
use Responsive\Notifications\Auth\UserVerification as UserVerificationNotification;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userid = Auth::user()->id;
		$editprofile = DB::select('select * from users where id = ?',[$userid]);
		$data = array('editprofile' => $editprofile);
		return view('dashboard')->with($data);
    }


	public function sangvish_logout()
	{
		Auth::logout();
       return back();
	}

public function sangvish_delaccount()
    {
        $userid = Auth::user()->id;
        $editprofile = DB::select('select * from users where id = ?',[$userid]);
        return view('delete-account',compact('editprofile'));
    }
	public function sangvish_deleteaccount()
	{
		$userid = Auth::user()->id;


		$userdetails = DB::table('users')
		 ->where('id', '=', $userid)
		 ->get();

	 $uemail = $userdetails[0]->email;


		DB::delete('delete from seller_services where user_id = ?',[$userid]);
	  DB::delete('delete from rating where email = ?',[$uemail]);
	  DB::delete('delete from booking where user_id = ?',[$userid]);

	   DB::delete('delete from shop_gallery where user_id = ?',[$userid]);
	   DB::delete('delete from shop where user_id = ?',[$userid]);


		DB::delete('delete from users where id!=1 and id = ?',[$userid]);
		return back();
	}




	protected function sangvish_edituserdata(Request $request)
    {

        $id = $request->id;
        if($request->profile_page=='yes')
        {

            $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email'
            ]);

            $rules = array(
            'email'=>'required|email|unique:users,email,'.$id,
            'name' => 'required|regex:/^[\w-]*$/|max:255|unique:users,name,'.$id);
            $messages = array(
            'email' => 'The :attribute field is already exists',
            'name' => 'The :attribute field must only be letters and numbers (no spaces)'
            );

            $data = $request->all();
            $validator = Validator::make(Input::all(), $rules, $messages);
            if ($validator->fails()) 
            {
                $failedRules = $validator->failed();
                return back()->withErrors($validator);
            }
            else
            {
                $name=$data['name'];
                $email=$data['email'];
                $password=bcrypt($data['password']);
                $phone=$data['phone'];
                $firstname = $data['firstname'];
                $lastname = $data['lastname'];

                if($data['password'] != "") 
                {
                    $passtxt=$password;
                } else 
                {
                    $passtxt=$data['savepassword'];
                }

                $admin=$data['usertype'];
                        //Address save                
                $address = Address::where('user_id', Auth::user()->id)->first();
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


                $user = User::find(Auth::user()->id);
                $user->name = $name;
                $user->password = $passtxt;
                $user->phone = $phone;
                $user->admin = $admin;
                $user->firstname = $firstname;
                $user->lastname = $lastname;
                if($data['dob'])
                    $user->dob = $data['dob'];
                                                    
                // don't save email directly if the user change their email
                // we will save it to verify_users table with new_email column
                // user needs to confirm the verification email
                // to change their email
                if($user->email != $email) {
                    $user->setAsUnverified();

                    $token = $user->generateToken();

                    $user->changeEmail($email);

                    $user->notify(new UserVerificationNotification($token, $email));
                }

                $user->save();
                return back()->with('success', 'Account has been updated');
            }

        }
		

        if($request->verification_page=='yes')
        {
            $data = $request->all();

            $rules = array( 'photo' => 'max:1024|mimes:pdf,jpg,jpeg,png',
                        'sia_doc'=> 'max:1024|mimes:pdf,jpg,jpeg,png',
                        'passphoto'=> 'max:1024|mimes:pdf,jpg,jpeg,png',
                        'address_proof'=> 'max:1024|mimes:pdf,jpg,jpeg,png',
                        'visa_page'=> 'max:1024|mimes:pdf,jpg,jpeg,png',
                        'pass_page'=> 'max:1024|mimes:pdf,jpg,jpeg,png'
            );

            $validator = Validator::make(Input::all(), $rules);

            if($validator->fails()) 
            {
                $failedRules = $validator->failed();

                return back()->withErrors($validator);
            } else 
            {   

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

                $currentpassphoto=$data['currentpassphoto'];
                $passphoto = Input::file('passphoto');
                $userdoc="/userdoc/";

                if($passphoto!="")
                {                    
                    $delpath = base_path('images'.$userdoc.$currentpassphoto);
                    File::delete($delpath); 
                    $passphotofilename  = time() . '.' . $passphoto->getClientOriginalExtension();
                    $path = base_path('images'.$userdoc.$passphotofilename);
                    Image::make($passphoto->getRealPath())->save($path);
                    $passphotoname=$passphotofilename;
                } 
                else 
                {
                    $passphotoname=$currentpassphoto;
                }

                $currentsiadoc=$data['currentsiadoc'];
                $siadoc = Input::file('sia_doc');
                if($siadoc!="")
                {                    
                    $delpath = base_path('images'.$userdoc.$currentsiadoc);
                    File::delete($delpath); 
                    $siadocfilename  = time() . '.' . $siadoc->getClientOriginalExtension();
                    $path = base_path('images'.$userdoc.$siadocfilename);
                    Image::make($siadoc->getRealPath())->save($path);
                    $siadocname=$siadocfilename;
                } else {
                    $siadocname=$currentsiadoc;
                }           
                $currentaddressproof=$data['currentaddressproof'];
                $addproof = Input::file('address_proof');
                if($addproof!="")
                {                    
                    $delpath = base_path('images'.$userdoc.$currentaddressproof);
                    File::delete($delpath); 
                    $addprooffilename  = time() . '.' . $addproof->getClientOriginalExtension();
                    $path = base_path('images'.$userdoc.$addprooffilename);
                    Image::make($addproof->getRealPath())->save($path);
                    $addrproofname=$addprooffilename;
                } 
                else 
                {
                    $addrproofname=$currentaddressproof;
                }

                $currentvisapage=$data['currentvisapage'];
                $visapage = Input::file('visa_page');
                if($visapage!="")
                {                    
                    $delpath = base_path('images'.$userdoc.$currentvisapage);
                    File::delete($delpath); 
                    $visapagefilename  = time() . '.' . $visapage->getClientOriginalExtension();
                    $path = base_path('images'.$userdoc.$visapagefilename);
                    Image::make($visapage->getRealPath())->save($path);
                    $visapagename=$visapagefilename;
                }
                else 
                {
                    $visapagename=$currentvisapage;
                }           
                $currentpasspage=$data['currentvisapage'];
                $passpage = Input::file('pass_page');
                if($visapage!="")
                {                    
                    $delpath = base_path('images'.$userdoc.$currentpasspage);
                    File::delete($delpath); 
                    $passpagefilename  = time() . '.' . $passpage->getClientOriginalExtension();
                    $path = base_path('images'.$userdoc.$passpagefilename);
                    Image::make($passpage->getRealPath())->save($path);
                    $passpagename=$passpagefilename;
                }
                else 
                {
                    $passpagename=$currentpasspage;
                }

                $user = User::find(Auth::user()->id);
                $user->photo = $savefname;
                $user->visa_page = $visapagename;
                $user->pass_page = $passpagename;
                $user->sia_doc = $siadocname;
                $user->passphoto = $passphotoname;
                $user->sia_expirydate = $data['sia_expirydate'];
                $user->sia_licence = $data['sia_licence'];
                $user->work_category = $data['category'];
                $user->visa_no = $data['visa_no'];
                $user->niutr_no = $data['niutr_no'];
                $user->nation_id = $data['nationality'];
                $user->address_proof = $addrproofname;
                $user->save();
                            
                

                return back()->with('success', 'Account has been updated');
            }
        }
	
	}


}
