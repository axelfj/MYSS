<?php

// To use the AQL statements.
use ArangoDBClient\Statement;

// The connection to the data base. 
require_once("connection.php");
$connection = connect();

// Delete document via AQL.
// Note that @@collection calls the collection where you want to delete the document. 
$query = "FOR x IN @@collection FILTER x.username == 'azzefj' REMOVE x IN @@collection RETURN OLD";

// So, go and delete it!
try {
    $statement = new Statement(
        $connection,
        array(
            "query" => $query,
            "count" => true,
            "batchSize" => 1,
            "sanitize" => true,
            "bindVars"  => array("@collection" => "user")
        )
    );

    $cursor = $statement->execute();
    
} catch (\ArangoDBClient\Exception $e) {
    echo $e;
}

