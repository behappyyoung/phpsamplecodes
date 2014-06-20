<?php
/**
 * Created by PhpStorm.
 * User: young
 * Date: 2/24/14
 * Time: 2:33 PM
 */
error_reporting(E_ALL);

include_once('Yummly.php');

class YummlyTest extends PHPUnit_Framework_TestCase {


    function setUp(){
        $this->yummly = new Yummly();
    }
    function tearDown() {
        // delete instance
        unset($this->yummly);
    }

    public function testgetRecipe(){

        $userresult = $this->yummly->getRecipe('');
        $this->assertEquals(false, $userresult);

    }


    public function testsearchRecipe(){
        $result = $this->yummly->searchRecipe('', 10, 20);
        $this->assertInternalType('array', $result);

        $result = $this->yummly->searchRecipe('-----', 10, 20);
        $this->assertInternalType('array', $result);

        $result = $this->yummly->searchRecipe('bacon', 10, 20);
        $this->assertInternalType('array', $result);
    }
}
 