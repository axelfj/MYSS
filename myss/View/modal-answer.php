<div id="<?php echo 'modal' . $commentKey; ?>" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reply to @<?php echo $singleComment['commentOwner']; ?></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <form action="<?php echo 'comment.inc.php?' . $commentKey . '%' . 'answerbtn' . $commentKey . '@' . $fileName; ?>"
                      method="post" enctype="multipart/form-data">
                <textarea id="<?php echo 'answer' . $commentKey; ?>"
                          name="<?php echo 'answer' . $commentKey; ?>" type="text"
                          class="form-control classComment"
                          placeholder="Type a new reply..." style="resize: none;"></textarea><br>
                    <input id="<?php echo 'tags_answer' . $commentKey; ?>"
                           name="<?php echo 'tags_answer' . $commentKey; ?>" type="text"
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
                        </div><!-- col-2 -->
                        <div class="col-md-4"></div>
                    </div><!-- row -->
                    <hr>
                    <button id="<?php echo 'answerbtn' . $commentKey; ?>"
                            name="<?php echo 'answerbtn' . $commentKey; ?>"
                            class="btn btn-primary pull-right btnComment" disabled>  <!--disabled-->
                        <!--<i class="fas fa-cog"></i>-->Answer
                    </button>
                </form>
                <br><br>

            </div>

        </div>

    </div>
</div>


