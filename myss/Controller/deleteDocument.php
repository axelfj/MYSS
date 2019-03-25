<?php
use ArangoDBClient\Statement;

require_once("connection.php");
$connection = connect();

// delete document via AQL
$query = "FOR x IN @collection FILTER x.d == 'qux' REMOVE x IN @@collection RETURN OLD";
try {
    $statement = new Statement(
        $connection,
        array(
            "query" => $query,
            "count" => true,
            "batchSize" => 1,
            "sanitize" => true,
            "bindVars" => array("@collection" => "firstCollection")
        )
    );
    $cursor = $statement->execute();
    var_dump($cursor);
} catch (\ArangoDBClient\Exception $e) {
    echo 'Error.';
}

