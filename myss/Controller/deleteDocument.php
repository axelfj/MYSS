<?php

use ArangoDBClient\Statement;

require_once("connection.php");
$connection = connect();

// delete document via AQL
$query = "FOR x IN user FILTER x.username == 'axl1210' REMOVE x IN user RETURN OLD";
// user => nombre de la tabla //
// username => campo que se desea filtrar

try {
    $statement = new Statement(
        $connection,
        array(
            "query" => $query,
            "count" => true,
            "batchSize" => 1,
            "sanitize" => true,
            "bindVars"  => array("x.username" => "axl1210")
        )
    );
    $cursor = $statement->execute();
    var_dump($cursor);
} catch (\ArangoDBClient\Exception $e) {
    echo $e;
}

