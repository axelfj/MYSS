<?php
include_once "header.php";
include_once "banner.php";

require_once "../Controller/connection.php";
require_once "../Controller/Controller.php";

$controller = new Controller();
?>

<section class="login">
    <?php
    $message = $controller->register($_POST);
    if ($message == "Register successful.") {
        header('Location: ..\View\login.php');
    }
    if (!empty($message)): ?>
        <p>
        <center><?= $message ?></center></p>
    <?php endif;
    ?>
    <div class="container" style="padding-top: 50px;">
        <center>
            <div class="col-md-6"
                 style="box-shadow: 0px 20px 30px rgba(0, 35, 71, 0.1);background: #ffffff;height: 650px;">
                <br>
                <h1 class="logo">MYSS</h1>
                <form action="signup.php" method="post" class="form-signin" enctype="multipart/form-data">
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
                    <div class="form-group">
                        <label class="genric-btn info-border circle">
                            <i class="fas fa-upload"></i>Upload picture
                            <input id="userImage" name="userImage" type="file" accept='image/*'
                                   class="uploadFile img" value="Upload Photo"
                                   style="width: 0px;height: 0px;overflow: hidden;">
                        </label>
                    </div>
                    <button id="signinbtn" name="signinbtn" class="genric-btn info circle" type="submit"
                            value="register" onclick="register()">Sign up
                    </button>
                    <br><br>
                    <a class="genric-btn info-border circle arrow" href="login.php" role="button">Already have an
                        account? Log in here.<span class="lnr lnr-arrow-right"></span></a>
                </form>
            </div>
        </center>
    </div>
</section>

<?php
include_once "footer.php";
?>
