<?php
include_once "header.php";
include_once "banner.php";

// Calls the connection and all the dependencies.
require_once "../Controller/connection.php";
require_once "../Model/UserQuery.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/CollectionHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";

// Calls the functions of Arango to manage Collections, Documents, Etc.
use ArangoDBCLient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Document as ArangoDocument;

try {
    if ((!empty($_POST['username'])) &&
        (!empty($_POST['email'])) &&
        (!empty($_POST['password'])) &&
        (!empty($_POST['name'])) &&
        (!empty($_POST['birthday']))) {

        if (User::isUsernameTaken($_POST['username']) == false) {
            if (User::isEmailTaken($_POST['email']) == false) {
                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

                    User::register(
                            $_POST['username'],
                            $_POST['email'],
                            $password,
                            $_POST['name'],
                            $_POST['birthday']);
                    header('Location: ..\View\login.php');
                } else {
                    $message = "Cannot register. The email is invalid.";
                }
            } else {
                $message = "Cannot register. The email has been taken";
            }
        } else {
            $message = "Cannot register. The username has been taken.";
        }
    }
} catch (Exception $e) {
    $message = $e->getMessage();
}
?>

<section class="login">
    <?php if (!empty($message)): ?>
        <p>
        <center><?= $message ?></center></p>
    <?php endif; ?>
    <div class="container" style="padding-top: 150px;">
        <center>
            <div class="col-md-6"
                 style="box-shadow: 0px 20px 30px rgba(0, 35, 71, 0.1);background: #ffffff;height:490px;">
                <br>
                <h1>MYSS</h1>
                <form action="signup.php" method="post" class="form-signin">
                    <h3 class="form-signin-heading">Register</h3><br>
                    <div class="form-group">
                        <input id="username" name="username" type="text" class="form-control" required
                               placeholder="Username">
                    </div>
                    <div class="form-group">
                        <input id="email" name="email" type="text" class="form-control" required placeholder="Email">
                    </div>
                    <div class="form-group">
                        <input id="password" name="password" type="password" class="form-control" required
                               placeholder="Password">
                    </div>
                    <div class="form-group">
                        <input id="name" name="name" type="text" class="form-control" required placeholder="Full name">
                    </div>
                    <div class="form-group">
                        <input id="birthday" name="birthday" type="date" parsley-trigger="change" required
                               class="form-control">
                    </div>
                    <button id="signinbtn" name="signinbtn" class="genric-btn info circle" type="submit">Sign up
                    </button>
                    <br>
                    <a class="btn" href="login.php" role="button">Already have an account? Log in here.</a>
                </form>
            </div>
        </center>
    </div>
</section>


<?php
include_once "footer.php";
?>
