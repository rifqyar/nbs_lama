<?php
echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>AJAX-ZOOM Lightbox & Co. examples with an iFrame</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">";
 
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
}

echo "
<link rel=\"stylesheet\" href=\"../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.css\" media=\"screen\" type=\"text/css\">
<link rel=\"stylesheet\" href=\"../axZm/plugins/demo/colorbox/example4/colorbox.css\" media=\"screen\" type=\"text/css\">

<script type=\"text/javascript\" src=\"../axZm/plugins/jquery-1.7.2.min.js\"></script>

<script type=\"text/javascript\" src=\"../axZm/plugins/demo/jquery.fancybox/jquery.easing.1.3.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/colorbox/jquery.colorbox.js\"></script>

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

jQuery(document).ready(function() {

	SyntaxHighlighter.all();

	jQuery("#ifrmExample1").fancybox({
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
		frameWidth			: 754,
		frameHeight			: 458
	});
	
	jQuery("#ifrmExample2").fancybox({
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
		frameWidth			: 722,
		frameHeight			: 530
	});
	
	jQuery("#ifrmExample3").fancybox({
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
		frameWidth			: 942,
		frameHeight			: 458
	});
	
	
	jQuery("#ifrmExample4").colorbox({
		width: 804, 
		height: 528, 
		iframe: true,
		scrolling: false
	});

	jQuery("#ifrmExample5").colorbox({
		width: 772, 
		height: 600, 
		iframe: true,
		scrolling: false
	});
	
	jQuery("#ifrmExample6").colorbox({
		width: 992, 
		height: 528, 
		iframe: true,
		scrolling: false
	});
});

</script>
<style type="text/css" media="screen"> 
	body {margin:0px; padding:0px;}
	html {margin:0px; padding:0px; border: 0; font-family: Tahoma, Arial; font-size: 10pt;}
	form {padding:0px; margin:0px}
	h2 {padding:0px; margin: 15px 0px 15px 0px; font-size: 16pt;}
	p {text-align: justify; text-justify: newspaper;}
</style>

<?php
echo "
</head>
<body>
";
include ('navi.php');
echo "<DIV style='width: 800px; margin: 0px auto;'>\n";
	
	echo "<DIV style='float: left; background-color: #FFFFFF; padding: 10px; margin: 5px; background-image: url(http://www.ajax-zoom.com/pic/zoomp/zoom_shot_1.jpg); background-repeat: no-repeat; background-position: 430px 10px;'>\n";
	
		echo "<h2>AJAX-ZOOM Lightbox & Co.<br>examples with an iFrame</h2>\n";
		
		echo "<DIV style='float: left; width: 150px;'>\n";
			echo "<strong>Fancybox</strong><br>";
			echo "<a class=\"iframe\" id='ifrmExample1' href=\"example1.php?zoomDir=4&example=1&iframe=1\">Example 1</a><br>";
			echo "<a class=\"iframe\" id='ifrmExample2' href=\"example1.php?zoomDir=1&example=2&iframe=1\">Example 2</a><br>";
			echo "<a class=\"iframe\" id='ifrmExample3' href=\"example1.php?zoomDir=11&example=3&iframe=1\">Example 3</a>";
		echo "</DIV>\n";
		
		echo "<DIV style='float: left; width: 150px;'>\n";
			echo "<strong>Colorbox</strong><br>";
			echo "<a id='ifrmExample4' href=\"example1.php?zoomDir=4&example=1&iframe=1\">Example 1</a><br>";
			echo "<a id='ifrmExample5' href=\"example1.php?zoomDir=1&example=2&iframe=1\">Example 2</a><br>";
			echo "<a id='ifrmExample6' href=\"example1.php?zoomDir=11&example=3&iframe=1\">Example 3</a>";
		echo "</DIV>\n";
	
		echo "<DIV style='clear: both;'>\n";
			echo "<DIV style='float: right; width: 360px; height: 150px;'></DIV>";
			?>
			<p style="padding-top:20px; font-size:120%">
			This example demonstrates how to open multiple zoom galleries with some lightbox clones (please click on the links above). 
			The content of the iframe in the lightboxes is simply the file of the first example (example1.php)
			Due to "cross scripting" issues lightboxes usually can not identify the size of the content inside an iframe. 
			Therefore you have to specify the size of the popup inside the lightbox options. 
			Fullscreen does not work, because AJAX-ZOOM can not breakout of the iframe. 
			</p>
			<p style="font-size:120%">
			Please note, that not all lightbox clones have the support for iframed content. 
			The ones used in this example are licensed under MIT, so you can use them in your projects as well. 
			If you are going to use a different lightbox clone make sure to remove the scrollbars from the iframe.
			</p>
			<?php
			
			$example = 2;
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