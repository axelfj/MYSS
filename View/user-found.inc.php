<?php
$controller = new Controller();

foreach ($dtoUser as $singleUser) {
    $userInfo = $controller->getProfile($singleUser['username']);
    ?>
    <div class="panel container" style="background-color: white;" id="<?php echo $singleUser['key']; ?>">
        <div class="col-md-12 container" style="background-color: white;">
            <div class="media">

                <div class="media-body">
                    <div class="row" style="padding-left: 20px;">
                        <a href="javascript:void(0)">
                            <img <?php echo "src= " . $userInfo['userImage']; ?> alt="" class="media-object">
                        </a>
                        <h4 style="padding-left: 10px;padding-top: 10px;">
                            <a href="<?php echo 'profile.php?' . $userInfo['username']; ?>"><?php echo $userInfo['username']; ?></a><br>
                            <p style="font-size: 15px;">
                                <?php echo $userInfo['name']; ?>
                            </p>
                        </h4>
                    </div>

                    <br>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>