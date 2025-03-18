<?php
/**
* Plugin: jQuery AJAX-ZOOM, ecommerce helper file: zoomLoad360.php
* Copyright: Copyright (c) 2010 Vadim Jacobi
* License Agreement: http://www.ajax-zoom.com/index.php?cid=download
* Version: 3.3.0 Patch: 2011-09-16
* Date: 2011-08-03
* URL: http://www.ajax-zoom.com
* Description: jQuery AJAX-ZOOM plugin - adds zoom & pan functionality to images and image galleries with javascript & PHP
* Documentation: http://www.ajax-zoom.com/index.php?cid=docs
*/

if(!session_id()){session_start();}

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>AJAX-ZOOM 360</title>
<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\"> 
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">
";
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
} 

?>

<link rel="stylesheet" href="axZm.css" type="text/css" media="screen">
<style type="text/css" media="screen"> 
	body {height: 100%; overflow: hidden;}
	html {font-family: Tahoma, Arial; font-size: 10pt; overflow: hidden;}
	.zoomHorGalleryDescr{display: none;}
	
	#zoomContainer{
		background-color: #FFFFFF;
	}

	.zFsO{
		background-color: #FFFFFF;
	}
</style>
<script type="text/javascript" src="plugins/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="jquery.axZm.js"></script>
</head>
<body>

	<script type="text/javascript">
	jQuery(document).ready(function(){
		var ajaxZoom = {};
		
		ajaxZoom.path = '';
		
		ajaxZoom.ajaxZoomCallbacks = {
			onStart: function(){
			
			},	
			onFullScreenClose: function(){
 				setTimeout(function(){parent.closeAxZmFull360();}, 1000);
			}
		};
		 
		ajaxZoom.queryString = "example=<?php echo $_GET['example'];?>&3dDir=<?php echo urldecode($_GET['3dDir']);?>";
		 
		// Using API jQuery.fn.axZm.openFullScreen
		jQuery.fn.axZm.openFullScreen(ajaxZoom.path, ajaxZoom.queryString, ajaxZoom.ajaxZoomCallbacks);
	});
	</script>
</body>
</html>