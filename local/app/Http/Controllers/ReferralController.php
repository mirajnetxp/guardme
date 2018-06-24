<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Responsive\Item;

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

		foreach ( $userItems as $key => $userItem ) {
			$items[ $key ] = Item::where( 'id', $userItem->item_id )->first();

			if ( $userItem->status == false ) {
				$status = 'Processing';
			}
			if ( $userItem->status == 1 ) {
				$status = 'Delivered';
			}
			if ( isset( $userItem->status ) == false ) {
				$status = 'Cancelled';
			}
			$items[ $key ]['status'] = $status;
		}

		$totalPoints  = Referral::where( 'to', $user->id )->get()->sum( 'points' );
		$remainPoints = $totalPoints - $user->spent;

		return view(
			'referral',
			[
				'user'         => Auth::user(),
				'editprofile'  => [ 0 => Auth::user() ],
				'referrals'    => $user->getReferrals(),
				'total_points' => $remainPoints,
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

		$totalPoints = Referral::where( 'to', auth()->user()->id )->get()->sum( 'points' );

		$remainPoints = $totalPoints - $user->spent;

		if ( $item && $item->id ) {
			if ( $remainPoints < $item->price ) {

				return redirect( '/referral' )->with('Insufficient_balance', "Insufficient balance.");
			}

			$userItem = new UserItem();

			$userItem->user_id = $user->id;
			$userItem->item_id = $item->id;
			$userItem->status  = false;
			$userItem->save();

			$user->spent = $user->spent + $item->price;
			$user->save();
			return redirect( '/referral' );
		}

		return redirect( '/redeem' );
	}
}
