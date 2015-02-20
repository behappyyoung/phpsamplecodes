<?php
/**
 * Created by Young Park.
 * Date: 2/13/15
 *
 */

include_once('header.php');
session_start();

    $access_token = $_REQUEST['access_token'];

    $para = '';
    $url = "https://developer-api.nest.com/?auth=".$access_token;
    $result = postJsonData2($url, $para, false);
    setcookie('nest_token', $access_token, time()+3600, '/');
    echo '<pre>';
    print_r(json_decode($result, true));
    echo '</pre>';

?>
