<?php
include_once '../config/config.php';


if(!isset($_POST['yard_id']))
{
	?>
		<center>
		<form enctype="multipart/form-data" action="./view_yard_single_view.php" method="post">
			<select name="yard_id">
			<?php 
				$query_get_yard = "SELECT * FROM YARD_AREA";
				$result_yard	= dbQuery($query_get_yard);
				while($row_yard = dbFetchArray($result_yard))
				{
			?>
			  <option value="<?php echo $row_yard['ID']?>"><?php echo $row_yard['NAME'] ?></option>
			<?php 
				}
			?>
			</select>
			<input type="submit" value=" Go "> </input>
		</form>
		</center>
	<?php
	die; 
}
$yard_id = $_POST['yard_id'];

$query_yard_area = "SELECT * FROM YARD_AREA WHERE ID LIKE '$yard_id'";
$result 		 = dbQuery($query_yard_area);
$yard_area		 = dbFetchArray($result);

$yard_name	= $yard_area['NAME'];
$width  	= $yard_area['WIDTH'];
$height 	= $yard_area['HEIGHT'];
//echo "-----$yard_id---------";
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
					get_cell_content(id);
					//result.append( id+"," );
					//$("#selectable li").eq(id).attr( "class", "ui-stacking-default");
				});
			}
		});
		
		$( "#dialog" ).dialog({
			autoOpen: false,
			show: "blind"
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
						$result			 = dbQuery($query_yard_cell);
						$cell_idx 		 = 0;
						$i = 1; 
						while($row_cell  = dbFetchArray($result))
						{	
							$m = ($width*$j) + 1;
							
							if($row_cell['STACK'] == 1)
							{
								$query_count_content = "SELECT BLOCKING_AREA.ID AS ID_BLOCK FROM BLOCKING_CELL INNER JOIN BLOCKING_AREA ON BLOCKING_CELL.ID_BLOCKING_AREA = BLOCKING_AREA.ID JOIN PLACEMENT ON PLACEMENT.ID_BLOCK = BLOCKING_CELL.ID_BLOCKING_AREA AND PLACEMENT.SLOT = BLOCKING_CELL.SLOT AND PLACEMENT.ROW = BLOCKING_CELL.ROW WHERE BLOCKING_AREA.ID_YARD_AREA = '$yard_id' AND BLOCKING_CELL.INDEX_CELL = '$cell_idx' ";
								$res_content 		 = dbQuery($query_count_content);
								$row_block			 = dbFetchArray($res_content);
								$id_block			 = $row_block['ID_BLOCK'];
								
								$query_get_r_s		 = "SELECT COUNT(*) AS JUM FROM BLOCKING_CELL INNER JOIN PLACEMENT ON BLOCKING_CELL.ID_BLOCKING_AREA = PLACEMENT.ID_BLOCK WHERE BLOCKING_CELL.ID_BLOCKING_AREA = '$id_block' AND BLOCKING_CELL.INDEX_CELL = '$cell_idx'";
								$res_r_s			 = dbQuery($query_get_r_s);
								$row_count		 	 = dbFetchArray($res_r_s);
								
								
					?>			
								<script> cell[<?php echo $cell_idx?>].stack = 1;</script>
								<li class="ui-stacking-default" <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><?php echo $row_count['JUM']?></li>							
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
	$result_block				 = dbQuery($query_blocking_area);
	
	while ($block_ = dbFetchArray($result_block))
	{
		$c_b = 0;
		$block_id 		= $block_['ID'];
		$block_name 	= $block_['NAME'];
		$block_color	= $block_['COLOR'];
		
		$query_blocking_cell = "SELECT * FROM BLOCKING_CELL WHERE ID_BLOCKING_AREA = '$block_id' ORDER BY INDEX_CELL ASC";
		$result_cell		 = dbQuery($query_blocking_cell);
?>	
		<script>
			block_name[v] 	= "<?php echo $block_name;?>";
			block_color[v]	= "<?php echo $block_color;?>";
			<?php 
				$h = 0;
				while($cell_block = dbFetchArray($result_cell))
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
		$result_block2  = dbQuery($query_blocking_area);
		
		while ($block_ = dbFetchArray($result_block2))
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
<div id="dialog" title="Stacking" style="font-size: 15px; margin-top: 50px;">
</div>
</body>

<script>
	function get_cell_content(id)
	{
		var cell_index  = id;
		var yard		= yard_id;
		var url 		= "./get_content_single.php";

		$.post( url, {index : cell_index, yard_id : yard},
			      function(data) {
		      			$("#dialog").html(data);
						$( "#dialog" ).dialog( "open" );
			      		console.log(data);
			      }
			    );
	}

</script>

</html>
