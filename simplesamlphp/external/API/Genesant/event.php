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
    $query = "INSERT INTO genesant_activity_inputs (`inputs`, `type`) values ('$testinputs', 'event') ";
    $result = $connection->exec($query);
    //$devresult = $devconnection->exec($query);  // for dev save
    

    if($result){    
        $testinputs = implode(',', $_REQUEST);
 //       $connection = new PDO('mysql:dbname='.$EXT_DB_NAME.';host='.$DATA_HOST, $DB_USERNAME, $DB_PASSWORD );
        $mpwg_connection = new PDO('mysql:dbname='.$DB_NAME.';host='.$DB_HOST, $DB_USERNAME, $DB_PASSWORD );
        $query = "INSERT INTO genesant_activity_event (`event_id`, `member_id`, `name`, `desc`, `target_date`, `entry_date`, `logText` )
            values ('".$_REQUEST['eventID']."', '".$_REQUEST['EMemberID']."', '".$_REQUEST['eventName']."' , '".$_REQUEST['description']."' , '".$_REQUEST['eventDate']."' , '".$_REQUEST['eventTimestamp']."' , '".$_REQUEST['logTxt']."'  ) ";
        $result = $connection->exec($query);

            $query ='INSERT INTO mpwg.SHN_reporting_memberpoint_genesant (member_id, date_earn, target_date, point_id, points )
            SELECT "'.$_REQUEST['EMemberID'].'","'.date('Y-m-d',  $_REQUEST['eventTimestamp']).'", "'.$_REQUEST['eventDate'].'" , id, value FROM mpwg.SHN_reporting_point_genesant WHERE matching_event = "'.$_REQUEST['eventName'].'" ';
           $result = $mpwg_connection->exec($query);
           $posstring = stripos($_REQUEST['eventName'], 'profile_pic');
           if( $posstring !== false){
                $firstpos = stripos($_REQUEST['logTxt'], '<img src="');
                $firststring = substr($_REQUEST['logTxt'], $firstpos+10);
                $middlepos = stripos($firststring, '.jpg"');
                $imgstring = substr($_REQUEST['logTxt'], $firstpos+10,  $middlepos+4);
                
                $query = 'SELECT * FROM  mpwg.SHN_member_info  WHERE member_id = "'.$_REQUEST['EMemberID'].'"';
                $stmt = $mpwg_connection->prepare($query);
                $stmt->execute();
                if ($stmt->fetchColumn() > 0) {
                    $query ='UPDATE mpwg.SHN_member_info SET genesant_pic_url = "'.$imgstring.'"  WHERE member_id = "'.$_REQUEST['EMemberID'].'"';             
                }else{
                    $query ='INSERT INTO mpwg.SHN_member_info (member_id, genesant_pic_url ) values ("'.$_REQUEST['EMemberID'].'", "'.$imgstring.'");';
                    
                }
                
                $stmt2 = $mpwg_connection->prepare($query);
                $result =  $stmt2->execute();
                

            //   echo $query;
           }

    }

    if($result){
        echo 'OK';
    }else{
        echo 'ERROR - event DB entry';
    }

}
exit();
?>