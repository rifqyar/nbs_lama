<?php
/**
* Plugin: jQuery AJAX-ZOOM, zoomDownload.php
* Copyright: Copyright (c) 2010 Vadim Jacobi
* License Agreement: http://www.ajax-zoom.com/index.php?cid=download
* Version: 3.3.0 Patch: 2011-10-17
* Date: 2011-08-03
* URL: http://www.ajax-zoom.com
* Description: jQuery AJAX-ZOOM plugin - adds zoom & pan functionality to images and image galleries with javascript & PHP
* Documentation: http://www.ajax-zoom.com/index.php?cid=docs
*/

if(!session_id()){session_start();}
error_reporting(0);
if (headers_sent()){
	exit;
}

include_once ("zoomInc.inc.php");

if (isset($_GET['zoomID']) && $zoom['config']['allowDownload']){
	$axZmH->downloadImage($zoom, $_GET['zoomID']);
}elseif (!$zoom['config']['allowDownload']){
	echo 'Download is not allowed.';
	exit;
}

?>
