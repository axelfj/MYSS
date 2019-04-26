<?php
require_once "../Model/UserQuery.php";
require_once "../Controller/DTOUser.php";
require_once "../Model/UserQuery.php";

class DAOUser
{
    private $dtoUser;

    public function __construct()
    {
        $this->dtoUser = new DTOUser();
    }

    public function getUser($email)
    {
        $this->dtoUser->setUser(UserQuery::getInformation($email));
        return $this->dtoUser;
    }

    public function getProfile($username)
    {
        $this->dtoUser->setUser(UserQuery::getProfile($username));
        return $this->dtoUser;
    }

    public function isEmailTaken($email)
    {
        $this->dtoUser->setUser(UserQuery::isEmailTaken($email));
        return $this->dtoUser;
    }

    public function isUsernameTaken($email)
    {
        $this->dtoUser->setUser(UserQuery::isUsernameTaken($email));
        return $this->dtoUser;
    }

    public function createNewUser($username, $email, $password, $name, $birthday, $userImage)
    {
        UserQuery::register($username, $email, $password, $name, $birthday, $userImage);
    }

    public function followUser($fromUser, $toUser)
    {
        return UserQuery::followUser($fromUser, $toUser);
    }

    public function ifFollowing($fromUser, $toUser)
    {
        return UserQuery::ifFollowing($fromUser, $toUser);
    }

    // Calls the UserQuery function to get all the friends from an User.
    public function getAllMyFriends($userId)
    {
        return UserQuery::getAllMyFriends($userId);
    }

}