<?php

namespace Responsive\Http\Controllers\Api\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Responsive\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Responsive\User;
use Responsive\Address;
use Responsive\Country;
use Illuminate\Support\Facades\Input;
use Responsive\Notifications\Auth\UserVerification as UserVerificationNotification;

class AuthController extends Controller
{
    use AuthenticatesUsers;


    public function apiRegister(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->apiLoginSuccessResponse();
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
            'name' => 'required|regex:/^[\w-]*$/|unique:users|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'gender' => 'required|string|max:255',
            'usertype' => 'required|string|max:255',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],

            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'gender' => $data['gender'],
            'phone' => $data['phoneno'],
            'photo' => '',
            'admin' => $data['usertype'],
        ]);
    }

    public function apiSocialLogin(Request $request)
    {
        /** @var User $user */
        $user = $this->getUserByEmail($request->get('email'));

        if($user->id){
            // existing user
        } else {
            // new user
            $user = $this->createNewUserFromEmail($request->get('email'));
        }

        \auth()->login($user);

        return $this->apiLoginSuccessResponse();
    }

    public function apiLogin(Request $request)
    {
        $credentials = $this->credentials($request);

        if(Auth::attempt($credentials)){
            return $this->apiLoginSuccessResponse();
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    protected function apiLoginSuccessResponse(){
        /** @var User $user */
        $user = Auth::user();
        $success['token'] =  $user->createToken(config('app.name'))->accessToken;
        return response()->json([
            'success' => $success
        ]);
    }

    public function getAuthUserDetails()
    {
        $user = Auth::user();
        return response()->json(['authUser' => $user]);
    }

    public function profile()
    {
        $user = Auth::user();
        return response()->json([
            'username' => $user->name,
            'first_name' => $user->firstname,
            'last_name' => $user->lastname,
            'email' => $user->email,
            'address_line1' => $user->address ? $user->address->line1 : null,
            'address_line2' => $user->address ? $user->address->line2 : null,
            'address_line3' => $user->address ? $user->address->line3 : null,
            'address_country' => $user->address ? $user->address->country : null,
            'address_postcode' => $user->address ? $user->address->postcode : null,
            'address_town' => $user->address ? $user->address->citytown : null,
            'phone' => $user->phone,
            'gender' => $user->gender,
            'dob' => $user->dob,
            'avatar' => $user->photo ? url("/local/images/userphoto/".$user->photo) : null,
            'nationality' => $user->nationality ? $user->nationality->name : null,
            'visa_number' => $user->visa_no,
            'sia_number' => $user->sia_license,
            'sia_expiry_date' => $user->sia_expirydate,
            'work_category' => User::getWorkCategories()[$user->work_category],
            'passport_photo' => $user->passphoto ? url("/local/images/userdoc/".$user->passphoto) : null,
            'passport_page' => $user->pass_page ? url("/local/images/userdoc/".$user->pass_page) : null,
            'visa_page' => $user->visa_page ? url("/local/images/userdoc/".$user->visa_page) : null,
            'sia_license' => $user->sia_doc ? url("/local/images/userdoc/".$user->sia_doc) : null,
            'address_proof' => $user->address_proof ? url("/local/images/userdoc/".$user->address_proof) : null,
        ]);
    }

    public function updateProfile(Request $request) {

		$data = $request->all();
        $id = Auth::user()->id;

		$rules = array(
			'email'=>'required|email|unique:users,email,'.$id,
			'name' => 'required|regex:/^[\w-]*$/|max:255|unique:users,name,'.$id,
        );

		$messages = array(
            'email.unique' => 'The :attribute field is already exists',
            'name.regex' => 'The :attribute field must only be letters and numbers (no spaces)'
		);

		$validator = Validator::make(Input::all(), $rules, $messages);

		if ($validator->fails()) {

			return response()->json(['errors' => $validator->errors()], 422);
		} else {
			$name=$data['name'];
			$email=$data['email'];
			$password=!empty($data['password'])?bcrypt($data['password']):'';
			$phone=isset($data['phone']) ? $data['phone'] : '';
			$currentphoto= Auth::user()->photo;
            $firstname = isset($data['firstname']) ? $data['firstname'] : '';
            $lastname = isset($data['lastname']) ? $data['lastname'] :'';

			$image = Input::get('photo');
			if (!empty($image)) {
				$userphoto="/userphoto/";
				$delpath = base_path('images'.$userphoto.$currentphoto);
				\File::delete($delpath);

                $pos  = strpos($image, ';');
                $type = explode(':', substr($image, 0, $pos))[1];
                $ext = explode('/', $type)[1];

                $filename  = time() . '.' . $ext;
                $path = base_path('images'.$userphoto.$filename);
                \Image::make(file_get_contents($image))->resize(200, 200)->save($path);
				$savefname=$filename;
			} else {
				$savefname=$currentphoto;
			}

            $currentpassphoto=Auth::user()->passphoto;
            $passphoto = Input::get('passphoto');
            $userdoc="/userdoc/";
            if(!empty($passphoto))
            {                    
                $delpath = base_path('images'.$userdoc.$currentpassphoto);
                \File::delete($delpath);	

                $pos  = strpos($passphoto, ';');
                $type = explode(':', substr($passphoto, 0, $pos))[1];
                $ext = explode('/', $type)[1];

                $passphotofilename  = time() . '.' . $ext;
                $path = base_path('images'.$userdoc.$passphotofilename);
                \Image::make(file_get_contents($passphoto))->save($path);
                $passphotoname=$passphotofilename;
            } else {
                $passphotoname=$currentpassphoto;
            }			
            $currentsiadoc=Auth::user()->sia_doc;
            $siadoc = Input::get('sia_doc');
            if(!empty($siadoc))
            {                    
                $delpath = base_path('images'.$userdoc.$currentsiadoc);
                \File::delete($delpath);	

                $pos  = strpos($siadoc, ';');
                $type = explode(':', substr($siadoc, 0, $pos))[1];
                $ext = explode('/', $type)[1];

                $siadocfilename  = time() . '.' . $ext;
                $path = base_path('images'.$userdoc.$siadocfilename);
                \Image::make(file_get_contents($siadoc))->save($path);
                $siadocname=$siadocfilename;
            } else {
                $siadocname=$currentsiadoc;
            }			
            $currentaddressproof=Auth::user()->address_proof;
            $addproof = Input::get('address_proof');
            if(!empty($addproof))
            {                    
                $delpath = base_path('images'.$userdoc.$currentaddressproof);
                \File::delete($delpath);	

                $pos  = strpos($addproof, ';');
                $type = explode(':', substr($addproof, 0, $pos))[1];
                $ext = explode('/', $type)[1];

                $addprooffilename  = time() . '.' . $ext;
                $path = base_path('images'.$userdoc.$addprooffilename);
                \Image::make(file_get_contents($addproof))->save($path);
                $addrproofname=$addprooffilename;
            } else {
                $addrproofname=$currentaddressproof;
            }			

            $currentvisapage=Auth::user()->visa_page;
            $visapage = Input::get('visa_page');
            if(!empty($visapage))
            {                    
                $delpath = base_path('images'.$userdoc.$currentvisapage);
                \File::delete($delpath);

                $pos  = strpos($visapage, ';');
                $type = explode(':', substr($visapage, 0, $pos))[1];
                $ext = explode('/', $type)[1];

                $visapagefilename  = time() . '.' . $ext;
                $path = base_path('images'.$userdoc.$visapagefilename);
                \Image::make(file_get_contents($visapage))->save($path);
                $visapagename=$visapagefilename;
            } else {
                $visapagename=$currentvisapage;
            }			
            $currentpasspage=Auth::user()->pass_page;
            $passpage = Input::get('pass_page');
            if(!empty($passpage))
            {                    
                $delpath = base_path('images'.$userdoc.$currentpasspage);
                \File::delete($delpath);	
                $pos  = strpos($passpage, ';');
                $type = explode(':', substr($passpage, 0, $pos))[1];
                $ext = explode('/', $type)[1];

                $passpagefilename  = time() . '.' . $ext;
                $path = base_path('images'.$userdoc.$passpagefilename);
                \Image::make(file_get_contents($passpage))->save($path);
                $passpagename=$passpagefilename;
            } else {
                $passpagename=$currentpasspage;
            }			
                        
			if(!empty($data['password'])) {
				$passtxt=$password;
			} else {
				$passtxt=Auth::user()->password;
			}

            //Address save                
            $address = Address::where('user_id', Auth::user()->id)->first();
            $postcode = isset($data['postcode'])?$data['postcode']:'';
            $houseno = isset($data['houseno'])?$data['houseno']:'';
            $line1 = isset($data['line1'])?$data['line1']:'';
            $line2 = isset($data['line2'])?$data['line2']:'';
            $line3 = isset($data['line3'])?$data['line3']:'';
            $locality = isset($data['locality'])?$data['locality']:'';
            $citytown = isset($data['town'])?$data['town']:'';
            $country = isset($data['country'])?$data['country']:'';
            $latitude = isset($data['latitude'])?$data['latitude']:'';
            $longitude = isset($data['longitude'])?$data['longitude']:'';
            if(!isset($address)){
                $address = new Address();
                $address->user_id = $id;
            }
            $address->postcode = $postcode;
            $address->houseno = $houseno;
            $address->line1 = $line1;
            $address->line2 = $line2;
            $address->line3 = $line3;
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
			$user->photo = $savefname;
            $user->visa_page = $visapagename;
            $user->pass_page = $passpagename;
            $user->address_proof = $addrproofname;
            $user->sia_doc = $siadocname;
            $user->passphoto = $passphotoname;
            isset($data['sia_expirydate']) ? $user->sia_expirydate = $data['sia_expirydate'] : '';
            isset($data['sia_licence']) ? $user->sia_licence = $data['sia_licence'] : '';
            isset($data['work_category']) ? $user->work_category = $data['work_category'] : 0;
            isset($data['visa_no']) ? $user->visa_no = $data['visa_no'] : '';
            isset($data['niutr_no']) ? $user->niutr_no = $data['niutr_no'] : '';
            $country = isset($data['nationality']) ? Country::where('name', $data['nationality'])->first() : null;
            $country ? $user->nation_id = $country->id : 0;
            $user->firstname = $firstname;
            $user->lastname = $lastname;
            isset($data['dob']) ? $user->dob = $data['dob'] : '';
            isset($data['gender']) ? $user->gender = $data['gender'] : '';
                                    
			// don't save email directly if the user change their email
			// we will save it to verify_users table with new_email column
			// user needs to confirm the verification email
			// to change their email
			if ($user->email != $email) {
				$user->setAsUnverified();

				$token = $user->generateToken();

				$user->changeEmail($email);

				$user->notify(new UserVerificationNotification($token, $email));
			}

			$user->save();

			return response()->json(['success' => true]);
        }

    }

    protected function credentials(Request $request)
    {
        $usernameInput = trim($request->{$this->username()});
        $usernameColumn = filter_var($usernameInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        return [$usernameColumn => $usernameInput, 'password' => $request->password];

    }

    private function getUserByEmail($emailAddress)
    {
        return User::firstOrNew([
            'email' => $emailAddress
        ]);
    }

    private function createNewUserFromEmail($emailAddress)
    {
        return User::create([
            'email' => $emailAddress
        ]);
    }
}
