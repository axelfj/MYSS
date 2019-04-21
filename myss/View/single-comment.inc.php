<!--
|-----------------------------------------------------------------------------|
| Note:                                                                       |
| The PHP variables that appear in this file, are declared in 'post.inc.php'. |
|-----------------------------------------------------------------------------|
-->

<div class="col-md-12 commentsblock border-top commentDiv" id="<?php echo $singleComment['key']; ?>">
    <div class="media">
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
            <p><?php echo $singleComment['text']; ?></p>
            <ul class="nav nav-pills pull-left" id="<?php echo 'commentTags' . $postOrCommentCounter; ?>">
                <li><a id="commentLike"
                       href="#"
                    ><i class="far fa-thumbs-up"></i>
                        0
                    </a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <li><a href="#" title="" onclick="toggleDivAnswer('answerDiv');" class="prevent"><i
                                class="far fa-comment-alt"></i>
                        <?php echo 'View comments (' . ')'; ?>
                    </a></li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <li><a href="#" title="" class="prevent"><i class="fas fa-tags"></i>
                        <?php echo str_replace(',', ', ', $singleComment['tagsComment']); ?>
                    </a></li>
            </ul>
            <br>
            <hr>
        </div>
    </div>
</div>