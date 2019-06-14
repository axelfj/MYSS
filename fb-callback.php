<?php

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

    // Now, let's catch his token and save it.
    $userToken = $helper->getAccessToken();
    if (isset($userToken)) {
        $_SESSION['userToken'] = (string)$userToken;        // We put it in the session to have it "globally".
    }

} catch (Exception $e) {
    echo $e->getTraceAsString();
}

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
