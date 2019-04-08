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
use ArangoDBClient\GraphHandler;
use ArangoDBClient\Vertex;

function userFollow($fromUser, $toUser){
    createEdge('user', $fromUser, 'user', $toUser, 'follows');
}

function connectTag($idPost, $idTag){
    createEdge('post', $idPost, 'tag', $idTag, 'has_tag');
}

function userPosted($idUser, $idPost){
    createEdge('user', $idUser, 'post', $idPost, 'posted');
}

function postHasComment($idPost, $idComment){
    createEdge('post', $idPost, 'comment', $idComment, 'has_comment');
}

function userCommented($idUser, $idComment){
    createEdge('user', $idUser, 'comment', $idComment, 'commented');
}

function userLiked($idUser, $idPost){
    createEdge('user', $idUser, 'post', $idPost, 'liked');
}

function createEdge($fromCollection, $idFrom, $toCollection, $idTo, $relation){
    $connection         = connect();
    $collectionHandler  = new ArangoCollectionHandler($connection);
    $edgeHandler        = new EdgeHandler($connection);
    $graphHandler       = new GraphHandler($connection);

    $cursorFrom = $collectionHandler->byExample($fromCollection, ['_key' => $idFrom]);
    $cursorTo   = $collectionHandler->byExample($toCollection, ['_key' => $idTo]);

    // Now, we get the documents iterating over the cursors.
    $from = null;
    $to = null;
    $resultingDocument  = array();

    foreach ($cursorFrom as $key => $value) {
        $resultingDocument[$key] = $value;
        $from = $resultingDocument[$key]->getHandle();
    }

    foreach ($cursorTo as $key => $value) {
        $resultingDocument[$key] = $value;
        $to = $resultingDocument[$key]->getHandle();
    }

    $edgeInfo   = [
        // info in the edge
    ];
    $linkBetween = Edge::createFromArray($edgeInfo);
    $edgeHandler->saveEdge($relation, $from, $to, $linkBetween);
}
