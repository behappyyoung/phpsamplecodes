<?php
/**
 * Created by PhpStorm.
 * User: young
 * Date: 3/17/14
 * Time: 3:48 PM
 */
//session_start();//

setcookie('test', 'testcookoe', time()+3600);


echo $_COOKIE['test'];

/*
$_SESSION['test'] = 'test session';

echo $_SESSION['test'];
echo $_SESSION['test'];
*/

session_unset();