<?php

require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/CollectionHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";
require_once "../Model/createEdges.php";
require_once "../Model/executeQuery.php";

session_start();

use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use function ArangoDBClient\readCollection;
use ArangoDBClient\Statement as ArangoStatement;


$url = $_SERVER['REQUEST_URI'];

$posStart = strpos($url, '@') + 1;
$posEnd = strlen($url);
$answerKey = substr($url, $posStart, $posEnd - $posStart);
$pos = strpos($url, 'profile.php');
if ($pos == false) {
    header('Location: index.php');
} else {
    header('Location: profile.php');
}
try {
    userLikedAnswer($_SESSION['userKey'], $answerKey);
} catch (Exception $e) {
    echo $e->getMessage();
}
