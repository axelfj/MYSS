<?php

use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;

require_once "../Controller/readCollection.php";
require_once "../Controller/Controller.php";
require_once "../Controller/DTOPost_Comment_Tag.php";

$database = connect();
$document = new ArangoCollectionHandler(connect());
$url = $_SERVER['REQUEST_URI'];
$pos = strpos($url, 'View') + 5;
$len = strlen($url);
$fileName = substr($url, $pos, $len);

$controller = new Controller();

try {
    if (isset($_SESSION['username'])) {
        $cursor = $document->byExample('post', ['visibility' => "Public"], ['visibility' => "Private"]);
        $valueFound = $cursor->getCount();

        if ($valueFound == 0) { ?>
            <h5>Nothing to show yet.</h5><br><br><br><br>
            <?php
        } else {
            if ($fileName == 'profile.php') {
                $dtoPost_Comment_Tag = $controller->getPosts($_SESSION['username']);
            } else {
                $dtoPost_Comment_Tag = $controller->getPosts(null);
            }
            $postCounter = 0;
            if (isset($dtoPost_Comment_Tag)) {

                foreach ($dtoPost_Comment_Tag as $singlePost) {
                    $comments = $controller->getComments($singlePost['key']);
                    $numberOfComments = ($comments != null) ? sizeof($comments) : 0;
                    ?>

                    <div class="panel container" style="background-color: white;"
                         id="<?php echo $singlePost['key']; ?>">
                        <div class="col-md-12 container" style="background-color: white;">
                            <div class="media">

                                <div class="media-body">
                                    <div class="row">
                                        <a href="javascript:void(0)">
                                            <img src="img/user.png" alt="" class="media-object">
                                        </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <h4 class="media-heading"><?php echo $singlePost['owner']; ?><br>
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
                                        <li><a href="#" title="" class="prevent" onclick="toggleDivAnswer('commentDiv');"><i class="far fa-comment-alt"></i>
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
                                foreach ($comments as $singleComment) { ?>
                                    <div class="col-md-12 commentsblock border-top" id="commentDiv" style="display: none;">
                                        <div class="media">
                                            <div class="media-left"><a href="javascript:void(0)"> <img
                                                            alt="64x64"
                                                            src="img/user.png"
                                                            class="media-object"> </a></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <div class="media-body">
                                                <h4 class="media-heading"><?php echo $singleComment['commentOwner']; ?>
                                                    <br>
                                                    <small>
                                                        <i class="fa fa-clock-o"></i> <?php echo $singleComment['time']; ?>
                                                    </small>
                                                </h4>
                                                <hr>
                                                <p><?php echo $singleComment['text']; ?></p>
                                                <ul class="nav nav-pills pull-left" id="<?php echo 'commentTags' . $postCounter; ?>">
                                                    <li><a id="commentLike"
                                                           href="#"
                                                        ><i class="far fa-thumbs-up"></i>
                                                            0
                                                        </a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <li><a href="#" title="" onclick="toggleDivAnswer('answerDiv');" class="prevent"><i class="far fa-comment-alt"></i>
                                                            <?php echo 'View comments ('  . ')'; ?>
                                                        </a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <li><a href="#" title="" class="prevent"><i class="fas fa-tags"></i>
                                                            <?php echo str_replace(',', ', ', $singleComment['tagsComment']); ?>
                                                        </a></li>
                                                </ul>
                                                <br>
                                                <hr>

                                            </div>
                                        </div>
                                        <!--Comment's answers-->
                                        <div class="col-md-12" id="answerDiv" style="display: none;">
                                            <div class="media" style="margin-left: 20px;">
                                                <div class="media-left"><a href="javascript:void(0)"> <img
                                                                alt="64x64"
                                                                src="img/user.png"
                                                                class="media-object"> </a></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <div class="media-body">
                                                    <h4 class="media-heading"><?php echo $singleComment['commentOwner']; ?>
                                                        <br>
                                                        <small>
                                                            <i class="fa fa-clock-o"></i> <?php echo $singleComment['time']; ?>
                                                        </small>
                                                    </h4>
                                                    <hr>
                                                    <p><?php echo $singleComment['text']; ?></p>
                                                    <ul class="nav nav-pills pull-left" id="<?php echo 'commentTags' . $postCounter; ?>">
                                                        <li><a id="commentLike"
                                                               href="#"
                                                            ><i class="far fa-thumbs-up"></i>
                                                                0
                                                            </a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <li><a href="#" title=""><i class="far fa-comment-alt"></i>
                                                                <?php echo 'View comments ('  . ')'; ?>
                                                            </a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <li><a href="#" title=""><i class="fas fa-tags"></i>
                                                                <?php echo str_replace(',', ', ', $singleComment['tagsComment']); ?>
                                                            </a></li>
                                                    </ul>
                                                    <br>
                                                    <hr>

                                                </div>
                                            </div>

                                        <!--Answer a comment-->
                                        <form action="<?php echo 'comment.inc.php?' . $singlePost['key'] . '%answerbtn' . $postCounter . '@' . $fileName; ?>"
                                              method="post">
                    <textarea id="<?php echo 'commentAnswer' . $postCounter; ?>"
                              name="<?php echo 'commentAnswer' . $postCounter; ?>" type="text"
                              class="form-control classComment"
                              placeholder="Answer this comment..." style="resize: none;"></textarea><br>
                                            <input id="<?php echo 'tagsCommentAnswer' . $postCounter; ?>"
                                                   name="<?php echo 'tagsCommentAnswer' . $postCounter; ?>" type="text"
                                                   data-role="tagsinput"
                                                   placeholder="Tags"><br><br>
                                            <div class="row" style="">
                                                <div class="col-md-4"></div>
                                                <div class="col-md-4 imgUp">
                                                    <div class="imagePreview"></div>
                                                    <label class="btn btn-primary"><i class="fas fa-upload"></i>
                                                        Upload photo<input id="postImage" name="postImage" type="file" accept='image/*'
                                                                           class="uploadFile img" value="Upload Photo"
                                                                           style="width: 0px;height: 0px;overflow: hidden;">
                                                    </label>
                                                </div><!-- col-2 -->
                                                <div class="col-md-4"></div>
                                            </div><!-- row -->
                                            <hr>
                                            <button id="<?php echo 'answerbtn' . $postCounter; ?>"
                                                    name="<?php echo 'answerbtn' . $postCounter; ?>"
                                                    class="btn btn-primary pull-right btnComment" disabled>  <!--disabled-->
                                                <!--<i class="fas fa-cog"></i>-->Comment
                                            </button>
                                            <br><br>
                                        </form><!--/Answer a comment-->
                                        </div><!--/Comment's answers-->
                                    </div>
                                    <?php
                                }
                            }
                            ?>

                            <hr>

                            <form action="<?php echo 'comment.inc.php?' . $singlePost['key'] . '%commentbtn' . $postCounter . '@' . $fileName; ?>"
                                  method="post">
                    <textarea id="<?php echo 'comment' . $postCounter; ?>"
                              name="<?php echo 'comment' . $postCounter; ?>" type="text"
                              class="form-control classComment"
                              placeholder="Type a new comment..." style="resize: none;"></textarea><br>
                                <input id="<?php echo 'tagsComment' . $postCounter; ?>"
                                       name="<?php echo 'tagsComment' . $postCounter; ?>" type="text"
                                       data-role="tagsinput"
                                       placeholder="Tags">
                                <hr>
                                <button id="<?php echo 'commentbtn' . $postCounter; ?>"
                                        name="<?php echo 'commentbtn' . $postCounter; ?>"
                                        class="btn btn-primary pull-right btnComment" disabled>  <!--disabled-->
                                    <!--<i class="fas fa-cog"></i>-->Comment
                                </button>
                                <br><br>
                            </form>

                        </div>
                    </div>
                    <?php
                    $postCounter++;
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