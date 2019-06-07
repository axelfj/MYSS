<?php
$userInfo = $controller->getProfile($_SESSION['username']);



?>
<div id="editProfile" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit your information</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">

                <form action="profile.php" method="post" class="form-signin" enctype="multipart/form-data">
                    <div class="form-group">
                        <input id="changeUsername" name="changeUsername" type="text" class="form-control" required
                               value="<?php echo $userInfo['username']; ?>">
                    </div>
                    <div class="form-group">
                        <input id="changeEmail" name="changeEmail" type="text" class="form-control" required
                               value="<?php echo $userInfo['email']; ?>">
                    </div>
                    <div class="form-group">
                        <input id="oldPassword" name="oldPassword" type="password" class="form-control"
                               placeholder="Current password">
                    </div>
                    <div class="form-group">
                        <input id="newPassword" name="newPassword" type="password" class="form-control"
                               placeholder="New password">
                    </div>
                    <div class="form-group">
                        <input id="changeName" name="changeName" type="text" class="form-control" required
                               value="<?php echo $userInfo['name']; ?>">
                    </div>
                    <div class="form-group">
                        <input id="changeBirthday" name="changeBirthday" type="date" class="form-control" parsley-trigger="change" required
                               value="<?php echo $userInfo['birthday']; ?>">
                    </div>
                    <div class="form-group" style="text-align:center;">
                        <label class="genric-btn info-border circle">
                            <i class="fas fa-upload"></i>Change picture
                            <input id="changeUserImage" name="changeUserImage" type="file" accept='image/*'
                                   class="uploadFile img" value="Upload Photo"
                                   style="width: 0px;height: 0px;overflow: hidden;">
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button id="saveBtn" name="saveBtn" class="btn btn-primary pull-right btnComment">
                            Save changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>