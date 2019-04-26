<form action="<?php echo 'comment.inc.php?' . $singlePost['key'] . '%' . 'commentbtn' . $postCounter . '@' . $fileName; ?>"
      method="post" enctype="multipart/form-data">
                    <textarea id="<?php echo 'comment' . $postCounter; ?>"
                              name="<?php echo 'comment' . $postCounter; ?>" type="text"
                              class="form-control classComment"
                              placeholder="Type a new comment..." style="resize: none;"></textarea><br>
    <input id="<?php echo 'tags_comment' . $postCounter; ?>"
           name="<?php echo 'tags_comment' . $postCounter; ?>" type="text"
           data-role="tagsinput"
           placeholder="Tags">
    <div class="row" style="">
        <div class="col-md-4"></div>
        <div class="col-md-4 imgUp">
            <div class="imagePreview"></div>
            <label class="btn btn-primary"><i class="fas fa-upload"></i>
                Upload photo
                <input id="commentImage" name="commentImage" type="file" accept='image/*'
                       class="uploadFile img" value="Upload Photo"
                       style="width: 0px;height: 0px;overflow: hidden;">
            </label>
        </div><!-- col-md-4 -->
        <div class="col-md-4"></div>
    </div><!-- row -->
    <hr>
    <button id="<?php echo 'commentbtn' . $postCounter; ?>"
            name="<?php echo 'commentbtn' . $postCounter; ?>"
            class="btn btn-primary pull-right btnComment" disabled> Comment
    </button>
    <br><br>
</form>
