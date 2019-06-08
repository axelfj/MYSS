<?php

// First, we start the session.
session_start();

// To know if we're coming from login.php or signup.php.
$url    = $_SERVER['REQUEST_URI'];
$flag   = stripos($url, "signup.php");  // If it is a number, then it's on the signup, else we're in the Login.

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

// We set the userToken in the session. 
$_SESSION['userToken'] = null;

try{

    // Now, let's catch his token and save it.
    $userToken = $helper->getAccessToken();
    if (isset($userToken)){
        $_SESSION['userToken'] = (string)$userToken;        // We put it in the session to have it "globally".
    }

} catch (Exception $e){
    echo $e->getTraceAsString();
}

// If we have his access token, then we can make an array with all of his information.
if ($_SESSION['userToken'] != null){

    // Now, we identify if we're logging or registering a new user.
    // Registering.
    if ($flag != false){
        try{

            // We assign the token.
            $facebookAPI->setDefaultAccessToken($_SESSION['userToken']);

            // Asks for: name, birthday, his profile pic.
            $queryResult        = $facebookAPI->get('/me?locale=en_US&fields=name,password');

            // Gets the information of the User.
            $userInformation = $queryResult->getGraphUser();

            var_dump($userInformation);
            echo $userInformation->getField('name');
        } catch (Exception $e){
            echo $e->getTraceAsString();
        }
    }
    // Logging.
    else{
        // We assign the token.
        $facebookAPI->setDefaultAccessToken($_SESSION['userToken']);

        // Asks for: name, birthday, his profile pic.
        $queryResult        = $facebookAPI->get('/me?locale=en_US&fields=name,birthday,profile_pic');

        // Gets the information of the User.
        $userInformation = $queryResult->getGraphUser();

        var_dump($userInformation);
        echo $userInformation->getField('name');

    }




}