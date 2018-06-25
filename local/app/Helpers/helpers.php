<?php

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;


function getUserType() {
    return auth()->user()->admin;
}

function getUserTypeString() {
    $type = auth()->user()->admin;
    $typeString = '';
    if ($type == 1) {
        $typeString = 'admin';
    } else if($type == 0) {
        $typeString = 'employer';
    } else if ($type == 2) {
        $typeString = 'freelancer';
    }
    return $typeString;
}

function isAdmin() {
    if (auth()->user()->admin == 1) {
        return true;
    }
    return false;
}

function isEmployer() {
    if (auth()->user()->admin == 0) {
        return true;
    }
    return false;
}

function isFreelancer() {
    if (auth()->user()->admin == 2) {
        return true;
    }
    return false;
}

/**
 * @param $snake
 * @return string
 */
function snakeToString($snake) {
    $string = str_replace('_', ' ', $snake);
    return ucwords($string);
}

/**
 * Sort associative array by value(e.g)
[149438] => Array
(
[Name] => Rients, Hollis
[score] => 11
)

[149436] => Array
(
[Name] => Pick, Chi
[score] => 13
)
we can sort this type of associative array by key according to value. here score, Name is key and this function will sort according to their value
 *
 * @param array $array
 * @param $key
 * @param bool|true $asc
 * @return array
 */
function sortByOneKey(array $array, $key, $asc = true)
{
    $result = array();
    $values = array();
    foreach ($array as $id => $value) {
        $values[$id] = isset($value[$key]) ? $value[$key] : '';
    }
    if ($asc) {
        asort($values, SORT_NATURAL | SORT_FLAG_CASE);
    } else {
        arsort($values, SORT_NATURAL | SORT_FLAG_CASE);
    }
    foreach ($values as $index => $value) {
        $result[$index] = $array[$index];
    }

    return $result;
}


/**
 * @param $title
 * @param $body
 * @param $token
 */
function SendNotification($title,$body,$token){

	$optionBuilder = new OptionsBuilder();
	$optionBuilder->setTimeToLive(60*20);

	$notificationBuilder = new PayloadNotificationBuilder($title);
	$notificationBuilder->setBody($body)
	                    ->setSound('default');

	$dataBuilder = new PayloadDataBuilder();
	$dataBuilder->addData(['a_data' => 'my_data']);

	$option = $optionBuilder->build();
	$notification = $notificationBuilder->build();
	$data = $dataBuilder->build();

	$downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
}