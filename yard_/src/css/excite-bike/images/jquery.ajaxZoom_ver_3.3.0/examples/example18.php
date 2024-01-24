<?php
if(!session_id()){session_start();}
 
echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>AJAX-ZOOM short tutorial 2</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">
";
 
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
}


?>

<style type="text/css" media="screen"> 
	body {height: 100%;}
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}
	.zoomHorGalleryDescr{display: none;}
</style>
<link rel="stylesheet" href="../axZm/axZm.css" type="text/css" media="screen">

<script type="text/javascript" src="../axZm/plugins/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>

<?php

// not needed - this is just for code formating
echo "
<link href=\"../axZm/plugins/demo/syntaxhighlighter/styles/shCore.css\" type=\"text/css\" rel=\"stylesheet\" />
<link href=\"../axZm/plugins/demo/syntaxhighlighter/styles/shThemeCustom.css\" type=\"text/css\" rel=\"stylesheet\" />
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/src/shCore.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushJScript.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushPhp.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushCss.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushXml.js\"></script>
<script type=\"text/javascript\">
SyntaxHighlighter.all();
</script>
";
 
echo "
</head>
<body>
";

include ('navi.php');

echo "<DIV style='width: 620px; margin: 0px auto;'>\n";
	
	echo "<DIV style='float: left; background-color: rgb(255,255,255); padding: 10px; margin: 5px;'>\n";
	
		echo "<h2>AJAX-ZOOM - short tutorial 2</h2>\n";
	
		echo "<DIV style='clear: both;'>\n";

			?>
			<p style="padding-top:20px;">
 			This example can be seen a short tutorial on how you could quickly define one or more images and embed AJAX-ZOOM into your application. 
			By passing / defining the query string parameter <code>$_GET['example'] = 20;</code> (in source code) some default settings from /axZm/zoomConfig.inc.php 
			are overridden in /axZm/zoomConfigCustom.inc.php after <code>elseif ($_GET['example'] == 20){</code> 
			So if changes in /axZm/zoomConfig.inc.php have no effect look for the same options /axZm/zoomConfigCustom.inc.php; 
			</p>
			
			<p>
			Thus in /axZm/zoomConfigCustom.inc.php after <code>elseif ($_GET['example'] == 20){</code> you could for example set: 
			<ul>
				<li><code>$zoom['config']['picDim']</code> - inner size of the player.</li>
				<li><code>$zoom['config']['useHorGallery']</code> - enable / disable horizontal gallery.</li>
				<li><code>$zoom['config']['useGallery']</code> - enable / disable vertical gallery.</li>
				<li><code>$zoom['config']['displayNavi']</code> - enable / disable navigation bar.</li>
				<li><code>$zoom['config']['innerMargin']</code> - border width around the player.</li>
				<li>and many others...</li>
			</ul>
			</p>

			<?php
			// Select a set of custom settings in zoomConfigCustom.inc.php
			$_GET['example'] = 20; 
			
			// Define the images directly here
			// There can be just one or more...
			$zoomData = array();
			
			$zoomData[1]['p'] = '/pic/zoom/animals/'; // Path to image
			$zoomData[1]['f'] = 'test_animals1.png'; // Image filename
			
			$zoomData[2]['p'] = '/pic/zoom/animals/';
			$zoomData[2]['f'] = 'test_animals2.png';

			$zoomData[3]['p'] = '/pic/zoom/boutique/';
			$zoomData[3]['f'] = 'test_boutique1.png';

			$zoomData[4]['p'] = '/pic/zoom/boutique/';
			$zoomData[4]['f'] = 'test_boutique2.png';
			
			$zoomData[5]['p'] = '/pic/zoom/boutique/';
			$zoomData[5]['f'] = 'test_boutique3.png';

			$zoomData[6]['p'] = '/pic/zoom/estate/';
			$zoomData[6]['f'] = 'test_estate1.png';
	
			$zoomData[7]['p'] = '/pic/zoom/estate/';
			$zoomData[7]['f'] = 'test_estate2.png';
			
			$zoomData[8]['p'] = '/pic/zoom/estate/';
			$zoomData[8]['f'] = 'test_estate3.png';	

			$zoomData[9]['p'] = '/pic/zoom/random/';
			$zoomData[9]['f'] = 'test_random1.png';
	
			$zoomData[10]['p'] = '/pic/zoom/random/';
			$zoomData[10]['f'] = 'test_random2.png';
			
			$zoomData[11]['p'] = '/pic/zoom/random/';
			$zoomData[11]['f'] = 'test_random3.png';	
		
			// Turn above array into string
			$_GET['zoomData'] = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), '+/=', '-_,');
			
			// Include all classes etc.
			require ('../axZm/zoomInc.inc.php');
			
			// Html output
			echo $axZmH->drawZoomBox($zoom, $zoomTmp);
			
			// JS config parameters from zoomConfig.inc.php and zoomConfigCustom.inc.php
			echo $axZmH->drawZoomJsConf($zoom, $rn = false, $pack = true);
			
			// JS load AJAX-ZOOM
			echo $axZmH->drawZoomJsLoad($zoom, $pack = true, $windowLoad = true);

			$example = 18;
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