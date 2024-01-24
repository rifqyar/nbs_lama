<?php
// ********************************************* PLEASE READ **************************************************
/* The PHP code below is only needed for the 3d/360 gallery. You can outsource it to a different php file.
Adjust $basePath if needed. Also change the reference to your outsourced file below:
now it is url: '<?php echo $_SERVER['PHP_SELF']?>', (self reference)
For a clean example without any gallery and text see example15_clean.php
*/
// *************************************************************************************************************

if(!session_id()){session_start();}

if ((isset($_GET['previewPic']) && isset($_GET['previewDir'])) || isset($_GET['firstPicDir'])){
	$noObjectsInclude = true;
}

require ('../axZm/zoomInc.inc.php');

$basePath = $zoom['config']['installPath'].'/pic/zoom3d';

// A helper function to show first image of subfolders on the fly
function firstPicDir($dir){
	global $axZmH;
	$return = '';
	$docRoot = $_SERVER['DOCUMENT_ROOT'];
	if (substr($docRoot,-1) == '/'){$docRoot = substr($docRoot,0,-1);}
	
	$files = array();
	$folders = array();
	$subFolders = array();
	$n = 0; 
	
	// Open folders
	foreach (glob($docRoot.$dir.'/*', GLOB_ONLYDIR) as $folder){
		if (basename($folder) != '..' && basename($folder) != '.'){
			
			// Scan the files and sort them
			$filesArray = scandir($folder);
			unset($filesArray[0], $filesArray[1]);
			$filesArray = $axZmH->natIndex($filesArray);
							
			// Path from root - .$filesArray[1] means first file
			$targetFile = $axZmH->checkSlash($folder.'/'.$filesArray[1], 'remove');
			
			// Check if this is a file (not a folder) and is valid image type
			if (is_file($targetFile) && $axZmH->isValidFileType($targetFile)){
				$n++;
				$files[$n] = $filesArray[1];
				$folders[$n] = basename($folder);
			} 
			
			// Subfolders
			elseif (is_dir($targetFile)){
				// Select a middle range folder if there are many levels
				$subDir = $axZmH->checkSlash($folder.'/'.$filesArray[ceil(count($filesArray)/2)], 'remove');
				
				// Scan the files and sort them
				$filesArray = scandir($subDir);
				unset($filesArray[0], $filesArray[1]);
				$filesArray = $axZmH->natIndex($filesArray);
				
				// Path from root - .$filesArray[1] means first file
				$targetFile = $axZmH->checkSlash($subDir.'/'.$filesArray[1], 'remove');
				
				// Check if this is a file and is valid image type
				if (is_file($targetFile) && $axZmH->isValidFileType($targetFile)){
					$n++;
					$files[$n] = $filesArray[1];
					$folders[$n] = basename($folder);
					$subFolders[$n] = basename($subDir);
				}
			}
		}
	}
	
	// Return jcarousel formated thumbnails
	$return .= '<ul id="mycarousel" class="jcarousel-skin-custom">';
	
	foreach ($folders as $k=>$v){
		if ($files[$k]){
			$return .= '<li>';
			$return .= '<a class="outerContainer" onClick="submitNewZoom(\''.$dir.'/'.$v.'\');">';
			$return .= "<div class='outerimg' style='background-image: url(".$_SERVER['PHP_SELF']."?previewPic=".$files[$k]."&previewDir=".$v."&previewSubDir=".$subFolders[$k].")'>";
			// Want a thumb description ?
			//$return .= "<div style='position: relative; top: 52px; font-size: 7px; overflow: hidden;'>".$files[$k]."</div>";
			$return .= "</div>";
			$return .= '</a>';
			$return .= '</li>';
		}
	}
	
	$return .= '</ul>';
	
	return $return;
}

// Return html to show first picture of a folder in a gallery
if (isset($_GET['firstPicDir'])){
	echo firstPicDir($basePath);
	exit;
}

// Show an image on the fly, will be cached on default
if (isset($_GET['previewPic']) && isset($_GET['previewDir'])){
	ob_start();
	$path = $axZmH->checkSlash($zoom['config']['fpPP'].$basePath.'/'.urldecode($_GET['previewDir']).(isset($_GET['previewSubDir']) ? '/'.$_GET['previewSubDir'] : ''),'add');
	$w = 62;
	$h = 62;
	$fillThumb = false;
	
	$ww = $w;
	$hh = $h;

	if ($axZmH->isValidPath($path) && file_exists($path.urldecode($_GET['previewPic']))){
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
		// Documentation: http://www.ajax-zoom.com/index.php?cid=docs#php_rawThumb
		$axZm->rawThumb($zoom, $path, urldecode($_GET['previewPic']), round($ww), round($hh), 100, true);
	}
	ob_end_flush();
	exit;
}




// ********************************************* PLEASE READ **************************************************
/* All above is only needed for the 3d/360 gallery. You can outsource it to a different php file.
Adjust $basePath if needed. Also change the reference to your outsourced file below:
now it is url: '<?php echo $_SERVER['PHP_SELF']?>', (self reference)
For a clean example without any gallery and text see example15_clean.php
*/
// *************************************************************************************************************
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>3D Spin Rotate & Zoom 360 product viewer Javascript jQuery VR Objects 360° Reel</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="imagetoolbar" content="no">

 
<?php
// Set scale for iPhone and disable user scalability
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
	
	/* Gallery thumbs */
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
		margin: 0px 3px 3px 0px;
		background-color: #E3E3E3;
		outline: none;
	}
</style>

<!--  jCarousel is only needed for the 3D/360 gallery above the AJAX-ZOOM player -->
<link href="../axZm/plugins/demo/jcarousel/skins/custom/skin.css" type="text/css" rel="stylesheet" />

<!--  Include jQuery core into head section -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>

<!--  jCarousel is only needed for the 3D/360 gallery above the AJAX-ZOOM player -->
<script type="text/javascript" src="../axZm/plugins/demo/jcarousel/lib/jquery.jcarousel.min.js"></script>

<!--  AJAX-ZOOM javascript -->
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>

<!--  syntaxhighlighter is not needed, you can remove this block along with SyntaxHighlighter.all(); below -->
<link href="../axZm/plugins/demo/syntaxhighlighter/styles/shCore.css" type="text/css" rel="stylesheet" />
<link href="../axZm/plugins/demo/syntaxhighlighter/styles/shThemeCustom.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/src/shCore.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushJScript.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushPhp.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushCss.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushXml.js"></script>




<script type="text/javascript">
	// SyntaxHighlighter is not needed
	SyntaxHighlighter.all();

	// After document is loaded...
	jQuery(document).ready(function() {
	
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
						// change the url: value below to the outsourced php file
						url: '<?php echo $_SERVER['PHP_SELF']?>',
						data: 'firstPicDir=1',
						dataType: 'html',
						cache: false,
						success: function (data){	
							// Remove temp message
							jQuery('#mycarousel_temp').remove();
							
							// Load the html return into container
							jQuery('#mycarousel_par').css('display', 'block').html(data);
							
							// Init the jcarousel slider
							jQuery('#mycarousel').jcarousel();
						}
					});

				}else{
					abc(500); 
				}
			}, t);
		};	
		
		abc(200);
		
	});
 
	// Function for changing the spin object from the top slider with 3D/360 objects
	function submitNewZoom(path){
		if (path){
			var data = 'example=17&3dDir='+path;
			jQuery.fn.axZm.loadAjaxSet(data);
		}	
	}

</script>


<?php
// This include is just for the demo, you can remove it
include ('navi.php');
?>

<DIV style='width: 620px; margin: 0px auto;'>

	<DIV style='float: left; background-color: white; padding: 10px; margin: 5px;'>

		<h2>AJAX-ZOOM - 3D Spin & Zoom Example</h2>
	
		<DIV style='clear: both;'>

			<p style="padding-top:20px; font-size:120%">
 			From Ver. 3.0.1 it is possible to display a series of images as VR Objects 360° with 3D Spin & Zoom. 
			The sprite contains a set of single images of the same object.  
			</p>
			
			<!--  Top slider with 360 objects -->
			<DIV style="height: 74px;">
				<!-- Temp message that will be removed after the slider initialization (jQuery('#mycarousel_temp').remove();)  -->
				<DIV id="mycarousel_temp" style="color: gray; margin-top: 20px; min-height: 30px; padding: 10px 0px 0px 40px; background: url(../axZm/icons/ajax-loader-map-white.gif) no-repeat;">
					Gallery with 360 objects will be loaded after the first spin is fully loaded, please wait...
				</DIV>
				
				<!--  Placeholder for 3d/360 gallery -->
				<DIV style="width: 602px; height: 74px; display: none;" id="mycarousel_par"></DIV>
			</DIV>
			
			
			<!--  Placeholder for AJAX-ZOOM player -->
			<DIV id="test" style='margin: 5px 0px 0px 0px; width: 602px; min-height: 400px; color: gray;'>
				<DIV style="margin: 0; padding-top: 175px; padding-left: 200px; min-height: 225px; background: url(../axZm/icons/ajax-loader-bar.gif) center center no-repeat;">
				Loading, please wait...
				</DIV>
			</DIV>


			<!--  Init AJAX-ZOOM player -->
			<script type="text/javascript">
			// Create empty jQuery object
			var ajaxZoom = {}; 
			
			// Define callbacks, for complete list check the docs
			ajaxZoom.opt = {
				onBeforeStart: function(){
					jQuery('.zoomContainer').css({backgroundColor: '#FFFFFF'});
					jQuery('.zoomLogHolder').css({width: 70});			
				}
			};
			
			// Define the path to the axZm folder, adjust the path if needed!
			ajaxZoom.path = "../axZm/"; 
			
			// Define your custom parameter query string
			// example=17 has many presets for 360 images
			// 3dDir - best of all absolute path to the folder with 360/3D images
			ajaxZoom.parameter = "example=17&3dDir=<?php echo $basePath;?>/Uvex_Occhiali"; 
			
			// The ID of the element (placeholder) where AJAX-ZOOM has to be inserted into
			ajaxZoom.divID = "test";
			</script>
			
			<!-- Include the loader file, adjust the path if needed! -->
			<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>

			
			
			<!-- Everything else is not needed for AJAX-ZOOM to work -->
			<script type="text/javascript">
				// These js functions are just for the demo, they are not needed for the implementation
				var setSpinState = function(q){
					if(jQuery.axZm){
						jQuery.axZm.spinEffect.enabled=q;
					}
				}
				
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

			<div style="text-align: right; margin-bottom: 10px">
			External controls example: 
			<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomIn({ajxTo: 750})">zoomIn</a> | 
			<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomOut({ajxTo: 750})">zoomOut</a> | 
			<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomReset()">reset</a> | 
			<a href="javascript: void(0)" onclick="jQuery.fn.axZm.switchPan()">switch pan</a> | 
			<a href="javascript: void(0)" onclick="jQuery.fn.axZm.switchSpin()">switch spin</a> | 
			<a href="javascript: void(0)" onclick="jQuery.fn.axZm.switchCrop()">switch crop</a> 
			</div>

			<div style="padding: 5px; border: red 1px solid; margin-bottom: 10px; font-size: 120%">
				<ul style="margin: 0px; padding: 0px 0px 0px 24px;">
					<li><a href="http://www.ajax-zoom.com/examples/example15_vr.php">Here You will find a VR Multirow Spin Rotate & Zoom Example</a>. 
					The only difference between regular 360 spin and multirow is that original images are placed in subfolders of the target folder. 
					You do not need to define these subfolders anywhere. AJAX-ZOOM will instantly detect them and procede all the images in them.
					</li>
					
					<li>For a clean example without anything else and without PHP code please <a href='example15_clean.php'>see this page</a>.
					</li> 
					
					<li>To start AJAX-ZOOM 360/VR in fullscreen mode, <a href="example15_fullscreen.php">see this page</a>.
					</li>

					<li>To load AJAX-ZOOM 360/VR in a lightbox e.g. Fancybox, <a href="example3.php">see this page</a>.
					</li>
					
					<li><a href="http://www.ajax-zoom.com/examples/example15_vr9.php">Here is a truly VR spherical example with 9 rows and 90 images per row = 810 images</a>. 
					</li>
					
					<li>Also see <a href="http://www.ajax-zoom.com/examples/example15a.php">this slightly modified example</a> 
					where the frame number ramains when spin object changes. Useful for simmilar objects or same objects with different colors.
					</li>
				</ul>
			</div>
			
			<div>
				A couple selected parameters which can visually be changed in this example 
				(more parameters in the <a href="http://www.ajax-zoom.com/index.php?cid=docs">online documentation</a>):
				<ul>
					<li>
					<span style="width: 100px; float: left;">Blur effect: </span>
					<a href="javascript: void(0)" onclick="setSpinState(false)">disable</a> | 
					<a href="javascript: void(0)" onclick="setSpinState(true)">enable</a>
					</li>
					<li>
					<span style="width: 100px; float: left;">Spin direction: </span>
					<a href="javascript: void(0)" onclick="reverseSpin()">reverse</a> 
					</li>
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
			
			<p>
			There are several <a href="http://www.ajax-zoom.com/index.php?cid=docs#VR_Object">options</a> to adjust the spin behaviour,  
			for example the blur effect during spin. The spin direction while dragging can be reversed. 
			Of course all other options from plain zoom are applicable to 360 degree spinner. 
			If you do not like the navigation bar, you can disable it. 
			Zooming is still possible with the mousewheel, with external controls for 
			<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomIn({ajxTo: 750})">zoomIn</a>, 
			<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomOut({ajxTo: 750})">zoomOut</a> (see API) or with the zoom slider, added in Ver. 3.0.2. 
			Also in this update an additional slider control for spinning has been added. 
			</p>

			<p>
			To load the spinner all you need is to pass the directory (3dDir) where images are located. 
			The number of frames depends on the number of images and will be determined instantly. 
			All image processing including the generation of image tiles is done on-the-fly during the first load of the VR Object in the browser.
			</p>
			
			<p>
			By defining the query string parameter in ajaxZoom.parameter example=17 some default settings from /axZm/zoomConfig.inc.php are overridden in /axZm/zoomConfigCustom.inc.php after <code>elseif ($_GET['example'] == 17){</code>.
			So if changes in /axZm/zoomConfig.inc.php have no effect look for the same options /axZm/zoomConfigCustom.inc.php;
			</p>

			<?php
			// This include is just for the demo, you can remove it
			$example = 15;
			include('syntax.php');					
			?>
			
			
		</DIV>
		
	</DIV>

</DIV>

<?php
// This include is just for the demo, you can remove it
include('footer.php');
?>

</body>
</html>