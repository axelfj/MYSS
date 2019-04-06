<?php
require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/CollectionHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";
session_start();
// Gets the url and finds the comment button that was pressed.
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Document as ArangoDocument;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;

$url          = $_SERVER['REQUEST_URI'];
$pos          = strpos($url, 'commentbtn')+10;
$len          = strlen($url);
$buttonNumber = substr($url, $pos, $len);

if(isset($_POST['commentbtn'.$buttonNumber])){
    echo 'sss:' . $_POST['comment' . $buttonNumber];

    try {
        if (!empty($_POST['comment' . $buttonNumber])) {

            $database = new ArangoDocumentHandler(connect());
            $document = new ArangoCollectionHandler(connect());

            $text       = $_POST['comment' . $buttonNumber];
            $tagsPost   = $_POST['tagsComment' . $buttonNumber];
            $owner      = $_SESSION['username'];
            $time       = date('j-m-y H:i');

            $post = new ArangoDocument();
            $post->set("text", $text);
            $post->set("tagsPost", $tagsPost);
            $post->set("owner", $owner);
            $post->set("time", $time);

            $newPost = $database->save("posts", $post);
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
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
