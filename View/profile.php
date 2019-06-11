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

$currentUrl = $_SERVER['REQUEST_URI']; // This url has the username after a '?' character.
$usernameVisited = $controller->getUserName($currentUrl);
$dtoUser = $controller->getProfile($usernameVisited);

date_default_timezone_set('America/Costa_Rica');

if (isset($_POST['followbtn'])) {
    $controller->followUser($_SESSION['userKey'], $dtoUser['key']);
} else if (isset($_POST['postbtn'])) {
    try {
        if (!empty($_POST['title']) && !empty($_POST['post'])) {
            $post = array();
            $post['title'] = $_POST['title'];
            $post['post'] = $_POST['post'];
            $post['tagsPost'] = $_POST['tagsPost'];
            $post['visibility'] = $_POST['visibility'];
            $post['username'] = $_SESSION['username'];
            $post['time'] = date('j-m-y H:i');

            $correctPost = $controller->verifyImageUpload($post, 'postImage');

            if (isset($correctPost)) {
                $dtoPost->setPosts($correctPost);
                $controller->createNewPost($dtoPost);
                unset($post);
            }
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
                    <div class="userpic">
                        <img <?php
                        if ($usernameVisited != false) {
                            echo "src= " . $dtoUser['userImage'];
                        } else {
                            echo "src= " . $_SESSION['userImage'];
                        }
                        ?>
                                alt="" class="userpicimg">
                    </div>
                    <h3 class="username" style="font-size: 18px;">
                        <?php
                        if (isset($_SESSION['name'])) {
                            switch ($usernameVisited) {
                                case $_SESSION['username']:
                                    echo $_SESSION['name'];
                                    break;
                                case false:
                                    echo $_SESSION['name'];
                                    break;
                                case !false:
                                    echo $dtoUser['name'];
                                    break;
                                default:
                                    echo $_SESSION['name'];
                                    break;
                            }
                        } else {
                            switch ($usernameVisited) {
                                case !false:
                                    echo $usernameVisited;
                                    break;
                                default:
                                    echo 'Name Last-Name';
                            }
                        }
                        ?></h3>
                    <p><?php
                        if (isset($_SESSION['username'])) {
                            switch ($usernameVisited) {
                                case false:
                                    echo '@' . $_SESSION['username'];
                                    break;
                                case !false:
                                    echo '@' . $usernameVisited;
                                    break;
                                default:
                                    echo $_SESSION['username'];
                                    break;
                            }
                        } else {
                            switch ($usernameVisited) {
                                case !false:
                                    echo $usernameVisited;
                                    break;
                                default:
                                    echo '@username';
                            }
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
                    <?php
                    $userFollowingUser = $controller->ifFollowing($_SESSION['userKey'], $dtoUser['key']);

                    if ($usernameVisited == false || $usernameVisited == $_SESSION['username']) { ?>
                        <!--<button id="followbtn" name="followbtn" class="btn btn-primary followbtn"
                                style="margin-top: 25px;">
                            <i class="fas fa-cog"></i>
                        </button>-->

                        <button class="btn btn-primary dropdown-toggle followbtn" type="button" id="dropdownMenuButton"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                style="margin-top: 25px;">
                            <i class="fas fa-cog"></i>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="logout.php">Log out</a>
                        </div>


                    <?php } else if (!$userFollowingUser) { ?>
                        <form method="post">
                            <button id="followbtn" name="followbtn" class="btn btn-primary followbtn" onclick=""
                                    style="margin-top: 25px;">
                                <i class="fas fa-user-plus"></i> Follow
                            </button>
                        </form>
                    <?php } else { ?>
                        <button id="followbtn" name="followbtn" class="btn btn-primary followbtn" onclick=""
                                style="margin-top: 25px;">
                            <i class="fas fa-user-check"></i> Following
                        </button>
                        <?php
                    }
                    ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- /.col-md-12 -->

        <div class="col-md-8 col-sm-12 pull-left posttimeline">
            <?php if ($usernameVisited == false || $usernameVisited == $_SESSION['username']) { ?>
                <div class="container" style="background-color: white;"><br>
                    <div class="container" style="background-color: white;">
                        <div class="container" style="background-color: white;">
                            <form action="profile.php" method="post" enctype="multipart/form-data">
                                <h4>New post</h4>
                                <hr>
                                <input id="title" name="title" type="text" class="form-control" required
                                       placeholder="Title"
                                       value="<?php if (isset($post)) {
                                           echo $post['title'];
                                       } ?>"><br>
                                <div class="row" style="">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4 imgUp">
                                        <div class="imagePreview"></div>
                                        <label class="btn btn-primary"><i class="fas fa-upload"></i>
                                            Upload photo
                                            <input id="postImage" name="postImage" type="file" accept='image/*'
                                                   class="uploadFile img" value="Upload Photo"
                                                   style="width: 0px;height: 0px;overflow: hidden;">
                                        </label>
                                    </div><!-- col-2 -->
                                    <div class="col-md-4"></div>
                                </div><!-- row -->
                                <textarea id="post" name="post" type="text" class="form-control" required
                                          placeholder="What are you doing right now?"
                                          style="resize: none;"><?php if (isset($post)) {
                                        echo $post['post'];
                                    } ?></textarea>
                                <br>
                                <input id="tagsPost" name="tagsPost" type="text" data-role="tagsinput"
                                       placeholder="Tags"
                                       value="<?php if (isset($post)) {
                                           echo $post['tagsPost'];
                                       } ?>">
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
            <?php } ?>

            <?php include_once "post.inc.php"; ?>
        </div>
        <div class="col-md-4 col-sm-12 pull-right">
            <div class="container" style="background-color: white;">
                <div class="container" style="background-color: white;"><br>
                    <h1 class="page-header small">Friends</h1>
                </div>
                <hr>
                <div class="col-md-12">
                    <div class="memberblock">
                        <?php
                        if ($usernameVisited != false) {
                            $userFriends = $controller->getAllMyFriends($dtoUser['key']);
                        } else {
                            $userFriends = $controller->getAllMyFriends($_SESSION['userKey']);
                        }

                        foreach ($userFriends as $friend) {
                            ?>
                            <?php
                            $friendArray = $friend->getAll();
                            $userKey = substr($friendArray['_to'], 5, strlen($friendArray['_to']));
                            $username = UserQuery::getUsernameAndImage($userKey);
                            ?>
                            <a href="<?php echo 'profile.php?' . $username['username']; ?>" class="member"> <img
                                        src="<?php echo $username['userImage']; ?>" alt="" style="border-radius: 50px;">
                                <center>
                                    <div class="memmbername"><?php echo $username['username']; ?></div>
                                </center>
                            </a>
                            <?php
                        } ?>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>


<?php
include_once "footer.php";
?>
