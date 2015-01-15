<?php

	session_start();
	$testmode=TRUE;
	require_once('../config.php');	
	$connection = mysql_connect($DATA_HOST, DB_USERNAME, DB_PASSWORD);
	$DB = mysql_select_db($EXT_DB_NAME);
	
require_once('pclzip.lib.php');
$sFile='';
$sDir = '/storage/data/braingames';//__DIR__; # php 5.3, for php <5.3 use dirname(__FILE__);
$sFilePattern = '*.zip';
if($_REQUEST['zipdt'] && trim($_REQUEST['zipdt'])!='')
{
	$zipdt = trim($_REQUEST['zipdt']);
}
else
{				
	$zipdt=date('Y-m-d');
}

$select_logquery = "SELECT id from  braingame_daily_log where log_date='".$zipdt."'";
$res_log=mysql_query($select_logquery);
$cnt_log=mysql_num_rows($res_log);
if($cnt_log==0)
{

	$sSearch = 'SelfHealth_MBSDaily_'.$zipdt;
	
	$sRegExp = '/\b'.$sSearch.'\b/i';
	$sel_zipfile='';
	foreach (glob($sDir . DIRECTORY_SEPARATOR . $sFilePattern) as $sFile){
	
		foreach (file($sFile) as $nLineNumber => $sLine){
			$path_parts=pathinfo($sFile);
			$fname=$path_parts['filename'];
			if($sSearch==substr($fname,0,strlen($sSearch)))
			{
			$sel_zipfile=$sFile;
			break;
			}
		   /* if (preg_match($sRegExp, $sLine) == 1){
	
				printf('<br/>Word "%s" found in %s, line %d', $sSearch, $sFile, $nLineNumber);
			  // echo $sFile;
				//break;
	
			}*/ // if
	
		} // foreach
	
	} // foreach
	
	$sFile=$sel_zipfile;
	$dtarr=explode('_',$sFile);
	$currDt=$dtarr[2].' '.str_replace('-',':',$dtarr[3]);
	$currDt=str_replace('.zip','',$currDt);
	//$archive = new PclZip(dirname(__FILE__).'/SelfHealth_MBSDaily_2013-04-17_18-00-45.zip');
	//exit(0);
	$archive = new PclZip($sFile);
	if ($archive->extract(PCLZIP_OPT_PATH, '/storage/data/destination') == 0) {
		echo "\n error while extract";
	} else {
		echo "\n extract ok";
	}
	
	$directory = '/storage/data/destination';
	
	$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
	
	while($it->valid()) {
	
		if (!$it->isDot()) {
			$colArray=array();
			//echo 'SubPathName: ' . $it->getSubPathName() . "\n";
			$PathfileName=$it->getSubPathName();
			$PathfileName=str_replace('.csv','',$PathfileName);
			$PathfileArr=explode('_',$PathfileName);
			$PathName=$PathfileArr[count($PathfileArr)-1];
			$fpath=$it->key();
			if($PathName!='')
			{
			CSVImport("braingame_daily_".$PathName, $fpath, $currDt, $zipdt);
			}
			//echo 'SubPath:     ' . $it->getSubPath() . "\n";
			//echo 'Key:         ' . $it->key() . "\n\n\n";
		}
	
		$it->next();
	}

}


function CSVImport($table, $csv_fieldname='csv', $dumpdt, $logdt) {
    if($csv_fieldname=='csv') return;

    $handle = fopen($csv_fieldname,'r');
    if(!$handle) die('Cannot open uploaded file.');

    $row_count = 0;
    $fieldscol=array();

    $rows = array();

    //Read the file as csv
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        
		if($row_count == 0)
		{
			foreach($data as $key=>$value) {
				$data[$key] = "`" . addslashes($value) . "`";
			}
			$fieldscol[] = implode(",",$data).',`created_date`';
		}
		else
		{
			foreach($data as $key=>$value) {
				if($key==0)
				{
					//echo "key : ".$key;
					//echo "value : ".$value;
					$select_query = "SELECT loginId from  braingame_daily_reporting_data where UserId='".$value."' limit 1 ";
					$res=mysql_query($select_query);
					$cntrec=mysql_num_rows($res);
					if($cntrec>0)
					{
						$recs=mysql_fetch_array($res);
						//$value=$recs['loginId'];
					}
					else
					{
					//echo "INSERT INTO braingame_daily_reporting_data (UserId) VALUES ('".addslashes($value)."')";
						mysql_query("INSERT INTO braingame_daily_reporting_data (UserId) VALUES ('".addslashes($value)."')") or die(" - Error loginId ".$value." Info for braingame_daily_reporting_data Table");
						//$value=mysql_insert_id();
					}
				}
				$data[$key] = "'" . addslashes($value) . "'";
			}
			$rows[] = implode(",",$data).",'".$dumpdt."'";
		}
		$row_count++;
    }
	$table=strtolower(str_replace(' ','_',$table));
	$table=substr($table,0,40);
	$sql_query = "INSERT INTO ".$table." (". implode(',',$fieldscol) .") VALUES(";
    $sql_query .= implode("),(", $rows);
    $sql_query .= ")";
    fclose($handle);

    if(count($rows)) { //If some recores  were found,
        //Replace these line with what is appropriate for your DB abstraction layer
        //mysql_query("TRUNCATE TABLE $table") or die("MySQL Error: " . mysql_error()); //Delete the existing records
       // mysql_query($sql_query) or die("MySQL Error: " . mysql_error()); // and insert the new ones.
	//echo $sql_query;
		mysql_query($sql_query);
        print 'Successfully imported '.$row_count.' record(s)';
		
		$select_logquery = "SELECT id from  braingame_daily_log where log_date='".$logdt."'";
		$res_log=mysql_query($select_logquery);
		$cnt_log=@mysql_num_rows($res_log);
		if($cnt_log==0)
		{
			$sql_logquery = "INSERT INTO braingame_daily_log (log_date) VALUES('".$logdt."')";
			mysql_query($sql_logquery);
		}
		
    } else {
        print 'Cannot import data - no records found.';
    }
}
?>
