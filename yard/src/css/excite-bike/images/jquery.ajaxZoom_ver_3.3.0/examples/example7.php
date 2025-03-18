<?php
if(!session_id()){session_start();}
unset ($_SESSION['imageZoom']);
$_SESSION['imageZoom']=array();

$_GET['example'] = 9;

if (!isset($_GET['zoomDir'])){
	$_GET['zoomDir'] = 'fashion';
	$_GET['zoomFile'] = 'suit_2.jpg';
}

require ('../axZm/zoomInc.inc.php');

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>AJAX-ZOOM Photo Zoom external javascript controls</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">
";
 
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
}
 

echo $axZmH->drawZoomStyle($zoom); 
echo $axZmH->drawZoomJs($zoom, $exclude = array()); 
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
	body {height: 100%;}
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}
	.outerimg{
		margin: 0px 5px 3px 0px;
		border: blue 2px solid;
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

<script type="text/javascript">
	SyntaxHighlighter.all();
	jQuery(window).load(function () {

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

		echo "<h2>Ajax Zoom - inLine implementation</h2>\n";
	
		echo "<DIV style='clear: both;'>\n";
			
			echo "<DIV id='zoomInlineContent' style='float: left; margin: 0px 10px 10px 0px;'>"; // background-color: #E5E5E5

			echo $axZmH->drawZoomBox($zoom, $zoomTmp);
			echo $axZmH->drawZoomJsConf($zoom, $rn = false, $pack = true);
			echo $axZmH->drawZoomJsLoad($zoom, $pack = true, $windowLoad = true);
			echo "</DIV>";
			
			echo "<p style='font-size:120%'>With the method <code>jQuery.fn.axZm.zoomSwitch()</code> it is posiible to switch to a different picture from outside of ajax zoom: </p>";
			/*
			foreach ($zoom['config']['pic_list_array'] as $k=>$v){
				echo "<a href=\"#\" onClick=\"jQuery.fn.axZm.zoomSwitch('$k'); return false;\">$v</a> ";
			}
			*/
			echo "<p style='font-size:120%'>Example with picture thumbs:";
			echo "</p>";
			
			echo "<DIV>";
			foreach ($zoom['config']['pic_list_array']as $k=>$v){
				echo "<a href=\"#\" onClick=\"jQuery.fn.axZm.zoomSwitch('$k'); return false;\"><img src='".$axZmH->composeFileName($zoom['config']['gallery'].$v, $zoom['config']['galleryFullPicDim'],'_')."' border='0' class='outerimg'></a>";
			}
			echo "</DIV>";
			
			echo "<DIV style='text-align: center'>
				<input type='button' value='<<' onClick=\"jQuery.fn.axZm.zoomPrevNext('prev')\">&nbsp;&nbsp;
				<input type='button' value='>>' onClick=\"jQuery.fn.axZm.zoomPrevNext('next')\">
			</DIV>";
			echo "<DIV><p style='font-size:120%'>Switch to next, prev image with the method <code>jQuery.fn.axZm.zoomPrevNext()</code></p></DIV>";
			
			?>
			<div style="text-align: left; margin-bottom: 10px">
			External controls example: 
			<a href="javascript: void(0)" onclick="$.fn.axZm.zoomIn({ajxTo: 750})">zoomIn</a> |  
			<a href="javascript: void(0)" onclick="$.fn.axZm.zoomOut({ajxTo: 750})">zoomOut</a> | 
			<a href="javascript: void(0)" onclick="$.fn.axZm.zoomReset()">reset</a>
			</div>			
			
			<?php	

			// An other example
			/*
			echo "<ul>";
			foreach ($zoomTmp['folderArray'] as $k=>$v){
				echo "<li><a href='#' onClick=\"jQuery.fn.axZm.loadAjaxSet('example=".$_GET['example']."&zoomDir=$k'); return false;\">$v</a></li>";
			}
			echo "</ul>";
			*/
			echo "<DIV style='clear: both'>";
				$example = 7;
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