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

class ReferralController extends Controller {

	public function getReferralList() {
		$user        = Auth::user();
		$refList     = $user->getReferrals();
		$totalPoints = Referral::where( 'to', auth()->user()->id )->get()->sum( 'points' );

		return response()->json( [ 'referrals' => $refList, 'total_point' => $totalPoints ] );
	}

	/**
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function redeem( Request $request ) {

		return response()->json( [ 'items' => Item::all() ] );
	}

	/**
	 * @param Request $request
	 * @param $id
	 *
	 * @return mixed
	 */
	public function checkout( Request $request, $id ) {
		$item = Item::where( 'id', $id )->first();
		$user = Auth::user();

		$totalPoints = Referral::where( 'to', auth()->user()->id )->get()->sum( 'points' );

		$remainPoints = $totalPoints - $user->spent;

		if ( $item && $item->id ) {
			if ( $remainPoints < $item->price ) {
				return response()->json( [ 'Insufficient balance' ], 403 );
			}

			$userItem = new UserItem();

			$userItem->user_id = $user->id;
			$userItem->item_id = $item->id;
			$userItem->status  = false;
			$userItem->save();

			$user->spent = $user->spent + $item->price;
			$user->save();
		}

		return response()->json( [ $userItem ], 200 );
	}

	/**
	 * @return mixed
	 */
	public function remainPoints() {

		$user         = Auth::user();
		$totalPoints  = Referral::where( 'to', $user->id )->get()->sum( 'points' );
		$remainPoints = $totalPoints - $user->spent;

		return response()->json( [ 'remain_points' => $remainPoints ] );
	}

	/**
	 *
	 */
	public function pointsSpend() {
		$user = Auth::user();

		return response()->json( [ 'point_spent' => $user->spent ] );
	}

	/**
	 * @return mixed
	 */
	public function boughtItems() {

		$user      = Auth::user();
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

		return response()->json( $items );
	}
}
