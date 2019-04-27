<div id="<?php echo 'like' . $postCommentOrAnswerKey; ?>" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Liked by</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <h5 class="modal-title">
                    <?php
                    $cursorWithUserKeys = PostQuery::getUserLiked($postCommentOrAnswerKey);
                    for ($i = 0; $i < sizeof($cursorWithUserKeys); $i++) {
                        $key = substr($cursorWithUserKeys[$i], 5, strlen($cursorWithUserKeys[$i]));
                        $cursorUser = UserQuery::getUsernameAndImage($key);
                        echo '<div class="row"  style="padding-left: 20px;">
                                <a href="javascript:void(0)">
                                    <img src= "' . $cursorUser['userImage'] . '" class="media-object"></a>
                                        <h4 style="padding-left: 10px;padding-top: 10px;">
                                        <a href="profile.php?' . $cursorUser['username'] . '">' . $cursorUser['username'] . '</a></h4>
                              </div>
                              <hr>';
                    }
                    ?></h5>
            </div>
        </div>
    </div>
</div>


