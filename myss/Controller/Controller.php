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

    function register($Data)
    {
        try {
            if ((!empty($Data['username'])) &&
                (!empty($Data['email'])) &&
                (!empty($Data['password'])) &&
                (!empty($Data['name'])) &&
                (!empty($Data['birthday']))) {



                if ($this->isUsernameTaken($Data['username']) == false) {
                    if ($this->isEmailTaken($Data['email']) == false) {
                        if (filter_var($Data['email'], FILTER_VALIDATE_EMAIL)) {

                            $password = password_hash($Data['password'], PASSWORD_BCRYPT);

                            $this->registerNewUser($Data['username'],
                                $Data['email'],
                                $password,
                                $Data['name'],
                                $Data['birthday']);

                            header('Location: ..\View\login.php');
                        } else {
                            return "Cannot register. The email is invalid.";
                        }
                    } else {
                        return "Cannot register. The email has been taken";
                    }
                } else {
                    return "Cannot register. The username has been taken.";
                }
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}