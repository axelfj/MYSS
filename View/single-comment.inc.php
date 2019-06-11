<!--
|-----------------------------------------------------------------------------|
| Note:                                                                       |
| The PHP variables that appear in this file, are declared in 'post.inc.php'. |
|-----------------------------------------------------------------------------|
-->

<div class="col-md-12 commentsblock border-top <?php echo $divClassName; ?>" style="display: none;">
    <div class="media" <?php if (strpos($divClassName, 'answer') !== false) echo 'style="margin-left: 30px;"' ?>>
        <div class="media-left"><a href="javascript:void(0)">
                <img <?php
                echo "src= " . $imageCommentOwner['userImage'];
                ?> alt="" class="media-object"> </a></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <div class="media-body">
            <h4 class="media-heading"><a href="#">
                    <a href="<?php echo 'profile.php?' . $singleComment['commentOwner']; ?>">
                        <?php echo $singleComment['commentOwner']; ?>
                    </a>
                    <br>
                    <small>
                        <i class="fa fa-clock-o"></i> <?php echo $singleComment['time']; ?>
                    </small>
            </h4>
            <hr>
            <?php
            if ($singleComment['destination'] != '') { ?>
                <img style="max-width:100%; max-height:100%;" src="<?php echo $singleComment['destination']; ?>">
                <br><br>
                <?php
            } ?>
            <p><?php echo $singleComment['text']; ?></p>

            <ul class="nav nav-pills pull-left" id="<?php echo 'commentTags' . $postOrCommentCounter; ?>">
                <li><a id="commentLike"
                       href="<?php
                       $commentCountLikes = $controller->verifyIfUserLikedComment($singleComment['commentKey'], $_SESSION['userKey']);
                       $answerCountLikes = $controller->verifyIfUserLikedAnswer($singleComment['commentKey'], $_SESSION['userKey']);
                       if ($commentCountLikes->getCount() == 0 && $divClassName[0] != 'a') {
                           echo 'commentLikes.inc.php?' . $fileName . '@' . $singleComment['commentKey'];
                       } else if ($answerCountLikes->getCount() == 0 && $divClassName[0] == 'a') {
                           echo 'answerLikes.inc.php?' . $fileName . '@' . $singleComment['commentKey'];
                       } else {
                           echo '#';
                       }

                       ?>"
                    ><i class="far fa-thumbs-up"></i>
                        <?php if ($divClassName[0] == 'a') {
                            $likeCount = PostQuery::getLikeCount($singleComment['commentKey'], 'answer');
                        } else {
                            $likeCount = PostQuery::getLikeCount($singleComment['commentKey'], 'comment');
                        }
                        echo $likeCount;
                        ?>
                    </a>
                    <a href="#" data-toggle="modal"
                       data-target="#<?php echo 'like' . $postCommentOrAnswerKey; ?>">
                        <?php
                        $userOrUsers = ($likeCount == 1) ? 'user ' : 'users ';
                        echo $userOrUsers . 'liked';
                        ?>
                    </a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php
                if (strpos($divClassName, 'answer') === false) {
                    ?>
                    <li><a href="#" title=""
                           onclick="toggleDivAnswer('<?php echo 'answer' . $singleComment['key']; ?>');"
                           class="prevent"><i class="far fa-comment"></i>
                            </i>
                            <?php echo 'View replies (' . $numberOfAnswers . ')'; ?>
                        </a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <li><a href="#" title="" data-toggle="modal" data-target="#<?php echo 'modal' . $commentKey; ?>"
                           class="prevent">
                            <i class="far fa-comment-dots"></i> Reply</a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php
                } ?>
                <li><a href="#" title="" class="prevent"><i class="fas fa-tags"></i>
                        <?php echo str_replace(',', ', ', $singleComment['tagsComment']); ?>
                    </a></li>
            </ul>
            <br><br>
        </div>
    </div>
</div>

<?php
include 'modal-answer.php';
include 'modal-likes.php';
?>

