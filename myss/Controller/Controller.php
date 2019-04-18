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

    public function getProfile($username)
    {
        $dtoUser = $this->daoUser->getProfile($username);
        return $dtoUser->getUser();
    }

    public function getPosts($username, $visibility)
    {
        $dtoPost_Comment_Tag = $this->daoPost_Comment_Tag->getPosts($username, $visibility);
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

    public function registerNewUser($username, $email, $password, $name, $birthday, $userImage)
    {
        $this->daoUser->createNewUser($username, $email, $password, $name, $birthday, $userImage);
    }

    function register($data)
    {
        try {
            if ((!empty($data['username'])) &&
                (!empty($data['email'])) &&
                (!empty($data['password'])) &&
                (!empty($data['name'])) &&
                (!empty($data['birthday']))) {
                if (empty($data['userImage'])){
                    return 'Please upload an imagen for your profile.';
                }

                if ($this->isUsernameTaken($data['username']) == false) {
                    if ($this->isEmailTaken($data['email']) == false) {
                        if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {

                            $password = password_hash($data['password'], PASSWORD_BCRYPT);

                            $this->registerNewUser(
                                $data['username'],
                                $data['email'],
                                $password,
                                $data['name'],
                                $data['birthday'],
                                $data['userImage']);

                            return "Register successful.";

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

    function login(){
        try {
            if (!empty($_POST['email']) &&
                !empty($_POST['password'])) {
                $controller = new Controller();
                $cursor = $controller->getUser($_POST['email']);

                if ($cursor->getCount() != 0) {

                    $personalInformation = array();
                    $resultingDocuments = array();

                    foreach ($cursor as $key => $value) {
                        $resultingDocuments [$key] = $value;
                        $personalInformation['username'] = $resultingDocuments [$key]->get('username');
                        $personalInformation['userKey'] = $resultingDocuments [$key]->get('key');
                        $personalInformation['name'] = $resultingDocuments [$key]->get('name');
                        $personalInformation['email'] = $resultingDocuments [$key]->get('email');
                        $personalInformation['password'] = $resultingDocuments [$key]->get('password');
                    }

                    if (password_verify($_POST['password'], $personalInformation['password'])) {
                        $_SESSION['username'] = $personalInformation['username'];
                        $_SESSION['userKey'] = $personalInformation['userKey'];
                        $_SESSION['name'] = $personalInformation['name'];
                        $_SESSION['email'] = $personalInformation['email'];
                        header('Location: ..\View\index.php');
                    } else {
                        return 'Incorrect password.';
                    }
                } else {
                    return 'The user is not registered.';
                }
            } else {
                return '';
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}