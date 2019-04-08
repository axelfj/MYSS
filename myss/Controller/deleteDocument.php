<?php

// To use the AQL statements.
use ArangoDBClient\Statement;
use ArangoDBClient\Exception as ArangoException;
// The connection to the data base.
require_once("connection.php");

function deleteDocument($collection, $idDocument){
    $connection = connect();
    $query = "FOR u IN @@".$collection." FILTER u._key == @".$idDocument." REMOVE u IN @@".$collection;
    var_dump($query);
    try {
        $statement = new Statement(
            $connection,
            array(
                "query" => $query,
                "count" => true,
                "batchSize" => 1,
                "sanitize" => true,
                "bindVars"  => array("collection" => "@".$collection)
            )
        );
        $statement->execute();
        echo 'Se ha eliminado correctamente al documento.';
    } catch (ArangoException $e) {
        echo $e->getMessage();
    }
}

deleteDocument('USER',271687);
