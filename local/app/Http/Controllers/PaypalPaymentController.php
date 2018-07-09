<?php
namespace Responsive\Http\Controllers;
use Responsive\Http\Traits\JobsTrait;
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
use Responsive\User;
use Mail;

class PaypalPaymentController extends Controller
{
    use JobsTrait;
    
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
        if(isset($redirect_url)) {
            // redirect to paypal
            return redirect($redirect_url);
        }
        return redirect(route('job.payment.details', ['id' => $id]))
            ->with('error', 'Unknown error occurred');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getPaymentStatus(Request $request)
    {
        $request_data = $request->all();
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
        if (!$payment_id && $request_data['paymentId']) {
            $payment_id = $request_data['paymentId'];
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
                'job_id' => $job_id,
                'status' => 1,
            ];
            // add money
            $walletTransaction = new WalletTransaction();
            $walletTransaction->addMoney($add_money_params);
            
            /* Email to users for selected radius */
            $job = Job::find($job_id);
            if($job){
                if( !empty($job->latitude) && !empty($job->longitude) && !empty($job->specific_area_min) && !empty($job->specific_area_max) ){
                    $latitude = $job->latitude;
                    $longitude = $job->longitude;
                    $specific_area_min = $job->specific_area_min;
                    $specific_area_max = $job->specific_area_max;
                    $usersRes = User::getUsersNearByJob($latitude, $longitude, $specific_area_min, $specific_area_max, 'kilometers');
                    if( count($usersRes) > 0 ){
                        foreach($usersRes as $usersResVal){
                            $data = array('name' => $usersResVal->name, 'specific_area_min' => $specific_area_min, 'specific_area_max' => $specific_area_max);
                            // Send mail
                            $this->jobStore($data, $usersResVal->id);                           
                        }
                    }
                }
            }
            
            return redirect(route('job.payment.details', ['id' => $job_id]))
                ->with('success', 'Congratulations, money has been added to your wallet. Please click confirm to activate the job.');
        }
        return redirect(route('job.payment.details', ['id' => $job_id]))
            ->with('error', 'Payment failed');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function addMoneyPaypal(Request $request)
    {

        $this->validate( $request, [
            'amount' => 'required',
            'success_url' => 'required',
            'payment_name' => 'required',
            'success_message' => 'required',
        ]);
        $payment_status_url = route('get.paypal.payment.status');
        $postedData = $request->all();
        $payment_amount = floatval($postedData['amount']);
        //var_dump(floatval($amount));exit;
        $success_url = $postedData['success_url'];
        $payment_name = $postedData['payment_name'];
        $success_message = $postedData['success_message'];
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $item_1 = new Item();
        $item_1->setName($payment_name)
            ->setCurrency($this->currency)
            ->setQuantity(1)
            ->setPrice($payment_amount);
        // add item to list
        $item_list = new ItemList();
        $item_list->setItems(array($item_1));
        $amount = new Amount();
        $amount->setCurrency($this->currency)
            ->setTotal($payment_amount);
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription($payment_name);
        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl($payment_status_url)
            ->setCancelUrl($payment_status_url);
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
        // add payment ID and urls to session
        session()->put('paypal_payment_id', $payment->getId());
        session()->put('user_id', auth()->user()->id);
        session()->put('success_url', $success_url);
        session()->put('success_message', $success_message);


        if(isset($redirect_url)) {
            // redirect to paypal
            return redirect($redirect_url);
        }
        return redirect(route('/'))
            ->with('error', 'Unknown error occurred');
    }
    // generic method to add balance

    public function getPaypalPaymentStatus()
    {
        // Get the payment ID clear
        $payment_id = session()->get('paypal_payment_id');
        $user_id = session()->get('user_id');
        $success_url = session()->get('success_url');
        $success_message = session()->get('success_message');
        // clear the session payment ID
        session()->forget('paypal_payment_id');
        session()->forget('success_url');
        session()->forget('user_id');
        if (empty(request()->get('PayerID')) || empty(request()->get('token'))) {
            return redirect($success_url)
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
        $paypal_id = $result->getId();
        if ($result->getState() == 'approved') { // payment made
            $paypalTransactions = $result->getTransactions();
            $total_amount_paid = $paypalTransactions[0]->getAmount()->getTotal();
            // call add money function to add amount
            $add_money_params = [
                'paypal_id' => $paypal_id,
                'amount' => $total_amount_paid,
                'user_id' => $user_id,
                'paypal_payment_status' => $result->getState(),
                'status' => 1
            ];
            // add money
            $walletTransaction = new WalletTransaction();
            $walletTransaction->addMoney($add_money_params);

            return redirect($success_url)
                ->with('success', $success_message);
        }
        return redirect($success_url)
            ->with('error', 'Payment failed');
    }
}
