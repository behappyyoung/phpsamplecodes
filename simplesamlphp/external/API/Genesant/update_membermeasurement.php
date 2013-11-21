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
    $query = 'SELECT member_id, name,  value  FROM externaldata.genesant_activity_measurement'.$where;
    
echo $query;    
    $statement = $data_connection->prepare($query);
    $statement->execute();    
//    $result = $statement->fetchAll();

echo '<br />';
        $mpwg_connection = new PDO('mysql:dbname='.$DB_NAME.';host='.$DB_HOST, $DB_USERNAME, $DB_PASSWORD );
        
         while ($row = $statement->fetch()) {
//            print $row[0] . "   " . $row[1] . "     "  .$row[2] ."   "  .date('Y-m-d',  $row[2]) ."      " .$row[3] .  " <br />";
            $query = 'SELECT * FROM SHN_member_info WHERE member_id="'.$row['member_id'].'"';
            $statement2 = $mpwg_connection->prepare($query);
            $statement2->execute();
            
            if($statement2->rowCount() > 0){
                $query ='UPDATE SHN_member_info SET '.$row['name'].'="'.$row['value'].'" WHERE member_id="'.$row['member_id'].'"';
            }else{
                $query ='INSERT INTO SHN_member_info (member_id, '.$row['name'].' )
                VALUES ("'.$row[0].'","'.$row['value'].'")';
            }
                echo $query.'<br />';            
                $statement2 = $mpwg_connection->prepare($query);
                $statement2->execute();            
            
          }
exit();
?>