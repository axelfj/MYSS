<?php
include_once "header.php";
include_once "banner.php";
?>

<section class="login">
    <div class="container" style="padding-top: 150px;">

        <center>
            <div class="col-md-6" style="box-shadow: 0px 20px 30px rgba(0, 35, 71, 0.1);background: #ffffff;height:490px;">
                <br><h1><i class="fa fa-smile"></i>MYSS</h1>
                <form method="post" class="form-signin">
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
                    <button id="signinbtn" name="signinbtn" class="genric-btn info circle" type="submit">Sign up</button><br>                                        
                    <a class="btn" href="login.php" role="button">Already have an account? Log in here.</a>                    
                </form>
            </div><!-- /.col -->
        </center>
    </div><!-- /.container -->
</section> 

<?php
include_once "footer.php";
?>
