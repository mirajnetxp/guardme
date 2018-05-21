<?php

namespace Responsive\Http\Controllers\Api;

use Illuminate\Support\Facades\DB;

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


    public function JobsList(){

        $jobDetails = Job::getMyJobs();
        $data = array() ;
      //  $calc = array() ;
       if(count($jobDetails) ==0 ){
           $data[] = [
               'message'=>'failed',
               'message_txt'=>'not items to display'

           ];
           return response()
               ->json($data, 200);
       } else{
           foreach($jobDetails as $list){
               $calc = Job::calculateJobAmount($list->id);
               $data[] = [
                   'message'=>'success',[
                       'id'=>$list->id ? $list->id: null,
                       'title'=>$list->title ? $list->title : null ,
                       'payment_date' => $list->getJobTransactions['created_at'] ? $list->getJobTransactions['created_at']: null,
//                     'vat' => $calc['vat_fee'] ,
//                     'amount' => $calc['grand_total']
                   ]
               ];
           }

           return response()
               ->json($data, 200);
       }




    }

    public function getJobTransactionDetails($id){
        $amount = Job::calculateJobAmount($id) ;
        $job = Transaction::with(['getTransactionJob'])
                           ->where('job_id' , $id)
                           ->get();

//        $job= Transaction::where('job_id' , $id )->get();
        $data = array();
        foreach($job as $list){
            $data[] = ['title'=> $list->title ,
                         'date_of_payment' =>$list->created_at ,
                         'paypal_ref'=> $list->paypal_id ,
                         'vat'=>  $amount['vat_fee'] ,
                          'commission'=>  $amount['admin_fee']   ,
                          'job_fee'=> $amount['basic_total']

                        ];
        }



        return response()
            ->json($data, 200);

    }


}
