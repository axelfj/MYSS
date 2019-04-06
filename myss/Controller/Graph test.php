<?php

// Requires the connection and all the dependencies.
require_once("connection.php");
require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/EdgeHandler.php";

// Uses the functions of ArangoDB.
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;

// This function gets two parameters and associates them in an edge.
// They must be their usernames.
function follow($fromUser, $toUser){

    // All the variables that we need to manage the function.
    $connection         = connect();
    $collectionHandler  = new ArangoCollectionHandler($connection);
    $edgeHandler        = new EdgeHandler();

    // We create two cursor to make the consults with the data.
    $cursorFrom = $collectionHandler->byExample('user', ['username' => $fromUser]);
    $cursorTo   = $collectionHandler->byExample('user', ['username' => $toUser]);

    // Now, we get the documents iterating over the cursors.
    $idFromUser         = null;
    $idToUser           = null;
    $resultingDocument  = array();

     foreach ($cursorFrom as $key => $value) {
        $resultingDocuments[$key] = $value;

     }

     var_dump($resultingDocuments);

    // now insert a link between Marketing and Jane
    $worksFor = Edge::createFromArray(['startDate' => '2009-06-23', 'endDate' => '2014-11-12']);
    $edgeHandler->saveEdge('worksFor', $marketing->getHandle(), $jane->getHandle(), $worksFor);


}

follow
