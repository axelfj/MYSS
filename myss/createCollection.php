<?php
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Collection as ArangoCollection;
use ArangoDBClient\DocumentHandler;

require_once("connection.php");

$connection = connect();
$collectionHandler = new ArangoCollectionHandler($connection);


if ($collectionHandler->has('user')) {
    $collectionHandler->drop('user');
}
if ($collectionHandler->has('posts')) {
    $collectionHandler->drop('posts');
}
if ($collectionHandler->has('tag')) {
    $collectionHandler->drop('tag');
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


