<?php
if(!session_id()){session_start();}
unset ($_SESSION['imageZoom']);
$_SESSION['imageZoom']=array();

// Simulate initial values for demo
$_GET['example'] = 2; // layout
$_GET['zoomDir'] = 2; // folder with images

require ('../axZm/zoomInc.inc.php');
 
 
 // On the fly generation of thumbs
if (isset($_GET['previewPic'])){
	ob_start();
	$path = $zoom['config']['picDir'];

	$w = 180;
	$h = 180;
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
		$axZm->rawThumb($zoom, $path, urldecode($_GET['previewPic']), round($ww), round($hh), 90, true);
	}
	ob_end_flush();
	exit;
}
 
echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>Ajax Zoom Demo Alternative Navi</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">";
 
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
}
 
// Include all needed css for zoom
echo $axZmH->drawZoomStyle($zoom); 

// Include css for colorbox plugin
echo "
<link rel=\"stylesheet\" href=\"../axZm/plugins/demo/colorbox/example4/colorbox.css\" media=\"screen\" type=\"text/css\">
<link rel=\"stylesheet\" href=\"../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.css\" media=\"screen\" type=\"text/css\">
";

// Include all needed js for zoom
echo $axZmH->drawZoomJs($zoom, $exclude = array()); 

// Include colorbox and jqDock plugins
echo "
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/colorbox/jquery.colorbox.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/jquery.jqDock/jquery.jqDock.js\"></script>
";

echo "
<link href=\"../axZm/plugins/demo/syntaxhighlighter/styles/shCore.css\" type=\"text/css\" rel=\"stylesheet\" />
<link href=\"../axZm/plugins/demo/syntaxhighlighter/styles/shThemeCustom.css\" type=\"text/css\" rel=\"stylesheet\" />
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/src/shCore.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushJScript.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushPhp.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushCss.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushXml.js\"></script>
";

?>
<script type="text/javascript">
function setColorBox(){
	/*Init colorbox on thumbnails
	Please note the callback: jQuery.fn.axZm(); that have to be 
	trigered after each load of colorbox or a differen "lightbox" implementation 
	*/

	jQuery(".thumbDemoLink").unbind().colorbox({
		initialWidth: 300,
		initialHeight: 300,
		scrolling: false,
		scrollbars: false,
		opacity: 0.95,
		preloadIMG: false
	}, function(){jQuery.fn.axZm();});
}

function setFancyBox(){
	// Attach fancybox to all links with class='thumbDemoLink'
	jQuery(".thumbDemoLink").unbind().fancybox({
		padding				: 0,
		overlayShow			: true,
		overlayOpacity		: 0.6,
		zoomSpeedIn			: 0,
		zoomSpeedOut		: 100,
		easingIn			: "swing",
		easingOut			: "swing",
		hideOnContentClick	: false, // Important
		centerOnScroll		: false,
		imageScale			: true,
		autoDimensions		: true,
		callbackOnShow		: function(){
			jQuery.fn.axZm();						
		}
	});	
}
jQuery(document).ready(function(){
	SyntaxHighlighter.all();
	setFancyBox();

	// jqDock menu
	jQuery('#menu1').jqDock({
		align: 'right',
		size: 70,
		distance: 90
	});

});
</script>
<style type="text/css" media="screen"> 
	body {margin:0px; padding:0px; background-image: none; }
	html {margin:0px; padding:0px; border: 0; font-family: Tahoma, Arial; font-size: 10pt;}
	form {padding:0px; margin:0px}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}
	.thumbDemoLink{}
	#menu1 {position: static; }
	#menu1 img {padding:0px 4px;}
	#menu1 div.jqDock {}

</style>

<?php
echo "
</head>
<body>
";
include ('navi.php');
echo "<DIV style='width: 800px; margin: 0px auto;'>\n";
	
	echo "<DIV style='float: left; width: 770px; background-color: #FFFFFF; padding: 10px; margin: 5px;'>\n";
	
		echo "<h2>AJAX-ZOOM usage with other plugins</h2>\n";

			echo "<div style='padding:10px;'>
			<input type='radio' name='selPlugin' onClick='return setFancyBox();' checked> - Fancybox
			<input type='radio' name='selPlugin' onClick='return setColorBox();'> - Colorbox 
			</div>";
			
			echo "<p style='font-size: 120%'>
			We thought it is a nice plug-in to combine AJAX-ZOOM with...
			</p>";

			echo " <div id='menu1'>";
			foreach ($pic_list_array as $k=>$v){
				// Apply a filter (disabled)
				//if (stristr($v,'bag')){
					echo "<a class='thumbDemoLink' href=\"../axZm/zoomLoad.php?zoomLoadAjax=1&zoomID=".$k."&zoomDir=".$_GET['zoomDir']."&example=".$_GET['example']."\">";
						/* Make use of thumbs generated for the gallery */
						//echo "<img src='".$axZmH->composeFileName($zoom['config']['gallery'].$v,'180x180','_')."' alt='' title=''>";
						
						/* Create thumbs on the fly, $zoom['config']['allowDynamicThumbs'] must be true, on default it is false */
						//echo "<img src='../axZm/zoomLoad.php?width=180&height=180&qual=90&previewPic=".$v."&previewDir=".$zoom['config']['pic']."' alt='' title=''>";
						
						/* Create thumbs on the fly with this file itself */
						echo "<img src='".$_SERVER['PHP_SELF']."?previewPic=".$v."' alt='' title=''>";
					echo "</a>";
				//}
			}
			echo "</div>";
			
			echo "<div style='padding-left:100px'>";
			$example = 6;
			include('syntax.php');				
				
				
			echo "</div>";
	echo "</DIV>\n";
echo "</DIV>\n";
include('footer.php');
echo "
</body>
</html>
";
?>