<?php
error_reporting(1);
				//	DB Connection
		
if( $testmode ){
		define('DB_HOST', 'localhost');
		define('DB_USERNAME', 'young');
		define('DB_PASSWORD', 'Team2012mysql');		
		define('DB_DATABASE', 'externaldata');
		
		define('DATA_DATABASE', 'braingames_data');
		
}else{
		define('DB_HOST', '50.56.42.37');
		define('DB_USERNAME', 'jboyer');
		define('DB_PASSWORD', '!L3tm3in!');	
		define('DB_DATABASE', 'mpwg');
		define('DB_DATABASE', 'externaldata');
		
		define('DATA_DATABASE', 'braingames_data');
		
}

$DB_HOST = DB_HOST;
$DB_USERNAME = DB_USERNAME;
$DB_PASSWORD = DB_PASSWORD;
$DB_NAME = DB_DATABASE;

		define('DATA_DIR', '/storage/data/braingames/');
		define('EXTRACT_DIR', DATA_DIR.'extracts/');
		

?>
