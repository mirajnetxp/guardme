<?php

namespace Responsive\Http\Controllers\Admin;

use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Refund;
use PayPal\Api\RefundRequest;
use PayPal\Api\Sale;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payment;
use Responsive\Http\Controllers\Controller;
use Responsive\Transaction;
use Illuminate\Support\Facades\Config;
use Responsive\User;
use DB;

class FreelancerPaymentController extends Controller {
	public function index() {
		$allFreeLancers = User::where( 'admin', 2 )
		                      ->with( 'paymentmethod' )
		                      ->get();

		foreach ( $allFreeLancers as $key => $value ) {
			$credit = DB::table( 'transactions as tr' )
			            ->select( DB::raw( 'SUM(amount) as total' ) )
			            ->join( 'job_applications as ja', 'ja.id', '=', 'tr.application_id' )
			            ->where( 'ja.applied_by', $value->id )
			            ->whereNotNull( 'tr.application_id' )
			            ->where( 'status', 1 )
			            ->where( 'debit_credit_type', 'credit' )
			            ->where( 'credit_payment_status', 'paid' )
			            ->get()
			            ->first();


			$balance                        = $credit->total ? $credit->total : 0;
			$allFreeLancers[ $key ]->credit = $balance;
		}


		return view( 'admin.freelancer-payment', [ 'allFreeLancers' => $allFreeLancers ] );
	}

	public function PayToAll() {


		$allFreeLancers = User::where( 'admin', 2 )
		                      ->join( 'payment_methods', 'users.id', '=', 'payment_methods.user_id' )
		                      ->where( 'method_type', 'bank' )
		                      ->get()
		                      ->map( function ( $item, $key ) {
			                      $bankObj              = json_decode( $item->method_details );
			                      $item->method_details = "Bank Name: $bankObj->bank_name , Account Name: $bankObj->ac_name , Short Code: $bankObj->sort_code , Account Number: $bankObj->ac_number";
			                      return $item;
		                      } );

		foreach ( $allFreeLancers as $key => $value ) {
			$credit = DB::table( 'transactions as tr' )
			            ->select( DB::raw( 'SUM(amount) as total' ) )
			            ->join( 'job_applications as ja', 'ja.id', '=', 'tr.application_id' )
			            ->where( 'ja.applied_by', $value->id )
			            ->whereNotNull( 'tr.application_id' )
			            ->where( 'status', 1 )
			            ->where( 'debit_credit_type', 'credit' )
			            ->where( 'credit_payment_status', 'paid' )
			            ->get()
			            ->first();


			$balance                        = $credit->total ? $credit->total : 0;
			$allFreeLancers[ $key ]->credit = $balance;
		}

//dd($allFreeLancers);
		$csvExporter = new \Laracsv\Export();
		$csvExporter->build( $allFreeLancers, [ 'email', 'name', 'method_details' ] )->download();
	}

}
