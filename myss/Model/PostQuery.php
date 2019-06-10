<?php

use ArangoDBClient\Document as ArangoDocument;
use ArangoDBClient\DocumentHandler as ArangoDocumentHandler;
use function ArangoDBClient\readCollection;
use ArangoDBClient\Statement as ArangoStatement;
use ArangoDBClient\CollectionHandler as ArangoCollectionHandler;

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
            if (!empty($infoPost['title']) && !empty($infoPost['post']) && !empty($infoPost['username'])) {

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
                return True;
            }
            return False;
        } catch (Exception $e) {
            $e->getMessage();
            return False;
        }
    }

    public static function createNewComment($dtoComment, $postOrCommentKey, $type)
    {
        try {
            $database = new ArangoDocumentHandler(connect());
            $infoComment = $dtoComment->getComments();

            $text = $infoComment['text'];
            $tagsComment = $infoComment['tagsComment'];
            $commentOwner = $infoComment['commentOwner'];
            $imagePath = $infoComment['destination'];
            $time = date('j-m-y H:i');

            $comment = new ArangoDocument();
            $comment->set("text", $text);
            $comment->set("tagsComment", $tagsComment);
            $comment->set("commentOwner", $commentOwner);
            $comment->set("destination", $imagePath);
            $comment->set("time", $time);

            $newComment = $database->save($type, $comment);

            // Gets just the number of key, because "$newPost" stores something like "post/83126"
            // and we just need that number.
            $pos = strpos($newComment, "/") + 1;
            $commentKey = substr($newComment, $pos, strlen($newComment));

            if ($type == 'comment') {
                postHasComment($postOrCommentKey, $commentKey);
            } else {
                commentHasAnswer($postOrCommentKey, $commentKey);
            }
            return true;
        }catch (Exception $e){
            return false;

        }

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
            } else if ($visibility == 'Private') {
                $query = [
                    'FOR u IN post 
                 FILTER u.owner == @username and u.visibility == "Private" 
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

    // Returns an array with all the comments' or answers' keys.
    public static function getCommentsKeys($postOrCommentKey, $collectionName)
    {
        try {
            // If we want just the comments' keys of a post, this will be the query.
            if ($collectionName == 'comment') {
                $query = [
                    'FOR u IN has_comment 
                 FILTER u._from == @from                                                         
                 RETURN {key: u._key, from: u._from, to: u._to}' => ['from' => 'post/' . $postOrCommentKey]];
            }
            // By the other hand, if we want the answers' keys of a specific comment, this
            // will be the query.
            else {
                $query = [
                    'FOR u IN has_answer 
                 FILTER u._from == @from                                                         
                 RETURN {key: u._key, from: u._from, to: u._to}' => ['from' => 'comment/' . $postOrCommentKey]];
            }

            $cursor = readCollection($query);

            return $cursor;

        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    // Returns an array with all the comments for a specific post or
    // the answers for a specific comment, depending on the edge name.
    public static function getCommentsOrAnswers($postKey, $collectionName)
    {
        try {
            $cursor = PostQuery::getCommentsKeys($postKey, $collectionName);
            $resultingDocuments = array();
            $numberOfComments = $cursor->getCount();

            // Length of the word 'comment'-> 7, but we need to begin the slice from pos 8.
            // Length of the word 'answer' -> 6, but we need to begin the slice from pos 7.
            ($collectionName == 'comment') ? $sliceStartsAt = 8 : $sliceStartsAt = 7;

            if ($numberOfComments > 0) {
                $commentsKeys = array();
                $userComments = array();

                foreach ($cursor as $key => $value) {
                    $resultingDocuments[$key] = $value;
                    /*$commentsKeys['postKey'] = $resultingDocuments[$key]->get('from');*/
                    $commentsKeys['commentKey'] = substr($resultingDocuments[$key]->get('to'), $sliceStartsAt,
                        strlen($resultingDocuments[$key]->get('to')));

                    array_push($userComments,
                        PostQuery::commentsFromKeyIntoArray($commentsKeys['commentKey'], $collectionName));
                }
                return $userComments;
            }
            return null;
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    // Returns into an array all the comments found by their keys.
    private static function commentsFromKeyIntoArray($commentKey, $collectionName)
    {
        $query = 'FOR x IN ' . $collectionName .
            ' FILTER x._key == @commentKey                   
                  RETURN {key: x._key, commentOwner: x.commentOwner, tagsComment: x.tagsComment, destination: x.destination,
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
                $comment['commentKey'] = $resultingDocuments[$key]->get('key');
                $comment['commentOwner'] = $resultingDocuments[$key]->get('commentOwner');
                $comment['tagsComment'] = $resultingDocuments[$key]->get('tagsComment');
                $comment['text'] = $resultingDocuments[$key]->get('text');
                $comment['time'] = $resultingDocuments[$key]->get('time');
                $comment['destination'] = $resultingDocuments[$key]->get('destination');
                $comment['key'] = $resultingDocuments[$key]->get('key');

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

    public static function getUserLiked($idPost)
    {
        try {
            $statements = [
                'FOR u in liked 
                FILTER u._to LIKE @key 
                RETURN u._from' => ['key' => '%' . $idPost]];

            $cursor = readCollection($statements);
            return $cursor->getAll();
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    public static function getPostLikeCount($idPost)
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

    public static function getLikeCount($idPost, $collection)
    {
        try {
            $statements = [
                'FOR u in liked 
                FILTER u._to == @key 
                RETURN u._from' => ['key' => $collection . '/' . $idPost]];
            $liked = readCollection($statements);
            return $liked->getCount();
        } catch (Exception $e) {
            $e->getMessage();
        }
    }

    public static function verifyIfUserLikedPost($postKey, $userKey)
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
            $tag .= '%';
            $query = [
                'FOR u IN post 
                FILTER u.tagsPosts LIKE @tag
                RETURN {key: u._key, owner: u.owner, title: u.title, text: u.text, destination: u.destination, 
                tagsPost: u.tagsPost, visibility: u.visibility, time: u.time, likes: u.likes}'
                => ['tag' => $tag]];
            $publicPosts = PostQuery::postsIntoArray($query);
            return $publicPosts;
        } catch (Exception $e) {
            $e->getMessage();
        }
    }


    public static function getTagKey($tag){
        try{
            if(!empty($tag)) {
                $text = "%". $tag . "%";
                $query = [
                    'FOR u IN tag 
                 FILTER u.name LIKE @tagName                                                         
                 RETURN {key: u._key}' => ['tagName' => $text]];

                $cursor = readCollection($query);

                if ($cursor->getCount() > 0) {
                    $tag = array();
                    $tagsFound = array();

                    foreach ($cursor as $key => $value) {
                        $resultingDocuments[$key] = $value;
                        $tag['key'] = $resultingDocuments[$key]->get('key');

                        array_push($tagsFound, $tag);
                    }
                    return $tagsFound;
                }
                return null;
            }

        }catch (Exception $exception){
            $exception->getMessage();
        }
    }

    public static function filterPostByTag2($tag){
        try{
            $cursor = PostQuery::getTagKey($tag);
            var_dump($cursor);
            if(!empty($cursor)) {
                $resultingTags = array();
                $tag =array();
                $tags =array();

                foreach ($cursor as $key => $value) {
                    $resultingTags[$key] = $value;
                    $tag["key"] = $resultingTags[$key]['key'];
                    array_push($tags, $tag);
                }

                $query2 = [
                    'FOR u IN has_tag
                     FILTER u._to == @to
                     RETURN {key: u._key, from: u._from, to: u._to}' => ['to' => 'tag/' . $tag["key"]]];

                $cursor2 = readCollection($query2);
                $post = array();
                $userPosts = array();
                foreach ($cursor2 as $key => $value) {
                    $resultingDocuments[$key] = $value;
                    $post['to'] = $resultingDocuments[$key]->get('to');
                    $post['from'] = $resultingDocuments[$key]->get('from');
                    $post['key'] = $resultingDocuments[$key]->get('key');
                    
                    array_push($userPosts, $post);
                }

                $foundPosts = array();

                foreach ($userPosts as $key => $value){
                    $postKey = $userPosts[$key]["from"];

                    $postKey = substr($postKey,5);
                    $post = self::getPostFromKey($postKey);

                    $foundPosts= array_merge($foundPosts, $post);
                }
                return $foundPosts;


            }
        }catch (Exception $exception){
            $exception->getMessage();
        }
    }



    public static function getPostFromKey($key){
        try{
            $query = [
                '
                FOR u IN post 
                FILTER u._key == @key 
                SORT u.time DESC 
                RETURN {key: u._key, owner: u.owner, title: u.title, text: u.text, destination: u.destination,
                tagsPost: u.tagsPost, visibility: u.visibility, time: u.time, likes: u.likes}'
            => ['key' => $key]];
            $posts = PostQuery::postsIntoArray($query);

            return $posts;
        }catch (Exception $e) {
            $e->getMessage();
        }
    }

    public static function verifyIfUserLikedComment($commentKey, $userKey)
    {
        $statements = [
            'FOR u IN liked 
            FILTER u._to == @commentKey && u._from == @userKey 
            RETURN u._from' => ['commentKey' => 'comment/' . $commentKey, 'userKey' => 'user/' . $userKey]];
        return readCollection($statements);
    }

    public static function verifyIfUserLikedAnswer($answerKey, $userKey)
    {
        $statements = [
            'FOR u IN liked 
            FILTER u._to == @answerKey && u._from == @userKey 
            RETURN u._from' => ['answerKey' => 'answer/' . $answerKey, 'userKey' => 'user/' . $userKey]];
        return readCollection($statements);
    }

    public static function like($userKey, $postKey)
    {
        try {
            $document = new ArangoCollectionHandler(connect());

            $cursor = $document->byExample('post', ['visibility' => "Public"], ['visibility' => "Private"]);

            if ($cursor->getCount() != 0) {
                userLiked($userKey, $postKey);
            }

        } catch (Exception $e) {
            echo $e->getMessage();

        }
    }

    public static function getTags(){
        $query = 'FOR t in tag 
             RETURN {tagName: t.name}';
        $statement = new ArangoStatement(
            connect(),
            array(
                "query"     => $query,
                "count"     => true,
                "batchSize" => 1000,
                "sanitize"  => true
            )
        );

        $cursor = $statement->execute();
        $resultingDocuments = array();
        $tags = array();

        foreach ($cursor as $key => $value) {
            $resultingDocuments[$key] = $value;
            array_push($tags, $resultingDocuments[$key]->get('tagName'));
        }

        return $tags;
    }

    public static function getPostByText($text){
        $texto = "%". $text . "%";
        $statement = [
            'FOR p IN post 
            FILTER p.text LIKE @text AND p.visibility == "Public"
            RETURN {key: p._key, owner: p.owner, title: p.title, text: p.text, destination: p.destination,
                tagsPost: p.tagsPost, visibility: p.visibility, time: p.time, likes: p.likes}' => ['text' => $texto]
        ];
        $posts = PostQuery::postsIntoArray($statement);
        return $posts;
    }
}
