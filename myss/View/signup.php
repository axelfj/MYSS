<?php
include_once "header.php";
include_once "banner.php";

// Connection to the database.
require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";

// Saves it more easily.
use ArangoDBClient\Document as ArangoDocument;
use ArangoDBCLient\DocumentHandler as ArangoInsert;

// Checks if all the values are set.
if((!empty($_POST['username'])) &&
    (!empty($_POST['email'])) &&
    (!empty($_POST['password'])) &&
    (!empty($_POST['name'])) &&
    (!empty($_POST['birthday']))){
    try{
        // Gets the parameters.
        $username   = $_POST['username'];
        $email      = $_POST['email'];
        $password   = password_hash($_POST['password'],PASSWORD_BCRYPT);
        $name       = $_POST['name'];
        $birthday   = $_POST['birthday'];

        // Creates an auxiliary to fill with the person.
        $person = new ArangoDocument();
        $person->set("username", $username);
        $person->set("email", $email);
        $person->set("password", $password);
        $person->set("name", $name);
        $person->set("birthday", $birthday);

        // Insert him in the database.
        $database  = new ArangoInsert(connect());
        $newPerson = $database->save("user", $person);

    }
    catch (Exception $e){
        $message = $e->getMessage();
    }
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
            </div><!-- /.col -->
        </center>
    </div><!-- /.container -->
</section>

<!-- Text Modal-->
<div class="modal fade" id="textModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h2 class="modal-title" id="exampleModalLabel">You're successfully registered.</h2>
            </div>
        </div>
    </div>
</div>

<?php
include_once "footer.php";
?>
