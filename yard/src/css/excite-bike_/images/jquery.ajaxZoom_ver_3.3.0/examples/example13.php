<?php
if(!session_id()){session_start();}

$mustang[1] = "
<p class='txt'>As Lee Iacocca's assistant general manager and chief engineer, Donald N. Frey was the head engineer for the Mustang project - supervising the overall development of the Mustang in a record 18 months - while Iacocca himself championed the project as Ford Division general manager. The Mustang prototype was a two-seat, mid-mounted engine roadster. This vehicle employed a Taunus (Ford Germany) V4 engine and was very similar in appearance to the much later Pontiac Fiero.
</p>
";
$mustang[] = "
<p class='txt'>It was claimed that the decision to abandon the two-seat design was in part due to the low sales experienced with the 2-seat 1955 Thunderbird. To broaden market appeal it was later remodeled as a four-seat car (with full space for the front bucket seats, as originally planned, and a rear bench seat with significantly less space than was common at the time). A \"Fastback 2+2\" model traded the conventional trunk space for increased interior volume as well as giving exterior lines similar to those of the second series of the Corvette Sting Ray and European sports cars such as the Jaguar E-Type.
</p>
";

$mustang[] = "
<p class='txt'>The new design was styled under the direction of Project Design Chief Joe Oros and his team of L. David Ash, Gale Halderman, and John Foster - in Ford's Lincoln - Mercury Division design studios, which produced the winning design in an intramural design contest instigated by Iacocca.
</p>
";

$mustang[] = "
<p class='txt'>Favourable publicity articles appeared in 2,600 newspapers the next morning, the day the car was \"officially\" revealed. A Mustang also appeared in the James Bond film Goldfinger in September 1964, the first time the car was used in a movie.
</p>
";
$mustang[] = "
<p class='txt'>To cut down the development cost and achieve a suggested retail price of US$2,368, the Mustang was based heavily on familiar yet simple components, many of which were already in production for other Ford models. Many (if not most) of the interior, chassis, suspension, and drive train components were derived from those used on Ford's Falcon and Fairlane. This use of common components also shortened the learning curve for assembly and repair workers, while at the same time allowing dealers to pick up the Mustang without also have to spend massive amounts of money on spare parts inventories to support the new car line.
</p>
";
$mustang[] = "
<p class='txt'>Original sales forecasts projected less than 100,000 units for the first year. This mark was surpassed in three months from rollout. Another 318,000 would be sold during the model year (a record), and in its first eighteen months, more than one million Mustangs were built.  All of these were VIN-identified as 1965 models, but several changes were made at the traditional opening of the new model year (beginning August 1964), including the addition of back-up lights on some models, the introduction of alternators to replace generators, and an upgrade of the V8 engine from 260 cu in (4.3 l) to 289 cu in (4.7 l) displacement. In the case of at least some six-cylinder Mustangs fitted with the 101 hp (75 kW) 170 cu in (2.8 l) Falcon engine, the rush into production included some unusual quirks, such as a horn ring bearing the 'Ford Falcon' logo beneath a trim ring emblazoned with 'Ford Mustang.' These characteristics made enough difference to warrant designation of the 121,538 earlier ones as \"1964½\" model-year Mustangs, a distinction that has endured with purists.
</p>




";

$mustang[] = "
<p class='txt'>All of the features added to the \"1965\" model were available as options or developmental modification to the \"1964½\" model, which in some cases led to \"mix-and-match\" confusion as surprised Ford exec hurriedly ramped up production by taking over lines originally intended for other car models' 1965 years. Some cars with 289 engines which were not given the chrome fender badges denoting the larger engine, and more than one car left the plant with cut-outs for back-up lights but no lights nor the later wiring harness needed to operate them. While these would today be additional-value collectors' items, most of these oddities were corrected at the dealer level, sometimes only after buyers had noticed them.
</p>
";

echo "
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
<html xmlns=\"http://www.w3.org/1999/xhtml\">
<head>
<title>AJAX-ZOOM iframe wordpress ebay image zoom</title>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">
<meta http-equiv=\"imagetoolbar\" content=\"no\">
";
 
if (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")){
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=0.5, maximum-scale=0.5, user-scalable=no\">";
}else{
	echo "<meta name=\"viewport\" content=\"width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no\">";
}

echo "<script type=\"text/javascript\" src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js\"></script>";

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
<style type="text/css" media="screen"> 
	body {height: 100%; margin: 0; padding: 0;}
	html {font-family: Tahoma, Arial; font-size: 10pt;}
	h2 {padding:0px; margin: 0px 0px 15px 0px; font-size: 16pt;}	
	p {text-align: justify; text-justify: newspaper;}
	.descr {background-color: #000000; color: #CCCCCC; height: 20px; padding: 3px 5px 0px 5px; text-align: right;}
	.txt {color: #555555;}
</style>

<?php
echo "
</head>
<body>
";
include ('navi.php');
echo "<DIV style='width: 800px; margin: 0px auto;'>\n";
	
	echo "<DIV style='float: left; min-width: 770px; padding: 10px; margin: 5px;'>\n";
	
		echo "<h2>AJAX-ZOOM - load zoom viewer in an iframe.</h2>\n";
		echo "<DIV style='clear: both;'>\n";

		echo "<p>
		Probably the simplest way to implement AJAX-ZOOM on any content management system is to use an iframe. 
		Currently this is also the only way to use AJAX-ZOOM cross-domain. 
		The need to get images from a different domain or sub domain can arise from using an imaging server located somewhere else 
		or showing AJAX-ZOOM on third party platforms like Ebay. 
		</p>
		";
		
		echo "<DIV style='float: right; overflow: hidden; width:482px; height: 390px; margin: 0px 0px 10px 10px'>
		<DIV class='descr'>Red convertible Mustang</DIV>
		<iframe src='example1.php?zoomDir=trasportation&zoomFile=mustang_1.jpg&example=16&iframe=1' width='482' height='370' scrolling='no' frameBorder='0' style='margin:0'></iframe>
		</DIV>";
		

		echo "<p>Source: wikipedia.org</p>";
		echo $mustang[1];
		echo $mustang[2];
		echo $mustang[3];
		echo $mustang[4];
		echo $mustang[5];
		
		/*
		echo "<DIV style='float: left; overflow: hidden; width:482px; height: 390px; margin: 0px 10px 10px 0px'>
		<DIV class='descr'>Silver Mustang first generation</DIV>
		<iframe src='example1.php?zoomDir=trasportation&&zoomFile=mustang_2.jpg&example=16&iframe=1' width='482' height='370' scrolling='no' frameBorder='0' style='margin:0'></iframe>
		</DIV>";
		*/
		?>
		
		<div id='myImage123'></div>
		<script type="text/javascript">
		var zoomUrl = {
			placeholderID: 'myImage123',
			path: 'http://www.ajax-zoom.com/examples/example1.php',  // change to your website
			parameter: 'zoomDir=trasportation&zoomFile=mustang_2.jpg&example=16&iframe=1',
			width: 482,
			height: 370,
			containerCss: {margin: '0px 10px 10px 0px', float: 'left'},
			descrHeight: 20,
			descrCssClass: 'descr',
			descrText: 'This image is shown from www.ajax-zoom.com'
		}
		</script>
		<script src="http://www.ajax-zoom.com/axZm/jquery.axZm.image.js" type="text/javascript"></script>
		
		<?php

		echo $mustang[6];
 		echo $mustang[7];

		$example = 13;
		include('syntax.php');		
		
		?>
		<script type="text/javascript">
		SyntaxHighlighter.all();
		</script>
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