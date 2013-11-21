<?php

	session_start();
	$testmode=TRUE;
	require_once('../configbrain.php');	
	require_once('pclzip.lib.php');



if($_REQUEST['zipdate'] && trim($_REQUEST['zipdate'])!='')
{
	$zipdate = trim($_REQUEST['zipdate']);
}
else
{				
	$zipdate=date('Y-m-d' );
}

	$sFile = glob(DATA_DIR . '*{'.$zipdate.'}*.zip', GLOB_BRACE);

	$filename = $sFile[0];
	$destination = EXTRACT_DIR.$zipdate.'/';
/*
	$archive = new PclZip($filename);
	
	if ($archive->extract($destination) == 0) {
		echo "\n error while extract";
	} else {
		echo "\n extract ok";
	}
*/

	$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($destination));
	
	
	while($it->valid()) {
		
		if (!$it->isDot()) {
			$colArray=array();
			$PathfileName=$it->getSubPathName();
			

			//echo $PathfileName.'--'.$newfileName.'<br />';
			// general save
				if($PathfileName!='')
				{
				//CSVImport("braingame_daily_".$PathName, $fpath, $currDt, $zipdate);
				}
			// selected data
				if((stripos($PathfileName, 'SelfHealth_WebNeuro_Data') !== false)){
					createTable($PathfileName);
				}
		}
	
		$it->next();
	}


function createTable($filename){
	global $destination;
	global $zipdate;
echo DB_DATABASE.DB_HOST. DB_USERNAME. DB_PASSWORD	;
	$connection = new PDO('mysql:dbname='.DB_DATABASE.';host='.DB_HOST, DB_USERNAME, DB_PASSWORD );
echo $destination.$filename;
	$fhandle = fopen($destination.$filename,'r');
	if(!$fhandle) die('Cannot open uploaded file.');
	    $tablefileName=str_replace('.csv','',$filename);
	    $pattern = '/_'.$zipdate.'_..-..-../';
	    $tablefileName=preg_replace($pattern,'',$tablefileName);
	    $tablefileName=str_replace(' ','_',$tablefileName);
	    $first_row = true;
	    $query = 'CREATE TABLE `SelfHealth_WebNeuro_Data` (`id` int(11) NOT NULL auto_increment, ';
	    while (($data = fgetcsv($fhandle, 0, ",")) !== FALSE) {
		
		if($first_row)
		{
			foreach($data as $key=>$value) {
				$query .= '`'.addslashes($value) . '` varchar(20) default NULL, ';
			}
			$query .= ' `created_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`));';
			$first_row = false;
			break;
		}
	    }
echo $query;	  
	    
try{
	$result = $connection->exec($query);
	echo $result.'here';
}catch(PDOException $e){
	print_r($e);
	echo 'error';
}
	

}

?>
