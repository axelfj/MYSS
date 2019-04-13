<?php

use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use function ArangoDBClient\readCollection;
use ArangoDBClient\Statement as ArangoStatement;

require_once "../Controller/readCollection.php";

$database = connect();
$document = new ArangoCollectionHandler(connect());
$url = $_SERVER['REQUEST_URI'];
$pos = strpos($url, 'View') + 5;
$len = strlen($url);
$fileName = substr($url, $pos, $len);

try {
    if (isset($_SESSION['username'])) {
        $cursor = $document->byExample('post', ['visibility' => "Public"], ['visibility' => "Private"]);
        $valueFound = $cursor->getCount();

        if ($valueFound == 0) { ?>
            <h5>Nothing to show yet.</h5><br><br><br><br>
            <?php
        } else {
            $statements = '';
            if ($fileName == 'profile.php') {
                $statements = [
                    'FOR u IN post 
                        FILTER u.owner == @username 
                        SORT u.time DESC 
                        RETURN {key: u._key, owner: u.owner, title: u.title, text: u.text, 
                        tagsPost: u.tagsPost, visibility: u.visibility, time: u.time, likes: u.likes}'
                    => ['username' => $_SESSION['username']]];
            } else {
                $statements = [
                    'FOR u IN post 
                        FILTER u.visibility == @visibility 
                        SORT u.time DESC 
                        RETURN {key: u._key, owner: u.owner, title: u.title, text: u.text, 
                        tagsPost: u.tagsPost, visibility: u.visibility, time: u.time, likes: u.likes}'
                    => ['visibility' => 'Public']];
            }

            $cursor = readCollection($statements);
            $resultingDocuments = array();

            if ($cursor->getCount() > 0) {

                $userPosts = array();
                $postCounter = 0;

                foreach ($cursor as $key => $value) {

                    $resultingDocuments[$key] = $value;
                    $userPosts['owner'] = $resultingDocuments[$key]->get('owner');
                    $userPosts['title'] = $resultingDocuments[$key]->get('title');
                    $userPosts['text'] = $resultingDocuments[$key]->get('text');
                    $userPosts['tagsPost'] = $resultingDocuments[$key]->get('tagsPost');
                    $userPosts['visibility'] = $resultingDocuments[$key]->get('visibility');
                    $userPosts['time'] = $resultingDocuments[$key]->get('time');
                    $userPosts['likes'] = $resultingDocuments[$key]->get('likes');

                    $postKey = $resultingDocuments[$key]->get('key');

                    $statements = [
                        'FOR u IN has_comment 
                        FILTER u.from == @from 
                        SORT u.time DESC 
                        RETURN {key: u._key, from: u._from, to: u._to}'
                        => ['from' => 'post/'.$postKey]];
                    $cursor = readCollection($statements);
                    $resultingComments = array();
                    $numberOfComments = $cursor->getCount();
                    ?>

                    <div class="panel container" style="background-color: white;"
                         id="<?php echo $resultingDocuments[$key]->get('key'); ?>">
                        <div class="col-md-12 container" style="background-color: white;">
                            <div class="media">
                                <div class="media-left"><a href="javascript:void(0)"><img
                                                src="img/user.png"
                                                alt=""
                                                class="media-object"> </a></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <div class="media-body">
                                    <h4 class="media-heading"><?php echo $userPosts['owner']; ?><br>
                                        <small><i class="fa fa-clock-o"
                                                  id="<?php echo 'time' . $postCounter; ?>"></i> <?php echo $userPosts['time']; ?>
                                        </small>
                                    </h4>
                                    <hr>
                                    <h5 id="<?php echo 'title' . $postCounter; ?>"><?php echo $userPosts['title']; ?></h5>
                                    <br>
                                    <p id="<?php echo 'text' . $postCounter; ?>"><?php echo $userPosts['text']; ?></p>

                                    <ul class="nav nav-pills pull-left" id="<?php echo 'tags' . $postCounter; ?>">
                                        <li><a id="like"
                                               href="<?php echo 'likes.inc.php?' . $fileName . '@' . $resultingDocuments[$key]->get('key'); ?>"
                                               title=""><i
                                                        class="far fa-thumbs-up"></i> <?php echo $userPosts['likes']; ?>
                                            </a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <li><a href="" title=""><i
                                                        class="far fa-comment-alt"></i> <?php echo $numberOfComments; ?>
                                            </a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <li><a href="" title=""><i class="fas fa-tags"></i>
                                                <?php echo str_replace(',', ', ', $userPosts['tagsPost']); ?>
                                            </a></li>
                                    </ul>
                                </div>
                            </div>

                            <?php
                            if ($numberOfComments > 0) {
                                $commentsKeys = array();

                                foreach ($cursor as $key => $value) {
                                    $resultingComments[$key] = $value;
                                    $commentsKeys['postKey'] = $resultingComments[$key]->get('from');
                                    $commentsKeys['commentKey'] = substr($resultingComments[$key]->get('to'), 8, strlen($resultingComments[$key]->get('to')));

                                    $query = 'FOR x IN comment FILTER x._key == @commentKey RETURN {key: x._key,
        commentOwner: x.commentOwner, tagsComment: x.tagsComment, text: x.text, time: x.time}';

                                    $statement = new ArangoStatement(
                                        $database,
                                        array(
                                            "query" => $query,
                                            "count" => true,
                                            "batchSize" => 1,   // It is suppose to only bring one.
                                            "sanitize" => true,
                                            "bindVars" => array("commentKey" => $commentsKeys['commentKey'])
                                        )
                                    );

                                    $cursor = $statement->execute();
                                    $resultingDocuments2 = array();

                                    if ($cursor->getCount() > 0) {
                                        $userComments = array();

                                        foreach ($cursor as $key => $value) {

                                            $resultingDocuments2[$key] = $value;
                                            $userComments['commentOwner'] = $resultingDocuments2[$key]->get('commentOwner');
                                            $userComments['tagsComment'] = $resultingDocuments2[$key]->get('tagsComment');
                                            $userComments['text'] = $resultingDocuments2[$key]->get('text');
                                            $userComments['time'] = $resultingDocuments2[$key]->get('time'); ?>
                                            <div class="col-md-12 commentsblock border-top">
                                                <div class="media">
                                                    <div class="media-left"><a href="javascript:void(0)"> <img
                                                                    alt="64x64"
                                                                    src="img/user.png"
                                                                    class="media-object"> </a></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <div class="media-body">
                                                        <h4 class="media-heading"><?php echo $userComments['commentOwner']; ?>
                                                            <br>
                                                            <small>
                                                                <i class="fa fa-clock-o"></i> <?php echo $userComments['time']; ?>
                                                            </small>
                                                        </h4>
                                                        <hr>
                                                        <p><?php echo $userComments['text']; ?></p>

                                                        <ul class="nav nav-pills pull-left">
                                                            <li><a href="" title=""><i
                                                                            class="fas fa-tags"></i> <?php echo str_replace(',', ', ', $userComments['tagsComment']); ?>
                                                                </a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div> <?php
                                        }
                                    }
                                }
                            } ?>

                            <hr>

                            <form action="<?php echo 'comment.inc.php?' . $postKey . '%commentbtn' . $postCounter . '@' . $fileName; ?>"
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

} catch (Exception $e) {
    $e->getMessage();
}

?>