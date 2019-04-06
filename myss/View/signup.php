<?php
include_once "header.php";
include_once "banner.php";

// Calls the connection and all the dependencies.
require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/CollectionHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";

// Calls the functions of Arango to manage Collections, Documents, Etc.
use ArangoDBCLient\DocumentHandler      as ArangoDocumentHandler;
use ArangoDBClient\CollectionHandler    as ArangoCollectionHandler;
use ArangoDBClient\Document             as ArangoDocument;

try {

    // Checks if all the values are set.
    if ((!empty($_POST['username'])) &&
        (!empty($_POST['email'])) &&
        (!empty($_POST['password'])) &&
        (!empty($_POST['name'])) &&
        (!empty($_POST['birthday']))){

        // Calls the DocumentHandler and CollectionHandler to make queries.
        $database = new ArangoDocumentHandler(connect());
        $document = new ArangoCollectionHandler(connect());

        // This function substitutes all the query and search for the username.
        $cursor = $document->byExample('user', ['username' => $_POST['username']]);

        // Count it, 0 = he's not in the database.
        $valueFound = $cursor->getCount();

        // We can proceed to insert him in the database.
        if ($valueFound == 0) {

            // An array to iterate the cursor.
            $resultingDocuments = array();

            // And an auxiliary var to save the email of the user.
            $emailOnDatabase = null;

            // Iterates over the array to process him.
            foreach ($cursor as $key => $value) {

                // After it saves him in the $resultingDocuments, we get the attributes that we want.
                $resultingDocuments[$key] = $value;

                // We can get the email now.
                $emailOnDatabase = $resultingDocuments[$key]->get('email');


                // Checks if the format of the email is valid and that has not been taken.
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && ($emailOnDatabase != $_POST['email'])) {
                    $message = 'The format of the email is invalid or has been taken.';
                } else {

                    // Gets all tha parameters to insert him.
                    $username = $_POST['username'];
                    $email = $_POST['email'];
                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $name = $_POST['name'];
                    $birthday = $_POST['birthday'];

                    // Creates a document that represents the person.
                    $user = new ArangoDocument();
                    $user->set("username", $username);
                    $user->set("email", $email);
                    $user->set("password", $password);
                    $user->set("name", $name);
                    $user->set("birthday", $birthday);

                    // Insert him in the collection user.
                    $newUser = $database->save("user", $user);
                    $message = 'You have been successfully registered';
                }
            }
        }
        else{
            $message = "Cannot register. The username/email had been taken.";
        }
    }
} catch (Exception $e){
    $message = $e -> getMessage();
}
?>

<section class="login">
    <?php if(!empty($message)): ?>
        <p> <?= $message ?></p>
    <?php endif; ?>
    <div class="container" style="padding-top: 150px;">
        <center>
            <div class="col-md-6" style="box-shadow: 0px 20px 30px rgba(0, 35, 71, 0.1);background: #ffffff;height:490px;">
                <br><h1>MYSS</h1>
                <form action="signup.php" method="post" class="form-signin">
                    <h3 class="form-signin-heading">Register</h3><br>
                    <div class="form-group">
                        <input id="username" name="username" type="text" class="form-control" required placeholder="Username">
                    </div>
                    <div class="form-group">
                        <input id="email" name="email" type="text" class="form-control" required placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input id="password" name="password" type="password" class="form-control" required placeholder="Password">
                    </div>
                    <div class="form-group">
                        <input id="name" name="name" type="text" class="form-control" required placeholder="Full name">
                    </div>
                    <div class="form-group">
                        <input id="birthday" name="birthday" type="date" parsley-trigger="change" required class="form-control">
                    </div>
                    <button id="signinbtn" name="signinbtn" class="genric-btn info circle" type="submit" data-toggle="modal" data-target="#textModal">Sign up</button><br>
                    <a class="btn" href="login.php" role="button">Already have an account? Log in here.</a>
                </form>
            </div>
        </center>
    </div>
</section>

<div class="modal fade" id="textModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="modal-title" id="exampleModalLabel">
                    <?php if(!empty($message)): echo $message; endif; ?>
                </h2>
            </div>
        </div>
    </div>
</div>

<?php
include_once "footer.php";
?>
