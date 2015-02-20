<?php
/**
 * Created by Young Park.
 * Date: 2/11/15
 *
 */
session_start();
include_once('Nest.php');

if(isset($_SESSION['access_token'])){
    header('Location: https://php.ypark.org/APIs/Nest/Devices.php');
}else if(isset($_REQUEST['code'])){
    $nest = new Nest();
    $result = $nest->getAccessToken($_REQUEST['code']);
    if($result['result']){
        $_SESSION['access_token'] = $result['access_token'];
        echo '<a href="Devices" > show devices  </a> <br />';
    }else{
        echo 'something wrong <br />';
        echo $result['error'];
        echo '<a href="https://php.ypark.org/APIs/Nest/" > Try Again </a>';
    }
}else{
    header('Location: https://home.nest.com/login/oauth2?client_id=f497bb7d-8d4d-4760-8d68-58ba9f9ceb1d&state=CA');
}
