<?php
$mpwgurl = (isset($_REQUEST['url']))? $_REQUEST['url'] : 'https://www.mypersonalwellnessguide.com';
$username = $_REQUEST['username'];
$clientid = $_REQUEST['clientid'];
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta charset="utf-8">
<meta name="viewport" content="width=1020">
<link rel="apple-touch-icon" href="<?=$mpwgurl?>/assets/mpw/images/icon_apple_iphone.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?=$mpwgurl?>/assets/mpw/images/icon_apple_ipad.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?=$mpwgurl?>/assets/mpw/images/icon_apple_iphone4.png">
<title>myPersonalWellnessGuide | Take control of your own health and well-being</title>
<meta name="Description" content="Personal Wellness Guide">
<meta name="Author" content="Self Health Network Development Team">
<meta name="Keywords" content="Personal Wellness Guide, Wellness Guide, Online Fitness">
<link href="<?=$mpwgurl?>/assets/mpw/styles/style.css" rel="stylesheet" type="text/css">
<link rel="Shortcut icon" href="<?=$mpwgurl?>/assets/mpw/images/favicon.png" type="image/png">
<!--[if lte IE 8]>
		<link href="<?=$mpwgurl?>/assets/mpw/styles/ie.css" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if IE]>
		<style>
			.TabView .Tabs a.Active::after { border-top-color:#4593be }
		 </style>
<![endif]-->
<script type="text/javascript"> 
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', 'UA-26477351-1']);
			_gaq.push(['_trackPageview']);			
			(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			})();			
</script>
</head>
<body id="braingame_page" class="braingame_page" data-status="logged_in">
				
<header id="header" >
<div class="container">
				<nav id="subnav"><span>Welcome Back <?=$username?></span> | 
						<a href="<?=$mpwgurl?>/account/">Account</a> | 
						<a href="<?=$mpwgurl?>/member/logout/">Logout</a>
				</nav>
				<nav id="nav">
<?php
$logo = false;
if($clientid !='' ){
$header = get_headers($mpwgurl.'/broker/images/'.$clientid.'.jpg');
        if(in_array('Content-Type: image/jpeg', $header)){
                 $logo = true;
	}
}

if($logo){		
				echo '<img src="'.$mpwgurl.'/broker/images/'.$clientid.'.jpg" alt="" style="position:absolute; top:-58px; left:10px;  padding:8px; max-height:80px;">
				      <img src="'.$mpwgurl.'/cobrand/mpw-logo.png" alt="" style="position:absolute; top:-68px; left:300px;  padding:8px;">';
}else{

				echo '<a id="logo" href="'.$mpwgurl.'/dashboard/" title="myPersonalWellnessGuide Logo"></a>';
} ?>
					
					<a href="<?=$mpwgurl?>/dashboard/" class="link_dashboard"><span title="Dashboard"></span></a>
					<a href="<?=$mpwgurl?>/life-skills/" class="link_life">Life Skills</a>
					<a href="<?=$mpwgurl?>/fitness/" class="link_fitness">Fitness &amp; Nutrition</a>
					<a href="<?=$mpwgurl?>/medical-records/" class="link_medical">Health Records</a>
					<a href="<?=$mpwgurl?>/health-overview/" class="link_resources">Health Resources</a>
					<a href="<?=$mpwgurl?>/mind-power/" class="link_navigator">Mind Power</a>
				</nav>


</div>
</header>
<div id="sublinks">
				<nav>
<a href="https://www.mybrainsolutions.com/MyBrain/Dashboard.aspx" target="_self" class="link_health_overview">Dashboard</a>
<a href="https://www.mybrainsolutions.com/Pages/TrainingThatsFun/Games.aspx" target="_self"> Games </a>
<a href="https://www.mybrainsolutions.com/Pages/TrainingThatsFun/GoMobile.aspx" target="_self" class="link_navigator">Go Mobile</a>
<a href="https://www.mybrainsolutions.com/Pages/TrainingThatsFun/Rewards.aspx"  target="_self" class="link_navigator">Rewards</a>
				</nav>
</div>

<section id="main">
<div class="third_party_iframe" id="braingame_iframe" style="text-align:center;width:100%;overflow-x:hidden;">




