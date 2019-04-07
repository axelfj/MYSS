<?php

// Requires the connection and all the dependencies.
require_once("connection.php");
require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/EdgeHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Edge.php";

// Uses the functions of ArangoDB.
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\EdgeHandler;
use ArangoDBClient\EdgeDefinition as EdgeDefinition;
use ArangoDBClient\Graph;
use ArangoDBClient\GraphHandler;


// This function gets two parameters and associates them in an edge.
// They must be their usernames.
function createGraph(){

    // All the variables that we need to manage the function.
    $connection         = connect();
    $collectionHandler  = new ArangoCollectionHandler($connection);
    $edgeHandler        = new EdgeHandler($connection);
    $graphHandler       = new GraphHandler($connection);
    $graph              = new Graph();

    $graph->set('_key', 'MYSS');

    try {
        $graphHandler->dropGraph($graph);
    } catch (\Exception $e) {
        // graph may not yet exist. ignore this error for now
    }

    $graphHandler->createGraph($graph);
}

function createEdges(){
    $connection = connect();
    $graphHandler = new GraphHandler($connection);

    // user->follows->user //
    $friendsEdge = new EdgeDefinition($connection);
    $friendsEdge->addFromCollection("user");
    $friendsEdge->addToCollection("user");
    $friendsEdge->setRelation("friends");

    // user->posted->post//
    $postedEdge = new EdgeDefinition($connection);
    $postedEdge->addFromCollection("user");
    $postedEdge->addToCollection("post");
    $postedEdge->setRelation("posted");

    // user->commented->post //
    $comentedEdge = new EdgeDefinition($connection);
    $comentedEdge->addFromCollection("user");
    $comentedEdge->addToCollection("post");
    $comentedEdge->setRelation("commented");

    // post->has_comment //
    $hasCommentEdge = new EdgeDefinition($connection);
    $hasCommentEdge->addFromCollection("post");
    $hasCommentEdge->addToCollection("comment");
    $hasCommentEdge->setRelation("has_comment");

    // post->has->tag //
    $hasTagEdge = new EdgeDefinition($connection);
    $hasTagEdge->addFromCollection("post");
    $hasTagEdge->addToCollection("tag");
    $hasTagEdge->setRelation("has_tag");

    $graphHandler->addEdgeDefinition( 'MYSS', $friendsEdge);
    $graphHandler->addEdgeDefinition( 'MYSS', $postedEdge);
    $graphHandler->addEdgeDefinition( 'MYSS', $comentedEdge);
    $graphHandler->addEdgeDefinition( 'MYSS', $hasCommentEdge);
    $graphHandler->addEdgeDefinition( 'MYSS', $hasTagEdge);

}

try{
    createGraph();
    createEdges();
    echo 'Se ha creado el grafo.';
} catch (Exception $e){
    // error handle
}


