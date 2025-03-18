<?php

if (!strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){

	if ($example == 2){
	
		echo "<h3>Header:</h3>";
		echo "<pre class='brush: js; html-script: true'>";
		echo htmlspecialchars ('
			<!-- Fancybox css file -->
			<link rel="stylesheet" href="/axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.css" media="screen" type="text/css">
			
			<!-- jQuery  -->
			<script type="text/javascript" src="/axZm/plugins/jquery-1.7.2.min.js"></script>
			
			<!-- Easing plugin for more transitions -->
			<script type="text/javascript" src="/axZm/plugins/demo/jquery.fancybox/jquery.easing.1.3.js"></script>
			
			<!-- Fancybox js -->
			<script type="text/javascript" src="/axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.js"></script>
			
			<script type="text/javascript">
				jQuery(document).ready(function() {
					// Bind fancybox to some ellement
					jQuery("#ifrmExample1").fancybox({
						"padding"				: 0,
						"overlayShow"			: true,
						"overlayOpacity"		: 0.4,
						"zoomSpeedIn"			: 500, // has nothing to do with AJAX-ZOOM
						"zoomSpeedOut"			: 500, // has nothing to do with AJAX-ZOOM
						"hideOnContentClick"	: false, // important
						"frameWidth"			: 754, // has to be defined
						"frameHeight"			: 458, // has to be defined
						"centerOnScroll"		: false,
						"imageScale"			: true,
						"easingIn"  			: "swing",
						"easingOut"				: "swing"
					});
				});
			</script>
			
			
		');
		echo "</pre>";		
		
		echo "<h3>Body:</h3>";
		echo "<pre class='brush: html;'>";
		echo htmlspecialchars ('
			<!-- A simple link, class must be "iframe" for fancybox -->
			<a class="iframe" id="ifrmExample1" href="example1.php?zoomDir=4&example=1">Example 1</a>
		');
		echo "</pre>";	
	
	
	}
	
	elseif ($example == 3){		
		echo "<h3>Head Fancybox:</h3>";
		echo "<pre class='brush: html;'>";
		echo htmlspecialchars('
			<link rel="stylesheet" href="../axZm/axZm.css" media="screen" type="text/css">
			<link rel="stylesheet" href="../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.css" media="screen" type="text/css">
			
			<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
			
			<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>
			<script type="text/javascript" src="../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.js"></script>
		');
		echo "</pre>";
		
		echo "<h3>Head Javascript:</h3>";
		echo "<pre class='brush: js;'>";
		echo htmlspecialchars('
			<script type="text/javascript">
			jQuery(document).ready(function() {
				// For more information take a look at the options of fancybox: http://fancybox.net/api
				jQuery(".ajaxExampleFancybox").fancybox({
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
						jQuery.fn.axZm(); // Important callback after loading
					},
					// Newer fancybox has different callback names
					onComplete : function(){
						jQuery.fn.axZm(); // Important callback after loading
					}

				});					
			});
		');
		echo "</pre>";	
		
		echo "<h3>Body:</h3>";
		echo "<pre class='brush: html;'>";
		echo htmlspecialchars('
			<!-- zoomLoadAjax=1 is needed for ajax request, zoomDir=4&example=4 or whatever a your custom parameters -->
			<a class="ajaxExampleFancybox" href="/axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=/pic/zoom/animals&example=4">Example 1</a>
			
			<!-- Please note that the provided lightboxes have been modified in order not to load the href string as image because the string contains .png or .jpg -->
			<a class="ajaxExampleFancybox" href="/axZm/zoomLoad.php?zoomLoadAjax=1&zoomData=/pic/zoom/animals/test_animals1.png|/pic/zoom/boutique/test_boutique3.png&example=4">Example 2</a>
		');
		echo "</pre>";
		
		echo "<h3>Alternative Body:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars('
			<!-- You can also compress the zoomData string like this: -->
			<?php
			// Create an array with images
			$zoomData = array();
			
			$zoomData[1]["p"] = "/pic/zoom/fashion/";
			$zoomData[1]["f"] = "test_fashion1.png";
			
			$zoomData[2]["p"] = "/pic/zoom/fashion/";
			$zoomData[2]["f"] = "test_fashion3.png";

			$zoomData = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), \'+/=\', \'-_,\');
			?>
			
			<!-- Then output $zoomData into html -->
			<a class="ajaxExampleFancybox" href="/axZm/zoomLoad.php?zoomLoadAjax=1&zoomData=<?php echo $zoomData;?>&example=4">Example 3</a>

		');
		echo "</pre>";
	}
	
	elseif ($example == 4){
		echo "<h3>Head PHP:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars('
			if(!session_id()){session_start();}
			unset ($_SESSION["imageZoom"]);
			$_SESSION["imageZoom"]=array();
			
			// Simulate custom parameters
			$_GET["example"] = 8;
			$_GET["zoomDir"] = "boutique";
			$_GET["zoomFile"] = "watch_2.jpg";
			
			// Contains all needed classes and other files
			require ("../axZm/zoomInc.inc.php");
		');
		echo "</pre>";
		
		echo "<h3>Head Javascript and CSS:</h3>";
		
		echo "<pre class='brush: html;'>";
		echo htmlspecialchars('
			<!-- Include the AJAX-ZOOM CSS file directly -->
			<link rel="stylesheet" href="/axZm/axZm.css" type="text/css" media="screen">
			
			<!-- Include the lavalamp menu CSS (just for this demo) -->
			<link rel="stylesheet" href="/axZm/plugins/demo/lavalamp/lavalamp_test.css" type="text/css" media="screen">
			
			<!-- Include jQuery (could also be 1.3.2) -->
			<script type="text/javascript" src="/axZm/plugins/jquery-1.7.2.min.js"></script>
			
			<!-- Include AJAX-ZOOM Javascript, all other requeired plugin\'s will be loaded instantly -->
			<script type="text/javascript" src="/axZm/jquery.axZm.js"></script>
			
			<!-- Include lavalamp menu Javascript (just for this demo) -->
			<script type="text/javascript" src="/axZm/plugins/demo/lavalamp/jquery.lavalamp.js"></script>
		');
		echo "</pre>";
		
		echo "<h3>Head Javascript custom function:</h3>";
		echo "<pre class='brush: js;'>";
		echo htmlspecialchars ('
			<script type="text/javascript">
				
				// This function is a custom one just for this example
				function submitNewZoom(menuItem){
					// Filter the needed value from id attribute
					var id = jQuery(menuItem).attr("id").split("zoomSet").join("");
					
					if (id){
						// Generate your custom parameters query string, in this case a directory
						var data = "example=<?php echo $_GET["example"];?>&zoomDir="+id;
						
						// Pass it to PHP
						jQuery.fn.axZm.loadAjaxSet(data);
					}	
				}
				
				jQuery(window).load(function () {
					jQuery("#lavalampMenu").lavaLamp({
						fx: "easeOutBack",
						speed: 750,
						click: function(event, menuItem) {
							submitNewZoom(menuItem); // Watch above!
							return false;
						}
					});
				});
				
			</script>
		');
		echo "</pre>";		
		
		
		echo "<h3>Body PHP:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
			// This div is just for the background image
			echo "<DIV style=\'margin: 20px 0px 0px 0px; padding: 6px; background-image: url(/axZm/icons/back_inline.png); background-repeat: no-repeat;\'>";
				
				// Include (print) AJAX-ZOOM html
				echo $axZmH->drawZoomBox($zoom, $zoomTmp);
				
				// Include (print) AJAX-ZOOM javascript
				echo $axZmH->drawZoomJsConf($zoom, $rn = false, $pack = true);
				echo $axZmH->drawZoomJsLoad($zoom, $pack = true, $windowLoad = true);
				
			echo "</DIV>";
			
			// This div is just for the background image
			echo "<DIV style=\'background-image: url(/axZm/icons/back_lava.png)\'>";
				
				echo "<ul class=\'lavaLampNoImageZoom\' id=\'lavalampMenu\'>";
				
				// Loop through in zoomObjects.inc.php generated array with folders
				foreach ($zoomTmp[\'folderArray\'] as $k=>$v){
					
					// Hide the key of the php folders array in the id attribut of the li tag (menu item)
					// This key will be extracted by submitNewZoom() custom js function above
					echo "<li id=\'zoomSet".$k."\'";
					
					// Find the selected (current) menu item
					if ($k == $_GET[\'zoomDir\'] OR $v == $_GET[\'zoomDir\']){
						echo " class=\'current\'";
					}
					
					echo "><a href=\'#\'>".$v."</a></li>";
				}
				
			echo "</ul></DIV>";
			
		');
		echo "</pre>";
	}
	
	elseif ($example == 5){
	
		echo "<h3>Head PHP:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
			if(!session_id()){session_start();}
			unset ($_SESSION[\'imageZoom\']);
			$_SESSION[\'imageZoom\']=array();
			$noObjectsInclude = true;
			
			$_GET["example"] = 2;
			$_GET["zoomDir"] = \'trasportation\';
			
			require (\'../axZm/zoomInc.inc.php\');
	
			/**
			  * This function returns 
			  * @param array $pic_list_array Array with images
			  * @param array $zoom Configuration array
			  * @return onject $axZmH Class instance
			  **/						
			function zoomThumbs($pic_list_array, $zoom, $axZmH){
				$return = \'\';
	
	
	
	
				foreach ($pic_list_array as $k=>$v){
					$return .= "<DIV class=\'thumbDemoBack\'>";
						$return .= "<DIV class=\'thumbDemo\' style=\'background-image:url(".$axZmH->composeFileName($zoom[\'config\'][\'gallery\'].$v,$zoom[\'config\'][\'galleryFullPicDim\'],\'_\').");\'>";
						$return .= "<a class=\'thumbDemoLink\' href=\"/axZm/zoomLoad.php?zoomLoadAjax=1&zoomID=".$k."&zoomDir=".$_GET[\'zoomDir\']."&example=".$_GET[\'example\']."\"><img src=\'".$zoom[\'config\'][\'icon\']."empty.gif\' class=\'thumbDemoImg\' border=\'0\'></a>";
						$return .= "</DIV>";
					$return .= "</DIV>";
				}			
				return $return;
			}
		');
		echo "</pre>";
	
		echo "<h3>Head CSS:</h3>";
		echo "<pre class='brush: css;'>";
		echo htmlspecialchars ('
			.thumbDemoBack {
				float: left; 
				width: 142px; 
				height: 139px; 
				margin-bottom: 5px; 
				margin-right: 7px;
				background-position: center center;
				background-repeat: no-repeat;
				background-image: url(/axZm/icons/thumb_back.png);
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
		');
		echo "</pre>";
	
		
		echo "<h3>Head Javascript:</h3>";
		echo "<pre class='brush: js;'>";
		echo htmlspecialchars ('
		<script type="text/javascript">
			function setFancyBox(){
				jQuery(".thumbDemoLink").unbind().fancybox({
				padding				: 0,
				overlayShow			: true,
				overlayOpacity		: 0.6,
				zoomSpeedIn			: 0,
				zoomSpeedOut		: 100,
				easingIn			: \'swing\',
				easingOut			: \'swing\',
				hideOnContentClick	: false, // important
				centerOnScroll		: true,
				imageScale			: true,
				autoDimensions		: \'true\',
				callbackOnShow		: function(){
					jQuery.fn.axZm();
					// Needs this var only for demo with changing dirs...
					jQuery.zoomLightbox =  \'fancybox\';
				}
				});
			}
			
			jQuery(document).ready(function() {
				setFancyBox();
			});
		</script>
		');
		echo "</pre>";
		
		echo "<h3>Body PHP:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
			echo zoomThumbs($pic_list_array, $zoom, $axZmH);
		');
		echo "</pre>";
	}
	
	elseif ($example == 6){
		
		echo "<h3>Head PHP:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
			if(!session_id()){session_start();}
			unset ($_SESSION[\'imageZoom\']);
			$_SESSION[\'imageZoom\']=array();
			
			// Simulate initial values for demo
			$_GET[\'example\'] = 2; // layout
			$_GET[\'zoomDir\'] = 2; // folder with images
			
			// Contains all needed classes and other files
			require ("../axZm/zoomInc.inc.php");
		');
		echo "</pre>";
		
		
		
		echo "<h3>Head php:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
			// Include all needed css for zoom
			echo $axZmH->drawZoomStyle($zoom); 
			
			// Include all needed js for zoom
			echo $axZmH->drawZoomJs($zoom, $exclude = array()); 
		');
		echo "</pre>";				
		
		
		
		echo "<h3>Head Stylesheets and JS files:</h3>";
		echo "<pre class='brush: html;'>";
		echo htmlspecialchars ('
			<!-- Include css for fancybox plugin --> 
			<link rel="stylesheet" href="/axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.css" media="screen" type="text/css">
	
			<!-- Include fancybox and jqDock plugins--> 
			<script type="text/javascript" src="/axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.js"></script>
			<script type="text/javascript" src="/axZm/plugins/demo/jquery.jqDock/jquery.jqDock.js"></script>
		');
		echo "</pre>";						
		
		
		
		echo "<h3>Head Javascript:</h3>";
		echo "<pre class='brush: js;'>";
		echo htmlspecialchars ('
		<script type="text/javascript">
		function setFancyBox(){
			// Attach fancybox to all links with class=\'thumbDemoLink\'
			jQuery(".thumbDemoLink").unbind().fancybox({
				padding				: 0,
				overlayShow			: true,
				overlayOpacity		: 0.6,
				zoomSpeedIn			: 0,
				zoomSpeedOut		: 100,
				easingIn			: "swing",
				easingOut			: "swing",
				hideOnContentClick	: false, // important
				centerOnScroll		: true,
				imageScale			: true,
				autoDimensions		: "true",
				callbackOnShow		: function(){jQuery.fn.axZm();} // important!
			});
		}
		jQuery(document).ready(function(){
			setFancyBox();
			// jqDock menu
			jQuery(\'#menu1\').jqDock({
				align: \'right\',
				size: 70,
				distance: 90
			});
		
		});
		</script>
		');
		echo "</pre>";					
		
		
	
		echo "<h3>Body PHP:</h3>";
		echo "<pre class='brush: js;'>";
		echo htmlspecialchars ('
			echo "<div id=\'menu1\'>";
			foreach ($pic_list_array as $k=>$v){
				// Apply a filter
				if (stristr($v,\'bag\') OR stristr($v,\'belt\')){
					echo "<a class=\'thumbDemoLink\' href=\"/axZm/zoomLoad.php?zoomLoadAjax=1&zoomID=".$k."&zoomDir=".$_GET[\'zoomDir\']."&example=".$_GET[\'example\']."\">";
						echo "<img src=\'".$axZmH->composeFileName($zoom[\'config\'][\'gallery\'].$v,\'180x180\',\'_\')."\' alt=\'\' title=\'\'>";
					echo "</a>";
				}
			}
			echo "</div>";
		');
		echo "</pre>";				
	}
	
	elseif ($example == 7){
		echo "<h3>Head PHP:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
			if(!session_id()){session_start();}
			unset ($_SESSION[\'imageZoom\']);
			$_SESSION[\'imageZoom\']=array();
			
			$_GET[\'example\'] = 9;
			
			if (!isset($_GET[\'zoomDir\'])){
				$_GET[\'zoomDir\'] = \'fashion\';
				$_GET[\'zoomID\'] = 8;
			}
			
			// Contains all needed classes and other files
			require ("../axZm/zoomInc.inc.php");
			
			// Returns AJAX-ZOOM CSS files
			echo $axZmH->drawZoomStyle($zoom);
			
			// Returns AJAX-ZOOM javascript files 
			echo $axZmH->drawZoomJs($zoom, $exclude = array());
			
		');
		echo "</pre>";
		
		echo "<h3>Body PHP:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
			echo "<DIV id=\'zoomInlineContent\' style=\'float: left; margin: 0px 10px 10px 0px;\'>";
			
			// Returns all needed HTML for AJAX-ZOOM. It is visible in the source code of a page.
			echo $axZmH->drawZoomBox($zoom, $zoomTmp);
			
			// Returns the configuration parameters
			echo $axZmH->drawZoomJsConf($zoom, $rn = false, $pack = true);
			
			// Returns the init function
			echo $axZmH->drawZoomJsLoad($zoom, $pack = true, $windowLoad = true);
			echo "</DIV>";
			
			// Draw the thumbs
			foreach ($zoom[\'config\'][\'pic_list_array\']as $k=>$v){
				echo "<a href=\"#\" onClick=\"jQuery.fn.axZm.zoomSwitch(\'$k\'); return false;\">
				<img src=\'".$axZmH->composeFileName($zoom[\'config\'][\'gallery\'].$v, 
				$zoom[\'config\'][\'galleryFullPicDim\'],\'_\')."\' border=\'0\' class=\'outerimg\'></a>";
			}		
			
			// Draw prev, next links
			echo "<input type=\'button\' value=\'<<\' onClick=\"jQuery.fn.axZm.zoomPrevNext(\'prev\')\">&nbsp;&nbsp;";
			echo "<input type=\'button\' value=\'>>\' onClick=\"jQuery.fn.axZm.zoomPrevNext(\'next\')\">";
			
		');
		echo "</pre>";
	}
	
	elseif ($example == 8){
		echo "<h3>Head PHP:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
			if(!session_id()){session_start();}
			unset ($_SESSION[\'imageZoom\']);
			$_SESSION[\'imageZoom\']=array();
			
			// Simulate dynamic configuration
			$_GET[\'example\'] = 9;
			
			// Simulate custom parameters
			if (!isset($_GET[\'zoomDir\'])){
				$_GET[\'zoomDir\'] = 5;
				$_GET[\'zoomID\'] = 13;
			}
			
			// Include the ajax zoom class and all needed php files
			require ("../axZm/zoomInc.inc.php");
			
			// This file (example8.php) is also used as ajax request target for the custom gallery outside the ajax zoom
			// An other words when the user clicks on a thumb to the right the file loads itself :-)
			if ($_GET[\'newGal\']){
				foreach ($zoom[\'config\'][\'pic_list_array\'] as $k=>$v){
					echo "<a href=\"#\" class=\"outerContainer\" 
					onClick=\"jQuery.fn.axZm.zoomSwitch(\'$k\'); return false;\">
					<DIV class=\"outerimg\" 
					style=\"background-image: url(".$axZmH->composeFileName($zoom[\'config\'][\'gallery\'].$v,$zoom[\'config\'][\'galleryFullPicDim\'],\'_\').") \">
					</DIV></a>";
				}
				
				// The above loop is the only information needed, so exit here...
				exit;
			}
		');
		echo "</pre>";
	
	
	
		echo "<h3>Head Stylesheets and JS files:</h3>";
		echo "<pre class='brush: html;'>";
		echo htmlspecialchars ('
			<!-- Include css for AJAX-ZOOM --> 
			<link rel="stylesheet" href="/axZm/axZm.css" type="text/css" media="screen">
	
			<!-- Include jQuery and AJAX-ZOOM javascripts --> 
			<script type="text/javascript" src="/axZm/plugins/jquery-1.7.2.js"></script>
			<script type="text/javascript" src="/axZm/jquery.axZm.js"></script>
		');
		echo "</pre>";		
	
	
		echo "<h3>Head Stylesheets and JS files:</h3>";
		echo "<pre class='brush: js;'>";
		echo htmlspecialchars ('
			<script type="text/javascript">
				
				// Example function to load custom gallery over ajax
				function loadNewGal(id){
					jQuery.ajax({
						url: \'<?php echo $_SERVER[\'PHP_SELF\'];?>\',
						data: \'zoomDir=\'+id+\'&newGal=1\',
						success: function (data){
							jQuery(\'#picThumbs\').html(data);
						}
					});		
				}
				
				// The above function is triggered after page is loaded
				jQuery(window).load(function () {
					// Init the gallery
					loadNewGal(<?php echo $_GET[\'zoomDir\'];?>);
				});
			
			</script>
		');
		echo "</pre>";	
	
		echo "<h3>Body PHP:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
			// Returns the html for ajax zoom
			echo $axZmH->drawZoomBox($zoom, $zoomTmp);
			
			// Returns dynamic javascript for ajax zoom
			echo $axZmH->drawZoomJsConf($zoom, $rn = false, $pack = true);
			
			// Returns javascript loader that triggers ajax zoom
			echo $axZmH->drawZoomJsLoad($zoom, $pack = true, $windowLoad = true);
			echo "</DIV>";
	
			/*
			// Clickable list of folders (can be any other parameter)
			echo "<ul>";
			foreach ($zoomTmp[\'folderArray\'] as $k=>$v){
				echo "<a href=\'#\' onClick=\"jQuery.fn.axZm.loadAjaxSet(\'example=".$_GET[\'example\']."&zoomDir=$k\'); loadNewGal($k); return false;\">$v</a> ";
			}
			echo "</ul>";
			*/
			
			echo "<div style=\'margin-bottom:10px\'>";
				echo "<select onChange=\"jQuery.fn.axZm.loadAjaxSet(\'example=".$_GET[\'example\']."&zoomDir=\'+this.value); loadNewGal(this.value);\">";
				foreach ($zoomTmp[\'folderArray\'] as $k=>$v){
					echo "<option value=\'$k\'";
					if (isset($_GET[\'zoomDir\'])){
						if ($_GET[\'zoomDir\']== $k OR $_GET[\'zoomDir\']== $v){
							echo \' selected\';
						}
					}
					echo ">$v</option>";
				}
				echo "</select> - Switch folder with AJAX";
			echo "</div>";
	
			echo "<DIV id=\'picThumbs\'></DIV>";
		');
		echo "</pre>";	
	
	}
	elseif ($example == 9){
	
	}
	elseif ($example == 10){
	
	}elseif ($example == 11){
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars('
		$zoom[\'config\'][\'pyrQual\'] = 80;
		$zoom[\'config\'][\'zoomDragAnm\'] = false;
		$zoom[\'config\'][\'zoomDragAjax\'] = 1500;
		$zoom[\'config\'][\'pyrLoadTiles\'] = true;
		$zoom[\'config\'][\'pyrTilesFadeInSpeed\'] = 1000;
		$zoom[\'config\'][\'pyrTilesFadeLoad\'] = 250;
		');
		echo "</pre>";
	}
	elseif ($example == 12){
		echo "<h3>Head PHP:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
			if(!session_id()){session_start();}
			unset ($_SESSION[\'imageZoom\']);
			$_SESSION[\'imageZoom\']=array();
			
			// Simulate dynamic configuration
			$_GET[\'example\'] = 15;
			
			$docRoot = $_SERVER[\'DOCUMENT_ROOT\'];
			if (substr($docRoot,-1) == \'/\'){$docRoot = substr($docRoot,0,-1);}
			$m=0; $zoomData = array();
			
			// Open some dirs
			foreach (glob($docRoot.\'/pic/zoom/\'.\'*\', GLOB_ONLYDIR) as $folder){
				// Constrain by selected folders
				$selectedFolders = array(\'boutique\', \'furniture\', \'objects\');
				if (basename($folder) != \'..\' && basename($folder) != \'.\' && in_array(basename($folder),$selectedFolders)){
					// Read files
					$handle = opendir($folder);
					while (false !== ($file = readdir($handle))){ 
						if (basename($file) != \'..\' && basename($file) != \'.\' && substr(basename($file),-3) == \'jpg\'){
							$m++;
							// Fill $zoomData with some values
							$zoomData[$m][\'p\']=basename($folder);
	
							$zoomData[$m][\'f\']=basename($file);
						}
					}
					closedir($handle);
				}
			}
			
			// Compress PHP array into a string and pass it as $_GET parameter
			$_GET[\'zoomData\'] = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), \'+/=\', \'-_,\');
			
			
			$docRoot = $_SERVER[\'DOCUMENT_ROOT\'];
			if (substr($docRoot,-1) == \'/\'){$docRoot = substr($docRoot,0,-1);}
			require ($docRoot.\'/axZm/zoomInc.inc.php\');
			
		');
		echo "</pre>";
	
		echo "<h3>Body PHP:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
			echo $axZmH->drawZoomBox($zoom, $zoomTmp);
			echo $axZmH->drawZoomJsConf($zoom, $rn = false, $pack = true);
			echo $axZmH->drawZoomJsLoad($zoom, $pack = true, $windowLoad = true);
		');
		echo "</pre>";	
	}
	
	elseif($example == 13){
		echo "<h3>Load AJAX-ZOOM in an iframe:</h3>";
		echo "<pre class='brush: js; html-script: true'>";
		echo htmlspecialchars ('
		<html>
		<head>
		</head>
		<body>
		<iframe src=\'example1.php?zoomDir=trasportation&zoomFile=mustang_1.jpg&example=16&iframe=1\' width=\'482\' height=\'370\' scrolling=\'no\' frameBorder=\'0\'></iframe>
		</body>
		</html>
		');
		echo "</pre>";	
		
		echo "
		<p>An other way to embed an iframe is to use javascript, which writes the same code, but with the javascript:</p>";
		echo "<pre class='brush: js; html-script: true'>";
		echo htmlspecialchars ('
		<html>
		<head>
		<script type=\'text/javascript\' src=\'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js\'></script>
		</head>
		<body>
		<div id=\'myImage123\'></div>
		<script type="text/javascript">
		var zoomUrl = {
			placeholderID: \'myImage123\',
			path: \'http://www.ajax-zoom.com/examples/example1.php\', // change to your website
			parameter: \'zoomDir=trasportation&zoomFile=mustang_2.jpg&example=16&iframe=1\',
			width: 482,
			height: 370,
			containerCss: \'margin: 0px 10px 10px 0px; float: left;\',
			descrHeight: 20,
			descrCssClass: \'descr\',
			descrText: \'Some text\'
		}
		</script>
		<script src="http://www.ajax-zoom.com/axZm/jquery.axZm.image.js" type="text/javascript"></script>
		</body>
		</html>
		');		
		echo "</pre>";	
	}
	elseif($example == 14){
		// inline of the file example14.php
	}
	elseif($example == 15){
		echo "<h3>Use custom loader:</h3>";
		echo "<pre class='brush: js; html-script: true'>";
		echo htmlspecialchars ('
			<script type="text/javascript">
			// Create new object
			var ajaxZoom = {}; 
			
			ajaxZoom.opt = {
				onBeforeStart: function(){
					jQuery(\'.zoomContainer\').css({backgroundColor: \'#FFFFFF\'});
					jQuery(\'.zoomLogHolder\').css({width: 70});			
				}
			};
			
			// Define the path to the axZm folder
			ajaxZoom.path = "../axZm/"; 
			
			// Define your custom parameter query string
			// By example=17 some settings are overridden in zoomConfigCustom.inc.php
			// 3dDir=/pic/zoom3d/Uvex_Occhiali is the absolute path to the directory with images
			ajaxZoom.parameter = "example=17&3dDir=/pic/zoom3d/Uvex_Occhiali"; 
			
			// The ID of the element where ajax-zoom has to be inserted into
			ajaxZoom.divID = "test";
			</script>
			
			<!-- Include the loader file -->
			<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>
		');
		echo "</pre>";
	}
	elseif($example == 16){
		
		echo "<h3>PHP part - define the images:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
			<?php
			// Create an array with images
			$zoomData[1][\'p\'] = \'/pic/zoom/fashion/\';
			$zoomData[1][\'f\'] = \'test_fashion1.png\';
			
			$zoomData[2][\'p\'] = \'/pic/zoom/fashion/\';
			$zoomData[2][\'f\'] = \'test_fashion3.png\';
		
			$zoomData[3][\'p\'] = \'/pic/zoom/fashion/\';
			$zoomData[3][\'f\'] = \'test_fashion2.png\';
		
			$zoomData[4][\'p\'] = \'/pic/zoom/fashion/\';
			$zoomData[4][\'f\'] = \'test_fashion4.png\';
			
			// Turn PHP array into string
			$_GET[\'zoomData\'] = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), \'+/=\', \'-_,\');
			?>
		');
		echo "</pre>";
		
		echo "<h3>Javascript part:</h3>";
		echo "<pre class='brush: js; html-script: true'>";
		echo htmlspecialchars ('
			<script type="text/javascript">
			// Create new object
			var ajaxZoom = {};
			
			// Callback functions
			ajaxZoom.opt = {
				onLoad: function(){
					// Hilight one of the thumbs
					jQuery("#thumb_"+jQuery.axZm.zoomID).css("borderColor", "blue");
					
					// Append custom toolbar to AJAX-ZOOM
					jQuery("#naviReplacement").appendTo("#zoomLayer").css({
						display: "block",
						left: 10,
						bottom: 10,
						zIndex: 111
					});
				},
				onImageChange: function(info){
					// Hilight selected thumb
					jQuery(".outerContainer").css("borderColor", "black");
					jQuery("#thumb_"+info.zoomID).css("borderColor", "blue");
				}
			};
			
			// Path to axZm folder
			ajaxZoom.path = "../axZm/";
			
			// Define your custom parameter query string
			// By example=18 some settings are overridden in zoomConfigCustom.inc.php
			// zoomData is the php array with images turned into string
			ajaxZoom.parameter = "zoomData=<?php echo $_GET["zoomData"]?>&example=18&zoomID=1"; 
			
			 // The ID of the element where ajax-zoom has to be inserted into
			ajaxZoom.divID = "test"; 
			</script>
			
			<!-- Include the loader file -->
			<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>
		');
		echo "</pre>";
	}
	elseif($example == 17){
		echo "<h3>PHP part - define the images:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
			<?php
			// Create an array with images
			$zoomData[1][\'p\'] = \'/pic/zoom/fashion/\';
			$zoomData[1][\'f\'] = \'test_fashion1.png\';
			
			// Turn PHP array into string
			$_GET[\'zoomData\'] = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), \'+/=\', \'-_,\');
			?>
		');
		echo "</pre>";
		
		echo "<h3>Javascript part:</h3>";
		echo "<pre class='brush: js; html-script: true'>";
		echo htmlspecialchars ('
			<script type="text/javascript">
			// Create new object
			var ajaxZoom = {};
			ajaxZoom.opt = {
				onLoad: function(){
					jQuery(\'#naviReplacement\').appendTo(\'#zoomLayer\').css({
						display: \'block\',
						left: 10,
						top: 540-32-10,
						zIndex: 111
					});
				}
			};
			// Path to the axZm folder
			ajaxZoom.path = \'../axZm/\'; 
			
			// Define your custom parameter query string
			// By example=19 some settings are overridden in zoomConfigCustom.inc.php
			// zoomData is the php array with images turned into string
			ajaxZoom.parameter = \'zoomData=<?php echo $_GET[\'zoomData\']?>&example=19\'; 
			
			// The id of the Div where ajax-zoom has to be inserted into
			ajaxZoom.divID = \'test\'; 
			</script>
			
			<!-- Include the loader file -->
			<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>
	
		');
		echo "</pre>";
	}
	
	elseif($example == 18){
		echo "<h3>Head Stylesheets and JS files:</h3>";
		echo "<pre class='brush: html;'>";
		echo htmlspecialchars ('
			<!-- Include css for AJAX-ZOOM --> 
			<link rel="stylesheet" href="../axZm/axZm.css" type="text/css" media="screen">
	
			<!-- Include jQuery and AJAX-ZOOM javascripts --> 
			<script type="text/javascript" src="../axZm/plugins/jquery-1.7.2.js"></script>
			<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>
		');
		echo "</pre>";	
	
		echo "<h3>Body PHP:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
			// Select a set of custom settings in zoomConfigCustom.inc.php
			$_GET[\'example\'] = 20; 
			
			// Define the images directly here
			// There can be just one or more...
			$zoomData = array();
			
			$zoomData[1][\'p\'] = \'/pic/zoom/animals/\'; // Path to image
			$zoomData[1][\'f\'] = \'test_animals1.png\'; // Image filename
			
			$zoomData[2][\'p\'] = \'/pic/zoom/animals/\';
			$zoomData[2][\'f\'] = \'test_animals2.png\';
	
			$zoomData[3][\'p\'] = \'/pic/zoom/boutique/\';
			$zoomData[3][\'f\'] = \'test_boutique1.png\';
	
			$zoomData[4][\'p\'] = \'/pic/zoom/boutique/\';
			$zoomData[4][\'f\'] = \'test_boutique2.png\';
			
			$zoomData[5][\'p\'] = \'/pic/zoom/boutique/\';
			$zoomData[5][\'f\'] = \'test_boutique3.png\';
	
			$zoomData[6][\'p\'] = \'/pic/zoom/estate/\';
			$zoomData[6][\'f\'] = \'test_estate1.png\';
	
			$zoomData[7][\'p\'] = \'/pic/zoom/estate/\';
			$zoomData[7][\'f\'] = \'test_estate2.png\';
			
			$zoomData[8][\'p\'] = \'/pic/zoom/estate/\';
			$zoomData[8][\'f\'] = \'test_estate3.png\';	
	
			$zoomData[9][\'p\'] = \'/pic/zoom/random/\';
			$zoomData[9][\'f\'] = \'test_random1.png\';
	
			$zoomData[10][\'p\'] = \'/pic/zoom/random/\';
			$zoomData[10][\'f\'] = \'test_random2.png\';
			
			$zoomData[11][\'p\'] = \'/pic/zoom/random/\';
			$zoomData[11][\'f\'] = \'test_random3.png\';	
		
			// Turn above array into string
			$_GET[\'zoomData\'] = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), \'+/=\', \'-_,\');
			
			// Include all classes etc.
			require (\'../axZm/zoomInc.inc.php\');
			
			// Html output
			echo $axZmH->drawZoomBox($zoom, $zoomTmp);
			
			// JS config parameters from zoomConfig.inc.php and zoomConfigCustom.inc.php
			echo $axZmH->drawZoomJsConf($zoom, $rn = false, $pack = true);
			
			// JS load AJAX-ZOOM
			echo $axZmH->drawZoomJsLoad($zoom, $pack = true, $windowLoad = true);
		');
		echo "</pre>";
	}
	
	elseif($example == 19){
		echo "<h3>Head Javascript:</h3>";
		echo "<pre class='brush: html;'>";
		echo htmlspecialchars ('
			<!-- jQuery core, needed! -->
			<script type="text/javascript" src="../axZm/plugins/jquery-1.7.2.min.js"></script>
		');
		echo "</pre>";
		
		echo "<h3>Body Javascript:</h3>";
		echo "<pre class='brush: javascript;'>";
		echo htmlspecialchars ('
				<script type="text/javascript">
				
				// Create new object
				var ajaxZoom = {}; 
				
				ajaxZoom.opt = {
					// Change some CSS, not needed
					onBeforeStart: function(){
						jQuery(\'.zoomContainer\').css({backgroundColor: \'#000000\'});
						jQuery(\'.zoomLogHolder\').css({width: 70});			
					}
				};
				
				// Define the path to the axZm folder
				ajaxZoom.path = "../axZm/"; 
				
				// Define your custom parameter query string
				// zoomCueFrames are the filter for key frames showing in the gallery
				// example=21 -> overwrites some default parameter in zoomConfigCustom.inc.php after elseif ($_GET[\'example\'] == 21)
				ajaxZoom.parameter = "zoomCueFrames=1,3,5,8,10,12&example=21&3dDir=/pic/zoom3d/Uvex_Occhiali"; 
				
				// The ID of the element where ajax-zoom has to be inserted into
				ajaxZoom.divID = "test";
				</script>
				
				<!-- Include the loader file -->
				<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>
	
		');
		echo "</pre>";		
	}
	
	elseif($example == 20){
		echo "<h3>Head Stylesheets and JS files:</h3>";
		echo "<pre class='brush: html;'>";
		echo htmlspecialchars ('
			<!-- CSS for jcarousel to show the thumbs under the image -->
			<link href="../axZm/plugins/demo/jcarousel/skins/custom/skin.css" type="text/css" rel="stylesheet" />
			
			<!-- jQuery core, needed! -->
			<script type="text/javascript" src="../axZm/plugins/jquery-1.7.2.min.js"></script>
			
			<!-- jcarousel js for thumbnails -->
			<script type="text/javascript" src="../axZm/plugins/demo/jcarousel/lib/jquery.jcarousel.min.js"></script>
		');
		echo "</pre>";
		
		echo "<h3>Head CSS:</h3>";
		echo "<pre class='brush: css;'>";
		echo htmlspecialchars ('
			<style type="text/css" media="screen"> 			
				/* Thumbnails in jcarousel */
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
		');
		echo "</pre>";	
		
		
		echo "<h3>Body PHP:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
	
			// Define the images that will be passed to AJAX-ZOOM
			// In your application this array should be build dynamically
			// The indexes can be any integer > 0
						
			$zoomData[1][\'p\'] = \'/pic/zoom/fashion/\'; // Path to image
			$zoomData[1][\'f\'] = \'test_fashion1.png\'; // Image filename
			
			$zoomData[2][\'p\'] = \'/pic/zoom/fashion/\';
			$zoomData[2][\'f\'] = \'test_fashion2.png\';
	
			$zoomData[3][\'p\'] = \'/pic/zoom/fashion/\';
			$zoomData[3][\'f\'] = \'test_fashion3.png\';
	
			$zoomData[4][\'p\'] = \'/pic/zoom/fashion/\';
			$zoomData[4][\'f\'] = \'test_fashion4.png\';
			
			// Encode and compress the array with images
			// It will be decoded in zoomObjects.inc.php : $_GET[\'zoomData\'] = $axZmH->uncompress($_GET[\'zoomData\']);
			// You can replace or remove this encoding procedure when using AJAX-ZOOM on not PHP pages
			// In this case it is important that in zoomObjects.inc.php the $_GET[\'zoomData\'] will turn into PHP array again (instead of $_GET[\'zoomData\'] = $axZmH->uncompress($_GET[\'zoomData\'])) 
			// If you have any questions on this or other issues please contact the support
			
			$_GET[\'zoomData\'] = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), \'+/=\', \'-_,\');
		
	 
		');
		echo "</pre>";	
		
		echo "<h3>Body HTML:</h3>";
		echo "<pre class='brush: html;'>";
		echo htmlspecialchars ('
			<!-- Wrapper for media data-->
			<DIV style=\'float: left; width: 250px; min-height: 400px; margin-right: 20px\'>
	
				<!-- Container for preview image (AJAX-ZOOM "image map") -->
				<DIV id=\'mapContainer\' style=\'position: absolute; width: 250px; height: 375px;\'></DIV>
	
				<!-- Container for zoomed image (will be displayed to the right from preview image) -->
				<DIV id=\'zoomedAreaDiv\' style=\'display: none; float: left; min-width: 450px; min-height: 450px; position: absolute; z-index: 20;\'></DIV>
				
				<!-- Touch devices additional control -->
				<DIV id="touchDevicesZoomButtons" style="clear: both; width: 250px; padding: 0; margin: 0; height: 20px; position: absolute; display: none;">
					<a href=\'javascript:void(0)\' onClick="jQuery.touchDevicesZoomOn()">ENABLE ZOOM</a> | 
					<a href=\'javascript:void(0)\' onClick="jQuery.touchDevicesZoomOff()">DISABLE ZOOM</a>
				</DIV>
	
				<!-- Navi replacement (plus and minus buttons for zooming) -->
				<DIV id="naviReplacement" style="text-align: left; position: absolute; display: none;">
					<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomIn({speed: 750, ajxTo: 1000, pZoom: 25})" style="outline-style: none;"><img src="../axZm/icons/zi_32x32.png" border="0" ></a>
					<a href="javascript: void(0)" onclick="jQuery.fn.axZm.zoomOut({speed: 750, ajxTo: 1000, pZoom: 25})" style="outline-style: none;"><img src="../axZm/icons/zo_32x32.png" border="0"></a>
				</DIV>	
				
				<!-- jcarousel with thumbs (will be filled with thumbs by javascript) -->
				<DIV id="jcarouselContainer" style="clear: both; width: 250px; position: absolute; display: none;">
					<ul id="mycarousel" class="jcarousel-skin-custom"></ul>
				</DIV>
				
			</DIV>
		');
		echo "</pre>";
	
		echo "<h3>Body Javascript:</h3>";
		echo "<pre class='brush: javascript;'>";
		echo htmlspecialchars ('
			<script type="text/javascript">
			// SyntaxHighlighter is just for the demo to show the source code, should be removed!
			SyntaxHighlighter.all();
			
			// Horizontal offset
			var zoomedAreaOffsetRight = 10;
			
			// Vertical offset
			var zoomedAreaOffsetTop = 0;
			
			// ID of zoomed area
			var zoomedAreaDiv = \'zoomedAreaDiv\';
			
			// Additional control functions for touch devices
			jQuery.touchDevicesZoomOn = function(){
				jQuery(\'#mapContainer, #\'+zoomedAreaDiv).unbind();
				jQuery(\'#zoomMapSel\').css(\'display\', \'block\');
				jQuery(\'#naviReplacement\').stop(true, false).css({display: \'block\', \'opacity\': 1});
				var relID = \'mapContainer\'; //  zoomMapHolder
				var rOffset = jQuery(\'#\'+relID).offset();
				jQuery(\'#\'+zoomedAreaDiv).stop(true, false).css({
					opacity: 1, 
					display: \'block\',
					left: Math.round(rOffset.left + jQuery(\'#\'+relID).width() + zoomedAreaOffsetRight),
					top: Math.round(rOffset.top + zoomedAreaOffsetTop)
				});
			};
			
			jQuery.touchDevicesZoomOff = function(){
				jQuery(\'#mapContainer, #\'+zoomedAreaDiv).unbind();
				jQuery(\'#zoomMapSel\').css(\'display\', \'none\');
				jQuery(\'#naviReplacement\').stop(true, false).css(\'opacity\', 0.0);
				jQuery(\'#\'+zoomedAreaDiv).stop(true, false).css({
					opacity: 0, 
					display: \'none\'
				});	
			};
			
			var ajaxZoom = {};
			
			// AJAX-ZOOM callbacks
			ajaxZoom.opt = {
			
				// AJAX-ZOOM callback triggered after AJAX-ZOOM is loaded
				onLoad: function(){
					//  zoomMapHolder
					var relID = \'mapContainer\'; 
					
					// Icons for zoomIn and zoomOut, not necessary
					jQuery(\'#naviReplacement\').appendTo(\'#mapContainer\').css({
						left: 10,
						top: 10,
						zIndex: 9999,
						opacity: (jQuery.browser.msie ? \'\' : 0)
					});
					
					// Background for zoom level, not necessary
					jQuery(\'<div />\').attr(\'id\', \'zoomLevelWrap\').css({
						position: \'absolute\',
						left: 0,
						top: 0,
						backgroundColor: \'#000000\',
						opacity: 0.3,
						width: 40,
						height: 20,
						zIndex: 9998
					}).appendTo(\'#zoomLayer\');
					
					// Zoom level, not necessary
					jQuery(\'#zoomLevel\').appendTo(\'#zoomLayer\').css({
						position: \'absolute\',
						color: \'#FFFFFF\',
						width: 40,
						padding: 3,
						margin: 0,
						fontSize: \'10pt\',
						display: \'block\',
						left: 0,
						top: 0,
						zIndex: 9999
					});
					
					// Some helper functions
					function getl(sep, str){
						return str.substring(str.lastIndexOf(sep)+1);
					}
					
					function getf(sep, str){
						var extLen = getl(sep, str).length;
						return str.substring(0, (str.length - extLen - 1));
					}
					
					function cfn(file){
						var full = \'_\'+jQuery.axZm.galFullPicX+\'x\'+jQuery.axZm.galFullPicY;
						return getf(\'.\', file)+full+\'.jpg\';
					}
					
					// Detect iphone
					function touchDevicesZoomTest() {
						if(/KHTML|WebKit/i.test(navigator.userAgent) && (\'ontouchstart\' in window)) {
							return true;
						}else{
							return false;
						}
					}
					
					// Hide zoom selector if mouse is not over
					jQuery(\'#zoomMapSel\').css(\'display\', \'none\');
	
					// Get the position of the preview image (AJAX-ZOOM "image map")
					var rPosition = jQuery(\'#\'+relID).position();
	
					// Position the jcarousel container below the preview image
					jQuery(\'#jcarouselContainer\').css({
						top: rPosition.top+jQuery(\'#\'+relID).height()+10,
						display: \'block\'
					});
					
					// Put thumbnails (generated by AJAX-ZOOM) into jcarousel container
					// jQuery.axZm.zoomGA is a JS object containing information about the images in the gallery
					// All thumbnails are created on the fly while loading first time
					jQuery.each(jQuery.axZm.zoomGA, function(k,v){
							var li = jQuery(\'<li />\');
							var a = jQuery(\'<a />\').addClass(\'outerContainer\').bind(\'click\',function(){jQuery.fn.axZm.zoomSwitch(k); return false;});
							var div = jQuery(\'<div />\').addClass(\'outerimg\').css(\'backgroundImage\', \'url(\'+jQuery.axZm.zoomGalDir+cfn(v.img)+\')\');
							jQuery(div).appendTo(a);
							jQuery(li).append(a).appendTo(\'#mycarousel\');
					});
					
					// Init jcarousel
					jQuery(\'#mycarousel\').jcarousel();
					
					// Dedect touch devices and add switch interface for them
					if (touchDevicesZoomTest()){
						// Add switch interface, can and should be styled as you want
						jQuery(\'#touchDevicesZoomButtons\').css({
							display: \'block\',
							top: rPosition.top+jQuery(\'#\'+relID).height()+10,
							zIndex: 99999
						});
						// Move the thumbnail container a little below
						jQuery(\'#jcarouselContainer\').css({
							top: parseInt(jQuery(\'#jcarouselContainer\').css(\'top\'))+jQuery(\'#touchDevicesZoomButtons\').height()
						});
					}
					
					
					// Mouseenter on preview image (AJAX-ZOOM "image map") function
					jQuery(\'#mapContainer\').bind(\'mouseenter\', function(){
						if (jQuery.removeHoverTimeout){clearTimeout(jQuery.removeHoverTimeout);}
	
						// Position AJAX-ZOOM area to the right of zoom map
						var rOffset = jQuery(\'#\'+relID).offset();
						jQuery(\'#\'+zoomedAreaDiv).stop(true, false).css({
							display: \'block\',
							opacity: 1,
							left: Math.round(rOffset.left + jQuery(\'#\'+relID).width() + zoomedAreaOffsetRight),
							top: Math.round(rOffset.top + zoomedAreaOffsetTop)
						});
						
						// Show zoom selector
						jQuery(\'#zoomMapSel\').css(\'display\', \'block\');
						
						if (!jQuery.browser.msie){
							jQuery(\'#naviReplacement\').stop(true, false).css({
								opacity: 1,
								display: \'block\'
							});
						}
					});
	 
					// Mouseleave on preview image (AJAX-ZOOM "image map") and the actual zoom area function
					jQuery(\'#mapContainer, #\'+zoomedAreaDiv).bind(\'mouseleave\', function(){
						jQuery.removeHoverTimeout = setTimeout(function(){
							jQuery(\'#\'+zoomedAreaDiv).stop(true, false).fadeTo(500, 0, function(){
								jQuery(this).css(\'display\', \'none\');
								jQuery(\'#zoomMapSel\').css(\'display\', \'none\');
							}); 
							if (!jQuery.browser.msie){
								jQuery(\'#naviReplacement\').stop(true, false).fadeTo(500, 0.0);
							}else{
								jQuery(\'#naviReplacement\').stop(true, false).css(\'display\', \'none\');
							}
						}, 300);
					});
					
					// Prevent closing zoom area when mouse is over it. 
					jQuery(\'#\'+zoomedAreaDiv).bind(\'mouseenter\', function(){
						if (jQuery.removeHoverTimeout){clearTimeout(jQuery.removeHoverTimeout);}
					});
				},
				onFullScreenClose: function(){
					jQuery(\'#mapContainer\').trigger(\'mouseleave\');
				},
				onMapMouseOverClick: function(){ // onMapMouseOverDbClick
					jQuery.fn.axZm.initFullScreen();
				}
			};
			
			// Path to the axZm folder (relative or absolute)
			ajaxZoom.path = \'../axZm/\'; 
			
			// Needed parameter query string
			// example=22 -> overwrites some default parameter in zoomConfigCustom.inc.php after elseif ($_GET[\'example\'] == 22)
			ajaxZoom.parameter = \'zoomData=<?php echo $_GET[\'zoomData\']?>&example=22\'; 
			
			// The id of the Div where ajax-zoom has to be inserted into
			ajaxZoom.divID = zoomedAreaDiv; 
			</script>
			
			<!-- AJAX-ZOOM loader file -->
			<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>
		');
		echo "</pre>";	
		
	}
	
	elseif ($example == 21){
	
		echo "<h3>Head PHP:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
			if(!session_id()){session_start();}
			unset ($_SESSION[\'imageZoom\']);
			$_SESSION[\'imageZoom\']=array();
			
			$_GET[\'example\'] = 2;
			
			if (!isset($_GET[\'zoomDir\'])){
				$_GET[\'zoomDir\'] = \'animals\';
			}else if(isset($_GET[\'zoomDir\']) AND !isset($_GET[\'previewPic\'])){
				$getDir=1;
			}
			
			require (\'../axZm/zoomInc.inc.php\');
			
			/**
			  * @param array $pic_list_array Array with images
			  * @param array $zoom Configuration array
			  * @return onject $axZmH Class instance
			  **/
			 
			function zoomThumbs($pic_list_array, $zoom, $axZmH){
				$return = \'\';
				foreach ($pic_list_array as $k=>$v){
					$return .= "<DIV class=\'thumbDemoBack\'>";
						$return .= "<DIV class=\"thumbDemo\" style=\'background-image:url(".$_SERVER[\'PHP_SELF\'].\'?zoomDir=\'.$_GET[\'zoomDir\'].\'&example=\'.$_GET[\'example\'].\'&previewPic=\'.$v.");\'>";
						$return .= "<a class=\"thumbDemoLink\" onClick=\"jQuery.fn.axZm.openFullScreen(\'/axZm/\', \'zoomID=".$k."&zoomDir=".$_GET[\'zoomDir\']."&example=".$_GET[\'example\']."\');\" href=\"javascript: void(0)\"><img src=\'".$zoom[\'config\'][\'icon\']."empty.gif\' class=\'thumbDemoImg\' border=\'0\'></a>";
						$return .= "</DIV>";
					$return .= "</DIV>";
				}			
				return $return;
			}
			
			// On the fly generation of thumbs
			if (isset($_GET[\'previewPic\'])){
				ob_start();
				$path = $zoom[\'config\'][\'picDir\'];
			
				$w = 100;
				$h = 100;
				$fillThumb = false;
				
				$ww = $w;
				$hh = $h;
				
				if ($fillThumb){
					$ratio = 1;
					$imgSize = getimagesize($path.urldecode($_GET[\'previewPic\']));
					if ($imgSize[0] > $imgSize[1]){
						$ratio = $imgSize[0] / $imgSize[1];
					} elseif ($imgSize[1] > $imgSize[0]){
						$ratio = $imgSize[1] / $imgSize[0];
					}
					$ww = $ww * $ratio;
					$hh = $hh * $ratio;
				}
				
				if ($axZmH->isValidPath($path)){
					$axZm->rawThumb($zoom, $path, urldecode($_GET[\'previewPic\']), round($ww), round($hh), 90, true);
				}
				ob_end_flush();
				exit;
			}
			
			// Return the thumbs list
			if ($getDir){
				echo zoomThumbs($pic_list_array, $zoom, $axZmH);
				exit;
			}
		');
		echo "</pre>";
	
		echo "<h3>Head CSS:</h3>";
		echo "<pre class='brush: css;'>";
		echo htmlspecialchars ('
			<link rel="stylesheet" href="../axZm/axZm.css" type="text/css" media="screen">
			.thumbDemoBack {
				float: left; 
				width: 142px; 
				height: 139px; 
				margin-bottom: 5px; 
				margin-right: 7px;
				background-position: center center;
				background-repeat: no-repeat;
				background-image: url(/axZm/icons/thumb_back.png);
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
		');
		echo "</pre>";
	
		
		echo "<h3>Head Javascript:</h3>";
		echo "<pre class='brush: js;'>";
		echo htmlspecialchars ('
		<script type="text/javascript" src="../axZm/plugins/jquery-1.7.2.min.js"></script>
		<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>
	
		<script type="text/javascript">
			function changeDir(dir){
				jQuery.ajax({
					url: \'<?php echo $_SERVER[\'PHP_SELF\'];?>\',
					data: \'zoomDir=\'+dir,
					cache: false,
					success: function (data){
						jQuery(\'#galDiv\').html(data);
					}
				});		
			}
		</script>
		');
		echo "</pre>";
		
		echo "<h3>Body PHP:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('
			// Select list with folders to choose from
			echo "<select onChange=\"changeDir(this.value)\">";
			foreach ($zoomTmp[\'folderArray\'] as $k=>$v){
				echo "<option value=\'$k\'";
				if (isset($_GET[\'zoomDir\'])){
					if ($_GET[\'zoomDir\']== $k) OR $_GET[\'zoomDir\']== $v){
						echo \' selected\';
					}
				}
				echo ">$v</option>";
			}
			echo "</select> - Switch folder with AJAX (can be any other custom parameter(s))";
			
			// Reeturn thumbs
			echo zoomThumbs($pic_list_array, $zoom, $axZmH);
		');
		echo "</pre>";
	}
	
	elseif($example == 23){
	
		echo "<h3>Body PHP:</h3>";
		echo "<pre class='brush: php;'>";
		echo htmlspecialchars ('		
			// Define the images directly here
			// There can be just one or more...
			$zoomData = array();
			
			$zoomData[1][\'p\'] = \'/pic/zoom/animals/\'; // Path to image
			$zoomData[1][\'f\'] = \'test_animals1.png\'; // Image filename
			
			$zoomData[2][\'p\'] = \'/pic/zoom/animals/\';
			$zoomData[2][\'f\'] = \'test_animals2.png\';
	
			$zoomData[3][\'p\'] = \'/pic/zoom/boutique/\';
			$zoomData[3][\'f\'] = \'test_boutique1.png\';
	
			$zoomData[4][\'p\'] = \'/pic/zoom/boutique/\';
			$zoomData[4][\'f\'] = \'test_boutique2.png\';
			
			$zoomData[5][\'p\'] = \'/pic/zoom/boutique/\';
			$zoomData[5][\'f\'] = \'test_boutique3.png\';
	
			$zoomData[6][\'p\'] = \'/pic/zoom/estate/\';
			$zoomData[6][\'f\'] = \'test_estate1.png\';
	
			$zoomData[7][\'p\'] = \'/pic/zoom/estate/\';
			$zoomData[7][\'f\'] = \'test_estate2.png\';
			
			$zoomData[8][\'p\'] = \'/pic/zoom/estate/\';
			$zoomData[8][\'f\'] = \'test_estate3.png\';	
	
			$zoomData[9][\'p\'] = \'/pic/zoom/random/\';
			$zoomData[9][\'f\'] = \'test_random1.png\';
	
			$zoomData[10][\'p\'] = \'/pic/zoom/random/\';
			$zoomData[10][\'f\'] = \'test_random2.png\';
			
			$zoomData[11][\'p\'] = \'/pic/zoom/random/\';
			$zoomData[11][\'f\'] = \'test_random3.png\';	
		
			// Turn above array into string
			$_GET[\'zoomData\'] = strtr(base64_encode(addslashes(gzcompress(serialize($zoomData),9))), \'+/=\', \'-_,\');
		');
		echo "</pre>";
		
		echo "<h3>Body HTML / Javascript:</h3>";
		echo "<pre class='brush: html;'>";
		echo htmlspecialchars ('
			<DIV id=\'test\' style="min-width: 602px; min-height: 549px; background-color: #CCCCCC">Loading, please wait...</DIV>
			
			<script type="text/javascript">
			var ajaxZoom = {};
			
			// AJAX-ZOOM callbacks (optional)
			ajaxZoom.opt = {
			
			};
			
			// Path to the axZm folder (relative or absolute)
			ajaxZoom.path = "../axZm/";
			
			// Needed parameter query string
			// example=22 -> overwrites some default parameter in zoomConfigCustom.inc.php after elseif ($_GET[\'example\'] == 20)
			ajaxZoom.parameter = "zoomData=<?php echo $_GET[\'zoomData\']?>&example=20";
			
			// The id of the Div where ajax-zoom has to be inserted into
			ajaxZoom.divID = \'test\';
			</script>
		 
			<!-- AJAX-ZOOM loader file -->
			<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script> 		
		');
		echo "</pre>";
	}

}
?>