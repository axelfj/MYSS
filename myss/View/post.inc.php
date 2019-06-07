<?php

use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;

require_once "../Controller/Controller.php";
require_once "../Controller/DTOPost_Comment_Tag.php";

$database = connect();
$document = new ArangoCollectionHandler(connect());

// Contains the URL where we are.
$url = $_SERVER['REQUEST_URI'];

// Applies string functions to get only the name that we want.
$pos = strpos($url, 'View') + 5;
if (strpos($url, '?') == false) {
    $len = strlen($url);
    $fileName = substr($url, $pos, $len);
} else {
    $len = strpos($url, '?');
    $fileName = substr($url, $pos, $len - $pos);
}

$controller = new Controller();

try {
    if (isset($_SESSION['username'])) {

        // Looks for the posts.
        $cursor = $document->byExample('post', ['visibility' => "Public"], ['visibility' => "Private"]);
        $valueFound = $cursor->getCount();

        // We got no posts.
        if ($valueFound == 0) { ?>
            <h5>Nothing to show yet.</h5><br><br><br><br>
            <?php
        } else {

            // If we're in the profile, then we must look for his posts.
            if ($fileName == 'profile.php') {

                // This checks if he's not visiting himself.
                // The var $usernameVisited is declared in profile.php.
                if ($usernameVisited != false && $usernameVisited != $_SESSION['username']) {

                    // Checks if they're friends.
                    // We must obtain his key to check with the function "ifFollowing".
                    $informationUsernameVisited = $controller->getProfile($usernameVisited);
                    $idUsernameVisited = $informationUsernameVisited['key'];

                    // We proceed to see if they're friends.
                    if ($controller->ifFollowing($_SESSION['userKey'], $idUsernameVisited)) {

                        // This brings all the posts of that user, but only in the profile of the person.
                        $dtoPost_Comment_Tag = $controller->getPosts($usernameVisited, '');
                        if (!empty($dtoPost_Comment_Tag)) {
                            sort($dtoPost_Comment_Tag);
                        }
                    } else {

                        $dtoPost_Comment_Tag = $controller->getPosts($usernameVisited, 'Public');
                        if (!empty($dtoPost_Comment_Tag)) {
                            sort($dtoPost_Comment_Tag);
                        }
                    }
                } // This query means that he's in his profile.
                else {
                    $dtoPost_Comment_Tag = $controller->getPosts($_SESSION['username'], '');
                    if (!empty($dtoPost_Comment_Tag)) {
                        sort($dtoPost_Comment_Tag);
                    }
                }
            } // This is the query that means that he's at the index and he's not searching posts by a specific tag.
            else if (!isset($dtoPost_Comment_Tag)) {

                // First, we will check if he's following someone.
                $friendsCursor = $controller->getAllMyFriends($_SESSION['userKey']);

                // Let's get all his friends into an array.
                $friendsArray = Array(); // Helps in the cursor.
                $usernameArray = Array();
                $privatePosts = Array(); // Here will be the posts.
                $friendsCounter = 0;
                $auxiliaryArray = Array(); // Helpfully.


                // We verify if we're following somebody.
                if ($friendsCursor->getCount() > 0) {

                    // Then we obtain their usernames.
                    // Remember that they will come: user/key.
                    foreach ($friendsCursor as $key => $value) {
                        $auxiliaryArray[$key] = $value;
                        $friendsArray[$friendsCounter] = $auxiliaryArray[$key]->get('_to');
                        $friendsCounter++;
                    }

                    // Now, let's get only their keys. And with that, their usernames.
                    for ($counter = 0; $counter < count($friendsArray); $counter++) {
                        $friendsArray[$counter] = substr($friendsArray[$counter], 5);
                        $userCursor = $document->byExample('user',
                            ['_key' => $friendsArray[$counter]]);

                        // Here will be fetched our username.
                        foreach ($userCursor as $key => $value) {
                            $auxiliaryArray[$key] = $value;
                            $usernameArray[$counter] = $auxiliaryArray[$key]->get('username');
                        }

                        // For every username in the username, let's retrieve their posts.
                        if ($controller->getPosts($usernameArray[$counter], 'Private') != null)
                            array_push($privatePosts, $controller->getPosts($usernameArray[$counter],
                                'Private'));
                    }

                    // We make the query, save those posts, append the private ones and set them to him.
                    // We also query our posts.
                    $publicPosts = $controller->getPosts(null, '');
                    $myPosts = $controller->getPosts($_SESSION['username'], 'Private');
                    $dtoPost_Comment_Tag = Array();

                    // We must obtain the array that is inside every array.
                    for ($counter = 0; $counter < sizeof($privatePosts); $counter++) {
                        array_push($publicPosts, $privatePosts[$counter][0]);
                    }

                    // We save the posts.
                    $dtoPost_Comment_Tag = $publicPosts + $privatePosts;

                    // We add the posts from the user that is logged in.
                    if ($myPosts > 0)
                        for ($counter = 0; $counter < sizeof($myPosts); $counter++) {
                            array_push($dtoPost_Comment_Tag, $myPosts[$counter]);
                        }

                    if (!empty($dtoPost_Comment_Tag)) {
                        sort($dtoPost_Comment_Tag);
                    }
                } // This means that he's following nobody.
                else {

                    // We will bring his privates posts and the public ones.
                    // And also checking that they aren't empty.
                    $publicPosts = $controller->getPosts(null, '');
                    $hisPosts = $controller->getPosts($_SESSION['username'], 'Private');
                    $dtoPost_Comment_Tag = Array();

                    if (!empty($publicPosts)) {
                        $dtoPost_Comment_Tag = $dtoPost_Comment_Tag + $publicPosts;
                    }
                    if (!empty($hisPosts)) {
                        for ($counter = 0; $counter < sizeof($hisPosts); $counter++) {
                            array_push($dtoPost_Comment_Tag, $hisPosts[$counter]);
                        }
                    }
                    if (empty($dtoPost_Comment_Tag)) {
                        $dtoPost_Comment_Tag = null;    // Very important! If not null, the code above will crash.
                    }

                    if (!empty($dtoPost_Comment_Tag)) {
                        sort($dtoPost_Comment_Tag);
                    }
                }
            }
            $postCounter = 0;
            if (isset($dtoPost_Comment_Tag) && sizeof($dtoPost_Comment_Tag) != 0) {

                foreach ($dtoPost_Comment_Tag as $singlePost) {
                    $comments = $controller->getComments($singlePost['key'], 'comment');
                    $numberOfComments = ($comments != null) ? sizeof($comments) : 0;
                    $divClassName = 'comment' . $singlePost['key'];
                    $image = $controller->getProfile($singlePost['owner']);
                    $postCommentOrAnswerKey = $singlePost['key'];
                    ?>

                    <div class="panel container" style="background-color: white;"
                         id="<?php echo $singlePost['key']; ?>">
                        <div class="col-md-12 container" style="background-color: white;">
                            <div class="media">

                                <div class="media-body">
                                    <div class="row">
                                        <a href="javascript:void(0)">
                                            <img <?php
                                            echo "src= " . $image['userImage'];
                                            ?> alt="" class="media-object">
                                        </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <h4 class=""><a
                                                    href="<?php echo 'profile.php?' . $singlePost['owner']; ?>"><?php echo $singlePost['owner']; ?></a><br>
                                            <small><i class="fa fa-clock-o"
                                                      id="<?php echo 'time' . $postCounter; ?>"></i> <?php echo $singlePost['time']; ?>
                                            </small>
                                        </h4>
                                    </div>
                                    <hr>
                                    <h4 id="<?php echo 'title' . $postCounter; ?>"><?php echo $singlePost['title']; ?></h4>
                                    <br>
                                    <?php
                                    if ($singlePost['destination'] != '') { ?>
                                        <img style="max-width:100%;max-height:100%;"
                                             src="<?php echo $singlePost['destination']; ?>">
                                        <br><br>
                                        <?php
                                    } ?>
                                    <p id="<?php echo 'text' . $postCounter; ?>"><?php echo $singlePost['text']; ?></p>

                                    <ul class="nav nav-pills pull-left" id="<?php echo 'tags' . $postCounter; ?>">
                                        <li><a id="like"
                                               href="<?php
                                               $user = $controller->verifyIfUserLikedPost($singlePost['key'], $_SESSION['userKey']);
                                               if ($user->getCount() == 0) {
                                                   echo 'likes.inc.php?' . $fileName . '@' . $singlePost['key'];
                                               } else {
                                                   echo '#';
                                               }
                                               ?>"
                                            ><i class="far fa-thumbs-up"></i>
                                                <?php echo PostQuery::getPostLikeCount($singlePost['key']); ?>
                                            </a>
                                            <a href="#" data-toggle="modal"
                                               data-target="#<?php echo 'like' . $postCommentOrAnswerKey; ?>">
                                                <?php
                                                $userOrUsers = (PostQuery::getPostLikeCount($singlePost['key']) == 1) ? 'user ' : 'users ';
                                                echo $userOrUsers . 'liked';
                                                ?>
                                            </a>
                                        </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <li><a href="#" title="" class="prevent"
                                               onclick="toggleDivAnswer('<?php echo $divClassName; ?>');"><i
                                                        class="far fa-comment"></i>
                                                <?php echo 'View comments (' . $numberOfComments . ')'; ?>
                                            </a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <li><a href="#" title="" class="prevent"><i class="fas fa-tags"></i>
                                                <?php echo str_replace(',', ', ', $singlePost['tagsPost']); ?>
                                            </a></li>
                                    </ul>
                                </div>
                            </div>
                            <?php
                            include 'modal-likes.php';
                            if (isset($comments)) {
                                $commentCounter = 0;
                                $postOrCommentCounter = $commentCounter;

                                foreach ($comments as $singleComment) {
                                    $imageCommentOwner = $controller->getProfile($singleComment['commentOwner']);
                                    $answers = $controller->getComments($singleComment['key'], 'answer');
                                    $numberOfAnswers = ($answers != null) ? sizeof($answers) : 0;
                                    $postCommentOrAnswerKey = $commentKey = $singleComment['key'];

                                    include 'single-comment.inc.php';

                                    if (isset($answers)) {

                                        foreach ($answers as $singleComment) {
                                            $imageCommentOwner = $controller->getProfile($singleComment['commentOwner']);
                                            $divClassName = 'answer' . $commentKey;
                                            $postCommentOrAnswerKey = $singleComment['key'];
                                            include 'single-comment.inc.php';
                                        }
                                    }
                                    $commentCounter++;
                                    $divClassName = 'comment' . $singlePost['key'];
                                }

                            }
                            ?>
                            <hr>
                            <?php include 'form-single-comment.inc.php'; ?>
                        </div>
                    </div>
                    <?php
                    $postCounter++;
                    $postOrCommentCounter = $postCounter;
                }
            } else if ($dtoPost_Comment_Tag != null && sizeof($dtoPost_Comment_Tag) == 0) {
                echo
                    '<div class="alert alert-info">
                    <strong>No results!</strong> No posts with tag "' . $_POST['searchByTag'] . '"
                </div>';
            }
        }
    } else {
        echo '<div class="alert alert-info">
            Register so you can see the posts!
        </div>';
    }

} catch
(Exception $e) {
    $e->getMessage();
}

?>