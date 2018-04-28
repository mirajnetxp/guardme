<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'account','namespace' => 'Api\Auth'], function(){
    Route::post('login','AuthController@apiLogin');
    Route::post('register','AuthController@apiRegister');
    Route::post('auth/social', 'AuthController@apiSocialLogin');

    Route::get('details','AuthController@getAuthUserDetails')->middleware('auth:api');
    Route::get('profile','AuthController@profile')->middleware('auth:api');
    Route::put('profile','AuthController@updateProfile')->middleware('auth:api');
});

Route::group(['prefix' => '/support/tickets', 'middleware' => 'auth:api'], function() {
    Route::get('/', 'TicketController@index');
    Route::post('/', 'TicketController@store');
    Route::get('/{id}', 'TicketController@show')->where('id', '[0-9]+');
    Route::put('/{id}', 'TicketController@update')->where('id', '[0-9]+');
    Route::post('/{id}/messages', 'MessageController@store')->where('id', '[0-9]+');
});


/**
 * 
 * Routes for verfication of users phone numbers
 * 
 */

Route::group(['prefix' => 'verify'], function () {
    Route::post('/otp', 'Api\VerificationController@otp');
    Route::post('/confirm', 'Api\VerificationController@confirm');
    Route::post('/change', 'Api\VerificationController@change');
});