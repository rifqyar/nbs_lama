<?php
if(!session_id()){session_start();}

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>Ajax Zoom Demo Lightbox & Co. Examples - Ajax</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">";

 

if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
} 


echo "
<link rel=\"stylesheet\" href=\"../axZm/axZm.css\" media=\"screen\" type=\"text/css\">
<link rel=\"stylesheet\" href=\"../axZm/plugins/demo/colorbox/example4/colorbox.css\" media=\"screen\" type=\"text/css\">
<link rel=\"stylesheet\" href=\"../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.css\" media=\"screen\" type=\"text/css\">
";


echo "
<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/jquery.axZm.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/colorbox/jquery.colorbox.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.2.6.pack.js\"></script>
";

// SyntaxHighlighter is not needed
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
jQuery(document).ready(function() {
	
	// SyntaxHighlighter is not needed
	SyntaxHighlighter.all();
	
	// Colorbox example
	jQuery(".ajaxExampleColorbox").colorbox({
		initialWidth: 300,
		initialHeight: 300,
		scrolling: false,
		scrollbars: false,
		preloading: false,
		opacity: 0.95,
		onClosed: function(){$.fn.axZm.spinStop();},
		ajax: true, // this option has been added by ajax-zoom to enforce loading href as url and not image
	}, function(){
		jQuery.fn.axZm(); // Important callback after loading
	});
	
	// Colorbox fancybox example
	jQuery(".ajaxExampleFancybox").fancybox({
		padding				: 0,
		overlayShow			: true,
		overlayOpacity		: 0.9,
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
		// Newer fancybox versions have different callback names
		onComplete : function(){
			jQuery.fn.axZm(); // Important callback after loading
		},
		
		callbackOnClose : function(){
			$.fn.axZm.spinStop();
		},
		
		onClosed : function(){
			$.fn.axZm.spinStop();
		}

	});	
	
});

</script>
<style type="text/css" media="screen"> 
	body {margin:0px; padding:0px;}
	html {margin:0px; padding:0px; border: 0; font-family: Tahoma, Arial; font-size: 10pt;}
	form {padding:0px; margin:0px}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}
	p {text-align: justify; text-justify: newspaper;}
	.zoomHorGalleryDescr{
		display:none;
	}
</style>

<?php
echo "
</head>
<body>
";
include ('navi.php');
echo "<DIV style='width: 800px; margin: 0px auto;'>\n";
	
	echo "<DIV style='float: left; background-color: #FFFFFF; padding: 10px; margin: 5px; background-image: url(http://www.ajax-zoom.com/pic/zoomp/zoom_shot_1.jpg); background-repeat: no-repeat; background-position: 430px 10px;'>\n";
	
		echo "<h2>Ajax Zoom Lightbox & Co.<br>examples with Ajax content</h2>\n";
		
		echo "<DIV style='clear: both; margin-bottom: 10px; font-size:120%'>Load all images from a directory with zoomDir</DIV>"; 
		
		echo "<DIV style='float: left; clear: both; width: 150px;'>\n";
			echo "<strong>Fancybox</strong><br>";
			echo "<a class='ajaxExampleFancybox' href='../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=/pic/zoom/estate&example=4'>Example 1</a><br>";
			echo "<a class='ajaxExampleFancybox' href='../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=/pic/zoom/animals&zoomID=4&example=5'>Example 2</a><br>";
			echo "<a class='ajaxExampleFancybox' href='../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=/pic/zoom/trasportation&example=6'>Example 3</a><br>";
			echo "<a class='ajaxExampleFancybox' href='../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=7&zoomID=3&example=5'>Example 4</a><br>";
			echo "<a class='ajaxExampleFancybox' href='../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=12&zoomID=16&example=7'>Example 5</a>";
		echo "</DIV>\n";
		
		echo "<DIV style='float: left; width: 150px;'>\n";
			echo "<strong>Colorbox</strong><br>";
			echo "<a class='ajaxExampleColorbox' href='../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=/pic/zoom/estate&example=4'>Example 1</a><br>";
			echo "<a class='ajaxExampleColorbox' href='../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=/pic/zoom/animals&zoomID=4&example=5'>Example 2</a><br>";
			echo "<a class='ajaxExampleColorbox' href='../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=/pic/zoom/trasportation&example=6'>Example 3</a><br>";
			echo "<a class='ajaxExampleColorbox' href='../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=7&zoomID=3&example=5'>Example 4</a><br>";
			echo "<a class='ajaxExampleColorbox' href='../axZm/zoomLoad.php?zoomLoadAjax=1&zoomDir=12&zoomID=16&example=7'>Example 5</a>";
		echo "</DIV>\n";
		
		
		echo "<DIV style='clear: both; margin-bottom: 10px; margin-bottom: 10px; font-size:120%'>Load specified images with zoomData</DIV>";
		echo "<DIV style='float: left; clear: both; width: 150px;'>\n";
			echo "<strong>Fancybox</strong><br>";
			echo "<a class='ajaxExampleFancybox' href='../axZm/zoomLoad.php?zoomLoadAjax=1&example=4&zoomData=/pic/zoom/animals/test_animals1.png|/pic/zoom/boutique/test_boutique3.png|/pic/zoom/furniture/test_furniture3.png'>Example 6</a><br>";
			echo "<a class='ajaxExampleFancybox' href='../axZm/zoomLoad.php?zoomLoadAjax=1&example=7&zoomData=/pic/zoom/animals/test_animals2.png|/pic/zoom/boutique/test_boutique2.png|/pic/zoom/furniture/test_furniture2.png'>Example 7</a><br>";
		echo "</DIV>\n";		

		echo "<DIV style='float: left; width: 150px;'>\n";
			echo "<strong>Colorbox</strong><br>";
			echo "<a class='ajaxExampleColorbox' href='../axZm/zoomLoad.php?zoomLoadAjax=1&example=4&zoomData=/pic/zoom/animals/test_animals1.png|/pic/zoom/boutique/test_boutique3.png|/pic/zoom/furniture/test_furniture3.png'>Example 6</a><br>";
			echo "<a class='ajaxExampleColorbox' href='../axZm/zoomLoad.php?zoomLoadAjax=1&example=7&zoomData=/pic/zoom/animals/test_animals2.png|/pic/zoom/boutique/test_boutique2.png|/pic/zoom/furniture/test_furniture2.png'>Example 7</a><br>";
		echo "</DIV>\n";	
		
		echo "<DIV style='clear: both; margin-bottom: 10px; margin-bottom: 10px; font-size:120%'>Load 360 / 3D images</DIV>";
		echo "<DIV style='float: left; clear: both; width: 150px;'>\n";
			echo "<strong>Fancybox</strong><br>";
			echo "<a class='ajaxExampleFancybox' href='../axZm/zoomLoad.php?zoomLoadAjax=1&example=17&3dDir=/pic/zoom3d/Uvex_Occhiali'>360 Example</a><br>";
		echo "</DIV>\n";		

		echo "<DIV style='float: left; width: 150px;'>\n";
			echo "<strong>Colorbox</strong><br>";
			echo "<a class='ajaxExampleColorbox' href='../axZm/zoomLoad.php?zoomLoadAjax=1&example=17&3dDir=/pic/zoom3d/Uvex_Occhiali'>360 Example</a><br>";
		echo "</DIV>\n";			


		/* Descriptions example*/
		
		echo "<DIV style='clear: both; margin-bottom: 10px; margin-bottom: 10px; font-size:120%'>Load specified images with zoomData and play with descriptions</DIV>";

		// Fancybox will be added later
		echo "<DIV style='float: left; clear: both; width: 150px;'>\n";
			echo "<strong>Fancybox</strong><br>";
		echo "</DIV>\n";	

		echo "<DIV style='float: left; width: 150px;'>\n";
			echo "<strong>Colorbox</strong><br>";
			echo "<a class='ajaxExampleColorboxDesc' href='../axZm/zoomLoad.php?zoomLoadAjax=1&example=4&zoomData=/pic/zoom/animals/test_animals1.png|/pic/zoom/boutique/test_boutique3.png|/pic/zoom/furniture/test_furniture3.png'>Example 8</a><br>";
			echo "<a class='ajaxExampleColorboxDesc' href='../axZm/zoomLoad.php?zoomLoadAjax=1&example=7&zoomData=/pic/zoom/animals/test_animals2.png|/pic/zoom/boutique/test_boutique2.png|/pic/zoom/furniture/test_furniture2.png'>Example 9</a><br>";
		echo "</DIV>\n";

		?>
				
		<script type="text/javascript">
		
		// Simple function to put descriptions in a div with fade effect
		function ajaxZoomAnimateDescr(title, descr){
			jQuery("#testTitleDiv").fadeTo(300,0, function(){
				jQuery(this).empty().html(title).fadeTo(300,1);
			});
		
			jQuery("#testDescrDiv").fadeTo(300,0, function(){
				jQuery(this).empty().html(descr).fadeTo(300,1);
			});
		}
		
		// Callbacks to pass to jQuery.fn.axZm() function
		var ajaxZoomCallbacks = {
			onLoad: function(){
				// Container for external title and description
				var testTitleDescrContainer = jQuery('<DIV />').css({
					clear: 'both', 
					padding: '5px 10px 10px 10px',
					backgroundColor: '#1D1D1A'
				});
				
				// Title div
				jQuery('<DIV />').attr('id', 'testTitleDiv').css({
					minHeight: 30,
					fontSize: '16pt',
					color: '#D3D3D3'
				}).appendTo(testTitleDescrContainer);
				
				// Description div
				jQuery('<DIV />').attr('id', 'testDescrDiv').css({
					minHeight: 40,
					fontSize: '10pt',
					color: '#FFFFFF'
				}).appendTo(testTitleDescrContainer);				
				
				// Margin div
				jQuery('<DIV />').css({
					minHeight: 10,
					clear: 'both'
				}).appendTo('#zoomAll');				
				
				// Append everything above after the player, could also be inside...
				testTitleDescrContainer.appendTo('#zoomAll');

				// Resize the colorbox
				jQuery.colorbox.resize();
				
				// Current image name
				var getImgName = jQuery.axZm.zoomGA[jQuery.axZm.zoomID]["img"];
				
				// Set descriptions and title
				ajaxZoomAnimateDescr(testTitle[getImgName], testDescr[getImgName]);
			
				// Titles of the thumbs
				jQuery.each(thumbTitle, function (fName, descr, download){
					jQuery.fn.axZm.setDescr(fName, testTitle[fName], descr, download);
				});
			}, 
			onImageChange: function(info){
				// Current image name
				var getImgName = jQuery.axZm.zoomGA[jQuery.axZm.zoomID]["img"];
				
				// Set descriptions and title
				ajaxZoomAnimateDescr(testTitle[getImgName], testDescr[getImgName]);
			}
		};
		
		// Init (php array like) js objects to store descriptions and titles
		var testTitle = {}; // Object with titles
		var testDescr = {}; // Object with longer descriptions
		var thumbTitle = {}; // Object with thumb descriptions
		
		testTitle["test_animals1.png"] = "Description animals 1";
		testDescr["test_animals1.png"] = "Long description animals 1 image file...";
		thumbTitle["test_animals1.png"] = "animals 1";

		testTitle["test_animals2.png"] = "Description animals 2";
		testDescr["test_animals2.png"] = "Long description animals 2 image file...";
		thumbTitle["test_animals2.png"] = "animals 2";

		testTitle["test_boutique2.png"] = "Description boutique 2";
		testDescr["test_boutique2.png"] = "Long description boutique 2 image file...";
		thumbTitle["test_boutique2.png"] = "boutique 2";

		testTitle["test_boutique3.png"] = "Description boutique 3";
		testDescr["test_boutique3.png"] = "Long description boutique 3 image file...";
		thumbTitle["test_boutique3.png"] = "boutique 3";

		testTitle["test_furniture2.png"] = "Description furniture 2";
		testDescr["test_furniture2.png"] = "Long description furniture 2 image file...";
		thumbTitle["test_furniture2.png"] = "furniture 2";
		
		testTitle["test_furniture3.png"] = "Description furniture 3";
		testDescr["test_furniture3.png"] = "Long description furniture 3 image file...";
		thumbTitle["test_furniture3.png"] = "furniture 3";		
		
		// Colorbox example
		jQuery(".ajaxExampleColorboxDesc").colorbox({
			initialWidth: 300,
			initialHeight: 300,
			scrolling: false,
			scrollbars: false,
			preloading: false,
			opacity: 0.95,
			ajax: true // this option has been added by ajax-zoom to enforce loading href as url and not image
		}, function(){
			jQuery.fn.axZm(ajaxZoomCallbacks); // Important callback after loading
		});
		
		</script>
		
		<?
		

		echo "<DIV style='clear: both;'>\n";
			echo "<DIV style='float: right; width: 360px; height: 150px;'></DIV>";
			?>
			<p style="padding-top:20px; font-size:120%">
			This example demonstrates how to open multiple zoom galleries with some lightbox clones (please click on the links above). 
			The content is loaded via Ajax requests. 
			This solution does not work "cross domain". 
			</p>
			<p style="font-size:120%">
			Please note, that not all lightbox clones fully suport ajax content.
			</p> 
			<p style="font-size:120%; font-weight: bold">
			When you have jpg or png in href of the link, which is the case when using zoomData and passing image paths directly without any encoding, 
			most lightboxes consider it as a direct link to the image file and do not trigger the request to the url via ajax. 
			<span style="text-decoration:underline">The here used and in the download package included lightboxes (Fancybox & Colorbox) have been slightly modified to load the url and not 
			"mistakenly" consider it to be an image...</span>
			</p>
			<?php

			$example = 3;
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