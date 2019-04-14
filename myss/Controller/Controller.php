<?php
require_once "../Controller/DAOPost_Comment_Tag.php";

class Controller
{
    private $daoPost_Comment_Tag;

    public function __construct()
    {
        $this->daoPost_Comment_Tag = new DAOPost_Comment_Tag();
    }

    public function createNewPost($dtoPost)
    {
        $this->daoPost_Comment_Tag->createNewPost($dtoPost);
    }

    public function verifyIfUserLiked($postKey, $userKey)
    {
        return $this->daoPost_Comment_Tag->verifyIfUserLiked($postKey, $userKey);
    }

    public function getPosts($username)
    {
        $dtoPost_Comment_Tag = $this->daoPost_Comment_Tag->getPosts($username);
        return $dtoPost_Comment_Tag->getPosts();
    }

    public function getComments($postKey)
    {
        $dtoPost_Comment_Tag = $this->daoPost_Comment_Tag->getComments($postKey);
        return $dtoPost_Comment_Tag->getComments();
    }
}