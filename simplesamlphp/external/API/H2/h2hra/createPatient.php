<?php

require_once('config.php');

$pid  = (isset($_GET['pid']))? $_GET['pid'] : '';

if($pid !='') {

    try {

        $PDO = new PDO('mysql:host=localhost;dbname='.DATABASE, 'h2hra', 'h2hra');
        $PDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $query = 'Select * from patient WHERE id="'.$pid.'"';
        $stmt = $PDO->query($query);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $token = $results[0]['token'];
        $PDO = null;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }

}else{

    echo 'no patient';
    exit();
}

if($results[0]['id'] !=''){
$curlURL = 'http://services.h2wellness.com/rest/patients/create/';

$queryString = '?first_name='.$results[0]['firstname'].'&last_name='.$results[0]['lastname'].'&gender=M&email='.$results[0]['email'].'&username=young&password=young';

echo $curlURL.$queryString;
$ch = curl_init($curlURL.$queryString);

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$output = curl_exec($ch);
echo ($output) ? 'User Created' : 'Error';
}else{
    echo 'error';
}
?>


<form action="getQuestions.php">
    <select name="pid">


    </select>

       <input type="submit" value="getQuestions" />
</form>