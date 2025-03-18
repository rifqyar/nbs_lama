<?php
if(!session_id()){session_start();}
unset ($_SESSION['imageZoom']);
$_SESSION['imageZoom']=array();

$_GET['example'] = 2;

if (!isset($_GET['zoomDir'])){
	$_GET['zoomDir'] = 'animals';
}

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
			$return .= "<DIV class=\"thumbDemo\" style='background-image:url(".$_SERVER['PHP_SELF'].'?zoomDir='.$_GET['zoomDir'].'&example='.$_GET['example'].'&previewPic='.$v.");'>";
			$return .= "<a class=\"thumbDemoLink\" onClick=\"jQuery.fn.axZm.openFullScreen('../axZm/', 'zoomID=".$k."&zoomDir=".$_GET['zoomDir']."&example=".$_GET['example']."', ajaxZoomCallbacks);\" href=\"javascript: void(0)\"><img src='".$zoom['config']['icon']."empty.gif' class='thumbDemoImg' border='0'></a>";
			$return .= "</DIV>";
		$return .= "</DIV>";
	}			
	return $return;
}

// On the fly generation of thumbs
if (isset($_GET['previewPic'])){
	ob_start();
	$path = $zoom['config']['picDir'];

	$w = 100;
	$h = 100;
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
		$axZm->rawThumb($zoom, $path, urldecode($_GET['previewPic']), round($ww), round($hh), 90, true);
	}
	ob_end_flush();
	exit;
}

// Return the thumbs list
if (isset($_GET['getDir'])){
	echo zoomThumbs($pic_list_array, $zoom, $axZmH);
	exit;
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>AJAX-ZOOM Fullscreen Gallery with Zoom & Pan</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="imagetoolbar" content="no">

<?php
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
}
?>

<link rel="stylesheet" href="../axZm/axZm.css" type="text/css" media="screen">
<script type="text/javascript" src="../axZm/plugins/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>


<link href="../axZm/plugins/demo/syntaxhighlighter/styles/shCore.css" type="text/css" rel="stylesheet" />
<link href="../axZm/plugins/demo/syntaxhighlighter/styles/shThemeCustom.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/src/shCore.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushJScript.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushPhp.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushCss.js"></script>
<script type="text/javascript" src="../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushXml.js"></script>
 
<script type="text/javascript">
SyntaxHighlighter.all();

function changeDir(dir){
	jQuery.ajax({
		url: '<?php echo $_SERVER['PHP_SELF'];?>',
		data: 'getDir=1&zoomDir='+dir,
		cache: false,
		success: function (data){
			jQuery('#galDiv').html(data);
		}
	});		
}

var ajaxZoomCallbacks = {};
ajaxZoomCallbacks = {
	// Add callbacks if needed, for example
	
	/*
	onFullScreenReady: function(){
		 alert('Fullscreen started');
	},
	onFullScreenClose: function(){
		 alert('Fullscreen closed');
	}
	*/
};
 
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

//echo "<DIV style='width: 800px; margin: 0px auto;'>\n";
	
	echo "<DIV style='float: left; background-color: #FFFFFF; padding: 10px; margin: 5px;'>\n";
	
		echo "<h2>AJAX-ZOOM - Open the viewer in fullscreen</h2>\n";
			
			echo "<div style='margin-bottom:10px'>";
				// Select list with folders to choose from
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
			
			echo '<p style="font-size: 120%">
			Gallery thumbs are created on the fly and are cached in /pic/cach folder using $axZm->rawThumb method. 
			Clicking on a thumb the jQuery.fn.axZm.openFullScreen javascript method is called which opens the selected thumb on the entire available inner window size. 
			</p>';

			echo "<DIV id='galDiv'>";
			// Reeturn thumbs
			echo zoomThumbs($pic_list_array, $zoom, $axZmH);
			echo "</DIV>";
			
			echo "<DIV style='clear:left'>\n";

				echo "<p style='font-size: 120%'>
				The original sourcecode (PHP) of this example is in the download package.
				</p>";

				$example = 21;
				include('syntax.php');						
			
			echo "</DIV>\n";
			
	echo "</DIV>\n";
//echo "</DIV>\n";
include('footer.php');
echo "
</body>
</html>
";
?>