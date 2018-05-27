<?php

namespace Responsive\Http\Controllers\Api;


use Illuminate\Http\Request;
use Responsive\Http\Controllers\Controller;
use Responsive\Transaction;
use Responsive\Job;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller {
	//
	public function getWalletData() {
		$wallet      = new Transaction();
		$wallet_data = $wallet->getAllTransactionsAndEscrowBalance();

		return response()
			->json( $wallet_data, 200 );
	}


	public function getTransactionsOfJobs() {
		$user_id = \Auth::user()->id;
		//echo $user_id ;
		$my_jobs = Job::select( 'id', 'title' )->with( 'getJobTransactions' )->get();
		// $user_transactions = Transaction::with(['getTransactionJob'])->where('user_id' , $user_id)->get();

		//echo json_encode($user_transactions);
		return response()
			->json( $my_jobs, 200 );

	}


	public function getJobTransactionDetails( $id ) {
		$wallet_data = Transaction::where( 'job_id', $id )->first();
		//  $data = ['name'=> 'maysoon' , 'age'=> 26] ;
		//echo json_encode($data);

		return response()
			->json( $wallet_data, 200 );

	}
	
//Created by Miraj
	public function getTransactionsList() {
		$userId=auth()->user()->id;
		$tL=DB::table('transactions')
			->where('job_id','>',0)
			->where('user_id',$userId)
			->leftJoin( 'security_jobs', 'transactions.job_id', '=', 'security_jobs.id' )
			->select('security_jobs.title','transactions.created_at','transactions.amount')
			->get();
//		->leftJoin( 'users', 'job_applications.applied_by', '=', 'users.id' )
		return response()->json($tL);
	}

}
