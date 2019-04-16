<?php
require_once "../Model/PostQuery.php";
require_once "../Controller/DTOUser.php";

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

    public function createNewUser($username, $email, $password, $name, $birthday)
    {
        UserQuery::register($username, $email, $password, $name, $birthday);
    }

}