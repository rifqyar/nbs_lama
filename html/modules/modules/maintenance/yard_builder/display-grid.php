<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="./yard/src/css/excite-bike/jquery-ui-1.8.16.custom.css">
	<script src="./yard/src/js/jquery-1.7.min.js" type="text/javascript"></script>
	<script src="./yard/src/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
	<script src="./colpickjs/jquery.colorpicker.js"></script>
	<link href="./colpickjs/jquery.colorpicker.css" rel="stylesheet" type="text/css"/>
	<script src="./colpickjs/i18n/jquery.ui.colorpicker-nl.js"></script>
        
	<link rel="stylesheet" href="./yard/src/css/main.css">
	
<?php 
        //create layout sesuai dengan parameter post dari slot dan row
	if(isset($_POST["slot"])&&isset($_POST["row"]))
	{
		$width 	= $_POST["slot"];
		$height = $_POST["row"];
	}
	else
	{
		$width 	= 0;
		$height = 0;	
	}
	$L	= $width * $height;
	
	if($width < 40) $m_div = 15;
	else $m_div = 20;
	
	//$s	= round((900 / $width) - (($m_div/100)*(900/$width)));
	$s  = 20;
?>
	<style>
	#feedback { font-size: 1.4em; }
	#selectable .ui-selecting { background: #FECA40; }
	#selectable .ui-selected { background: #F39814; color: white; }
	#selectable { list-style-type: none; margin: 0; padding: 0; }
	#selectable li {float: left; width: <?php echo $s."px"?>; height: <?php echo $s."px"?>; font-size: 4em; text-align: center; }
	
	
	</style>
	<script>
	var cell        = new Array();
	var block_name 	= new Array();
	var block_color = new Array();
	
	var count_block = 0;
	var slot  = <?php echo $width;?>;
	var row	  = <?php echo $height;?>;

	var total = row*slot; 
	
	$(function() {
		for (var i = 0; i < total; i++)
		{
			cell[i] = 0;
			cell[i] = new Object();
		}
		
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

<fieldset  style="margin: 30px 5px 5px 5px;"  >
<span id="select-result" style="display: none;"></span>
<span id="result"></span>
<center>

<div class="grid" style="margin-top:0px; border:1px solid black;overflow-y:scroll;overflow-x:scroll;">
	<table border="0" width="500%" height="800%">
		<tr align="center" valign="top">
			<td align="center" valign="middle" style="padding-left: 10px; padding-right: 2px;">
				<ol id="selectable">
					<?php 
						$j = 1;
						for($i = 1; $i <= $L; $i++)
						{	
							$m = ($width*$j) + 1;
					?>		
							<li class="ui-state-default" <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><?php //echo $i?></li>
					<?php 
						}
					
					?>
				</ol>
			</td>
		</tr>
	</table>
</div>

</center>

<div style="clear:both; padding-bottom:300px;"></div>


<div style="padding-left: 10px; float:left">
	<form id="build_yard" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_builder/display-grid" method="post">
            <fieldset><legend><font color="red"><b>Step 1 - Create Layout</b></font></legend>
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
            </fieldset>
	</form>
</div>

<div style="padding-left: 30px; float:left">
    <fieldset><legend><font color="red"><b>Step 2 - Block Allocation</b></font></legend>
	<form id="build_stack" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_builder/display-grid" method="post">
            
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
	<form id="de_stack" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_builder/display-grid" method="post">
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
</fieldset>
</div>

<!--<div style="padding-left: 30px; float:left">
	<form id="reset" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_builder/display-grid" method="post">
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
</div> -->
<div style="padding-left: 30px; float:left">
    <fieldset><legend><font color="red"><b>Step 3 - Set Block</b></font></legend>
	<form id="blocking" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_builder/display-grid" method="post">
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
					Jumlah Tier
				</td>
				<td>
					:
				</td>
				<td>
					<input type="text" size="5" name="block_tier" id="block_tier">
				</td>
			</tr>
                        <tr>
				<td>
					Kategori Blok (Bongkar/Muat)
				</td>
				<td>
					:
				</td>
				<td>
					<input type="text" size="15" name="block_kategori" id="block_kategori">
				</td>
			</tr>
                        <tr>
				<td>
					Posisi Blok (vertical/horizontal)
				</td>
				<td>
					:
				</td>
				<td>
					<input type="text" size="15" name="block_posisi" id="block_posisi">
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
					<input type="text" size="8" name="block_color" id="block_color" class="cp-alt" />
					<span class="cp-alt-target" style="display: inline-block; border: thin solid black; padding: 0.8em 2em;">
					</span>
					<script>
					$( function() 
					{
						$('.cp-alt').colorpicker(
						{
							altField: '.cp-alt-target',
							altProperties: 'background-color,color',
							altAlpha: true,
							alpha: true
						});
					});
					</script>

				</td>
			</tr>
			<tr>
				<td colspan="3" style="padding-left: 40px;" align="right">
					<input type="submit" value=" Set " id="set_block"> | <input type="submit" value=" UnSet " id="unblock">
				</td>
			</tr>

		</table>
	</form>
    </fieldset>
</div>
<div style="clear:both"></div>
<div style="padding-left: 0px; float:left">
	<form id="build_file" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_builder/load_grid" method="post">
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

<div style="padding-left: 10px; float:left" >
    <fieldset><legend><font color="red"><b>Step 4 - Save Yard</b></font></legend>
	<form id="db_use" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_builder/use_grid" method="post">
		<table style="font-size: 12px; font-weight: bold;">

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
    </fieldset>
    <br /><br />
    <br /><br />
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
//
//	$("#reset").click(function(event) {
//		  event.preventDefault();
//		  //alert($("#result").html());
//		  	for (var i = 0; i < total; i++)
//			{
//				cell[i].stack = 0;
//			  	cell[i].block = "";
//			}
//			$("#selectable li").attr( "style", "  border: 1px solid #ffffff; " );
//			$("#selectable li").attr( "title", "" );
//			$("#selectable li").attr( "class", "ui-state-default");
//
//		});
//	
	$("#set_block").click(function(event) {
		event.preventDefault();
		//alert($("#result").html());
		var selected = $("#select-result").html();
		var array_s  = selected.split(",");
		//var color 	 = $("#block_color").val();
		var name 	 = $("#block_name").val();
        var tier 	 = $("#block_tier").val();
        var kategori = $("#block_kategori").val();
		var posisi 	 = $("#block_posisi").val();
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
			//block_color[count_block] = color;
            block_tier[count_block]	 = tier;
            block_kategori[count_block] = kategori;
			block_posisi[count_block] = posisi;
			count_block++;
		}
		
		for (var i = 0; i < (array_s.length-1); i++)
		{
			cell[array_s[i]].block = name;
			//console.log("--"+cell[array_s[i]].block+"--");
			//console.log("--"+cell[array_s[i]].stack+"--");
			//$("#selectable li").eq(array_s[i]).attr( "style", "  border: 1px solid #"+color+"; " );
			$("#selectable li").eq(array_s[i]).attr( "title", "  Blok "+name );
                        $("#selectable li").eq(array_s[i]).attr( "tier", "  Tier "+tier );
                         $("#selectable li").eq(array_s[i]).attr( "kategori", "  Kategori "+kategori );
                        $("#selectable li").eq(array_s[i]).attr( "posisi", "  Posisi "+posisi );
		}
	});

	$("#unblock").click(function(event) {
		event.preventDefault();
		//alert($("#result").html());
		var selected = $("#select-result").html();
		var array_s  = selected.split(",");
	//	var color 	 = $("#block_color").val();
		var name 	 = $("#block_name").val();
                var tier 	 = $("#block_tier").val();
                var kategori	 = $("#block_kategori").val();
		var posisi 	 = $("#block_posisi").val();
		//console.log("++"+selected+"++");
		for (var i = 0; i < (array_s.length-1); i++)
		{
			cell[array_s[i]].block = "";
			$("#selectable li").eq(array_s[i]).attr( "style", "  border: 1px solid #ffffff; " );
			$("#selectable li").eq(array_s[i]).attr( "title", "" );
                        $("#selectable li").eq(array_s[i]).attr( "tier", "" );
                        $("#selectable li").eq(array_s[i]).attr( "kategori", "" );
                        $("#selectable li").eq(array_s[i]).attr( "posisi", "" );
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
			block_str += "<block><name>"+block_name[j]+"</name><tier>"+block_tier[j]+"</tier><posisi>"+block_posisi[j]+"</posisi><kategori>"+block_kategori[j]+"</kategori><cell>"+index_block[j].join(",")+"</cell></block>";
		}

		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><yard>"+width_str+height_str+stack_str+block_str+"</yard>";
		console.log(xml_str);
		 var url = "<?=HOME?>maintenance.yard_builder.ajax/yard_save";
		$.post( url, { xml_: xml_str},
			      function(data) {
					  console.log(data);
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
			block_str += "<block><name>"+block_name[j]+"</name><tier>"+block_tier[j]+"</tier><posisi>"+block_posisi[j]+"</posisi><kategori>"+block_kategori[j]+"</kategori><cell>"+index_block[j].join(",")+"</cell></block>";
		}

		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><yard>"+width_str+height_str+stack_str+block_str+"</yard>";
		console.log(xml_str);
		 var url = "<?=HOME?>maintenance.yard_builder.ajax/yard_dblink";
		 var yard_name_ = $("#yard_name").val();
		$.post( url, { xml_: xml_str, yard_name : yard_name_},
			      function(data) {
					alert(data);
		      		console.log(data);
			      }
			    );
	});
	
</script>

</fieldset>
</body>
</html>
