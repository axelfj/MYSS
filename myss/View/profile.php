<?php
include_once "header.php";
include_once "navbar.php";
?>

<!--<head><link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css"></head>-->
<div class="container">
    <div class="row">
        <div class="col-md-12 text-center ">
            <div class="panel panel-default container" style="background-color: white;padding-top: 13px;">
                <div class="userprofile social container" style="background-color: white;">
                    <div class="userpic"><img src="img/hiena.png" alt="" class="userpicimg"></div>
                    <h3 class="username">Hiena galáctica (new MYSS account jiji)</h3>
                    <p>Me gusta hueler pegamento</p>
                </div>
                <div class="col-md-12 border-top border-bottom">
                    <ul class="nav nav-pills pull-left countlist" role="tablist">
                        <li role="presentation">
                            <h3>1452<br>
                                <small>Follower</small>
                            </h3>
                        </li>
                        <li role="presentation">
                            <h3>666<br>
                                <small>Following</small>
                            </h3>
                        </li>
                        <li role="presentation">
                            <h3>5000<br>
                                <small>Activity</small>
                            </h3>
                        </li>
                    </ul>
                    <button class="btn btn-primary followbtn"><i class="fas fa-cog"></i>Follow</button>

                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- /.col-md-12 -->

        <div class="col-md-8 col-sm-12 pull-left posttimeline">
            <div class="container" style="background-color: white;"><br>
                <div class="container" style="background-color: white;">
                    <div class="container" style="background-color: white;">
                        <form>
                            <h4>New post</h4>
                            <hr>
                            <input id="title" name="title" type="text" class="form-control" required
                                   placeholder="Title"><br>
                            <textarea id="post" name="post" type="text" class="form-control" required
                                      placeholder="What are you doing right now?"></textarea>
                            <br>
                            <input type="text" value="Aquí deberían ir los tags, pero no me sirve jeje"
                                   data-role="tagsinput" class="form-control"/>
                            <hr>
                            <button type="submit" class="btn btn-success pull-right"> Share</button>
                        </form>
                        <br><br>
                    </div>
                    <!-- Status Upload  -->
                </div>
            </div>
            <br><h1 class="page-header small" style="color: grey;">Your posts</h1><br>
            <div class="panel container" style="background-color: white;">
                <div class="btn-group pull-right postbtn">
                    <button type="button" class="dotbtn dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="padding-top: 10px;">
                        <span class="dots"></span></button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li><a href="javascript:void(0)">Hide this</a></li>
                        <li><a href="javascript:void(0)">Report</a></li>
                    </ul>
                </div>
                <div class="col-md-12 container" style="background-color: white;">
                    <div class="media">
                        <div class="media-left"><a href="javascript:void(0)"><img
                                        src="https://bootdey.com/img/Content/avatar/avatar3.png" alt=""
                                        class="media-object"> </a></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="media-body">
                            <h4 class="media-heading">Lucky Sans<br>
                                <small><i class="fa fa-clock-o"></i> Yesterday, 2:00 am</small>
                            </h4>
                            <hr>
                            <p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante
                                sollicitudin commodo. Cras purus odio. </p>

                            <ul class="nav nav-pills pull-left ">
                                <li><a href="" title=""><i class="far fa-thumbs-up"></i> 2015</a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <li><a href="" title=""><i class="far fa-comment-alt"></i> 25</a></li>
                                <!--<li><a href="" title=""><i class="glyphicon glyphicon-share-alt"></i> 15</a></li>-->
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-12 commentsblock border-top">
                        <div class="media">
                            <div class="media-left"><a href="javascript:void(0)"> <img alt="64x64"
                                                                                       src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                                                       class="media-object"> </a></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <div class="media-body">
                                <h4 class="media-heading">Astha Smith</h4>
                                <p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante
                                    sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra
                                    turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue
                                    felis in faucibus.</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <input id="comment" name="comment" type="text" class="form-control" placeholder="Type a new comment..."><br>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 pull-right">
            <div class="container" style="background-color: white;">
                <div class="container" style="background-color: white;"><br>
                    <h1 class="page-header small">Friends</h1>
                </div>
                <hr>
                <div class="col-md-12">
                    <div class="memberblock"><a href="" class="member"> <img
                                    src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="">
                            <div class="memmbername">Ajay Sriram</div>
                        </a> <a href="" class="member"> <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                             alt="">
                            <div class="memmbername">Rajesh Sriram</div>
                        </a> <a href="" class="member"> <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                             alt="">
                            <div class="memmbername">Manish Sriram</div>
                        </a> <a href="" class="member"> <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                             alt="">
                            <div class="memmbername">Chandra Amin</div>
                        </a> <a href="" class="member"> <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                             alt="">
                            <div class="memmbername">John Sriram</div>
                        </a> <a href="" class="member"> <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                                                             alt="">
                            <div class="memmbername">Lincoln Sriram</div>
                        </a></div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>


<?php
include_once "footer.php";
?>
