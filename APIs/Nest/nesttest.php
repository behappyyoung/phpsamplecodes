<?php
/**
 * Created by Young Park.
 * Date: 2/13/15
 *
 */

include_once('Nest.php');
session_start();
$nest = new Nest();

$_SESSION['access_token']='c.m2oeWf1sSOxXtYDGCFbbWItjcFbxy3ll851EFBy7MaivpogJPLH9ld4i5EvPtXPDXjz2t7f1D0xmkRvSOim35sB7uZRmjzgNzuR0k1xZ4exa1iG9XORB4XetNttlgQEu1g63PMULMu3o7EIm';


    $access_token = $_SESSION['access_token'];



    $result = $nest->showDevices($access_token);

    setcookie('nest_token', $access_token, time()+3600, '/');
echo '<a href="interact" > See Interaction </a>';
    echo '<pre>';
    print_r($result);
    echo '</pre>';

?>
