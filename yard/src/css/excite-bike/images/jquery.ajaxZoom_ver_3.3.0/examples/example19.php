<?php
if(!session_id()){session_start();}

// Not needed if remove $zoom['config']['installPath'] from ajaxZoom.parameter string below...
require ('../axZm/zoomInc.inc.php');

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>AJAX-ZOOM high resolution animations</title>
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
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}
	p {text-align: justify; text-justify: newspaper;}
	
	.rbox{
		-moz-border-radius: 10px;
    	-webkit-border-radius: 10px;
    	border-radius: 10px 10px 10px 10px;
	}
	#zoomGalHorArea{background-color: #191919}
	#zoomGalHorDiv{background-color: #191919}
	#zoomGalHorCont{background-color: #191919}
	#zoomGalHor{background-color: #191919}
	
	
</style>
<!-- jQuery core, needed! -->
<script type="text/javascript" src="../axZm/plugins/jquery-1.7.2.min.js"></script>

<!-- AJAX-ZOOM core, not needed here, will be loaded dynamically if not present -->
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>

<!-- Javascript to style the syntax, not needed! -->
<link href="../axZm/plugins/demo/syntaxhighlighter/styles/shCore.css" type="text/css" rel="stylesheet" />
<link href="../axZm/plugins/demo/syntaxhighlighter/styles/shThemeCustom.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/src/shCore.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushJScript.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushPhp.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushCss.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushXml.js"></script>
 
<script type="text/javascript">
	// SyntaxHighlighter is just for the demo to show the source code, should be removed!
	SyntaxHighlighter.all();
</script>

<?php
echo "
</head>
<body>
";
include ('navi.php');
echo "<DIV style='width: 620px; margin: 0px auto;'>\n";
	
	echo "<DIV style='float: left; background-color: rgb(255,255,255); padding: 10px; margin: 5px;'>\n";
	
		echo "<h2>AJAX-ZOOM - high resolution animations</h2>\n";
	
		echo "<DIV style='clear: both;'>\n";
			?>
			<p style="padding-top:20px;">
			This animation example derivates from the lately introduced 360 degree functionality. 
			The mouse / touch spin feature is turned off. 
			Users navigate over the UI slider and by clicking on some selected key frames from the animation, which are loaded into horizontal gallery. 
			Choosing the vertical gallery instead is also possible and is a matter of changing an option in the config file. 
			One of the new features is the play / pause button for the animation. 
			It could also be turned on for standard 360 spin. 
			All you need to load the animation is to pass the directory path (3dDir) with high resolution images to AJAX-ZOOM as a query string parameter (see below). 
			Image processing including the generation of image tiles is done on-the-fly during the first load in the browser.
			</p>
			
			<DIV class='rbox' style='width: 600px; min-height: 400px; padding: 10px; background-color: #818181'>
				<DIV id='test' style='margin: 0px; width: 600px; min-height: 400px;'>Loading, please wait...</DIV>
			</DIV>
			
			
			<script type="text/javascript">
			
			// Create new object
			var ajaxZoom = {}; 
			
			ajaxZoom.opt = {
				// Change some CSS, not needed
				onBeforeStart: function(){
					jQuery('.zoomContainer').css({backgroundColor: '#000000'});
					jQuery('.zoomLogHolder').css({width: 70});			
				}
			};
			
			// Define the path to the axZm folder
			ajaxZoom.path = "../axZm/"; 
			
			// Define your custom parameter query string
			// zoomCueFrames are the filter for key frames showing in the gallery
			// example=21 -> overwrites some default parameter in zoomConfigCustom.inc.php after elseif ($_GET['example'] == 21)
			ajaxZoom.parameter = "zoomCueFrames=1,3,5,8,10,12&example=21&3dDir=<?php echo $zoom['config']['installPath'];?>/pic/zoom3d/Uvex_Occhiali"; 
			
			// The ID of the element where ajax-zoom has to be inserted into
			ajaxZoom.divID = "test";
			</script>
			
			<!-- Include the loader file -->
			<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>

			
			<script type="text/javascript">
				// These js functions are just for the demo, they are not needed fox the example
				var setNaviStatus = function(q){
					if(jQuery.axZm){
						if (q===false){
							jQuery('#zoomNavigation').css('display', 'none');
							jQuery.fn.axZm.switchSpin(true);
						} else{
							jQuery('#zoomNavigation').css('display', 'block');
						}
					}
				}
				
				var reverseSpin = function(){
					if(jQuery.axZm){
						if (jQuery.axZm.spinReverse === true){
							jQuery.axZm.spinReverse = false;
						}else{
							jQuery.axZm.spinReverse = true;
						}
					}
				}
				
				var setZoomSlider = function(q){
					if (q === false){
						jQuery('#zoomSliderZoomContainer').css('visibility', 'hidden');
					} else {
						jQuery('#zoomSliderZoomContainer').css('visibility', 'visible');
					}
				}
				
				var setSpinSlider = function(q){
					if (q === false){
						jQuery('#zoomSliderSpinContainer').css('display', 'none');
					} else {
						jQuery('#zoomSliderSpinContainer').css('display', 'block');
					}
				}
			</script>
 
			<div style="margin-top: 15px ">
				A couple selected parameters which can visually be changed in this example 
				(more parameters in the <a href="http://www.ajax-zoom.com/index.php?cid=docs">online documentation</a>):
				<ul>
					<li>
					<span style="width: 100px; float: left;">Navigation bar: </span>
					<a href="javascript: void(0)" onclick="setNaviStatus(false)">disable</a> | 
					<a href="javascript: void(0)" onclick="setNaviStatus(true)">enable</a>
					</li>
					<li>
					<span style="width: 100px; float: left;">Zoom slider: </span>
					<a href="javascript: void(0)" onclick="setZoomSlider(false)">disable</a> |  
					<a href="javascript: void(0)" onclick="setZoomSlider(true)">enable</a>
					</li>
					<li>
					<span style="width: 100px; float: left;">Spin slider: </span>
					<a href="javascript: void(0)" onclick="setSpinSlider(false)">disable</a> |
					<a href="javascript: void(0)" onclick="setSpinSlider(true)">enable</a>  
					</li>
				</ul>
			</div>
			<?php
			
			$example = 19;
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