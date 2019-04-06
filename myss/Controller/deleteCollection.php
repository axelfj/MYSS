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