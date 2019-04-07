<?php

use ArangoDBClient\Statement;

require_once("connection.php");
$connection = connect();

// execute AQL queries
$query = 'FOR x IN post OUTBOUND post/has_comment return x.title';

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
    echo $e->getMessage();
}