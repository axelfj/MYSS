<?php
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\DocumentHandler;

require_once("connection.php");
require ("createCollection.php");

$connection = connect();
$collectionHandler = new ArangoCollectionHandler($connection);
$documentHandler = new DocumentHandler($connection);

// create a document(row) for User Collection //
$document = new \ArangoDBClient\Document();
$document->set("username", "axl1210");
$document->set("mail", "axl1210@gmail.com");
$document->set("password", "1234");
$document->set("name", "Axel Fernández Jiménez");
$document->set("birthday", "12-10-1998");
// save document in UserCollection
$documentId = $documentHandler->save($userCollection, $document);

// create a document(row) for Post Collection //
$document = new \ArangoDBClient\Document();
$document->set("title", "Estudiantes del TEC hacen una progra pichudisima");
$document->set("description", "Los estudiantes Andres Brenes, Jorge Barquero, Axel Fernández y Juan Escobar revolucionan la web.");
// save document in PostCollection
$documentId = $documentHandler->save($postCollection, $document);

// create a document(row) for Tag Collection //
$document = new \ArangoDBClient\Document();
$document->set("tag", "Fancy");
// save document in PostCollection
$documentId = $documentHandler->save($tagCollection, $document);