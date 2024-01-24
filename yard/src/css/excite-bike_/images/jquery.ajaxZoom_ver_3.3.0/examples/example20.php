<?php
if(!session_id()){session_start();}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>AJAX-ZOOM - mouse over zoom javascript flyout zoom</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="imagetoolbar" content="no">
<?php
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
}
?>
<!-- CSS for jcarousel to show the thumbs under the image -->
<link href="../axZm/plugins/demo/jcarousel/skins/custom/skin.css" type="text/css" rel="stylesheet" />

<!-- CSS to style the syntax, not needed! -->
<link href="../axZm/plugins/demo/syntaxhighlighter/styles/shCore.css" type="text/css" rel="stylesheet" />
<link href="../axZm/plugins/demo/syntaxhighlighter/styles/shThemeCustom.css" type="text/css" rel="stylesheet" />


<!-- jQuery core, needed! -->
<script type="text/javascript" src="../axZm/plugins/jquery-1.7.2.min.js"></script>

<!-- jcarousel js for thumbnails -->
<script type="text/javascript" src="../axZm/plugins/demo/jcarousel/lib/jquery.jcarousel.min.js"></script>

<!-- Javascript to style the syntax, not needed! -->
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/src/shCore.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushJScript.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushPhp.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushCss.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushXml.js"></script>


<style type="text/css" media="screen"> 
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}	
	p {text-align: justify;}
	
	/* Thumbnails in jcarousel */
	.outerimg{
		background-position: center center;
		width: 62px;
		height: 62px;
		margin: 1px 0px 0px 1px;
		background-repeat: no-repeat;
	}
	
	.outerContainer{
		display: block;
		float: left;
		cursor: pointer; 
		width: 64px;
		height: 64px; 
		margin: 0px 3px 3px 0px;
		background-color: #E3E3E3;
		outline: none;
	}
	
	/* Overwrite some css from /axZm/axZm.css */
	.zoomHorGalleryDescr{
		display:none;
	}
	
	#zoomContainer{
		background-color: #E3E3E3;
	}
	
	#zoomMapSel{
		border-color: blue;
	}

	#zoomMapSelArea{
		background-color: blue;
	}	
	
	#zoomMapLoading{
		background-color: #FFFFFF;
		background-image: url('../axZm/icons/ajax-loader-map-white.gif');
	}
	
	#zoomMapHolder{
		background-color: #FFFFFF;
		background-image: url('../axZm/icons/ajax-loader-map-white.gif');
	}
	
</style>

</head>
<body>

<?php
// Top navigation for examples, not needed!
include ('navi.php');

echo "<DIV style='width: 800px; margin: 0px auto;'>\n";
	
	echo "<DIV style='float: left; min-height: 600px; min-width: 780px; background-color: #FFFFFF; margin: 5px;'>\n";
	
		echo "<h2>AJAX-ZOOM - outer image map outside. Mouseover initialization - hover over the image zoom (flyout zoom).</h2>\n";
		echo "<DIV style='clear: both;'>\n";

			// Define the images that will be passed to AJAX-ZOOM
			// In your application this array should be build dynamically
			// The indexes can be any integer > 0
 			$zoomData[1]['p'] = '/pic/zoom/fashion/'; // Path to image
			$zoomData[1]['f'] = 'test_fashion1.png'; // Image filename
			
			$zoomData[2]['p'] = '/pic/zoom/fashion/';
			$zoomData[2]['f'] = 'test_fashion2.png';
			
			$zoomData[3]['p'] = '/pic/zoom/fashion/';
			$zoomData[3]['f'] = 'test_fashion3.png';
			
			$zoomData[4]['p'] = '/pic/zoom/fashion/';
			$zoomData[4]['f'] = 'test_fashion4.png';
			
			// Encode and compress the array with images
			// It will be decoded in zoomObjects.inc.php : $_GET['zoomData'] = $axZmH->uncompress($_GET['zoomData']);
			// You can replace or remove this encoding procedure when using AJAX-ZOOM on not PHP pages
			// In this case it is important that in zoomObjects.inc.php the $_GET['zoomData'] will turn into PHP array again (instead of $_GET['zoomData'] = $axZmH->uncompress($_GET['zoomData'])) 
			// If you have any questions on this or other issues please contact the support
			$_GET['zoomData'] = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), '+/=', '-_,');
			
			?>
			
			<!-- Wrapper for media data-->
			<DIV style='float: left; width: 250px; min-height: 400px; margin-right: 20px'>

				<!-- Container for preview image (AJAX-ZOOM "image map") -->
				<DIV id='mapContainer' style='position: absolute; width: 250px; height: 375px;'></DIV>

				<!-- Container for zoomed image (will be displayed to the right from preview image) -->
				<DIV id='zoomedAreaDiv' style='display: none; float: left; min-width: 450px; min-height: 450px; position: absolute; z-index: 20;'></DIV>
				
				<!-- Touch devices additional control -->
				<DIV id="touchDevicesZoomButtons" style="clear: both; width: 250px; padding: 0; margin: 0; height: 20px; position: absolute; display: none;">
					<a href='javascript:void(0)' onClick="jQuery.touchDevicesZoomOn()">ENABLE ZOOM</a> | 
					<a href='javascript:void(0)' onClick="jQuery.touchDevicesZoomOff()">DISABLE ZOOM</a>
				</DIV>

				<!-- Navi replacement (plus and minus buttons for zooming) -->
				<DIV id="naviReplacement" style="text-align: left; position: absolute; display: none;">
					<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomIn({speed: 750, ajxTo: 1000, pZoom: 25})" style="outline-style: none;"><img src="../axZm/icons/zi_32x32.png" border="0" ></a>
					<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomOut({speed: 750, ajxTo: 1000, pZoom: 25})" style="outline-style: none;"><img src="../axZm/icons/zo_32x32.png" border="0"></a>
				</DIV>	
				
				<!-- jcarousel with thumbs (will be filled with thumbs by javascript) -->
				<DIV id="jcarouselContainer" style="clear: both; width: 250px; position: absolute; display: none;">
					<ul id="mycarousel" class="jcarousel-skin-custom"></ul>
				</DIV>
				
			</DIV>
			
			<?php
			echo '<span style="font-size: 100%">
			In this experiment we simulate the zoom effect used in many web shops these days. 
			The main advantage of using AJAX-ZOOM is that it does not require the client to load the entire high resolution image. 
			Only some image tiles are loaded at first and more tiles are byloaded as the user pans around. Simmilar to Google Maps. 
			Thus the source image can be of any size and quality.<br><br>
			
			The user has also a possibility to change the zoom level by clicking on the 
			magnifier icons (can be replaced with other icons) or by using the mousewheel when hover the zoomed image area. 
			Within the zoomed image area it is also possible to pan the image with the mouse or finger on mobile touch devices 
			like iPad. As these touch devices do not understand the mouseover event an additional control will appear under the 
			image with which the user activates the zoom functionality by clicking on it.<br><br>
			
			At the right top of the image area there is a small icon. Clicking on it triggers the initialization of fullscreen. 
			Additionally you can trigger fullscreen by clicking on any other icon using the API method $.fn.axZm.initFullScreen(). 
			
			For the thumbnails below we simply use the jCarousel plugin (not necessary). 
			In this example the thumbnails are instantly generated by AJAX-ZOOM. 
			This can be turned of if thumbnails are already generated by the web shop software: 
			<a href="http://www.ajax-zoom.com/index.php?cid=docs#galleryNoThumbs">$zoom[\'config\'][\'galleryNoThumbs\'] = true;</a><br><br>
			
			See this experiment <a href="http://www.ajax-zoom.com/demo/magento/index.php/chair.html?displayModus=flyout">implemented in Magento</a>.<br><br>

			Patch: 2012-02-13 Optional dragging by mouse over on not touch devices. 
			On click or doubleclick fullscreen mode is opened, as always - optionally (see callback onMapMouseOverClick in the source).
			</span>
			';

		
		
		echo "</DIV>\n";
		
	echo "</DIV>\n";
	
	
 
		?>
		<script type="text/javascript">
		// SyntaxHighlighter is just for the demo to show the source code, should be removed!
		SyntaxHighlighter.all();
		
		// Horizontal offset
		var zoomedAreaOffsetRight = 10;
		
		// Vertical offset
		var zoomedAreaOffsetTop = 0;
		
		// ID of zoomed area
		var zoomedAreaDiv = 'zoomedAreaDiv';
		
		// Additional control functions for touch devices
		jQuery.touchDevicesZoomOn = function(){
			jQuery('#mapContainer, #'+zoomedAreaDiv).unbind();
			jQuery('#zoomMapSel').css('display', 'block');
			jQuery('#naviReplacement').stop(true, false).css({display: 'block', 'opacity': 1});
			var relID = 'mapContainer'; //  zoomMapHolder
			var rOffset = jQuery('#'+relID).offset();
			jQuery('#'+zoomedAreaDiv).stop(true, false).css({
				opacity: 1, 
				display: 'block',
				left: Math.round(rOffset.left + jQuery('#'+relID).width() + zoomedAreaOffsetRight),
				top: Math.round(rOffset.top + zoomedAreaOffsetTop)
			});
		};
		
		jQuery.touchDevicesZoomOff = function(){
			jQuery('#mapContainer, #'+zoomedAreaDiv).unbind();
			jQuery('#zoomMapSel').css('display', 'none');
			jQuery('#naviReplacement').stop(true, false).css('opacity', 0.0);
			jQuery('#'+zoomedAreaDiv).stop(true, false).css({
				opacity: 0, 
				display: 'none'
			});	
		};
		
		var ajaxZoom = {};
		
		// AJAX-ZOOM callbacks
		ajaxZoom.opt = {
		
			// AJAX-ZOOM callback triggered after AJAX-ZOOM is loaded
			onLoad: function(){
				//  zoomMapHolder
				var relID = 'mapContainer'; 
				
				// Icons for zoomIn and zoomOut, not necessary
				jQuery('#naviReplacement').appendTo('#mapContainer').css({
					left: 10,
					top: 10,
					zIndex: 9999,
					opacity: (jQuery.browser.msie ? '' : 0)
				});
				
				// Background for zoom level, not necessary
				jQuery('<div />').attr('id', 'zoomLevelWrap').css({
					position: 'absolute',
					left: 0,
					top: 0,
					backgroundColor: '#000000',
					opacity: 0.3,
					width: 40,
					height: 20,
					zIndex: 9998
				}).appendTo('#zoomLayer');
				
				// Zoom level, not necessary
				jQuery('#zoomLevel').appendTo('#zoomLayer').css({
					position: 'absolute',
					color: '#FFFFFF',
					width: 40,
					padding: 3,
					margin: 0,
					fontSize: '10pt',
					display: 'block',
					left: 0,
					top: 0,
					zIndex: 9999
				});
				
				// Some helper functions
				function getl(sep, str){
					return str.substring(str.lastIndexOf(sep)+1);
				}
				
				function getf(sep, str){
					var extLen = getl(sep, str).length;
					return str.substring(0, (str.length - extLen - 1));
				}
				
				function cfn(file){
					var full = '_'+jQuery.axZm.galFullPicX+'x'+jQuery.axZm.galFullPicY;
					return getf('.', file)+full+'.jpg';
				}
				
				// Detect iphone
				function touchDevicesZoomTest() {
					if(/KHTML|WebKit/i.test(navigator.userAgent) && ('ontouchstart' in window)) {
						return true;
					}else{
						return false;
					}
				}
				
				// Hide zoom selector if mouse is not over
				jQuery('#zoomMapSel').css('display', 'none');

				// Get the position of the preview image (AJAX-ZOOM "image map")
				var rPosition = jQuery('#'+relID).position();

				// Position the jcarousel container below the preview image
				jQuery('#jcarouselContainer').css({
					top: rPosition.top+jQuery('#'+relID).height()+10,
					display: 'block'
				});
				
				// Put thumbnails (generated by AJAX-ZOOM) into jcarousel container
				// jQuery.axZm.zoomGA is a JS object containing information about the images in the gallery
				// All thumbnails are created on the fly while loading first time
				jQuery.each(jQuery.axZm.zoomGA, function(k,v){
						var li = jQuery('<li />');
						var a = jQuery('<a />').addClass('outerContainer').bind('click',function(){jQuery.fn.axZm.zoomSwitch(k); return false;});
						var div = jQuery('<div />').addClass('outerimg').css('backgroundImage', 'url('+jQuery.axZm.zoomGalDir+cfn(v.img)+')');
						jQuery(div).appendTo(a);
						jQuery(li).append(a).appendTo('#mycarousel');
				});
				
				// Init jcarousel
				jQuery('#mycarousel').jcarousel();
				
				// Dedect touch devices and add switch interface for them
				if (touchDevicesZoomTest()){
					// Add switch interface, can and should be styled as you want
					jQuery('#touchDevicesZoomButtons').css({
						display: 'block',
						top: rPosition.top+jQuery('#'+relID).height()+10,
						zIndex: 99999
					});
					// Move the thumbnail container a little below
					jQuery('#jcarouselContainer').css({
						top: parseInt(jQuery('#jcarouselContainer').css('top'))+jQuery('#touchDevicesZoomButtons').height()
					});
				}
				
				
				// Mouseenter on preview image (AJAX-ZOOM "image map") function
				jQuery('#mapContainer').bind('mouseenter', function(){
					if (jQuery.removeHoverTimeout){clearTimeout(jQuery.removeHoverTimeout);}

					// Position AJAX-ZOOM area to the right of zoom map
					var rOffset = jQuery('#'+relID).offset();
					jQuery('#'+zoomedAreaDiv).stop(true, false).css({
						display: 'block',
						opacity: 1,
						left: Math.round(rOffset.left + jQuery('#'+relID).width() + zoomedAreaOffsetRight),
						top: Math.round(rOffset.top + zoomedAreaOffsetTop)
					});
					
					// Show zoom selector
					jQuery('#zoomMapSel').css('display', 'block');
					
					if (!jQuery.browser.msie){
						jQuery('#naviReplacement').stop(true, false).css({
							opacity: 1,
							display: 'block'
						});
					}
				});
 
				// Mouseleave on preview image (AJAX-ZOOM "image map") and the actual zoom area function
				jQuery('#mapContainer, #'+zoomedAreaDiv).bind('mouseleave', function(){
					jQuery.removeHoverTimeout = setTimeout(function(){
						jQuery('#'+zoomedAreaDiv).stop(true, false).fadeTo(500, 0, function(){
							jQuery(this).css('display', 'none');
							jQuery('#zoomMapSel').css('display', 'none');
						}); 
						if (!jQuery.browser.msie){
							jQuery('#naviReplacement').stop(true, false).fadeTo(500, 0.0);
						}else{
							jQuery('#naviReplacement').stop(true, false).css('display', 'none');
						}
					}, 300);
				});
				
				// Prevent closing zoom area when mouse is over it. 
				jQuery('#'+zoomedAreaDiv).bind('mouseenter', function(){
					if (jQuery.removeHoverTimeout){clearTimeout(jQuery.removeHoverTimeout);}
				});
			},
			onFullScreenStart: function(){
				var cont = jQuery('#jcarouselContainer');
				var zoomC = jQuery('#zoomContainer');
				cont.data('top', parseFloat(cont.css('top')));
				cont.data('prev', cont.prev().attr('id'));
				cont.appendTo(zoomC).css({
					zIndex: 99999,
					top: jQuery(window).height() - cont.height() - 20,
					left: 20
				});
				zoomC.data('back', zoomC.css('backgroundColor'));
				zoomC.css('backgroundColor', '#000000');
			},
			
			onFullScreenResizeEnd: function(){
				var cont = jQuery('#jcarouselContainer');
				cont.css({
					top: jQuery(window).height() - cont.height() - 20,
					left: 20
				});
			},
			
			onFullScreenClose: function(){
				var cont = jQuery('#jcarouselContainer');
				var zoomC = jQuery('#zoomContainer');
				
				cont.insertAfter('#'+cont.data('prev')).css({
					zIndex: '',
					top: cont.data('top'),
					left: ''
				});
				
				zoomC.css('backgroundColor', zoomC.data('back'));
				jQuery('#mapContainer').trigger('mouseleave');
			}
		};
		
		// Path to the axZm folder (relative or absolute)
		ajaxZoom.path = '../axZm/'; 
		
		// Needed parameter query string
		// example=22 -> overwrites some default parameter in zoomConfigCustom.inc.php after elseif ($_GET['example'] == 22)
		ajaxZoom.parameter = 'zoomData=<?php echo $_GET['zoomData']?>&example=22'; 
		
		// The id of the Div where ajax-zoom has to be inserted into
		ajaxZoom.divID = zoomedAreaDiv; 
		</script>
		
		<!-- AJAX-ZOOM loader file -->
		<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>
		
		<?php
		
		
	
	// Show syntax for examples, not needed!
	$example = 20; include('syntax.php');

echo "</DIV>\n";

// Bottom navigation for examples, not needed!
include('footer.php');
?>
</body>
</html>