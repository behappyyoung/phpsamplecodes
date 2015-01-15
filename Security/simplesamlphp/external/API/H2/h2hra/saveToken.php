<?php

require_once('config.php');

$myjson  = (isset($_REQUEST['myjson']))? $_REQUEST['myjson'] : '';


//var_dump($output);

if($myjson!=''){

    $email =  $myjson['data']['response']['User']['email'];
    $uuid = $myjson['data']['response']['User']['uuid'];
    $token = $myjson['data']['response']['User']['token'];

    try {

        $PDO = new PDO('mysql:host=localhost;dbname='.DATABASE, 'h2hra', 'h2hra');
        $PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $query = 'Update patient set `uuid` = "'.$uuid.'", `token` = "'.$token.'" WHERE email="'.$email.'"';
        echo $query;
        $results = $PDO->exec($query);
        $PDO = null;
        echo $results;
        exit();
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

}else{

    echo 'no patient';
    exit();
}

