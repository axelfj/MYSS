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
if ($collectionHandler->has('post')) {
    $collectionHandler->drop('post');
}
if ($collectionHandler->has('comment')) {
    $collectionHandler->drop('comment');
}
if ($collectionHandler->has('tag')) {
    $collectionHandler->drop('tag');
}
if ($collectionHandler->has('follows')) {
    $collectionHandler->drop('follows');
}
if ($collectionHandler->has('commented')) {
    $collectionHandler->drop('commented');
}
if ($collectionHandler->has('posted')) {
    $collectionHandler->drop('posted');
}
if ($collectionHandler->has('has_tag')) {
    $collectionHandler->drop('has_tag');
}
if ($collectionHandler->has('has_comment')) {
    $collectionHandler->drop('has_comment');
}
if ($collectionHandler->has('liked')) {
    $collectionHandler->drop('liked');
}

// User collection, will hold every user in the system //
$userCollection = new ArangoCollection();
$userCollection->setName('user');
$id = $collectionHandler->create($userCollection);

// Post collection, will hold every post in the system //
$postCollection = new ArangoCollection();
$postCollection->setName('post');
$id = $collectionHandler->create($postCollection);

// Tag collection, will hold every comment in the system //
$commentCollection = new ArangoCollection();
$commentCollection->setName('comment');
$id = $collectionHandler-> create($commentCollection);

// User collection, will hold every user in the system //
$tagCollection = new ArangoCollection();
$tagCollection->setName('tag');
$id = $collectionHandler-> create($tagCollection);

// create the friends edge //
$followsEdge = new ArangoCollection();
$followsEdge->setName('follows');
$followsEdge->setType(3);
$id = $collectionHandler->create($followsEdge);

// create the friends edge //
$commentedEdge = new ArangoCollection();
$commentedEdge->setName('commented');
$commentedEdge->setType(3);
$id = $collectionHandler->create($commentedEdge);

// create the posted edge //
$postedEdge = new ArangoCollection();
$postedEdge->setName('posted');
$postedEdge->setType(3);
$id = $collectionHandler->create($postedEdge);

// create the posted edge //
$likedEdge = new ArangoCollection();
$likedEdge->setName('liked');
$likedEdge->setType(3);
$id = $collectionHandler->create($likedEdge);

// create the has_tag edge //
$has_commentEdge = new ArangoCollection();
$has_commentEdge->setName('has_comment');
$has_commentEdge->setType(3);
$id = $collectionHandler-> create($has_commentEdge);

// create the has_tag edge //
$has_tagEdge = new ArangoCollection();
$has_tagEdge->setName('has_tag');
$has_tagEdge->setType(3);
$id = $collectionHandler-> create($has_tagEdge);

echo 'Se han creado las colecciones y aristas correctamente.';
