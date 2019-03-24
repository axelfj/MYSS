<?php
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Collection as ArangoCollection;
/**
 * Created by PhpStorm.
 * User: Simastir_da
 * Date: 24/03/2019
 * Time: 12:33
 */

require_once("connection.php");

$connection = connect();


$collectionHandler = new ArangoCollectionHandler($connection);

if ($collectionHandler->has('users')) {
    $collectionHandler->drop('users');
}
if ($collectionHandler->has('posts')) {
    $collectionHandler->drop('posts');
}
if ($collectionHandler->has('tag')) {
    $collectionHandler->drop('tag');
}

$userCollection = new ArangoCollection();
$userCollection->setName('users');
$id = $collectionHandler->create($userCollection);
var_dump($id);

$postCollection = new ArangoCollection();
$postCollection->setName('posts');
$id = $collectionHandler->create($postCollection);
var_dump($id);

$tagCollection = new ArangoCollection();
$tagCollection->setName('tag');
$id = $collectionHandler-> create($tagCollection);
var_dump($id);