<?php
use ArangoDBClient\Statement;

require_once("connection.php");
$connection = connect();

// update document via AQL
$document = array(
    "c" => "qux",
);

$query = "UPDATE @key WITH @doc IN user RETURN NEW";
try {
    $statement = new Statement(
        $connection,
        array(
            "query" => $query,
            "count" => true,
            "batchSize" => 1,
            "sanitize" => true,
            "bindVars" => array("doc" => $document, "key" => "user")
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

