<?php
/**
* Plugin: jQuery AJAX-ZOOM
* Copyright: Copyright (c) 2010 Jacobi Vadim
* License Agreement: http://www.ajax-zoom.com/index.php?cid=download
* Version: 3.3.0 Patch: 2011-10-20
* Date: 2011-08-03
* URL: http://www.ajax-zoom.com
* Description: jQuery AJAX-ZOOM plugin - adds zoom & pan functionality to images and image galleries with javascript & PHP
* Documentation: http://www.ajax-zoom.com/index.php?cid=docs
*/

ini_set("display_errors", 0);
if(!session_id()){session_start();}
require('axZm/axZmH.class.php');
$axZmH = new axZmH();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>AJAX-ZOOM</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css" media="screen"> 
	html {font-family: Tahoma, Arial; font-size: 10pt; margin: 0; padding: 0;}
	body {margin: 0; padding: 0;}
	h2 {padding:0px; margin: 20px 0px 10px 0px; font-size: 16pt;}
	p { }
	.exampleImg{
		border: #000000 1px solid;
		cursor: pointer;
		margin: 0px 7px 10px 0px;
		-moz-box-shadow: 0px 2px 2px #C2C2C2;
		box-shadow: 0px 2px 2px #C2C2C2;
		-webkit-box-shadow: 0px 2px 2px #C2C2C2;
	}
	
	.rbox{
		-moz-border-radius: 4px;
		-webkit-border-radius: 4px;
		border-radius: 4px 4px 4px 4px;
	}
</style>
</head>
<body>
<?php
function rplc($x,$style='') {
	if (function_exists('ereg_replace')){
		$x = ereg_replace('[-a-z0-9!#$%&\'*+/=?^_`{|}~]+@([.]?[a-zA-Z0-9_/-])*','<a href=\'mailto:\\0\' '.$style.'>\\0</a>',$x);
		$x = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a target='_blank' href='\\0'>\\0</a>",$x);
		$x = ereg_replace("[a-zA-Z0-9\/\_]+(manual.txt)","<a target='_blank' href='\\0'>\\0</a>",str_replace('/axZm/','axZm/',$x));
	}
	return $x;
}

function readtxt($f){
	$return = '';
	$filename = $f;
    $ini_handle = fopen($filename, "r");
    $return = fread($ini_handle, filesize($filename));
	$return = nl2br ($return);
	return rplc($return);
}

echo "<DIV style='width: 930px; margin: 0px auto;'>\n";

	echo "<h2>Readme.txt</h2>";
	echo "<DIV>";
	echo readtxt('readme.txt');
	echo "</DIV>";

	// Some tests
	$error = '';
	$warning = '';
	$info = '';
	
	if (!defined('PHALANGER')){
		$php_version = phpversion();
		if (intval($php_version) < 5){
			$error = '<li>You need PHP 5 to run AJAX-ZOOM. Currently you are running PHP version: '.$php_version.'</li>';
		}
		if (!function_exists('gd_info')){
			$error .= '<li>GD Lib is not installed on your server. AJAX-ZOOM needs it to operate properly.</li>';
		}
		
		$extensions = get_loaded_extensions();
		$SourceGuardian = false;
		$ionCube = false;
		$zendDecoder = false;
		$gettext = false;
	
		foreach ($extensions as $k=>$v){
			if (stristr($v, 'ioncube')){
				$ionCube = true;
			}
			if (stristr($v, 'sourceguardian')){
				$SourceGuardian = true;
			}
			if (stristr($v, 'gettext')){
				$gettext = true;
			}
		}
		
		if(function_exists('zend_optimizer_version')){
			$zendDecoder = true;
		}
	
	
	
		if (!($ionCube || $SourceGuardian || $zendDecoder)){
			if (!ini_get('enable_dl')){
				$error .= '<li>It seems that neither SourceGuardian nor ionCube nor Zend Optimizer are installed. 
				Because dynamically loaded extensions aren\'t enabled it is essential to take care about this problem!!! 
				</li>';
			}else{
				$error .= '<li>It seems that neither SourceGuardian nor ionCube nor Zend Optimazer are installed. ';
			}
			
			$error .= "
				Please make sure Ioncube or Sourceguardian loaders or Zend Optimizer (PHP 5.3+ - Zend Guard Loader extension) are installed. 
				Run <a href='loaders/ioncube/loader-wizard.php'>loaders/ioncube/loader-wizard.php</a> for Ioncube or 
				<a href='loaders/sourceguardian/howto-install.php'>loaders/sourceguardian/howto-install.php</a> for Sourceguardian installation wizards. 
				See here for the Zend decoders: <a href='http://www.zend.com/products/guard/downloads' target='_blank'>http://www.zend.com/products/guard/downloads</a>
			";
			
			$error .= '</li>';
		}
		
		if ($SourceGuardian && !$ionCube){
			$warning .= '<li>SourceGuardian is installed on the Server, but ionCube is not. 
			Please make sure you switch between the encoded files in /axZm/zoomInc.inc.php; 
			Just uncomment the desired line with the require() statement. 
			\'axZm.class.ixed.php\' is the SourceGuardian version and \'axZm.class.ioncube.php\' is the IonCube encoded version.
			</li>';
		} elseif ($zendDecoder && !$ionCube){
			$warning .= '<li>Zend decoder is installed on the Server, but ionCube is not. 
			Please make sure you switch between the encoded files in /axZm/zoomInc.inc.php; 
			Just uncomment the desired line with the require() statement. 
			\'axZm.class.zend.php\' is the Zend version and \'axZm.class.ioncube.php\' is the IonCube encoded version.
			</li>';	
		}
		
		if ($SourceGuardian && $ionCube){
			$info .= "<li>Both loaders - SourceGuardian and ionCube seem to be installed on this server. 
			You can choose between the encoded scripts by editing the file '/axZm/zoomInc.inc.php'. 
			Just uncomment the desired line with the require() statement. 'axZm.class.ixed.php' is the SourceGuardian version 
			and 'axZm.class.ioncube.php' is the IonCube encoded version. 
			</li>";
		}
	
		if (!$gettext){
			$warning .= '<li>For some options "gettext" is needed to be installed on the server.
			</li>';
		}
		
		if (($ionCube || $SourceGuardian) && is_dir('loaders')){
			$warning .= "<li>Please remove 'loaders' directory with the installer scripts once IonCube or Sourceguardian loaders are installed. 
			At least one of the loaders seems to be installed on the server.
			</li>";	
		}
		
		if ($error){
			echo "<h2>Error</h2>";
			echo "<DIV style='padding: 3px; margin: 5px 0px 5px 0px; border: red 5px solid' class='rbox'><ul>$error</ul></DIV>";
		}	
	
		if ($warning){
			echo "<h2>Warning</h2>";
			echo "<DIV style='padding: 3px; margin: 5px 0px 5px 0px; border: orange 5px solid' class='rbox'><ul>$warning</ul></DIV>";
		}		
	
		if ($info){
			echo "<h2>Info</h2>";
			echo "<DIV style='padding: 3px; margin: 5px 0px 5px 0px; border: gray 5px solid' class='rbox'><ul>$info</ul></DIV>";
		}
	}
	
	if (!$error){
		echo "<h2>Congratulations</h2>";
		echo '<DIV style="padding: 10px; margin: 5px 0px 5px 0px; border: green 5px solid" class="rbox">
		AJAX-ZOOM should run on this server. In case You get an error stating, 
		that images could not be found or broken layout, 
		please open /axZm/zoomConfig.inc.php and set these options manually:<br><br>
		<ul>
			<li>$zoom[\'config\'][\'installPath\']<br><br>
				Replace:<br>
				$zoom[\'config\'][\'installPath\'] = $axZmH->installPath();<br>
				with:<br>
				$zoom[\'config\'][\'installPath\'] = \'\';<br>
				or if the path to your application is \'/shop\', then set:
				$zoom[\'config\'][\'installPath\'] = \'/shop\';<br><br>
			</li>
			
			<li>$zoom[\'config\'][\'fpPP\']<br><br>
			Server root path to www directory, 
			e.g. \'/srv/www/htdocs/web123\' or \'/home/yourdomain/public_html\'. 
			Usually it is $_SERVER[\'DOCUMENT_ROOT\']; without slash at the end. 
			Set this option manually if it does not produce correct results!
			</li>
		</ul>
		
		Please also be aware of that in file \'/axZm/zoomConfigCustom.inc.php\' 
		some of the options may be overridden depending on the parameter $_GET[\'example\'] 
		passed over the query string in / from the examples! 
		If you will make changes in \'/axZm/zoomConfig.inc.php\' and they do not change anything, 
		then see if this information can help you.<br><br>
		
		Have fun with AJAX-ZOOM. 
		</DIV>';
	}
	
	echo "<h2>Some Local Examples</h2>";
	echo "<p>Local examples do not contain high-resolution images. 
	Please find these and other examples with high-resolution images at ".rplc('http://www.ajax-zoom.com/index.php?cid=examples')."
	</p>";


	echo "<DIV style='line-height: 0px;'>";
	
	$files = scandir('examples');
	$files = $axZmH->natIndex($files);
	foreach ($files as $k=>$file){
		if (strstr($file,'example')){
			$num = intval(str_replace(array('example','.php'),'',basename($file)));
			echo "<a href='examples/example".$num.".php'><img src='http://www.ajax-zoom.com/pic/layout/image-zoom_".$num.".jpg' class='exampleImg'></a>";
		}
	}
	echo "</DIV>";
	
echo "</DIV>";
?>
</body>
</html>
