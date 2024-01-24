<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>iPad style 3D/360° html5 spin rotate and zoom with no navigationbar and custom buttons</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="imagetoolbar" content="no">

<meta property="og:type" content="article"/>
<meta property="og:title" content="iPad style 360° html5 spin rotate and zoom with no navigationbar and custom buttons"/>
<meta property="og:description" content="Nice looking 360° html5 spin rotate tool with custom buttons. Works very good on iPad."/>
<meta name="description" content="Nice looking 360° html5 spin rotate tool with custom buttons. Works very good on iPad." />
<meta property="og:image" content="http://www.ajax-zoom.com/pic/layout/image-zoom_28.jpg"/> 

<?php
// Set scale for iPhone and disable user scalability
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
} 
?>


<style type="text/css" media="screen"> 
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 35px 0px 15px 0px; font-size: 16pt;}
	p {text-align: justify; text-justify: newspaper;}
</style>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>


<!--  syntaxhighlighter is not needed, you can remove this block along with SyntaxHighlighter.all(); below -->
<link href="../axZm/plugins/demo/syntaxhighlighter/styles/shCore.css" type="text/css" rel="stylesheet" />
<link href="../axZm/plugins/demo/syntaxhighlighter/styles/shThemeCustom.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/src/shCore.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushJScript.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushPhp.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushCss.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushXml.js"></script>

</head>
<body>

<?php
// This include is just for the demo, you can remove it
include ('navi.php');
?>

<DIV style="width: 720px; margin: 0 auto;">
	<h2>iPad style 3D/360° rotate example with no navigationbar and custom buttons</h2>
	<DIV id='test' style='width: 720px; min-height: 480px; color: gray; border: #CCCCCC 1px solid'>
		<DIV style='margin: 0; padding-top: 175px; padding-left: 260px; min-height: 225px; background: url(../axZm/icons/ajax-loader-bar.gif) center center no-repeat;'>
		Loading, please wait...
		</DIV>
	</DIV>
	
	<DIV style="clear:both">
		<p>Yes, it is possible to achieve full control over spin, zoom and pan of a 3D/360° object with just two buttons. 
		In this example the navigation bar, as well as spin and zoom sliders are deaktivated. 
		Instead there are only two custom buttons placed directly over the player. 
		They are injected with JavaScript over callback function - see sourcecode.
		</p>
		<p>The example object loaded into the player on www.ajax-zoom.com is a multilevel (multirow) 3D one. 
		However it makes no difference to a regular 360° product spin with just one row.
		</p>
		<p>Many more examples and information on 360° spins can be found in 
		<a href="http://www.ajax-zoom.com/examples/example15.php">example15.php</a>. 
		In the <a href="http://www.ajax-zoom.com/index.php?cid=docs#VR_Object">docs</a> you will find more options to adjust 360 spin tool.
		</p>
	</DIV>

</DIV>


<!-- SyntaxHighlighter is not needed -->
<script type="text/javascript">
SyntaxHighlighter.all();
</script>

<!--  Init AJAX-ZOOM player -->
<script type="text/javascript">
// Create empty jQuery object
var ajaxZoom = {}; 

// Simple function to check the state of the player and highlight appropriate custom button
function highlightSwitchedButton(){
	if (jQuery.axZm.spinSwitched){
		jQuery('#customButtonSpin').attr('src', '../axZm/icons/button_iPad_spin_act.png');
		jQuery('#customButtonMove').attr('src', '../axZm/icons/button_iPad_move.png');
	}
	else if (jQuery.axZm.panSwitched){
		jQuery('#customButtonMove').attr('src', '../axZm/icons/button_iPad_move_act.png');
		jQuery('#customButtonSpin').attr('src', '../axZm/icons/button_iPad_spin.png');
	}
}

// Define callbacks, for complete list check the docs
ajaxZoom.opt = {
	onBeforeStart: function(){
		// Set backgrounf color, can also be done in css file
		jQuery('.zoomContainer').css({backgroundColor: '#FFFFFF'});		
	},
	onLoad: function(){ // onSpinPreloadEnd
		// Load image button for spin switch, position it, append to the player and bind API function to it
		jQuery('<img>').attr('id', 'customButtonSpin').attr('src', '../axZm/icons/button_iPad_spin.png').
			appendTo('#zoomLayer').css({
			position: 'absolute',
			left: 10,
			bottom: 10,
			zIndex: 55,
			cursor: 'pointer'
		}).bind('click', function(){jQuery.fn.axZm.switchSpin(), highlightSwitchedButton();});
			
		// Load image button for pan switch, position it, append to the player and bind API function to it
		jQuery('<img>').attr('id', 'customButtonMove').attr('src', '../axZm/icons/button_iPad_move.png').
			appendTo('#zoomLayer').css({
			position: 'absolute',
			left: 10+56*1,
			bottom: 10,
			zIndex: 55,
			cursor: 'pointer'
		}).bind('click', function(){jQuery.fn.axZm.switchPan(); highlightSwitchedButton();});

		highlightSwitchedButton();
		
		/* Some other possible buttons are deaktivated :-)
		// Load image button for zoomIn, position it, append to the player and bind API function to it
		jQuery('<img>').attr('src', '../axZm/icons/button_iPad_zoomIn.png').
			appendTo('#zoomLayer').css({
			position: 'absolute',
			left: 10+56*2,
			bottom: 10,
			zIndex: 55,
			cursor: 'pointer'
		}).bind('click', function(){jQuery.fn.axZm.zoomIn({ajxTo: 750})});
		
		// Load image button for zoomOut, position it, append to the player and bind API function to it
		jQuery('<img>').attr('src', '../axZm/icons/button_iPad_zoomOut.png').
			appendTo('#zoomLayer').css({
			position: 'absolute',
			left: 10+56*3,
			bottom: 10,
			zIndex: 55,
			cursor: 'pointer'
		}).bind('click', function(){jQuery.fn.axZm.zoomOut({ajxTo: 750})});		

		// Load image button for reset, position it, append to the player and bind API function to it
		jQuery('<img>').attr('src', '../axZm/icons/button_iPad_reload.png').
			appendTo('#zoomLayer').css({
			position: 'absolute',
			left: 10+56*4,
			bottom: 10,
			zIndex: 55,
			cursor: 'pointer'
		}).bind('click', function(){jQuery.fn.axZm.zoomReset()});
		*/
	},
	onCropEnd: function(){
		// if forceToPan option is true, it instantly switches to pan when reached 100% zoom
		// check if tool state has been changed and highlight custom button
		setTimeout(function(){highlightSwitchedButton();}, 500);
	}
};

// Define the path to the axZm folder, adjust the path if needed!
ajaxZoom.path = "../axZm/"; 

// Define your custom parameter query string
// example=spinIpad has many presets for 360 images
// 3dDir - best of all absolute path to the folder with 360/3D images
ajaxZoom.parameter = "example=spinIpad&3dDir=../pic/zoom3d/Uvex_Occhiali"; 

// The ID of the element where ajax-zoom has to be inserted into
ajaxZoom.divID = "test";
</script>

<!-- Include the loader file -->
<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>


<?php
// This include is just for the demo, you can remove it
include('footer.php');
?>
</body>
</html>