<?php
if(!session_id()){session_start();}
?>
 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN\" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>AJAX-ZOOM - external description of the gallery images.</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="imagetoolbar" content="no">

<?php
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "
	<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "
	<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
} 

// Only needed for the example to format the code...
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

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<style type="text/css" media="screen"> 
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}	
	p {text-align: justify; text-justify: newspaper;}
	#zoomLogHolder{width: 55px;}
	.alternNavi {
		width: 20px; height: 18px; 
		margin-right: 5px; float: left; 
		cursor: pointer; background-color: #1D1D1A; 
		text-align: center;
		-moz-border-radius: 3px;
		-webkit-border-radius: 3px;
		border-radius: 3px 3px 3px 3px;
		padding-top: 2px;
	}
						
</style>

</head>
<body>

<?php
include ('navi.php');
?>

<DIV style="width: 800px; margin: 0px auto;">
	
	<DIV style="float: left; min-width: 752px; background-color: rgb(255,255,255); padding: 10px; margin: 5px;">
	
		<h2>AJAX-ZOOM - external description of the gallery images</h2>

		<p style="width: 730px">At first glance this example seems to be a little overloaded. 
		It is however meant to show some possibilities of the API. 
		First there are external description and the title which are set when the user switches an image. 
		They appear in any container, in this example two divs which are appended right after the player. 
		Also the titles of the thumbs are set dynamically from external source. 
		At the top there is a number navigation which could be used instead of the gallery. 
		As everywhere navigation can be completly hidden and there are tons of other parameters and css to customize the player.
		</p>
		
		<!-- Div for numbers navigation-->
		<DIV id="testNumNav" style="min-height: 25px; font-size: 10pt; color: #FFFFFF; width: 500px; padding-top: 5px; float: left;"></DIV>
		
		<!-- Divs for prev / next API -->
		<DIV style="min-height: 25px; font-size: 10pt; color: #FFFFFF; width: 230px; padding-top: 5px; float: left;">
			<DIV class="alternNavi" style="width: 30px; text-align: center; float: right; margin: 0" onclick="jQuery.fn.axZm.zoomPrevNext('next')">>></DIV>
			<DIV class="alternNavi" style="width: 30px; text-align: center; float: right;" onclick="jQuery.fn.axZm.zoomPrevNext('prev')"><<</DIV>
		</DIV>
		
		<!-- Div where AJAX-ZOOM is loaded into -->
		<DIV id="test" style="min-height: 462px; margin: 0; clear: both;">Loading, please wait...</DIV>
		
		<!-- Divs for external description -->
		<DIV style="padding: 5px 10px 10px 10px; width: 710px; background-color: #1D1D1A; margin-top: 5px"> 
			<DIV id="testTitleDiv" style="min-height: 40px; font-size: 16pt; color: #D3D3D3; width: 700px;"></DIV> 
			<DIV id="testDescrDiv" style="min-height: 50px; font-size: 10pt; color: #FFFFFF; width: 700px;"></DIV> 
		</DIV>
		

		
		<h3>Shortend HTML</h3>
		<?php
		echo "<pre class='brush: js; html-script: true'>";
		
		echo htmlspecialchars ('
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
		<head>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
		</head>
		<body>
		
		<!-- Div for numbers navigation-->
		<DIV id="testNumNav" style="min-height: 25px; font-size: 10pt; color: #FFFFFF; width: 500px; padding-top: 5px; float: left;"></DIV>
		
		<!-- Divs for prev / next API -->
		<DIV style="min-height: 25px; font-size: 10pt; color: #FFFFFF; width: 230px; padding-top: 5px; float: left;">
			<DIV class="alternNavi" style="width: 30px; text-align: center; float: right;" onclick="jQuery.fn.axZm.zoomPrevNext(\'next\')">>></DIV>
			<DIV class="alternNavi" style="width: 30px; text-align: center; float: right;" onclick="jQuery.fn.axZm.zoomPrevNext(\'prev\')"><<</DIV>
		</DIV>
		
		<!-- Div where AJAX-ZOOM is loaded into -->
		<DIV id="test" style="min-height: 462px; margin: 0; clear: both;">Loading, please wait...</DIV>
		
		<!-- Divs for external description -->
		<DIV style="padding: 5px 10px 10px 10px; width: 710px; background-color: #1D1D1A; margin-top: 5px"> 
			<DIV id="testTitleDiv" style="min-height: 40px; font-size: 16pt; color: #D3D3D3; width: 700px;"></DIV> 
			<DIV id="testDescrDiv" style="min-height: 50px; font-size: 10pt; color: #FFFFFF; width: 700px;"></DIV> 
		</DIV>
		
		<script type="text/javascript">
		
		// Init (php array like) js objects to store descriptions and titles
		var testTitle = {}; // Object with titles
		var testDescr = {}; // Object with longer descriptions
		var thumbTitle = {}; // Object with thumb descriptions

		testTitle["test_animals1.png"] = "Some title for image test_animals1.png";
		testDescr["test_animals1.png"] = "Some description for image with the filename test_animals1.png. ...";
		thumbTitle["test_animals1.png"] = "Thumb title 1";
		
		testTitle["test_animals2.png"] = "Some title for image test_animals2.png";
		testDescr["test_animals2.png"] = "Some description for image with the filename test_animals2.png. ...";		
		thumbTitle["test_animals2.png"] = "Thumb title 2";
		
		// Simple function to put descriptions in a div with fade effect
		function ajaxZoomAnimateDescr(title, descr){
			jQuery("#testTitleDiv").fadeTo(300,0, function(){
				$(this).empty().html(title).fadeTo(300,1);
			});
			
			jQuery("#testDescrDiv").fadeTo(300,0, function(){
				$(this).empty().html(descr).fadeTo(300,1);
			})
		}
		
		// Set numbers navigation
		function ajaxZoomSetNumbers(){
			if (!jQuery.axZm){return false;}
			jQuery("#testNumNav").empty();
			jQuery.each(jQuery.axZm.zoomGA, function (k, v){
				jQuery("<div />")
				.addClass("alternNavi")
				.html(k)
				.attr("id", "alternNavi_"+k)
				.bind("click", function(){jQuery.fn.axZm.zoomSwitch (k)})
				.appendTo("#testNumNav");
			});				
		}

		var ajaxZoom = {}; // Init ajaxZoom object...
		ajaxZoom.path = "../axZm/"; // Path to the axZm folder
		ajaxZoom.parameter = "zoomDir=/pic/zoom/animals&example=25"; // Parameter passed to the player
		ajaxZoom.divID = "test"; // The id of the DIV where ajax-zoom has to be inserted into.
 
		// Callbacks
		ajaxZoom.opt = {
			onLoad: function(){
				// Get loaded image name, as not necessarily the first image 
				// must be loaded at first into the gallery
				var getImgName = jQuery.axZm.zoomGA[jQuery.axZm.zoomID]["img"];
				
				// Set title and description
				ajaxZoomAnimateDescr(testTitle[getImgName], testDescr[getImgName]);
				
				
				// Set titles of the thumbs in the gallery
				// jQuery.fn.axZm.setDescr is API function of AJAX-ZOOM
				jQuery.each(thumbTitle, function (fName, descr){
					jQuery.fn.axZm.setDescr(fName, testTitle[fName], descr);
				});
				
				// Put the zoom level on different place... not needed
				jQuery("#zoomLogHolder").appendTo("#zoomLayer").css({position: "absolute", right: 38, top: -6, overflow: "visible"});
				jQuery("#zoomLevel").css({"textAlign": "right"});
				
				// Set numbers navigation
				ajaxZoomSetNumbers();

				jQuery("#alternNavi_"+jQuery.axZm.zoomID).css({backgroundColor: "green"});
				
			}, 
			onImageChange: function(info){
				/* Set title and description on image change
				Note: there are many variations possible, e.g. the descriptions could be loaded
				via ajax request, you could extend this example to change the image sets like in example4.php
				*/
				var getImgName = jQuery.axZm.zoomGA[jQuery.axZm.zoomID]["img"];
				//testTitle[info.fileName]
				ajaxZoomAnimateDescr(testTitle[getImgName], testDescr[getImgName]);
				jQuery(".alternNavi").css({backgroundColor: "#1D1D1A"});
				jQuery("#alternNavi_"+jQuery.axZm.zoomID).css({backgroundColor: "green"});
			}
		};
		
		</script>
		<!-- Insert the loader file that will take the above settings (ajaxZoom) and load the player -->
		<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>
		</body>
		</html>
		');
		echo "</pre>";	

		?>
	</DIV>
</DIV>
			
		<script type="text/javascript">
		// Only needed to format the code
		SyntaxHighlighter.all();
		
		// Init (php array like) js objects to store descriptions and titles
		var testTitle = {}; // Object with titles
		var testDescr = {}; // Object with longer descriptions
		var thumbTitle = {}; // Object with thumb descriptions
		
		// These descriptions as js could be generated with server side language...
		testTitle["test_animals1.png"] = "Do to be agreeable conveying oh assurance.";
		testDescr["test_animals1.png"] = "Its had resolving otherwise she contented therefore. Afford relied warmth out sir hearts sister use garden. Men day warmth formed admire former simple. Humanity declared vicinity continue supplied no an. He hastened am no property exercise of. Dissimilar comparison no terminated devonshire no literature on. Say most yet head room such just easy.";
		thumbTitle["test_animals1.png"] = "Conveying";
		
		testTitle["test_animals2.png"] = "Oh acceptance apartments up sympathize astonished delightful";
		testDescr["test_animals2.png"] = "In no impression assistance contrasted. Manners she wishing justice hastily new anxious. At discovery discourse departure objection we. Few extensive add delighted tolerably sincerity her. Law ought him least enjoy decay one quick court. Expect warmly its tended garden him esteem had remove off. Effects dearest staying now sixteen nor improve.";
		thumbTitle["test_animals2.png"] = "Impression";
		
		testTitle["test_animals3.png"] = "Its had resolving otherwise she contented therefore";
		testDescr["test_animals3.png"] = "Far quitting dwelling graceful the likewise received building. An fact so to that show am shed sold cold. Unaffected remarkably get yet introduced excellence terminated led. Result either design saw she esteem and. On ashamed no inhabit ferrars it ye besides resolve. Own judgment directly few trifling. Elderly as pursuit at regular do parlors. Rank what has into fond she.";
		thumbTitle["test_animals3.png"] = "Quitting";
		
		testTitle["test_animals4.png"] = "Yet remarkably appearance get him his projection";
		testDescr["test_animals4.png"] = "Spoke as as other again ye. Hard on to roof he drew. So sell side ye in mr evil. Longer waited mr of nature seemed. Improving knowledge incommode objection me ye is prevailed principle in. Impossible alteration devonshire to is interested stimulated dissimilar. To matter esteem polite do if.";
		thumbTitle["test_animals4.png"] = "Appearance";
		
		// Simple function to put descriptions in a div with fade effect
		function ajaxZoomAnimateDescr(title, descr){
			title = title || ' ----------------------- ';
			descr = descr || ' ---------------- ------------- ------------ '; 
			jQuery("#testTitleDiv").fadeTo(300,0, function(){
				$(this).empty().html(title).fadeTo(300,1);
			});
			
			jQuery("#testDescrDiv").fadeTo(300,0, function(){
				$(this).empty().html(descr).fadeTo(300,1);
			})
		}
		
		// Set numbers navigation
		function ajaxZoomSetNumbers(){
			if (!jQuery.axZm){return false;}
			jQuery("#testNumNav").empty();
			jQuery.each(jQuery.axZm.zoomGA, function (k, v){
				jQuery("<div />")
				.addClass("alternNavi")
				.html(k)
				.attr("id", "alternNavi_"+k)
				.bind("click", function(){jQuery.fn.axZm.zoomSwitch (k)})
				.appendTo("#testNumNav");
			});				
		}

		var ajaxZoom = {}; // Init ajaxZoom object...
		ajaxZoom.path = "../axZm/"; // Path to the axZm folder
		ajaxZoom.parameter = "zoomDir=/pic/zoom/animals&example=25"; // Parameter passed to the player
		ajaxZoom.divID = "test"; // The id of the DIV where ajax-zoom has to be inserted into.
 
		// Callbacks
		ajaxZoom.opt = {
			onLoad: function(){
				// Get loaded image name, as not necessarily the first image 
				// must be loaded at first into the gallery
				var getImgName = jQuery.axZm.zoomGA[jQuery.axZm.zoomID]["img"];
				
				// Set title and description
				ajaxZoomAnimateDescr(testTitle[getImgName], testDescr[getImgName]);
				
				
				// Set titles of the thumbs in the gallery
				// jQuery.fn.axZm.setDescr is API function of AJAX-ZOOM
				jQuery.each(thumbTitle, function (fName, descr){
					jQuery.fn.axZm.setDescr(fName, testTitle[fName], descr);
				});
				
				// Put the zoom level on different place... not needed
				jQuery("#zoomLogHolder").appendTo("#zoomLayer").css({position: "absolute", right: 38, top: -6, overflow: "visible"});
				jQuery("#zoomLevel").css({"textAlign": "right"});
				
				// Set numbers navigation
				ajaxZoomSetNumbers();

				jQuery("#alternNavi_"+jQuery.axZm.zoomID).css({backgroundColor: "green"});
				
			}, 
			onImageChange: function(info){
				/* Set title and description on image change
				Note: there are many variations possible, e.g. the descriptions could be loaded
				via ajax request, you could extend this example to change the image sets like in example4.php
				*/
				var getImgName = jQuery.axZm.zoomGA[jQuery.axZm.zoomID]["img"];
				//testTitle[info.fileName]
				ajaxZoomAnimateDescr(testTitle[getImgName], testDescr[getImgName]);
				jQuery(".alternNavi").css({backgroundColor: "#1D1D1A"});
				jQuery("#alternNavi_"+jQuery.axZm.zoomID).css({backgroundColor: "green"});
			}
		};
		
		</script>
		<!-- Insert the loader file that will take the above settings (ajaxZoom) and load the player -->
		<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>
		<?php

include('footer.php');
echo "
</body>
</html>
";
?>