<?php

use ArangoDBClient\Document as ArangoDocument;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use function ArangoDBClient\readCollection;
use ArangoDBClient\Statement as ArangoStatement;

require_once "../Model/executeQuery.php";
require_once "../Model/createEdges.php";

$database = connect();

class PostQuery
{
    public static function createNewPost($dtoPost)
    {
        try {
            $database = new ArangoDocumentHandler(connect());
            $infoPost = $dtoPost->getPosts();

            $title = $infoPost['title'];
            $text = $infoPost['post'];
            $imagePath = $infoPost['destination'];
            $tagsPost = $infoPost['tagsPost'];
            $visibility = $infoPost['visibility'];
            $owner = $infoPost['username'];
            $time = date('j-m-y H:i');

            $post = new ArangoDocument();
            $post->set("title", $title);
            $post->set("text", $text);
            $post->set("destination", $imagePath);
            $post->set("tagsPost", $tagsPost);
            $post->set("visibility", $visibility);
            $post->set("owner", $owner);
            $post->set("time", $time);

            $newPost = $database->save("post", $post);
            $postKey = substr($newPost, 5, 10);
            $tagsArray = explode(",", $tagsPost);

            connectTags($postKey, $tagsArray);

            $userKey = $_SESSION['userKey'];
            userPosted($userKey, $postKey);
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    public static function createNewComment($dtoComment, $postKey)
    {
        $database = new ArangoDocumentHandler(connect());
        $infoComment = $dtoComment->getComments();

        $text = $infoComment['text'];
        $tagsComment = $infoComment['tagsComment'];
        $commentOwner = $infoComment['commentOwner'];
        $time = date('j-m-y H:i');

        $comment = new ArangoDocument();
        $comment->set("text", $text);
        $comment->set("tagsComment", $tagsComment);
        $comment->set("commentOwner", $commentOwner);
        $comment->set("time", $time);

        $newComment = $database->save("comment", $comment);

        // Gets just the number of key, because "$newPost" stores something like "post/83126"
        // and we just need that number.
        $pos = strpos($newComment, "/") + 1;
        $commentKey = substr($newComment, $pos, strlen($newComment));

        createEdge('post', $postKey, 'comment', $commentKey, 'has_comment');
    }

    public static function getMyPosts($username, $visibility)
    {
        try {
            if ($visibility == 'Public') {
                $query = [
                    'FOR u IN post 
                 FILTER u.owner == @username and u.visibility == "Public"
                 SORT u.time DESC 
                 RETURN {key: u._key, owner: u.owner, title: u.title, text: u.text, destination: u.destination, 
                          tagsPost: u.tagsPost, visibility: u.visibility, time: u.time, likes: u.likes}'
                    => ['username' => $username]];
            } else {
                $query = [
                    'FOR u IN post 
                 FILTER u.owner == @username 
                 SORT u.time DESC 
                 RETURN {key: u._key, owner: u.owner, title: u.title, text: u.text, destination: u.destination, 
                          tagsPost: u.tagsPost, visibility: u.visibility, time: u.time, likes: u.likes}'
                    => ['username' => $username]];
            }

            $publicPosts = PostQuery::postsIntoArray($query);

            return $publicPosts;
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    public static function getAllPublicPosts()
    {
        try {
            $query = [
                'FOR u IN post 
                        FILTER u.visibility == @visibility 
                        SORT u.time DESC 
                        RETURN {key: u._key, owner: u.owner, title: u.title, text: u.text, destination: u.destination,
                        tagsPost: u.tagsPost, visibility: u.visibility, time: u.time, likes: u.likes}'
                => ['visibility' => 'Public']];

            $publicPosts = PostQuery::postsIntoArray($query);

            return $publicPosts;
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    // Returns an array with all the comments' keys.
    public static function getCommentsKeys($postKey)
    {
        try {
            $query = [
                'FOR u IN has_comment 
                 FILTER u._from == @from                                                         
                 RETURN {key: u._key, from: u._from, to: u._to}' => ['from' => 'post/' . $postKey]];

            $cursor = readCollection($query);

            return $cursor;

        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    // Returns an array with all the comments for a specific post.
    public static function getPostComments($postKey)
    {
        try {
            $cursor = PostQuery::getCommentsKeys($postKey);
            $resultingDocuments = array();
            $numberOfComments = $cursor->getCount();

            if ($numberOfComments > 0) {
                $commentsKeys = array();
                $userComments = array();

                foreach ($cursor as $key => $value) {
                    $resultingDocuments[$key] = $value;
                    $commentsKeys['postKey'] = $resultingDocuments[$key]->get('from');
                    $commentsKeys['commentKey'] = substr($resultingDocuments[$key]->get('to'), 8,
                        strlen($resultingDocuments[$key]->get('to')));

                    array_push($userComments, PostQuery::commentsFromKeyIntoArray($commentsKeys['commentKey']));
                }
                return $userComments;
            }
            return null;
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    // Returns into an array all the comments found by their keys.
    private static function commentsFromKeyIntoArray($commentKey)
    {
        $query = 'FOR x IN comment 
                  FILTER x._key == @commentKey                   
                  RETURN {key: x._key, commentOwner: x.commentOwner, tagsComment: x.tagsComment, 
                  text: x.text, time: x.time}';

        $statement = new ArangoStatement(
            connect(),
            array(
                "query" => $query,
                "count" => true,
                "batchSize" => 1,
                "sanitize" => true,
                "bindVars" => array("commentKey" => $commentKey)
            )
        );

        $cursor = $statement->execute();
        $resultingDocuments = array();
        $numberOfComments = $cursor->getCount();

        if ($numberOfComments > 0) {
            $comment = array();
            $userComments = array();

            foreach ($cursor as $key => $value) {

                $resultingDocuments[$key] = $value;
                $comment['commentOwner'] = $resultingDocuments[$key]->get('commentOwner');
                $comment['tagsComment'] = $resultingDocuments[$key]->get('tagsComment');
                $comment['text'] = $resultingDocuments[$key]->get('text');
                $comment['time'] = $resultingDocuments[$key]->get('time');

                $userComments += $comment;
            }
            return $userComments;
        }
        return null;
    }

    // Returns into an array all the posts found.
    private static function postsIntoArray($query)
    {
        $cursor = readCollection($query);
        $resultingDocuments = array();

        if ($cursor->getCount() > 0) {
            $post = array();
            $userPosts = array();

            foreach ($cursor as $key => $value) {
                $resultingDocuments[$key] = $value;
                $post['owner'] = $resultingDocuments[$key]->get('owner');
                $post['title'] = $resultingDocuments[$key]->get('title');
                $post['text'] = $resultingDocuments[$key]->get('text');
                $post['destination'] = $resultingDocuments[$key]->get('destination');
                $post['tagsPost'] = $resultingDocuments[$key]->get('tagsPost');
                $post['visibility'] = $resultingDocuments[$key]->get('visibility');
                $post['time'] = $resultingDocuments[$key]->get('time');
                $post['likes'] = $resultingDocuments[$key]->get('likes');
                $post['key'] = $resultingDocuments[$key]->get('key');

                array_push($userPosts, $post);
            }
            return $userPosts;
        }
        return null;
    }

    public static function getLikesCount($idPost)
    {
        try {
            $statements = [
                'FOR u in liked FILTER u._to == @postKey RETURN u' => ['postKey' => 'post/' . $idPost]];
            $liked = readCollection($statements);
            return $liked->getCount();
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    // Verifies if an user already liked a specific post.
    public static function verifyIfUserLiked($postKey, $userKey)
    {
        $statements = [
            'FOR u IN liked 
            FILTER u._to == @postKey && u._from == @userKey 
            RETURN u._from' => ['postKey' => 'post/' . $postKey, 'userKey' => 'user/' . $userKey]];

        return readCollection($statements);
    }

    public static function filterPostByTag($tag)
    {
        try {
            $query = [
                'FOR u IN post 
                FILTER u.tagPost LIKE @tag
                RETURN {key: u._key, owner: u.owner, title: u.title, text: u.text, destination: u.destination, tagsPost: u.tagsPost, 
                visibility: u.visibility, time: u.time, likes: u.likes}'
                => ['tagsPosts' => $tag . "%"]];
            return readCollection($query);
        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}
