<?php
if(!session_id()){session_start();}

// Show an image on the fly
// PHP code below is only needed for dynamically generated thumbs
if (isset($_GET['previewPic']) && isset($_GET['previewDir'])){
	$noObjectsInclude = true;
	include('../axZm/zoomInc.inc.php');
	ob_start();
	$path = $axZmH->checkSlash($zoom['config']['fpPP'].$zoom['config']['installPath'].urldecode($_GET['previewDir']),'add');
	$w = 90;
	$h = 90;
	$fillThumb = false;
	
	$ww = $w;
	$hh = $h;
	
	if ($fillThumb){
		$ratio = 1;
		$imgSize = getimagesize($path.urldecode($_GET['previewPic']));
		if ($imgSize[0] > $imgSize[1]){
			$ratio = $imgSize[0] / $imgSize[1];
		} elseif ($imgSize[1] > $imgSize[0]){
			$ratio = $imgSize[1] / $imgSize[0];
		}
		$ww = $ww * $ratio;
		$hh = $hh * $ratio;
	}
	
	if ($axZmH->isValidPath($path)){
		$axZm->rawThumb($zoom, $path, urldecode($_GET['previewPic']), round($ww), round($hh), 95, true);
	}
	ob_end_flush();
	exit;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>AJAX-ZOOM - image map outside, custom layout</title> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="imagetoolbar" content="no">

<?php 
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
}
?>

<script type="text/javascript" src="../axZm/plugins/jquery-1.7.2.min.js"></script>

<?php 
if (!isset($_GET['iframe'])){
?>
	<!-- Fancybox is only needed to demostrate opening this page inside fancybox as iframed content 
	As long there are no AJAX-ZOOM instances on the same page you can also open it as ajax content, see example3.php
	-->
	<link rel="stylesheet" href="../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.css" media="screen" type="text/css">
	<script type="text/javascript" src="../axZm/plugins/demo/jquery.fancybox/jquery.easing.1.3.js"></script>
	<script type="text/javascript" src="../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.js"></script>
	
	<!--  syntaxhighlighter is not needed, you can remove this block along with SyntaxHighlighter.all(); below -->
	<link href="../axZm/plugins/demo/syntaxhighlighter/styles/shCore.css" type="text/css" rel="stylesheet" />
	<link href="../axZm/plugins/demo/syntaxhighlighter/styles/shThemeCustom.css" type="text/css" rel="stylesheet" />
	<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/src/shCore.js"></script>
	<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushJScript.js"></script>
	<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushPhp.js"></script>
	<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushCss.js"></script>
	<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushXml.js"></script>
	
		
	<script type="text/javascript">
	jQuery(document).ready(function() {
		// SyntaxHighlighter is not needed
		SyntaxHighlighter.all();
		
		jQuery('#ifrmExample1').fancybox({
			padding				: 0,
			overlayShow			: true,
			overlayColor		: '#000000',
			overlayOpacity		: 0.6,
			zoomSpeedIn			: 0,
			zoomSpeedOut		: 100,
			easingIn			: 'swing',
			easingOut			: 'swing',
			hideOnContentClick	: false, // Important
			centerOnScroll		: true,
			imageScale			: true,
			autoDimensions		: true,
			frameWidth			: 792,
			frameHeight			: 689
		});		
	});
	</script>
		
	<?php 
	}
	?>



	<style type="text/css" media="screen"> 
		html {font-family: Tahoma, Arial; font-size: 10pt;}
		h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}	
		p {text-align: justify; text-justify: newspaper;}
	
		#zoomContainer{
			background-color: #FFFFFF;
		}
		
		#zoomMapHolder{
			border-color: #CCCCCC;
		}
		
		.outerimg{
			background-position: center center;
			width: 90px;
			height: 90px;
			margin: 5px 0px 0px 5px;
			background-repeat: no-repeat;
		}
		
		.outerContainer{
			display: block;
			float: left;
			cursor: pointer; 
			width: 100px;
			height: 100px; 
			margin: 0px 7px 7px 0px;
			border: #CCCCCC 1px solid;
			background-color: #FFFFFF;
			-moz-border-radius: 5px;
			-webkit-border-radius: 5px;
			border-radius: 5px;
			outline-style: none;
			-moz-box-shadow: 1px 1px 1px #FDFDFD;
			box-shadow: 1px 1px 1px #FDFDFD;
			-webkit-box-shadow: 1px 1px 1px #FDFDFD;
		}
	</style>
 
 </head>
<body>

<?php
if (!isset($_GET['iframe'])){
	include ('navi.php');
	echo "<DIV style='width: 800px; margin: 0px auto;'>\n";
}
		// Start layout box
		echo "<DIV style='float: left; min-height: 600px; min-width: 770px; background-color: #FFFFFF; border: #CCCCCC 1px ".(isset($_GET['iframe']) ? 'solid' : 'dashed')."; padding: 10px; margin-bottom: 10px; background-image: url(../axZm/icons/body.gif);'>\n";
		
		echo "<h2 style='color: #444444; text-shadow: 2px 2px 2px #FFFFFF;'>AJAX-ZOOM - no toolbar, image map outside, zoom slider enabled, custom navi using api functions</h2>\n";
		
		echo "<DIV style='clear: both;'>\n";

			// Create an array with images
			if (!isset($_GET['iframe'])){
				$zoomData[1]['p'] = '/pic/zoom/fashion/';
				$zoomData[1]['f'] = 'test_fashion1.png';
				
				$zoomData[2]['p'] = '/pic/zoom/fashion/';
				$zoomData[2]['f'] = 'test_fashion3.png';
				
				$zoomData[3]['p'] = '/pic/zoom/fashion/';
				$zoomData[3]['f'] = 'test_fashion2.png';
				
				$zoomData[4]['p'] = '/pic/zoom/fashion/';
				$zoomData[4]['f'] = 'test_fashion4.png';
			
				$_GET['zoomData'] = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), '+/=', '-_,');
			}
			
			echo "<DIV id='test' style='float: left; width: 402px; height: 602px;'>Loading, please wait...</DIV>\n";
			
			echo "<DIV style='float: left; width: 348px; height: 602px; margin-left: 20px'>\n";
				
				echo "<DIV style='position: absolute; width: 348px; height: 602px;'>\n";
					
					echo "<DIV style='margin-bottom: 5px'>Free HTML layout, e.g image galery, add to cart button...</DIV>\n";
					
					if ($_GET['zoomData']){
						// Uncompress zoomData array if it has been passed over the query string (loaded in iframe lightbox)
						$zoomData = unserialize(gzuncompress(stripslashes(base64_decode(strtr($_GET['zoomData'], '-_,', '+/=')))));
					}
					
					// Display your custom gallery
					if (count($zoomData) > 1){
						foreach($zoomData as $k => $v){							
							// The thumbs are created on the fly each time. Can be used for testing. Should be replaced with static generated thumbs...
							// echo "<a href=\"javascript: void(0)\" id=\"thumb_".$k."\" class=\"outerContainer\" onClick=\"jQuery.fn.axZm.zoomSwitch('$k'); return false;\"><DIV class=\"outerimg\" style=\"background-image: url(".$_SERVER['PHP_SELF']."?previewPic=".$v['f']."&previewDir=".$v['p'].") \"></DIV></a>\n";
							
							// Static generated thumbs
							echo "<a href=\"javascript: void(0)\" id=\"thumb_".$k."\" class=\"outerContainer\" onClick=\"jQuery.fn.axZm.zoomSwitch('$k'); return false;\"><DIV class=\"outerimg\" style=\"background-image: url(../pic/zoomgallery/".substr($v['f'],0,-4)."_90x90.jpg) \"></DIV></a>\n";
						}
					}
					
					// Container for image map, the id should be same as in $zoom['config']['mapParent']
					echo "<DIV id='mapContainer' style='position: absolute; width: 120px; height: ".round(600*0.5+2)."px; left: 0px; top: ".round(600-(600*0.5)-20)."px;'></DIV>";
					
					// Example simple links for basic controlls
					echo '<div id="naviLinks" style="text-align: left; position: absolute; top: '.round(600-15).'px; left: 0px;">
					<a href="javascript: void(0)" style="color: #444444" onclick="jQuery.fn.axZm.zoomIn({ajxTo: 750})">ZoomIn</a> |  
					<a href="javascript: void(0)" style="color: #444444" onclick="jQuery.fn.axZm.zoomOut({ajxTo: 750})">ZoomOut</a> | 
					<a href="javascript: void(0)" style="color: #444444" onclick="jQuery.fn.axZm.zoomReset()">Reset</a> 
					</div>';
					
					/*
					echo '<div style="text-align: left; position: absolute; top: '.round(600-32).'px; left: 0px;">
					<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomIn({ajxTo: 750})" style="outline-style: none;"><img src="../axZm/icons/zi_32x32.png" border="0" ></a>
					<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomOut({ajxTo: 750})" style="outline-style: none;"><img src="../axZm/icons/zo_32x32.png" border="0"></a>
					<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomReset()" style="outline-style: none;"><img src="../axZm/icons/zr_32x32.png" border="0"></a>
					</div>';	
					*/		
					
					// This div is appended to AJAX-ZOOM player with "onLoad" callback, see below
					echo '<div id="naviReplacement" style="text-align: left; position: absolute; display: none;">
					<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomIn({ajxTo: 750})" style="outline-style: none;"><img src="../axZm/icons/zi_32x32.png" border="0" ></a>
					<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomOut({ajxTo: 750})" style="outline-style: none;"><img src="../axZm/icons/zo_32x32.png" border="0"></a>
					<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomReset()" style="outline-style: none;"><img src="../axZm/icons/zr_32x32.png" border="0"></a>
					</div>';	
					
				echo "</DIV>";
			echo "</DIV>";

			?>
			<script type="text/javascript">
			 // Create new object
			var ajaxZoom = {};
			
			// Callback functions
			ajaxZoom.opt = {
				onLoad: function(){
					// Hilight one of the thumbs
					jQuery("#thumb_"+jQuery.axZm.zoomID).css("borderColor", "#000000");
					
					// Append custom toolbar to AJAX-ZOOM
					jQuery("#naviReplacement").appendTo("#zoomLayer").css({
						display: "block",
						left: 10,
						bottom: 10,
						zIndex: 111
					});
					
					
					/*
					// Append zoom level if you want
					jQuery('#zoomLevel').css({
						position: 'absolute',
						color: '#FFFFFF',
						fontSize: '12px',
						width: 35,
						backgroundColor: '#000000',
						margin: 0,
						padding: 2
					}).appendTo("#zoomLayer");
					*/
				},
				onImageChange: function(info){
					// Hilight selected thumb
					jQuery(".outerContainer").css("borderColor", "#CCCCCC");
					jQuery("#thumb_"+info.zoomID).css("borderColor", "#000000");
				}
			};
			
			// Path to axZm folder
			ajaxZoom.path = "../axZm/";
			
			// Define your custom parameter query string
			// By example=18 some settings are overridden in zoomConfigCustom.inc.php
			// zoomData is the php array with images turned into string
			ajaxZoom.parameter = "zoomData=<?php echo $_GET["zoomData"]?>&example=18&zoomID=1"; 
			
			 // The ID of the element where ajax-zoom has to be inserted into
			ajaxZoom.divID = "test"; 
			</script>
			
			<!-- Include the loader file -->
			<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>
			<?php
			
		echo "</DIV>\n";

	echo "</DIV>\n"; // End layout box
	
if (!isset($_GET['iframe'])){
		?>
		<p><b>General:</b> sometimes designers have the need for very simple image zoom feature with 
		extended styling possibilities. Above is an example of AJAX-ZOOM with only basic features enabled. 
		</p>
		
		<p><b>Loading in a lightbox:</b> if you want to show this content in a lightbox, e.g. as an iframe 
		the $zoomData array (see below) needs to be defined in the page from where the lightbox is inited. 
		For demonstration this page has been prepared to strip all unneeded html.<br> 
		<DIV style="border: #CCCCCC 1px dashed; padding: 10px; background-image: url(../axZm/icons/body.gif);">
		<a style="font-size: 200%; text-shadow: 1px 1px 1px #999999;" class="iframe" id="ifrmExample1" href="<?php echo $_SERVER['PHP_SELF'];?>?zoomData=<?php echo $_GET["zoomData"];?>&iframe=1">Click here to load this page in a lightbox</a>.
		</DIV>
		</p>
		
		<p><b>Toolbar:</b> in this example the "native" toolbar is completly disabled. 
		Instead there is a custom div container with some icons in it. 
		Each icon has an onclick event with api function like jQuery.fn.axZm.zoomIn() or jQuery.fn.axZm.zoomOut(). 
		After AJAX-ZOOM is loaded this div container is appended to and positioned inside AJAX-ZOOM with the help of the 
		"onLoad" callback. 
		</p>
		
		<p><b>Image map:</b> unlike in most other examples the image map is positioned outside of AJAX-ZOOM player. 
		This can be done with the option "mapParent" in /axZm/zoomConfig.inc.php or zoomConfigCustom.inc.php; 
		"mapParent" defines the ID of an block element e.g. a DIV on the page. 
		The size of the map is controlled by the option "mapFract".
		</p>
		


		<?php
		$example = 16;
		include('syntax.php');
	
	echo "</DIV>\n";
	
	include('footer.php');
}

echo "
</body>
</html>
";
?>


