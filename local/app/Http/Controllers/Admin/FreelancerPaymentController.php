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

			if ( $balance == 0 ) {
				unset( $allFreeLancers[ $key ] );
			}
		}


		return view( 'admin.freelancer-payment', [ 'allFreeLancers' => $allFreeLancers ] );
	}

	public function PayToAllBank() {


		$allFreeLancers = User::where( 'admin', 2 )
		                      ->join( 'payment_methods', 'users.id', '=', 'payment_methods.user_id' )
		                      ->where( 'method_type', 'bank' )
		                      ->select( 'users.id', 'users.name',
			                      'users.email',
			                      'payment_methods.method_type',
			                      'payment_methods.method_details' )
		                      ->get()
		                      ->map( function ( $item, $key ) {
			                      $bankObj             = json_decode( $item->method_details );
			                      $item->BankName      = $bankObj->bank_name;
			                      $item->AccountName   = $bankObj->ac_name;
			                      $item->ShortCode     = $bankObj->sort_code;
			                      $item->AccountNumber = $bankObj->ac_number;

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

			if ( $balance == 0 ) {
				unset( $allFreeLancers[ $key ] );
			} else {
				DB::table( 'transactions as tr' )
				  ->join( 'job_applications as ja', 'ja.id', '=', 'tr.application_id' )
				  ->where( 'ja.applied_by', $value->id )
				  ->whereNotNull( 'tr.application_id' )
				  ->where( 'status', 1 )
				  ->where( 'debit_credit_type', 'credit' )
				  ->where( 'credit_payment_status', 'paid' )
				  ->update( [ 'status' => 0 ] );
			}
		}
		if ( count( $allFreeLancers ) <= 0 ) {
			return back();
		}
		$csvExporter = new \Laracsv\Export();
		$csvExporter->build( $allFreeLancers, [
			'email',
			'name',
			'BankName',
			'AccountName',
			'ShortCode',
			'AccountNumber',
			'credit'
		] )->download();


	}

	public function PayToBank( $id ) {

		$Freelancer = User::where( 'users.id', $id )
		                  ->join( 'payment_methods', 'users.id', '=', 'payment_methods.user_id' )
		                  ->where( 'method_type', 'bank' )
		                  ->select( 'users.id', 'users.name',
			                  'users.email',
			                  'payment_methods.method_type',
			                  'payment_methods.method_details' )
		                  ->get()
		                  ->map( function ( $item, $key ) {
			                  $bankObj             = json_decode( $item->method_details );
			                  $item->BankName      = $bankObj->bank_name;
			                  $item->AccountName   = $bankObj->ac_name;
			                  $item->ShortCode     = $bankObj->sort_code;
			                  $item->AccountNumber = $bankObj->ac_number;

			                  return $item;
		                  } );

		$credit = DB::table( 'transactions as tr' )
		            ->select( DB::raw( 'SUM(amount) as total' ) )
		            ->join( 'job_applications as ja', 'ja.id', '=', 'tr.application_id' )
		            ->where( 'ja.applied_by', $id )
		            ->whereNotNull( 'tr.application_id' )
		            ->where( 'status', 1 )
		            ->where( 'debit_credit_type', 'credit' )
		            ->where( 'credit_payment_status', 'paid' )
		            ->get()
		            ->first();

		$balance = $credit->total ? $credit->total : 0;

		foreach ( $Freelancer as $key => $value ) {
			$Freelancer[ $key ]->credit = $balance;
		}

		DB::table( 'transactions as tr' )
		  ->join( 'job_applications as ja', 'ja.id', '=', 'tr.application_id' )
		  ->where( 'ja.applied_by', $id )
		  ->whereNotNull( 'tr.application_id' )
		  ->where( 'status', 1 )
		  ->where( 'debit_credit_type', 'credit' )
		  ->where( 'credit_payment_status', 'paid' )
		  ->update( [ 'status' => 0 ] );

		$csvExporter = new \Laracsv\Export();
		$csvExporter->build( $Freelancer, [
			'email',
			'name',
			'BankName',
			'AccountName',
			'ShortCode',
			'AccountNumber',
			'credit'
		] )->download();
	}

	public function PayplePayoutsAll() {

		$allFreeLancers = User::where( 'admin', 2 )
		                      ->join( 'payment_methods', 'users.id', '=', 'payment_methods.user_id' )
		                      ->where( 'method_type', 'payple' )
		                      ->select(
			                      'users.id',
			                      'users.name',
			                      'users.email',
			                      'payment_methods.method_type',
			                      'payment_methods.method_details' )
		                      ->get()
		                      ->map( function ( $item, $key ) {

			                      $item->currency   = "GBP";
			                      $item->CustomerID = "ID00$item->id";
			                      $item->note       = "Thanks $item->name !";

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

			if ( $balance == 0 ) {
				unset( $allFreeLancers[ $key ] );
			} else {
				DB::table( 'transactions as tr' )
				  ->join( 'job_applications as ja', 'ja.id', '=', 'tr.application_id' )
				  ->where( 'ja.applied_by', $value->id )
				  ->whereNotNull( 'tr.application_id' )
				  ->where( 'status', 1 )
				  ->where( 'debit_credit_type', 'credit' )
				  ->where( 'credit_payment_status', 'paid' )
				  ->update( [ 'status' => 0 ] );
			}
		}
		if ( count( $allFreeLancers ) <= 0 ) {
			return back();
		}
		$csvExporter = new \Laracsv\Export();
		$csvExporter->build( $allFreeLancers, [
			'method_details' => 'Recipient ID',
			'credit'         => 'Payment',
			'currency'       => 'Currency',
			'CustomerID'     => 'Customer ID',
			'note'           => 'Note',


		] )->download();

	}

	public function PayplePayout( $id ) {
		$Freelancer = User::where( 'users.id', $id )
		                  ->join( 'payment_methods', 'users.id', '=', 'payment_methods.user_id' )
		                  ->where( 'method_type', 'payple' )
		                  ->select(
			                  'users.id',
			                  'users.name',
			                  'users.email',
			                  'payment_methods.method_type',
			                  'payment_methods.method_details' )
		                  ->get()
		                  ->map( function ( $item, $key ) {
			                  $item->currency   = "GBP";
			                  $item->CustomerID = "ID00$item->id";
			                  $item->note       = "Thanks $item->name !";

			                  return $item;
		                  } );

		$credit = DB::table( 'transactions as tr' )
		            ->select( DB::raw( 'SUM(amount) as total' ) )
		            ->join( 'job_applications as ja', 'ja.id', '=', 'tr.application_id' )
		            ->where( 'ja.applied_by', $id )
		            ->whereNotNull( 'tr.application_id' )
		            ->where( 'status', 1 )
		            ->where( 'debit_credit_type', 'credit' )
		            ->where( 'credit_payment_status', 'paid' )
		            ->get()
		            ->first();

		$balance = $credit->total ? $credit->total : 0;

		foreach ( $Freelancer as $key => $value ) {
			$Freelancer[ $key ]->credit = $balance;
		}

		DB::table( 'transactions as tr' )
		  ->join( 'job_applications as ja', 'ja.id', '=', 'tr.application_id' )
		  ->where( 'ja.applied_by', $id )
		  ->whereNotNull( 'tr.application_id' )
		  ->where( 'status', 1 )
		  ->where( 'debit_credit_type', 'credit' )
		  ->where( 'credit_payment_status', 'paid' )
		  ->update( [ 'status' => 0 ] );


		$csvExporter = new \Laracsv\Export();
		$csvExporter->build( $Freelancer, [
			'method_details' => 'Recipient ID',
			'credit'         => 'Payment',
			'currency'       => 'Currency',
			'CustomerID'     => 'Customer ID',
			'note'           => 'Note',


		] )->download();
	}
}
