<?php
include_once "header.php";
include_once "navbar.php";

require_once "../Controller/connection.php";
require_once "../Controller/Controller.php";
require_once "../Controller/DTOPost_Comment_Tag.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/CollectionHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";

$controller = new Controller();
$dtoPost = new DTOPost_Comment_Tag();

date_default_timezone_set('America/Costa_Rica');

if (isset($_POST['postbtn'])) {
    try {
        if (!empty($_POST['title']) &&
            !empty($_POST['post'])) {

            $post = array();
            $post['title'] = $_POST['title'];
            $post['post'] = $_POST['post'];
            $post['tagsPost'] = $_POST['tagsPost'];
            $post['visibility'] = $_POST['visibility'];
            $post['username'] = $_SESSION['username'];
            $post['time'] = date('j-m-y H:i');

            $dtoPost->setPosts($post);
            $controller->createNewPost($dtoPost);
        }

    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-md-12 text-center ">
            <div class="panel panel-default container" style="background-color: white;padding-top: 13px;">
                <div class="userprofile social container" style="background-color: white;">
                    <div class="userpic"><img src="img/user.png" alt="" class="userpicimg"></div>
                    <h3 class="username" style="font-size: 18px;">
                        <?php
                        if (isset($_SESSION['name'])) {
                            echo $_SESSION['name'];
                        } else {
                            echo 'Name Last-Name';
                        }
                        ?></h3>
                    <p><?php
                        if (isset($_SESSION['username'])) {
                            echo '@' . $_SESSION['username'];
                        } else {
                            echo '@username';
                        }
                        ?></p>
                </div>
                <div class="col-md-12 border-top border-bottom">
                    <ul class="nav nav-pills pull-left countlist" role="tablist">
                        <li role="presentation">
                            <h3>#<br>
                                <small>Follower</small>
                            </h3>
                        </li>
                        <li role="presentation">
                            <h3>#<br>
                                <small>Following</small>
                            </h3>
                        </li>
                        <li role="presentation">
                            <h3>#<br>
                                <small>Posts</small>
                            </h3>
                        </li>
                    </ul>
                    <button id="followbtn" name="followbtn" class="btn btn-primary followbtn">
                        <!--<i class="fas fa-cog"></i>-->Follow
                    </button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- /.col-md-12 -->

        <div class="col-md-8 col-sm-12 pull-left posttimeline">
            <div class="container" style="background-color: white;"><br>
                <div class="container" style="background-color: white;">
                    <div class="container" style="background-color: white;">
                        <form action="profile.php" method="post">
                            <h4>New post</h4>
                            <hr>
                            <input id="title" name="title" type="text" class="form-control" required
                                   placeholder="Title"><br>
                            <textarea id="post" name="post" type="text" class="form-control" required
                                      placeholder="What are you doing right now?" style="resize: none;"></textarea>
                            <br>
                            <div class="row" style="">
                                <div class="col-md-4"></div>
                                <div class="col-md-4 imgUp">
                                    <div class="imagePreview"></div>
                                    <label class="btn btn-primary">
                                        Upload<input type="file" accept='image/*' class="uploadFile img" value="Upload Photo"
                                                     style="width: 0px;height: 0px;overflow: hidden;">
                                    </label>
                                </div><!-- col-2 -->
                                <div class="col-md-4"></div>
                            </div><!-- row -->
                            <input id="tagsPost" name="tagsPost" type="text" data-role="tagsinput" placeholder="Tags">
                            <hr>
                            <div class="row">
                                <div class="col-md-2">
                                    <select id="visibility" name="visibility" class="browser-default">
                                        <option value="Public">Public</option>
                                        <option value="Private">Private</option>
                                    </select>
                                </div>
                                <div class="col-md-10">
                                    <button id="postbtn" name="postbtn" type="submit"
                                            class="btn btn-success pull-right"> Post
                                    </button>
                                </div>
                            </div>
                        </form>
                        <br><br>
                    </div>
                    <!-- Status Upload  -->
                </div>
            </div>
            <br>
            <h1 class="page-header small" style="color: grey;">Your posts</h1><br>
            <?php include_once "post.inc.php"; ?>
        </div>
        <div class="col-md-4 col-sm-12 pull-right">
            <div class="container" style="background-color: white;">
                <div class="container" style="background-color: white;"><br>
                    <h1 class="page-header small">Friends</h1>
                </div>
                <hr>
                <div class="col-md-12">
                    <div class="memberblock"><a href="" class="member"> <img
                                    src="img/user.png" alt="">
                            <div class="memmbername">Ajay Sriram</div>
                        </a> <a href="" class="member"> <img src="img/user.png"
                                                             alt="">
                            <div class="memmbername">Rajesh Sriram</div>
                        </a> <a href="" class="member"> <img src="img/user.png"
                                                             alt="">
                            <div class="memmbername">Manish Sriram</div>
                        </a> <a href="" class="member"> <img src="img/user.png"
                                                             alt="">
                            <div class="memmbername">Chandra Amin</div>
                        </a> <a href="" class="member"> <img src="img/user.png"
                                                             alt="">
                            <div class="memmbername">John Sriram</div>
                        </a> <a href="" class="member"> <img src="img/user.png"
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
