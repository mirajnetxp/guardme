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

class PaymentsController extends Controller
{
    //
    private $_api_context;
    protected $currency = 'GBP';
    protected $sale_id;
    public function __construct() {
        $this->middleware( 'admin' );

        // setup PayPal api context
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function index($type) {
        $function = $type . '_payments';
        return $this->$function();
    }

    /**
     * @param $transaction_id
     * @return mixed
     */
    public function employerPaymentDetails($transaction_id) {
        $trans = new Transaction();
        $transaction = $trans->getEmployerPaymentRecords($transaction_id)->first();
        if (empty($transaction)) {
            return redirect()->route('employer.payment.listing', ['type' => 'employer']);
        }
        // get sale id of the transactions
        $amount_details = json_decode($transaction->extra_details);
        return view( 'admin.employer_payment_details' )->with( ['payment' => $transaction, 'amount_details' => $amount_details] );
    }

    public function completeRefund($transaction_id) {


        $trans = new Transaction();
        $transaction = $trans->getEmployerPaymentRecords($transaction_id)->first();
        // get sale id
        $payment = Payment::get($transaction->paypal_id, $this->_api_context);
        $sale_id = $payment->getTransactions()[0]->getRelatedResources()[0]->getSale()->getId();
        // set amount
        $amount = new Amount();
        $amt = (string) round($transaction->amount, 2);
        $amount->setTotal($amt)
            ->setCurrency($this->currency);
        // set amount to refund object
        $refund = new Refund();
        $refund->setAmount($amount);
        // execute refund
        $sale = new Sale();
        $sale->setId($sale_id);
        try {
            $refundedSale = $sale->refund($refund, $this->_api_context);
            Transaction::where('id', $transaction->transaction_id)->update(['credit_payment_status' => 'complete']);
            return response()
                ->json( ['Refund Completed successfully'], 200 );
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            return response()
                ->json( ['Error occurred'], 500 );
        } catch (Exception $ex) {
            return response()
                ->json( ['Error occurred'], 500 );
        }

    }
    private function employer_payments() {

        $trans = new Transaction();
        $transactions = $trans->getEmployerPaymentRecords();
        return view( 'admin.employer_payments' )->with( 'payments', $transactions );
    }

    private function freelancer_payments() {
        $transactions = Transaction::where('debit_credit_type', 'credit')->where('status', 1)->where('credit_payment_status', 'paid')->get();

        return view( 'admin.payments' )->with( 'transactions', $transactions );
    }



}
