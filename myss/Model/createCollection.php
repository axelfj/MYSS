<?php
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Collection as ArangoCollection;
use ArangoDBClient\EdgeHandler as ArangoEdgeHandler;
use ArangoDBClient\Edge as ArangoEdge;

require_once("../Controller/connection.php");

// User collection, will hold every user in the system //
createCollection('user', 2);
createCollection('post', 2);
createCollection('comment', 2);
createCollection('tag', 2);
createCollection('follows', 3);
createCollection('liked', 3);
createCollection('has_comment', 3);
createCollection('has_tag', 3);
createCollection('posted', 3);
createCollection('commented', 3);

function createCollection($collectionName, $collectionType){
    $connection = connect();
    $collectionHandler = new ArangoCollectionHandler($connection);

    if ($collectionHandler->has($collectionName)){
        $collectionHandler->drop($collectionName);
    }

    $collection  = new ArangoCollection();
    $collection->setName($collectionName);
    $collection->setType($collectionType);
    $collectionHandler-> create($collection);
}


echo 'Se han creado las colecciones y aristas correctamente.';
