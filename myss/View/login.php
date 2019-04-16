<?php
include_once "header.php";
include_once "banner.php";

require_once "../Controller/connection.php";
require_once "../Model/UserQuery.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Statement.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";

// Start the session.
session_start();

if (isset($_SESSION['userId'])) {
    header('Location: ..\View\index.php');
}

// Creates a connection to the database.
$database = connect();

function login()
{
    try {
        if (empty($_POST['email']) || empty($_POST['password'])) {
            return '';
        }
        $cursor = UserQuery::getInformation($_POST['email'], $_POST['password']);
        if ($cursor->getCount() != 0) {

            $personalInformation = array();
            $resultingDocuments = array();

            foreach ($cursor as $key => $value) {
                $resultingDocuments [$key] = $value;
                $personalInformation['username'] = $resultingDocuments [$key]->get('username');
                $personalInformation['userKey'] = $resultingDocuments [$key]->get('key');
                $personalInformation['name'] = $resultingDocuments [$key]->get('name');
                $personalInformation['email'] = $resultingDocuments [$key]->get('email');
                $personalInformation['password'] = $resultingDocuments [$key]->get('password');
            }

            if (password_verify($_POST['password'], $personalInformation['password'])) {
                $_SESSION['username'] = $personalInformation['username'];
                $_SESSION['userKey'] = $personalInformation['userKey'];
                $_SESSION['name'] = $personalInformation['name'];
                $_SESSION['email'] = $personalInformation['email'];
                header('Location: ..\View\index.php');
            } else {
                return 'Incorrect password.';
            }
        } else {
            return 'The user is not registered.';
        }
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

?>
<center>
    <section class="">
        <?php
        $message = login();
        if (!empty($message)): ?>
            <p> <?= $message; ?></p>
        <?php endif; ?>
        <div class="container" style="padding-top: 150px;">
            <center>
                <div class="col-md-6"
                     style="box-shadow: 0px 20px 30px rgba(0, 35, 71, 0.1);background: #ffffff;height:365px;">
                    <br>
                    <h1>MYSS</h1>
                    <form method="post" class="form-signin" action="login.php">
                        <h3 class="form-signin-heading">Log In</h3><br>
                        <div class="form-group">
                            <input name="email" type="text" class="form-control" required placeholder="Email">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" required placeholder="Password">
                        </div>
                        <button id="loginbtn" name="loginbtn" class="genric-btn info circle" type="submit" value="login"
                                onclick="login()">Log In
                        </button>
                        <br>
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
