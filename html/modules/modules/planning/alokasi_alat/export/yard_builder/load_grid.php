<?php
$uploaddir = './';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {

} else {
    echo "Possible file upload attack!\n";
    die();
}

$data = simplexml_load_file($uploadfile);

$width  	= $data->width;
$height 	= $data->height;
$stack_cell = $data->index; 
$index 		= explode(",", $stack_cell);

$block 			= $data->block;
$block_sum	 	= count($block);


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>jQuery UI Selectable - Display as grid</title>
	<link rel="stylesheet" href="../config/src/css/excite-bike/jquery-ui-1.8.16.custom.css">
	<script src="../config/src/js/jquery-1.7.min.js" type="text/javascript"></script>
	<script src="../config/src/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
	<link rel="stylesheet" href="../config/src/css/main.css">
	
<?php 
	$L	= $width * $height;
	
	if($width < 40) $m_div = 15;
	else $m_div = 20;
	
	$s	= round((600 / $width) - (($m_div/100)*(600/$width)));
?>
	<style>
	#feedback { font-size: 1.4em; }
	#selectable .ui-selecting { background: #FECA40; }
	#selectable .ui-selected { background: #F39814; color: white; }
	#selectable { list-style-type: none; margin: 0; padding: 0; }
	#selectable li {float: left; width: <?php echo $s."px"?>; height: <?php echo $s."px"?>; font-size: 4em; text-align: center; }
	
	
	</style>
	<script>
	var cell  		= new Array();
	var block_name 	= new Array();
	var block_color = new Array();
	
	var count_block = <?php echo $block_sum?>;
	var slot  = <?php echo $width;?>;
	var row	  = <?php echo $height;?>;

	var total = row*slot; 
	var m	  = 0;
	var v	  = 0;

	for (var i = 0; i < total; i++)
	{
		cell[i] = 0;
		cell[i] = new Object();
	}
	$(function() {
		

		
		$( "#selectable" ).selectable({
			selected: function() {
				var result = $( "#select-result" ).empty();
				$( ".ui-selected", this ).each(function() {
					var id = $("#selectable li").index(this);
					result.append( id+"," );
					//$("#selectable li").eq(id).attr( "class", "ui-stacking-default");
				});
			}
		});

	});
	</script>
</head>
<body>
<span id="select-result" style="display: none;"></span>
<span id="result"></span>
<center>

<div class="grid" style="float: left">
	<table border="0" width="100%">
		<tr align="center" valign="top">
			<td align="center" valign="middle" style="padding-left: 10px; padding-right: 2px;">
				<ol id="selectable">
					<?php 
						$j = 1;
						$p = 0;
						for($i = 1; $i <= $L; $i++)
						{	
							$m = ($width*$j) + 1;
							$cell_idx = $i - 1;
							
							if($cell_idx == $index[$p])
							{
					?>			
								<script> cell[<?php echo $cell_idx?>].stack = 1;</script>
								<li class="ui-stacking-default" <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>></li>							
					<?php 
								$p++;
							}
							else
							{
					?>		
							<li class="ui-state-default" <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><?php //echo $i?></li>
					<?php 
							}
						}
					
					?>
				</ol>
			</td>
		</tr>
	</table>
</div>

</center>

<?php 
	foreach ($block as $block_)
	{
		$c_b = 0;
		$cell_block	= explode(",",$block_->cell)
?>	
		<script>
			block_name[v] 	= "<?php echo $block_->name;?>";
			block_color[v]	= "<?php echo $block_->color;?>";
			<?php 
				for ($h = 0; $h < count($cell_block); $h++)
				{
					?>
						cell[<?php echo $cell_block[$h]?>].block = block_name[v];
						$("#selectable li").eq(<?php echo $cell_block[$h]?>).attr( "style", "  border: 1px solid "+block_color[v]+"; " );
						$("#selectable li").eq(<?php echo $cell_block[$h]?>).attr( "title", "  Blok "+block_name[v] );
					<?php 	
				}
			?>
			v++;
		</script>	
<?php 
	}

?>

<div>
	<form id="build_yard" enctype="multipart/form-data" action="./display-grid.php" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td>
					Set Jumlah Slot  
				</td>
				<td>
					:
				</td>
				<td>
					<input type="text" size="10" name="slot">
				</td>
			</tr>
			<tr>
				<td>
					Set Jumlah Row  
				</td>
				<td>
					:
				</td>
				<td>
					<input type="text" size="10" name="row">
				</td>
			</tr>
			<tr>
				<td style="padding-left: 40px;" align="right">
					<input type="submit" value="build">
				</td>
			</tr>
		</table>
	</form>
</div>

<div style="padding-top: 40px;">
	<form id="build_stack" enctype="multipart/form-data" action="./display-grid.php" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td>
					Alokasikan Sebagai Area Penumpukan  
				</td>
			</tr>
			<tr>
				<td style="padding-left: 40px;" align="right">
					<input type="submit" id="stack" value="Alokasikan" >
				</td>
			</tr>
		</table>
	</form>
</div>

<div style="padding-top: 40px;">
	<form id="de_stack" enctype="multipart/form-data" action="./display-grid.php" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td>
					Dealokasikan Sebagai Area Penumpukan  
				</td>
			</tr>
			<tr>
				<td style="padding-left: 40px;" align="right">
					<input type="submit" id="destack" value="Dealokasikan" >
				</td>
			</tr>
		</table>
	</form>
</div>

<div style="padding-top: 10px;">
	<form id="reset" enctype="multipart/form-data" action="./display-grid.php" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td>
					RESET  
				</td>
			</tr>
			<tr>
				<td style="padding-left: 40px;" align="right">
					<input type="submit" id="reset" value="Reset" >
				</td>
			</tr>
			<tr>
				<td>
					<hr/>
				</td>
			</tr>
		</table>
	</form>
</div>

<div style="padding-top: 10px;">
	<form id="blocking" enctype="multipart/form-data" action="./display-grid.php" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td>
					Nama Blok 
				</td>
				<td>
					:
				</td>
				<td>
					<input type="text" size="10" name="block_name" id="block_name">
				</td>
			</tr>
			<tr>
				<td>
					Warna 
				</td>
				<td>
					:
				</td>
				<td>
					<input type="text" size="10" name="block_color" id="block_color">
				</td>
			</tr>
			<tr>
				<td style="padding-right: 40px;" align="right">
					<input type="submit" value=" Set " id="set_block">
				</td>
				<td>
					|
				</td>
				<td style="padding-left: 40px;" align="right">
					<input type="submit" value=" UnSet " id="unblock">
				</td>
			</tr>
			<tr>
				<td colspan = "3">
					<hr/>
				</td>
			</tr>
		</table>
	</form>
</div>

<div style="padding-top: 10px;">
	<form id="build_file" enctype="multipart/form-data" action="./load_grid.php" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td>
					XML File
				</td>
				<td>
					<input type="submit" value=" Generate " id="save">
				</td>
				<td id="file_at">
				</td>
			</tr>
			<tr>
				<td colspan = "3">
					<hr/>
				</td>
			</tr>
			<tr>
				<td>
					Load
				</td>
				<td style="padding-right: 10px;" colspan="2">
					<input type="file" id="save" name="userfile">
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<input type="submit" value=" Load "></input>
				</td>
			</tr>
		</table>
	</form>
</div>

<div style="padding-top: 10px;" align="center">
	<form id="db_use" enctype="multipart/form-data" action="./use_grid.php" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td colspan = "3">
					<hr/>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					Nama Lapangan 
				</td>
				<td>
					<input type="text" id="yard_name" value=""></input>
				</td>
			</tr> 
			<tr>
				<td colspan="2">
					Gunakan Lapangan Ini
				</td>
				<td id="yard_link">
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<input type="submit" value=" Use " id="db_link"></input>
				</td>
			</tr>
		</table>
	</form>
</div>
<script>

	function matrix_translate(index) {
		var r;
		var s;
		
		s = index % slot;
		var temp = index - s;
		r = (temp / slot)+1;

		var matrix = ""+s+"-"+r;
		return matrix;
	}

	$("#stack").click(function(event) {
		  event.preventDefault();
		  //alert($("#result").html());
		  var selected = $("#select-result").html();
		  var array_s  = selected.split(",");
		  console.log("++"+selected+"++");
		  for (var i = 0; i < (array_s.length-1); i++)
		  {
			//console.log("--"+cell[i]+"--");
			cell[array_s[i]].stack = 1;
			$("#selectable li").eq(array_s[i]).attr( "class", "ui-stacking-default");
		  }
		});
	
	$("#destack").click(function(event) {
		  event.preventDefault();
		  //alert($("#result").html());
		  var selected = $("#select-result").html();
		  var array_s  = selected.split(",");

		  console.log("++"+selected+"++");
		  for (var i = 0; i < (array_s.length-1); i++)
		  {
			console.log("--"+array_s[i]+"--");
			cell[array_s[i]].stack = 0;
			$("#selectable li").eq(array_s[i]).attr( "class", "ui-state-default");
		  }
		});

	$("#reset").click(function(event) {
		  event.preventDefault();
		  //alert($("#result").html());
		  	for (var i = 0; i < total; i++)
			{
				cell[i].stack = 0;
			  	cell[i].block = "";
			}
			$("#selectable li").attr( "style", "  border: 1px solid #ffffff; " );
			$("#selectable li").attr( "title", "" );
			$("#selectable li").attr( "class", "ui-state-default");

		});

	$("#set_block").click(function(event) {
		event.preventDefault();
		//alert($("#result").html());
		var selected = $("#select-result").html();
		var array_s  = selected.split(",");
		var color 	 = $("#block_color").val();
		var name 	 = $("#block_name").val();
		//console.log("++"+selected+"++");
		var p = 0;
		for (var i = 0; i < count_block; i++)
		{
			if(block_name[i] == name)
				p = 1;
		}

		if(p == 0)
		{
			block_name[count_block]	 = name;
			block_color[count_block] = color;
			count_block++;
		}
		
		for (var i = 0; i < (array_s.length-1); i++)
		{
			cell[array_s[i]].block = name;
			//console.log("--"+cell[array_s[i]].block+"--");
			//console.log("--"+cell[array_s[i]].stack+"--");
			$("#selectable li").eq(array_s[i]).attr( "style", "  border: 1px solid "+color+"; " );
			$("#selectable li").eq(array_s[i]).attr( "title", "  Blok "+name );
		}
	});

	$("#unblock").click(function(event) {
		event.preventDefault();
		//alert($("#result").html());
		var selected = $("#select-result").html();
		var array_s  = selected.split(",");
		var color 	 = $("#block_color").val();
		var name 	 = $("#block_name").val();
		//console.log("++"+selected+"++");
		for (var i = 0; i < (array_s.length-1); i++)
		{
			cell[array_s[i]].block = "";
			$("#selectable li").eq(array_s[i]).attr( "style", "  border: 1px solid #ffffff; " );
			$("#selectable li").eq(array_s[i]).attr( "title", "" );
		}
	});

	$("#save").click(function(event) {
		event.preventDefault();
		//build width and height
		var width_str 	= "<width>"+slot+"</width>";
		var height_str	= "<height>"+row+"</height>";

		//build array of stacking area
		var j = 0;
		var index_stack = new Array();
		for (var i = 0; i < total; i++)
		{
			if(cell[i].stack == 1)
			{
				index_stack[j] = i;
				j++;
			}
		}
		var stack_ 		= index_stack.join(",");
		var stack_str	= "<index>"+stack_+"</index>"; 
		console.log("=="+stack_str+"==");
	
		//build array of blocking area
		var index_block = new Array();
		var p = 0;
		for (var j = 0; j < count_block; j++)
		{
			index_block[j] = new Array();
			for (var i = 0; i < total; i++)
			{
				if(cell[i].block == block_name[j])
				{
					index_block[j][p] = i;
					p++;
				}
			}
			p = 0;
		}
		
		var block_str = "";
		for (var j = 0; j < count_block; j++)
		{
			block_str += "<block><name>"+block_name[j]+"</name><color>"+block_color[j]+"</color><cell>"+index_block[j].join(",")+"</cell></block>";
		}

		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><yard>"+width_str+height_str+stack_str+block_str+"</yard>";
		console.log(xml_str);
		 var url = "./yard_save.php";
		$.post( url, { xml_: xml_str},
			      function(data) {
		      		if(data == "success")
		      		{
		      			$("#file_at").html("<a href='./saved_file.xml'>Save As File ini</a>");
		      		}
			      }
			    );
	});

	$("#db_link").click(function(event) {
		event.preventDefault();
		//build width and height
		var width_str 	= "<width>"+slot+"</width>";
		var height_str	= "<height>"+row+"</height>";

		//build array of stacking area
		var j = 0;
		var index_stack = new Array();
		for (var i = 0; i < total; i++)
		{
			if(cell[i].stack == 1)
			{
				index_stack[j] = i;
				j++;
			}
		}
		var stack_ 		= index_stack.join(",");
		var stack_str	= "<index>"+stack_+"</index>"; 
		console.log("=="+stack_str+"==");
	
		//build array of blocking area
		var index_block = new Array();
		var p = 0;
		for (var j = 0; j < count_block; j++)
		{
			index_block[j] = new Array();
			for (var i = 0; i < total; i++)
			{
				if(cell[i].block == block_name[j])
				{
					index_block[j][p] = i;
					p++;
				}
			}
			p = 0;
		}
		
		var block_str = "";
		for (var j = 0; j < count_block; j++)
		{
			block_str += "<block><name>"+block_name[j]+"</name><color>"+block_color[j]+"</color><cell>"+index_block[j].join(",")+"</cell></block>";
		}

		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><yard>"+width_str+height_str+stack_str+block_str+"</yard>";
		console.log(xml_str);
		var yard_name_ = $("#yard_name").val();
		 var url = "./yard_dblink.php";
		$.post( url, { xml_: xml_str, yard_name : yard_name_},
			      function(data) {
			      		alert(data);
			      		console.log(data);
			      }
			    );
	});
	
</script>
</body>
</html>
