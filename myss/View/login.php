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
use ArangoDBClient\Document as ArangoDocument;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
// Start the session.
session_start();

// Verifies that the User has already logged-in.
// If he's in, we must redirect him.
if (isset($_SESSION['userId'])){
    header('Location: ..\View\index.php');
}

// Creates a connection to the database.
$database = connect();
try{
    if ((!empty($_POST['email'])) &&
        (!empty($_POST['password']))) {
        // Saves the email on a var.
        $emailOnInput = $_POST['email'];

        // Makes an AQL query to look for the username with his email.
        $query = 'FOR x IN user FILTER x.email == @email RETURN x.password';

        // Creates an Statement so we can bind the vars.
        // He will look for the username in the collection user with the username = 'username'.
        // Note that this will only returns the key, so later we have to read it again.
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

        foreach ($cursor as $key => $value) {
            $resultingDocuments[$key] = $value;
        }
        var_dump($resultingDocuments);
        if ($resultingDocuments != null){
            $password = $resultingDocuments[0];
            echo $password;
        }
        else{
            echo 'No hay usuario registrado con ese email';
        }

    }
} catch (Exception $e){
    $message = $e ->getMessage();
}
?>
    <center>
        <section class="">
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
                            <a class="btn" href="#" role="button">Forgot your password?</a>
                        </form>
                    </div><!-- /.col -->
                </center>
            </div><!-- /.container -->
        </section>
    </center>
<?php
include_once "footer.php";
?>
