<?php
session_start();
if($_GET['test']=='test'){
	$testmode=true;
}else{
	$testmode = false;	
}


require_once("../config.php");
$result = false;
 date_default_timezone_set('US/Eastern');
var_dump($_REQUEST);
if(isset($_REQUEST['member_id'])){
    $where = ' WHERE member_id= '.$_REQUEST['member_id'];
    
}else{
    $where = '';
}

if(isset($_REQUEST['date'])){
    if($where==''){
        $where =' WHERE ';
    }else{
        $where .=' AND ';
    }
    $inputdate = strtotime($_REQUEST['date'].' 00:00:00' );
    $daterange = strtotime('+1 days', $inputdate);
    
    $where .= ' ( entry_date BETWEEN "'.$inputdate.'"  AND "'.$daterange.'" )';
}

    $data_connection = new PDO('mysql:dbname='.$EXT_DB_NAME.';host='.$DATA_HOST, $DB_USERNAME, $DB_PASSWORD );
    $query = 'SELECT e.member_id, e.name,  e.entry_date, e.target_date,  pg.value, pg.id  FROM externaldata.genesant_activity_event e
JOIN mpwg.SHN_reporting_point_genesant pg ON e.name = pg.matching_event'.$where.' order by member_id, e.entry_date desc  ';
echo $query;    
    $statement = $data_connection->prepare($query);
    $statement->execute();    
//    $result = $statement->fetchAll();

echo '<br />';
        $mpwg_connection = new PDO('mysql:dbname='.$DB_NAME.';host='.$DB_HOST, $DB_USERNAME, $DB_PASSWORD );
        
         while ($row = $statement->fetch()) {
//            print $row[0] . "   " . $row[1] . "     "  .$row[2] ."   "  .date('Y-m-d',  $row[2]) ."      " .$row[3] .  " <br />";
    
            $query ='INSERT INTO SHN_reporting_memberpoint_genesant (member_id, point_id, date_earn, target_date,  points )
            VALUES ("'.$row[0].'","'.$row[5].'","'.date('Y-m-d',  $row[2]).'", "'.$row[3].'", "'.$row[4].'" )';
            echo $query.'<br />';
            $statement2 = $mpwg_connection->prepare($query);
            $statement2->execute();
            
          }
exit();
?>