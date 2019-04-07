<?php
require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/CollectionHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";
require_once "../Controller/edges.php";

session_start();

// Gets the url and finds the comment button that was pressed.
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Document as ArangoDocument;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\Statement as ArangoStatement;

// Gets the number of the button that was pressed.
$url = $_SERVER['REQUEST_URI'];
$pos = strpos($url, 'commentbtn') + 10;
$len = strlen($url);
$buttonNumber = substr($url, $pos, $len);

// Gets the key of the post that is getting commented.
$posStart = strpos($url, '?') + 1;
$posEnd = strpos($url, '%');
$postKey = substr($url, $posStart, $posEnd - $posStart);

if (isset($_POST['commentbtn' . $buttonNumber])) {
    try {
        if (!empty($_POST['comment' . $buttonNumber])) {

            $database = new ArangoDocumentHandler(connect());
            $document = new ArangoCollectionHandler(connect());

            $text = $_POST['comment' . $buttonNumber];
            $tagsPost = $_POST['tagsComment' . $buttonNumber];
            $owner = $_SESSION['username'];
            $time = date('j-m-y H:i');

            $comment = new ArangoDocument();
            $comment->set("text", $text);
            $comment->set("tagsComment", $tagsPost);
            $comment->set("commentOwner", $owner);
            $comment->set("time", $time);

            $newPost = $database->save("comment", $comment);

            // Gets just the number of key, because "$newPost" stores something like "post/83126"
            // and we just need that number.
            $pos = strpos($newPost, "/") + 1;
            $commentKey = substr($newPost, $pos, strlen($newPost));

            postHasComment($postKey, $commentKey);
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}

header('Location: profile.php');




