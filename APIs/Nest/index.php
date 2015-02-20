<?php
/**
 * Created by Young Park.
 * Date: 2/11/15
 *
 */
session_start();
include_once('Nest.php');

if(isset($_SESSION['access_token'])){
    header('Location: https://partnerserver.hondasvl.com/Services/Nest/Devices');
}else if(isset($_REQUEST['code'])){
    $nest = new Nest();
    $result = $nest->getAccessToken($_REQUEST['code']);
    if($result['result']){
        $_SESSION['access_token'] = $result['access_token'];
        echo '<a href="Devices" > show devices  </a> <br />';
    }else{
        echo 'something wrong <br />';
        echo $result['error'];
        echo '<a href="https://partnerserver.hondasvl.com/Services/Nest/" > Try Again </a>';
    }
}else{
    header('Location: https://home.nest.com/login/oauth2?client_id=e0101285-4b16-4ef8-988f-3adc4d7de30f&state=CA');
}
