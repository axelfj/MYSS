<?php
/**
 * Created by PhpStorm.
 * User: Simastir_da
 * Date: 15/04/2019
 * Time: 23:19
 */

use PHPUnit\Framework\TestCase;
include_once "../Controller/Controller.php";

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
        $message = $this->controller->register($data);
        if($complete) {
            $this->assertEquals("Register successful.", $message);
        } else{
            $this->assertEquals("", $message);
        }
    }


    public function userDataProviderComplete(){
        $array = array();
        $array ['username'] = 'YValle';
        $array ['email'] = 'yvalle@gmail.com';
        $array ['password'] = '1234';
        $array ['name'] = 'Yocasta Valle';
        $array ['birthday'] = "23-08-1998";
        $array['userImage'] = "image.png";


        return [
            [$array, True]
        ];
    }

    public function userDataProviderWithoutUsername(){
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

    public function userDataProviderWithoutEmail(){
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

    public function userDataProviderWithoutPassword(){
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

    public function userDataProviderWithoutName(){
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

    public function userDataProviderWithoutBirthday(){
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

    public function userDataProviderRepeatedEmail(){
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
    public function testNonRepetitiveUsername($data){
        $message = $this->controller->register($data);

        $this->assertEquals("Cannot register. The username has been taken.", $message);
    }

    /**
     * @depends testRegisterNewUser
     * @dataProvider userDataProviderRepeatedEmail
     */
    public function testNonRepetitiveEmail($data){
        $message = $this->controller->register($data);

        $this->assertEquals("Cannot register. The email has been taken.", $message);
    }

    /**
     * @depends testRegisterNewUser
     * */
    public function testIsEmailTaken(){
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
    public function testLoginAllData($data, $correct){
        $message = $this->controller->login($data);

        switch($correct){
            case("Correct"):
                $this->assertEquals("Login succesful.", $message);
                break;
            case("Incorrect Email"):
                $this->assertEquals("The user is not registered.", $message);
                break;
            case("Incorrect Password"):
                $this->assertEquals("Incorrect password.", $message);
                break;
            case("Missing"):
                $this->assertEquals("Missing data.", $message);
                break;

        }
    }

    public function loginDataProviderCorrectPassword(){
        $array = array();
        $array ['email'] = 'yvalle@gmail.com';
        $array ['password'] = '1234';

        return[
            [$array, "Correct"]
        ];
    }

    public function loginDataProviderIncorrectEmail(){
        $array = array();
        $array ['email'] = 'yvalle@gmai.com';
        $array ['password'] = '1234';

        return[
            [$array, "Incorrect Email"]
        ];
    }

    public function loginDataProviderIncorrectPassword(){
        $array = array();
        $array ['email'] = 'yvalle@gmail.com';
        $array ['password'] = '12345';

        return[
            [$array, "Incorrect Password"]
        ];
    }

    public function loginDataProviderWithoutEmail(){
        $array = array();
        $array ['email'] = '';
        $array ['password'] = '1234';

        return[
            [$array, "Missing"]
        ];
    }

    public function loginDataProviderWithoutPassword(){
        $array = array();
        $array ['email'] = 'yvalle@gmail.com';
        $array ['password'] = '';

        return[
            [$array, "Missing"]
        ];
    }
}