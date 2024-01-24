<?php
if(!session_id()){session_start();}

if (!isset($_GET['zoomID'])){
	$_GET['zoomID'] = 1;
}
if (!isset($_GET['zoomDir'])){
	$_GET['zoomDir'] = 'animals';
}

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>AJAX-ZOOM - callback examples</title>
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

<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/src/shCore.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushJScript.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushPhp.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushCss.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/demo/syntaxhighlighter/scripts/shBrushXml.js\"></script>

<script type=\"text/javascript\" src=\"../axZm/plugins/jquery-1.7.2.min.js\"></script>
<script type=\"text/javascript\" src=\"../axZm/plugins/jquery.scrollTo.min.js\"></script>
";

?>

<style type="text/css" media="screen"> 
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}	
	p {text-align: justify; text-justify: newspaper;}
	.zoomHorGalleryDescr{
		display:none;
	}
	.consoleEntry {
		border-bottom: green 1px dotted;
		padding: 3px;
		margin-bottom: 2px;
	}
	#callBackConsole{
		height: 350px; 
		overflow-x: hidden;
		overflow-y: auto;
		font-size: 8pt;
		background-color: #101010;
		color: #3CC628;
		width: 350px;
		border-bottom: #000000 5px solid;
		border-left: #000000 5px solid;
		border-right: #000000 5px solid;
	}
	.h3{
		background-color: #000000;
		color: #FFFFFF;
		font-size: 12pt;
		padding-top: 5px;
		padding-bottom: 5px;
		width: 350px;
		border-top: #000000 5px solid;
		border-left: #000000 5px solid;
		border-right: #000000 5px solid;
	}
	form {margin: 0; padding: 0;}
</style>

<?php
echo "
</head>
<body>
";
include ('navi.php');
echo "<DIV style='width: 800px; margin: 0px auto;'>\n";
	
	echo "<DIV style='float: left; min-width: 800px; background-color: rgb(255,255,255); padding: 10px; margin: 5px;'>\n";
	
		echo "<h2>AJAX-ZOOM - callback examples (last updated 2012-02-17)</h2>\n";
		echo "<DIV style='clear: both;'>\n";
		echo "<DIV id='test' style='float: right; width:430px; height: 442px; margin: 0px 0px 10px 10px'>Loading, please wait...</DIV>";

		echo "<DIV id='callBackConsoleContainer'>";
			echo "<DIV id='callBackConsoleInner'>";
				echo "<DIV class='h3'>Callback Console <input type='button' value='Clear' style='font-size: 8pt; float: right;' onClick=\"$('#callBackConsole').empty();\"></DIV>";
				echo "<DIV id='callBackConsole'></DIV>";
			echo "</DIV>";
		echo "</DIV>";
		
		echo "
		<FORM id='someFormID' onSubmit=\"return false\">
		<table cellspacing='2' cellpadding='2'>
			<tr>
				<td width='100'>Callback onSelection:</td>
				<td width='80'>x1: <input type='text' id='z_x1' style='width: 40px'></td>
				<td width='80'>y1: <input type='text' id='z_y1' style='width: 40px'></td>
				<td width='80'>x2: <input type='text' id='z_x2' style='width: 40px'></td>
				<td width='80'>y2: <input type='text' id='z_y2' style='width: 40px'></td>
			</tr>
		</table>
		</FORM>
		";		

		
		echo "
		<p>AJAX-ZOOM has some callbacks that can be used to develop custom applications. 
		It is planed to extend the callbacks and API in general with high priority. 
		So if You miss something please contact us and we will implement whatever you need in the next release or as quick patch. 
		The <a href='http://www.ajax-zoom.com/index.php?cid=docs#heading_7'>API documentation</a> will be updated bit by bit.
		Thank You.
		</p>
		
		<p>After the initialization of AJAX-ZOOM the callback functions are stored in the object 
		jQuery.axZm.userFunc e.g. jQuery.axZm.userFunc.onZoomInClickStart so you can access them later by redifining them if needed:
		</p>
		";
		echo "<pre class='brush: js;'>";
		echo htmlspecialchars ('
			jQuery.axZm.userFunc.onZoomInClickStart = function(){
				// Do something
				alert(\'This is a test\');
				
				// Or disable the function
				jQuery.axZm.userFunc.onZoomInClickStart = null;
			};
		');
		echo "</pre>";

		
		?>
		<script type="text/javascript">
		SyntaxHighlighter.all();
		
		
		function strReplace(s, r, subj) {
			if (!subj){return;}
			return subj.split(s).join(r);
		}
		
		// Function append to console for demonstration of callbacks
		function appendToConsole(string){
			$('#callBackConsole').append('<DIV class="consoleEntry">'+strReplace('\n','<br>',string)+'</DIV>')
			.scrollTo(999999999999);
		}
		
		// Example of a callback function defined elsewhere
		var someCallBackFunction = function(info){
			var string = 'Callback Name: \'onZoomInClickStart\'\n';
			string += 'viewport - x: '+info.viewport.x+', y: '+info.viewport.y+'\n';
			string += 'click - x: '+info.click.x+', y: '+info.click.y+'\n';
			string += 'crop - x1: '+info.crop.x1+', y1: '+info.crop.y1+', x2: '+info.crop.x2+', y2: '+info.crop.y2+'\n';
			string += 'zoomRatio: '+info.zoomRatio+'\n';
			
			appendToConsole(string);
		};
		
		var ajaxZoom = {};
		ajaxZoom.path = '../axZm/'; // Path to the axZm folder
		ajaxZoom.parameter = 'zoomID=<?php echo $_GET['zoomID']?>&zoomDir=<?php echo $_GET['zoomDir']?>&example=11'; // Needed parameter
		ajaxZoom.divID = 'test'; // The id of the Div where ajax-zoom has to be inserted
		
		// Callbacks passed over the options. All other options are defined in zoomConfig.inc.php
		ajaxZoom.opt = {
			onBeforeStart: function(){
				appendToConsole('Callback \'onBeforeStart\' triggered');
			},
			
			onStart: function(){
				appendToConsole('Callback \'onStart\' triggered');
			},
			
			onLoad: function(){
				appendToConsole('Callback \'onLoad\' triggered');
			},
			
			onZoomInClickStart: someCallBackFunction,
			
			onZoomInClickEnd: function(info){
				appendToConsole('Callback \'onZoomInClickEnd\' triggered');
			},

			onZoomOutClickStart: function(info){
				var string = 'Callback Name: \'onZoomOutClickStart\'\n';
				string += 'viewport - x: '+info.viewport.x+', y: '+info.viewport.y+'\n';
				string += 'click - x: '+info.click.x+', y: '+info.click.y+'\n';
				string += 'crop - x1: '+info.crop.x1+', y1: '+info.crop.y1+', x2: '+info.crop.x2+', y2: '+info.crop.y2+'\n';
				string += 'zoomRatio: '+info.zoomRatio+'\n';
				
				appendToConsole(string);
			},
			
			onZoomOutClickEnd: function(info){
				appendToConsole('Callback \'onZoomOutClickEnd\' triggered');
			},
			
			onSelection: function(info){
				$('#z_x1').val(Math.round(info.selector.x1));
				$('#z_y1').val(Math.round(info.selector.y1));
				$('#z_x2').val(Math.round(info.selector.x2));
				$('#z_y2').val(Math.round(info.selector.y2));
			},
			
			onSelectionEnd: function(info){
				var string = 'Callback Name: \'onSelectionEnd\'\n';
				string += 'Selector - x1: '+info.selector.x1+', y1: '+info.selector.y1+', x2: '+info.selector.x2+', y2: '+info.selector.y2+' Width: '+info.selector.width+', Height: '+info.selector.height+'\n';
				string += 'viewport - x: '+info.viewport.x+', y: '+info.viewport.y+'\n';
				string += 'crop - x1: '+info.crop.x1+', y1: '+info.crop.y1+', x2: '+info.crop.x2+', y2: '+info.crop.y2+'\n';
				string += 'zoomRatio: '+info.zoomRatio+'\n';
				appendToConsole(string);
			},
			
			onCropEnd: function(info){
				var string = 'Callback Name: \'onCropEnd\'\n';
				string += 'crop - x1: '+info.crop.x1+', y1: '+info.crop.y1+', x2: '+info.crop.x2+', y2: '+info.crop.y2+'\n';
				// Only by stitching the images server side or cropping from original image.
				string += 'Zoomed image: '+jQuery.axZm.zoomedImage+'\n';
				appendToConsole(string); 
			},
			
			onImageChange: function(info){
				var string = 'Callback Name: \'onImageChange\'\n';
				string += 'zoomID: '+info.zoomID+'\n';
				string += 'smallImage: '+info.smallImage+'\n';
				string += 'fileName: '+info.fileName+'\n';
				if (info.path){
					string += 'path: '+info.path+'\n';
				}
				string += 'Width: '+jQuery.axZm.ow+'px\n';
				string += 'Height: '+jQuery.axZm.oh+'px\n';
				
				appendToConsole(string);
			},
			
			onRestoreEnd: function(){
				appendToConsole('Callback \'onRestoreEnd\' triggered');
			},
			
			onSliderZoom: function(info){
				appendToConsole('Callback \'onSliderZoom\' triggered');
			},
			
			onZoomInButtonStart: function(info){
				var string = 'Callback Name: \'onZoomInButtonStart\'\n';
				string += 'viewport - x: '+info.viewport.x+', y: '+info.viewport.y+'\n';
				string += 'click - x: '+info.click.x+', y: '+info.click.y+'\n';
				string += 'crop - x1: '+info.crop.x1+', y1: '+info.crop.y1+', x2: '+info.crop.x2+', y2: '+info.crop.y2+'\n';
				string += 'zoomRatio: '+info.zoomRatio+'\n';
				
				appendToConsole(string);
			},
			
			onZoomOutButtonStart: function(info){
				var string = 'Callback Name: \'onZoomOutButtonStart\'\n';
				string += 'viewport - x: '+info.viewport.x+', y: '+info.viewport.y+'\n';
				string += 'click - x: '+info.click.x+', y: '+info.click.y+'\n';
				string += 'crop - x1: '+info.crop.x1+', y1: '+info.crop.y1+', x2: '+info.crop.x2+', y2: '+info.crop.y2+'\n';
				string += 'zoomRatio: '+info.zoomRatio+'\n';
				
				appendToConsole(string);
			},
			
			onZoomInButtonEnd: function(info){
				appendToConsole('Callback \'onZoomInButtonEnd\' triggered');
			},
			
			onZoomOutButtonEnd: function(info){
				appendToConsole('Callback \'onZoomOutButtonEnd\' triggered');
			},
			
			onFullScreenStart: function(){
				appendToConsole('Callback \'onFullScreenStart\' triggered');
			},
			
			onFullScreenReady: function(){
				appendToConsole('Callback \'onFullScreenReady\' triggered');
				
				jQuery('#callBackConsole').css('height', 200);
				jQuery('#callBackConsoleInner').css({
					position: 'absolute',
					zIndex: 123,
					opacity: 0.8
				}).appendTo('#zoomLayer').css({
					top: (parseInt(jQuery('#zoomLayer').css('height')) - jQuery('#callBackConsoleInner').height()),
					left: (parseInt(jQuery('#zoomLayer').css('width')) - jQuery('#callBackConsoleInner').width())
				});
			},
			
			onFullScreenResizeEnd: function(){
				appendToConsole('Callback \'onFullScreenResizeEnd\' triggered');
				jQuery('#callBackConsoleInner').css({
					top: (parseInt(jQuery('#zoomLayer').css('height')) - jQuery('#callBackConsoleInner').height()),
					left: (parseInt(jQuery('#zoomLayer').css('width')) - jQuery('#callBackConsoleInner').width())
				});
			},
			
			onFullScreenClose: function(){
				appendToConsole('Callback \'onFullScreenClose\' triggered');
				
				jQuery('#callBackConsoleInner').appendTo('#callBackConsoleContainer').css({
					position: '',
					left: '',
					top: '',
					opacity: 1,
					zIndex: ''
				});
				jQuery('#callBackConsole').css('height', 300);
			},
			
			onButtonClickMove_N: function(){
				appendToConsole('Callback \'onButtonClickMove_N\' triggered');
			},

			onButtonClickMove_E: function(){
				appendToConsole('Callback \'onButtonClickMove_E\' triggered');
			},

			onButtonClickMove_S: function(){
				appendToConsole('Callback \'onButtonClickMove_S\' triggered');
			},	
			
			onButtonClickMove_W: function(){
				appendToConsole('Callback \'onButtonClickMove_W\' triggered');
			},
			
			onMouseWheelZoomIn: function(info){
				var string = 'Callback Name: \'onMouseWheelZoomIn\'\n';
				string += 'viewport - x: '+info.viewport.x+', y: '+info.viewport.y+'\n';
				string += 'click - x: '+info.click.x+', y: '+info.click.y+'\n';
				string += 'crop - x1: '+info.crop.x1+', y1: '+info.crop.y1+', x2: '+info.crop.x2+', y2: '+info.crop.y2+'\n';
				string += 'zoomRatio: '+info.zoomRatio+'\n';
				
				appendToConsole(string);
			},
			
			onMouseWheelZoomOut: function(info){
				var string = 'Callback Name: \'onMouseWheelZoomOut\'\n';
				string += 'viewport - x: '+info.viewport.x+', y: '+info.viewport.y+'\n';
				string += 'click - x: '+info.click.x+', y: '+info.click.y+'\n';
				string += 'crop - x1: '+info.crop.x1+', y1: '+info.crop.y1+', x2: '+info.crop.x2+', y2: '+info.crop.y2+'\n';
				string += 'zoomRatio: '+info.zoomRatio+'\n';
				
				appendToConsole(string);
			},
			
			onHorGalLoaded: function(){
				appendToConsole('Callback \'onHorGalLoaded\' triggered');
			}
			
		};
		</script>
		<script type="text/javascript" src="../axZm/jquery.axZm.loader.js"></script>

<?php		
		echo "</DIV>\n";
		
	echo "</DIV>\n";
echo "</DIV>\n";
include('footer.php');
echo "
</body>
</html>
";
?>