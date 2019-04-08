<?php

use ArangoDBClient\Statement;
require_once("connection.php");

function readDocument($collection){
    $connection = connect();
    $query = 'FOR x IN '.$collection.' RETURN x';
    try {
        $statement = new Statement(
            $connection,
            array(
                "query" => $query,
                "count" => true,
                "batchSize" => 1000,
                "sanitize" => true
            )
        );
        $cursor = $statement->execute();
        $resultingDocuments = array();

        foreach ($cursor as $key => $value) {
            $resultingDocuments[$key] = $value;
        }
        var_dump($resultingDocuments);
    } catch (\ArangoDBClient\Exception $e) {
        echo 'Error.';
    }
}



