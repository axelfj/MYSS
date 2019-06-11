<?php
require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/CollectionHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";
require_once "../Controller/Controller.php";
require_once "../Controller/DTOPost_Comment_Tag.php";

session_start();

// Gets the url and finds the comment button that was pressed.
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Document as ArangoDocument;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\Statement as ArangoStatement;

date_default_timezone_set('America/Costa_Rica');
// Gets the number of the button that was pressed.
$url = $_SERVER['REQUEST_URI'];
$posStart = strpos($url, 'commentbtn');

if ($posStart != false) {
    $posStart += 10;
    $buttonName = 'commentbtn';
} else {
    $posStart = strpos($url, 'answerbtn') + 9;
    $buttonName = 'answerbtn';
}

$posEnd = strpos($url, '@');
$buttonNumber = substr($url, $posStart, $posEnd - $posStart);

// Gets the key of the post that is getting commented.
$posStart = strpos($url, '?') + 1;
$posEnd = strpos($url, '%');
$postKey = substr($url, $posStart, $posEnd - $posStart);

$controller = new Controller();
$dtoComment = new DTOPost_Comment_Tag();
echo 'pos: ' . $posStart . ' name: ' . $buttonName . ' numb: ' . $buttonNumber;

if (isset($_POST[$buttonName . $buttonNumber])) {
    try {
        if (!empty($_POST[substr($buttonName, 0, -3) . $buttonNumber])) {
            $comment = array();
            $comment['text'] = $_POST[substr($buttonName, 0, -3) . $buttonNumber];
            $comment['tagsComment'] = $_POST['tags_' . substr($buttonName, 0, -3) . $buttonNumber];
            $comment['commentOwner'] = $_SESSION['username'];

            $correctImageComment = $controller->verifyImageUpload($comment, 'commentImage');

            if (isset($correctImageComment)) {
                $dtoComment->setComments($correctImageComment);
                $controller->createNewComment($dtoComment, $postKey, substr($buttonName, 0, -3));
                unset($comment);
            }
        }
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}

$pos = strpos($url, 'profile.php');
if ($pos == false) {
    $pos = strpos($url, 'index.php');
}
$len = strlen($url);
$fileName = substr($url, $pos, $len);

header('Location: ' . $fileName);




