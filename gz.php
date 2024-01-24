<?php
if (!isset($_GET['f'])) die();
$f   = $_GET['f'];

$js  = array(
	'js/ajax.js',
	'js/jquery.js',
	'js/jquery.slidemenu.js',
	'js/ajax.js',
	'js/jquery.blockui.js',
	'js/calendar/basicgray/jscalendar.js',
	'js/jquery.updater.js',
	'css/default.css',
	'css/application.css'
	);
$css = array();

if (in_array($f,$js)) header('Content-type: application/javascript',true);
else if (in_array($f,$css)) header('Content-type: text/css',true);
else die();

ob_start('ob_gzhandler');
header('Cache-Control: public',true);
header('Expires: Mon, 23 Mar 2020 00:00:00 GMT');

if (file_exists($f)) readfile($f);
?>