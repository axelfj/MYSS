<?php
require_once "../Model/PostQuery.php";
require_once "../Controller/DTOPost_Comment_Tag.php";

class DAOPost_Comment_Tag
{
    private $dtoPost_Comment_Tag;

    public function __construct()
    {
        $this->dtoPost_Comment_Tag = new DTOPost_Comment_Tag();
    }

    public function createNewPost($dtoPost)
    {
        PostQuery::createNewPost($dtoPost);
    }

    public function createNewComment($dtoComment, $postKey)
    {
        PostQuery::createNewComment($dtoComment, $postKey);
    }

    public function verifyIfUserLiked($postKey, $userKey)
    {
        return PostQuery::verifyIfUserLiked($postKey, $userKey);
    }

    public function getPosts($username, $visibility)
    {
        // If there's an username, it means that we need the posts from the current user.
        if (isset($username)) {
            $this->dtoPost_Comment_Tag->setPosts(PostQuery::getMyPosts($username, $visibility));
        }
        // If there's not an username, it means that we need all the posts to show them
        // in the index.
        else {
            $this->dtoPost_Comment_Tag->setPosts(PostQuery::getAllPublicPosts());
        }
        return $this->dtoPost_Comment_Tag;
    }

    public function getComments($postKey)
    {
        $this->dtoPost_Comment_Tag->setComments(PostQuery::getPostComments($postKey));
        return $this->dtoPost_Comment_Tag;
    }
}