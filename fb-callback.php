<?php
session_start();
require_once ('vendor/autoload.php');

try {
    $facebookAPI = new \Facebook\Facebook([
        'app_id' => '476705966414394',
        'app_secret' => '801157a31bd6e8885a233ee1ffcbd06b',
        'default_graph_version' => 'v3.3'
    ]);
} catch (\Facebook\Exceptions\FacebookSDKException $e) {
}

$helper = $facebookAPI->getRedirectLoginHelper();

try {
    $userToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}

if (! isset($userToken)) {
    if ($helper->getError()) {
        header('HTTP/1.0 401 Unauthorized');
        echo "Error: " . $helper->getError() . "\n";
        echo "Error Code: " . $helper->getErrorCode() . "\n";
        echo "Error Reason: " . $helper->getErrorReason() . "\n";
        echo "Error Description: " . $helper->getErrorDescription() . "\n";
    } else {
        header('HTTP/1.0 400 Bad Request');
        echo 'Bad request';
    }
    exit;
}

// Logged in
echo '<h3>Access Token</h3>';
var_dump($userToken->getValue());

// The OAuth 2.0 client handler helps us manage access tokens
$oAuth2Client = $facebookAPI->getOAuth2Client();

// Get the access token metadata from /debug_token
$tokenMetadata = $oAuth2Client->debugToken($userToken);
echo '<h3>Metadata</h3>';
var_dump($tokenMetadata);

// Validation (these will throw FacebookSDKException's when they fail)
$tokenMetadata->validateAppId('476705966414394');

// If you know the user ID this access token belongs to, you can validate it here
//$tokenMetadata->validateUserId('123');
$tokenMetadata->validateExpiration();

if (! $userToken->isLongLived()) {
    // Exchanges a short-lived access token for a long-lived one
    try {
        $userToken = $oAuth2Client->getLongLivedAccessToken($userToken);
    } catch (Facebook\Exceptions\FacebookSDKException $e) {
        echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
    exit;
  }

    echo '<h3>Long-lived</h3>';
    var_dump($userToken->getValue());
}

$_SESSION['userToken'] = (string)$userToken;  // We put it in the session to have it "globally".
var_dump($_SESSION);
// If we have his access token, then we can make an array with all of his information.
    if ($_SESSION['userToken'] != null) {
        try {

            // We assign the token.
            $facebookAPI->setDefaultAccessToken($_SESSION['userToken']);

            // Asks for: name, birthday, his profile pic.
            $queryResult = $facebookAPI->get('/me?locale=en_US&fields=email');

            // Gets the information of the User.
            $userInformation = $queryResult->getGraphUser();

            // We save this email.
            $facebookEmailProvidedByAPI = $userInformation->getField('email');


            // Now, let's see if he's in the database.
            $controller = new Controller();
            if ($controller->isEmailTaken($facebookEmailProvidedByAPI) == true) {

                // We gather his information and set it in the $_SESSION.
                $userInformationCursor = $controller->getUser($facebookEmailProvidedByAPI);
                $resultingDocuments = array();
                $profile = array();
                foreach ($userInformationCursor as $key => $value) {
                    $resultingDocuments[$key] = $value;
                    $profile['username'] = $resultingDocuments[$key]->get('username');
                    $profile['name'] = $resultingDocuments[$key]->get('name');
                    $profile['email'] = $resultingDocuments[$key]->get('email');
                    $profile['key'] = $resultingDocuments[$key]->get('key');
                    $profile['userImage'] = $resultingDocuments[$key]->get('userImage');
                }

                // We have his information, let's set it to the Session.
                $_SESSION['username'] = $profile['username'];
                $_SESSION['userKey'] = $profile['key'];
                $_SESSION['name'] = $profile['name'];
                $_SESSION['email'] = $profile['email'];
                $_SESSION['userImage'] = $profile['userImage'];

                header('http://myss-qa.herokuapp.com/View/index.php');
            } // He's not in the database, let's redirect him to the register.
            else {
                header('http://myss-qa.herokuapp.com/View/login.php');
                // Send a message here.
            }

        } catch (Exception $e) {
            echo $e->getTraceAsString();
        }
    }


