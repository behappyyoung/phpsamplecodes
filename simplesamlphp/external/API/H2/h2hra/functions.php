<?php

require_once('config.php');

function showArray($myarray){
    echo '<pre>';
    foreach($myarray as $key => $value){
        echo $key. '=>'. $value. '</br />';
    }
    echo '</pre>';
}


?>