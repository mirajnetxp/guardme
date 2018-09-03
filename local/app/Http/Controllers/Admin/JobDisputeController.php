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
use Responsive\Job;
use Responsive\JobApplication;
use Responsive\Ticket;
use Responsive\Transaction;
use Illuminate\Support\Facades\Config;
use Responsive\User;
use DB;

class JobDisputeController extends Controller {
	public function index() {
		$disputJobIdes = JobApplication::where( 'completion_status', 2 )->get()->pluck( 'job_id' )->toArray();
		$disputJobs    = Job::whereIn( 'id', $disputJobIdes )->get();
		foreach ( $disputJobs as $key => $value ) {
			$ticketObj                   = new Ticket();
			$tickets                     = $ticketObj->DisputTicket( $value->id );
			$disputJobs[ $key ]->tickets = $tickets;
		}
		return view( 'admin.job-disput', [ 'disputJobs' => $disputJobs ] );
	}

}
