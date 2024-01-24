<?php
if(!session_id()){session_start();}
unset ($_SESSION['imageZoom']);
$_SESSION['imageZoom']=array();

// Simulate dynamic configuration
$_GET['example'] = 9;

// Simulate custom parameters
if (!isset($_GET['zoomDir'])){
	$_GET['zoomDir'] = 5;
	$_GET['zoomID'] = 1;
}

// Include the ajax zoom class and all needed php files
/*
$docRoot = $_SERVER['DOCUMENT_ROOT'];
if (substr($docRoot,-1) == '/'){$docRoot = substr($docRoot,0,-1);}
require ($docRoot.'/axZm/zoomInc.inc.php');
*/
require ('../axZm/zoomInc.inc.php');

// This file (example8.php) is also used as ajax target for custom gallery outside the ajax zoom
if ($_GET['newGal']){
	foreach ($zoom['config']['pic_list_array'] as $k=>$v){
		echo "<a href=\"#\" class=\"outerContainer\" onClick=\"jQuery.fn.axZm.zoomSwitch('$k'); return false;\"><DIV class=\"outerimg\" style=\"background-image: url(".$axZmH->composeFileName($zoom['config']['gallery'].$v,$zoom['config']['galleryFullPicDim'],'_').") \"></DIV></a>";
	}
	exit;
}

// The Header, please make sure to use valid doctype for best performance.
// Please note, that ie8 is not bug free, so you better include this line of code into your header:
// <meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\"> 
// This has nothing to do with ajax zoom, Microsoft does it itself on microsoft.com :-)
echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>AJAX-ZOOM inLine Implementation api examples</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">
";
 
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
}
 
/*
// Returns all needed css stylesheets
echo $axZmH->drawZoomStyle($zoom); 
// Returns all needed js files
echo $axZmH->drawZoomJs($zoom, $exclude = array()); 
*/
?>
<link rel="stylesheet" href="../axZm/axZm.css" type="text/css" media="screen">

<style type="text/css" media="screen"> 
	body {height: 100%;}
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}
	
	#picThumbs{
		float:left;
		width: 430px;
	}
	
	.outerimg{
		background-position: center center;
		width: 70px;
		height: 70px;
		margin: 5px 0px 0px 5px;
		background-repeat: no-repeat;
	}
	.outerContainer{
		display: block;
		float: left;
		cursor: pointer; 
		width: 80px;
		height: 80px; 
		margin: 0px 3px 3px 0px;
		border: #444444 1px solid;
		background-color: #E3E3E3;
		-moz-border-radius: 5px;
		-webkit-border-radius: 5px;
		border-radius: 5px 5px 5px 5px;
	}
	
	.p{text-align: justify; text-justify: newspaper;}
	
	
	/* Override some default styles for the demo
	For your application you schould change the css file!	
	*/
	.zoomNavigation{
		background-image: none;
		background-color:#FFFFFF;
	}
	
	.zoomContainer {
		background-color: #FFFFFF;
	}
	
	.zoomLogHolder{
		width: 50px;
		height: 35px;
	}
	
	.zoomLogJustLevel{
		width: 45px;
		color: #444444;
		font-size: 13pt; 
		font-family: Tahoma, Arial;
		margin: 10px 0px 0px 3px;
	}
	
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
	// Example function to load custom gallery over ajax
	function loadNewGal(id){
		jQuery.ajax({
			url: '<?php echo $_SERVER['PHP_SELF'];?>',
			data: 'zoomDir='+id+'&newGal=1',
			success: function (data){
				jQuery('#picThumbs').html(data);
			}
		});		
	}
	
	// The above function is triggered after page is loaded
	jQuery(window).load(function () {
		// Init the gallery
		loadNewGal(<?php echo $_GET['zoomDir'];?>);
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
	
		echo "<h2>AJAX-ZOOM - embedded implementation, jQuery.fn.axZm.zoomSwitch() and jQuery.fn.axZm.loadAjaxSet() demonstration</h2>\n";
	
		echo "<DIV style='clear: both;'>\n";
			echo "<p style='font-size: 120%'>With the method <code>jQuery.fn.axZm.zoomSwitch()</code> it is posiible to switch to a different picture from outside of ajax zoom.  
			With the method <code>jQuery.fn.axZm.loadAjaxSet()</code> it is possible to load a different set of images. Combining this two methods 
			together you can build an extended ajax gallery around the zoom with virtually unlimited number of images!
			";		
				
			echo "<DIV id='zoomInlineContent' style='float: left; margin: 0px 10px 10px 0px;'>"; // background-color: #E5E5E5
			
			// Returns the html for ajax zoom
			echo $axZmH->drawZoomBox($zoom, $zoomTmp);
			
			// Returns dynamic javascript for ajax zoom
			echo $axZmH->drawZoomJsConf($zoom, $rn = false, $pack = true);
			
			// Returns javascript loader that triggers ajax zoom
			echo $axZmH->drawZoomJsLoad($zoom, $pack = true, $windowLoad = true);
			echo "</DIV>";

			/*
			// Clickable list of folders (can be any other parameter)
			echo "<ul>";
			foreach ($zoomTmp['folderArray'] as $k=>$v){
				echo "<a href='#' onClick=\"jQuery.fn.axZm.loadAjaxSet('example=".$_GET['example']."&zoomDir=$k'); loadNewGal($k); return false;\">$v</a> ";
			}
			echo "</ul>";
			*/
			
			echo "<div style='margin-bottom:10px'>";
				echo "<select onChange=\"jQuery.fn.axZm.loadAjaxSet('example=".$_GET['example']."&zoomDir='+this.value); loadNewGal(this.value);\">";
				foreach ($zoomTmp['folderArray'] as $k=>$v){
					echo "<option value='$k'";
					if (isset($_GET['zoomDir'])){
						if ($_GET['zoomDir'] == $k OR $_GET['zoomDir'] == $v){
							echo ' selected';
						}
					}
					echo ">$v</option>";
				}
				echo "</select> - Switch folder with AJAX";
			echo "</div>";
			
			?>
			<div style="text-align: left; margin-bottom: 10px">
			External controls example: 
			<a href="javascript: void(0)" onclick="$.fn.axZm.zoomIn({ajxTo: 750})">zoomIn</a> |  
			<a href="javascript: void(0)" onclick="$.fn.axZm.zoomOut({ajxTo: 750})">zoomOut</a> | 
			<a href="javascript: void(0)" onclick="$.fn.axZm.zoomReset()">reset</a>
			</div>			
			
			<?php			
			
			echo "<DIV id='picThumbs'></DIV>";
			
			echo "</p>";
			
			echo "<DIV style='clear: left;'>";
				$example = 8;
				include('syntax.php');		
			echo "</DIV>";
			
		echo "</DIV>\n";
		
	echo "</DIV>\n";
echo "</DIV>\n";
include('footer.php');
echo "
</body>
</html>
";
?>