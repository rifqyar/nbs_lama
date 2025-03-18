<?php
if(!session_id()){session_start();}


if ((isset($_GET['previewPic']) && isset($_GET['previewDir'])) || isset($_GET['firstPicDir'])){
	$noObjectsInclude = true;
}

require ('../axZm/zoomInc.inc.php');


// A helper function to show first image of subfolders on the fly
function firstPicDir($dir){
	$return = '';
	$docRoot = $_SERVER['DOCUMENT_ROOT'];
	if (substr($docRoot,-1) == '/'){$docRoot = substr($docRoot,0,-1);}
	
	$files = array();
	$folders = array();
	
	// Open some dirs
	foreach (glob($docRoot.$dir.'*', GLOB_ONLYDIR) as $folder){
		if (basename($folder) != '..' && basename($folder) != '.'){
			$folders[] = basename($folder);
			$filesArray = scandir($folder);
			$files[] = $filesArray[2];
		}
	}

	// $return .= '<ul id="mycarousel">';
	
	foreach ($folders as $k=>$v){	
		//$return .= '<li>';
		$return .= '<a class="outerContainer" onClick="submitNewZoom(\''.$dir.$v.'\');">';
		$return .= "<div class='outerimg' style='background-image: url(".$_SERVER['PHP_SELF']."?previewPic=".$files[$k]."&previewDir=".$v.")'></div>";
		$return .= '</a>';
		//$return .= '</li>';
	}
	
	//$return .= '</ul>';
	
	return $return;
}

if (isset($_GET['firstPicDir'])){
	echo firstPicDir($zoom['config']['installPath'].'/pic/zoom3d/');
	exit;
}

// Show an image on the fly
if (isset($_GET['previewPic']) && isset($_GET['previewDir'])){
	ob_start();
	$path = $axZmH->checkSlash($zoom['config']['fpPP'].$zoom['config']['installPath'].'/pic/zoom3d/'.urldecode($_GET['previewDir']),'add');
	$w = 62;
	$h = 62;
	$fillThumb = false;
	
	$ww = $w;
	$hh = $h;
	
	if ($fillThumb){
		$ratio = 1;
		$imgSize = getimagesize($path.urldecode($_GET['previewPic']));
		if ($imgSize[0] > $imgSize[1]){
			$ratio = $imgSize[0] / $imgSize[1];
		} elseif ($imgSize[1] > $imgSize[0]){
			$ratio = $imgSize[1] / $imgSize[0];
		}
		$ww = $ww * $ratio;
		$hh = $hh * $ratio;
	}
	
	if ($axZmH->isValidPath($path)){
		$axZm->rawThumb($zoom, $path, urldecode($_GET['previewPic']), round($ww), round($hh), 100, true);
	}
	ob_end_flush();
	exit;
}





echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>AJAX-ZOOM dynamic width and height liquid layout design javascript</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">
";
 
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
}

?>
<link rel="stylesheet" href="../axZm/axZm.css" type="text/css" media="screen">
<script type="text/javascript" src="../axZm/plugins/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>

<style type="text/css" media="screen"> 
	body {height: 100%; overflow: hidden;}
	html {font-family: Tahoma, Arial; font-size: 10pt; overflow: hidden;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}
	.zoomHorGalleryDescr{display: none;}
	
	#zoomContainer{
		background-color: #FFFFFF;
	}

	.zFsO{
		background-color: #FFFFFF;
	}
 
	#nav{
		float: left;
		width: 225px;
		background-color: #BABABA;
		border-right: #808080 solid 1px;
		padding: 5px 0px 0px 5px;
		overflow: hidden;
		overflow-y: auto;
	}
	
	#content{
		height: 150px;
		float: right;
		background-color: #FFFFFF;
	}
	
	#footer{
		clear: both;
	}

	.outerimg{
		background-position: center center;
		width: 62px;
		height: 62px;
		margin: 1px 0px 0px 1px;
		background-repeat: no-repeat;
	}
	
	.outerContainer{
		display: block;
		float: left;
		cursor: pointer; 
		width: 64px;
		height: 64px; 
		margin: 0px 5px 5px 0px;
		background-color: #E3E3E3;
		outline: none;
	}
</style>

<script type="text/javascript">
var adjustHeight = function(){
	var a = jQuery(window).height() - jQuery('#header').height() - jQuery('#footer').height() - 1;
	jQuery('#content').css({height: a, width: jQuery(window).width() - jQuery('#nav').width() - parseInt(jQuery('#nav').css('paddingLeft')) - parseInt(jQuery('#nav').css('paddingRight')) - parseInt(jQuery('#nav').css('border-right-width'))-1});
	jQuery('#nav').css('height', a);
}

jQuery(document).ready(function(){
	 adjustHeight();
	 
	 jQuery(window).bind('resize', adjustHeight);

		// Load the top slider with 360 objects after the first spin is loaded
		var abc = function(t){
			setTimeout(function(){
				var check = false;
				
				// Waiting for jQuery.axZm.spinPreloaded var to appear
				if (jQuery.axZm){
					if (jQuery.axZm.spinPreloaded){
						check = true;
					}
				}
				
				// If jQuery.axZm.spinPreloaded load the slider
				if (check){
					jQuery.ajax({
						// Query the same file and trigger firstPicDir PHP function
						// firstPicDir and its call should be outsourced into some other file in real application
						// "Show an image on the fly" call should be outsourced too
						// change the url: value below
						url: '<?php echo $_SERVER['PHP_SELF']?>',
						data: 'firstPicDir=1',
						dataType: 'html',
						cache: false,
						success: function (data){	
							// Load the html return into container
							jQuery('#nav').html(data);
							
							// Init the jcarousel slider
							//jQuery('#mycarousel').jcarousel();
						}
					});

				}else{
					abc(500); 
				}
			}, t);
		};	
		
		abc(200);
	 
	 
});

// Function for changing the spin object
function submitNewZoom(path){
	if (path){
		var data = 'example=17&3dDir='+path;
		jQuery.fn.axZm.loadAjaxSet(data);
	}	
}
</script>
 
 
</head>
<body>
 
	<div id="header"><?php include ('navi.php');?></div>
	<div id="nav">
		<div style="padding: 7px; color: #FFFFFF;" id="mycarousel_temp">
			Gallery with 360 objects will be loaded after the first spin is fully loaded, please wait...
		</div>
	</div>
	
	<div id="content">
		<div style="padding:20px; color: #000000; font-size: 16pt">Loading, please wait...</div>
	</div>
 

	<div id="naviReplacement1" style="text-align: left; width: 24px; position: absolute; display: none;">
	<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomIn({ajxTo: 750, pZoom: 50})" style="outline-style: none;"><img src="../axZm/icons/zoom_in1.png" border="0" ></a>
	</div>
	
	<div id="naviReplacement2" style="text-align: left; width: 24px; position: absolute; display: none;">
	<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomOut({ajxTo: 750, pZoom: 50})" style="outline-style: none;"><img src="../axZm/icons/zoom_out1.png" border="0"></a>
	</div>
 
	<script type="text/javascript">
	jQuery(document).ready(function(){
		var ajaxZoom = {};
		
		ajaxZoom.path = '../axZm/';
		
		ajaxZoom.ajaxZoomCallbacks = {
			onStart: function(){
				jQuery.axZm.fullScreenRel = 'content';
			},	
			onFullScreenStart: function(){
 
			}/*,
			onFullScreenReady: function(){
				// Add custom buttons
				jQuery("#naviReplacement1").appendTo("#zoomLayer").css({
					display: "block",
					left: 10,
					top: 130,
					zIndex: 111
				});
				
				jQuery("#naviReplacement2").appendTo("#zoomLayer").css({
					display: "block",
					left: 10,
					top: 330,
					zIndex: 111
				});
			},
			onFullScreenResizeEnd: function(){

			}
			*/
		};
		
		// $_GET['zoomData'] is the stringified and packed php array, see above
		// $_GET['zoomData'] = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), '+/=', '-_,');
		// $zoomData can also contain just one image!
		// By example=23 some default settings from axZmConfig.inc.php are overriden in axZmConfigCustom.inc.php after
		// elseif ($_GET['example' == 23), for example $zoom['config']['fullScreenRel'] = 'content'; 
		// whereby content is the id of the div container where ajax-zoom needs to be fit into
		 
		ajaxZoom.queryString = "example=17&3dDir=<?php echo $zoom['config']['installPath'];?>/pic/zoom3d/Uvex_Occhiali"; 
		// Using API jQuery.fn.axZm.openFullScreen
		jQuery.fn.axZm.openFullScreen(ajaxZoom.path, ajaxZoom.queryString, ajaxZoom.ajaxZoomCallbacks);
	});
	</script>

</body>
</html>