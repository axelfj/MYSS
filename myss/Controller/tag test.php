<?php
/**
 * Created by PhpStorm.
 * User: Simastir_da
 * Date: 06/04/2019
 * Time: 15:57
 */


use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\DocumentHandler;
use ArangoDBClient\Statement;

require_once("connection.php");
require "../Controller/connection.php";
require "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";
use ArangoDBClient\Document as ArangoDocument;
use ArangoDBCLient\DocumentHandler as ArangoInsert;

//Funcion para conectar un post con un tag
//Recibe el texto del tag y el id del post
function connectTag($idPost, $Tag){

    $connection = connect();
    $collectionHandler  = new ArangoCollectionHandler($connection);
    $edgeHandler        = new EdgeHandler($connection);

    $idTag = null;
    $resultingDocument = null;

    $cursorTag   = $collectionHandler->byExample('tag', ['name' => $Tag]);

    if($cursorTag->getCount() == 0){
        //crear el Tag nuevo
    }

    foreach ($cursorTag as $key => $value) {
        $resultingDocument[$key] = $value;

        // Gets the id of the FromUser.
        $idTag = $resultingDocument[$key]->getHandle();
    }

    $edgeInfo   = [
        // info in the edge
    ];
    $linkBetween = Edge::createFromArray($edgeInfo);
    $edgeHandler->saveEdge('has', $idPost, $idTag, $linkBetween);


}