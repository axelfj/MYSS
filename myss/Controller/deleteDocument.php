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

>>>>>>> 261aeb6c44c20358316a518aeb307021029ef27d
