<?php
include_once "header.php";
include_once "navbar.php";

require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/CollectionHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";

use ArangoDBCLient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Document as ArangoDocument;
date_default_timezone_set('America/Costa_Rica');

if (isset($_POST['postbtn'])){
    try {
        if (!empty($_POST['title']) &&
            !empty($_POST['post'])) {

            $database = new ArangoDocumentHandler(connect());
            $document = new ArangoCollectionHandler(connect());

            $title      = $_POST['title'];
            $text       = $_POST['post'];
            $tagsPost   = $_POST['tagsPost'];
            $visibility = $_POST['visibility'];
            $owner      = $_SESSION['username'];
            $time       = date('j-m-y H:i');

            $post = new ArangoDocument();
            $post->set("title", $title);
            $post->set("text", $text);
            $post->set("tagsPost", $tagsPost);
            $post->set("visibility", $visibility);
            $post->set("owner", $owner);
            $post->set("time", $time);

            $newPost = $database->save("posts", $post);
            $message = 'You have been successfully registered';
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
                    <h3 class="username" style="font-size: 18px;"><?php echo $_SESSION['name']?></h3>
                    <p><?php echo '@'.$_SESSION['username']?></p>
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
                                      placeholder="What are you doing right now?"></textarea>
                            <br>
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
