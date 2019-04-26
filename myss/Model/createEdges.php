<?php

include_once "../Model/createTags.php";

require_once "../Controller/connection.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/EdgeHandler.php";
require_once "../Controller/arangodb-php/lib/ArangoDBClient/Edge.php";

use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;
use ArangoDBClient\EdgeHandler;
use ArangoDBClient\Edge;
use ArangoDBClient\GraphHandler;
use ArangoDBClient\Vertex;

// Can make an user to follow another. One must send the key from the username.
function userFollow($fromUser, $toUser){
    createEdge('user', $fromUser, 'user', $toUser, 'follows');
}

function connectTags($idPost, $tagsArray){
    $document = new ArangoCollectionHandler(connect());
    foreach ($tagsArray as $tag){
        $cursorTag = $document->byExample('tag', ['name' => $tag]);
        if ($cursorTag->getCount() == 0) {
            $idTag = createTag($tag);
            $tagkey = substr($idTag, 4, 10);
            createEdge('post', $idPost, 'tag', $tagkey, 'has_tag');
        } else {
            foreach ($cursorTag as $key => $value) {
                $resultingDocument[$key] = $value;

                // Gets the id of the tag.
                $idTag = $resultingDocument[$key]->getHandle();
                $tagkey = substr($idTag, 4, 10);
                createEdge('post', $idPost, 'tag', $tagkey, 'has_tag');
            }
        }
    }
}

function userPosted($idUser, $idPost){
    createEdge('user', $idUser, 'post', $idPost, 'posted');
}

function postHasComment($idPost, $idComment){
    createEdge('post', $idPost, 'comment', $idComment, 'has_comment');
}

function commentHasAnswer($idComment, $idAnswer){
    createEdge('comment', $idComment, 'answer', $idAnswer, 'has_answer');
}

function userCommented($idUser, $idComment){
    createEdge('user', $idUser, 'comment', $idComment, 'commented');
}

function userLikedPost($idUser, $idPost){
    createEdge('user', $idUser, 'post', $idPost, 'liked');
}

function userLikedComment($idUser, $idPost){
    createEdge('user', $idUser, 'comment', $idPost, 'liked');
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
