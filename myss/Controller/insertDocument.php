<?php
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\DocumentHandler;
use ArangoDBClient\Statement;

require_once("connection.php");
require("createCollection.php");

$connection = connect();
$collectionHandler = new ArangoCollectionHandler($connection);
$documentHandler = new DocumentHandler($connection);

// insert document via AQL
$document = array(
    "username"    => "axl1210",
    "mail"    => "axl1210@gmail.com",
    "password"    => "1234",
    "name"    => "Axel Fernández Jiménez",
    "birthday"    => "12-10-1998"
);

$query = 'INSERT @doc INTO user RETURN NEW';
try {
    $statement = new Statement(
        $connection,
        array(
            "query" => $query,
            "count" => true,
            "batchSize" => 1,
            "sanitize" => true,
            "bindVars" => array("doc" => $document)
        )
    );
    $cursor = $statement->execute();
    $resultingDocuments = array();

    foreach ($cursor as $key => $value) {
        $resultingDocuments[$key] = $value;
    }
    var_dump($resultingDocuments);
} catch (\ArangoDBClient\Exception $e) {
    echo $e;
}

