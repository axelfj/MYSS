<?php

session_start();

require_once "../Controller/Controller.php";
if (isset($_POST['search'])) {

    try {
        $controller = new Controller();
        $dtoPost_Comment_Tag = $controller->filterPostsByTag($_POST['search'],1);
        $dtoUser = $controller->filterUsername($_POST['search'],2);
        $dtoText = $controller->filterDescription($_POST['search'],3);
    } catch (Exception $e) {
        throwException($e);
    }
}
?>

<body style="background-color: #E7E7E9;" class="profile-page">
<!--================ Start Header Area =================-->
<header class="">
    <nav class="navbar navbar-expand-lg navbar-light bg-light static-navbar justify-content-end">
        <a class="navbar-brand" style="color:rgb(56, 164, 255);padding-left:400px;" href="index.php"
           onmouseover="this.style.color='rgb(0, 138, 255)';" onmouseout="this.style.color='rgb(56, 164, 255)'">MYSS</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse flex-grow-0 ml-auto" id="navbarSupportedContent">
            <ul class="navbar-nav text-left">
                <li class="nav-item">
                    <div class="input-group md-form form-sm form-2 pl-0">
                        <form class="search-form" action="index.php" method="post">
                            <input class="form-control my-0 py-1 blue-border" type="text" placeholder="Search"
                                   aria-label="Search" name="search" id="search">
                        </form>
                        <div class="input-group-append">
                            <i class="fas fa-search" aria-hidden="true"
                               style="padding-top: 15px; padding-left: 5px;"></i>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse flex-grow-0 ml-auto" id="navbarSupportedContent">
            <ul class="navbar-nav text-right" style="padding-right:400px;">
                <li class="nav-item">
                    <a class="nav-link" href="profile.php" onmouseover="this.style.color='rgb(56, 164, 255)'"
                       onmouseout="this.style.color='';">
                        <i class="fas fa-user-alt"></i>
                        <span class="badge badge-notify"></span> Profile</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<br><br><br><br>
<!--================ End Header Area =================-->


