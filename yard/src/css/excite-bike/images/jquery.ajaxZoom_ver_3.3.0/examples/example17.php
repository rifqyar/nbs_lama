<?php
if(!session_id()){session_start();}
 
echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>AJAX-ZOOM - image map same size as zoom</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">
";
 
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
}

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

<style type="text/css" media="screen"> 
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}	
	p {text-align: justify; text-justify: newspaper;}
	.zoomHorGalleryDescr{
		display:none;
	}
	#zoomContainer{
		background-color: #FFFFFF;
	}
	
	#zoomMapSel{
		border-color: blue /*#30FF00*/
	}

	#zoomMapSelArea{
		background-color: blue;
	}	
	
</style>

<?php
echo "
</head>
<body>
";
include ('navi.php');
echo "<DIV style='width: 800px; margin: 0px auto;'>\n";
	
	echo "<DIV style='float: left; min-height: 500px; min-width: 760px; background-color: #FFFFFF; border: #CCCCCC 1px dashed; padding: 10px; margin: 5px;'>\n";
	
		echo "<h2>AJAX-ZOOM - outer image map same size as initial image. Autozoom after load enabled.</h2>\n";
		echo "<DIV style='clear: both;'>\n";

		$zoomData[1]['p'] = '/pic/zoom/fashion/';
		$zoomData[1]['f'] = 'test_fashion1.png';
		
		$_GET['zoomData'] = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), '+/=', '-_,');

		
		echo "<DIV style='float: left; width: 362px; height: 542px; margin-right: 20px'>";
			echo "<DIV id='mapContainer' style='position: absolute; width: 362px; height: 542px;'></DIV>";
		echo "</DIV>";
		
		echo "<DIV id='test' style='float: left; width: 362px; height: 542px;'>Loading, please wait...</DIV>";

		echo '<div id="naviReplacement" style="text-align: left; position: absolute; display: none;">
		<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomIn({ajxTo: 750})" style="outline-style: none;"><img src="../axZm/icons/zi_32x32.png" border="0" ></a>
		<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomOut({ajxTo: 750})" style="outline-style: none;"><img src="../axZm/icons/zo_32x32.png" border="0"></a>
		<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomReset()" style="outline-style: none;"><img src="../axZm/icons/zr_32x32.png" border="0"></a>
		</div>';	

		?>
		<script type="text/javascript">
		SyntaxHighlighter.all();
		// Create new object
		var ajaxZoom = {};
		ajaxZoom.opt = {
			onLoad: function(){
				jQuery('#naviReplacement').appendTo('#zoomLayer').css({
					display: 'block',
					left: 10,
					top: 540-32-10,
					zIndex: 111
				});
			}
		};
		// Path to the axZm folder
		ajaxZoom.path = '../axZm/'; 
		
		// Define your custom parameter query string
		// By example=19 some settings are overridden in zoomConfigCustom.inc.php
		// zoomData is the php array with images turned into string
		ajaxZoom.parameter = 'zoomData=<?php echo $_GET['zoomData']?>&example=19'; 
		
		// The id of the Div where ajax-zoom has to be inserted into
		ajaxZoom.divID = 'test'; 
		</script>
		
		<!-- Include the loader file -->
		<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>
		<?php
		echo "</DIV>\n";
		
	echo "</DIV>\n";
	
	$example = 17;
	include('syntax.php');
	
echo "</DIV>\n";
include('footer.php');
echo "
</body>
</html>
";
?>