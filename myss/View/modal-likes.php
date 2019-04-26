<div id="<?php echo 'like' . $postCounter; ?>" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Liked by</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                <h5 class="modal-title">@<?php echo $singlePost['owner']; ?></h5>
            </div>
        </div>
    </div>
</div>


