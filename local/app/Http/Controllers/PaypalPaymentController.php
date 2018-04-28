<?php

namespace Responsive\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;
use Responsive\Transaction as WalletTransaction;

use Responsive\Job;

class PaypalPaymentController extends Controller
{
    //
    private $_api_context;
    protected $currency = 'GBP';
    public function __construct()
    {
        // setup PayPal api context
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }
    public function postPayment($id)
    {
        $jobDetails = Job::calculateJobAmount($id);
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $item_1 = new Item();
        $item_1->setName('Fee for Job')
            ->setCurrency($this->currency)
            ->setQuantity(1)
            ->setPrice($jobDetails['basic_total']);

        $item_2 = new Item();
        $item_2->setName('VAT Fee')
            ->setCurrency($this->currency)
            ->setQuantity(1)
            ->setPrice($jobDetails['vat_fee']);

        $item_3 = new Item();
        $item_3->setName('Admin Fee')
            ->setCurrency($this->currency)
            ->setQuantity(1)
            ->setPrice($jobDetails['admin_fee']);

        // add item to list
        $item_list = new ItemList();
        $item_list->setItems(array($item_1, $item_2, $item_3));

        $amount = new Amount();
        $amount->setCurrency($this->currency)
            ->setTotal($jobDetails['grand_total']);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Fees to create job');

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl(route('payment.status'))
            ->setCancelUrl(route('payment.status'));

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
                $err_data = json_decode($ex->getData(), true);
                exit;
            } else {
                die('Some error occur, sorry for inconvenient');
            }
        }

        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        // add payment ID to session
        session()->put('paypal_payment_id', $payment->getId());
        session()->put('job_id', $id);
        session()->put('user_id', auth()->user()->id);




        /*$trans = new WalletTransaction();
        $params = [
            'debit_credit_type' => 'debit',
            'amount' => $jobDetails['grand_total'],
            'title' => 'added some money to create job',
            'paypal_id' => $payment->getId()
        ];
        // will add a debit entry with status 0
        $trans->addMoney($params);
        // have to add all three credit entries for job creation
        $job_fee = [
            'job_id' => $id
        ];
        $trans->fundJobFee($job_fee);

        $admin_fee = [
            'job_id' => $id,
            'debit_credit_type' => 'credit',
            'type' => 'admin_fee',
            'title' => 'admin fee',
            'credit_payment_status' => 'funded'
        ];
        $vat_fee = [
            'job_id' => $id,
            'debit_credit_type' => 'credit',
            'type' => 'vat_fee',
            'title' => 'vat fee',
            'credit_payment_status' => 'funded'
        ];*/
        if(isset($redirect_url)) {
            // redirect to paypal
            return redirect($redirect_url);
        }
        return redirect(route('job.payment.details', ['id' => $id]))
            ->with('error', 'Unknown error occurred');
    }

    public function getPaymentStatus()
    {
        // Get the payment ID and job id before session clear
        $payment_id = session()->get('paypal_payment_id');
        $job_id = session()->get('job_id');
        $user_id = session()->get('user_id');
        // clear the session payment ID
        session()->forget('paypal_payment_id');

        if (empty(request()->get('PayerID')) || empty(request()->get('token'))) {
            return redirect()->route('original.route')
                ->with('error', 'Payment failed');
        }

        $payment = Payment::get($payment_id, $this->_api_context);

        // PaymentExecution object includes information necessary
        // to execute a PayPal account payment.
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site
        $execution = new PaymentExecution();
        $execution->setPayerId(request()->get('PayerID'));

        //Execute the payment
        $result = $payment->execute($execution, $this->_api_context);

        if ($result->getState() == 'approved') { // payment made
            $paypalTransactions = $result->getTransactions();
            $total_amount_paid = $paypalTransactions[0]->getAmount()->getTotal();
            // call add money function to add amount
            $add_money_params = [
                'paypal_id' => $payment_id,
                'amount' => $total_amount_paid,
                'user_id' => $user_id,
                'paypal_payment_status' => $result->getState(),
                'status' => 1
            ];
            // add money
            $walletTransaction = new WalletTransaction();
            $walletTransaction->addMoney($add_money_params);
            return redirect(route('job.payment.details', ['id' => $job_id]))
                ->with('success', 'Congratulations, Money has been added to your wallet. Please confirm activiate your job now.');
        }
        return redirect(route('job.payment.details', ['id' => $job_id]))
            ->with('error', 'Payment failed');
    }

}
