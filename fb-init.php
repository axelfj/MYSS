<?php

// First, we start the session.
session_start();

// Includes the Facebook information.
require_once "vendor/autoload.php";

// We make an instance of Facebook.
/*
 * Notes:
 * → The information in the array is provided by Facebook developers.
 * → It has a limit, don't overcast it.
 */
try {
    $facebookAPI = new \Facebook\Facebook([
        'app_id' => '476705966414394',
        'app_secret' => '801157a31bd6e8885a233ee1ffcbd06b',
        'default_graph_version' => 'v3.3'
    ]);
} catch (\Facebook\Exceptions\FacebookSDKException $e) {
}

// We set the userToken in the session.
$_SESSION['userToken'] = null;

$helper = $facebookAPI->getRedirectLoginHelper();
$permissions = ['email', 'user_likes']; // optional
$loginURL = $helper->getLoginUrl('https://myss-qa.herokuapp.com/fb-callback.php'); // Redirects here.

