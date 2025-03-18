<?php

$db	= getDB("storage");

if(!isset($_POST['yard_id']))
{
	?>
		<center>
        
        <fieldset style="margin: 30px 10px 10px 10px; height:100px; vertical-align:middle">
            <form enctype="multipart/form-data" action="<?=HOME?><?=APPID?>/view_yard" method="post">
            	<br/><br/><br/>
                <select name="yard_id">
                <?php 
                    $query_get_yard = "SELECT * FROM YARD_AREA";
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

$query_yard_area = "SELECT * FROM YARD_AREA WHERE ID LIKE '$yard_id'";
$result 		 = $db->query($query_yard_area);
$yard_area		 = $result->fetchRow();

$yard_name	= $yard_area['NAMA_YARD'];
$width  	= $yard_area['WIDTH'];
$height 	= $yard_area['HEIGHT'];
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
			$( "#dialog" ).dialog( "open" );
			return false;
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
						$query_yard_cell = "SELECT * FROM YARD_CELL WHERE ID_YARD_AREA = '$yard_id' ORDER BY INDEX_CELL ASC";
						$result			 = $db->query($query_yard_cell);
						$cell_idx 		 = 0;
						$i = 1; 
						$row_cell_		 = $result->getAll();
						foreach($row_cell_  as $row_cell)
						{	
							$m = ($width*$j) + 1;
							
							if($row_cell['STACK'] == 1)
							{
								$query_count_content = "SELECT BLOCKING_AREA.ID AS ID_BLOCK FROM BLOCKING_CELL INNER JOIN BLOCKING_AREA ON BLOCKING_CELL.ID_BLOCKING_AREA = BLOCKING_AREA.ID JOIN PLACEMENT ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_CELL.ID_BLOCKING_AREA AND PLACEMENT.SLOT_ = BLOCKING_CELL.SLOT_ AND PLACEMENT.ROW_ = BLOCKING_CELL.ROW_ WHERE BLOCKING_AREA.ID_YARD_AREA = '$yard_id' AND BLOCKING_CELL.INDEX_CELL = '$cell_idx' ";
								$res_content 		 = $db->query($query_count_content);
								$row_block			 = $res_content->fetchRow();
								$id_block			 = $row_block['ID_BLOCK'];
								
								$query_get_r_s		 = "SELECT COUNT(*) AS JUM FROM BLOCKING_CELL INNER JOIN PLACEMENT ON BLOCKING_CELL.ID_BLOCKING_AREA = PLACEMENT.ID_BLOCKING_AREA AND PLACEMENT.SLOT_ = BLOCKING_CELL.SLOT_ AND PLACEMENT.ROW_ = BLOCKING_CELL.ROW_ WHERE BLOCKING_CELL.ID_BLOCKING_AREA = '$id_block' AND BLOCKING_CELL.INDEX_CELL = '$cell_idx'";
								$res_r_s			 = $db->query($query_get_r_s);
								$row_count		 	 = $res_r_s->fetchRow();
								
								
					?>			
								<script> cell[<?php echo $cell_idx?>].stack = 1;</script>
								<li class="ui-stacking-default"  <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><font size="3px"><?php echo $row_count['JUM']?></font></li>							
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

<?php 

	$query_blocking_area = "SELECT * FROM BLOCKING_AREA WHERE ID_YARD_AREA = '$yard_id'";
	$result_block	 	 = $db->query($query_blocking_area);
	$block__			 = $result_block->getAll();
	
	foreach ($block__ as $block_)
	{
		$c_b = 0;
		$block_id 		= $block_['ID'];
		$block_name 	= $block_['NAME'];
		$block_color	= $block_['COLOR'];
		
		$query_blocking_cell = "SELECT * FROM BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$block_id' ORDER BY INDEX_CELL ASC";
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
						$("#selectable li").eq(<?php echo $cell_block['INDEX_CELL']?>).attr( "title", "Block "+block_name[v]+",<?php echo $cell_block['SLOT']."-".$cell_block['ROW'];?>" );
					<?php 	
					$h++;
				}
			?>
			v++;
		</script>	
<?php 
	}

?>

<div>
	<table>
		<?php 
		$result_block2  = $db->query($query_blocking_area);
		$block__		= $result_block2->getAll();
		
		foreach ($block__ as $block_)
		{
			?>
			<tr>
				<td style="background-color: <?php echo $block_['COLOR']?>" height="10px" width="15px">
				
				</td>
				<td>
					:
				</td>
				<td>
					Blok <?php echo $block_['NAME'] ?>
				</td>
			</tr>
		<?php 
		}
		?>
	</table>
</div>

<div style="margin-top:30px;">
	<form>
	<table>
		<tr>
			<td colspan=5 align="center">
				Container Movement
			</td>
		</tr>
		<tr>
			<td>
				Container No. 
			</td>
			<td>
				:
			</td>
			<td colspan=3>
				<input type="text" size="15" onkeyup="fill_loc($('#cont_no').val())" id="cont_no"></input>
			</td>
		</tr>
		<tr>
			<td>
				Last Placement
			</td>
			<td>
				:
			</td>
			<td>
				Block <input type="text" id="old_block" readonly style="background-color: #D0D0D0; width: 25px;"></input>
			</td>
			<td>
				- Slot <input type="text" id="old_slot" readonly style="background-color: #D0D0D0; width: 25px;"></input>
			</td>
			<td>
			  	- Row <input type="text" id="old_row" readonly style="background-color: #D0D0D0; width: 25px;"></input> 
			</td>
			<td>
			  	- Tier <input type="text" id="old_tier" readonly style="background-color: #D0D0D0; width: 25px;"></input> 
			</td>
		</tr>
		<tr>
			<td>
				New Placement
			</td>
			<td>
				:
			</td>
			<td>
				Block <input type="text" size=2 name="n_block_name" style="width:25px;"></input>
			</td>
			<td>
				- Slot <input type="text" size=2 name="n_slot" style="width:25px;"></input>
			</td>
			<td>
			  	- Row <input type="text" size=2 name="n_row" style="width:25px;"></input> 
			</td>
			<td>
			  	- Tier <input type="text" size=2 name="n_tier" style="width:25px;"></input> 
			</td>
		</tr>
		<tr>
			<td colspan=5>
				<input type="submit" value="Change" id="shift_button"></input>
			</td>
		</tr>
	</table>
	</form>
</div>

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
		      			//$("#dialog").html(data);
						$( "#dialog" ).dialog( "open" );
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