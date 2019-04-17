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

    public function __construct()
    {
        parent::__construct();
        $this->controller = new Controller();
    }


    public function testIsUsernameTaken()
    {
        $this->assertFalse($this->controller->isUsernameTaken("YValle"));
    }

    public function testIsEmailTaken(){
        $this->assertFalse($this->controller->isEmailTaken("YValle@gmail.com"));
    }



    /*
     * @dataProvider userDataProvider
     * */
    public function testRegisterNewUser($data){
//        $array = array();
//        $array ['username'] = 'YValle';
//        $array ['email'] = 'yvalle@gmail.com';
//        $array ['password'] = '1234';
//        $array ['name'] = 'Yocasta Valle';
//        $array ['birthday'] = "23-08-1998";

        $message = $this->controller->register($data);

        $this->assertEquals("", $message);
    }

    public function userDataProvider(){
        $array = array();
        $array ['username'] = 'YValle';
        $array ['email'] = 'yvalle@gmail.com';
        $array ['password'] = '1234';
        $array ['name'] = 'Yocasta Valle';
        $array ['birthday'] = "23-08-1998";

        return [
            [$array]
        ];
    }
}