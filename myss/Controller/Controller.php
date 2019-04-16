<?php
require_once "../Controller/DAOPost_Comment_Tag.php";
require_once "../Controller/DAOUser.php";

class Controller
{
    private $daoPost_Comment_Tag;
    private $daoUser;

    public function __construct()
    {
        $this->daoUser = new DAOUser();
        $this->daoPost_Comment_Tag = new DAOPost_Comment_Tag();
    }

    public function createNewPost($dtoPost)
    {
        $this->daoPost_Comment_Tag->createNewPost($dtoPost);
    }

    public function createNewComment($dtoComment, $postKey)
    {
        $this->daoPost_Comment_Tag->createNewComment($dtoComment, $postKey);
    }

    public function verifyIfUserLiked($postKey, $userKey)
    {
        return $this->daoPost_Comment_Tag->verifyIfUserLiked($postKey, $userKey);
    }

    public function getUser($email)
    {
        $dtoUser = $this->daoUser->getUser($email);
        return $dtoUser->getUser();
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

    public function isUsernameTaken($username)
    {
        $dtoUser = $this->daoUser->isUsernameTaken($username);
        return $dtoUser->getUser();
    }

    public function isEmailTaken($email)
    {
        $dtoUser = $this->daoUser->isEmailTaken($email);
        return $dtoUser->getUser();
    }

    public function registerNewUser($username, $email, $password, $name, $birthday)
    {
        $this->daoUser->createNewUser($username, $email, $password, $name, $birthday);
    }

}