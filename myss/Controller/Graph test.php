<?php
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\DocumentHandler;
use ArangoDBClient\Statement;

require_once("connection.php");
require "../Controller/connection.php";
require "../Controller/arangodb-php/lib/ArangoDBClient/DocumentHandler.php";
use ArangoDBClient\Document as ArangoDocument;
use ArangoDBCLient\DocumentHandler as ArangoInsert;

function followed(){
    $connection = connect();
    $collectionHandler = new ArangoCollectionHandler($connection);
    $documentHandler = new DocumentHandler($connection);
    $query = 'FOR u IN user FILTER u.username == @username UPDATE u WITH { followed: [@follow] } IN user';
    try {
        $statement = new Statement(
            $connection,
            array(
                "query" => $query,
                "count" => true,
                "batchSize" => 1,
                "sanitize" => true,
                "bindVars" => array("doc" => $document)
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
}

function follower(){
    $connection = connect();
    $collectionHandler = new ArangoCollectionHandler($connection);
    $documentHandler = new DocumentHandler($connection);
    $query = 'FOR u IN user FILTER u.username == @username UPDATE u WITH { follower: [@follow] } IN user';
    try {
        $statement = new Statement(
            $connection,
            array(
                "query" => $query,
                "count" => true,
                "batchSize" => 1,
                "sanitize" => true,
                "bindVars" => array("doc" => $document)
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
}
