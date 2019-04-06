<?php
// Gets the url and finds the comment button that was pressed.
$url          = $_SERVER['REQUEST_URI'];
$pos          = strpos($url, 'commentbtn')+10;
$len          = strlen($url);
$buttonNumber = substr($url, $pos, $len);

if(isset($_POST['commentbtn'.$buttonNumber])){
    echo 'sss:' . $_POST['comment' . $buttonNumber];
}
else{
    echo 'nooo';
}
?>
<div class="col-md-12 commentsblock border-top">
    <div class="media">
        <div class="media-left"><a href="javascript:void(0)"> <img alt="64x64"
                                                                   src="img/user.png"
                                                                   class="media-object"> </a></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <div class="media-body">
            <h4 class="media-heading">Astha Smith</h4>
            <p>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante
                sollicitudin commodo. Cras purus odio, vestibulum in vulputate at, tempus viverra
                turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue
                felis in faucibus.</p>
        </div>
    </div>
</div>
