<?php

// Requires the connection and all the dependencies.
require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/EdgeHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Edge.php";

// Uses the functions of ArangoDB.
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\EdgeHandler;
use ArangoDBClient\EdgeDefinition as EdgeDefinition;
use ArangoDBClient\Graph;
use ArangoDBClient\GraphHandler;

function createGraph(){
    $connection         = connect();
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

    $friendsEdge = createEdge('user','follows','user');
    $postedEdge = createEdge('user','posted','post');
    $commentedEdge = createEdge('user','commented','post');
    $likedEdge = createEdge('user','liked','post');
    $hasCommentEdge = createEdge('post','has_comment','comment');
    $hasTagEdge = createEdge('post','has_tag','tag');

    $graphHandler->addEdgeDefinition( 'MYSS', $friendsEdge);
    $graphHandler->addEdgeDefinition( 'MYSS', $postedEdge);
    $graphHandler->addEdgeDefinition( 'MYSS', $commentedEdge);
    $graphHandler->addEdgeDefinition( 'MYSS', $likedEdge);
    $graphHandler->addEdgeDefinition( 'MYSS', $hasCommentEdge);
    $graphHandler->addEdgeDefinition( 'MYSS', $hasTagEdge);

}

function createEdge($fromCollection, $relation, $toCollection){
    $connection = connect();
    $edge = new EdgeDefinition($connection);
    $edge->addFromCollection($fromCollection);
    $edge->setRelation($relation);
    $edge->addToCollection($toCollection);
    return $edge;
}

try{
    createGraph();
    createEdges();
    echo 'Se ha creado el grafo.';
} catch (Exception $e){
    // error handle
}


