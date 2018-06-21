<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Responsive\Item;

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
	/**
	 * Loyalty page
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function index( Request $request ) {
		$user        = Auth::user();
		$wallet      = new Transaction();
		$wallet_data = $wallet->getAllTransactionsAndEscrowBalance();
		//Get list of ids of items that user bought
		$userItems = UserItem::where( 'user_id', $user->id )->get();
		$items     = [];

		foreach ( $userItems as $userItem ) {
			$items[] = Item::where( 'id', $userItem->item_id )->first();
		}



		return view(
			'referral',
			[
				'user'         => Auth::user(),
				'editprofile'  => [ 0 => Auth::user() ],
				'referrals'    => $user->getReferrals(),
//				'total_points' => $total_point,
				'items'        => $items,
				'wallet_data'  => $wallet_data
			]
		);
	}

	/**
	 * Redeem page with list of items
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function redeem( Request $request ) {

		if ( ! Auth::Check() ) {
			return redirect( '/' );
		}

		return view(
			'redeem',
			[
				'user'        => Auth::user(),
				'editprofile' => [ 0 => Auth::user() ],
				'items'       => Item::all()
			]
		);
	}

	/**
	 * 'Buy' action for referral item
	 *
	 * @param Request $request
	 * @param $id
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function checkout( Request $request, $id ) {
		$item = Item::where( 'id', $id )->first();
		$user = Auth::user();

		//Check if item actually exists
		if ( $item && $item->id ) {
			//Check if user have enough balance
			if ( $user->getBalance() >= $item->price ) {
				DB::transaction( function () use ( $user, $item ) {
					//Assign item to user
					$userItem = UserItem::create( [
						'user_id' => $user->id,
						'item_id' => $item->id
					] );

					$user->spent = $user->spent + $item->price;
					$user->save();
				} );

				return redirect( '/referral' );
			}
		}

		return redirect( '/redeem' );
	}
}
