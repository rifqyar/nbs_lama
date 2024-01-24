<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Fullscreen image zoom gallery</title>
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
	body {height: 100%; overflow: hidden;}
	html {overflow: hidden;}
</style>

<link rel="stylesheet" href="../axZm/axZm.css" type="text/css" media="screen">
<script type="text/javascript" src="../axZm/plugins/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>
 
</head>
<body>
	<?php
	$zoomData = array();
	$zoomData[1]['p'] = '/pic/zoom/high_res/'; // Absolute paths
	$zoomData[1]['f'] = 'test_high_res2.png'; // Image Name	
	$_GET['zoomData'] = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), '+/=', '-_,');
	?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			// IE Fix
			jQuery('body').css({
				height: jQuery(window).height()
			});
	
			var callbacks = {
				onLoad: function(){
					jQuery('#header').appendTo('#zoomLayer').css({
						display: 'block',
						top: 0,
						left: ($(window).width()-190)/2
					});
					
				},
				onFullScreenResizeEnd: function(){
					jQuery('#header').css({
						left: ($(window).width()-190)/2
					});				
				}
			}
			jQuery.fn.axZm.openFullScreen('../axZm/', 'zoomData=<?php echo $_GET['zoomData'];?>&example=24', callbacks);
		});
	</script>
	
<div id="header" style="width: 260px; display: none; z-index: 3; position: absolute;"><?php include ('navi.php');?></div>

</body>
</html>
 