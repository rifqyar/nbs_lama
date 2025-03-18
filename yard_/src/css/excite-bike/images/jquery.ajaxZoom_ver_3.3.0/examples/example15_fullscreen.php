<?php
if(!session_id()){session_start();}


echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>3D Spin Rotate & Zoom Clean Example Fullscreen</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">

";
 
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
}
 
?>
<style type="text/css" media="screen"> 
	html {font-family: Tahoma, Arial; font-size: 10pt;}
</style>
<link rel="stylesheet" href="../axZm/axZm.css" type="text/css" media="screen">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>

</head>
<body>

<script type="text/javascript">
		jQuery(document).ready(function() {
			// IE Fix
			jQuery('body').css({
				height: jQuery(window).height()
			});
	
			var callbacks = {
				// Remove close buttons, can also be done in the config file!
				onLoad: function(){
					$.axZm.fullScreenExitText = false;
					jQuery('#zoomFullScreenButton').parent().remove();
					jQuery('#zoomCornerFsc').remove();
				}
			}
			jQuery.fn.axZm.openFullScreen('../axZm/', '3dDir=../pic/zoom3d/Uvex_Occhiali&example=17', callbacks);
		});
</script>


</body>
</html>