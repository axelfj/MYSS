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
if(strpos($url, '?') == false){
    $len = strlen($url);
    $fileName = substr($url, $pos, $len);
}else{
    $len = strpos($url, '?');
    $fileName = substr($url, $pos, $len-$pos);
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
                if($usernameVisited != false && $usernameVisited != $_SESSION['username']){
                    $dtoPost_Comment_Tag = $controller->getPosts($usernameVisited, 'Public');
                }
                else{
                    $dtoPost_Comment_Tag = $controller->getPosts($_SESSION['username'], '');
                }
            } else {
                $dtoPost_Comment_Tag = $controller->getPosts(null, '');
            }
            $postCounter = 0;
            if (isset($dtoPost_Comment_Tag)) {

                foreach ($dtoPost_Comment_Tag as $singlePost) {
                    $comments = $controller->getComments($singlePost['key'], 'comment');
                    $numberOfComments = ($comments != null) ? sizeof($comments) : 0;
                    $divClassName =  'comment' . $singlePost['key'];
                    $image = $controller->getProfile($singlePost['owner']);
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
                                    <h4 class=""><a href="<?php echo 'profile.php?' . $singlePost['owner']; ?>"><?php echo $singlePost['owner']; ?></a><br>
                                        <small><i class="fa fa-clock-o"
                                                  id="<?php echo 'time' . $postCounter; ?>"></i> <?php echo $singlePost['time']; ?>
                                        </small>
                                    </h4>
                                    </div>
                                    <hr>
                                    <h4 id="<?php echo 'title' . $postCounter; ?>"><?php echo $singlePost['title']; ?></h4>
                                    <br>
                                    <?php
                                    if($singlePost['destination'] != ''){ ?>
                                        <img style="max-width:100%;max-height:100%;" src="<?php echo $singlePost['destination'];?>">
                                        <br><br>
                                    <?php
                                    }?>
                                    <p id="<?php echo 'text' . $postCounter; ?>"><?php echo $singlePost['text']; ?></p>

                                    <ul class="nav nav-pills pull-left" id="<?php echo 'tags' . $postCounter; ?>">
                                        <li><a id="like"
                                               href="<?php
                                               $user = $controller->verifyIfUserLiked($singlePost['key'], $_SESSION['userKey']);
                                               if ($user->getCount() == 0) {
                                                   echo 'likes.inc.php?' . $fileName . '@' . $singlePost['key'];
                                               } else {
                                                   echo '#';
                                               }
                                               ?>"
                                            ><i class="far fa-thumbs-up"></i>
                                                <?php echo PostQuery::getLikesCount($singlePost['key']); ?>
                                            </a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <li><a href="#" title="" class="prevent" onclick="toggleDivAnswer('<?php echo $divClassName;?>');"><i class="far fa-comment-alt"></i>
                                                <?php echo 'View comments (' . $numberOfComments . ')'; ?>
                                            </a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <li><a href="#" title="" class="prevent"><i class="fas fa-tags"></i>
                                                <?php echo str_replace(',', ', ', $singlePost['tagsPost']); ?>
                                            </a></li>
                                    </ul>
                                </div>
                            </div>

                            <?php
                            if (isset($comments)) {
                                $commentCounter = 0;
                                $postOrCommentCounter = $commentCounter;
                                foreach ($comments as $singleComment) {
                                    $imageCommentOwner = $controller->getProfile($singleComment['commentOwner']);
                                    $answers = $controller->getComments($singleComment['key'], 'answer');
                                    $numberOfAnswers = ($answers != null) ? sizeof($answers) : 0;

                                    include 'single-comment.inc.php';

                                    // The comment has answers.
                                    if(isset($answers)){
                                        $divClassName = 'answer' . $singleComment['key'];
                                        foreach ($answers as $singleComment){
                                            $imageCommentOwner = $controller->getProfile($singleComment['commentOwner']);
                                            include 'single-comment.inc.php';
                                        }
                                    }
                                    $commentCounter++;
                                }
                                /*include 'form-single-comment.inc.php';*/
                            }
                            ?>
                            <hr>
                            <?php include 'form-single-comment.inc.php';?>
                        </div>
                    </div>
                    <?php
                    $postCounter++;
                    $postOrCommentCounter = $postCounter;
                }
            }
        }
    } else {
        echo 'Register so you can see the posts!';
    }

} catch
(Exception $e) {
    $e->getMessage();
}

?>