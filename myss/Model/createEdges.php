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

function userFollow($fromUser, $toUser){
    try{
        if($toUser == null){
            return false;
        }
        createEdge('user', $fromUser, 'user', $toUser, 'follows');
        return true;
    } catch (Exception $e){
        return false;
    }
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

function userLikedComment($idUser, $idComment){
    createEdge('user', $idUser, 'comment', $idComment, 'liked');
}

function userLikedAnswer($idUser, $idAnswer){
    createEdge('user', $idUser, 'answer', $idAnswer, 'liked');
}

function createEdge($fromCollection, $idFrom, $toCollection, $idTo, $relation){
    $connection         = connect();
    $collectionHandler  = new ArangoCollectionHandler($connection);
    $edgeHandler        = new EdgeHandler($connection);
    $graphHandler       = new GraphHandler($connection);

    $cursorFrom = $collectionHandler->byExample($fromCollection, ['_key' => $idFrom]);
    $cursorTo   = $collectionHandler->byExample($toCollection, ['_key' => $idTo]);

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
