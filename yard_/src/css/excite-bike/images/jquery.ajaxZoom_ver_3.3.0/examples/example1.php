<?php
if(!session_id()){session_start();}

unset ($_SESSION['imageZoom']);
$_SESSION['imageZoom']=array();

if (!isset($_GET['example'])){
	$_GET['example'] = 10;
}

if (!isset($_GET['zoomID']) AND !isset($_GET['zoomDir'])){
	$_GET['zoomID'] = 11;
}

if (!isset($_GET['zoomDir'])){
	$_GET['zoomDir'] = 1;
}

//$docRoot = $_SERVER['DOCUMENT_ROOT'];
//if (substr($docRoot,-1) == '/'){$docRoot = substr($docRoot,0,-1);}
//require ($docRoot.'/axZm/zoomInc.inc.php');
require ('../axZm/zoomInc.inc.php');

if (!isset($_GET['iframe'])){
	$zoom['config']['visualConf'] = true;
}

// For iframe
$bodyStyle='';
if (isset($_GET['iframe'])){$bodyStyle = " style='background-image: none'";}
// 
// Doctype, only for demo
echo $axZmH->setDoctype($zoom['config']['doctype']);

// 
echo "
<head>
<title>Example AJAX-ZOOM image gallery PHP dynamic configuration</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">
";
 
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
}

// Returns all needed css stylesheets
echo $axZmH->drawZoomStyle($zoom);


/* Returns all needed js files
First parameter $zoom is the config array, 
with the second parameter $exclude you can exclude certain javascripts, 
e.g. if already jquery core library is included, write $exclude = array('jquery');
if jqzery ui is also already present on your page, write $exclude = array('jquery', 'ui.core')
*/
echo $axZmH->drawZoomJs($zoom, $exclude = array()); 


/* Returns all needed js configuration parameters
Second parameter $rn - linebreak
Third parameter will "pack" and obfuscate javascript
*/
echo $axZmH->drawZoomJsConf($zoom, $rn = false, $pack = true);

#############################
## Returns jquery "onLoad" ##
#############################
// Returns window onload javascript (the init function of ajax zoom)
echo $axZmH->drawZoomJsLoad($zoom, $pack = true, $windowLoad = true, $jsObject = false);

echo"
</head>
<body$bodyStyle>
";
if (!isset($_GET['iframe'])){
	include ('navi.php');
}

// Rerurn AJAX-ZOOM html
echo $axZmH->drawZoomBox($zoom, $zoomTmp);

include('footer.php');
?>



</body></html>