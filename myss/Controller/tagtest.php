<?php
/**
 * Created by PhpStorm.
 * User: Simastir_da
 * Date: 07/04/2019
 * Time: 20:41
 */


require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/CollectionHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Cursor.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Edge.php";
use ArangoDBClient\Edge;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use ArangoDBClient\EdgeHandler;
use ArangoDBClient\Document as ArangoDocument;


$connection = connect();
$collectionHandler = new ArangoCollectionHandler($connection);

$edgeHandler = new EdgeHandler($connection);

function connectTag($idPost, $Tag)
{

    global $collectionHandler;
    global $edgeHandler;

    $idTag = null;
    $resultingDocument = null;

    $cursorTag = $collectionHandler->byExample('tag', ['name' => $Tag]);
    $cursorPost   = $collectionHandler->byExample('post', ['_key' => $idPost]);

    if ($cursorTag->getCount() == 0) {
        createTag($Tag);
        $cursorTag = $collectionHandler->byExample('tag', ['name' => $Tag]);
    } else{
        //echo $Tag;
    }

    foreach ($cursorTag as $key => $value) {
        $resultingDocument[$key] = $value;

        // Gets the id of the FromUser.
        $idTag = $resultingDocument[$key]->getHandle();
    }

    foreach ($cursorPost as $key => $value) {
        $resultingDocument[$key] = $value;
        $idPost = $resultingDocument[$key]->getHandle();
    }

    $edgeInfo = [
        // info in the edge
    ];
    $linkBetween = Edge::createFromArray($edgeInfo);
    $edgeHandler->saveEdge('has_tag', $idPost, $idTag, $linkBetween);
}

function createTag($name){

    $database = new ArangoDocumentHandler(connect());

    $tag = new ArangoDocument();
    $tag->set("name", $name);

    $newTag = $database->save("tag", $tag);

}

