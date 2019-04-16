<?php
/**
 * Created by PhpStorm.
 * User: Simastir_da
 * Date: 15/04/2019
 * Time: 23:19
 */

use PHPUnit\Framework\TestCase;

class signuptests extends TestCase
{

    public function testGuapo()
    {
        $axel = $this->nameAxel();

        $this->assertEquals(
            "Guapo",
            $axel
        );
    }

    public function nameAxel(){
        return "gay";
    }

}