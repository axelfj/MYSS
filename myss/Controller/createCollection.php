<?php
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Collection as ArangoCollection;
use ArangoDBClient\EdgeHandler as ArangoEdgeHandler;
use ArangoDBClient\Edge as ArangoEdge;

require_once("connection.php");

$connection = connect();
$collectionHandler = new ArangoCollectionHandler($connection);
$edgeHandler = new ArangoEdgeHandler($connection);

if ($collectionHandler->has('user')) {
    $collectionHandler->drop('user');
}
if ($collectionHandler->has('posts')) {
    $collectionHandler->drop('posts');
}
if ($collectionHandler->has('tag')) {
    $collectionHandler->drop('tag');
}
if ($collectionHandler->has('follows')) {
    $collectionHandler->drop('follows');
}


// create the User collection //
$userCollection = new ArangoCollection();
$userCollection->setName('user');
$id = $collectionHandler->create($userCollection);

// create the Post collection //
$postCollection = new ArangoCollection();
$postCollection->setName('posts');
$id = $collectionHandler->create($postCollection);

// create the Tag collection //
$tagCollection = new ArangoCollection();
$tagCollection->setName('tag');
$id = $collectionHandler-> create($tagCollection);

// create the Comment collection //
$commentEdge = new ArangoEdge();
$commentEdge->setName('has_comment');
$id = $edgeHandler->;

// create the userXuser collection //
$followsEdge = new ArangoEdge();
$followsEdge->setName('follows');
$id = $edgeHandler->create($followsEdge);

// create the posted edge //
$postedEdge = new ArangoEdge();
$postedEdge->setName('posted');
//$id = $edgeHandler->create($postedEdge);

// create the postXtag collection //
$has_tagEdge = new ArangoEdge();
$has_tagEdge->setName('has_tags');
//$id = $edgeHandler->create($has_tagEdge);


echo 'Se han creado las colecciones y aristas correctamente.';
