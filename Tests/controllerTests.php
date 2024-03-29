<?php
/**
 * Created by PhpStorm.
 * User: Simastir_da
 * Date: 15/04/2019
 * Time: 23:19
 */

use PHPUnit\Framework\TestCase;
include_once "../Controller/Controller.php";
include_once "../Controller/DTOPost_Comment_Tag.php";

class controllerTests extends TestCase
{

    private $controller;


    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName); //
        $this->controller = new Controller();
    }

    /**
     * @dataProvider userDataProviderWithoutUsername
     * @dataProvider userDataProviderWithoutEmail
     * @dataProvider userDataProviderWithoutPassword
     * @dataProvider userDataProviderWithoutName
     * @dataProvider userDataProviderWithoutBirthday
     * @dataProvider userDataProviderComplete
     * */


    public function testRegisterNewUser($data, $complete){
        $_FILES['userImage']['name'] = "";
        $_FILES['userImage']['tmp_name'] = "";
        $message = $this->controller->register($data);

        if($complete) {
            $this->assertEquals("Register successful.", $message);
        } else{
            $this->assertEquals("", $message);
        }
    }


    public function userDataProviderComplete()
    {
        $array = array();
        $array ['username'] = 'YValle';
        $array ['email'] = 'yvalle@gmail.com';
        $array ['password'] = '1234';
        $array ['name'] = 'Yocasta Valle';
        $array ['birthday'] = "23-08-1998";
        $array['userImage'] = "";


        return [
            [$array, True]
        ];
    }

    public function userDataProviderWithoutUsername()
    {
        $array = array();
        $array ['username'] = '';
        $array ['email'] = 'yvalle@gmail.com';
        $array ['password'] = '1234';
        $array ['name'] = 'Yocasta Valle';
        $array ['birthday'] = "23-08-1998";
        $array['userImage'] = "image.png";


        return [
            [$array, False]
        ];
    }

    public function userDataProviderWithoutEmail()
    {
        $array = array();
        $array ['username'] = 'YValle';
        $array ['email'] = '';
        $array ['password'] = '1234';
        $array ['name'] = 'Yocasta Valle';
        $array ['birthday'] = "23-08-1998";
        $array['userImage'] = "image.png";


        return [
            [$array, False]
        ];
    }

    public function userDataProviderWithoutPassword()
    {
        $array = array();
        $array ['username'] = 'YValle';
        $array ['email'] = 'yvalle@gmail.com';
        $array ['password'] = '';
        $array ['name'] = 'Yocasta Valle';
        $array ['birthday'] = "23-08-1998";
        $array['userImage'] = "image.png";


        return [
            [$array, False]
        ];
    }

    public function userDataProviderWithoutName()
    {
        $array = array();
        $array ['username'] = 'YValle';
        $array ['email'] = 'yvalle@gmail.com';
        $array ['password'] = '1234';
        $array ['name'] = '';
        $array ['birthday'] = "23-08-1998";
        $array['userImage'] = "image.png";


        return [
            [$array, False]
        ];
    }

    public function userDataProviderWithoutBirthday()
    {
        $array = array();
        $array ['username'] = 'YValle';
        $array ['email'] = 'yvalle@gmail.com';
        $array ['password'] = '1234';
        $array ['name'] = 'Yocasta Valle';
        $array ['birthday'] = "";
        $array['userImage'] = "image.png";


        return [
            [$array, False]
        ];
    }

    public function userDataProviderRepeatedEmail()
    {
        $array = array();
        $array ['username'] = 'YValle1';
        $array ['email'] = 'yvalle@gmail.com';
        $array ['password'] = '1234';
        $array ['name'] = 'Yocasta Valle';
        $array ['birthday'] = "23-08-1998";
        $array['userImage'] = "image.png";


        return [
            [$array, False]
        ];
    }

    /**
     * @depends testRegisterNewUser
     * */
    public function testIsUsernameTaken()
    {
        $this->assertTrue($this->controller->isUsernameTaken("YValle"));
    }

    /**
     * @depends testRegisterNewUser
     * @dataProvider userDataProviderComplete
     */
    public function testNonRepetitiveUsername($data)
    {
        $message = $this->controller->register($data);

        $this->assertEquals("Cannot register. The username has been taken.", $message);
    }

    /**
     * @depends testRegisterNewUser
     * @dataProvider userDataProviderRepeatedEmail
     */
    public function testNonRepetitiveEmail($data)
    {
        $message = $this->controller->register($data);

        $this->assertEquals("Cannot register. The email has been taken.", $message);
    }

    /**
     * @depends testRegisterNewUser
     * */
    public function testIsEmailTaken()
    {
        $this->assertTrue($this->controller->isEmailTaken("yvalle@gmail.com"));
    }


    /**
     * @depends testRegisterNewUser
     * @dataProvider loginDataProviderCorrectPassword
     * @dataProvider loginDataProviderIncorrectEmail
     * @dataProvider loginDataProviderIncorrectPassword
     * @dataProvider loginDataProviderWithoutEmail
     * @dataProvider loginDataProviderWithoutPassword
     * */
    public function testLoginAllData($data, $correct)
    {
        $message = $this->controller->login($data);

        switch($correct){
            case("Correct"):
                $this->assertEquals("Login successful.", $message);
                break;
            case("Incorrect Email"):
                $this->assertEquals("The user is not registered.", $message);
                break;
            case("Incorrect Password"):
                $this->assertEquals("Incorrect password.", $message);
                break;
            case("Missing"):
                $this->assertEquals("", $message);
                break;

        }
    }

    public function loginDataProviderCorrectPassword()
    {
        $array = array();
        $array ['email'] = 'yvalle@gmail.com';
        $array ['password'] = '1234';

        return[
            [$array, "Correct"]
        ];
    }

    public function loginDataProviderIncorrectEmail()
    {
        $array = array();
        $array ['email'] = 'yvalle@gmai.com';
        $array ['password'] = '1234';

        return[
            [$array, "Incorrect Email"]
        ];
    }

    public function loginDataProviderIncorrectPassword()
    {
        $array = array();
        $array ['email'] = 'yvalle@gmail.com';
        $array ['password'] = '12345';

        return[
            [$array, "Incorrect Password"]
        ];
    }

    public function loginDataProviderWithoutEmail()
    {
        $array = array();
        $array ['email'] = '';
        $array ['password'] = '1234';

        return[
            [$array, "Missing"]
        ];
    }

    public function loginDataProviderWithoutPassword()
    {
        $array = array();
        $array ['email'] = 'yvalle@gmail.com';
        $array ['password'] = '';

        return[
            [$array, "Missing"]
        ];
    }

    /**
     * @depends testLoginAllData
     * @dataProvider dtoPostDataProviderCorrectDataPublic
     * @dataProvider dtoPostDataProviderCorrectDataPrivate
     * @dataProvider dtoPostDataProviderMissingBody
     * @dataProvider dtoPostDataProviderMissingTitle
     * @dataProvider dtoPostDataProviderMissingUser
     */
    public function testCreatePost($dtoPost, $correct)
    {
        $test = $this->controller->createNewPost($dtoPost);
        if($correct) {
            $this->assertTrue($test);
        } else{
            $this->assertFalse($test);
        }
    }

    public function dtoPostDataProviderCorrectDataPublic(){
        $post = array();
        $post['title'] = "Título de prueba";
        $post['post'] = "Este será un post de prueba público";
        $post['tagsPost'] = "Prueba,Public";
        $post['visibility'] = "Public";
        $post['username'] = "YValle";
        $post['time'] = date('j-m-y H:i');
        $post['destination'] = "";

        $dtoPosts = new DTOPost_Comment_Tag();
        $dtoPosts->setPosts($post);

        return[
            [$dtoPosts, True]
        ];
    }


    public function dtoPostDataProviderCorrectDataPrivate(){
        $post = array();
        $post['title'] = "Título de prueba";
        $post['post'] = "Este será un post de prueba privado";
        $post['tagsPost'] = "Prueba,Privado";
        $post['visibility'] = "Private";
        $post['username'] = "YValle";
        $post['time'] = date('j-m-y H:i');
        $post['destination'] = "";

        $dtoPosts = new DTOPost_Comment_Tag();
        $dtoPosts->setPosts($post);

        return[
            [$dtoPosts, True]
        ];
    }

    public function dtoPostDataProviderMissingBody(){

        $post = array();
        $post['title'] = "Título de prueba";
        $post['post'] = "";
        $post['tagsPost'] = "Prueba";
        $post['visibility'] = "Public";
        $post['username'] = "YValle";
        $post['time'] = date('j-m-y H:i');
        $post['destination'] = "";

        $dtoPosts = new DTOPost_Comment_Tag();
        $dtoPosts->setPosts($post);

        return[
            [$dtoPosts, False]
        ];
    }

    public function dtoPostDataProviderMissingTitle()
    {
        $post = array();
        $post['title'] = "";
        $post['post'] = "Este será un post de prueba";
        $post['tagsPost'] = "Prueba";
        $post['visibility'] = "Public";
        $post['username'] = "YValle";
        $post['time'] = date('j-m-y H:i');
        $post['destination'] = "";

        $dtoPosts = new DTOPost_Comment_Tag();
        $dtoPosts->setPosts($post);

        return[
            [$dtoPosts, False]
        ];
    }

    public function dtoPostDataProviderMissingUser()
    {
        $post = array();
        $post['title'] = "Título de prueba";
        $post['post'] = "Este será un post de prueba";
        $post['tagsPost'] = "Prueba";
        $post['visibility'] = "Public";
        $post['username'] = "";
        $post['time'] = date('j-m-y H:i');
        $post['destination'] = "";

        $dtoPosts = new DTOPost_Comment_Tag();
        $dtoPosts->setPosts($post);

        return[
            [$dtoPosts, False]
        ];
    }


    /**
     * @depends testLoginAllData
     * @dataProvider followedDataProviderExist
     * @dataProvider followDataProviderDontExist
     */
    public function testFollowUser($followed, $exists){

        $dtoUser = $this->controller->getProfile($followed["username"]);
        $message = $this->controller->followUser($_SESSION['userKey'], $dtoUser['key']);
        if($exists) {
            $this->assertTrue($message);
        } else{
            $this->assertFalse($message);
        }
    }

    public function followedDataProviderExist(){
        $dtoUser = array();
        $dtoUser ['username'] = 'usuarioSeguido';
        $dtoUser ['email'] = 'userprueba@gmail.com';
        $dtoUser ['password'] = '1234';
        $dtoUser ['name'] = 'Usuario de Prueba';
        $dtoUser ['birthday'] = "23-08-1998";
        $dtoUser['userImage'] = "image.png";
        $_FILES['userImage']['name'] = "";
        $_FILES['userImage']['tmp_name'] = "";

        $this->controller->register($dtoUser);

        return[
            [$dtoUser, True]
        ];
    }

    public function followDataProviderDontExist(){
        $dtoUser = array();
        $dtoUser ['username'] = 'esteusuarionoexiste';
        $dtoUser ['email'] = 'inexistente@gmail.com';
        $dtoUser ['password'] = '1234';
        $dtoUser ['name'] = 'No existe';
        $dtoUser ['birthday'] = "23-08-1998";
        $dtoUser['userImage'] = "image.png";

        return[
            [$dtoUser, False]
        ];
    }

    /**
     * @depends testFollowUser
     * @dataProvider followedDataProvider
     * @dataProvider notFollowedDataProvider
     * @dataProvider followDataProviderDontExist
     */
    public function testIsFollowing($followed, $isFollowing){
        $dtoUser = $this->controller->getProfile($followed["username"]);
        $message = $this->controller->ifFollowing($_SESSION['userKey'], $dtoUser['key']);
        if($isFollowing){
            $this->assertTrue($message);
        } else{
            $this->assertFalse($message);
        }
    }

    public function followedDataProvider(){
        $dtoUser = array();
        $dtoUser ['username'] = 'usuarioSeguido';
        $dtoUser ['email'] = 'userprueba@gmail.com';
        $dtoUser ['password'] = '1234';
        $dtoUser ['name'] = 'Usuario de Prueba';
        $dtoUser ['birthday'] = "23-08-1998";
        $dtoUser['userImage'] = "image.png";

        return[
            [$dtoUser, True]
        ];
    }

    public function notFollowedDataProvider(){
        $dtoUser = array();
        $dtoUser ['username'] = 'usuarioNoSeguido';
        $dtoUser ['email'] = 'userprueba2@gmail.com';
        $dtoUser ['password'] = '1234';
        $dtoUser ['name'] = 'Usuario de Prueba';
        $dtoUser ['birthday'] = "23-08-1998";
        $dtoUser['userImage'] = "image.png";
        $_FILES['userImage']['name'] = "";
        $_FILES['userImage']['tmp_name'] = "";

        $this->controller->register($dtoUser);

        return[
            [$dtoUser, False]
        ];
    }

    /**
     * @depends testFollowUser
     */
    public function testGetAllFriends(){
        $cursor = $this->controller->getAllMyFriends($_SESSION['userKey']);

        $this->assertEquals(1, $cursor->getCount());
    }

    /**
     * @depends testCreatePost
     */
    public function testGetPostsPublic(){
        $cursor = $this->controller->getPosts($_SESSION['username'], "Public");
        $this->assertEquals(1, sizeof($cursor));
    }

    /**
     * @depends testCreatePost
     */
    public function testGetPostsPrivate(){
        $cursor = $this->controller->getPosts($_SESSION['username'], "Private");
        $this->assertEquals(1, sizeof($cursor));
    }

    /**
     * @depends testCreatePost
     */
    public function testComment(){
        $cursor = $this->controller->getPosts($_SESSION['username'], "Public");
        $postKey = $cursor[0]["key"];


        $comment['text'] = 'Este es un comentario de prueba.';
        $comment['tagsComment'] = 'Comentario,Prueba';
        $comment['commentOwner'] = "YValle";
        $comment['destination'] = '';

        $dtoComment = new DTOPost_Comment_Tag();
        $dtoComment->setComments($comment);

        $message = $this->controller->createNewComment($dtoComment, $postKey, "comment");

        $this->assertTrue($message);
    }


    //Pruebas tercer proyecto

    /**
     * @depends testFollowUser
     * @dataProvider friendDataProvider
     * @dataProvider unFriendDataProvider
     * @dataProvider similarUsernameFriendDataProvider
     */
    public function testSearchFriends($username, $followed){
        $result = $this->controller->filterUsername($username);
        $resultLength = count($result);
        if($followed) {
            $this->assertEquals(1, $resultLength);
        } else{
            $this->assertEquals(0, $resultLength);
        }
    }

    public function similarUsernameFriendDataProvider(){
        return[
            ["usuario", true]
        ];
    }

    public function friendDataProvider(){
        return[
            ["usuarioSeguido", true]
        ];
    }

    public function unFriendDataProvider(){
        return[
            ["usuarioNoSeguido", false]
        ];
    }

    /**
     * @depends testCreatePost
     * @dataProvider searchedPrivateDataProvided
     * @dataProvider searchedPublicDataProvided
     * @dataProvider searchedSimilarDataProvided
     */

    public function testSearchPostByDescription($description, $founded){
        $result = $this->controller->filterDescription($description);
        if($founded){
            $resultLength = count($result);
            $this->assertEquals(1, $resultLength);
        }else{
            $this->assertEquals(null, $result);
        }
    }

    public function searchedPublicDataProvided(){
        return[
            ["prueba público", true]
        ];
    }

    public function searchedPrivateDataProvided(){
        return[
            ["prueba Privado", false]
        ];
    }

    public function searchedSimilarDataProvided(){
        return[
            ["prueba", true]
        ];
    }

    /**
     * @depends testLoginAllData
     * @dataProvider correctUsernameDataProvider
     * @dataProvider incorrectDataProvider
     * @dataProvider usedUsernameDataProvider
    */
    public function testModifyUsername($newUsename, $correct){
        $data = $_SESSION;
        $_FILES['changeUserImage']['name'] = "";
        $_FILES['changeUserImage']['tmp_name'] = "";
        $data["username"] = $newUsename;
        $result = $this->controller->changeInformation($data);
        if($correct){
            $this->assertEquals('<div class="alert alert-success" role="alert">
                            The information has been updated.</div>', $result[0]);
        }
        else if($newUsename == "") {
            $this->assertEquals("<div class=\"alert alert-danger\" role=\"alert\">
                            The username can't be empty</div>", $result[0]);
        } else{
            $this->assertEquals("<div class=\"alert alert-danger\" role=\"alert\">
                            The typed username it's already taken. Please try with another one.</div>", $result[0]);
        }
    }

    public function correctUsernameDataProvider(){
        return[
            ["newUsername", true]
        ];
    }

    public function incorrectDataProvider(){
        return[
            ["", false]
        ];
    }

    public function usedUsernameDataProvider(){
        return[
            ["usuarioSeguido", false]
        ];
    }

    /**
     * @depends testLoginAllData
     * @dataProvider correctNameDataProvider
     * @dataProvider incorrectDataProvider
     */
    public function testModifyName($newName, $correct){
        $data = $_SESSION;
        $_FILES['changeUserImage']['name'] = "";
        $_FILES['changeUserImage']['tmp_name'] = "";
        $data["name"] = $newName;
        $result = $this->controller->changeInformation($data);
        if($correct){
            $this->assertEquals('<div class="alert alert-success" role="alert">
                            The information has been updated.</div>', $result[0]);
        } else{
            $this->assertEquals('<div class="alert alert-danger" role="alert">
                            The name can\'t be empty</div>', $result[0]);
        }
    }


    public function correctNameDataProvider(){
        return[
          ["New Name", true]
        ];
    }

    /**
     * @depends testLoginAllData
     * @dataProvider correctEmailDataProvider
     * @dataProvider incorrectDataProvider
     * @dataProvider takenEmailDataProvider
     *
     */
    public function testModifyEmail($email, $correct){
        $data = $_SESSION;
        $_FILES['changeUserImage']['name'] = "";
        $_FILES['changeUserImage']['tmp_name'] = "";
        $data["email"] = $email;
        $result = $this->controller->changeInformation($data);
        if($correct){
            $this->assertEquals('<div class="alert alert-success" role="alert">
                            The information has been updated.</div>', $result[0]);
        }
        else if($email == "") {
            $this->assertEquals("<div class=\"alert alert-danger\" role=\"alert\">The email is invalid.</div>", $result[0]);
        } else{
            $this->assertEquals("<div class=\"alert alert-danger\" role=\"alert\">
                            The typed email it's already taken. Please try with another one.</div>", $result[0]);
        }
    }

    public function correctEmailDataProvider(){
        return[
            ["newemail@gmail.com", true]
        ];
    }

    public function takenEmailDataProvider(){
        return[
            ["userprueba2@gmail.com", false]
        ];
    }

    /**
     * @depends testLoginAllData
     * @dataProvider incorrectPasswordDataProvider
     * @dataProvider correctPasswordDataProvider
     * @dataProvider missingOldPasswordDataProvider
     * @dataProvider missingNewPasswordDataProvider

     *
     */
    public function testModifyPassword($oldPassword, $newPassword, $correct){
        $data = $_SESSION;
        $_FILES['changeUserImage']['name'] = "";
        $_FILES['changeUserImage']['tmp_name'] = "";
        $data["oldPassword"] = $oldPassword;
        $data["newPassword"] = $newPassword;
        $result = $this->controller->changeInformation($data);
        if($correct){
            $this->assertEquals('<div class="alert alert-success" role="alert">
                            The information has been updated.</div>', $result[0]);
        }else if(empty($oldPassword)){
            $this->assertEquals('<div class="alert alert-danger" role="alert">You have to type your current password.</div>', $result[0]);
        } else if(empty($newPassword)){
            $this->assertEquals('<div class="alert alert-danger" role="alert">The new password can\'t be empty.</div>', $result[0]);
        } else{
            $this->assertEquals('<div class="alert alert-danger" role="alert">
                            The current password typed is incorrect.</div>', $result[0]);

        }
    }

    public function correctPasswordDataProvider(){
        return[
            ["1234", "12345", true]
        ];
    }

    public function missingOldPasswordDataProvider(){
        return[
            ["", "12345", false]
        ];
    }

    public function missingNewPasswordDataProvider(){
        return[
            ["1234", "", false]
        ];
    }

    public function incorrectPasswordDataProvider(){
        return[
            ["12345", "1234", false]
        ];
    }

    /**
     * @depends testLoginAllData
     * @dataProvider correctImageDataProvider
     * @dataProvider incorrectImageDataProvider
     */

    public function testModifyImage($imageName, $correct){
        $data = $_SESSION;
        $_FILES['changeUserImage']['name'] = $imageName;
        $_FILES['changeUserImage']['tmp_name'] = $imageName;

        $result = $this->controller->changeInformation($data);
        if($correct){
            $this->assertEquals('<div class="alert alert-success" role="alert">
                            The information has been updated.</div>', $result[0]);
        }
        else{
            $this->assertEquals('<div class="alert alert-danger" role="alert">You just can upload ".gif", ".jpg", ".jpeg" and ".png" files</div>', $result[0]);
        }
    }

    public function correctImageDataProvider(){
        return[
            ["image.PNG", true]
        ];
    }

    public function incorrectImageDataProvider(){
        return[
            ["image.PDF", false]
        ];
    }
}