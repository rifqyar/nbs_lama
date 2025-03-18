<?php
if(!session_id()){session_start();}

$_GET['zoomID'] = 1;
$_GET['zoomDir'] = 6;

// The following function is crap to produce the "lorem impsum" text as placeholder
function lorem($abs = 999){
	$return = '';
	$filename = 'lorem.txt';
    $ini_handle = fopen($filename, "r");
    $ini_contents = fread($ini_handle, filesize($filename));
	$ini_contents = nl2br ($ini_contents);
	$array = explode('<br />',$ini_contents);
	$n=0;
	foreach ($array as $text){
		$n++;
		if ($n <= $abs){
			$return.="<p>$text</p>\n";
		} else{
			break;
		}
	}
	return $return;
}

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>AJAX-ZOOM - embed with custom loader</title>
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
</style>

<?php
echo "
</head>
<body>
";
include ('navi.php');
echo "<DIV style='width: 800px; margin: 0px auto;'>\n";
	
	echo "<DIV style='float: left; min-width: 770px; background-color: rgb(255,255,255); padding: 10px; margin: 5px;'>\n";
	
		echo "<h2>AJAX-ZOOM - embed with custom loader</h2>\n";
		echo "<DIV style='clear: both;'>\n";
		echo "<DIV id='test' style='float: right; width:430px; height: 442px; margin: 0px 0px 10px 10px'>Loading, please wait...</DIV>";
		echo lorem(3);
		
		echo "<h4>Embed AJAX-ZOOM with custom loader:</h4>";
		echo "<pre class='brush: js; html-script: true'>";
		echo htmlspecialchars ('
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
		<head>
		<!-- jQuery is not required, as it will be initialized by the axZm.loader.js -->
		</head>
		<body>
		<div id="test">This text will be replaced after AJAX-ZOOM is loaded</div>
		<script type="text/javascript">
		var ajaxZoom = {}; // New object
		ajaxZoom.path = "../axZm/"; // Path to the axZm folder
		ajaxZoom.parameter = "zoomFile=bedroom_3d.jpg&zoomDir=furniture&example=13"; // Your custom parameter
		ajaxZoom.divID = "test"; // The id of the element where ajax-zoom has to be inserted into
		ajaxZoom.opt = {}; // No options, see api jQuery.fn.axZm (options)
		</script>
		<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>
		</body>
		</html>
		');
		echo "</pre>";	
		
		?>
		<script type="text/javascript">
		SyntaxHighlighter.all();
		var ajaxZoom = {};
		ajaxZoom.path = '../axZm/'; // Path to the axZm folder
		ajaxZoom.parameter = 'zoomID=<?php echo $_GET['zoomID']?>&zoomDir=<?php echo $_GET['zoomDir']?>&example=11'; // Needed parameter
		ajaxZoom.divID = 'test'; // The id of the Div where ajax-zoom has to be inserted
		</script>
		<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>
		<?php
		echo "</DIV>\n";
		
	echo "</DIV>\n";
echo "</DIV>\n";
include('footer.php');
echo "
</body>
</html>
";
?>