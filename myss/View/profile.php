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

$usernameVisited = getUserName();
$dtoUser = $controller->getProfile($usernameVisited);

if (isset($_POST['followbtn'])) {
    $controller->followUser($_SESSION['userKey'], $dtoUser['key']);
}

if (isset($_POST['postbtn'])) {
    try {
        if (!empty($_POST['title']) && !empty($_POST['post'])) {
            $post = array();
            $post['title'] = $_POST['title'];
            $post['post'] = $_POST['post'];
            $post['tagsPost'] = $_POST['tagsPost'];
            $post['visibility'] = $_POST['visibility'];
            $post['username'] = $_SESSION['username'];
            $post['time'] = date('j-m-y H:i');

            $correctPost = verifyImageUpload($post);

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

// Verifies if an user is trying to visit another user's profile.
// If that occurs, then this function will return the username of the
// user that is getting visited. If not, then it will return false.
function getUserName()
{
    $url = $_SERVER['REQUEST_URI']; // This url has the username after a '?' character.
    $posStart = strpos($url, '?');
    $posEnd = strlen($url);

    if ($posStart != false) {
        $username = substr($url, $posStart + 1, $posEnd - $posStart);
        return $username;
    }
    return false;
}

// This function verifies if just an image is going to be uploaded. If that's true,
// the destination path to the image will be set to $post array.
function verifyImageUpload($post)
{
    $imageName = $_FILES['postImage']['name'];
    $imageTempName = $_FILES['postImage']['tmp_name'];

    if ($imageName != "") {
        $type = explode('.', $imageName);
        $type = strtolower($type[count($type) - 1]);

        // If there's an image to upload, the destination is set and the image is moved to
        // that destination.
        if (in_array($type, array('gif', 'jpg', 'jpeg', 'png'))) {
            $destination = 'userImages/' . uniqid(rand()) . '.' . $type;
            $post['destination'] = $destination;
            move_uploaded_file($imageTempName, $destination);
        } else {
            // If the user tries to upload anything else that is not an image, an
            // error message appears and the function returns null.
            echo '<div class="alert alert-danger" role="alert">You just can upload ".gif", ".jpg", ".jpeg" and ".png" files</div>';
            return null;
        }
    } else { // If there's not an image to upload, the destination is empty.
        $post['destination'] = '';
    }
    return $post;
}

?>

<div class="container">
    <div class="row">
        <div class="col-md-12 text-center ">
            <div class="panel panel-default container" style="background-color: white;padding-top: 13px;">
                <div class="userprofile social container" style="background-color: white;">
                    <div class="userpic"><img <?php echo "src= " . $usernameVisited['userImage']; ?> alt=""
                                                                                                     class="userpicimg">
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
                        <button id="followbtn" name="followbtn" class="btn btn-primary followbtn"
                                style="margin-top: 25px;">
                            <i class="fas fa-cog"></i>
                        </button>
                    <?php } else if (!$userFollowingUser) { ?>
                        <form method="post">
                            <button id="followbtn" name="followbtn" class="btn btn-primary followbtn" onclick="prueba()"
                                    style="margin-top: 25px;">
                                <i class="fas fa-user-plus"></i> Follow
                            </button>
                        </form>
                    <?php } else { ?>
                        <button id="followbtn" name="followbtn" class="btn btn-primary followbtn" onclick="prueba()"
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
