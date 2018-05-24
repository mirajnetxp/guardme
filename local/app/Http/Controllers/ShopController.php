<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Auth;
use File;
use Image;
use Mail;
use Illuminate\Support\Facades\Validator;

use Responsive\Country;
use Responsive\Address;
use Responsive\User;
use Responsive\Businesscategory;
use Responsive\Shop;
class ShopController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

	 public function __construct()
    {
       $this->middleware('auth');
    }



    public function sangvish_viewshop()
    {
    	$userid = Auth::user()->id;
		$editprofile = DB::select('select * from users where id = ?',[$userid]);
		$data = array('editprofile' => $editprofile);

      $time = array("12:00 AM"=>"0", "01:00 AM"=>"1", "02:00 AM"=>"2", "03:00 AM"=>"3", "04:00 AM"=>"4", "05:00 AM"=>"5", "06:00 AM"=>"6", "07:00 AM"=>"7", "08:00 AM"=>"8",
	 "09:00 AM"=>"9", "10:00 AM"=>"10", "11:00 AM"=>"11", "12:00 PM"=>"12", "01:00 PM"=>"13", "02:00 PM"=>"14", "03:00 PM"=>"15", "04:00 PM"=>"16", "05:00 PM"=>"17", "06:00 PM"=>"18",
	 "07:00 PM"=>"19", "08:00 PM"=>"20", "09:00 PM"=>"21", "10:00 PM"=>"22", "11:00 PM"=>"23");

	 $days=array("1 Day" => "1", "2 Days" => "2", "3 Days" => "3", "4 Days" => "4", "5 Days" => "5", "6 Days" => "6", "7 Days" => "7", "8 Days" => "8", "9 Days" => "9",
			"10 Days" => "10", "11 Days" => "11", "12 Days" => "12", "13 Days" => "13", "14 Days" => "14", "15 Days" => "15", "16 Days" => "16", "17 Days" => "17", "18 Days" => "18",
			"19 Days" => "19", "20 Days" => "20", "21 Days" => "21", "22 Days" => "22", "23 Days" => "23", "24 Days" => "24", "25 Days" => "25", "26 Days" => "26", "27 Days" => "27",
			"28 Days" => "28", "29 Days" => "29", "30 Days" => "30");


		$daytxt=array("Sunday" => "0", "Monday" => "1", "Tuesday" => "2", "Wednesday" => "3", "Thursday" => "4", "Friday" => "5", "Saturday" => "6");

        $sellermail = Auth::user()->email;
        $shopcount = DB::table('shop')
		 ->where('seller_email', '=', $sellermail)
		 ->count();

        $uberid=Auth::user()->id;

        $viewservice = DB::table('seller_services')
        ->where('user_id', $uberid)
        ->orderBy('id','desc')
        ->leftJoin('subservices', 'subservices.subid', '=', 'seller_services.subservice_id')
        ->get();

        $set_id=1;
        $setting = DB::table('settings')->where('id', $set_id)->get();

        $countries = Country::all();
        $address = Address::where('user_id', Auth::user()->id)->get();

        $shop = DB::table('shop')->where('seller_email', '=', $sellermail)->get();
                if($editprofile[0]->admin == 0){ // employer
                        if ($shop->isEmpty()) {
                                return redirect('/addcompany');
                        }
                        if($shop[0]->start_time > 12)
                        {
                                $start=$shop[0]->start_time - 12;
                                $stime=$start."PM";
                        }
                        else
                        {
                                $stime=$shop[0]->start_time."AM";
                        }
                        if($shop[0]->end_time>12)
                        {
                                $end=$shop[0]->end_time-12;
                                $etime=$end."PM";
                        }
                        else
                        {
                                $etime=$shop[0]->end_time."AM";
                        }
                        $sel=explode(",",$shop[0]->shop_date);
                        $lev=count($sel);
                        $shop_id = $shop[0]->id;
                        $rating_count = DB::table('rating')->where('rshop_id', '=', $shop_id)->count();
                        $rating = DB::table('rating')->leftJoin('users', 'users.email', '=', 'rating.email')
                        ->where('rshop_id', '=', $shop_id)->orderBy('rid', 'desc')->get();
                        $data = array('time' => $time, 'days' =>  $days, 'daytxt' => $daytxt, 'shopcount' => $shopcount, 'shop' => $shop, 'stime' => $stime,
                            'etime' => $etime, 'lev' => $lev, 'sel' => $sel, 'viewservice' => $viewservice, 'setting' => $setting, 'rating_count' => $rating_count, 'rating' => $rating);
                        return view('shop', compact('data', 'userid', 'editprofile', 'countries','address'))->with($data);
                    }
                    else{
                        $data = array('rating_count' => 0);
                        return view('shop', compact( 'userid', 'editprofile', 'countries','address'))->with($data);
                    }
    }

    public function sangvish_verification()
    {
    	$userid = Auth::user()->id;
		$editprofile = DB::select('select * from users where id = ?',[$userid]);
		$data = array('editprofile' => $editprofile);

        $sellermail = Auth::user()->email;
       

        $uberid=Auth::user()->id;


        $set_id=1;
        $setting = DB::table('settings')->where('id', $set_id)->get();

        $countries = Country::all();
        $address = Address::where('user_id', Auth::user()->id)->get();

        $data = array('rating_count' => 0);
        return view('verification', compact( 'userid', 'editprofile', 'countries','address'));
    }

public function sangvish_viewshop_old()
    {
    	$userid = Auth::user()->id;
		$editprofile = DB::select('select * from users where id = ?',[$userid]);
		$data = array('editprofile' => $editprofile);

      $time = array("12:00 AM"=>"0", "01:00 AM"=>"1", "02:00 AM"=>"2", "03:00 AM"=>"3", "04:00 AM"=>"4", "05:00 AM"=>"5", "06:00 AM"=>"6", "07:00 AM"=>"7", "08:00 AM"=>"8",
	 "09:00 AM"=>"9", "10:00 AM"=>"10", "11:00 AM"=>"11", "12:00 PM"=>"12", "01:00 PM"=>"13", "02:00 PM"=>"14", "03:00 PM"=>"15", "04:00 PM"=>"16", "05:00 PM"=>"17", "06:00 PM"=>"18",
	 "07:00 PM"=>"19", "08:00 PM"=>"20", "09:00 PM"=>"21", "10:00 PM"=>"22", "11:00 PM"=>"23");

	 $days=array("1 Day" => "1", "2 Days" => "2", "3 Days" => "3", "4 Days" => "4", "5 Days" => "5", "6 Days" => "6", "7 Days" => "7", "8 Days" => "8", "9 Days" => "9",
			"10 Days" => "10", "11 Days" => "11", "12 Days" => "12", "13 Days" => "13", "14 Days" => "14", "15 Days" => "15", "16 Days" => "16", "17 Days" => "17", "18 Days" => "18",
			"19 Days" => "19", "20 Days" => "20", "21 Days" => "21", "22 Days" => "22", "23 Days" => "23", "24 Days" => "24", "25 Days" => "25", "26 Days" => "26", "27 Days" => "27",
			"28 Days" => "28", "29 Days" => "29", "30 Days" => "30");


		$daytxt=array("Sunday" => "0", "Monday" => "1", "Tuesday" => "2", "Wednesday" => "3", "Thursday" => "4", "Friday" => "5", "Saturday" => "6");

        $sellermail = Auth::user()->email;
        $shopcount = DB::table('shop')
		 ->where('seller_email', '=', $sellermail)
		 ->count();

        $uberid=Auth::user()->id;

        $viewservice = DB::table('seller_services')
        ->where('user_id', $uberid)
        ->orderBy('id','desc')
        ->leftJoin('subservices', 'subservices.subid', '=', 'seller_services.subservice_id')
        ->get();

        $set_id=1;
        $setting = DB::table('settings')->where('id', $set_id)->get();

        $countries = Country::all();
        $address = Address::where('user_id', Auth::user()->id)->get();

        $shop = DB::table('shop')->where('seller_email', '=', $sellermail)->get();
                if($editprofile[0]->admin == 0){ // employer
                        if ($shop->isEmpty()) {
                                return redirect('/addcompany');
                        }
                        if($shop[0]->start_time > 12)
                        {
                                $start=$shop[0]->start_time - 12;
                                $stime=$start."PM";
                        }
                        else
                        {
                                $stime=$shop[0]->start_time."AM";
                        }
                        if($shop[0]->end_time>12)
                        {
                                $end=$shop[0]->end_time-12;
                                $etime=$end."PM";
                        }
                        else
                        {
                                $etime=$shop[0]->end_time."AM";
                        }
                        $sel=explode(",",$shop[0]->shop_date);
                        $lev=count($sel);
                        $shop_id = $shop[0]->id;
                        $rating_count = DB::table('rating')->where('rshop_id', '=', $shop_id)->count();
                        $rating = DB::table('rating')->leftJoin('users', 'users.email', '=', 'rating.email')
                        ->where('rshop_id', '=', $shop_id)->orderBy('rid', 'desc')->get();
                        $data = array('time' => $time, 'days' =>  $days, 'daytxt' => $daytxt, 'shopcount' => $shopcount, 'shop' => $shop, 'stime' => $stime,
                            'etime' => $etime, 'lev' => $lev, 'sel' => $sel, 'viewservice' => $viewservice, 'setting' => $setting, 'rating_count' => $rating_count, 'rating' => $rating);
                        return view('shop', compact('data', 'userid', 'editprofile', 'countries','address'))->with($data);
                    }
                    else{
                        $data = array('rating_count' => 0);
                        return view('shop-old', compact( 'userid', 'editprofile', 'countries','address'))->with($data);
                    }
    }




    function editcompany()
    {
        $userid = Auth::user()->id;
        $editprofile = User::where('id',$userid)->with(['address','company','company.bcategory'])->get();

        $b_cats = Businesscategory::all();
        //$data = array('editprofile' => $editprofile);
        //dd($editprofile);
        return view('company', compact( 'b_cats','userid', 'editprofile'));
    }

function updatecompany(Request $request)
{
    //dd($request->all());

    $company = Shop::find($request->company_id);
    $company->shop_name = $request->shop_name;
    $company->business_categoryid = $request->shop_category;
    $company->shop_phone_no = $request->phone;
    $company->company_email = $request->company_email;
    $company->address = $request->address;
    $company->description = $request->description;
    $company->save();

    return back()->with('success', 'Company Info has been updated');
}


 public function sangvish_addshop()
    {







      $time = array("12:00 AM"=>"0", "01:00 AM"=>"1", "02:00 AM"=>"2", "03:00 AM"=>"3", "04:00 AM"=>"4", "05:00 AM"=>"5", "06:00 AM"=>"6", "07:00 AM"=>"7", "08:00 AM"=>"8",
	 "09:00 AM"=>"9", "10:00 AM"=>"10", "11:00 AM"=>"11", "12:00 PM"=>"12", "01:00 PM"=>"13", "02:00 PM"=>"14", "03:00 PM"=>"15", "04:00 PM"=>"16", "05:00 PM"=>"17", "06:00 PM"=>"18",
	 "07:00 PM"=>"19", "08:00 PM"=>"20", "09:00 PM"=>"21", "10:00 PM"=>"22", "11:00 PM"=>"23");

	 $days=array("1 Day" => "1", "2 Days" => "2", "3 Days" => "3", "4 Days" => "4", "5 Days" => "5", "6 Days" => "6", "7 Days" => "7", "8 Days" => "8", "9 Days" => "9",
			"10 Days" => "10", "11 Days" => "11", "12 Days" => "12", "13 Days" => "13", "14 Days" => "14", "15 Days" => "15", "16 Days" => "16", "17 Days" => "17", "18 Days" => "18",
			"19 Days" => "19", "20 Days" => "20", "21 Days" => "21", "22 Days" => "22", "23 Days" => "23", "24 Days" => "24", "25 Days" => "25", "26 Days" => "26", "27 Days" => "27",
			"28 Days" => "28", "29 Days" => "29", "30 Days" => "30");


	$daytxt=array("Sunday" => "0", "Monday" => "1", "Tuesday" => "2", "Wednesday" => "3", "Thursday" => "4", "Friday" => "5", "Saturday" => "6");

	    $sellermail = Auth::user()->email;
    	 $shopcount = DB::table('shop')
		 ->where('seller_email', '=', $sellermail)
		 ->count();


          $shop = DB::table('shop')
                ->where('seller_email', '=', $sellermail)
                ->get();


		$admin_idd=1;

		$admin_email_id = DB::table('users')
                ->where('id', '=', $admin_idd)
                ->get();



		$siteid=1;
		$site_setting=DB::select('select * from settings where id = ?',[$siteid]);




                $address = Address::where('user_id', Auth::user()->id)->get();
                $categories = \Responsive\Businesscategory::all();
		$data = array('time' => $time, 'days' =>  $days, 'daytxt' => $daytxt,'address'=>$address, 'shopcount' => $shopcount, 'shop' => $shop, 'admin_email_id' => $admin_email_id,
		'site_setting' => $site_setting,'categories'=>$categories);
            return view('addshop')->with($data);
    }

































	public function sangvish_editshop(Request $request)
    {





		$testimonials = DB::table('testimonials')->orderBy('id', 'desc')->get();

      $time = array("12:00 AM"=>"0", "01:00 AM"=>"1", "02:00 AM"=>"2", "03:00 AM"=>"3", "04:00 AM"=>"4", "05:00 AM"=>"5", "06:00 AM"=>"6", "07:00 AM"=>"7", "08:00 AM"=>"8",
	 "09:00 AM"=>"9", "10:00 AM"=>"10", "11:00 AM"=>"11", "12:00 PM"=>"12", "01:00 PM"=>"13", "02:00 PM"=>"14", "03:00 PM"=>"15", "04:00 PM"=>"16", "05:00 PM"=>"17", "06:00 PM"=>"18",
	 "07:00 PM"=>"19", "08:00 PM"=>"20", "09:00 PM"=>"21", "10:00 PM"=>"22", "11:00 PM"=>"23");

	 $days=array("1 Day" => "1", "2 Days" => "2", "3 Days" => "3", "4 Days" => "4", "5 Days" => "5", "6 Days" => "6", "7 Days" => "7", "8 Days" => "8", "9 Days" => "9",
			"10 Days" => "10", "11 Days" => "11", "12 Days" => "12", "13 Days" => "13", "14 Days" => "14", "15 Days" => "15", "16 Days" => "16", "17 Days" => "17", "18 Days" => "18",
			"19 Days" => "19", "20 Days" => "20", "21 Days" => "21", "22 Days" => "22", "23 Days" => "23", "24 Days" => "24", "25 Days" => "25", "26 Days" => "26", "27 Days" => "27",
			"28 Days" => "28", "29 Days" => "29", "30 Days" => "30");


	$daytxt=array("Sunday" => "0", "Monday" => "1", "Tuesday" => "2", "Wednesday" => "3", "Thursday" => "4", "Friday" => "5", "Saturday" => "6");

	    $sellermail = Auth::user()->email;
    	 $shopcount = DB::table('shop')
		 ->where('seller_email', '=', $sellermail)
		 ->count();


          $shop = DB::table('shop')
                ->where('seller_email', '=', $sellermail)
                ->get();



		if($shop[0]->start_time > 12)
					{
						$start=$shop[0]->start_time - 12;
						$stime=$start."PM";
					}
					else
					{
						$stime=$shop[0]->start_time."AM";
					}
					if($shop[0]->end_time>12)
					{
						$end=$shop[0]->end_time-12;
						$etime=$end."PM";
					}
					else
					{
						$etime=$shop[0]->end_time."AM";
					}

		$sel=explode(",",$shop[0]->shop_date);
		$lev=count($sel);


		$requestid = $request->id;

		$editshop = DB::select('select * from shop where id = ?',[$requestid]);






		$data = array('time' => $time, 'days' =>  $days, 'daytxt' => $daytxt, 'shopcount' => $shopcount, 'shop' => $shop, 'stime' => $stime,
		'etime' => $etime, 'lev' => $lev, 'sel' => $sel, 'requestid' => $requestid, 'editshop' => $editshop);
            return view('editshop')->with($data);
    }








































	protected function sangvish_savedata(Request $request)
    {



		 $data = $request->all();

		$editid=$data['editid'];





		 $rules = array(
                 'shop_phone_no' => 'unique:shop',
		'shop_cover_photo' => 'max:1024|mimes:jpg,jpeg,png',
		'shop_profile_photo' => 'max:1024|mimes:jpg,jpeg,png'


        );

		$messages = array(
                    'shop_phone_no.unique' => 'The phonenumber is already exists',
            'email' => 'The :attribute field is already exists',
            'name' => 'The :attribute field must only be letters and numbers (no spaces)'

        );


		 $validator = Validator::make(Input::all(), $rules, $messages);




		if ($validator->fails())
		{
			$failedRules = $validator->failed();
			
                        return back()->withInput()->withErrors($validator);
		}
		else
		{

	     $shop_cover_photo = Input::file('shop_cover_photo');
		 if($shop_cover_photo!="")
		 {
			if($editid!="")
			{
			$shophoto="/shop/";
			$delpath = base_path('images'.$shophoto.$data['current_cover']);
			File::delete($delpath);
			}

            $filename  = time() . '.' . $shop_cover_photo->getClientOriginalExtension();
            $shopphoto="/shop/";
            $path = base_path('images'.$shopphoto.$filename);
			$destinationPath=base_path('images'.$shopphoto);


               Image::make($shop_cover_photo->getRealPath())->resize(1400, 300)->save($path);

				$namef=$filename;
		 }
		 else
		 {
			 if($editid!="")
			 {
				 $namef=$data['current_cover'];
			 }
			 else
			 {
			 $namef="";
			 }
		 }


		 $shop_profile_photo = Input::file('shop_profile_photo');
		 if($shop_profile_photo!="")
		 {
			 if($editid!="")
			{
			 $shopro="/shop/";
			$delpaths = base_path('images'.$shopro.$data['current_photo']);
			File::delete($delpaths);
			}

            $profilename  = time() . '.' . $shop_profile_photo->getClientOriginalExtension();
            $shopphoto="/shop/";
            $paths = base_path('images'.$shopphoto.$profilename);



               Image::make($shop_profile_photo->getRealPath())->resize(150, 150)->save($paths);

				$namepro=$profilename;
		 }
		 else
		 {
			 if($editid!="")
			 {
				 $namepro=$data['current_photo'];
			 }
			 else
			 {

			 $namepro="";
			 }
		 }








		$shop_name=$data['shop_name'];
		//$shop_address=$data['shop_address'];

		//$shop_city=$data['shop_city'];
		//$shop_pin_code=$data['shop_pin_code'];


		//$shop_country=$data['shop_country'];
		//$shop_state=$data['shop_state'];
                
		$shop_phone_no=$data['shop_phone_no'];
		$shop_desc=$data['shop_desc'];
		//$shop_working_days=$data['shop_working_days'];

		//$shop_start_time=$data['shop_start_time'];
		//$shop_end_time=$data['shop_end_time'];



                /**
					if($shop_start_time > 12)
					{
						$start=$shop_start_time - 12;
						$stime=$start."PM";
					}
					else
					{
						$stime=$shop_start_time."AM";
					}
					if($shop_end_time > 12)
					{
						$end=$shop_end_time-12;
						$etime=$end."PM";
					}
					else
					{
						$etime=$shop_end_time."AM";
					}
                 **/




		//$shop_booking_upto=$data['shop_booking_upto'];
		//$shop_booking_hour=$data['shop_booking_hour'];




		//$workdays="";
		//foreach($shop_working_days as $working_days)
		//{
		//	$workdays .=$working_days.',';
		//}
		//$workingdays=rtrim($workdays,",");

		$sellermail = Auth::user()->email;

		$sellerid = Auth::user()->id;
                $business_categoryid=$data['category'];
                $company_email=$data['company_email'];

		$featured="no";

		$status="unapproved";

		$admin_email_status=0;

		$adminid=1;

		if(!empty($data['status']))
		{
		$editstatus=$data['status'];
		}
		else
		{
			$editstatus="";
		}

		$site_logo=$data['site_logo'];

		$site_name=$data['site_name'];
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

                $address = Address::where('user_id', Auth::user()->id)->first();
                if(!isset($address)){
                    $address = new Address();
                    $address->user_id = Auth::user()->id;
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

		$sellermaile = Auth::user()->email;
    	 $shopcnt = DB::table('shop')
		 ->where('seller_email', '=', $sellermaile)
		 ->count();

		if($editid=="")
		{
			if($shopcnt==0)
			{



		DB::insert('insert into shop (shop_name,address,city,pin_code,country,state,shop_phone_no,description,shop_date,start_time,end_time,cover_photo,
		profile_photo,seller_email,user_id,featured,status,admin_email_status,booking_opening_days,booking_per_hour,business_categoryid,company_email) values (?, ? , ?, ?, ?, ? , ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)',
		[$shop_name,'','','','','',$shop_phone_no,$shop_desc,'','',
		'',$namef,$namepro,$sellermail,$sellerid,$featured,$status,$admin_email_status,'','',$business_categoryid,$company_email]);




/**
       Mail::send('shopuseremail', ['shop_name' => '', 'address' => '', 'city' => '', 'pin_code' => '', 'country' => '',
	   'state' => '', 'shop_phone_no' => $shop_phone_no, 'description' => $shop_desc, 'booking_opening_days' => '', 'business_categoryid'=>$business_categoryid,
	   'booking_per_hour' => '', 'stime' => '', 'etime' => '', 'site_logo' => $site_logo, 'site_name' => $site_name ,'company_email'=> $company_email], function ($message)
        {
            $message->subject('Shop Created Successfully');

            $message->from(Auth::user()->email, Auth::user()->name);

            $message->to(Input::get('admin_email_id')); 


			$message->from(Input::get('admin_email_id'), 'Admin');

            $message->to(Input::get('admin_email_id'));



        });




		Mail::send('shopadminemail', ['shop_name' => '', 'address' => '', 'city' =>'', 'pin_code' => '', 'country' => '',
	   'state' => '', 'shop_phone_no' => $shop_phone_no, 'description' => $shop_desc, 'booking_opening_days' => '',
	   'booking_per_hour' => '', 'stime' => '', 'etime' => '', 'site_logo' => $site_logo, 'site_name' => $site_name,'company_email'=>$company_email,'business_category_id'=>$business_categoryid ], function ($message)
        {
            $message->subject('New Shop Created');

            $message->from(Input::get('admin_email_id'), 'Admin');

            $message->to(Auth::user()->email);

        });

**/





			}




		}
		else if($editid!="")
		{
			DB::update('update shop set shop_name="'.$shop_name.'",address="",city="",pin_code="",country="",business_categoryid="'.$business_categoryid.'",
			state="",shop_phone_no="'.$shop_phone_no.'",description="'.$shop_desc.'",shop_date="",start_time="",company_email="'.$company_email.'",
			end_time="",cover_photo="'.$namef.'",profile_photo="'.$namepro.'",seller_email="'.$sellermail.'",user_id="'.$sellerid.'",featured="'.$featured.'",
			status="'.$editstatus.'",admin_email_status="'.$admin_email_status.'",booking_opening_days="",booking_per_hour="" where id = ?', [$editid]);
		}


			/* return back()->with('success', 'Shop has been created');*/



		return redirect('account');



      }


	}



}
