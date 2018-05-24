<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => Responsive\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    
    'twilio' => [
        'from'    => env('TWILIO_FROM'),
        'token'   => env('TWILIO_TOKEN'),
        'account' => env('TWILIO_ACCOUNT'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID','217187885509588'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET','fb90db16e4ceceae760b8c2affdd2765'),
        'redirect' => env('FACEBOOK_REDIRECT','https://guarddme.com/account/login/facebook/callback'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID','62137224865-kh7adl3qg0775kbio5d00bl190d2sim2.apps.googleusercontent.com'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET','wNlLtWJvUV7l1DIhDBWQItzx'),
        'redirect' => env('GOOGLE_REDIRECT','https://guarddme.com/account/login/google/callback'),
    ],

];
