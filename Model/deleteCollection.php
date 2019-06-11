<?php

use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\Collection as ArangoCollection;

require_once("../Controller/connection.php");

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