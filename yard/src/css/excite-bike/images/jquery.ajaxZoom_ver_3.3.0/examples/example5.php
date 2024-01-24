<?php
if(!session_id()){session_start();}
unset ($_SESSION['imageZoom']);
$_SESSION['imageZoom']=array();

//$noObjectsInclude = true;

$_GET['example'] = 2;

if (!isset($_GET['zoomDir'])){
	$_GET['zoomDir'] = 'animals';
}else if(isset($_GET['zoomDir']) AND !isset($_GET['abc'])){
	$getDir=1;
}

/*
$docRoot = $_SERVER['DOCUMENT_ROOT'];
if (substr($docRoot,-1) == '/'){$docRoot = substr($docRoot,0,-1);}
require ($docRoot.'/axZm/zoomInc.inc.php');
*/

require ('../axZm/zoomInc.inc.php');

/**
  * @param array $pic_list_array Array with images
  * @param array $zoom Configuration array
  * @return onject $axZmH Class instance
  **/	
function zoomThumbs($pic_list_array, $zoom, $axZmH){
	$return = '';
	foreach ($pic_list_array as $k=>$v){
		$return .= "<DIV class='thumbDemoBack'>";
			$return .= "<DIV class='thumbDemo' style='background-image:url(".$axZmH->composeFileName($zoom['config']['gallery'].$v,$zoom['config']['galleryFullPicDim'],'_').");'>";
			$return .= "<a class='thumbDemoLink' href=\"../axZm/zoomLoad.php?zoomLoadAjax=1&zoomID=".$k."&zoomDir=".$_GET['zoomDir']."&example=".$_GET['example']."\"><img src='".$zoom['config']['icon']."empty.gif' class='thumbDemoImg' border='0'></a>";
			$return .= "</DIV>";
		$return .= "</DIV>";
	}			
	return $return;
}

if ($getDir){
	echo zoomThumbs($pic_list_array, $zoom, $axZmH);
	exit;
}

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>AJAX-ZOOM Simple Gallery with PHP & Lightbox</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">";
 
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
}
 
//echo $axZmH->drawZoomStyle($zoom); 

echo "
<link rel=\"stylesheet\" href=\"../axZm/plugins/demo/colorbox/example4/colorbox.css\" media=\"screen\" type=\"text/css\">
<link rel=\"stylesheet\" href=\"../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.css\" media=\"screen\" type=\"text/css\">
<link rel=\"stylesheet\" href=\"../axZm/axZm.css\" type=\"text/css\" media=\"screen\">
";

//echo $axZmH->drawZoomJs($zoom, $exclude = array()); 

echo "
<script type=\"text/javascript\" src=\"../axZm/plugins/jquery-1.7.2.min.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/jquery.axZm.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/colorbox/jquery.colorbox.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.js\"></script>
";

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

function setColorBox(){
	/*Init colorbox on thumbnails
	Please note the callback: jQuery.fn.axZm(); that have to be 
	trigered after each load of colorbox or a differen "lightbox" implementation 
	*/
	jQuery(".thumbDemoLink").unbind().colorbox({
		initialWidth: 300,
		initialHeight: 300,
		scrolling: false,
		scrollbars: false,
		opacity: 0.95,
		preloadIMG: false,
		preloading: false
	}, function(){
		jQuery.fn.axZm();
		// Needs this var only for demo with changing dirs...
		jQuery.zoomLightbox =  'colorbox';
	});
}

function setFancyBox(){
	jQuery(".thumbDemoLink").unbind().fancybox({
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
		callbackOnShow		: function(){
			jQuery.fn.axZm();						
			// Needs this var only for demo with changing dirs...
			jQuery.zoomLightbox =  'fancybox';			
		}
	});	
	

}

function changeDir(dir){
	jQuery.ajax({
		url: '<?php echo $_SERVER['PHP_SELF'];?>',
		data: 'zoomDir='+dir,
		cache: false,
		success: function (data){
			jQuery('#galDiv').html(data);
		},
		complete: function () {
			if (jQuery.zoomLightbox == 'fancybox'){
				setFancyBox();
			}else if (jQuery.zoomLightbox == 'colorbox'){
				setColorBox();
			} else{
				setFancyBox();
			}
		}
	});		
}

jQuery(document).ready(function() {
	setFancyBox();
});
</script>
<style type="text/css" media="screen"> 
	body {margin:0px; padding:0px; background-image: none;}
	html {margin:0px; padding:0px; border: 0; font-family: Tahoma, Arial; font-size: 10pt;}
	form {padding:0px; margin:0px}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}
	p {text-align: justify; text-justify: newspaper;}
	
	.thumbDemoBack {
		float: left; 
		width: 142px; 
		height: 139px; 
		margin-bottom: 5px; 
		margin-right: 7px;
		background-position: center center;
		background-repeat: no-repeat;
		background-image: url('../axZm/icons/thumb_back.png');
		overflow: hidden;
	}
	.thumbDemo {
		width: 100px; 
		height: 100px; 
		margin-left: 21px;
		margin-top: 20px;
		background-position: center center;
		background-repeat: no-repeat;
	}
	.thumbDemoImg {
		width: 100px;
		height: 100px;
	}
	.thumbDemoLink{
	
	}
</style>

<?php
echo "
</head>
<body>
";
include ('navi.php');
echo "<DIV style='width: 800px; margin: 0px auto;'>\n";
	
	echo "<DIV style='float: left; width: 770px; background-color: #FFFFFF; padding: 10px; margin: 5px;'>\n";
	
		echo "<h2>AJAX-ZOOM Lightbox & Co. Examples - Simple gallery</h2>\n";
			
			echo "<div style='margin-bottom:10px'>";
				echo "<input type='radio' name='selPlugin' onClick='return setFancyBox();' checked> - Fancybox";
				echo "<input type='radio' name='selPlugin' onClick='return setColorBox();'> - Colorbox";
			echo "</div>";
			
			
			echo "<div style='margin-bottom:10px'>";
				echo "<select onChange=\"changeDir(this.value)\">";
				foreach ($zoomTmp['folderArray'] as $k=>$v){
					echo "<option value='$k'";
					if (isset($_GET['zoomDir'])){
						if ($_GET['zoomDir'] == $k OR $_GET['zoomDir'] == $v){
							echo ' selected';
						}
					}
					echo ">$v</option>";
				}
				echo "</select> - Switch folder with AJAX (can be any other custom parameter(s))";
			echo "</div>";
			
			echo "<div style='margin-bottom:10px'>";
				echo "<FORM method='GET' action='".$_SERVER['PHP_SELF']."'>";
				echo "<input type='hidden' name='abc' value='1'>";
				
				echo "<select name='zoomDir' onChange=\"this.form.submit()\">";
				foreach ($zoomTmp['folderArray'] as $k=>$v){
					echo "<option value='$k'";
					if (isset($_GET['zoomDir'])){
						if ($_GET['zoomDir'] == $k OR $_GET['zoomDir'] == $v){
							echo ' selected';
						}
					}
					echo ">$v</option>";
				}
				echo "</select> - Switch folder with query string, with page reloaded";
				echo "</FORM>";
			echo "</div>";
			
			echo "<p style='font-size: 120%'>
			In this example we \"misuse\" the inline gallery thumbnails from AJAX-ZOOM for a simple html gallery.
			</p>";

			echo "<DIV id='galDiv'>";
			echo zoomThumbs($pic_list_array, $zoom, $axZmH);
			echo "</DIV>";
			
			echo "<DIV style='clear:left'>\n";

				echo "<p style='font-size: 120%'>
				To keep things not to complicated - following is a simplified version of the actual code. 
				In order to change the thubnails over a dropdown or any other kind of menu with AJAX there is an additional javascript function and logic necessary. 
				For complete solution please take a look at the original sourcecode (PHP) of this example!
				</p>";

				$example = 5;
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