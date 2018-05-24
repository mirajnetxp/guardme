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

class UsersController extends Controller {
	/**
	 * Show a list of all of the application's users.
	 *
	 * @return Response
	 */
	public function index() {
		$users = DB::table( 'users' )
		           ->leftJoin( 'address', 'users.id', '=', 'address.user_id' )
		           ->orderBy( 'users.id', 'desc' )
					->select(
						'users.id',
						'users.name',
						'users.email',
						'users.verified',
						'users.admin',
						'users.gender',
						'users.phone',
						'users.photo',
						'users.created_at',
						'users.firstname',
						'users.lastname',
						'users.dob',
						'users.phone_verified',
						'address.postcode',
						'address.houseno',
						'address.line1',
						'address.line2',
						'address.line3',
						'address.line4',
						'address.locality',
						'address.citytown',
						'address.country'
					)
		           ->get();


		return view( 'admin.users', [ 'users' => $users ] );
	}


	public function destroy( $id ) {

		$image       = DB::table( 'users' )->where( 'id', $id )->first();
		$orginalfile = $image->photo;
		$userphoto   = "/userphoto/";
		$path        = base_path( 'images' . $userphoto . $orginalfile );
		File::delete( $path );


		$userdetails = DB::table( 'users' )
		                 ->where( 'id', '=', $id )
		                 ->get();

		$uemail = $userdetails[0]->email;

		DB::delete( 'delete from seller_services where user_id = ?', [ $id ] );
		DB::delete( 'delete from rating where email = ?', [ $uemail ] );
		DB::delete( 'delete from booking where user_id = ?', [ $id ] );

		DB::delete( 'delete from shop_gallery where user_id = ?', [ $id ] );
		DB::delete( 'delete from shop where user_id = ?', [ $id ] );
		DB::delete( 'delete from users where id!=1 and id = ?', [ $id ] );

		return back();

	}

}