<?php
//TODO : Delete this on production
use Responsive\Channels\SMS;
use Responsive\Http\Repositories\UsersRepository;
use Responsive\User;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/*Route::get('/index', 'CommonController@home');
Route::get('/', 'CommonController@home');*/



//TODO : Delete this method on production
Route::get( '/user-de', function (){
	$u=User::all();
	return response()->json($u);
});


Route::get( '/notification-s', function (){
	$optionBuilder = new OptionsBuilder();
	$optionBuilder->setTimeToLive(60*20);

	$notificationBuilder = new PayloadNotificationBuilder('my title');
	$notificationBuilder->setBody('Hello world from laravel by miraj')
	                    ->setSound('default');

	$dataBuilder = new PayloadDataBuilder();
	$dataBuilder->addData(['a_data' => 'my_data']);

	$option = $optionBuilder->build();
	$notification = $notificationBuilder->build();
	$data = $dataBuilder->build();

	$token = "eHHzHYMa7U4:APA91bH33HKox5ZVMBDegfHQATB4yraCKrMOVn4F9ojWLeAVKBAcInD1dZ73O0L0LpMnymeHW2DrfM7gWvV_M5TXI7fOKQAorqNxIXk_C532G26FFLEg8fecYkZ5luoStIu6fEdOiQm5";

	$downstreamResponse = FCM::sendTo($token, $option, $notification, $data);



	return response()->json(
		[
			"numberSuccess"=>$downstreamResponse->numberSuccess(),
			"numberFailure"=>$downstreamResponse->numberFailure(),
			"numberModification"=>$downstreamResponse->numberModification(),
		]
	);
});



//social login
Route::group( [ 'prefix' => 'account', 'namespace' => 'Auth' ], function () {
	Route::get( 'login/{provider}', 'SocialAuthController@socialLogin' )
	     ->where( 'provider', 'google|facebook' );

	Route::get( 'login/{provider}/callback', 'SocialAuthController@handleProviderCallback' )
	     ->where( 'provider', 'google|facebook' );

	Route::post( 'login/{provider}/callback', 'SocialAuthController@completeSocialAuth' )
	     ->where( 'provider', 'google|facebook' );
} );


Route::get( '/', 'IndexController@sangvish_index' );
Route::get( '/referral', 'ReferralController@index' );
Route::get( '/redeem', 'ReferralController@redeem' );
Route::get( '/redeem/{id}', 'ReferralController@checkout' );
Route::get( '/index', 'IndexController@sangvish_index' );

Route::get( 'searchajax', array( 'as' => 'searchajax', 'uses' => 'IndexController@sangvish_autoComplete' ) );


Route::get( 'dateavailable/{val}', array( 'as' => 'dateavailable', 'uses' => 'BookingController@dateavailable' ) );


Route::get( '/logout', 'DashboardController@sangvish_logout' );
Route::get( '/delete_account', 'DashboardController@sangvish_delaccount' );
Route::post( '/delete-account', 'DashboardController@sangvish_deleteaccount' );
Route::post( '/dashboard', [ 'as' => 'dashboard', 'uses' => 'DashboardController@sangvish_edituserdata' ] );

Route::get( '/account', 'ShopController@sangvish_viewshop' );

Route::get( '/verification', 'ShopController@sangvish_verification' );
Route::get( '/account-old', 'ShopController@sangvish_viewshop_old' );

Route::get( '/addcompany', 'ShopController@sangvish_addshop' );

Route::get( '/editshop/{id}', 'ShopController@sangvish_editshop' );

Route::get( '/company', 'ShopController@editcompany' );
Route::post( '/updatecompany', [ 'as' => 'update-company', 'uses' => 'ShopController@updatecompany' ] );

Route::post( '/editshop', [ 'as' => 'editshop', 'uses' => 'ShopController@sangvish_savedata' ] );

Route::post( '/addcompany', [ 'as' => 'addshop', 'uses' => 'ShopController@sangvish_savedata' ] );


Route::get( '/vendor/{id}', 'VendorController@sangvish_showpage' );
Route::post( '/vendor', [ 'as' => 'vendor', 'uses' => 'VendorController@sangvish_savedata' ] );


Route::get( '/booking/{shop_id}/{service_id}/{userid}', 'BookingController@sangvish_showpage' );
Route::post( '/booking', [ 'as' => 'booking', 'uses' => 'BookingController@sangvish_savedata' ] );


Route::get( '/booking_info', 'BookinginfoController@sangvish_viewbook' );

Route::post( '/booking_info', [ 'as' => 'booking_info', 'uses' => 'PaymentController@sangvish_showpage' ] );

Route::get( '/payment/{sum_val}/{admin_email}', 'PaymentController@sangvish_showpage' );


Route::get( '/success/{cid}', 'SuccessController@sangvish_showpage' );


Route::get( '/cancel', 'CancelController@sangvish_showpage' );

Route::get( '/myorder', 'MyorderController@sangvish_showpage' );

Route::get( '/myorder/{id}', 'MyorderController@sangvish_destroy' );


Route::get( '/my_bookings', 'MybookingsController@sangvish_showpage' );


Route::post( '/my_bookings', [ 'as' => 'my_bookings', 'uses' => 'MybookingsController@sangvish_savedata' ] );


Route::get( '/wallet', 'WalletController@show' );
Route::get( '/wallet/jobs/find', 'WalletController@searchJobs' );
Route::get( '/wallet/invoice/{id}', 'WalletController@invoice' );
Route::get( '/wallet-dashboard', 'WalletController@view' );
Route::get( '/invoice', 'WalletController@freelancerInvoice' );
Route::get( '/invoicepdf/{id}', 'WalletController@InvoicePDF' );
Route::get( '/invoicepdf-child/{id}/{user_id}', 'WalletController@InvoiceChildPDF' );

Route::post( '/wallet', [ 'as' => 'wallet', 'uses' => 'WalletController@sangvish_savedata' ] );

// Settings
Route::get( '/settings', 'SettingController@show' );
Route::get( '/settings/visibality', 'SettingController@visibality' );

/* Authentication routes */
Auth::routes();


/* User Verification */
Route::get( '/user/confirmation', 'Auth\VerificationController@getConfirmation' )
     ->name( 'user.email_confirmation' );
Route::get( '/user/verification/{token}', 'Auth\VerificationController@getVerification' )
     ->name( 'user.verify_email' );
Route::get( '/user/resend_verification', 'Auth\VerificationController@getResendVerification' )
     ->name( 'user.resend_verification' );

/* API of User Verification */
// Use with 'uid' get parameter (eg.: /api/user/verification/status?uid=12)
Route::get( '/api/user/verification/status', 'Api\Auth\VerificationController@getVerificationStatus' )
     ->name( 'api.user.verification_status' );
Route::post( '/api/user/verified', 'Api\Auth\VerificationController@postVerified' )
     ->name( 'api.user.verified' );
Route::post( '/api/user/unverified', 'Api\Auth\VerificationController@postUnverified' )
     ->name( 'api.user.unverified' );

Route::get( '/about', 'PageController@sangvish_about' );

Route::get( '/404', 'PageController@sangvish_404' );


Route::get( '/how-it-works', 'PageController@sangvish_howit' );

Route::get( '/safety', 'PageController@sangvish_safety' );

Route::get( '/service-guide', 'PageController@sangvish_guide' );

Route::get( '/how-to-pages', 'PageController@sangvish_topages' );


Route::get( '/success-stories', 'PageController@sangvish_stories' );


Route::get( '/terms-conditions', 'PageController@sangvish_terms' );

Route::get( '/privacy-policy', 'PageController@sangvish_privacy' );

Route::get( '/contact', 'PageController@sangvish_contact' );

Route::post( '/contact', [ 'as' => 'contact', 'uses' => 'PageController@sangvish_mailsend' ] );


Route::get( '/post-job', 'ServicesController@sangvish_index' );
Route::get( '/post-job/{id}', 'ServicesController@sangvish_editdata' );

Route::post( '/post-job', [ 'as' => 'services', 'uses' => 'ServicesController@sangvish_savedata' ] );
Route::get( '/post-job/{did}/delete', 'ServicesController@sangvish_destroy' );


Route::get( '/gallery', 'GalleryController@sangvish_index' );
Route::post( '/gallery', [ 'as' => 'gallery', 'uses' => 'GalleryController@sangvish_savedata' ] );
Route::get( '/gallery/{id}', 'GalleryController@sangvish_editdata' );
Route::get( '/gallery/{did}/delete', 'GalleryController@sangvish_destroy' );


Route::get( '/search/{id}', 'SearchController@sangvish_homeindex' );

//Route::post('/search', ['as'=>'search','uses'=>'SearchController@sangvish_index']);
Route::get( '/shopsearch', 'SearchController@sangvish_view' );
Route::post( '/shopsearch', [ 'as' => 'shopsearch', 'uses' => 'SearchController@sangvish_search' ] );

/****************** new search functionality(added by Adnan)****/
Route::get( '/search', 'SearchController@getpersonnelsearch' );

Route::post( '/search', [ 'as' => 'post-personnel-search', 'uses' => 'SearchController@postpersonnelsearch' ] );


Route::get( '/personnel-profile/{id}', [ 'as' => 'person-profile', 'uses' => 'SearchController@personnelprofile' ] );

/************************* End Adnan code**************/


Route::get( '/subservices', 'SubservicesController@sangvish_index' );

Route::get( '/subservices/{id}', 'SubservicesController@sangvish_servicefind' );


/* Route::group(['namespace' => 'Admin', 'middleware' => 'admin'], function() {*/

Route::group( [ 'middleware' => 'admin' ], function () {

	Route::get( '/admin', 'Admin\DashboardController@index' );
	Route::get( '/admin/referral/items', 'Admin\ReferralController@index' );
	Route::post( '/admin/referral/item/create', [ 'as'   => 'admin.itemcreate',
	                                              'uses' => 'Admin\ReferralController@create'
	] );
	Route::post( '/admin/users/balance/add', [ 'as'   => 'admin.addbalance',
	                                           'uses' => 'Admin\ReferralController@balance'
	] );
	Route::get( '/admin/index', 'Admin\DashboardController@index' );

	/* message */
	Route::get('admin/message','admin\MessageController@index');
	Route::post('admin/message','admin\MessageController@sendMessage');
	/* end message */

	/* user */
	Route::get( '/admin/users', 'Admin\UsersController@index' );
	Route::get( '/admin/adduser', 'Admin\AdduserController@formview' );
	Route::post( '/admin/adduser', [ 'as' => 'admin.adduser', 'uses' => 'Admin\AdduserController@adduserdata' ] );

	Route::get( '/admin/users/{id}', 'Admin\UsersController@destroy' );
	Route::get( '/admin/edituser/{id}', 'Admin\EdituserController@showform' );
	Route::post( '/admin/edituser', [ 'as' => 'admin.edituser', 'uses' => 'Admin\EdituserController@edituserdata' ] );
	Route::get( 'admin/phone-status/{id}', 'Admin\EdituserController@phone_status' );
	Route::get( 'admin/email-status/{id}', 'Admin\EdituserController@email_status' );
	/* end user */


	/* services */
	Route::get( '/admin/services', 'Admin\ServicesController@index' );
	Route::get( '/admin/addservice', 'Admin\AddserviceController@formview' );
	Route::post( '/admin/addservice', [ 'as'   => 'admin.addservice',
	                                    'uses' => 'Admin\AddserviceController@addservicedata'
	] );
	Route::get( '/admin/services/{id}', 'Admin\ServicesController@destroy' );
	Route::get( '/admin/editservice/{id}', 'Admin\EditserviceController@showform' );
	Route::post( '/admin/editservice', [ 'as'   => 'admin.editservice',
	                                     'uses' => 'Admin\EditserviceController@editservicedata'
	] );

	/* end services */


	/* sub services */

	Route::get( '/admin/subservices', 'Admin\SubservicesController@index' );
	Route::get( '/admin/addsubservice', 'Admin\AddsubserviceController@formview' );
	Route::get( '/admin/addsubservice', 'Admin\AddsubserviceController@getservice' );
	Route::post( '/admin/addsubservice', [ 'as'   => 'admin.addsubservice',
	                                       'uses' => 'Admin\AddsubserviceController@addsubservicedata'
	] );
	Route::get( '/admin/subservices/{id}', 'Admin\SubservicesController@destroy' );


	Route::get( '/admin/editsubservice/{id}', 'Admin\EditsubserviceController@edit' );

	Route::post( '/admin/editsubservice', [ 'as'   => 'admin.editsubservice',
	                                        'uses' => 'Admin\EditsubserviceController@editsubservicedata'
	] );
	/* end sub services */


	/* Testimonials */

	Route::get( '/admin/testimonials', 'Admin\TestimonialsController@index' );
	Route::get( '/admin/add-testimonial', 'Admin\AddtestimonialController@formview' );
	Route::post( '/admin/add-testimonial', [ 'as'   => 'admin.add-testimonial',
	                                         'uses' => 'Admin\AddtestimonialController@addtestimonialdata'
	] );
	Route::get( '/admin/testimonials/{id}', 'Admin\TestimonialsController@destroy' );
	Route::get( '/admin/edit-testimonial/{id}', 'Admin\EdittestimonialController@showform' );
	Route::post( '/admin/edit-testimonial', [ 'as'   => 'admin.edit-testimonial',
	                                          'uses' => 'Admin\EdittestimonialController@testimonialdata'
	] );


	/* end Testimonials */


	/* pages */

	Route::get( '/admin/pages', 'Admin\PagesController@index' );
	Route::get( '/admin/edit-page/{id}', 'Admin\PagesController@showform' );
	Route::post( '/admin/edit-page', [ 'as' => 'admin.edit-page', 'uses' => 'Admin\PagesController@pagedata' ] );

	/* end pages */


	/* start settings */


	Route::get( '/admin/settings', 'Admin\SettingsController@showform' );
	Route::post( '/admin/settings', [ 'as' => 'admin.settings', 'uses' => 'Admin\SettingsController@editsettings' ] );

	/* end settings */

	/* start user doc Verification */
	Route::get( '/admin/Verification', 'Admin\VerificationController@showUsers' );
	Route::get( '/admin/Verification/uder-details/{id}', 'Admin\VerificationController@userDetail' )->name( 'admin.Verification.details' );
	Route::get( '/admin/user-doc/approved/{id}', 'Admin\VerificationController@userDocApproved' )->name( 'admin.user.doc.approved' );
	Route::get( '/admin/user-doc/decline/{id}', 'Admin\VerificationController@userDocDecline' )->name( 'admin.user.doc.decline' );

	/* end user doc Verification */

	/* start shop */

	Route::get( '/admin/shop', 'Admin\ShopController@index' );
	Route::get( '/admin/edit-shop/{id}', 'Admin\ShopController@showform' );
	Route::post( '/admin/edit-shop', [ 'as' => 'admin.edit-shop', 'uses' => 'Admin\ShopController@savedata' ] );
	Route::get( '/admin/shop/{id}', 'Admin\ShopController@destroy' );

	Route::get( '/admin/suspend/{id}', 'Admin\ShopController@suspend' );
	Route::get( '/admin/unsuspend/{id}', 'Admin\ShopController@unsuspend' );

	/* end shop */


	/* booking history */

	Route::get( '/admin/booking', 'Admin\BookingController@index' );
	Route::get( '/admin/booking/{id}', 'Admin\BookingController@destroy' );

	/*  end booking history */


	/* withdraw */

	Route::get( '/admin/pending_withdraw', 'Admin\WithdrawController@index' );
	Route::get( '/admin/pending_withdraw/{id}', 'Admin\WithdrawController@update' );
	Route::get( '/admin/completed_withdraw', 'Admin\WithdrawController@doneindex' );

	/* end withdraw */


} );


Route::group( [ 'middleware' => 'web' ], function () {

	Route::get( '/dashboard', 'DashboardController@index' );


} );


/**
 * Start Tickets Module
 */


Route::group( [ 'prefix' => '/support/tickets', 'middleware' => 'auth' ], function () {
	Route::get( '/', 'TicketController@index' )->name( 'ticket.index' );
	Route::get( '/create', 'TicketController@create' )->name( 'ticket.create' );
	Route::post( '/', 'TicketController@store' )->name( 'ticket.store' );
	Route::post( '/', 'TicketController@close_account_store' )->name( 'ticket.close_account' );
	Route::get( '/{id}', 'TicketController@show' )->where( 'id', '[0-9]+' )
	     ->name( 'ticket.show' );
	Route::put( '/{id}', 'TicketController@update' )->where( 'id', '[0-9]+' )
	     ->name( 'ticket.update' );
	Route::post( '/{id}/messages', 'MessageController@store' )->where( 'id', '[0-9]+' )
	     ->name( 'tickets.messages.store' );
} );

/*Start Security Jobs Routes*/

Route::group( [ 'prefix' => '/jobs', 'middleware' => 'auth' ], function () {
	Route::get( '/create', 'JobsController@create' )->name( 'job.create' );
	Route::get( '/schedule/{id}', 'JobsController@schedule' )->name( 'job.schedule' );
	Route::get( '/broadcast/{id}', 'JobsController@broadcast' )->name( 'job.broadcast' );
	Route::get( '/payment-details/{id}', 'JobsController@paymentDetails' )->name( 'job.payment.details' );

	Route::post( '/create-paypal-payment/{id}', 'PaypalPaymentController@postPayment' )->name( 'create.paypal.payment' );
	Route::get( '/payment-status', 'PaypalPaymentController@getPaymentStatus' )->name( 'payment.status' );

	Route::get( '/job-confirmation', 'JobsController@confirmation' )->name( 'job.confirmation' );
	Route::get( '/my', 'JobsController@myJobs' )->name( 'my.jobs' );
	Route::get( '/myfilter', 'JobsController@myJob' )->name( 'my.job' );
	Route::get( '/saved', 'JobsController@savedJobs' )->name( 'saved.jobs' );
	Route::get( '/my/applications/{id}', 'JobsController@myJobApplications' )->name( 'my.job.applications' );
	Route::get( '/application/{id}/{u_id}', 'JobsController@viewApplication' )->name( 'view.application' );

	Route::get( '/payment/requests', 'JobsController@paymentRequests' )->name( 'payment.requests' );
	Route::get( '/payment/request/details/{id}', 'JobsController@paymentRequestDetails' )->name( 'payment.request.details' );
	Route::get( '/application/payment/request/{application_id}', 'JobsController@applicationPaymentRequest' )->name( 'application.payment.request' );

	Route::get( '/apply/{id}', 'JobsController@applyJob' )->name( 'apply.job' );
	Route::get( '/proposals', 'JobsController@myProposals' )->name( 'my.proposals' );
	Route::get( '/proposal', 'JobsController@myProposal' )->name( 'my.proposal' );
	Route::get( '/view/application/{app_id}/{job_id}', 'JobsController@myApplicationView' )->name( 'my.application.view' );
	Route::get( '/save/{id}', 'JobsController@saveJobsToProfile' );
	Route::get( '/remove/{id}', 'JobsController@removeJobsFromProfile' );
	Route::get( '/leave/feedback/{application_id}', 'JobsController@leaveFeedback' )->name( 'leave.feedback' );
	Route::get( '/tip/{application_id}', 'JobsController@giveTip' )->name( 'give.tip' );
	Route::get( '/tip/details/{transaction_id}', 'JobsController@tipDetails' )->name( 'tip.details' );
} );
//
Route::group( [ 'middleware' => 'auth' ], function () {
	// favourite freelancers related route.
	Route::get( '/my/favourite/freelancers', 'JobsController@favouriteFreelancers' )->name( 'my.favourite.freelancers' );
	// Teams related route
	Route::get( '/my/teams', 'TeamsController@myTeams' )->name( 'my.teams' );
	Route::get( '/teams/create', 'TeamsController@createTeam' )->name( 'create.team' );
	Route::get( '/team/detail/{team_id}', 'TeamsController@showTeam' )->name( 'team.detail' );
} );

Route::post( '/add-balance-via-paypal', 'PaypalPaymentController@addMoneyPaypal' )->name( 'add.money.paypal' )->middleware( 'auth' );
Route::get( '/get-paypal-payment-status', 'PaypalPaymentController@getPaypalPaymentStatus' )->name( 'get.paypal.payment.status' )->middleware( 'auth' );

// Guest route for find job
Route::get( '/jobs/posted/view', 'JobsController@myJobPostView' )->name( 'posted.jobs.view' );
Route::get( '/jobs/find', 'JobsController@findJobs' )->name( 'find.jobs' );
Route::post( '/jobs/find', 'JobsController@postfindJobs' )->name( 'post.find.jobs' );
Route::get( '/job/{id}', 'JobsController@viewJob' )->name( 'view.job' );

Route::get( '/phone', 'VerificationController@phone' )->middleware( 'auth' );

Route::get( '/jobs/favourites/{id}', 'JobsController@getFavouriteJobs' )->name( 'favourites.jobs' );
Route::post( '/jobs/favourites/{id}', 'JobsController@postFavouriteJobs' )->name( 'post.favorites.jobs' );
Route::get( '/jobs/{id}', 'JobsController@getJobs' )->name( 'get.jobs' );
Route::post( '/jobs/{id}', 'JobsController@postJobs' )->name( 'post.jobs' );


Route::get( '/test', 'test@getTransactionsOfJobs' );
Route::get( '/test2/{id}', 'test@getJobTransactionDetails' );


