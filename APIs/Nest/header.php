<?php
/**
 * Created by Young Park.
 * Date: 2/11/15
 *
 */

function serviceLog($title,  $mssage){
    error_log("\n ".$title.' : '. $mssage ."\n", 3, $_SERVER['DOCUMENT_ROOT'].'/Services/Nest/Logs/nest.log');
}


function dumpLog($title, $dump){
    ob_start();
    var_dump($dump);
    $logs = ob_get_contents();
    ob_end_clean();
    error_log("\n ".$title.' : '. $logs ."\n", 3, $_SERVER['DOCUMENT_ROOT'].'/Services/Nest/Logs/nest.log');
}


function postJsonData($url, $para, $post=true){
    $ch = curl_init($url);
    if($post){
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $para);
    }else{
        curl_setopt($ch, CURLOPT_POST,0);
    }

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER,    array("Content-type: application/json"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    $response = curl_exec($ch);
    serviceLog('curl response : ', $response);
    curl_close($ch);
    return $response;
}


function postJsonData2($url, $para, $post=true){
    $ch = curl_init($url);
    if($post){
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $para);
    }else{
        curl_setopt($ch, CURLOPT_POST,0);
    }

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER,    array("Content-type: application/json", "Content-type: text/event-stream"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    $response = curl_exec($ch);
    serviceLog('curl response : ', $response);
    curl_close($ch);
    return $response;
}


