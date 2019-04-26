<?php

require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/CollectionHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";
require_once "../Model/createEdges.php";
require_once "../Model/executeQuery.php";

session_start();

use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;

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
        userLikedPost($_SESSION['userKey'], $postKey);
    }

} catch (Exception $e) {
    echo $e->getMessage();
}
