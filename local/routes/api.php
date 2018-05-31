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
	Route::post( '/', 'TicketController@store' );
	Route::get( '/{id}', 'TicketController@show' )->where( 'id', '[0-9]+' );
	Route::put( '/{id}', 'TicketController@update' )->where( 'id', '[0-9]+' );
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
	Route::post( 'post/tip/{application_id}', 'JobsController@postTip' )->name( 'api.post.tip' );
	Route::post( 'confirm/tip/{transaction_id}', 'JobsController@confirmTip' )->name( 'api.confirm.tip' );

	Route::post('cancel/{application_id}', 'JobsController@cancelHiredApplication')->name('api.cancel.job');
} );


Route::group( [ 'namespace' => 'Api', 'middleware' => 'auth:api' ], function () {

	Route::get( '/security-categories', 'JobsController@getSecurityCategories' );
	Route::get( '/business-categories', 'JobsController@getBusinessCategories' );
	Route::get( '/wallet-data', 'WalletController@getWalletData' );
	Route::post( '/find-jobs', 'JobsController@findJobs' )->name( 'api.find.jobs' );
	Route::post( '/job-details', 'JobsController@jobDetailsLocation' )->name( 'api.job.details' );
	Route::post( '/search', 'SearchController@getpersonnelsearch' );

	Route::post('/toggle/favourite/{freelancer_id}', 'JobsController@toggleFavouriteFreelancer')->name('api.toggle.favourite.freelancer');
} );


Route::group( [ 'prefix' => 'wallet', 'namespace' => 'Api' ], function () {

	Route::get( '/jobTrans', 'WalletController@getTransactionsOfJobs' )->middleware( 'auth:api' );
	Route::get( '/details/{id}', 'WalletController@getJobTransactionDetails' )->middleware( 'auth:api' );

} );



// Api's Created By Miraj......


Route::group( [ 'prefix' => 'freelancer', 'namespace' => 'Api', 'middleware' => 'auth:api'], function () {

	Route::get( '/applied/job/list', 'FreelancerJobsController@applyedJobList' );
	Route::get( '/awarded/jobs', 'FreelancerJobsController@awardedJobs' );


	Route::post( '/save/job/{id}', 'FreelancerJobsController@saveJob' );
	Route::get( '/saved/job/list', 'FreelancerJobsController@SaveJobList' );

} );

Route::group( [ 'prefix' => 'employer', 'namespace' => 'Api', 'middleware' => 'auth:api'], function () {


	Route::get( '/awarded/jobs', 'EmployerJobsController@awardedJobs' );


} );

Route::group( ['namespace' => 'Api', 'middleware' => 'auth:api'], function () {

	Route::get( '/job/{id}/applications/list', 'EmployerJobsController@JobApplications' );

	Route::get( '/job/transaction/list', 'WalletController@getTransactionsList' );




} );