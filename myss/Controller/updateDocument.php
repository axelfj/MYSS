<?php

use ArangoDBClient\DocumentHandler;

// First, the connection to the database. 
require_once("connection.php");

function updateDocument($collection, $idDocument)
{
    $connection = connect();
    $documentHandler = new DocumentHandler($connection);
    try {
        $document = $documentHandler->get($collection, $idDocument);
        // data to update //
        $documentHandler->update($document);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
