<?php

use Illuminate\Http\Request;


Route::middleware( 'auth:api' )->get( '/user', function ( Request $request ) {
	return $request->user();
} );

Route::get( '/search', 'Api\SearchController@getpersonnelsearch' );
Route::get( '/search/{id}', 'Api\SearchController@personnelprofile' );


Route::group( [ 'prefix' => 'account', 'namespace' => 'Api\Auth' ], function () {
	Route::post( 'login', 'AuthController@apiLogin' );
	Route::post( 'register', 'AuthController@apiRegister' );
	Route::post( 'auth/social', 'AuthController@apiSocialLogin' );

	Route::get( 'details', 'AuthController@getAuthUserDetails' )->middleware( 'auth:api' );
	Route::get( 'profile', 'AuthController@profile' )->middleware( 'auth:api' );
	Route::put( 'profile', 'AuthController@updateProfile' )->middleware( 'auth:api' );
} );

Route::group( [ 'prefix' => '/support/tickets', 'namespace' => 'Api', 'middleware' => 'auth:api' ], function () {
	Route::get( '/', 'TicketController@index' );
	Route::post( '/', 'TicketController@store' )->name( 'api.store.ticket' );
	Route::get( '/{id}', 'TicketController@show' )->where( 'id', '[0-9]+' );
	Route::put( '/{id}', 'TicketController@update' )->where( 'id', '[0-9]+' );
	Route::get( '/open', 'TicketController@openTickets' );

	Route::post( '/{id}/messages', 'MessageController@store' )->where( 'id', '[0-9]+' );
} );


/**
 *
 * Routes for verfication of users phone numbers
 *
 */

Route::group( [ 'prefix' => 'verify' ], function () {
	Route::post( '/otp', 'Api\VerificationController@otp' );
	Route::post( '/confirm', 'Api\VerificationController@confirm' );
	Route::post( '/change', 'Api\VerificationController@change' );
} );

/*Routes for jobs*/

Route::group( [ 'prefix' => 'jobs', 'namespace' => 'Api', 'middleware' => 'auth:api' ], function () {

	Route::get( 'awarded', 'JobsController@totalUserAwardedJobs' );
	Route::get( 'applied', 'JobsController@totalAppliedJobsForUser' );
	Route::get( 'published', 'JobsController@totalCreatedJobsForEmployer' );

	Route::get( 'open', 'JobsController@allOpenJobs' );


	Route::post( 'create', 'JobsController@create' )->name( 'api.create.job' );
	Route::post( 'schedule/{id}', 'JobsController@schedule' )->name( 'api.schedule.job' );
	Route::post( 'broadcast/{id}', 'JobsController@broadcast' )->name( 'api.broadcast.job' );
	Route::post( 'calculate-job-amount/{id}', 'JobsController@getJobAmount' )->name( 'api.amount.job' );

	// add balance to wallet
	Route::post( 'add-money', 'JobsController@addMoney' )->name( 'api.add.money' );

	// activate job, it will add 3 credit entries i) job fee ii) admin fee iii) vat fee
	Route::post( 'activate-job/{id}', 'JobsController@activateJob' )->name( 'api.activate.job' );
	Route::post( 'apply/{id}', 'JobsController@applyJob' )->name( 'api.apply.job' );
	Route::post( 'mark/hired/{id}', 'JobsController@markHired' )->name( 'api.mark.hired' );


	Route::get( 'my', 'JobsController@myJobs' )->name( 'api.my.jobs' );
	Route::get( 'proposals', 'JobsController@myProposals' )->name( 'api.my.proposals' );
	Route::post( 'mark-application-as-complete/{id}', 'JobsController@markApplicationAsComplete' )->name( 'api.mark.application.complete' );
	Route::post( 'leave/feedback/{application_id}', 'JobsController@leaveFeedback' )->name( 'api.leave.feedback' );

	// Tip Api
	Route::get( '/tip/{application_id}', 'JobsController@giveTip' );  // Step -1
	Route::post( 'post/tip/{application_id}', 'JobsController@postTip' )->name( 'api.post.tip' ); // Step -2
	Route::get( '/tip/details/{transaction_id}', 'JobsController@tipDetails' ); // Step -3
	Route::post( 'confirm/tip/{transaction_id}', 'JobsController@confirmTip' )->name( 'api.confirm.tip' );// Step -4

	Route::post( 'cancel/{application_id}', 'JobsController@cancelHiredApplication' )->name( 'api.cancel.job' );

	// payment request from freelancer

	Route::post( 'create/payment/request', 'JobsController@createPaymentRequest' )->name( 'api.create.payment.request' );
	Route::post( 'approve/payment/request/{payment_request_id}', 'JobsController@approvePaymentRequest' )->name( 'api.approve.payment.request' );
	Route::get( 'api/jobs/payment/request/list', 'JobsController@paymentRequests' );


	Route::post( '/pause/{job_id}', 'JobsController@pauseJob' )->name( 'api.pause.job' );
	Route::post( '/restart/{job_id}', 'JobsController@restartJob' )->name( 'api.restart.job' );

} );


Route::group( [ 'namespace' => 'Api', 'middleware' => 'auth:api' ], function () {

	Route::get( '/security-categories', 'JobsController@getSecurityCategories' );
	Route::get( '/business-categories', 'JobsController@getBusinessCategories' );
	Route::get( '/wallet-data', 'WalletController@getWalletData' );
	Route::post( '/find-jobs', 'JobsController@findJobs' )->name( 'api.find.jobs' );
	Route::post( '/job-details', 'JobsController@jobDetailsLocation' )->name( 'api.job.details' );
	Route::post( '/search', 'SearchController@getpersonnelsearch' );

	Route::post( '/toggle/favourite/{freelancer_id}', 'JobsController@toggleFavouriteFreelancer' )->name( 'api.toggle.favourite.freelancer' );
	Route::get( '/favourite/freelancers', 'JobsController@favouriteFreelancers' );
	Route::get( '/total/favorites/freelancer', 'JobsController@totaoFF' );
	// Teams specific routes
	Route::post( '/team/create', 'TeamsController@create' )->name( 'api.team.create' );
	Route::get( '/get/teams', 'TeamsController@getAllteam' );
	Route::get( '/total/team', 'TeamsController@totaoTeam' );
	Route::post( '/team/add/member', 'TeamsController@addMember' )->name( 'api.add.member.to.team' );

	//tracking Route
	Route::post( '/post/tracking', 'TrackingController@postTracking' );


} );


Route::group( [ 'prefix' => 'wallet', 'namespace' => 'Api' ], function () {

	Route::get( '/jobTrans', 'WalletController@getTransactionsOfJobs' )->middleware( 'auth:api' );
	Route::get( '/details/{id}', 'WalletController@getJobTransactionDetails' )->middleware( 'auth:api' );

} );


// Api's Created By Miraj......


Route::group( [ 'prefix' => 'freelancer', 'namespace' => 'Api', 'middleware' => 'auth:api' ], function () {

	Route::get( '/applied/job/list', 'FreelancerJobsController@applyedJobList' );
	Route::get( '/awarded/jobs', 'FreelancerJobsController@awardedJobs' );

	Route::get( '/job/decline/{application_id} ', 'FreelancerJobsController@JobDecline' );

	Route::get( '/withdraw/{application_id}', 'FreelancerJobsController@withdrawApplication' );

	Route::post( '/save/job/{id}', 'FreelancerJobsController@saveJob' );
	Route::get( '/saved/job/list', 'FreelancerJobsController@SaveJobList' );

	Route::get( '/average/feedback/{id}', 'FreelancerJobsController@averageFeedback' );

	Route::get( '/open/job/applications', 'FreelancerJobsController@openJobApplications' );


	Route::get( '/settings/visibility', 'FreelancerJobsController@visibility' );
	Route::post( '/settings/visibility/toggle', 'FreelancerJobsController@SetVisibility' );

} );

Route::group( [ 'prefix' => 'employer', 'namespace' => 'Api', 'middleware' => 'auth:api' ], function () {

	Route::get( '/job/decline/{application_id} ', 'EmployerJobsController@JobDecline' );

	Route::post( '/award/job/to/{application_id}', 'EmployerJobsController@awardTo' );


	Route::get( '/awarded/jobs', 'EmployerJobsController@awardedJobs' );

	Route::get( '/open/awarded/jobs', 'EmployerJobsController@awardedOpenJobs' );

	Route::get( '/wallet/invoice/{job_id}', 'EmployerJobsController@invoice' );


} );

Route::group( [ 'namespace' => 'Api', 'middleware' => 'auth:api' ], function () {

	Route::get( '/job/{id}/applications/list', 'EmployerJobsController@JobApplications' );
	Route::get( '/job/transaction/list', 'WalletController@getTransactionsList' );
	Route::get( '/job/transaction/list', 'WalletController@getTransactionsList' );


	Route::get( 'referrals/list', 'ReferralController@getReferralList' );

	//redeem
	Route::get( '/redeem', 'ReferralController@redeem' );
	Route::get( '/redeem/{id}', 'ReferralController@checkout' );
	Route::get( '/remain/points', 'ReferralController@remainPoints' );
	Route::get( '/points/spent', 'ReferralController@pointsSpend' );
	Route::get( '/items/bought', 'ReferralController@boughtItems' );

	//FCM
	Route::post( '/fcm/token', 'FcmController@StoreToken' );

} );

	//notification
Route::group( [ 'prefix' => 'notification', 'namespace' => 'Api', 'middleware' => 'auth:api' ], function () {
	Route::get( '/unread ', 'NotificationController@unread' );
	Route::get( '/mark/as/read ', 'NotificationController@markAsRead' );

} );

Route::post( '/add-balance-via-paypal', 'PaypalPaymentController@addMoneyPaypal' );

// Company api
Route::group( [ 'namespace' => 'Api', 'middleware' => 'auth:api' ], function () {

	Route::get( '/get/company/details', 'ShopController@getDetails' );
	Route::post( '/update/company/details', 'ShopController@updatecompany' );


});

