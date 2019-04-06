<?php
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Collection as ArangoCollection;

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
if ($collectionHandler->has('comment')) {
    $collectionHandler->drop('comment');
}
if ($collectionHandler->has('friends')) {
    $collectionHandler->drop('friends');
}
if ($collectionHandler->has('userXpost')) {
    $collectionHandler->drop('userXpost');
}
if ($collectionHandler->has('postXtag')) {
    $collectionHandler->drop('postXtag');
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
$commentCollection = new ArangoCollection();
$commentCollection->setName('comment');
$id = $collectionHandler-> create($commentCollection);

// create the userXuser collection //
$userXuserCollection = new ArangoCollection();
$userXuserCollection->setName('userXuser');
$id = $collectionHandler->create($userXuserCollection);

// create the userXpost collection //
$userXpostCollection = new ArangoCollection();
$userXpostCollection->setName('userXpost');
$id = $collectionHandler->create($userXpostCollection);

// create the postXtag collection //
$postXtagCollection = new ArangoCollection();
$postXtagCollection->setName('postXtag');
$id = $collectionHandler->create($postXtagCollection);


echo 'Se han creado las colecciones correctamente.';
