<?php
if(!session_id()){session_start();}
?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN\" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>AJAX-ZOOM short tutorial 2 - no PHP / ASP.NET</title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="imagetoolbar" content="no">

<?php
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "
	<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "
	<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
} 

// Only needed for the example to format the code...
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

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<style type="text/css" media="screen"> 
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}	
	p {text-align: justify; text-justify: newspaper;}
	#zoomLogHolder{width: 55px;}
						
</style>

</head>
<body>

<?php
include ('navi.php');
?>

<DIV style="width: 800px; margin: 0px auto;">
	
	<DIV style="float: left; min-width: 752px; background-color: rgb(255,255,255); padding: 10px; margin: 5px;">
	
		<h2>AJAX-ZOOM - short tutorial 1</h2>

		<p style="width: 730px">
		This example does not require PHP codes and could be also inserted with an WYSIWYG editor into any content. 
		All you have to do is to define ajaxZoom.parameter string with paths to the source images. 
		</p>
		
		<p>
		By defining the query string parameter in ajaxZoom.parameter example=2 some default settings from /axZm/zoomConfig.inc.php 
		are overridden in /axZm/zoomConfigCustom.inc.php after <code>elseif ($_GET['example'] == 2){</code> 
		So if changes in /axZm/zoomConfig.inc.php have no effect look for the same options /axZm/zoomConfigCustom.inc.php; 
		</p>
		
		<p>
		Thus in /axZm/zoomConfigCustom.inc.php after <code>elseif ($_GET['example'] == 2){</code> you could for example set: 
		<ul>
			<li><code>$zoom['config']['picDim']</code> - inner size of the player.</li>
			<li><code>$zoom['config']['useHorGallery']</code> - enable / disable horizontal gallery.</li>
			<li><code>$zoom['config']['useGallery']</code> - enable / disable vertical gallery.</li>
			<li><code>$zoom['config']['displayNavi']</code> - enable / disable navigation bar.</li>
			<li><code>$zoom['config']['innerMargin']</code> - border width around the player.</li>
			<li>and many others...</li>
		</ul>
		</p>

		<!-- Div where AJAX-ZOOM is loaded into -->
		<DIV id="test" style="min-height: 462px; margin: 0; clear: both;">Loading, please wait...</DIV>
	

		
		<h3>The code</h3>
		<?php
		echo "<pre class='brush: js; html-script: true'>";
		
		echo htmlspecialchars ('
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
		<head>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.3/jquery.min.js"></script>
		</head>
		<body>
		<!-- Div where AJAX-ZOOM is loaded into -->
		<DIV id="test" style="min-height: 462px; margin: 0; clear: both;">Loading, please wait...</DIV>
		
		<script type="text/javascript">
		// Create new object
		var ajaxZoom = {}; 

		// Define the path to the axZm folder
		ajaxZoom.path = "../axZm/"; 
		
		// Define your custom parameter query string
		ajaxZoom.parameter = "example=2&zoomData=/pic/zoom/furniture/test_furniture1.png|/pic/zoom/furniture/test_furniture2.png|/pic/zoom/boutique/test_boutique1.png|/pic/zoom/boutique/test_boutique2.png"; 
		
		// The ID of the element where ajax-zoom has to be inserted into
		ajaxZoom.divID = "test";
		
		</script>
		<!-- Insert the loader file that will take the above settings (ajaxZoom) and load the player -->
		<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>
		</body>
		</html>
		');
		echo "</pre>";		
		?>
	</DIV>
</DIV>
			
		<script type="text/javascript">
		// Only needed to format the code
		SyntaxHighlighter.all();

		// Create new object
		var ajaxZoom = {}; 

		// Define the path to the axZm folder
		ajaxZoom.path = "../axZm/"; 
		
		// Define your custom parameter query string
		ajaxZoom.parameter = "example=2&zoomData=/pic/zoom/furniture/test_furniture1.png|/pic/zoom/furniture/test_furniture2.png|/pic/zoom/boutique/test_boutique1.png|/pic/zoom/boutique/test_boutique2.png"; 
		
		// The ID of the element where ajax-zoom has to be inserted into
		ajaxZoom.divID = "test";
		
		</script>
		<!-- Insert the loader file that will take the above settings (ajaxZoom) and load the player -->
		<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>
		<?php

include('footer.php');
echo "
</body>
</html>
";
?>