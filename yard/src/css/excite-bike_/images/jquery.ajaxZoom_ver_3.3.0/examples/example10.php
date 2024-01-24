<?php
if(!session_id()){session_start();}

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>AJAX-ZOOM - imagearea select, zoom to specified coordinates</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">
";
 
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
}

echo "
<link href=\"../axZm/plugins/demo/syntaxhighlighter/styles/shCore.css\" type=\"text/css\" rel=\"stylesheet\" />
<link href=\"../axZm/plugins/demo/syntaxhighlighter/styles/shThemeCustom.css\" type=\"text/css\" rel=\"stylesheet\" />

<script type=\"text/javascript\" src=\"../axZm/plugins/jquery-1.7.2.min.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/jquery.jqDock/jquery.jqDock.js\"></script>

<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/src/shCore.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushJScript.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushPhp.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushCss.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushXml.js\"></script>
";


?>

<style type="text/css" media="screen"> 
	body {height: 100%;}
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}
	p {text-align: justify; text-justify: newspaper;}
	input {font-size: 9pt;} 
	form {padding:0px; margin: 0px;}
	.thumbDemoLink{}
	#menu1 {position: static;}
	#menu1 img {padding: 3px 6px; background-color: black;}
	#menu1 div.jqDock {float: right;}
	#menu1 div.jqDock div{}
	#menu1 div.jqDockLabel{
		font-size: 14px; 
		padding: 5px; 
		border: 3px solid green;
		background-color:#000000;
		color:#FFFFFF;
		-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=75)";
		filter: alpha(opacity=0.75);
		opacity: 0.75;
	}

</style>
<script type="text/javascript">
jQuery(document).ready(function(){
	SyntaxHighlighter.all();
	// jqDock menu
	jQuery('#menu1').jqDock({
		align: 'left',
		size: 80,
		distance: 90,
		labels: true,
		duration: 500,
		coefficient: 1.5,
		labels : 'tr'
	});
});

function getZoomParam(form){
	var fields = ['x1', 'y1', 'x2', 'y2'];
	if (jQuery.axZm.lastZoom){
		jQuery.each(jQuery.axZm.lastZoom, function(k, v){
			jQuery('#'+form+' input[name=z_'+k+']').val(Math.round(v));
		}); 
	}else{
		jQuery.each(fields, function(k, v){
			jQuery('#'+form+' input[name=z_'+v+']').val('');
		}); 	
	}
	if (jQuery.axZm.lastSel){
		jQuery.each(jQuery.axZm.lastSel, function(k, v){
			jQuery('#'+form+' input[name=s_'+k+']').val(Math.round(v));
		}); 
	}else{
		jQuery.each(fields, function(k, v){
			jQuery('#'+form+' input[name=s_'+v+']').val('');
		}); 	
	}
}

function setZoomParam(form){
	var fields = ['x1', 'y1', 'x2', 'y2'];
	var param = {};
	var value;
	var fld;
	jQuery.each(fields, function(k, v){
		value = jQuery('#'+form+' input[name=z_'+v+']').val();
		param[v] = value;
		fld = '#'+form+' input[name=z_'+v+']';
		if (!value){
			jQuery(fld).val(0);
		}
	}); 

	jQuery.fn.axZm.zoomTo(param);
}
</script>
<?php
echo "
</head>
<body>
";
include ('navi.php');
echo "<DIV style='width: 800px; margin: 0px auto;'>\n";
	
	echo "<DIV style='float: left; min-width: 770px; background-color: rgb(255,255,255); padding: 10px; margin: 5px;'>\n";
	
		echo "<h2>AJAX-ZOOM - embed with custom loader, jQuery.axZm.zoomTo() demo</h2>\n";
		echo "<DIV style='clear: both;'>\n";
		
			echo "<DIV style='float: right; width: 524px;'>";
			
				echo "<DIV style='float: right; width: 524px; height: 430px; background-image: url(../axZm/icons/customframe1.jpg)'>";
					echo "<DIV id='test' style='width:422px; height: 330px; margin: 44px 0px 0px 51px'></DIV>";
					echo "<DIV style='text-align: right; margin-top: 6px'>
					<span style='padding: 0px 47px 0px 0px; color: white; font-size: 8pt;'>Photo by: <a style='font-size: 8pt; color: white' href='http://www.photodesignbycarstenklein.com' target='_blank'>Carsten Klein</a></span>
					</DIV>";					
				echo "</DIV>";

			echo "</DIV>";

			echo "<DIV>";
				echo "<DIV style='float: right; width: 96px'>";
					echo "<DIV id='menu1'>";
						echo "<a href='#' class='thumbDemoLink' onClick=\"jQuery.fn.axZm.zoomTo({x1: 2584, y1: 1650, x2: 3424, y2: 2210, motion: 'easeOutBack', motionZoomed: 'easeOutQuad', speed: 1500, speedZoomed: 1000}); return false;\"><img src='../pic/zoomcrop/demo_lamp.jpg' alt='Lamp' title='Lamp' border='0'></a>";
						echo "<a href='#' class='thumbDemoLink' onClick=\"jQuery.fn.axZm.zoomTo({x1: 245, y1: 1089, x2: 1450, y2: 1892, motion: 'easeOutBack', motionZoomed: 'easeOutQuad', speed: 1500, speedZoomed: 1000}); return false;\"><img src='../pic/zoomcrop/demo_painting.jpg' title='Lamp' border='0'></a>";
						echo "<a href='#' class='thumbDemoLink' onClick=\"jQuery.fn.axZm.zoomTo({x1: 3058, y1: 2529, x2: 3898, y2: 3089, motion: 'easeOutBack', motionZoomed: 'easeOutQuad', speed: 1500, speedZoomed: 1000}); return false;\"><img src='../pic/zoomcrop/demo_vase.jpg' title='Vase' border='0'></a>";
						echo "<a href='#' class='thumbDemoLink' onClick=\"jQuery.fn.axZm.zoomTo({x1: 2054, y1: 2626, x2: 2474, y2: 2906, motion: 'easeOutBack',  motionZoomed: 'easeOutQuad', speed: 1500, speedZoomed: 1000}); return false;\"><img src='../pic/zoomcrop/demo_blanket.jpg' title='Blanket' border='0'></a>";
						echo "<a href='#' class='thumbDemoLink' onClick=\"jQuery.fn.axZm.zoomTo({x1: 3304, y1: 2859, x2: 5518, y2: 4335, motion: 'easeOutBack', motionZoomed: 'easeOutQuad', speed: 1500, speedZoomed: 1000}); return false;\"><img src='../pic/zoomcrop/demo_armchair.jpg' title='Armchair' border='0'></a>";
						echo "<a href='#' class='thumbDemoLink' onClick=\"jQuery.fn.axZm.zoomTo({x1: 1866, y1: 1620, x2: 2706, y2: 2180, motion: 'easeOutBack', motionZoomed: 'easeOutQuad', speed: 1500, speedZoomed: 1000}); return false;\"><img src='../pic/zoomcrop/demo_lounger.jpg' title='Lounger' border='0'></a>";
						echo "<a href='#' class='thumbDemoLink' onClick=\"jQuery.fn.axZm.zoomTo({x1: 0, y1: 0, x2: 30000, y2: 20000, motion: 'easeOutBack', motionZoomed: 'easeOutQuad', speed: 300, speedZoomed: 300}); return false;\">Reset</a>";
					echo "</DIV>";
				echo "</DIV>";
			echo "</DIV>";
			
			//echo "<a href='#' onClick=\"jQuery.fn.axZm.remove()\">remove</a><br>";
			//echo "<a href='#' onClick=\"jQuery.fn.axZm.load({path: '../axZm/', parameter: 'zoomDir=high_res&example=13', divID: 'test'})\">load</a><br>";		
			
			echo "<DIV style='clear: both;'>";
				echo "<DIV id='infoDiv' style='float: right; width: 500px'>
					<FORM id='parametersF' onSubmit=\"return false\">
						<table cellspacing='2' cellpadding='2'>
							<tr>
								<td width='100'>Original image:</td>
								<td width='80'>x1: <input type='text' name='z_x1' style='width: 40px'></td>
								<td width='80'>y1: <input type='text' name='z_y1' style='width: 40px'></td>
								<td width='80'>x2: <input type='text' name='z_x2' style='width: 40px'></td>
								<td width='80'>y2: <input type='text' name='z_y2' style='width: 40px'></td>
							</tr>
							<tr>
								<td>Initial image:</td>
								<td width='80'>x1: <input type='text' name='s_x1' style='width: 40px'></td>
								<td width='80'>y1: <input type='text' name='s_y1' style='width: 40px'></td>
								<td width='80'>x2: <input type='text' name='s_x2' style='width: 40px'></td>
								<td width='80'>y2: <input type='text' name='s_y2' style='width: 40px'></td>
							</tr>
							<tr>
								<td colspan='5'>
								<input type='button' value='GET parameters' onClick=\"getZoomParam(this.form.id);\"> &nbsp;&nbsp;
								<input type='button' value='SET parameters' onClick=\"setZoomParam(this.form.id);\">
								</td>
							</tr>
						</table>
					</FORM>
				</DIV>";
				
			echo "</DIV>";
							
			echo "<DIV style='clear: both;'>\n";
				echo "<p style='font-size: 120%'>
				Zooming into a predefined image area is very simple. 
				All you need to know are the coordinates of the edges in the <u>original image</u>: x1, y1 (top left corner) and x2, y2 (bottom right corner).
				You will then need to pass this coordinates to the method jQuery.fn.axZm.zoomTo() and bind the method to any event: 
				</p>";
				
				echo "<pre class='brush: html'>";
				echo htmlspecialchars ("<a href='#' onClick=\"jQuery.fn.axZm.zoomTo({x1: 2584, y1: 1650, x2: 3424, y2: 2210, motion: 'easeOutBack', motionZoomed: 'easeOutSine', speed: 1500, speedZoomed: 750}); return false;\">Lamp</a>");
				echo "</pre>";		
				
				echo "
				<p style='font-size: 120%'>
				Alternatively of knowing the coordinated of the edges in the original image you can also pass the coordinates of the edges in the <u>initial image</u>. 
				Doing so you must additionally set the option parameter \"initial: true\". Since this coordinates depend on the 
				<a href='http://www.ajax-zoom.com/index.php?cid=docs#picDim'>image size of the initial image</a>, e.g. 
				'480x360', changing this size would produce unpredicted results. It is therefore recomended to use the first method with the coordinated in the 
				original sizes image.
				</p>";
		
				echo "<pre class='brush: html'>";
				echo htmlspecialchars ("<a href='#' onClick=\"jQuery.fn.axZm.zoomTo({x1: 167, y1: 107, x2: 221, y2: 143, initial: true}); return false;\">Lamp</a>");
				echo "</pre>";		
				
				echo "<p style='font-size: 120%'>
				
				</p>";
		
				echo "<h4>Embed AJAX-ZOOM with custom loader:</h4>";
				echo "<pre class='brush: js; html-script: true'>";
				echo htmlspecialchars ('
				<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
				<html xmlns=\"http://www.w3.org/1999/xhtml\">
				<meta http-equiv=\"X-UA-Compatible\" content=\"IE=EmulateIE7\">
				<head>
				<!-- jQuery is not required, as it will be initialized by the axZm.loader.js -->
				</head>
				<body>
				<div id="test">This text will be replaced after AJAX-ZOOM is loaded</div>
				<script type="text/javascript">
				var ajaxZoom = {}; // New object
				ajaxZoom.path = "../axZm/"; // Path to the axZm folder
				ajaxZoom.parameter = "zoomFile=bedroom_3d.jpg&zoomDir=furniture&example=13"; // Your custom parameter
				ajaxZoom.divID = "test"; // The id of the ellement where ajax-zoom has to be inserted into
				</script>
				<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>
				</body>
				</html>
				');
				echo "</pre>";		
		
				?>
				<script type="text/javascript">
				var ajaxZoom = {}; // New object
				ajaxZoom.path = '../axZm/'; // Path to the axZm folder
				ajaxZoom.parameter = 'zoomFile=objects_test.jpg&zoomDir=furniture&example=13'; // Custom parameter
				ajaxZoom.divID = 'test'; // The id of the Div where ajax-zoom has to be inserted into		
				</script>
				<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>

			<?php
			echo "</DIV>\n";
		
		echo "</DIV>\n";
		
	echo "</DIV>\n";
echo "</DIV>\n";
include('footer.php');
echo "
</body>
</html>
";
?>