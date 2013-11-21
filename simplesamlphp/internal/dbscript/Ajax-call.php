<?php

$process='';
$servType='';
$dbname='';
$livdbname='';
$devTbl='';
$livTbl='';

if(isset($_REQUEST['process']) && trim($_REQUEST['process'])!='')
{
	$process=trim($_REQUEST['process']);	
}
if(isset($_REQUEST['servType']) && trim($_REQUEST['servType'])!='')
{
	$servType=trim($_REQUEST['servType']);	
}
if(isset($_REQUEST['dbname']) && trim($_REQUEST['dbname'])!='')
{
	$dbname=trim($_REQUEST['dbname']);	
}
if(isset($_REQUEST['livdbname']) && trim($_REQUEST['livdbname'])!='')
{
	$livdbname=trim($_REQUEST['livdbname']);	
}
if(isset($_REQUEST['devTbl']) && trim($_REQUEST['devTbl'])!='')
{
	$devTbl=trim($_REQUEST['devTbl']);	
}
if(isset($_REQUEST['livTbl']) && trim($_REQUEST['livTbl'])!='')
{
	$livTbl=trim($_REQUEST['livTbl']);	
}
switch ($process)
{
  case "showtables":
  showtables($dbname,$servType);
  break;
  case "chktables":
  chktables($dbname, $livdbname,$devTbl, $livTbl);
  break;
}

function showtables($dbname, $servType)
{
 	include($servType.'Config.php');
	$DB = mysql_select_db($dbname);
	$r = mysql_query("SHOW TABLES");
	$output='';
	while ($row = mysql_fetch_array($r)) {
		$output.='<option value="'.$row[0].'">'.$row[0].'</option>';
	}
	if(trim($output)!='')
	{
		$output='tables:<br/><select name="tablelist_'.$servType.'" id="tablelist_'.$servType.'" size="10" multiple="multiple" class="multiselect">'.$output.'</select>';
	}
	echo $output;
}


function chktables($devdbname, $livdbname,$devTbl, $livTbl)
{
 	$res_output='';
	$res_error='';
	
	include('devConfig.php');
	$devoutput='';
	$output='';
	
	$DB = mysql_select_db($devdbname);
	$q = mysql_query('DESCRIBE '.$devTbl);
	while($row = mysql_fetch_array($q)) {
		$devoutput.="{$row['Field']} - {$row['Type']} ";
	}
	
	
	include('livConfig.php');
	$livoutput='';
	$DB = mysql_select_db($livdbname);
	$q = mysql_query('DESCRIBE '.$livTbl);
	while($row = mysql_fetch_array($q)) {
		$livoutput.="{$row['Field']} - {$row['Type']} ";
	}
	
	if(trim($devoutput)==$livoutput)
	{
		$res_output.=$devTbl.' - '.$livTbl.'\n';
	}
	else
	{
		$res_error.=$devTbl.' - '.$livTbl.'\n';
	}
	
	
	if(trim($res_output)!='')
	{
		$output.='<div>Matched tables <br/>'.$res_output.'</div>';
	}
	if(trim($res_error)!='')
	{
		$output.='<div>Not Matched tables <br/>'.$res_error.'</div>';
	}

	echo $output;
}

?>


   
