<?php

namespace Responsive\Http\Controllers\Api;

use Illuminate\Http\Request;
use Responsive\Http\Controllers\Controller;


class FcmController extends Controller {
	//
	public function StoreToken( Request $request ) {
		$fcmToken = $request->fcm_token;

		$user            = auth()->user();
		$user->fcm_token = $fcmToken;
		$user->save();

	}
}
