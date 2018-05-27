<?php

namespace Responsive\Http\Controllers\Api;


use Illuminate\Http\Request;
use Responsive\Http\Controllers\Controller;
use Responsive\Transaction;
use Responsive\Job;


class WalletController extends Controller
{
    //
    public function getWalletData() {
        $wallet = new Transaction();
        $wallet_data = $wallet->getAllTransactionsAndEscrowBalance();
        return response()
            ->json($wallet_data, 200);
    }



    public function getTransactionsOfJobs(){

        // get wallet data job wise
        $wallet = new Transaction();
        $job_transactions_data = $wallet->getJobsTransactionData();
        return response()
            ->json($job_transactions_data , 200);

    }


    public function getJobTransactionDetails($id){
        $wallet_data = Transaction::where('job_id' , $id )->first();
     //  $data = ['name'=> 'maysoon' , 'age'=> 26] ;
       //echo json_encode($data);

        return response()
            ->json($wallet_data, 200);

    }


}
