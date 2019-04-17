<?php

require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/CollectionHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Edge.php";

use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\EdgeHandler;
use ArangoDBClient\Document as ArangoDocument;


$connection = connect();
$collectionHandler = new ArangoCollectionHandler($connection);
$edgeHandler = new EdgeHandler($connection);

function createTag($name){

    $database = new ArangoDocumentHandler(connect());

    $tag = new ArangoDocument();
    $tag->set("name", $name);

    $newTag = $database->save("tag", $tag);
    return $newTag;
}

