<?php

use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\Statement as ArangoStatement;

$database = connect();
$document = new ArangoCollectionHandler(connect());

$cursor = $document->byExample('posts', ['owner' => $_SESSION['username']]);

// Count it, 0 = he's not in the database.
$valueFound = $cursor->getCount();

if ($valueFound == 0) { ?>
    <h5>You don't have any posts yet.</h5><br><br><br><br>
    <?php
} else {

    $query = 'FOR x IN posts FILTER x.owner == @username SORT x.time DESC RETURN {key: x._key,
        title: x.title, text: x.text, tagsPost: x.tagsPost, visibility: x.visibility, time: x.time}';

    $statement = new ArangoStatement(
        $database,
        array(
            "query" => $query,
            "count" => true,
            "batchSize" => 1,   // It is suppose to only bring one.
            "sanitize" => true,
            "bindVars" => array("username" => $_SESSION['username'])
        )
    );

    $cursor = $statement->execute();
    $resultingDocuments = array();

    if ($cursor->getCount() > 0) {

        $userPosts = array();
        $postCounter = 0;

        foreach ($cursor as $key => $value) {

            $resultingDocuments[$key] = $value;
            $userPosts['title'] = $resultingDocuments[$key]->get('title');
            $userPosts['text'] = $resultingDocuments[$key]->get('text');
            $userPosts['tagsPost'] = $resultingDocuments[$key]->get('tagsPost');
            $userPosts['visibility'] = $resultingDocuments[$key]->get('visibility');
            $userPosts['time'] = $resultingDocuments[$key]->get('time');

            ?>

            <div class="panel container" style="background-color: white;">
                <div class="btn-group pull-right postbtn">
                    <button type="button" class="dotbtn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"
                            style="padding-top: 10px;">
                        <span class="dots"></span></button>
                    <ul class="dropdown-menu pull-right" role="menu">
                        <li><a href="javascript:void(0)">Hide this</a></li>
                        <li><a href="javascript:void(0)">Report</a></li>
                    </ul>
                </div>
                <div class="col-md-12 container" style="background-color: white;">
                    <div class="media">
                        <div class="media-left"><a href="javascript:void(0)"><img
                                        src="img/user.png"
                                        alt=""
                                        class="media-object"> </a></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <div class="media-body">
                            <h4 class="media-heading"><?php echo $_SESSION['username']; ?><br>
                                <small><i class="fa fa-clock-o"
                                          id="<?php echo 'time' . $postCounter; ?>"></i> <?php echo $userPosts['time']; ?>
                                </small>
                            </h4>
                            <hr>
                            <h5 id="<?php echo 'title' . $postCounter; ?>"><?php echo $userPosts['title']; ?></h5><br>
                            <p id="<?php echo 'text' . $postCounter; ?>"><?php echo $userPosts['text']; ?></p>

                            <ul class="nav nav-pills pull-left" id="<?php echo 'tags' . $postCounter; ?>">
                                <li><a href="" title=""><i class="far fa-thumbs-up"></i> 2015</a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <li><a href="" title=""><i class="far fa-comment-alt"></i> 25</a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <li><a href="" title=""><i
                                                class="fas fa-tags"></i> <?php echo str_replace(',', ', ', $userPosts['tagsPost']); ?>
                                    </a></li>
                            </ul>
                        </div>
                    </div>

                    <?php /*include "comment.inc.php"; */ ?>

                    <hr>
                    <form action="<?php echo 'comment.inc.php' . '?commentbtn' . $postCounter; ?>" method="post">
                    <textarea id="<?php echo 'comment' . $postCounter; ?>"
                              name="<?php echo 'comment' . $postCounter; ?>" type="text" class="form-control classComment"
                              placeholder="Type a new comment..." style="resize: none;"></textarea><br>
                        <input id="<?php echo 'tagsComment' . $postCounter; ?>"
                               name="<?php echo 'tagsComment' . $postCounter; ?>" type="text" data-role="tagsinput"
                               placeholder="Tags">
                        <hr>
                        <button id="<?php echo 'commentbtn' . $postCounter; ?>"
                                name="<?php echo 'commentbtn' . $postCounter; ?>"
                                class="btn btn-primary pull-right btnComment" disabled>  <!--disabled-->
                            <!--<i class="fas fa-cog"></i>-->Comment
                        </button>
                    </form>
                    <br><br><br>
                </div>
            </div>
            <?php
            $postCounter++;
        }
    }
}
?>