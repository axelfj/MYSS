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
            $this->assertEquals("Missing data", $message);
        }
    }


    public function userDataProviderComplete(){
        $array = array();
        $array ['username'] = 'YValle';
        $array ['email'] = 'yvalle@gmail.com';
        $array ['password'] = '1234';
        $array ['name'] = 'Yocasta Valle';
        $array ['birthday'] = "23-08-1998";


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


        return [
            [$array, False]
        ];
    }

    public function userDataProviderwithoutPassword(){
        $array = array();
        $array ['username'] = 'YValle';
        $array ['email'] = 'yvalle@gmail.com';
        $array ['password'] = '';
        $array ['name'] = 'Yocasta Valle';
        $array ['birthday'] = "23-08-1998";


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
     * */

    public function testIsEmailTaken(){
        $this->assertTrue($this->controller->isEmailTaken("yvalle@gmail.com"));
    }
}