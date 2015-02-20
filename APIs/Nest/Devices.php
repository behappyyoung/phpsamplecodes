<?php
/**
 * Created by Young Park.
 * Date: 2/13/15
 *
 */

session_start();
include_once('Nest.php');
if(isset($_SESSION['access_token'])) {
    $access_token = $_SESSION['access_token'];
    $nest = new Nest();
    $result = $nest->showDevices($access_token);
    setcookie('nest_token', $access_token, time()+3600, '/');
    echo '<a href="interact.php" > See Interaction </a>';
    echo '<pre>';
    print_r($result);
    echo '</pre>';
}else{
    header('Location: https://php.ypark.org/APIs/Nest/');
}
?>


