<html lang="en">
<head>
    <meta charset="utf-8">
	<link rel="stylesheet" href="yard/src/css/excite-bike/jquery-ui-1.8.16.custom.css">
	<script src="yard/src/js/jquery-1.9.1.min.js" type="text/javascript"></script>
	<script src="yard/src/js/jquery-migrate-1.1.0.min.js" type="text/javascript"></script>
	<script src="yard/src/js/jquery.maphilight.js" type="text/javascript"></script>
	<script src="yard/src/js/jquery.imagemapster.js" type="text/javascript"></script>
	<script src="yard/src/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="yard/src/css/main.css">
        
        
    <script type="text/javascript" src="tooltip/stickytooltip.js"></script>
    <link rel="stylesheet" type="text/css" href="tooltip/stickytooltip.css" />
	
	<style>
	
	</style>

</head>
<body>
<div id="container">
 
 <div id="image">
<img style="width:400px;height:400px;border:0;" id="shape1" src="images/shape1.gif" alt="shape1" usemap="#shape1">

 </div>
</div>
<script>
$(document).ready(function ()
{
	$('#shape1').mapster({
	//singleSelect : false,
	//render_highlight : { altImage : 'images/shape2.gif' },
    //mapKey: 'color',
	fill : true, altImage : 'images/shape2.gif',
	selected : true
	//fillOpacity : 1,
     //areas : [{key : 'green', selected : true, altImage : 'images/shape2.gif'}]
});
});
</script>

<map name="shape1">
<area href="#" color="blue" shape="poly" coords="210,40,196,28,171,23,145,24,112,29,106,43,105,54,98,56,58,52,44,54,33,83,29,107,45,144,46,148,165,192,175,205,197,140,206,84,207,36" alt="">
<area href="#" color="purple" shape="poly" coords="30,162,50,163,153,201,173,219,138,352,129,373,102,377,37,375,28,357,21,307,33,290,56,286,63,284,61,277,30,254,22,231,24,185,31,161" alt="">
<area href="#" color="red" shape="poly" coords="216,97,203,161,188,204,185,233,165,292,159,327,144,372,156,379,191,386,228,385,241,376,232,322,229,278,227,223,223,171,223,132,217,96" alt="">
<area href="#" color="orange" shape="poly" coords="238,213,260,208,282,195,296,188,323,185,360,194,368,203,376,227,376,272,375,286,370,289,358,289,357,294,365,305,368,329,365,357,361,370,351,379,331,383,310,382,251,364,249,344,241,307,239,210" alt="">
<area href="#" color="green" shape="poly" coords="288,176,256,195,239,201,236,135,221,53,221,46,242,32,292,32,297,44,300,59,302,64,342,64,361,73,370,90,370,124,370,124,363,146,351,163,329,172,289,176" alt="">
</map>
</body>
</html>