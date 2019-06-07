<?php

// First, we start the session.
session_start();

// Includes the Facebook information.
require "vendor/autoload.php";

// We make an instance of Facebook.
/*
 * Notes:
 * → The information in the array is provided by Facebook developers.
 * → It has a limit, don't overcast it.
 */
$facebookAPI = new \Facebook\Facebook([
   'app_id'                 => '476705966414394',
   'app_secret'             => '801157a31bd6e8885a233ee1ffcbd06b',
    'default_graph_version' => 'v2.10'
]);

$helper     = $facebookAPI->getRedirectLoginHelper();
$loginURL   = $helper->getLoginUrl('http://localhost/MYSS/myss/View/login.php'); // Redirects here.

try{

    // Now, let's catch the information.
    $userToken = $helper->getAccessToken();
    if (isset($userToken)){
        $_SESSION['userToken'] = (string)$userToken;
        header("Location:../View/login.php");
    }

} catch (Exception $e){
    echo $e->getTraceAsString();
}

// If we have his accesso token.
if ($_SESSION['userToken']){

    try{
        $facebookAPI->setDefaultAccessToken($_SESSION['userToken']);
        $queryResult = $facebookAPI->get('/me?locale=en_US&fields=name,email');
        $userInformation = $queryResult->getGraphUser();
        echo $userInformation->getField('name');
    } catch (Exception $e){
        echo $e->getTraceAsString();
    }


}