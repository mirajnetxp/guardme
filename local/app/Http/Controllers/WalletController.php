<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Mail;
use Auth;
use Crypt;
use Responsive\Transaction;
use URL;

class WalletController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
	public function show() {
		$user_id = auth()->user()->id;
		$wallet = new Transaction();
		//$available_balance = $wallet->getWalletAvailableBalance();
		$escrow_balance = $wallet->getWalletEscrowBalance();
		$all_transactions = Transaction::where('status', 1)
			->where('user_id', $user_id)
			->get();
		return view('wallet', compact('all_transactions', 'escrow_balance'));
	}
}
