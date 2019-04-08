<?php

require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/CollectionHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";
require_once "../Controller/edges.php";

session_start();

// Gets the url and finds the comment button that was pressed.
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Statement as ArangoStatement;

// Gets the key of the post that is getting commented.
$url = $_SERVER['REQUEST_URI'];
$posStart = strpos($url, '@') + 1;
$posEnd = strlen($url);
$postKey = substr($url, $posStart, $posEnd - $posStart);

$database = connect();
$document = new ArangoCollectionHandler(connect());

$cursor = $document->byExample('post', ['visibility' => "Public"], ['visibility' => "Private"]);
$valueFound = $cursor->getCount();

if ($valueFound != 0) {


    $query = 'FOR x IN post FILTER x._key == @var UPDATE { _key: x._key, likes: x.likes+1} IN post';

    $statement = new ArangoStatement(
        $database,
        array(
            "query" => $query,
            "count" => true,
            "batchSize" => 1,   // It is suppose to only bring one.
            "sanitize" => true,
            "bindVars" => array("var" => $postKey)
        )
    );

    $cursor = $statement->execute();
}

$pos = strpos($url, 'profile.php');
if ($pos == false) {
    header('Location: index.php');
} else {
    header('Location: profile.php');
}