<?php

use ArangoDBClient\DocumentHandler;

// First, the connection to the database. 
require_once("connection.php");
$connection = connect();

// Handler to manage the documents. 
$documentHandler = new DocumentHandler($connection);

// Then, it gets the item that needs to be changed.
$collectionName = "user"; 
$documentId     = "16690"; // This is burned in the code. 
$document = $documentHandler->get($collectionName, $documentId);

// So, that document should be in $document. 
// Let's update it. 
$document->set("username", "azzefj");
$documentHandler->update($document);



/*
use ArangoDBClient\statement;

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
*/

