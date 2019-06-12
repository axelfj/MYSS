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
        return $this->daoPost_Comment_Tag->createNewPost($dtoPost);
    }

    public function createNewComment($dtoComment, $postKey, $type)
    {
        return $this->daoPost_Comment_Tag->createNewComment($dtoComment, $postKey, $type);
    }

    public function verifyIfUserLikedPost($postKey, $userKey)
    {
        return $this->daoPost_Comment_Tag->verifyIfUserLikedPost($postKey, $userKey);
    }

    public function verifyIfUserLikedComment($postKey, $userKey)
    {
        return $this->daoPost_Comment_Tag->verifyIfUserLikedComment($postKey, $userKey);
    }

    public function verifyIfUserLikedAnswer($postKey, $userKey)
    {
        return $this->daoPost_Comment_Tag->verifyIfUserLikedAnswer($postKey, $userKey);
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

    public function getComments($postKey, $collectionName)
    {
        $dtoPost_Comment_Tag = $this->daoPost_Comment_Tag->getComments($postKey, $collectionName);
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

    public function followUser($fromUser, $toUser)
    {
        return $this->daoUser->followUser($fromUser, $toUser);
    }

    public function ifFollowing($fromUser, $toUser)
    {
        return $this->daoUser->ifFollowing($fromUser, $toUser);
    }


    // Tells the DAO to get to return all the friends.
    public function getAllMyFriends($userId)
    {
        return $this->daoUser->getAllMyFriends($userId);
    }

    public function register($data)
    {
        try {
            if ((!empty($data['username'])) &&
                (!empty($data['email'])) &&
                (!empty($data['password'])) &&
                (!empty($data['name'])) &&
                (!empty($data['birthday']))) {

                $imageName = $_FILES['userImage']['name'];
                $imageTempName = $_FILES['userImage']['tmp_name'];

                if ($imageName != "") {

                    $type = explode('.', $imageName);
                    $type = strtolower($type[count($type) - 1]);

                    if (in_array($type, array('gif', 'jpg', 'jpeg', 'png'))) {

                        $userImage = 'profilePictures/' . uniqid(rand()) . '.' . $type;
                        $data['userImage'] = $userImage;
                        move_uploaded_file($imageTempName, $userImage);
                    } else {
                        return '<div class="alert alert-danger" role="alert">You just can upload ".gif", ".jpg", ".jpeg" and ".png" files</div>';
                    }

                } else {
                    $data['userImage'] = 'img/user.png';
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
                        return "Cannot register. The email has been taken.";
                    }
                } else {
                    return "Cannot register. The username has been taken.";
                }
            } else {
                return "";
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function login($data)
    {
        try {
            if (!empty($data['email']) &&
                !empty($data['password'])) {

                $cursor = $this->getUser($data['email']);

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
                        $personalInformation['userImage'] = $resultingDocuments [$key]->get('userImage');
                    }

                    if (password_verify($data['password'], $personalInformation['password'])) {
                        $_SESSION['username'] = $personalInformation['username'];
                        $_SESSION['userKey'] = $personalInformation['userKey'];
                        $_SESSION['name'] = $personalInformation['name'];
                        $_SESSION['email'] = $personalInformation['email'];
                        $_SESSION['userImage'] = $personalInformation['userImage'];

                        return "Login successful.";
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


    public function changeInformation($data)
    {
        try {
            $messages = array();
            $errorChangingUsername = $this->changeUsername($data['username']);
            $errorChangingEmail = $this->changeEmail($data['email']);
            $errorChangingPicture = $this->changePicture();
            $errorChangingName = $this->changeName($data['name']);

            if (!empty($data['oldPassword']) || !empty($data['newPassword'])) {
                $errorChangingPassword = $this->changePassword($data['oldPassword'], $data['newPassword']);

                if (isset($errorChangingPassword)) {
                    array_push($messages, $errorChangingPassword);
                }
            }
            if (isset($errorChangingUsername)) {
                array_push($messages, $errorChangingUsername);
            }
            if (isset($errorChangingEmail)) {
                array_push($messages, $errorChangingEmail);
            }
            if (isset($errorChangingPicture)) {
                array_push($messages, $errorChangingPicture);
            }
            if (isset($errorChangingName)) {
                array_push($messages, $errorChangingName);
            }
            if (isset($data['birthday'])) {
                $this->daoUser->changeBirthday($_SESSION['username'], $data['birthday']);
            }
            // There are no errors.
            if (empty($messages)) {
                array_push($messages, '<div class="alert alert-success" role="alert">
                            The information has been updated.</div>');
            }
            return $messages;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    private function changeUsername($newUsername)
    {
        if ($newUsername != $_SESSION['username']) {
            if (!$this->isUsernameTaken($newUsername)) {
                if (!ctype_space($newUsername) && !empty($newUsername)){
                    $this->daoUser->changeUsername($_SESSION['username'], $newUsername);
                } else {
                    return '<div class="alert alert-danger" role="alert">
                            The username can\'t be empty</div>';
                }
            } else {
                return '<div class="alert alert-danger" role="alert">
                            The typed username it\'s already taken. Please try with another one.</div>';
            }
        }
    }

    private function changeName($newName)
    {
        if ($newName != $_SESSION['name']) {
            if (!ctype_space($newName) && !empty($newName)){
                $this->daoUser->changeName($_SESSION['username'], $newName);
            } else {
                return '<div class="alert alert-danger" role="alert">
                            The name can\'t be empty</div>';
            }
        }
    }

    private function changeEmail($newEmail)
    {
        if ($newEmail != $_SESSION['email']) {
            if (!$this->isEmailTaken($newEmail)) {
                if (filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                    $this->daoUser->changeEmail($_SESSION['email'], $newEmail);
                } else {
                    return '<div class="alert alert-danger" role="alert">The email is invalid.</div>';
                }
            } else {
                return '<div class="alert alert-danger" role="alert">
                            The typed email it\'s already taken. Please try with another one.</div>';
            }
        }
    }

    private function changePicture()
    {
        $imageName = $_FILES['changeUserImage']['name'];
        $imageTempName = $_FILES['changeUserImage']['tmp_name'];

        if ($imageName != "") {
            $type = explode('.', $imageName);
            $type = strtolower($type[count($type) - 1]);

            if (in_array($type, array('gif', 'jpg', 'jpeg', 'png'))) {
                $userImage = 'profilePictures/' . uniqid(rand()) . '.' . $type;
                $this->daoUser->changePicture($_SESSION['username'], $userImage);
                move_uploaded_file($imageTempName, $userImage);
            } else {
                return '<div class="alert alert-danger" role="alert">You just can upload ".gif", ".jpg", ".jpeg" and ".png" files</div>';
            }

        }
    }

    private function changePassword($oldPassword, $newPassword)
    {
        $cursor = $this->getUser($_SESSION['email']);
        $oldPasswordEncrypted = '';
        $resultingDocuments = array();

        if (empty($oldPassword)) {
            return '<div class="alert alert-danger" role="alert">You have to type your current password.</div>';
        }
        if (empty($newPassword)) {
            return '<div class="alert alert-danger" role="alert">The new password can\'t be empty.</div>';
        }

        foreach ($cursor as $key => $value) {
            $resultingDocuments [$key] = $value;
            $oldPasswordEncrypted = $resultingDocuments [$key]->get('password');
        }
        if (password_verify($oldPassword, $oldPasswordEncrypted)) {
            $newPasswordEncrypted = password_hash($newPassword, PASSWORD_BCRYPT);
            $this->daoUser->changePassword($_SESSION['username'], $newPasswordEncrypted);
        } else {
            return '<div class="alert alert-danger" role="alert">
                            The current password typed is incorrect.</div>';
        }
    }


    public function filterPostsByTag($tag)
    {
        $dtoPost_Comment_Tag = $this->daoPost_Comment_Tag->filterPostsByTag($tag);
        return $dtoPost_Comment_Tag->getPosts();
    }

    public function filterUsername($user)
    {
        $result = $this->daoUser->filterUsername($user);
        return $result;
    }

    public function filterDescription($description)
    {
        $dtoPost_Comment_Tag = $this->daoPost_Comment_Tag->filterPostsByDescription($description);
        return $dtoPost_Comment_Tag->getPosts();
    }

    // Verifies if an user is trying to visit another user's profile.
    // If that occurs, then this function will return the username of the
    // user that is getting visited. If not, then it will return false.
    public function getUserName($url)
    {
        $posStart = strpos($url, '?');
        $posEnd = strlen($url);

        if ($posStart != false) {
            $username = substr($url, $posStart + 1, $posEnd - $posStart);
            return $username;
        }
        return false;
    }

    // This function verifies if just an image is going to be uploaded. If that's true,
    // the destination path to the image will be set to $post array.
    public function verifyImageUpload($post, $imageInputName)
    {
        $imageName = $_FILES[$imageInputName]['name'];
        $imageTempName = $_FILES[$imageInputName]['tmp_name'];

        if ($imageName != "") {
            $type = explode('.', $imageName);
            $type = strtolower($type[count($type) - 1]);

            // If there's an image to upload, the destination is set and the image is moved to
            // that destination.
            if (in_array($type, array('gif', 'jpg', 'jpeg', 'png'))) {
                $destination = 'userImages/' . uniqid(rand()) . '.' . $type;
                $post['destination'] = $destination;
                move_uploaded_file($imageTempName, $destination);
            } else {
                // If the user tries to upload anything else that is not an image, an
                // error message appears and the function returns null.
                echo '<div class="alert alert-danger" role="alert">You just can upload ".gif", ".jpg", ".jpeg" and ".png" files</div>';
                return null;
            }
        } else { // If there's not an image to upload, the destination is empty.
            $post['destination'] = '';
        }
        return $post;
    }

    public function like($userKey, $postKey)
    {
        $this->daoPost_Comment_Tag->like($userKey, $postKey);
    }

    public function getTags()
    {
        return $this->daoPost_Comment_Tag->getTags();
    }

    public function search($text, $mode){
        if ($mode == 1)
            $this->filterPostsByTag($text);
        if ($mode == 2)
            $this->filterDescription($text);
        if ($mode == 3)
            $this->filterUsername($text);
    }
}