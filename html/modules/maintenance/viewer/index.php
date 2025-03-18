
<?php

$db	= getDB();

if(!isset($_POST['yard_id']))
{
	?>
		<center>
        
        <fieldset style="margin: 30px 10px 10px 10px; height:100px; vertical-align:middle">
            <form enctype="multipart/form-data" action="<?=HOME?>maintenance.viewer" method="post">
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
	<link rel="stylesheet" href="./yard/src/css/main.css">
	


<?php 
	$L	= $width * $height ;
	
	if($width < 40) $m_div = 15;
	else $m_div = 20;
	
	$s	= round((900 / $width) - (($m_div/100)*(900/$width)));
	//$s = 30;
	//$t = 15;
?>
	<style>
	#feedback { font-size: 1.4em; }
	#selectable .ui-selecting { background: #FECA40; }
	#selectable .ui-selected { background: #F39814; color: white; }
	#selectable { list-style-type: none; margin: 0; padding: 0; }
	#selectable li {float: left; width: <?php echo ($s)."px"?>; height: <?php echo $s."px"?>; font-size: 4em; text-align: center; }
	
	
	</style>

	<script type="text/javascript">
        $(document).ready(function() 
		{
			$( "#kategori" ).autocomplete({
				minLength: 3,
				source: "maintenance.viewer.auto/parameter",
				focus: function( event, ui ) {
					$( "#kategori" ).val( ui.item.KODE);
					return false;
				},
				select: function( event, ui ) {
					$( "#kategori" ).val( ui.item.NAMA);
					$( "#id_kategori" ).val( ui.item.ID);
					$( "#id_kategori2" ).val( ui.item.INFO);
					return false;
				}
			})
			.data( "autocomplete" )._renderItem = function( ul, item ) {
				return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + item.NAMA + "<br />" + item.INFO + "</a>")
					.appendTo( ul );
			};
	});
</script>

	
	<script>
		   
	var cell  		= new Array();
	var block_name 	= new Array();
	var block_color = new Array();
	var yard_id		= "<?php echo $yard_id?>";
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
					//get_cell_content(id);
					result.append( id+"," );
					get_cell_content(result.html());
					//$("#selectable li").eq(id).attr( "class", "ui-stacking-default");
				});
			}
		});
		
		$( "#dialog" ).dialog({
			autoOpen: false,
			show: "blind",
			width: 450 
		});

		$( "#opener" ).click(function() {
			//$( "#dialog" ).dialog( "open" );
			return false;
		});
	});
	</script>
</head>
<body>
<span id="select-result" style="display: none;"></span>
<span id="result"></span>
<center>


<div style="margin-top:30px;">
	 <form enctype="multipart/form-data" action="<?=HOME?>maintenance.viewer" method="post">
	<table border='0' width='100%'>
		<tr>
			<td class="grid-cell" valign="top" style="font-size:12px;color:#000000" width='200' align="right" >
			Preview lapangan berdasarkan
			</td><td  class="grid-cell" valign="top" style="font-size:12px;color:#000000" width='50' align="center" >: </td>
			<td>
				 <select name="based" id='based'>
				<option value="">-- Pilih -- </option>
                  <option value="kapal">Kapal</option>
				  <option value="consignee">Consignee</option>
				  <option value="size">Ukuran</option>
                </select>
			</td>
		</tr>
		<tr>
			<td class="grid-cell" valign="top" style="font-size:12px;color:#000000" width='200' align="right" >
			Masukkan parameter 
			</td><td  class="grid-cell" valign="top" style="font-size:12px;color:#000000" width='50' align="center" >: </td>
			<td>
			<input type='text' id='kategori' name='kategori' size='20'>
			<input type='hidden' id='id_kategori' name='id_kategori' size='20'>
			<input type='hidden' id='id_kategori2' name='id_kategori2' size='20'>			
			<input type='hidden' id='yard_id' name='yard_id' value='<?=$_POST['yard_id']?>' size='20'> &nbsp  <input type="submit" value=" View "> </input>

			</td>		</tr>
	</table>
	</form>
</div>
<div style="margin-top:20px;border:1px solid black;width:900px;height:350px;overflow-y:scroll;overflow-x:scroll;">
<p style="width:100%;">
<div class="grid" style="float: left">
	<table border="0" width="100%" align="center" >
	<tr align="center" valign="top"><td width='100'></td></tr>
		<tr align="center" valign="top">
			<td align="center" valign="top" style="padding-left: 0px; padding-right: 1px;">
				<ol id="selectable">
					<?php 
					    if(!isset($_POST['kategori'])){
						$j = 1;
						$p = 0;
						$query_yard_cell = "SELECT * FROM YD_YARD_CELL WHERE ID_YARD_AREA = '$yard_id' ORDER BY INDEX_CELL ASC";
						$result			 = $db->query($query_yard_cell);
						$cell_idx 		 = 0;
						$i = 1; 
						$row_cell_		 = $result->getAll();
						foreach($row_cell_  as $row_cell)
						{	
							$m = ($width*$j) + 1;
							
							if($row_cell['STATUS_STACK'] == 1)
							{
								$query_count_content = "SELECT YD_BLOCKING_AREA.ID AS ID_BLOCK FROM YD_BLOCKING_CELL INNER JOIN YD_BLOCKING_AREA ON YD_BLOCKING_CELL.ID_BLOCKING_AREA = YD_BLOCKING_AREA.ID JOIN YD_PLACEMENT_YARD ON YD_PLACEMENT_YARD.ID_BLOCKING_AREA = YD_BLOCKING_CELL.ID_BLOCKING_AREA AND YD_PLACEMENT_YARD.SLOT_YARD = YD_BLOCKING_CELL.SLOT_ AND YD_PLACEMENT_YARD.ROW_YARD = YD_BLOCKING_CELL.ROW_ WHERE YD_BLOCKING_AREA.ID_YARD_AREA = '$yard_id' AND YD_BLOCKING_CELL.INDEX_CELL = '$cell_idx'";
								$res_content 		 = $db->query($query_count_content);
								$row_block			 = $res_content->fetchRow();
								$id_block			 = $row_block['ID_BLOCK'];
								
								$query_get_r_s		 = "SELECT COUNT(*) AS JUM FROM YD_BLOCKING_CELL INNER JOIN YD_PLACEMENT_YARD ON YD_BLOCKING_CELL.ID_BLOCKING_AREA = YD_PLACEMENT_YARD.ID_BLOCKING_AREA AND YD_PLACEMENT_YARD.SLOT_YARD = YD_BLOCKING_CELL.SLOT_ AND YD_PLACEMENT_YARD.ROW_YARD = YD_BLOCKING_CELL.ROW_ WHERE YD_BLOCKING_CELL.ID_BLOCKING_AREA = '$id_block' AND YD_BLOCKING_CELL.INDEX_CELL = '$cell_idx'";
								$res_r_s			 = $db->query($query_get_r_s);
								$row_count		 	 = $res_r_s->fetchRow();
								
						
					?>			
								<script> cell[<?php echo $cell_idx?>].stack = 1;</script>
								<li class="ui-stacking-default" valign='top' <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><font size="2px"><?php echo $row_count['JUM']?><?php echo $cell_idx?></font></li>							
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
					
					} else {
						$j = 1;
						$p = 0;
						$query_yard_cell = "SELECT * FROM YD_YARD_CELL WHERE ID_YARD_AREA = '$yard_id' ORDER BY INDEX_CELL ASC";
						$result			 = $db->query($query_yard_cell);
						$cell_idx 		 = 0;
						$i = 1; 
						$row_cell_		 = $result->getAll();
						foreach($row_cell_  as $row_cell)
						{	
							$m = ($width*$j) + 1;
							
							if($row_cell['STATUS_STACK'] == 1)
							{
								$query_count_content = "SELECT YD_BLOCKING_AREA.ID AS ID_BLOCK FROM YD_BLOCKING_CELL INNER JOIN YD_BLOCKING_AREA ON YD_BLOCKING_CELL.ID_BLOCKING_AREA = YD_BLOCKING_AREA.ID JOIN YD_PLACEMENT_YARD ON YD_PLACEMENT_YARD.ID_BLOCKING_AREA = YD_BLOCKING_CELL.ID_BLOCKING_AREA AND YD_PLACEMENT_YARD.SLOT_YARD = YD_BLOCKING_CELL.SLOT_ AND YD_PLACEMENT_YARD.ROW_YARD = YD_BLOCKING_CELL.ROW_ WHERE YD_BLOCKING_AREA.ID_YARD_AREA = '$yard_id' AND YD_BLOCKING_CELL.INDEX_CELL = '$cell_idx'";
								$res_content 		 = $db->query($query_count_content);
								$row_block			 = $res_content->fetchRow();
								$id_block			 = $row_block['ID_BLOCK'];
								
								$query_get_r_s		 = "SELECT COUNT(*) AS JUM FROM YD_BLOCKING_CELL INNER JOIN YD_PLACEMENT_YARD ON YD_BLOCKING_CELL.ID_BLOCKING_AREA = YD_PLACEMENT_YARD.ID_BLOCKING_AREA AND YD_PLACEMENT_YARD.SLOT_YARD = YD_BLOCKING_CELL.SLOT_ AND YD_PLACEMENT_YARD.ROW_YARD = YD_BLOCKING_CELL.ROW_ WHERE YD_BLOCKING_CELL.ID_BLOCKING_AREA = '$id_block' AND YD_BLOCKING_CELL.INDEX_CELL = '$cell_idx'";
								$res_r_s			 = $db->query($query_get_r_s);
								$row_count		 	 = $res_r_s->fetchRow();
	
								if ($_POST['kategori'] == 'kapal'){
									$kate	= $_POST['kategori1'];
									$query_get_r_t		 = "SELECT 'Y' STATUS FROM dual WHERE '$kate' IN
																    (SELECT DISTINCT TB_REQ_RECEIVING_H.ID_VS
																        FROM YD_BLOCKING_AREA
																       INNER JOIN YD_PLACEMENT_YARD
																          ON YD_BLOCKING_AREA.ID = YD_PLACEMENT_YARD.ID_BLOCKING_AREA
																       INNER JOIN TB_REQ_RECEIVING_H
																          ON TB_REQ_RECEIVING_H.ID_REQ = YD_PLACEMENT_YARD.ID_REQ
																 WHERE YD_PLACEMENT_YARD.ID_BLOCKING_AREA = '$id_block'
																       AND YD_PLACEMENT_YARD.ID_CELL = '$cell_idx')";
									$res_r_t			 = $db->query($query_get_r_t);
									$row_count_		 	 = $res_r_t->fetchRow();
									
									if ($row_count_['STATUS'] == 'Y')	{
										?>			
										<script> cell[<?php echo $cell_idx?>].stack = 1;</script>
										<li class="ui-placement-default" valign='top' <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><font size="2px"><?php //echo $row_count['JUM']?></font></li>							
										<?php 
										} else {
											?>			
										<script> cell[<?php echo $cell_idx?>].stack = 1;</script>
										<li class="ui-stacking-default" valign='top' <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><font size="2px"><?php //echo $row_count['JUM']?></font></li>							
										<?php 
									
										}
								} else if ($_POST['kategori'] == 'consignee'){
										$kate	= $_POST['kategori2'];
										$query_get_r_t		 = "SELECT 'Y' STATUS FROM dual WHERE '$kate' IN
																    (SELECT DISTINCT TB_REQ_RECEIVING_H.KODE_PBM
																        FROM YD_BLOCKING_AREA
																       INNER JOIN YD_PLACEMENT_YARD
																          ON YD_BLOCKING_AREA.ID = YD_PLACEMENT_YARD.ID_BLOCKING_AREA
																       INNER JOIN TB_REQ_RECEIVING_H
																          ON TB_REQ_RECEIVING_H.ID_REQ = YD_PLACEMENT_YARD.ID_REQ
																 WHERE YD_PLACEMENT_YARD.ID_BLOCKING_AREA = '$id_block'
																       AND YD_PLACEMENT_YARD.ID_CELL = '$cell_idx')";
										$res_r_t			 = $db->query($query_get_r_t);
										$row_count_		 	 = $res_r_t->fetchRow();
										
									if ($row_count_['STATUS'] == 'Y')	{
										?>			
										<script> cell[<?php echo $cell_idx?>].stack = 1;</script>
										<li class="ui-placement-default" valign='top' <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><font size="2px"><?php echo $kate?></font></li>							
										<?php 
										} else {
											?>			
										<script> cell[<?php echo $cell_idx?>].stack = 1;</script>
										<li class="ui-stacking-default" valign='top' <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><font size="2px"><?php echo $kate?></font></li>							
										<?php 
									
										}
								} else if ($_POST['kategori'] == 'size'){
									$kate	= $_POST['kategori'];
									$query_get_r_t		 = "SELECT 'Y' STATUS FROM dual WHERE '$kate' IN  (SELECT YD_PLACEMENT_YARD.SIZE_
															  FROM  YD_PLACEMENT_YARD
															 WHERE YD_PLACEMENT_YARD.ID_BLOCKING_AREA = '$id_block'
															       AND YD_PLACEMENT_YARD.ID_CELL = '$cell_idx')";
									$res_r_t			 = $db->query($query_get_r_t);
									$row_count_		 	 = $res_r_t->fetchRow();
									
									if ($row_count_['STATUS'] == 'Y')	{
										?>			
										<script> cell[<?php echo $cell_idx?>].stack = 1;</script>
										<li class="ui-placement-default" valign='top' <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><font size="2px"><?php echo $kate?></font></li>							
										<?php 
										} else {
											?>			
										<script> cell[<?php echo $cell_idx?>].stack = 1;</script>
										<li class="ui-stacking-default" valign='top' <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><font size="2px"><?php echo $kate?></font></li>							
										<?php 
									
										}
								}
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
					
					
					}?>
				</ol>
			</td>
		</tr>
	</table>
</div>
</p>
</div>
<br>
<br />
</center>

<?php 

	$query_blocking_area = "SELECT * FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA = '$yard_id'";
	$result_block	 	 = $db->query($query_blocking_area);
	$block__			 = $result_block->getAll();
	
	foreach ($block__ as $block_)
	{
		$c_b = 0;
		$block_id 		= $block_['ID'];
		$block_name 	= $block_['NAME'];
		$block_color	= $block_['COLOR'];
		
		$query_blocking_cell = "SELECT * FROM YD_BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$block_id' ORDER BY INDEX_CELL ASC";
		$result_cell		 = $db->query($query_blocking_cell);
?>	
		<script>
			block_name[v] 	= "<?php echo $block_name;?>";
			block_color[v]	= "<?php echo $block_color;?>";
			<?php 
				$h = 0;
				$cell_block_	= $result_cell->getAll();
				foreach($cell_block_ as $cell_block)
				{
					?>
						cell[<?php echo $cell_block['INDEX_CELL']?>].block = block_name[v];
						$("#selectable li").eq(<?php echo $cell_block['INDEX_CELL']?>).attr( "style", "  border: 1px solid "+block_color[v]+"; " );
						$("#selectable li").eq(<?php echo $cell_block['INDEX_CELL']?>).attr( "title", "Block "+block_name[v]+",<?php echo $cell_block['SLOT_']."-".$cell_block['ROW_'];?>" );
					<?php 	
					$h++;
				}
			?>
			v++;
		</script>	
<?php 
	}

?>

<div id="dialog" title="Stacking" style="font-size: 15px; margin-top: 50px;">
</div>
</body>

<script>
	function get_cell_content(id)
	{
		var cell_index  = id;
		var yard		= "<?php echo $yard_id?>";
		var url 		= "<?=HOME?><?=APPID?>.ajax/get_content";
		
		console.log(id);
		
		$.post( url, {index : cell_index, yard_id : yard},
			      function(data) {
		      			$("#dialog").html(data);
						//$( "#dialog" ).dialog( "open" );
			      		console.log(data);
			      }
			    );
	}

	function fill_loc(cont_no)
	{
		var url 		= "<?=HOME?><?=APPID?>/find_container";
	
		$.post( url, {container_no : cont_no},
			      function(data) {
			      		var ret = data.split("-");
			      		
			      		$("#old_block").val(ret[0]);
			      		$("#old_slot").val(ret[1]);
			      		$("#old_row").val(ret[2]);
			      		$("#old_tier").val(ret[3]);
				  }
			    );
	}
	
</script>

</html>
<?php

}

?>