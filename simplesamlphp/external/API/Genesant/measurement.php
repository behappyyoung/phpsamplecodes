<?php
session_start();
if($_GET['test']=='test'){
	$testmode=true;
}else{
	$testmode = false;	
}


require_once("../config.php");
$result = false;

if(empty($_REQUEST)){
    echo 'ERROR - Parameter is Empty ';
    
}else{
    $testinputs = implode(',', $_REQUEST);
    $connection = new PDO('mysql:dbname='.$EXT_DB_NAME.';host='.$DATA_HOST, $DB_USERNAME, $DB_PASSWORD );
//    $devconnection = new PDO('mysql:dbname='.$EXT_DB_NAME.';host='.$DEV_DATA_HOST, $DB_USERNAME, $DB_PASSWORD );
    $query = "INSERT INTO genesant_activity_inputs (`inputs`, `type`) values ('$testinputs', 'mesurement') ";
    $result = $connection->exec($query);
//    $devresult = $devconnection->exec($query);  // for dev save
    
    if($result){    
        $testinputs = implode(',', $_REQUEST);

        $mpwg_connection = new PDO('mysql:dbname='.$DB_NAME.';host='.$DB_HOST, $DB_USERNAME, $DB_PASSWORD );
        $query = "INSERT INTO genesant_activity_measurement ( `member_id`, `name`, `desc`, `value`, `target_date`, `entry_date`)
            values ('".$_REQUEST['EMemberID']."', '".$_REQUEST['name']."' , '".$_REQUEST['description']."' , '".$_REQUEST['value']."' , '".$_REQUEST['date']."' , '".$_REQUEST['timestamp']."'  ) ";
        $result = $connection->exec($query);
        
            $query = 'SELECT * FROM mpwg.SHN_member_info WHERE member_id="'.$_REQUEST['EMemberID'].'"';
            $statement = $mpwg_connection->prepare($query);
            $statement->execute();
            
            if($statement->rowCount() > 0){
                $query ='UPDATE mpwg.SHN_member_info SET '.$_REQUEST['name'].'="'.$_REQUEST['value'].'" WHERE member_id="'.$_REQUEST['EMemberID'].'"';
            }else{
                $query ='INSERT INTO mpwg.SHN_member_info (member_id, '.$_REQUEST['name'].' )
                VALUES ("'.$_REQUEST['EMemberID'].'","'.$_REQUEST['value'].'")';
            }
                echo $query.'<br />';            
                $statement = $mpwg_connection->prepare($query);
                $statement->execute();         
        
    }

    if($result){
        echo 'OK';
    }else{
        echo 'ERROR - measurement DB entry';
    }
}
exit();
?>