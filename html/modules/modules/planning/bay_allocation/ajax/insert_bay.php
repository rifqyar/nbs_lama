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
<link rel="stylesheet" href="./yard/src/css/main_2.css">
<script type="text/javascript" src="<?=HOME;?>js/stickytooltip.js"></script>
<link rel="stylesheet" type="text/css" href="<?=HOME;?>css/stickytooltip.css" />	
	
<?php 
	
	//print_r($_GET['bay']);die;
		$id_vs = $_GET['id'];
		$id_bay = $_GET['bay'];
		$posisi = $_GET['posisi'];
		//print_r($id_vs);die;
		$db = getDB();
		$ves_voy = "SELECT JENIS_KAPAL FROM RBM_H WHERE TRIM(NO_UKK) = TRIM('$id_vs')";
		$vvoy = $db->query($ves_voy);
		$hasil_vv = $vvoy->fetchRow();
		$js_v=$hasil_vv['JENIS_KAPAL'];
		
		$query_bay = "SELECT ID,JML_ROW,JML_TIER_UNDER,JML_TIER_ON FROM STW_BAY_AREA WHERE BAY = '$id_bay' AND ID_VS = '$id_vs'";
        $result6 = $db->query($query_bay);
        $bay = $result6->fetchRow();
		//print_r($bay['JML_ROW']);die;
		
		$width 	= $bay['JML_ROW'];
		$height_under = $bay['JML_TIER_UNDER'];
		$height_on = $bay['JML_TIER_ON'];
		$bay_area = $bay['ID'];
		
		$width_h = $width+1;
		$height_h = $height_under+$height_on+3;
	
	$L_h = $width_h * $height_h;
	$L_d = ($width_h-1) * $height_h;
	
	if(($width_h < 40)&&($height_h < 40)) $m_div = 15;
	else $m_div = 20;
	
	$s	= round((800 / (($width_h+$height_h)-3)) - (($m_div/100)*(800/(($width_h+$height_h)-3))));
?>
<style>
	#feedback { font-size: 1.4em; }
	#selectable .ui-selecting { background: #FECA40; }
	#selectable .ui-selected { background: #F39814; color: white; }
	#selectable { list-style-type: none; margin: 0; padding: 0; }
	#selectable li {float: left; width: <?php echo $s."px"?>; height: <?php echo $s."px"?>; font-size: 1em; text-align: center; padding-top: 10px;}	
</style>	
	
	<script>
	var cell  		= new Array();
	var block_name 	= new Array();
	var block_color = new Array();
	var block_rfr   = new Array();
	
	var count_block = 0;
	var alokasi = 1;
	var vessel = "<? echo $id_vs;?>";
	var slot  = <?php echo $width_h;?>;
	var row	  = <?php echo $height_h;?>;
	var posisi = "<?php echo $posisi;?>";
	var palka = <? echo ($height_on+1)*$width_h;?>;
	
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
		  $('#block_name').val(alokasi);
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

	$("#set_block").click(function(event) {
		event.preventDefault();
		//alert($("#result").html());
		var selected = $("#select-result").html();
		var array_s  = selected.split(",");
		var color 	 = $("#block_color").val();
		var name 	 = $("#block_name").val();
		var rfr      = $("#plug_rfr").val();
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
			block_rfr[count_block] = rfr;
			count_block++;
			alokasi++;
			//alert(count_block);
		}
		
		for (var i = 0; i < (array_s.length-1); i++)
		{
			cell[array_s[i]].block = name;
			//console.log("--"+cell[array_s[i]].block+"--");
			//console.log("--"+cell[array_s[i]].stack+"--");
			if(((array_s[i])%slot)==0)
			{
				$("#selectable li").eq(array_s[i]).attr( "style", " clear: both; border: 1px solid #"+color+"; " );
			}
			else
			{
				$("#selectable li").eq(array_s[i]).attr( "style", "  border: 1px solid #"+color+"; " );
			}
			$("#selectable li").eq(array_s[i]).attr( "title", "  Bay "+name );
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
		var posisi_str  = "<posisi>"+posisi+"</posisi>";
		var palka_str  = "<palka>"+palka+"</palka>";

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
			block_str += "<block><name>"+block_name[j]+"</name><color>"+block_color[j]+"</color><rfr>"+block_rfr[j]+"</rfr><cell>"+index_block[j].join(",")+"</cell></block>";
		}

		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><bay>"+width_str+height_str+posisi_str+palka_str+stack_str+block_str+"</bay>";
		console.log(xml_str);
		var url = "<?=HOME;?>planning.bay_allocation.ajax/yard_save";
		$.post( url, { xml_: xml_str},
			      function(data) {
					  console.log(data);
		      		if(data == "success")
		      		{
		      			$("#file_at").html("<a href='<?=HOME;?>planning.bay_allocation.ajax/saved_file.xml'>Save As File ini</a>");						
		      		}
			      }
			    );
	});

	$("#db_link").click(function(event) {
		event.preventDefault();
		var vessel_str = "<vessel>"+vessel+"</vessel>";
		var width_str 	= "<width>"+slot+"</width>";
		var height_str	= "<height>"+row+"</height>";
		var posisi_str  = "<posisi>"+posisi+"</posisi>";
		var palka_str  = "<palka>"+palka+"</palka>";
		
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
			block_str += "<block><name>"+block_name[j]+"</name><color>"+block_color[j]+"</color><rfr>"+block_rfr[j]+"</rfr><cell>"+index_block[j].join(",")+"</cell></block>";
		}

		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><bay>"+vessel_str+width_str+height_str+posisi_str+palka_str+stack_str+block_str+"</bay>";
		console.log(xml_str);
		 var url = "<?=HOME?>planning.bay_allocation.ajax/yard_dblink";
		 var bay_ = $("#id_bay").val();
		 var id_vs_ = "<?=$id_vs?>";
		 //alert(id_vs_);
		$.post( url, { xml_: xml_str, bay : bay_, id_vs : id_vs_},function(data2){
					//alert(data2);
		      		console.log(data2);
					window.location = "<?=HOME?>planning.bay_allocation/bay_alokasi?vs="+id_vs_+"&j_k=<?=$js_v;?>";			
			    });
	});
	
</script>

</head>
<body>

<fieldset  style="margin: 10px 5px 0px 0px; ">
<span id="select-result" style="display: none;"></span>
<span id="result"></span>
<center>

<div class="grid">	
	<table border="0" width="80%" align="center">
		<tr align="center" valign="top">
			<td align="center" valign="middle" style="padding-left: 10px; padding-right: 2px;">
				<ol id="selectable">
				<?php 
						$j = 1;
						$x = ($height_on+1)*$width_h;
						$y = (($height_on+2)*$width_h)+1;
						$z = (($height_h-1)*$width_h)+1;
						$ganjil=1;
						$tier_on = 78+($height_on*2);
						$tier_under = $height_under*2;
						//print_r($y);die;
						
						if($posisi=='above')
						{
							$query_cell2 = "SELECT CELL_NUMBER,ROW_,TIER_,STATUS_STACK FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_area' AND POSISI_STACK IN ('ABOVE','HATCH') ORDER BY CELL_NUMBER ASC";
							$result3 = $db->query($query_cell2);
							$blok2 = $result3->getAll();
						}
						else
						{
							$query_cell2 = "SELECT CELL_NUMBER,ROW_,TIER_,STATUS_STACK FROM STW_BAY_CELL WHERE ID_BAY_AREA = '$bay_area' AND POSISI_STACK IN ('BELOW','HATCH') ORDER BY CELL_NUMBER ASC";
							$result3 = $db->query($query_cell2);
							$blok2 = $result3->getAll();
						}					
						
						foreach ($blok2 as $row8){
							$i = $row8['CELL_NUMBER']+1;
							$row_bay = $row8['ROW_'];
							$tier_bay = $row8['TIER_'];
							
						if($posisi=='above')
						{		
							
							if($row8['STATUS_STACK']=='N')
							{							
							$m = ($width_h*$j)+1;							
						    if(($i<=(($width_h-2)/2))&&($i>=1)) /*row genap on deck*/
							{?>
						    <li style="text-align: center; border: 1px solid #ffffff;" <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><?php echo ($width_h-2)-(2*($i-1));?></li>						
						    <? }
							else if($i==($width_h/2)) /*row poros*/
							{ ?>
							<li style="text-align: center; border: 1px solid #ffffff;" <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><?php echo "0";?></li>
							<? }
							else if(($i<=($width_h-1))&&($i>(($width_h)/2))) /*row ganjil on deck*/
							{ ?>
							<li style="text-align: center; border: 1px solid #ffffff;" <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><?php echo $ganjil;?></li>
							<? $ganjil+=2;}
							else if($i==$width_h)
						   { ?>
							<li style="text-align: center; border: 1px solid #ffffff;" <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><?php //echo $i;?></li>
							<? }							
							else if(((($i+1)%$width_h)==1)&&($i<=$x)) /*labeling tier on deck*/
						   { ?>
						    <li style="text-align: center; border: 1px solid #ffffff;" <?php if (($i%$m) == 0){ $j++ ?>style="clear: both;"<?php }?>><?php echo $tier_on;?></li>
							<? $tier_on-=2;}
							else if(($i<$y)&&($i>$x)) /*posisi palka*/
							{?>										
							<li style="text-align: center; border: 1px solid #ffffff; <?php if (($i%$m) == 0){ $j++; ?>clear: both;<?php }?>"><b><?php echo "H";?></b></li>
							<? }							
							else {?>
							<li class="ui-state-default" <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><?php //echo $i;?></li>					
							<? }  
						} else if($row8['STATUS_STACK']=='A')
						  { ?>
							
							<li style="text-align: center; border: 1px solid #ffffff;" <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>>+</li>
							
						<?	
							}
						}
						else
						{
						
							if($row8['STATUS_STACK']=='N')
							{						    							
							if(($i<$y)&&($i>$x)) /*posisi palka*/
							{?>										
							<li style="text-align: center; border: 1px solid #ffffff; <?php if (($i%$width_h) == 1){ ?>clear: both;<?php }?>"><b><?php echo "H";?></b></li>
							<? }						    
							else if($i==$L_h)
						   { ?>
							<li style="text-align: center; border: 1px solid #ffffff;" <?php if (($i%$width_h) == 1){ $j++;	?>style="clear: both;"<?php }?>><?php //echo $i;?></li>
							<? }
							else if(($i>=$z)&&($i<$L_h)) /*labeling row under deck*/
						   { ?>
							<li style="text-align: center; border: 1px solid #ffffff; <?php if (($i%$width_h) == 1){ ?>clear: both;<?php }?>"><?php echo $row_bay;?></li>
							<? }
							else if(((($i+1)%$width_h)==1)&&($i>$x)) /*labeling tier under deck*/
							{ ?>
							<li style="text-align: center; border: 1px solid #ffffff;" <?php if (($i%$width_h) == 1){ $j++; ?>style="clear: both;"<?php }?>><?php echo $tier_under;?></li>
							<? $tier_under-=2;}	
							else {?>
							<li class="ui-state-default" <?php if (($i%$width_h) == 1){ ?>style="clear: both;"<?php }?>><?php //echo $i;?></li>					
							<? }  
						} else if($row8['STATUS_STACK']=='A')
						  { ?>
							
							<li style="text-align: center; border: 1px solid #ffffff;" <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>>+</li>
							
						<?	
							}
						
						}
							} ?>
				</ol>
			</td>
		</tr>		
	</table>
</div>

</center>

<div style="clear:both; padding-bottom:<?php if($posisi=='above') { echo ($height_on+30)."px"; } else { echo ($height_under+30)."px"; } ?>;"></div>

<div style="padding-left: 60px; float:left">
	<form id="build_stack" enctype="multipart/form-data" action="<?=HOME?>planning.bay_allocation.ajax/insert_bay" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td>
					Alokasikan Sebagai Area Stowage  
				</td>
			</tr>
			<tr>
				<td style="padding-left: 40px;" align="right">
					<input type="submit" id="stack" value="Alokasikan" >
				</td>
			</tr>
		</table>
	</form>
	<form id="de_stack" enctype="multipart/form-data" action="<?=HOME?>planning.bay_allocation.ajax/insert_bay" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td>
					Dealokasikan Sebagai Area Stowage  
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

<div style="padding-left: 60px; float:left">
	<form id="blocking" enctype="multipart/form-data" action="<?=HOME?>planning.bay_allocation.ajax/insert_bay" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td>
					Alokasi 
				</td>
				<td>
					:
				</td>
				<td>
					<input type="text" size="3" name="block_name" id="block_name" readonly=readonly>
				</td>
			</tr>
			<tr>
				<td>
					Plug IN 
				</td>
				<td>
					:
				</td>
				<td>
					<select name="plug_rfr" id="plug_rfr">
						<option value="">-pilih-</option>
						<option value="T">T</option>
						<option value="Y">Y</option>
					</select>
					<input type="hidden" name="id_bay" id="id_bay" value="<? echo $id_bay; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					Colour
				</td>
				<td>
					:
				</td>
				<td>
					<input type="text" size="10" name="block_color" id="block_color" class="cp-alt" />
					<span class="cp-alt-target" style="display: inline-block; border: thin solid black; padding: 0.5em 1em;">
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
				<td style="padding-right: 10px;" align="right">
					<input type="submit" value=" Set " id="set_block">
				</td>
				<td>
					|
				</td>
				<td style="padding-left: 10px;" align="left">
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

<div style="clear:both"></div>
<div style="padding-left: 40px; float:left" >
	<form id="db_use" enctype="multipart/form-data" action="<?=HOME?>planning.bay_allocation/bay_alokasi?vs=<?=$id_vs?>&j_k=<?=$js_v?>" method="get">
		<table style="font-size: 12px; font-weight: bold;">			
			<tr>
				<td colspan="2">
					<input type="hidden" id="j_vs" name="j_vs" value="<?=$js_v?>"/>
					<b>GUNAKAN AREA INI</b>&nbsp;&nbsp;&nbsp;<input type="submit" value=" USE " id="db_link"></input>
				</td>
				<td id="yard_link">
				</td>
			</tr>
			<tr>
				<td colspan = "3">&nbsp;</td>
			</tr>
		</table>
	</form>
</div>
</fieldset>
</body>
</html>