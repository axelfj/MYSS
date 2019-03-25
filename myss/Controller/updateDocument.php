<?php
<<<<<<< HEAD
/**
 * Created by PhpStorm.
 * User: azzefj
 * Date: 3/24/2019
 * Time: 6:03 PM
 */
=======
use ArangoDBClient\Statement;

require_once("connection.php");
$connection = connect();

// update document via AQL
$document = array(
    "name" => "azzefj",
);

$query = 'UPDATE @name WITH @doc IN user RETURN NEW';

try {
    $statement = new Statement(
        $connection,
        array(
            "query"     => $query,
            "count"     => true,
            "batchSize" => 1,
            "sanitize"  => true,
            "bindVars"  => array("doc" => $document, "name" => "Axel Fernández Jiménez")
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

>>>>>>> 261aeb6c44c20358316a518aeb307021029ef27d
