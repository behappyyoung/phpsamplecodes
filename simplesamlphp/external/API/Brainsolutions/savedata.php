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
				//if((stripos($PathfileName, 'SelfHealth_WebNeuro_Data') !== false)||(stripos($PathfileName, 'Solution Session') !== false)||(stripos($PathfileName, 'MBS User') !== false)){
				if((stripos($PathfileName, 'SelfHealth_WebNeuro_Data') !== false)){
					SaveIntoDB($PathfileName);
				}
		}
	
		$it->next();
	}


function SaveIntoDB($filename){
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
	    $delquery = "TRUNCATE TABLE $tablefileName" ;
	    $result = $connection->exec($delquery);
	    $addquery='';
	    $first_row = true;
	    while (($data = fgetcsv($fhandle, 0, ",")) !== FALSE) {
		
		if($first_row)
		{
			foreach($data as $key=>$value) {
				$data[$key] = "`" . addslashes($value) . "`";
			}
			$fieldscol = implode(",",$data).',`created_date`';
			$first_row = false;
		}
		else{
		
			//var_dump($data);
			foreach($data as $key=>$value) {
				$data[$key] = "'" . addslashes($value) . "'";
			}
			$rowdata = implode(",",$data).', \''.date('Y-m-d').'\'';
//			$query = 'SELECT * FROM '.$tablefileName.' WHERE Login= "'.$data['0'].'" AND SolutionSessionId= "'.$data['1'].'"' ;
//			echo $query;
//			$excount = $connection->exec($query);
//			if($excount > 0){
//				echo 'exist';
//			}else{
				$addquery .= 'INSERT INTO '.$tablefileName.' ('.$fieldscol.') VALUES ('.$rowdata.');';	
//			}
		}
	    }
echo $addquery;	    
try{
	$result = $connection->exec($addquery);
	echo $result.'here';
}catch(PDOException $e){
	print_r($e);
	echo 'error';
}
	

}

?>
