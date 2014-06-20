<?php
/**
 * Created by PhpStorm.
 * User: young
 * Date: 2/24/14
 * Time: 2:33 PM
 */
error_reporting(E_ALL);

include_once('NutritionLib.php');

class NutritionLibTest extends PHPUnit_Framework_TestCase {

    function setUp(){
        $this->nlib = new NutritionLib();
    }
    function tearDown() {
        // delete instance
        unset($this->nlib);
    }
}

 