<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Responsive\Businesscategory;
use Responsive\SecurityCategory;
use Responsive\Job;
use Responsive\Transaction;

class JobsController extends Controller
{
    //
    public function create() {

        $all_security_categories = SecurityCategory::get();
        $all_business_categories = Businesscategory::get();
        
        return view('jobs.create', compact('all_security_categories', 'all_business_categories'));
    }
    public function schedule($id) {
        return view('jobs.schedule', compact('id'));
    }

    public function broadcast($id) {
        $all_security_categories = SecurityCategory::get();
        return view('jobs.broadcast', compact('id', 'all_security_categories'));
    }

    public function paymentDetails($id) {
        $trans = new Transaction();
        $available_balance = $trans->getWalletAvailableBalance();
        $jobDetails = Job::calculateJobAmount($id);
        return view('jobs.payment-details', compact('jobDetails', 'id', 'available_balance'));
    }

    public function confirmation() {
        return view('jobs.confirm');
    }

}
