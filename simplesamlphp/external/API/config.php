<?php
error_reporting(1);
				//	DB Connection
		
if( $testmode ){
		define('DB_HOST', 'localhost');
		define('DB_USERNAME', 'young');
		define('DB_PASSWORD', 'Team2012mysql');		
		define('DB_DATABASE', 'mpwg');
		
		define('DATA_HOST', 'localhost');
		define('DEV_DATA_HOST', 'localhost');
		define('DATA_DATABASE', 'externaldata');
		
}else{
		define('DB_HOST', '50.56.42.37');
		define('DB_USERNAME', 'jboyer');
		define('DB_PASSWORD', '!L3tm3in!');		
		define('DB_DATABASE', 'mpwg');
		
		define('DATA_HOST', '50.56.42.37');
		define('DEV_DATA_HOST', 'localhost');
		define('DATA_DATABASE', 'externaldata');
}




$DB_HOST = DB_HOST;
$DB_USERNAME = DB_USERNAME;
$DB_PASSWORD = DB_PASSWORD;
$DB_NAME = DB_DATABASE;

$DATA_HOST = DATA_HOST;
$EXT_DB_NAME = DATA_DATABASE;
$DEV_DATA_HOST = DEV_DATA_HOST;

?>
