<?php
if(!session_id()){session_start();}
unset ($_SESSION['imageZoom']);
$_SESSION['imageZoom']=array();

$_GET['example'] = 15;

$m=0; 
$zoomData = array();

// Open some dirs
foreach (glob('../pic/zoom/'.'*', GLOB_ONLYDIR) as $folder){
	// Constrain by selected folders
	$selectedFolders = array('boutique', 'furniture', 'objects');
	if (basename($folder) != '..' && basename($folder) != '.' && in_array(basename($folder),$selectedFolders)){
		// Read files
		$handle = opendir($folder);
		while (false !== ($file = readdir($handle))){ 
			if (basename($file) != '..' && basename($file) != '.'){
				$m++;
				// Fill $zoomData with some values
				$zoomData[$m]['p']=basename($folder);
				$zoomData[$m]['f']=basename($file);
			}
		}
		closedir($handle);
	}
}

// Compress PHP array into a string and pass it as $_GET parameter
$_GET['zoomData'] = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), '+/=', '-_,');
 
require ('../axZm/zoomInc.inc.php');

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>Image Zoom load images from multiple directories</title>
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


<link rel="stylesheet" href="../axZm/axZm.css" type="text/css" media="screen">
<style type="text/css" media="screen"> 
	body {height: 100%;}
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}
	p {text-align: justify; text-justify: newspaper;}
	.zoomLogHolder{width: 70px;}
	
</style>

<script type="text/javascript" src="../axZm/plugins/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>
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

</script>

<?php


echo "
</head>
<body>
";
include ('navi.php');
echo "<DIV style='width: 960px; margin: 0px auto;'>\n";
	
	echo "<DIV style='float: left; background-color: rgb(255,255,255); padding: 10px; margin: 5px;'>\n";
	
		echo "<h2>AJAX-ZOOM - embedded implementation, load images from multiple directories</h2>\n";
	
		echo "<DIV style='clear: both;'>\n";
			?>
			<p style="padding-top:20px; font-size:120%">
			Since Ver. 2.1.2 it is possible to load images into the gallery from different locations. 
			Before all images in the gallery had to be under the same path. 
			The <a href="http://www.ajax-zoom.com/demo/magento/" target="_blank">Magento commerce implementation</a> of AJAX-ZOOM uses a similar approach.
			</p>
			
			<?php
			echo "<DIV id='zoomInlineContent' style='margin: 20px 0px 0px 0px;'>"; // background-color: #E5E5E5
			echo $axZmH->drawZoomBox($zoom, $zoomTmp);
			echo $axZmH->drawZoomJsConf($zoom, $rn = false, $pack = true);
			echo $axZmH->drawZoomJsLoad($zoom, $pack = true, $windowLoad = true);
			echo "</DIV>";

			?>
			<div style="text-align: right; margin-bottom: 10px">
			External controls example: 
			<a href="javascript: void(0)" onclick="$.fn.axZm.zoomIn({ajxTo: 750})">zoomIn</a> |  
			<a href="javascript: void(0)" onclick="$.fn.axZm.zoomOut({ajxTo: 750})">zoomOut</a> | 
			<a href="javascript: void(0)" onclick="$.fn.axZm.zoomReset()">reset</a>
			</div>
			
			<p style="padding-top:20px; font-size:120%">
			Additionally the vertical gallery is placed to the left and has four image thumbs in a row - everything adjustable with the 
			<a href="http://www.ajax-zoom.com/index.php?cid=docs#Vertical_Gallery">options</a>.
			Also there is an "<a href="http://www.ajax-zoom.com/index.php?cid=docs#autoZoom">auto zoom effect</a>" 
			applied to the first image (can be changed to all).
			</p>
			<p style="padding-top:20px; font-size:120%">
			In this example images are collected from a couple of folders.
			</p>
			
			<?php
			$example = 12;
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