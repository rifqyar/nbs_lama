<?php
if(!session_id()){session_start();}
unset ($_SESSION['imageZoom']);
$_SESSION['imageZoom']=array();

$_GET['example'] = 14;

if (!isset($_GET['zoomDir'])){
	$_GET['zoomDir'] = 'high_res';
	$_GET['zoomFile'] = 'slr_test.jpg';
}

/*
$docRoot = $_SERVER['DOCUMENT_ROOT'];
if (substr($docRoot,-1) == '/'){$docRoot = substr($docRoot,0,-1);}
require ($docRoot.'/axZm/zoomInc.inc.php');
*/

require ('../axZm/zoomInc.inc.php');


echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>Image Zoom Javascript PHP load tiles</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">
";
 
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
}

//echo $axZmH->drawZoomStyle($zoom); 
//echo $axZmH->drawZoomJs($zoom, $exclude = array()); 

?>

<style type="text/css" media="screen"> 
	body {height: 100%;}
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}
	p {text-align: justify; text-justify: newspaper;}
</style>
<link rel="stylesheet" href="../axZm/axZm.css" type="text/css" media="screen">
<link rel="stylesheet" href="../axZm/plugins/demo/lavalamp/lavalamp_test.css" type="text/css" media="screen">

<script type="text/javascript" src="../axZm/plugins/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>

<script type="text/javascript" src="../axZm/plugins/demo/lavalamp/jquery.lavalamp.js"></script>

<?php

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
	SyntaxHighlighter.all();
	
	function submitNewZoom(menuItem){
		var id = jQuery(menuItem).attr('id').split('zoomSet').join('');
		if (id){
			var data = 'example=<?php echo $_GET['example'];?>&zoomDir='+id;
			jQuery.fn.axZm.loadAjaxSet(data);
		}	
	}
	
	jQuery(window).load(function () {
		
		jQuery("#lavalampMenu").lavaLamp({
			fx: "easeOutBack",
			speed: 750,
			click: function(event, menuItem) {
				submitNewZoom(menuItem);
				return false;
			}
		});

	});

</script>

<?php


echo "
</head>
<body>
";
include ('navi.php');
echo "<DIV style='width: 800px; margin: 0px auto;'>\n";
	
	echo "<DIV style='float: left; background-color: rgb(255,255,255); padding: 10px; margin: 5px;'>\n";
	
		echo "<h2>AJAX-ZOOM - embedded implementation, load image tiles directly</h2>\n";
	
		echo "<DIV style='clear: both;'>\n";
			//echo "<DIV style='float: right; width: 360px; height: 200px;'></DIV>";
			?>
			<p style="padding-top:20px; font-size:120%">
			This example is basically the same as <a href="example4.php">this one</a>, except that the image tiles are loaded directly. 
			Try to zoom in and then pan around. The image tiles are loaded as You pan.
			</p>
			
			<?php
			echo "<DIV id='zoomInlineContent' style='margin: 20px 0px 0px 0px; padding: 6px; background-image: url(../axZm/icons/back_inline.png); background-repeat: no-repeat;'>"; // background-color: #E5E5E5
			echo $axZmH->drawZoomBox($zoom, $zoomTmp);
			echo $axZmH->drawZoomJsConf($zoom, $rn = false, $pack = true);
			echo $axZmH->drawZoomJsLoad($zoom, $pack = true, $windowLoad = true);
			echo "</DIV>";
			
			echo "<DIV style='background-image: url(../axZm/icons/back_lava.png); background-repeat: no-repeat;'><ul class=\"lavaLampNoImageZoom\" id=\"lavalampMenu\">";
				foreach ($zoomTmp['folderArray'] as $k=>$v){
					echo "<li id=\"zoomSet$k\"";
					if ($k == $_GET['zoomDir'] OR $v == $_GET['zoomDir']){echo " class=\"current\"";}
					echo "><a href=\"#\">$v</a></li>";
				}
			echo "</ul></DIV>";
			?>
			<p style="padding-top:20px; font-size:120%">
			The key differences are the new configuration parameters (zoomConfig.inc.php) introduced in Ver. 2.0.0 and listed below. 
			Also the old parameter $zoom['config']['pyrQual'] - the jpg compression level of the image tiles, should be set somewhat less than 100. 
			That's it. 
			</p>
			
			<?php
			$example = 11;
			include('syntax.php');					

		echo "</DIV>\n";
		
	echo "</DIV>\n";
echo "</DIV>\n";
include('footer.php');
echo "
</body>
</html>
";
?>