<?php
use ArangoDBClient\Statement;

require_once("connection.php");
$connection = connect();

// execute AQL queries
$query = 'FOR x IN user RETURN x.name';
// user => nombre de colección a la que se le piden datos //

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

