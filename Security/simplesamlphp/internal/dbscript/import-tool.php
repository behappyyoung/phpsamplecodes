<?php

// DEV server
	include('devConfig.php');	

	$r = mysql_query("SHOW DATABASES");
	$optionsdev='';
	while ($row = mysql_fetch_assoc($r)) {
		$optionsdev.='<option value="'.$row['Database'].'">'.$row['Database'].'</option>';
	}

// LIVE server
	include('livConfig.php');	

	$r = mysql_query("SHOW DATABASES");
	$optionsliv='';
	while ($row = mysql_fetch_assoc($r)) {
		$optionsliv.='<option value="'.$row['Database'].'">'.$row['Database'].'</option>';
	}

?>
<html>
<head>
<link href="css/import.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="assets/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="assets/style.css" />
<link rel="stylesheet" type="text/css" href="assets/prettify.css" />
<link rel="stylesheet" type="text/css" href="assets/jquery-ui.css" />
<script type="text/javascript" src="assets/jquery.js"></script>
<script type="text/javascript" src="assets/jquery-ui.min.js"></script>
<script type="text/javascript" src="assets/jquery.multiselect.js"></script>
<script type="text/javascript" src="assets/prettify.js"></script>
<script type="text/javascript">
$(function(){

	$(".multiselect").multiselect({
		selectedList: 10
	});
	
	$(".singleselect").multiselect({
		multiple: false,
		header: "Select an option",
		noneSelectedText: "Select an Option",
		selectedList: 1
	});
	
});
</script>
<!--<script src="js/jquery-1.9.1.min.js"></script>-->
<script src="js/import.js"></script>
</head>
<body>
<div id="dev">
	<div>Dev server</div>
	<div>
	<select name="dblist_dev" id="dblist_dev" onChange="loadtables(this, 'dev')" class="singleselect">
	<?php echo $optionsdev;?>
	</select>
	</div>
	<div id="tabledev">
	</div>
</div>
<div id="liv">
	<div>Live server</div>
	<div>
   <select name="dblist_liv" id="dblist_liv" onChange="loadtables(this, 'liv')" class="singleselect">
	<?php echo $optionsliv;?>
	</select>
	</div>
	<div id="tableliv">
	</div>
</div>
<div id="dboperation">
	<div>
		<img src="images/left-button.png"  onClick="chktables('right')"/>
		<!--<img src="images/right-button.png"  onClick="chktables('left')"/>-->
	</div>
</div>
<div id="resultMsg" ></div>
</body>
</html>
