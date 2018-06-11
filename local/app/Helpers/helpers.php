<?php


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