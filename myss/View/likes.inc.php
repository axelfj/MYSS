<?php

require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/CollectionHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";
require_once "../Controller/createEdges.php";
require_once "../Controller/readCollection.php";

session_start();

use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use function ArangoDBClient\readCollection;
use ArangoDBClient\Statement as ArangoStatement;


$url = $_SERVER['REQUEST_URI'];
$posStart = strpos($url, '@') + 1;
$posEnd = strlen($url);
$postKey = substr($url, $posStart, $posEnd - $posStart);
$pos = strpos($url, 'profile.php');
if ($pos == false) {
    header('Location: index.php');
} else {
    header('Location: profile.php');
}
try {
    $database = connect();
    $document = new ArangoCollectionHandler(connect());

    $cursor = $document->byExample('post', ['visibility' => "Public"], ['visibility' => "Private"]);

    if ($cursor->getCount() != 0) {
        $statements = [
            'FOR u IN liked 
            FILTER u._to == @postKey && u._from == @userKey 
            RETURN u._from'
            => ['postKey' => $postKey, 'userKey' => $_SESSION['userKey']]];
        $liked = readCollection($statements);

        if ($_SESSION['userKey'] != $liked->current()){
            userLiked($_SESSION['userKey'], $postKey);
        }
        else{
            // user already liked //
        }
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
