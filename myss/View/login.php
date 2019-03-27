<?php
include_once "header.php";
include_once "banner.php";

// Connection to the database.
require_once "../Controller/connection.php";

// Checks if all the values are set.
if((!empty($_POST['email'])) && (!empty($_POST['password']))){

}
?>
<center>
<section class="">
    <div class="container" style="padding-top: 150px;">
        <center>
            <div class="col-md-6" style="box-shadow: 0px 20px 30px rgba(0, 35, 71, 0.1);background: #ffffff;height:365px;">
                <br><h1>MYSS</h1>
                <form method="post" class="form-signin">
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
