<?php

namespace Responsive\Http\Controllers\Admin;

use Responsive\Http\Controllers\Controller;
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
use Responsive\JobApplication;
use Responsive\Transaction as WalletTransaction;
use Responsive\Job;
use Responsive\User;
use Mail;

class PaypalPaymentController extends Controller {
	use JobsTrait;

	private $_api_context;
	protected $currency = 'GBP';

	public function __construct() {
		// setup PayPal api context
		$paypal_conf        = Config::get( 'paypal' );
		$this->_api_context = new ApiContext( new OAuthTokenCredential( $paypal_conf['client_id'], $paypal_conf['secret'] ) );
		$this->_api_context->setConfig( $paypal_conf['settings'] );
	}

	public function PayplePayoutsAll() {

//		$payouts           = new \PayPal\Api\Payout();
//		$senderBatchHeader = new \PayPal\Api\PayoutSenderBatchHeader();
//
//		$senderBatchHeader->setSenderBatchId( uniqid() )
//		                  ->setEmailSubject( "You have a payment" );
//
//		$senderItem2 = new \PayPal\Api\PayoutItem();
//		$senderItem2->setRecipientType( 'Email' )
//		            ->setNote( 'Thanks you.' )
//		            ->setReceiver( 'shirt-supplier-one@gmail.com' )
//		            ->setSenderItemId( "item_1" . uniqid() )
//		            ->setAmount( new \PayPal\Api\Currency( '{
//                        "value":"0.99",
//                        "currency":"GBP"
//                    }' ) );
//
//		$senderItem1 = new \PayPal\Api\PayoutItem();
//		$senderItem1->setRecipientType( 'Email' )
//		            ->setNote( 'Thanks you.' )
//		            ->setReceiver( 'shirt-supplier-one@gmail.com' )
//		            ->setSenderItemId( "item_1" . uniqid() )
//		            ->setAmount( new \PayPal\Api\Currency( '{
//                        "value":"0.99",
//                        "currency":"GBP"
//                    }' ) );
//
//		$senderItem3 = new \PayPal\Api\PayoutItem(
//			array(
//				"recipient_type" => "EMAIL",
//				"receiver"       => "shirt-supplier-three@mail.com",
//				"note"           => "Thank you.",
//				"sender_item_id" => uniqid(),
//				"amount"         => array(
//					"value"    => "0.90",
//					"currency" => "GBP"
//				)
//
//			)
//		);
//
//		$payouts->setSenderBatchHeader( $senderBatchHeader )
//		        ->addItem( $senderItem1 )->addItem( $senderItem2 )->addItem( $senderItem3 );
//
//
//		$request = clone $payouts;
//		try {
//			$output = $payouts->create( null, $this->_api_context );
//		} catch ( Exception $ex ) {
//			ResultPrinter::printError( "Created Batch Payout", "Payout", null, $request, $ex );
//			exit( 1 );
//		}
//
//		ResultPrinter::printResult( "Created Batch Payout", "Payout", $output->getBatchHeader()->getPayoutBatchId(), $request, $output );
//
//		return $output;

//		$data1 = [
//			'data1' => 'value_1',
//			'data2' => 'value_2',
//		];
//
//		$paypal_conf = Config::get('paypal');
//		$auth=new ApiContext( new OAuthTokenCredential( $paypal_conf['client_id'], $paypal_conf['secret'] ) );
//		$auth->setConfig( $paypal_conf['settings'] );
//		dd($auth);
//
//		$curl = curl_init();
//
//		curl_setopt_array( $curl, array(
//			CURLOPT_URL            => "https://api.sandbox.paypal.com/v1/payments/payouts",
//			CURLOPT_RETURNTRANSFER => true,
//			CURLOPT_ENCODING       => "",
//			CURLOPT_MAXREDIRS      => 10,
//			CURLOPT_TIMEOUT        => 30000,
//			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
//			CURLOPT_CUSTOMREQUEST  => "POST",
//			CURLOPT_POSTFIELDS     => json_encode( $data1 ),
//			CURLOPT_HTTPHEADER     => array(
//				// Set here requred headers
//				"accept: */*",
//				"accept-language: en-US,en;q=0.8",
//				"content-type: application/json",
//			),
//		) );
//
//		$response = curl_exec( $curl );
//		$err      = curl_error( $curl );
//
//		curl_close( $curl );
//
//		if ( $err ) {
//			echo "cURL Error #:" . $err;
//		} else {
//			print_r( json_decode( $response ) );
//		}

	}
}
