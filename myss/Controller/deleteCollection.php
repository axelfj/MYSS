<?php

use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Collection as ArangoCollection;

require_once("connection.php");

function deleteCollection($collectionName)
{
    $connection = connect();
    $collectionHandler = new ArangoCollectionHandler($connection);
    try {
        $collectionHandler->drop($collectionName);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}