<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\DB;
use Mail;
use Auth;
use Crypt;
use Responsive\Transaction;
use Responsive\User;
use Responsive\Job;
use Responsive\JobApplication;
use URL;
use Carbon\Carbon;
use PDF;

class WalletController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
	public function show() {
		$wallet = new Transaction();
		$wallet_data = $wallet->getAllTransactionsAndEscrowBalance();
		// return view('wallet', compact('wallet_data'));
		$user = auth()->user();
		if($user->admin == 0){
			$jobs = DB::select('select distinct security_jobs.id, security_jobs.title, sum(transactions.amount) as amount, security_jobs.created_at from security_jobs, transactions where transactions.job_id = security_jobs.id and transactions.status = 1 and transactions.user_id = '.$user->id.' group by job_id');
		}else if($user->admin == 2){
			$jobs = DB::select('select distinct security_jobs.id, security_jobs.title, sum(transactions.amount) as amount, security_jobs.number_of_freelancers, security_jobs.created_at from security_jobs, job_applications, transactions where job_applications.job_id = security_jobs.id and transactions.job_id = security_jobs.id and is_hired = 1 and applied_by = '.$user->id.' and transactions.type = "job_fee" group by security_jobs.id');
		}else{
            $jobs = array();
        }
		// dd($jobs);
		return view('wallet', compact('jobs', 'wallet_data'));
	}

	public function view() {
		$wallet = new Transaction();
		$wallet_data = $wallet->getAllTransactionsAndEscrowBalance();
		$userid = auth()->user()->id;
		$editprofile = User::where('id',$userid)->get();

        $user = auth()->user();
        if($user->admin == 0){
            $jobs = DB::select('select distinct security_jobs.id, security_jobs.title, sum(transactions.amount) as amount, security_jobs.created_at from security_jobs, transactions where transactions.job_id = security_jobs.id and transactions.status = 1 and transactions.user_id = '.$user->id.' group by job_id');
        }else if($user->admin == 2){
            $jobs = DB::select('select distinct security_jobs.id, security_jobs.title, sum(transactions.amount) as amount, security_jobs.number_of_freelancers, security_jobs.created_at from security_jobs, job_applications, transactions where job_applications.job_id = security_jobs.id and transactions.job_id = security_jobs.id and is_hired = 1 and applied_by = '.$user->id.' and transactions.type = "job_fee" group by security_jobs.id');
        }else{
            $jobs = array();
        }
		return view('wallet-dashboard', compact('wallet_data', 'editprofile', 'jobs'));
	}

	public function searchJobs(Request $request) 
    {
    	$wallet = new Transaction();
		$wallet_data = $wallet->getAllTransactionsAndEscrowBalance();
    	$keyword = $request->keyword;
    	$start_date = $request->start_date;
    	$end_date = $request->end_date;
    	$user = auth()->user();

    	if($keyword != ''){
			if($user->admin == 0){
				$jobs = DB::select('select distinct security_jobs.id, security_jobs.title, transactions.amount, transactions.created_at from security_jobs, transactions where transactions.job_id = security_jobs.id and transactions.status = 1 and transactions.user_id = '.$user->id.' and (security_jobs.id like "%'.$keyword.'%" or security_jobs.title like "%'.$keyword.'%") group by job_id');
			}else if($user->admin == 2){
				$jobs = DB::select('select distinct security_jobs.id, security_jobs.title, transactions.amount, transactions.created_at from security_jobs, job_applications, transactions where job_applications.job_id = security_jobs.id and transactions.job_id = security_jobs.id and is_hired = 1 and applied_by = '.$user->id.' and (security_jobs.id like "%'.$keyword.'%" or security_jobs.title like "%'.$keyword.'%") group by security_jobs.id');
			}
    	}
    	else if($start_date != null && $end_date != null && $start_date < $end_date){
    		$format = "y_m_d";
			$date1  = date("Y-m-d", strtotime($start_date));
			$date2  = date("Y-m-d", strtotime($end_date));
			// dd($date1." ".$date2);
    		if($user->admin == 0){
				$jobs = DB::select('select distinct security_jobs.id, security_jobs.title, transactions.amount, transactions.created_at from security_jobs, transactions where transactions.job_id = security_jobs.id and transactions.status = 1 and transactions.user_id = '.$user->id.' and (transactions.created_at between "'.$date1.'" and "'.$date2.'" ) group by security_jobs.id');
			}else if($user->admin == 2){
				$jobs = DB::select('select distinct security_jobs.id, security_jobs.title, transactions.amount, transactions.created_at from security_jobs, job_applications, transactions where job_applications.job_id = security_jobs.id and transactions.job_id = security_jobs.id and is_hired = 1 and applied_by = '.$user->id.' and (transactions.created_at between "'.$date1.'" and "'.$date2.'" ) group by security_jobs.id');
			}
    	}else{
    		$jobs = array();
    	}
    	return view('wallet', compact('jobs', 'wallet_data'));
    }

	public function invoice(Request $request, $id){
		// return view('invoice-employer');
		$user = auth()->user();
		$user_id = auth()->user()->id;
        $balance = '';
        
        $from = array();        
        $from = $user;
	    $from->date = Carbon::now();
        $all_transactions = array();

        if(!empty($user_id)) {
            if($user->admin == 2){
                $all_transactions = DB::select('select security_jobs.title, transactions.id, transactions.amount, transactions.created_at, security_jobs.number_of_freelancers, transactions.credit_payment_status as status from security_jobs, transactions where transactions.job_id = security_jobs.id and transactions.status = 1 and transactions.type = "job_fee" and security_jobs.id = '.$id);
            }else if($user->admin == 0){
                $credit = Transaction::select(DB::raw('SUM(amount) as total'))
                    ->groupBy('user_id')
                    // ->where('user_id', $user_id)
                    ->where('job_id', $id)
                    ->where('status', 1)
                    ->where(function($query){
                        $query->orWhere('credit_payment_status', 'paid')
                            ->orWhere('credit_payment_status', 'funded');
                    })
                    ->where('debit_credit_type', 'credit')
                    ->get()->first();
                $total_credit = !empty($credit->total) ? ($credit->total) : 0;
                $balance = $total_credit;

                $all_transactions = DB::select('select transactions.title, transactions.id, transactions.created_at, transactions.amount, security_jobs.number_of_freelancers, transactions.credit_payment_status as status from security_jobs, transactions where transactions.job_id = security_jobs.id and transactions.status = 1 and security_jobs.id = '.$id);
                $applied_by = JobApplication::select('applied_by')->where('job_id', $id)->get();
                // dd($applied_by);
                foreach ($all_transactions as $key => $transactions) {
                    if($transactions->title == 'Job Fee'){
                        $transactions->user_id = $applied_by;
                    }
                }
                // dd($all_transactions);
            }
        }
        if (!empty($all_transactions)) {
	        if($user->admin == 2){
	            if($request->has('download')){
		            $pdf = PDF::loadView('invoice-freelancer', compact('all_transactions', 'balance', 'from', 'id'));
		            return $pdf->download('invoice.pdf');
		        }
	        	return view('invoice-freelancer', compact('all_transactions', 'balance', 'from', 'id'));
	        }
	        else if($user->admin == 0){
	        	if($request->has('download')){
		            $pdf = PDF::loadView('invoice-employer', compact('all_transactions', 'balance', 'from', 'id'));
		            return $pdf->download('invoice.pdf');
		        }
	        	return view('invoice-employer', compact('all_transactions', 'balance', 'from', 'id'));
	        }
        }

        return view('invoice-freelancer', compact('all_transactions', 'balance', 'from', 'id'));
	}

    public function freelancerInvoice(Request $request){
        $user_id = $request->user_id;
        $id = $request->id;
        $all_transactions = DB::select('select security_jobs.title, transactions.id, transactions.amount, transactions.created_at, security_jobs.number_of_freelancers, transactions.credit_payment_status as status from security_jobs, transactions where transactions.job_id = security_jobs.id and transactions.status = 1 and transactions.type = "job_fee" and security_jobs.id = '.$id);

        $from = User::find($user_id);
        $from->date = Carbon::now();

        if($request->has('download')){
                    $pdf = PDF::loadView('invoice-freelancer', compact('all_transactions', 'balance', 'from', 'id'));
                    return $pdf->download('invoice.pdf');
                }
                return view('invoice-freelancer', compact('all_transactions', 'balance', 'from', 'id'));
    }
}
