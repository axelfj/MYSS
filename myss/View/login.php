<?php
include_once "header.php";
include_once "banner.php";

// Connection to the database and dependencies.
require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Statement.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";

// So we can cast it in the Statement.
use ArangoDBClient\Statement as ArangoStatement;

// Start the session.
session_start();

// Verifies that the User has already logged-in.
// If he's in, we must redirect him.
if (isset($_SESSION['userId'])){
    header('Location: ..\View\index.php');
}

// Creates a connection to the database.
$database = connect();

try {
    if ((!empty($_POST['email'])) &&
        (!empty($_POST['password']))) {

        // Saves the email and the password on a vars.
        $emailOnInput       = $_POST['email'];
        $passwordOnInput    = $_POST['password'];

        // Makes an AQL query to look for the username with his email.
        // Also, we're extracting all the info to save it in the Session.
        $query = 'FOR x IN user FILTER x.email == @email RETURN {password: x.password, key: x._key, 
        username: x.username, name: x.name, email: x.email}';

        // Creates an Statement so we can bind the vars.
        // He will look for the username in the collection user with the email = to the email that is on the POST.
        $statement = new ArangoStatement(
            $database,
            array(
                "query" => $query,
                "count" => true,
                "batchSize" => 1,   // It is suppose to only bring one.
                "sanitize" => true,
                "bindVars" => array("email" => $emailOnInput)
            )
        );

        // Executes the query.
        $cursor = $statement->execute();

        // And saves the result in an array.
        $resultingDocuments = array();

        // He will count how many fetches are in the cursor, if the cursor says 0, it means that he's not in the
        // database.
        // Checks if the User exists.
        if ($cursor->getCount() > 0) {

            // Creates an another array to save the information of the person without saving it in the session without
            // knowing if he's writing the right information.
            $personalInformation = array();

            // Iterates over the array to process him.
            foreach ($cursor as $key => $value) {

                // After it saves him in the $resultingDocuments, we get the attributes that we want.
                $resultingDocuments[$key]           = $value;
                $personalInformation['username']    = $resultingDocuments[$key]->get('username');
                $personalInformation['userKey']     = $resultingDocuments[$key]->get('key');
                $personalInformation['name']        = $resultingDocuments[$key]->get('name');
                $personalInformation['email']       = $resultingDocuments[$key]->get('email');
                $personalInformation['password']    = $resultingDocuments[$key]->get('password');
             }

            // Now, we must compare his password with the one that is in the form.
            if (password_verify($passwordOnInput, $personalInformation['password'])){

                // Save his information in the session and redirect him.
                $_SESSION['username']   = $personalInformation['username'];
                $_SESSION['userKey']    = $personalInformation['userKey'];
                $_SESSION['name']       = $personalInformation['name'];
                $_SESSION['email']      = $personalInformation['email'];

                // Finally, redirect him to the index.
                header('Location: ..\View\index.php');
            }
            else{
                $message = 'Incorrect password.';
            }
        } else {
            $message = 'The user is not registered.';
        }
    }
}
    catch (Exception $e){
    $message = $e ->getMessage();
}

?>
    <center>
        <section class="">
            <?php if(!empty($message)): ?>
                <p> <?= $message ?></p>
            <?php endif; ?>
            <div class="container" style="padding-top: 150px;">
                <center>
                    <div class="col-md-6" style="box-shadow: 0px 20px 30px rgba(0, 35, 71, 0.1);background: #ffffff;height:365px;">
                        <br><h1>MYSS</h1>
                        <form method="post" class="form-signin" action="login.php">
                            <h3 class="form-signin-heading">Log In</h3><br>
                            <div class="form-group">
                                <input name="email" type="text" class="form-control" required placeholder="Email">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" required placeholder="Password">
                            </div>
                            <button id="loginbtn" name="loginbtn" class="genric-btn info circle" type="submit">Log In</button><br>
                            <a class="btn" href="signup.php" role="button">Don't have an account yet? Register here.</a><br>
                        </form>
                    </div><!-- /.col -->
                </center>
            </div><!-- /.container -->
        </section>
    </center>
<?php
include_once "footer.php";
?>
