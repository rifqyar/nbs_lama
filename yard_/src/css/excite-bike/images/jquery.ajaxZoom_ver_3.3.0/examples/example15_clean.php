<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>3D Spin Rotate & Zoom Clean Example</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="imagetoolbar" content="no">


<?php
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
} 
?>


<style type="text/css" media="screen"> 
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}
	p {text-align: justify; text-justify: newspaper;}
</style>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>

</head>
<body>


<DIV id='test' style='width: 602px; min-height: 400px; color: gray;'>
	<DIV style='margin: 0; padding-top: 175px; padding-left: 200px; min-height: 225px; background: url(../axZm/icons/ajax-loader-bar.gif) center center no-repeat;'>
	Loading, please wait...
	</DIV>
</DIV>

<DIV style="clear:both">
Clean 360 example, no PHP in this file, see sourcecode.
</DIV>

<script type="text/javascript">
// Create new object
var ajaxZoom = {}; 

// Callbacks
ajaxZoom.opt = {
	onBeforeStart: function(){
		jQuery('.zoomContainer').css({backgroundColor: '#FFFFFF'});
		jQuery('.zoomLogHolder').css({width: 70});			
	}
};

// Define the path to the axZm folder
ajaxZoom.path = "../axZm/"; 

// Define your custom parameter query string
ajaxZoom.parameter = "example=17&3dDir=../pic/zoom3d/Uvex_Occhiali"; 

// The ID of the element where ajax-zoom has to be inserted into
ajaxZoom.divID = "test";
</script>

<!-- Include the loader file -->
<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>



</body>
</html>