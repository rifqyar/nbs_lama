<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Minimalistic design HTML5 360°/3D spin, rotate, zoom, pan gallery</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="imagetoolbar" content="no">

<meta property="og:type" content="article"/>
<meta property="og:title" content="Minimalistic design HTML5 360°/3D spin, rotate, zoom, pan gallery"/>
<meta property="og:description" content="Display high resolution 360°/3D objects and plain 2D images in one jQuery HTML5 player for your product presentations."/>
<meta name="description" content="Display high resolution 360°/3D objects and plain 2D images in one jQuery HTML5 player for your product presentations." />
<meta property="og:image" content="http://www.ajax-zoom.com/pic/layout/image-zoom_29.jpg"/> 

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
	.customThumbOuter{
 		display: block; 
		width: 75px;
		height: 50px;
		border: #CCCCCC 1px solid;
		background-position: center center;
		background-repeat: no-repeat;
		margin-bottom: 5px;
		cursor: pointer;
		outline:none;
		-moz-border-radius: 4px;
		-webkit-border-radius: 4px;
		border-radius: 4px 4px 4px 4px;
		-khtml-border-radius: 4px;
 
	}
	.customThumbInner{
		position: relative;
		width: 75px;
		height: 50px;
		outline:none;
	}


</style>

<!-- AJAX-ZOOM css file -->
<link rel="stylesheet" type="text/css" href="../axZm/axZm.css" media="all" />

<!-- jQuery core -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<!-- AJAX-ZOOM main js file -->
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>
 
</head>
<body>

<?php
// This include is just for the demo, you can remove it
include ('navi.php');
?>

<DIV style="width: 740px; margin: 0 auto;">
	<h2>Minimalistic design, custom buttons, 3D/360 objects and plain 2D images in one custom gallery controlled with API</h2>
	
	<!-- Container where AJAX-ZOOM will be loaded into
	Everything inside is removed when AJAX-ZOOM is loaded
	-->	
	<DIV id='axZmPlayerContainer' class="shadow" style='width: 600px; min-height: 400px; color: gray; float: left'>
		<DIV style='margin: 0; padding-top: 175px; padding-left: 260px; min-height: 225px; background: url(../axZm/icons/ajax-loader-bar.gif) center center no-repeat;'>
		Loading, please wait...
		</DIV>
	</DIV>

	<!-- Display custom gallery right to the player 
	In fact it can be anywhere and look how ever
	The thumbs are generated and loaded dynamically, e.g. background-image: url(../axZm/zoomLoad.php?previewPic=nike_running_01.jpg&[...]
	$zoom['config']['allowDynamicThumbs'] in /axZm/zoomConfig.inc.php must be set to true
	Of course you could replace it with static images, although the results of this dynamic generation are cached in filesystem
	-->
	<DIV id='axZmNaviContainer' style='width: 110px; height: 400px; color: gray; float: left; margin-left: 15px; overflow: hidden; overflow-y: auto;'>
		<DIV class="customThumbOuter" style="background-image: url(../axZm/zoomLoad.php?previewPic=test_green_3d_image_001.png&previewDir=../pic/zoom3d/Uvex_Occhiali/&qual=90&width=75&height=50)">
			<DIV class="customThumbInner" onClick="jQuery.axZmSwitchImage('image360', false, '../pic/zoom3d/Uvex_Occhiali', true, null)">
				<img src="../axZm/icons/360_2.png" style="position: absolute; z-index: 1; top: -1px; right: -17px;">
			</DIV>
		</DIV>

		<a class="customThumbOuter" href="javascript: void(0)" onClick="jQuery.axZmSwitchImage('imageZoom', 'test_boutique1.png', '../pic/zoom/boutique/test_boutique1.png|../pic/zoom/boutique/test_boutique2.png|../pic/zoom/objects/test_objects1.png|../pic/zoom/objects/test_objects2.png')"
		style= "background-image: url(../axZm/zoomLoad.php?previewPic=test_boutique1.png&previewDir=../pic/zoom/boutique/&qual=90&width=75&height=50)">
		</a>
		<a class="customThumbOuter" href="javascript: void(0)" onClick="jQuery.axZmSwitchImage('imageZoom', 'test_boutique2.png', '../pic/zoom/boutique/test_boutique1.png|../pic/zoom/boutique/test_boutique2.png|../pic/zoom/objects/test_objects1.png|../pic/zoom/objects/test_objects2.png')" 
		style= "background-image: url(../axZm/zoomLoad.php?previewPic=test_boutique2.png&previewDir=../pic/zoom/boutique/&qual=90&width=75&height=50)">
		</a>
		<a class="customThumbOuter" href="javascript: void(0)" onClick="jQuery.axZmSwitchImage('imageZoom', 'test_objects1.png', '../pic/zoom/boutique/test_boutique1.png|../pic/zoom/boutique/test_boutique2.png|../pic/zoom/objects/test_objects1.png|../pic/zoom/objects/test_objects2.png')" 
		style= "background-image: url(../axZm/zoomLoad.php?previewPic=test_objects1.png&previewDir=../pic/zoom/objects/&qual=90&width=75&height=50)">
		</a>
		<a class="customThumbOuter" href="javascript: void(0)" onClick="jQuery.axZmSwitchImage('imageZoom', 'test_objects2.png', '../pic/zoom/boutique/test_boutique1.png|../pic/zoom/boutique/test_boutique2.png|../pic/zoom/objects/test_objects1.png|../pic/zoom/objects/test_objects2.png')" 
		style= "background-image: url(../axZm/zoomLoad.php?previewPic=test_objects2.png&previewDir=../pic/zoom/objects/&qual=90&width=75&height=50)">
		</a>
		
 		<!--
		<a href="javascript: void(0)" onClick="jQuery.axZmSwitchImage('image360', false, '../pic/zoom3d/Uvex_Occhiali', true, null)">360° image 1</a><br>
		<a href="javascript: void(0)" onClick="jQuery.axZmSwitchImage('imageZoom', 'test_boutique1.png', '../pic/zoom/boutique/test_boutique1.png|../pic/zoom/boutique/test_boutique2.png|../pic/zoom/objects/test_objects1.png|../pic/zoom/objects/test_objects2.png')">Plain 2D image 1</a><br>
		<a href="javascript: void(0)" onClick="jQuery.axZmSwitchImage('imageZoom', 'test_boutique2.png', '../pic/zoom/boutique/test_boutique1.png|../pic/zoom/boutique/test_boutique2.png|../pic/zoom/objects/test_objects1.png|../pic/zoom/objects/test_objects2.png')">Plain 2D image 2</a><br>
		<a href="javascript: void(0)" onClick="jQuery.axZmSwitchImage('imageZoom', 'test_objects1.png', '../pic/zoom/boutique/test_boutique1.png|../pic/zoom/boutique/test_boutique2.png|../pic/zoom/objects/test_objects1.png|../pic/zoom/objects/test_objects2.png')">Plain 2D image 3</a><br>
		<a href="javascript: void(0)" onClick="jQuery.axZmSwitchImage('imageZoom', 'test_objects2.png', '../pic/zoom/boutique/test_boutique1.png|../pic/zoom/boutique/test_boutique2.png|../pic/zoom/objects/test_objects1.png|../pic/zoom/objects/test_objects2.png')">Plain 2D image 4</a><br>
		-->
		 
	</DIV>
	
	<!-- Bla, bla-->
	<DIV style="clear:both;">
 		<p style="padding-top: 20px">It has been often asked how to manage 360/3D product spins and plain 2D images in one gallery. 
		The vertical and horizontal galleries which are integrated into AJAX-ZOOM do not support it. 
		However it is possible to make a custom HTML gallery with both - 360 and 2D images and control the player 
		over API functions. In this example we have made a custom function jQuery.axZmSwitchImage() including some 
		additional logic - when switching between regular 2D images and 360 object it is necessary (or more easy) 
		to reload the player in the background. Thus the javascript function jQuery.axZmSwitchImage() handels the task. 
		You will find the code in the source of this file. Edit, adjust the function as needed - it is commented. 
		Please note: some default settings from /axZm/zoomConfig.inc.php are overridden in /axZm/zoomConfigCustom.inc.php after 
		<code>elseif ($_GET['example'] == 'spinAnd2D'){</code>
		So if changes in /axZm/zoomConfig.inc.php have no effect look for the same options /axZm/zoomConfigCustom.inc.php; 
		</p>
		
		<p>Of course this "gallery" can be used with only 2D images or only 3D. 
		So in case you don't have a 360 photography for a product you can still use this example.
		</p>
		
		<p>Last but not least: there is no PHP needed to run it in your actual application. 
		An other words you can use it e.g. with Phalanger in ASP.NET environment.
		</p>
	</DIV>

</DIV>


<!--  Init AJAX-ZOOM player -->
<script type="text/javascript">

jQuery(document).ready(function() {
	// Manually trigger loading the first item of custom thumbs
	// jQuery.axZmSwitchImage('image360', false, '../pic/zoom3d/Nike_Running', true, null);
	
	// Instantly trigger loading the first item of custom thumbs by geting it's click event
	jQuery(".customThumbInner").first().click();
	
	// Add mouseover for custom thumbs
	/*
	jQuery(".customThumbOuter").bind('mouseover', function(){
		jQuery(this).css('borderColor', '#323232');
	}).bind('mouseout', function(){
		jQuery(this).css('borderColor', '#cccccc');
	});
	*/
});


/**
  * Custom function to load AJAX-ZOOM player, switch images / 3d / 360 objects. Edit it to suit your needs.
  * @param string mode - possible values 'image360' and 'imageZoom'
  * @param string lnk - first selected image if mode is 'imageZoom'
  * @param string zData - query string - images to load or path to 360 folder
  * @param mixed spinReverse - reverse spin direction x axis, overrides $zoom['config']['spinReverse'] in zoomConfig.inc.php / zoomConfigCustom.inc.php
  * @param mixed spinReverseZ - reverse spin direction y axis (multirow), overrides $zoom['config']['spinReverseZ'] in zoomConfig.inc.php / zoomConfigCustom.inc.php
  **/	

jQuery.axZmSwitchImage = function(mode, lnk, zData, spinReverse, spinReverseZ){
	
	// Create empty jQuery object
	var ajaxZoom = {}; 

	// The ID of the element where ajax-zoom has to be inserted into
	ajaxZoom.divID = "axZmPlayerContainer";

	// Define the path to the axZm folder, adjust the path if needed!
	ajaxZoom.path = "../axZm/";
	
	// Define your custom parameter query string
	// example=spinAnd2D has many presets for 360 images
	// 3dDir - best of all absolute path to the folder with 360/3D images
	ajaxZoom.parameter = "example=spinAnd2D&"+((mode == 'image360') ? '3dDir=' : 'zoomData=')+zData;
	
	// Add specific parameters for 360 images
	// Find it in zoomConfigCustom.inc.php after elseif ($_GET['example'] == 'spinAnd2D'){ and then if (isset($_GET['image360'])){
	if (mode == 'image360'){
		ajaxZoom.parameter += '&image360=1';
	}
	
	// Add parameter for which image file should be loaded first
	if (lnk){
		ajaxZoom.parameter += '&zoomFile='+lnk;
	}
	
	// Stop 360 rotating if needed
	if (jQuery.axZmCurrentZoomMod){
		jQuery.fn.axZm.spinStop();
	}
	
	// Same mode as previous 360 object / 2D image
	if (jQuery.axZmCurrentZoomMod == mode){
		
		// Switch between images in the gallery
		if (mode == 'imageZoom'){
			jQuery.fn.axZm.zoomSwitch(lnk);
		}
		
		// Load a new 360 set
		else if (mode == 'image360'){
			jQuery.fn.axZm.loadAjaxSet(ajaxZoom.parameter);
			
			if (spinReverse === true || spinReverse === false){
				$.axZm.spinReverse = spinReverse;
			}
		
			if (spinReverseZ === true || spinReverseZ === false){
				setTimeout(function(){$.axZm.spinReverseZ = spinReverseZ;}, 2000);
			}
		}
		
	}
	
	// if previous mode is different or not present, reload / load the player
	else{

		// Save current mode
		jQuery.axZmCurrentZoomMod = mode; // imageZoom / image360

		// Define callbacks if needed, for complete list check the docs
		ajaxZoom.opt = {
			onBeforeStart: function(){
				// Set background color, can also be done in css file, not necessary
				jQuery('.zoomContainer').css({backgroundColor: '#FFFFFF'});		
			},
			onLoad: function(){ // onSpinPreloadEnd
				// Set border color of image map selector, can also be done in css file, not necessary
				jQuery('.zoomMapSel').css('border-color', '#0083DE');
				jQuery('.zoomMapSelArea').css('backgroundColor', '#0083DE');
				
				// Set mouse spin direction on X-axis, not necessary
				if (spinReverse === true || spinReverse === false){
					$.axZm.spinReverse = spinReverse;
				}
				
				// Set mouse spin direction on Y-axis, not necessary
				if (spinReverseZ === true || spinReverseZ === false){
					$.axZm.spinReverseZ = spinReverseZ;
				}
				
				// Custom navigation buttons for 360/3D spins
				if (mode == 'image360'){
					// Load image button for fullscreen, position it, append to the player and bind custom function to it
					/*
					jQuery('<img>').attr('id', 'customButtonFullscreen').attr('src',  ajaxZoom.path+ 'icons/button_iPad_fullscreen_open.png').
						appendTo('#zoomLayer').css({
						position: 'absolute',
						left: 10+56*0,
						bottom: 10,
						zIndex: 55,
						cursor: 'pointer'
					}).bind('click', function(){fullScreenToggle();});
					*/
					
					// Load image button for spin switch, position it, append to the player and bind API function to it
					jQuery('<img>').attr('id', 'customButtonSpin').attr('src', ajaxZoom.path+ 'icons/button_iPad_spin.png').
						appendTo('#zoomLayer').css({
						position: 'absolute',
						left: 10,
						bottom: 10+56*1,
						zIndex: 55,
						cursor: 'pointer'
					}).bind('click', function(){jQuery.fn.axZm.switchSpin(), highlightSwitchedButton();});
						
					// Load image button for pan switch, position it, append to the player and bind API function to it
					jQuery('<img>').attr('id', 'customButtonMove').attr('src',  ajaxZoom.path+ 'icons/button_iPad_move.png').
						appendTo('#zoomLayer').css({
						position: 'absolute',
						left: 10+56*0,
						bottom: 10,
						zIndex: 55,
						cursor: 'pointer'
					}).bind('click', function(){jQuery.fn.axZm.switchPan(); highlightSwitchedButton();});


					
					
					highlightSwitchedButton();
				}
				
				else if (mode == 'imageZoom'){
					// Load image button for reset, position it, append to the player and bind API function to it
					jQuery('<img>').attr('src', '../axZm/icons/button_iPad_reload.png').
						appendTo('#zoomLayer').css({
						position: 'absolute',
						left: 10+56*0,
						bottom: 10,
						zIndex: 55,
						cursor: 'pointer'
					}).bind('click', function(){jQuery.fn.axZm.zoomReset()});
					
					/* Some other possible buttons are deaktivated :-)
					// Load image button for zoomIn, position it, append to the player and bind API function to it
					jQuery('<img>').attr('src', '../axZm/icons/button_iPad_zoomIn.png').
						appendTo('#zoomLayer').css({
						position: 'absolute',
						left: 10+56*1,
						bottom: 10,
						zIndex: 55,
						cursor: 'pointer'
					}).bind('click', function(){jQuery.fn.axZm.zoomIn({ajxTo: 750})});
					
					// Load image button for zoomOut, position it, append to the player and bind API function to it
					jQuery('<img>').attr('src', '../axZm/icons/button_iPad_zoomOut.png').
						appendTo('#zoomLayer').css({
						position: 'absolute',
						left: 10+56*2,
						bottom: 10,
						zIndex: 55,
						cursor: 'pointer'
					}).bind('click', function(){jQuery.fn.axZm.zoomOut({ajxTo: 750})});		
			

					*/
				}
			},
			onSpinPreloadEnd: function(){
				// Manage spin/pan buttons
				highlightSwitchedButton();
			},
			onCropEnd: function(){
				// if forceToPan option is true, it instantly switches to pan when reached 100% zoom
				// check if tool state has been changed and highlight custom button
				setTimeout(function(){highlightSwitchedButton();}, 500);
			}
		};
	
		// Function to check the state of the 360 player and highlight appropriate custom button
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
		
		function fullScreenToggle(){
			if (jQuery.axZm.fsi){
				jQuery.fn.axZm.closeFullScreen(false);
				setTimeout(function(){
					if (!jQuery.axZm.fsi){
						jQuery('#customButtonFullscreen').attr('src', ajaxZoom.path+ 'icons/button_iPad_fullscreen_open.png')
					}
				}, 200);
			}else{
				jQuery.fn.axZm.initFullScreen(false);
				setTimeout(function(){if (jQuery.axZm.fsi){
						jQuery('#customButtonFullscreen').attr('src', ajaxZoom.path+ 'icons/button_iPad_fullscreen_close.png')
					}
				}, 200);
			}
		}
		
		// Load / Reload AJAX-ZOOM
		function ajaxZoomLoad(){
			var url = ajaxZoom.path + 'zoomLoad.php';
			var parameter = 'zoomLoadAjax=1&'+ajaxZoom.parameter;
			 
			jQuery.ajax({
				url: url,
				data: parameter, // important
				dataType: 'html',
				cache: false,
				success: function (data){
					if (jQuery.isFunction(jQuery.fn.axZm) && data){
						jQuery('#'+ajaxZoom.divID).html(data);
					}
				},
				complete: function () {
					if (jQuery.isFunction(jQuery.fn.axZm)){
						jQuery.fn.axZm(ajaxZoom.opt);
					}
				}
			});
		}
		
		ajaxZoomLoad();
	}
}
</script>



<?php
// This include is just for the demo, you can remove it
include('footer.php');
?>
</body>
</html>