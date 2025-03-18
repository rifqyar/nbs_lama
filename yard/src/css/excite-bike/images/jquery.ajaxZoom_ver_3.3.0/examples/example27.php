<?php
if(!session_id()){session_start();}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Javascript Image Zoom Fullscreen in jQuery Fancybox fluid design</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="imagetoolbar" content="no">

 
<?php
// Set scale for iPhone and disable user scalability
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
} 
?>

<!--  Include AJAX-ZOOM css, adjust the path if needed. Best set absolute path -->
<link rel="stylesheet" href="../axZm/axZm.css" media="screen" type="text/css">

<!--  Include Fancybox lightbox css, adjust the path if needed. Best set absolute path -->
<link rel="stylesheet" href="../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.css" media="screen" type="text/css">

<!--  Include jQuery core into head section -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<script type="text/javascript">
/* could work in noConflict mod (without using $)*/
// jQuery.noConflict();
</script>

<!--  AJAX-ZOOM javascript, adjust the path if needed. Best set absolute path -->
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>

<!--  Fancybox lightbox javascript, please note: it has been slightly modified for AJAX-ZOOM !!! Adjust the path if needed. Best set absolute path -->
<script type="text/javascript" src="../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.pack.js"></script>

<!--  Syntaxhighlighter is not needed, you can remove this block along with SyntaxHighlighter.all(); below -->
<link href="../axZm/plugins/demo/syntaxhighlighter/styles/shCore.css" type="text/css" rel="stylesheet" />
<link href="../axZm/plugins/demo/syntaxhighlighter/styles/shThemeCustom.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/src/shCore.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushJScript.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushPhp.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushCss.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushXml.js"></script>


<script type="text/javascript">

// When document is loaded
jQuery(document).ready(function() {

	// SyntaxHighlighter is not needed
	SyntaxHighlighter.all();
	
	// Custom single function to open AJAX-ZOOM in fancybox with dynamic width and hight and window resize support!
	// var queryString is a single string that determines which images will be loaded
	jQuery.openAjaxZoomInFancyBox = function(queryString){
		
		// Fancybox padding
		var boxPadding = 10;

		// Create empty jQuery object wich will be passed to AJAX-ZOOM
		var ajaxZoom = {};
		
		// Define the path to the axZm folder, adjust the path if needed! Best of all set absolute path to axZm
		ajaxZoom.path = '../axZm/';		

		// Calculations of the width and hight passed to fancybox
		var boxW = jQuery(window).width() - boxPadding*2 - 80;
		var boxH = jQuery(window).height() - boxPadding*2 - 80;

		// Trigger some callbacks; for full list please refer to the docs...
		ajaxZoom.ajaxZoomCallbacks = {
			
			onFullScreenReady: function(){
			
				// Generate navi replacements (the two round plus and minus buttons), not really needed
				if (queryString.indexOf("zoom3d") == -1){ // just for this example - do not apply for the lightbox with 360/3d
					// Zoom in button
					jQuery('<div />').css({cursor: 'pointer', zIndex: 111, left: 10, top: 130, width: 24, height: 24, position: 'absolute', backgroundImage: 'url(../axZm/icons/zoom_in1.png)'}).
					bind('click', function(){jQuery.fn.axZm.zoomIn({ajxTo: 750, pZoom: 50});}).
					appendTo("#zoomLayer");
					
					// Zoom out button
					jQuery('<div />').css({cursor: 'pointer', zIndex: 111, left: 10,top: 330, width: 24, height: 24, position: 'absolute', backgroundImage: 'url(../axZm/icons/zoom_out1.png)'}).
					bind('click', function(){jQuery.fn.axZm.zoomOut({ajxTo: 750, pZoom: 50});}).
					appendTo("#zoomLayer");
					
					// Remove the background image of the trackbar in vertical gallery. Can also be done in the css file.
					jQuery('.jspTrack').css({background: 'none'});
				}
			}
		};
		
		// Helper function to adjust fancybox width and hight when browser window resizes
		var fanyDim = {};
		
		var cBoxOnWinResize = function(){
			var difW = jQuery(window).width() - fanyDim.wW;
			var difH = jQuery(window).height() - fanyDim.wH;
			
			jQuery('#fancybox-content').css({
				width: fanyDim.fCw + difW,
				height: fanyDim.fCh + difH
			});
			
			jQuery('#fancybox-wrap').css({
				width: fanyDim.fWrw + difW,
				height: fanyDim.fWrh + difH
			});

			jQuery('#fancybox-outer').css({
				width: fanyDim.fOw + difW,
				height: fanyDim.fOh + difH
			});
		}		
		
		// Create an html element to pretend there is something to load into fancybox
		jQuery('#tempLoadingMassage').remove(); // if present :-)
		jQuery('<DIV />').html('Loading, please wait...').attr('id', 'tempLoadingMassage').css('display', 'none').appendTo('body');
		
		// Trigger fancybox
		jQuery.fancybox({
			padding: boxPadding, // optional, boxPadding defined at very beginning
			overlayShow: true, // optional, show overlay
			overlayOpacity: 0.75, // optional, overlay opacity
			hideOnContentClick: false, // required, do not hide when clicked inside fancybox (AJAX-ZOOM is there)
			centerOnScroll: true, // optional
			width: boxW, // required, boxW is calculated at very beginning
			height: boxH, // required, boxH is calculated at very beginning
			autoScale: false, // required
			autoDimensions: false, // required
			href : '#tempLoadingMassage', // required, pretend there is something to load :-)
			onComplete : function(){
				// Save initial dimensions of the fancybox when it is opened
				// Needed to recalculate the width when window resizes with cBoxOnWinResize function
				fanyDim = {
					fCw: jQuery('#fancybox-content').width(),
					fCh: jQuery('#fancybox-content').height(),
					fWrw: jQuery('#fancybox-wrap').width(),
					fWrh: jQuery('#fancybox-wrap').height(),
					fOw: jQuery('#fancybox-outer').width(),
					fOh: jQuery('#fancybox-outer').height(),
					wW: jQuery(window).width(),
					wH: jQuery(window).height()
				};
				
				
				// Bind custom window resize function (cBoxOnWinResize) to enable size adjustments for the fancybox
				jQuery(window).unbind('resize', cBoxOnWinResize).bind('resize', cBoxOnWinResize);
				
				// Init AJAX-ZOOM
				jQuery.fn.axZm.openFullScreen(ajaxZoom.path, queryString, ajaxZoom.ajaxZoomCallbacks, 'fancybox-content');
			},
			onClosed : function(){
				// Unbind custom fancybox resize function
				// Bug: open fancybox -> resize window -> close fancybox -> resize window -> open fancybox :: it renders wrong
				// Disabling unbind helps...
				//jQuery(window).unbind('resize', cBoxOnWinResize);
				
				// If 360/3D loaded into the fancybox
				jQuery.fn.axZm.spinStop();
				
				// Completly remove AJAX-ZOOM from DOM
				jQuery.fn.axZm.remove(true);
			}
	
		});
		
	}; // End jQuery.openAjaxZoomInFancyBox
	

	
});

</script>


<style type="text/css" media="screen"> 
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}
	
	.exampleLink{
		display: block;
		border: #CCCCCC 1px solid; 
		padding: 10px; 
		margin-top: 10px; 
		background-image: url(../axZm/icons/body.gif);
		font-size: 150%; 
		text-shadow: 1px 1px 1px #999999; 
		text-decoration: none;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		border-radius: 5px;
	}
	
	/* overwrite some css from ../axZm/axZm.css */
	.zoomHorGalleryDescr{
		display: none;
	}
	
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
	
</style>

</head>
<body>

<?php
// This include is just for the demo, you can remove it
include ('navi.php');
?>

<DIV style='width: 800px; margin: 0px auto;'>
	
	<DIV style='float: left; background-color: #FFFFFF; padding: 10px; margin: 5px;'>
	
		<h2>AJAX-ZOOM Full Screen Lightbox Examples</h2>
		<p>In this example AJAX-ZOOM is loaded into the maximized lighbox (Fancybox). 
		The sizes of the Fancybox and AJAX-ZOOM player inside are determined by the window size. 
		By changing the browser window size (orientation change on iOS) all sizes are instantly adjusted (try it). 
		In order to call AJAX-ZOOM with Fancybox in such a way we have created a single function (jQuery.openAjaxZoomInFancyBox) 
		which can be found in the head section of this document.</p> 
		
		<p>Besides jQuery core library (e.g. http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js) you will need Fancybox JavaScript 
		and AJAX-ZOOM main JavaScript file (/axZm/jquery.axZm.js) to include into the head section of the page. 
		For some methods retrieving the images (CSV) our slightly modified version of Fancybox is required (look for explanation below). 
		The <a href="http://www.ajax-zoom.com/index.php?cid=download">download package</a> contains all necessary files. 
		</p>
		
		<p>The following three methods cause the same result - open a set of images in "fullscreen lightbox". 
		The first one compresses a PHP array with images into a string. This string is then passed as value of "zoomData" parameter to AJAX-ZOOM. 
		As you can see the process requires PHP to be executed in your actual application. 
		The second and third methods do not require PHP - only JavaScript and are very good suitable for frontends 
		generated with other programing languages such as ASP.NET; Specifically for ASP.NET you can run AJAX-ZOOM 
		with "<a href="http://www.ajax-zoom.com/index.php?cid=docs#phalanger">Phalanger</a>". 
		</p>

		<p>By the way - this example can serve as tutorial on defining the content to load into AJAX-ZOOM player! 
		There are some other ways of embedding and passing this information to AJAX-ZOOM (see other examples), 
		but the main parameters (zoomData, zoomDir, 3dDir, zoomFile and zoomID) remain unchanged on default. 
		See <a href="http://www.ajax-zoom.com/index.php?cid=docs#heading_10" target="_blank">appendix in the documentation</a> 
		if you need to define your own parameters.
		</p>
		
		<p>Please note that by passing example=someNumber to AJAX-ZOOM over jQuery.openAjaxZoomInFancyBox() some default settings from "/axZm/zoomConfig.inc.php" 
		are overridden in "/axZm/zoomConfigCustom.inc.php" after elseif ($_GET['example'] == someNumber){ 
		So if changes in "/axZm/zoomConfig.inc.php" have no effect look for the same options "/axZm/zoomConfigCustom.inc.php".
		</p>
		
		<p>For opening AJAX-ZOOM in a regular lightbox see e.g. <a href="http://www.ajax-zoom.com/examples/example3.php">example3.php</a>; 
		In <a href="http://www.ajax-zoom.com/examples/example22.php">example22.php</a> you will find a way to embed AJAX-ZOOM into fluid design. 
		In <a href="http://www.ajax-zoom.com/examples/example21.php">example21.php</a> AJAX-ZOOM is opened at maximum width and hight of the browser window.
		</p>
				
		<?php
		////////////////////////////////////////////////////////
		/// zoomData method 1 - compressing PHP into string ////
		////////////////////////////////////////////////////////
		
		// With this function you can optionally compress a PHP array with images to a string
		// This string can then be passed to AJAX-ZOOM as "zoomData" parameter
		// It will be instantly decompressed into PHP array inside AJAX-ZOOM (/axZm/zoomObjects.inc.php)
		function axZmCompress($data){
			return strtr(base64_encode(addslashes(gzcompress(serialize($data),9))), '+/=', '-_,');
		}
		
		$zoomData = array();
	 
		$zoomData[1]['p'] = '/pic/zoom/boutique/'; // Path to image
		$zoomData[1]['f'] = 'test_boutique1.png'; // Image filename
	 
		$zoomData[2]['p'] = '/pic/zoom/boutique/'; // Path to image
		$zoomData[2]['f'] = 'test_boutique2.png'; // Image filename
	
		$zoomData[3]['p'] = '/pic/zoom/boutique/'; // Path to image
		$zoomData[3]['f'] = 'test_boutique3.png'; // Image filename		
		
		$zoomData[4]['p'] = '/pic/zoom/fashion/'; // Path to image
		$zoomData[4]['f'] = 'test_fashion1.png'; // Image filename
	
		$zoomData[5]['p'] = '/pic/zoom/fashion/'; // Path to image
		$zoomData[5]['f'] = 'test_fashion2.png'; // Image filename
	
		$zoomData[6]['p'] = '/pic/zoom/fashion/'; // Path to image
		$zoomData[6]['f'] = 'test_fashion3.png'; // Image filename
		
		$zoomData = axZmCompress($zoomData);
		?>
		
		<a href="javascript:void(0)" class="exampleLink" onClick="jQuery.openAjaxZoomInFancyBox('example=23&zoomData=<?php echo $zoomData;?>');">
		1. Click me: PHP array compressed zoomData</a><br>
		<p>Images are gathered in a PHP array. Afterwards they are compressed to a string. 
		Finally this string will be passed as "zoomData" query string parameter to AJAX-ZOOM. 
		</p>
		

		<?php
		// This is only syntax highlighting, not needed
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
		// With this function you can compress a PHP array with images to a string
		// This string can then be passed to AJAX-ZOOM as "zoomData" parameter
		// It will be instantly decompressed into PHP array inside AJAX-ZOOM (/axZm/zoomObjects.inc.php)
		function axZmCompress($data){
			return strtr(base64_encode(addslashes(gzcompress(serialize($data),9))), "+/=", "-_,");
		}
		
		// Create empty array
		$zoomData = array();
		
		// Add images to the array
		$zoomData[1]["p"] = "/pic/zoom/boutique/"; // Path to image
		$zoomData[1]["f"] = "test_boutique1.png"; // Image filename
	 
		$zoomData[2]["p"] = "/pic/zoom/boutique/"; // Path to image
		$zoomData[2]["f"] = "test_boutique2.png"; // Image filename
	
		$zoomData[3]["p"] = "/pic/zoom/boutique/"; // Path to image
		$zoomData[3]["f"] = "test_boutique3.png"; // Image filename		
		
		$zoomData[4]["p"] = "/pic/zoom/fashion/"; // Path to image
		$zoomData[4]["f"] = "test_fashion1.png"; // Image filename
	
		$zoomData[5]["p"] = "/pic/zoom/fashion/"; // Path to image
		$zoomData[5]["f"] = "test_fashion2.png"; // Image filename

		$zoomData[6]["p"] = "/pic/zoom/fashion/"; // Path to image
		$zoomData[6]["f"] = "test_fashion3.png"; // Image filename
				
		// Compress array into string
		$zoomData = axZmCompress($zoomData);
		');
		echo "</pre>";
		
		echo "<pre class='brush: html;'>";
		echo htmlspecialchars ('
		<!-- Simple link with onClick -->
		<a href="javascript:void(0)" onClick="jQuery.openAjaxZoomInFancyBox(\'example=23&zoomData=<?php echo $zoomData;?>\');">1. PHP array compressed zoomData</a>
		');
		echo "</pre>";
		?>
		
		<p>Additionally you can pass "zoomID" parameter. The value of "zoomID" (number of an image in the array) determines which image has to be loaded first. -> 
		<a href="javascript:void(0)" onClick="jQuery.openAjaxZoomInFancyBox('example=23&zoomID=2&zoomData=<?php echo $zoomData;?>');">
		Test: open specific file first with zoomID
		</a>
		</p> 
		
		<p>Instead of "zoomID" you can pass "zoomFile" parameter. The value of "zoomFile" (full path with the image filename) determines which image has to be loaded first. -> 
		<a href="javascript:void(0)" onClick="jQuery.openAjaxZoomInFancyBox('example=23&zoomFile=<?php echo axZmCompress('/pic/zoom/fashion/test_fashion1.png');?>&zoomData=<?php echo $zoomData;?>');">
		Test: open specific file first with zoomFile
		</a>
		</p> 
		
		
		<!--////////////////////////////////////////////////////
		/// zoomData method 2 - full CSV paths to the images ///
		////////////////////////////////////////////////////////		
		-->
		
		<a href="javascript:void(0)" class="exampleLink" onClick="jQuery.openAjaxZoomInFancyBox('example=23&zoomData=/pic/zoom/boutique/test_boutique1.png|/pic/zoom/boutique/test_boutique2.png|/pic/zoom/boutique/test_boutique1.png|/pic/zoom/fashion/test_fashion1.png|/pic/zoom/fashion/test_fashion2.png|/pic/zoom/fashion/test_fashion3.png');">
		2. Click me: CSV - zoomData full paths separated  by "|" </a>
		<p>Same results as "PHP array compressed zoomData" above can be achieved by simply passing CSV as zoomData parameter - by "|" separated paths to the images. 
		This does not require any PHP code in your actual application. 
		<b>PLEASE NOTE: in order to do this we have slightly modified the latest fancybox Ver. 1.3.4; 
		Please use our modified version, because the standard version will expect an image if it founds .jpg, .png or other image formats in a string and will 
		return a massage saying the image can't be found. The modification enforces ajax request if "zoomData" is found in the string.</b>
		</p>
		
		<?php
		// This is only syntax highlighting, not needed
		echo "<pre class='brush: html;'>";
		echo htmlspecialchars ('
		<!-- Simple link with onClick -->
		<a href="javascript:void(0)" onClick="jQuery.openAjaxZoomInFancyBox(\'example=23&
		zoomData=/pic/zoom/boutique/test_boutique1.png|/pic/zoom/boutique/test_boutique2.png|
		/pic/zoom/boutique/test_boutique3.png|/pic/zoom/fashion/test_fashion1.png|
		/pic/zoom/fashion/test_fashion2.png|/pic/zoom/fashion/test_fashion3.png\')">2. CSV - 
		zoomData full paths separated  by "|"</a>
		');
		echo "</pre>";
		?>

		<p>Additionally you can pass "zoomID" parameter. The value of "zoomID" (number of an image in the array) determines which image has to be loaded first. -> 
		<a href="javascript:void(0)" onClick="jQuery.openAjaxZoomInFancyBox('example=23&zoomID=2&zoomData=/pic/zoom/boutique/test_boutique1.png|/pic/zoom/boutique/test_boutique2.png|/pic/zoom/boutique/test_boutique1.png|/pic/zoom/fashion/test_fashion1.png|/pic/zoom/fashion/test_fashion2.png|/pic/zoom/fashion/test_fashion3.png');">
		Test: open specific file first with zoomID
		</a>
		</p> 
		
		<p>Instead of "zoomID" you can pass "zoomFile" parameter. The value of "zoomFile" (full path with the image filename) determines which image has to be loaded first. -> 
		<a href="javascript:void(0)" onClick="jQuery.openAjaxZoomInFancyBox('example=23&zoomFile=/pic/zoom/fashion/test_fashion2.png&zoomData=/pic/zoom/boutique/test_boutique1.png|/pic/zoom/boutique/test_boutique2.png|/pic/zoom/boutique/test_boutique1.png|/pic/zoom/fashion/test_fashion1.png|/pic/zoom/fashion/test_fashion2.png|/pic/zoom/fashion/test_fashion3.png');">
		Test: open specific file first with zoomFile
		</a>
		</p> 

		<!--//////////////////////////////////////////////////////////////////////////
		/// zoomData method 1 & 2 with use Path defined in $zoom['config']['pic'] ////
		//////////////////////////////////////////////////////////////////////////////
		-->
		<a href="javascript:void(0)" class="exampleLink" onClick="jQuery.openAjaxZoomInFancyBox('example=23&zoomData=boutique/test_boutique1.png|boutique/test_boutique2.png|boutique/test_boutique1.png|fashion/test_fashion1.png|fashion/test_fashion2.png|fashion/test_fashion3.png');">3. Click me: $zoom['config']['pic'] as prefix</a>
		<p>$zoom['config']['pic'] in /axZm/zoomConfig.inc.php can be used as prefix to all paths passed to AJAX-ZOOM. 
		If you need to point to the image located in "/pic/zoom/boutique/bag_6.jpg" and $zoom['config']['pic'] is "/pic/zoom/" the path can be just "boutique/bag_6.jpg". 
		No matter what is defined in $zoom['config']['pic'] you can pass the full path too, e.g "/pic/zoom/boutique/bag_6.jpg" will work as well. 
		<b>PLEASE NOTE: in order to do this we have slightly modified the latest fancybox Ver. 1.3.4; 
		Please use our modified version, because the standard version will expect an image if it founds .jpg, .png or other image formats in a string and will 
		return a massage saying the image can't be found. The modification enforces ajax request if 'zoomData' is found in the string.</b>
		</p>

		<?php
		// This is only syntax highlighting, not needed
		echo "<pre class='brush: html;'>";
		echo htmlspecialchars ('
		<!-- Simple link with onClick -->
		<a href="javascript:void(0)" onClick="jQuery.openAjaxZoomInFancyBox(\'example=23&
		zoomData=boutique/test_boutique1.png|boutique/test_boutique2.png|
		boutique/test_boutique3.png|fashion/test_fashion1.png|
		fashion/test_fashion2.png|fashion/test_fashion3.png\');">3. $zoom[\'config\'][\'pic\'] as prefix</a>
		');
		echo "</pre>";
		?>

		<p>Additionally you can pass "zoomID" parameter. The value of "zoomID" (number of an image in the array) determines which image has to be loaded first. -> 
		<a href="javascript:void(0)" onClick="jQuery.openAjaxZoomInFancyBox('example=23&zoomID=2&zoomData=boutique/test_boutique1.png|boutique/test_boutique2.png|boutique/test_boutique1.png|fashion/test_fashion1.png|fashion/test_fashion2.png|fashion/test_fashion3.png');">
		Test: open specific file first with zoomID
		</a>
		</p> 
		
		<p>Instead of "zoomID" you can pass "zoomFile" parameter. The value of "zoomFile" (full path with the image filename) determines which image has to be loaded first. -> 
		<a href="javascript:void(0)" onClick="jQuery.openAjaxZoomInFancyBox('example=23&zoomFile=fashion/fashion_1.jpg&zoomData=boutique/test_boutique1.png|boutique/test_boutique2.png|boutique/test_boutique1.png|fashion/test_fashion1.png|fashion/test_fashion2.png|fashion/test_fashion3.png');">
		Test: open specific file first with zoomFile
		</a>
		</p>

		<!--////////////////////////////////////////////////////////////////////////
		/// zoomDir - load entire directory with images with just one parameter ////
		////////////////////////////////////////////////////////////////////////////
		-->
		<a href="javascript:void(0)" class="exampleLink" onClick="jQuery.openAjaxZoomInFancyBox('example=23&zoomDir=animals');">
		4. Click me: zoomDir - load entire directory with images</a>
		<p>You can load entire directory with images by passing zoomDir parameter e.g. "/pic/zoom/animals"; 
		As in method 3 above - provided, that $zoom['config']['pic'] is set to e.g. "/pic/" the value of zoomDir parameter can be just "zoom/animals" 
		or if $zoom['config']['pic'] is set to "/pic/zoom/", the value of zoomDir can be "animals".
		</p>

		<?php
		// This is only syntax highlighting, not needed
		echo "<pre class='brush: html;'>";
		echo htmlspecialchars ('
		<!-- Simple link with onClick -->
		<a href="javascript:void(0)" onClick="jQuery.openAjaxZoomInFancyBox(\'example=23&zoomDir=/pic/zoom/animals\');">4. zoomDir - load entire directory with images</a>
		');
		echo "</pre>";
		?>

		<p>Additionally you can pass "zoomID" parameter. The value of "zoomID" (number of an image in the array) determines which image has to be loaded first. -> 
		<a href="javascript:void(0)" onClick="jQuery.openAjaxZoomInFancyBox('example=23&zoomID=2&zoomDir=/pic/zoom/animals');">
		Test: open specific file first with zoomID
		</a>
		</p> 
		
		<p>Instead of "zoomID" you can pass "zoomFile" parameter. The value of "zoomFile" (full path with the image filename) determines which image has to be loaded first. -> 
		<a href="javascript:void(0)" onClick="jQuery.openAjaxZoomInFancyBox('example=23&zoomFile=dog.jpg&zoomDir=/pic/zoom/animals');">
		Test: open specific file first with zoomFile
		</a>
		</p>
		
		<!--/////////////////////////////////////////////////////////////////////////
		/// 3dDir - load 360/3D by passing the path to the folder with images ///////
		/////////////////////////////////////////////////////////////////////////////
		-->
		<a href="javascript:void(0)" class="exampleLink" onClick="jQuery.openAjaxZoomInFancyBox('example=17&3dDir=../pic/zoom3d/Uvex_Occhiali');">5. Click me: 3dDir - 360 / 3D animations</a>
		<p>Load 360/3D animations by passing the parameter 3dDir - path to the directory with images.
		</p>

		<p>Please Note: the only difference between regular 360 spin and 3D (multirow) is that original images are placed in subfolders of the target folder. 
		E.g. the path passed to the folder is "/pic/zoom3d/Uvex_Occhiali" images of each row are placed in subfolders, 
		e.g. /pic/zoom3d/Uvex_Occhiali/row_1, /pic/zoom3d/Uvex_Occhiali/row_2, /pic/zoom3d/Uvex_Occhiali/row_3; 
		You do not need to define these subfolders anywhere. AJAX-ZOOM will instantly detect them and procede all the images in them. 
		</p>

		<?php
		// This is only syntax highlighting, not needed
		echo "<pre class='brush: html;'>";
		echo htmlspecialchars ('
		<!-- Simple link with onClick -->
		<a href="javascript:void(0)" class="exampleLink" onClick="jQuery.openAjaxZoomInFancyBox(\'example=17&3dDir=/pic/zoom3d/Uvex_Occhiali\');">5. 3dDir - 360 / 3D animations</a>
		');
		echo "</pre>";
		?>

		<p>Additionally you can pass "zoomID" parameter. The value of "zoomID" (number of an image in the array) determines which image has to be loaded first. -> 
		<a href="javascript:void(0)" onClick="jQuery.openAjaxZoomInFancyBox('example=17&zoomID=15&3dDir=../pic/zoom3d/Uvex_Occhiali');">
		Test: open specific file first with zoomID
		</a>
		</p> 	

	</DIV>
</DIV>
<?php
// This include is just for the demo, you can remove it
include('footer.php');
?>
</body>
</html>