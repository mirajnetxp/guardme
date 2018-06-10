<?php

namespace Responsive\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Responsive\Item;
use Responsive\Http\Controllers\Controller;
use Responsive\Referral;
use Responsive\Transaction;
use Responsive\Url;
use Responsive\User;
use Responsive\UserItem;

/**
 * Class ReferralController
 * Controller for referral system on frontend
 *
 * @package Responsive\Http\Controllers
 */
class ReferralController extends Controller {

	public function getReferralList() {
		$user    = Auth::user();
		$refList = $user->getReferrals();
		$totalPoints=Referral::where( 'to', auth()->user()->id)->get()->sum( 'points' );

		return response()->json( [ 'referrals' => $refList , 'total_point'=>$totalPoints] );
	}
}
