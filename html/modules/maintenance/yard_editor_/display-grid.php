<?php

$db	= getDB();

if(!isset($_POST['yard_id']))
{
	?>
		<center>
        
        <fieldset style="margin: 30px 10px 10px 10px; height:100px; vertical-align:middle">
            <form enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_editor/display-grid" method="post">
            	<br/><br/><br/>
                <select name="yard_id">
                <?php 
                    $query_get_yard = "SELECT * FROM YD_YARD_AREA";
                    $result_yard	= $db->query($query_get_yard);
                    $row_yard		= $result_yard->getAll();
                    foreach($row_yard as $row)
                    {
                ?>
                  <option value="<?php echo $row['ID']?>"><?php echo $row['NAMA_YARD'] ?></option>
                <?php 
                    }
                ?>
                </select>
                <input type="submit" value=" Go "> </input>
            </form>
        </fieldset>
        
		</center>
	<?php
}
else
{
$yard_id = $_POST['yard_id'];

$query_yard_area = "SELECT * FROM YD_YARD_AREA WHERE ID LIKE '$yard_id'";
$result 		 = $db->query($query_yard_area);
$yard_area		 = $result->fetchRow();

$yard_name	= $yard_area['NAMA_YARD'];
$width  	= $yard_area['WIDTH'];
$height 	= $yard_area['LENGTH'];
//echo "-----$yard_id---------";

?>

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
	
       <?
        
        $L	= $width * $height;
	
	if($width < 40) $m_div = 15;
	else $m_div = 20;
	
	$s	= round((900 / $width) - (($m_div/100)*(900/$width)));
?>
	<style>
	#feedback { font-size: 1.4em; }
	#selectable .ui-selecting { background: #FECA40; }
	#selectable .ui-selected { background: #F39814; color: white; }
	#selectable { list-style-type: none; margin: 0; padding: 0; }
	#selectable li {float: left; width: <?php echo $s."px"?>; height: <?php echo $s."px"?>; font-size: 4em; text-align: center;}
	
	
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
<script type="text/javascript">
        $(document).ready(function() 
		{
			$( "#block_name" ).autocomplete({
				minLength: 1,
				source: "maintenance.yard_editor.auto/block_name",
				focus: function( event, ui ) {
					$( "#block_name" ).val( ui.item.KODE);
					return false;
				},
				select: function( event, ui ) {
					$( "#block_name" ).val( ui.item.NAME);
					$( "#block_tier" ).val( ui.item.TIER);
					$( "#block_posisi" ).val( ui.item.POSISI);
                                        $( "#block_color" ).val( ui.item.COLOR);
					return false;
				}
			})
			.data( "autocomplete" )._renderItem = function( ul, item ) {
				return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + item.NAME + "</a>")
					.appendTo( ul );
			};
	});
</script>
	

</head>
<body>

<fieldset  style="margin: 30px 5px 5px 5px; ">
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
						$query_yard_cell = "SELECT * FROM YD_YARD_CELL WHERE ID_YARD_AREA = '$yard_id' ORDER BY INDEX_CELL ASC";
						$result      = $db->query($query_yard_cell);
						$cell_idx    = 0;
						$i = 1; 
						$row_cell_   = $result->getAll();
						foreach($row_cell_  as $row_cell)
						{	
							$m = ($width*$j) + 1;
							
							if($row_cell['STATUS_STACK'] == 1)
							{

                                                          //      echo "SELECT a.NAME, b.SLOT_, b.ROW_ FROM YD_BLOCKING_AREA a, YD_BLOCKING_CELL b WHERE a.ID = b.ID_BLOCKING_AREA AND b.INDEX_CELL = '$cell_idx' AND a.ID_YARD_AREA = '$yard_id'";die;
                                                                $query_get_r_s	     = "SELECT a.NAME, b.SLOT_, b.ROW_ FROM YD_BLOCKING_AREA a, YD_BLOCKING_CELL b WHERE a.ID = b.ID_BLOCKING_AREA AND b.INDEX_CELL = '$cell_idx' AND a.ID_YARD_AREA = '$yard_id'";
								$res_r_s	     = $db->query($query_get_r_s);
								$row_count           = $res_r_s->fetchRow();
                                                                $name     	     = $row_count['NAME'];
                                                                $slot    	     = $row_count['SLOT_'];
                                                                $row       	     = $row_count['ROW_'];
								
								
					?>			
								<script> cell[<?php echo $cell_idx?>].stack = 1;</script>
								<li class="ui-stacking-default"  <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><font size="1px"><?php echo $name.'-'.$slot.'-'.$row;?></font></li>							
					<?php 
								$p++;
							}
							else
							{
					?>		
							<li class="ui-state-default" <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><?php //echo $i?></li>
					<?php 
							}
							$cell_idx++;
							$i++;
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
	<form id="build_yard" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_editor/display-grid" method="post">
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
	<form id="build_stack" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_editor/display-grid" method="post">
            
                <table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td>
					Alokasikan Sebagai Area Penumpukan  
				</td>
			</tr>
			<tr>
				<td style="padding-left: 40px;" align="right">
                                        <input type="hidden" id="yard_id" value="<?=$yard_id?>">
					<input type="submit" id="stack" value="Alokasikan" >
				</td>
			</tr>
		</table>
	</form>
	<form id="de_stack" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_editor/display-grid" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td>
					Dealokasikfan Sebagai Area Penumpukan  
				</td>
			</tr>
			<tr>
				<td style="padding-left: 40px;" align="right">
                                        <input type="hidden" id="yard_id" value="<?=$yard_id?>">
					<input type="submit" id="destack" value="Dealokasikan" >
				</td>
			</tr>
		</table>
	</form>
</fieldset>
</div>

<!--<div style="padding-left: 30px; float:left">
	<form id="reset" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_editor/display-grid" method="post">
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
	<form id="blocking" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_editor/display-grid" method="post">
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
<!--<div style="clear:both"></div>
<div style="padding-left: 0px; float:left">
	<form id="build_file" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_editor/load_grid" method="post">
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
</div>-->
<!--
<div style="padding-left: 10px; float:left" >
    <fieldset><legend><font color="red"><b>Step 4 - Save Yard</b></font></legend>
	<form id="db_use" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_editor/use_grid" method="post">
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
</div>-->

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
                  var yard_id_  = $("#yard_id").val();
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
                  var yard_id_  = $("#yard_id").val();
                  var url      = "{$HOME}maintenance.yard_editor.ajax/cek_placement";
                  var selector    = '.dialog';
                  
		  console.log("++"+selected+"++");
		  for (var i = 0; i < (array_s.length-1); i++)
		  {
                        $.post(url,{id_cell : array_s[$i], yard_id: yard_id_},function(data){
                        console.log(data);
                        if(data == "EXIST")
                        {
                                alert("Masih terdapat container placement di lokasi tersebut, Tolong lakukan relokasi terlebih dahulu.");	
                        } else {
                                //muncul dialog konfirmasi apakah anda yakin?
                                console.log("--"+array_s[i]+"--");
                                cell[array_s[i]].stack = 0;
                                $("#selectable li").eq(array_s[i]).attr( "class", "ui-state-default");
                        }
                        });
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
		var color 	 = $("#block_color").val();
		var name 	 = $("#block_name").val();
                var tier 	 = $("#block_tier").val();
                var kategori 	 = $("#block_kategori").val();
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
			block_color[count_block] = color;
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
			$("#selectable li").eq(array_s[i]).attr( "style", "  border: 1px solid #"+color+"; " );
			$("#selectable li").eq(array_s[i]).attr( "title", "  Blok "+name );
		}
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
			block_str += "<block><name>"+block_name[j]+"</name><color>"+block_color[j]+"</color><tier>"+block_tier[j]+"</tier><posisi>"+block_posisi[j]+"</posisi><kategori>"+block_kategori[j]+"</kategori><cell>"+index_block[j].join(",")+"</cell></block>";
		}

		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><yard>"+block_str+"</yard>";
		console.log(xml_str);
		 var url = "<?=HOME?>maintenance.yard_editor.ajax/yard_dblink";
		$.post( url, { xml_: xml_str},
			      function(data) {
					alert(data);
		      		console.log(data);
			      }
			    );
	});

	$("#unblock").click(function(event) {
		event.preventDefault();
		//alert($("#result").html());
		var selected = $("#select-result").html();
		var array_s  = selected.split(",");
		var color 	 = $("#block_color").val();
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
			block_str += "<block><name>"+block_name[j]+"</name><color>"+block_color[j]+"</color><tier>"+block_tier[j]+"</tier><posisi>"+block_posisi[j]+"</posisi><kategori>"+block_kategori[j]+"</kategori><cell>"+index_block[j].join(",")+"</cell></block>";
		}

		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><yard>"+width_str+height_str+stack_str+block_str+"</yard>";
		console.log(xml_str);
		 var url = "<?=HOME?>maintenance.yard_editor.ajax/yard_save";
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
			block_str += "<block><name>"+block_name[j]+"</name><color>"+block_color[j]+"</color><tier>"+block_tier[j]+"</tier><posisi>"+block_posisi[j]+"</posisi><kategori>"+block_kategori[j]+"</kategori><cell>"+index_block[j].join(",")+"</cell></block>";
		}

		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><yard>"+width_str+height_str+stack_str+block_str+"</yard>";
		console.log(xml_str);
		 var url = "<?=HOME?>maintenance.yard_editor.ajax/yard_dblink";
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

<?php

}

?>
