<?php
if(!session_id()){session_start();}
 
echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>AJAX-ZOOM dynamic width and height liquid layout design javascript</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">
";
 
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
}

?>
<link rel="stylesheet" href="../axZm/axZm.css" type="text/css" media="screen">
<script type="text/javascript" src="../axZm/plugins/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>

<style type="text/css" media="screen"> 
	body {height: 100%; overflow: hidden;}
	html {font-family: Tahoma, Arial; font-size: 10pt; overflow: hidden;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}
	.zoomHorGalleryDescr{display: none;}
	
	#zoomContainer{
		background-color: #FFFFFF;
	}
	
	.zoomGalleryVerticalContainer{
		background-color: #FFFFFF;
	}
	
	.zFsO{
		background-color: #FFFFFF;
	}
	.zoomGalleryBox, .zoomGalleryBoxOver, .zoomGalleryBoxSelected{
		background-color: #BABABA;
		border-color: #808080;
		color: #FFFFFF;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		border-radius: 5px;
		-moz-box-shadow: 1px 1px 1px #CCCCCC;
		box-shadow: 1px 1px 1px #CCCCCC;
		-webkit-box-shadow: 1px 1px 1px #CCCCCC;

	}
	.zoomGalleryBoxOver{
		background-color: #BABABA;
		border-color: #808080;
		color: #FFFFFF;
	}
	
	.zoomMapHolder{
		border-color: #808080;
	}
	
	.zoomGalleryHorizontalContainer, .zoomGalleryHorizontalArrow{
		background-color: #FFFFFF;
	}
	#zoomGalHorDiv{
		background-color: #FFFFFF;
	}
	
	.zoomHorGalleryBox, .zoomHorGalleryBoxOver, .zoomHorGalleryBoxSelected{
		background-color: #BABABA;
		border-color: #808080;
		color: #FFFFFF;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		border-radius: 5px;
		-moz-box-shadow: 1px 1px 1px #CCCCCC;
		box-shadow: 1px 1px 1px #CCCCCC;
		-webkit-box-shadow: 1px 1px 1px #CCCCCC;
	}
	
	.zoomHorGalleryBoxOver{
		background-color: #BABABA;
		border-color: #808080;
		color: #FFFFFF;
	}
	
	.zoomFullGalleryBox, .zoomFullGalleryBoxOver, .zoomFullGalleryBoxSelected{
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		border-radius: 5px;
		-moz-box-shadow: 1px 1px 1px #111110;
		box-shadow: 1px 1px 1px #111110;
		-webkit-box-shadow: 1px 1px 1px #111110;	
	}
	
 
	#nav{
		float: left;
		width: 220px;
		background-color: #BABABA;
		border-right: #808080 solid 1px;
	}
	
	#content{
		height: 150px;
		float: right;
		background-color: #FFFFFF;
	}
	
	#footer{
		clear: both;
	}

</style>



<script type="text/javascript">
var adjustHeight = function(){
	var a = jQuery(window).height() - jQuery('#header').height() - jQuery('#footer').height() - 1;
	jQuery('#content').css({height: a, width: jQuery(window).width() - jQuery('#nav').width() - parseInt(jQuery('#nav').css('border-right-width')) - 1});
	jQuery('#nav').css('height', a);
}
jQuery(document).ready(function(){
	 adjustHeight();
	 jQuery(window).bind('resize', adjustHeight);
});
</script>
<?php

// not needed - this is just for code formating
echo "
<link href=\"../axZm/plugins/demo/syntaxhighlighter/styles/shCore.css\" type=\"text/css\" rel=\"stylesheet\" />
<link href=\"../axZm/plugins/demo/syntaxhighlighter/styles/shThemeCustom.css\" type=\"text/css\" rel=\"stylesheet\" />
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/src/shCore.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushJScript.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushPhp.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushCss.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushXml.js\"></script>
<script type=\"text/javascript\">
SyntaxHighlighter.all();
</script>
";
?>
 
</head>
<body>
 
 
	<div id="header"><?php include ('navi.php');?></div>
	<div id="nav">
		<div style="padding: 7px; color: #FFFFFF; font-size: 80%">
			An example of AJAX-ZOOM placed inside a container with dynamic width and height (depending on screen resolution and browser window size). 
			Resize the window to see the adjustments. Please note that the layout in this example is controlled by javascript (see "adjustHeight" function in the sourcecode). 
			If you have a fluid layout which adjusts instantly you do not need such a javascript controll.<br><br>
			Navigationbar is disabled. The vertical gallery can also be disabled or replaced with horizontal. 

		</div>
	</div>
	
	<div id="content">
		<div style="padding:20px; color: #000000; font-size: 16pt">Loading, please wait...</div>
	</div>
	
	<div id="footer"><?php include('footer.php');?></div> 

	<div id="naviReplacement1" style="text-align: left; width: 24px; position: absolute; display: none;">
	<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomIn({ajxTo: 750, pZoom: 50})" style="outline-style: none;"><img src="../axZm/icons/zoom_in1.png" border="0" ></a>
	</div>
	
	<div id="naviReplacement2" style="text-align: left; width: 24px; position: absolute; display: none;">
	<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomOut({ajxTo: 750, pZoom: 50})" style="outline-style: none;"><img src="../axZm/icons/zoom_out1.png" border="0"></a>
	</div>
	

	<?php
	
	// Define the images directly here
	// There can be just one or more...
	$zoomData = array();
 
	$zoomData[1]['p'] = '/pic/zoom/animals/'; // Path to image
	$zoomData[1]['f'] = 'test_animals1.png'; // Image filename

	$zoomData[2]['p'] = '/pic/zoom/animals/'; // Path to image
	$zoomData[2]['f'] = 'test_animals2.png'; // Image filename
	
	$zoomData[3]['p'] = '/pic/zoom/animals/'; // Path to image
	$zoomData[3]['f'] = 'test_animals3.png'; // Image filename

	$zoomData[4]['p'] = '/pic/zoom/animals/'; // Path to image
	$zoomData[4]['f'] = 'test_animals4.png'; // Image filename		
	
	$zoomData[5]['p'] = '/pic/zoom/boutique/'; // Path to image
	$zoomData[5]['f'] = 'test_boutique1.png'; // Image filename

	$zoomData[6]['p'] = '/pic/zoom/boutique/'; // Path to image
	$zoomData[6]['f'] = 'test_boutique2.png'; // Image filename
	
	$zoomData[7]['p'] = '/pic/zoom/boutique/'; // Path to image
	$zoomData[7]['f'] = 'test_boutique3.png'; // Image filename

	$zoomData[8]['p'] = '/pic/zoom/boutique/'; // Path to image
	$zoomData[8]['f'] = 'test_boutique4.png'; // Image filename		
	
	$zoomData[9]['p'] = '/pic/zoom/objects/'; // Path to image
	$zoomData[9]['f'] = 'test_objects1.png'; // Image filename	

	$zoomData[10]['p'] = '/pic/zoom/objects/'; // Path to image
	$zoomData[10]['f'] = 'test_objects2.png'; // Image filename	
	
	// Turn above array into string
	$_GET['zoomData'] = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), '+/=', '-_,');
	
	?>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		var ajaxZoom = {};
		
		ajaxZoom.path = '../axZm/';
		
		ajaxZoom.ajaxZoomCallbacks = {
			onStart: function(){

			},	
			onFullScreenStart: function(){
 
			},
			onFullScreenReady: function(){
				// Add custom buttons
				jQuery("#naviReplacement1").appendTo("#zoomLayer").css({
					display: "block",
					left: 10,
					top: 130,
					zIndex: 111
				});
				
				jQuery("#naviReplacement2").appendTo("#zoomLayer").css({
					display: "block",
					left: 10,
					top: 330,
					zIndex: 111
				});
			},
			onFullScreenResizeEnd: function(){

			}
		};
		
		// $_GET['zoomData'] is the stringified and packed php array, see above
		// $_GET['zoomData'] = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), '+/=', '-_,');
		// $zoomData can also contain just one image!
		// By example=23 some default settings from axZmConfig.inc.php are overriden in axZmConfigCustom.inc.php after
		// elseif ($_GET['example' == 23), for example $zoom['config']['fullScreenRel'] = 'content'; 
		// whereby content is the id of the div container where ajax-zoom needs to be fit into
		ajaxZoom.queryString = 'zoomData=<?php echo $_GET['zoomData']?>&example=23';

		// Using API jQuery.fn.axZm.openFullScreen
		jQuery.fn.axZm.openFullScreen(ajaxZoom.path, ajaxZoom.queryString, ajaxZoom.ajaxZoomCallbacks);
	});
	</script>

</body>
</html>