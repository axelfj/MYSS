<?php

// Requires the connection and all the dependencies.
require_once("connection.php");
require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/EdgeHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Edge.php";

// Uses the functions of ArangoDB.
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\EdgeHandler;
use ArangoDBClient\Edge;
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
    //$graph->addEdgeDefinition(EdgeDefinition::createDirectedRelation('follows', 'user'));

    try {
        $graphHandler->dropGraph($graph);
    } catch (\Exception $e) {
        // graph may not yet exist. ignore this error for now
    }

    $graphHandler->createGraph($graph);
}

function createEdges(){
    // All the variables that we need to manage the function.
    $connection         = connect();
    $collectionHandler  = new ArangoCollectionHandler($connection);
    $edgeHandler        = new EdgeHandler($connection);
    $graphHandler       = new GraphHandler($connection);
    $graph              = new Graph();

    $graph->get('MYSS');
    //var_dump($graph->getEdgeDefinitions());
    $userCollection = $collectionHandler->get('user');
    $postsCollection = $collectionHandler->get('posts');
    $tagCollection = $collectionHandler->get('tag');

    try {
        $graphHandler->saveVertex('MYSS', $userCollection);
        $graphHandler->saveVertex('MYSS', $postsCollection);
        $graphHandler->saveVertex('MYSS', $tagCollection);
    } catch (\ArangoDBClient\Exception $e) {
        echo $e->getMessage();
    }

    //$graph->addEdgeDefinition('comments', 'user', 'posts');

}

try{
    createGraph();
    createEdges();
    echo 'Se ha creado el grafo.';
} catch (Exception $e){
    // error handle
}


