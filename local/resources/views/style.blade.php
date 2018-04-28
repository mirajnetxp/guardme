<?php
/*
Theme Name: thumbstack
Theme URI: http://migrateshop.com
Author: Migrate Shop Team
Author URI: http://sangvish.com
Description: Multi vendor marketplace
Version: 1.0
Text Domain: sangvish-tn
*/
?>
 <?php 
 use Illuminate\Support\Facades\Route;
$currentPaths= Route::getFacadeRoot()->current()->uri();
 $url = URL::to("/"); 
 $setid=1;
		$setts = DB::table('settings')
		->where('id', '=', $setid)
		->get();
		$name = Route::currentRouteName();
 if($currentPaths=="/")
 {
	 $pagetitle="Home";
 }
 else 
 {
	 $pagetitle=$currentPaths;
 }
 ?>
 
 <title><?php echo $setts[0]->site_name;?> - <?php echo $pagetitle;?></title>
	 <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	 <!-- css stylesheets -->
	 <?php if(!empty($setts[0]->site_favicon)){?>
	 <link rel="icon" type="image/x-icon" href="<?php echo $url.'/local/images/settings/'.$setts[0]->site_favicon;?>" />
	 <?php } else { ?>
	 <link rel="icon" type="image/x-icon" href="<?php echo $url.'/local/images/noimage.jpg';?>" />
	 <?php } ?>

	<!-- CSS -->
    <link rel="stylesheet" href="<?php echo $url;?>/css/bootstrap.min.css" >
    <link rel="stylesheet" href="<?php echo $url;?>/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo $url;?>/css/icofont.css"> 
    <link rel="stylesheet" href="<?php echo $url;?>/css/slidr.css">     
    <link rel="stylesheet" href="<?php echo $url;?>/css/main.css">  
	<link id="preset" rel="stylesheet" href="<?php echo $url;?>/css/presets/preset1.css">	
    <link rel="stylesheet" href="<?php echo $url;?>/css/responsive.css">
	<!-- CSS -->
	<!--old required css -->
		<link href="<?php echo $url;?>/css/flexslider.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="<?php echo $url;?>/css/validationEngine.jquery.css" type="text/css"/>
		<link href="<?php echo $url;?>/css/autocomplete.css" rel="stylesheet" type="text/css">
		<link href="<?php echo $url;?>/css/jquery.multiselect.css" rel="stylesheet" type="text/css">
		<link href="<?php echo $url;?>/css/lightbox.min.css" rel="stylesheet" type="text/css">
		<link href="<?php echo $url;?>/css/jquery-ui.css" rel="stylesheet" type="text/css">


		<link href="<?php echo $url;?>/css/custom.css" rel="stylesheet" type="text/css">
	<!--old required css -->
	
	<!-- font -->
		<link href='https://fonts.googleapis.com/css?family=Ubuntu:400,500,700,300' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Signika+Negative:400,300,600,700' rel='stylesheet' type='text/css'>
	<!-- font -->
	 

	<!-- JS -->
    <script src="<?php echo $url;?>/js/jquery.min.js"></script>
    <script src="<?php echo $url;?>/js/bootstrap.min.js"></script>
    <script src="<?php echo $url;?>/js/price-range.js"></script>   
    <script src="<?php echo $url;?>/js/main.js"></script>
	<script src="<?php echo $url;?>/js/switcher.js"></script>
	
	
	
	
	
	<script src="<?php echo $url;?>/js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>
	<script src="<?php echo $url;?>/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
	<script>
		jQuery(document).ready(function(){
			jQuery("#formID").validationEngine({showOneMessage:true,promptPosition : "topLeft", scroll: false});
		});

		jQuery(document).ready(function(){
			jQuery("#formID2").validationEngine({showOneMessage:true,promptPosition : "topLeft", scroll: false});
		});
	</script>
	
	

	
	
	
	

	
	