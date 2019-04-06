<?php session_start(); ?>
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
                        <form class="search-form" action="#">
                            <input class="form-control my-0 py-1 blue-border" type="text" placeholder="Search"
                                   aria-label="Search">
                        </form>
                        <div class="input-group-append">
                            <i class="fas fa-search" aria-hidden="true" style="padding-top: 15px; padding-left: 5px;"></i>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div class="collapse navbar-collapse flex-grow-0 ml-auto" id="navbarSupportedContent">
            <ul class="navbar-nav text-right" style="padding-right:400px;">
                <li class="nav-item">
                    <a class="nav-link" href="#" onmouseover="this.style.color='rgb(56, 164, 255)'"
                       onmouseout="this.style.color='';">
                        <i class="fas fa-plus">

                        </i>
                        <span class="badge badge-notify"> </span> New post</a>
                </li>
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

<!--================ Pop-Up Post Area =================-->
<div class="modal fade" id="postModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>


