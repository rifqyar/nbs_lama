<?php

$db	= getDB();


$query_yard_area = "SELECT * FROM YD_YARD_AREA WHERE STATUS = 'AKTIF'";
$result          = $db->query($query_yard_area);
$yard_area       = $result->fetchRow();

$yard_id        = $yard_area['ID'];
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
	$L	= $width * $height;
	
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
	#selectable li {float: left; width: <?php echo $s."px"?>; height: <?php echo $s."px"?>; font-size: 4em; text-align: center; }
	
	
	</style>
	<script>
	var cell  		= new Array();
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
<span id="select-result" style="display: none;"></span>
<span id="result"></span>
<center>
<div style="margin-top:20px;border:1px solid black;width:auto;height:auto;overflow-y:scroll;overflow-x:scroll;">
<p style="width:100%;">
<div class="grid" style="float: left">
	<table border="0" width="100%" align="center" >
	<tr align="center" valign="top"><td width='70'></td></tr>
		<tr align="center" valign="top">
			<td align="center" valign="middle" style="padding-left: 10px; padding-right: 1px;">
				<ol id="selectable">
					<?php 
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
								$query_count_content    = "SELECT b.STATUS_BM FROM YD_BLOCKING_AREA a, YD_BLOCKING_CELL b WHERE a.ID = b.ID_BLOCKING_AREA AND a.ID_YARD_AREA = '$yard_id' AND b.INDEX_CELL = '$cell_idx'";
								$res_content            = $db->query($query_count_content);
								$row_block		= $res_content->fetchRow();
								$status 		= $row_block['STATUS_BM'];
								if ($status == 'BONGKAR'){
                                                                        $stat = 'B'; ?>
                                                                        <script> cell[<?php echo $cell_idx?>].stack = 1;</script>
								<li class="ui-hijau-default"  <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><font size="1px"><?php echo $stat?></font></li>							
                                                               <? } else {
                                                                        $stat = 'M';
                                                                        ?>
                                                                        <script> cell[<?php echo $cell_idx?>].stack = 1;</script>
								<li class="ui-placement-default"  <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><font size="1px"><?php echo $stat?></font></li>							
                                                                         <?
                                                                }
				
					?>			
								
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
</p>
</div>
</div>



<div id="dialog" title="Stacking" style="font-size: 15px; margin-top: 20px;">
</div>

<div style="padding-left: 30px; float:left">
    <fieldset><legend><font color="red"><b>Step 3 - Set Block</b></font></legend>
	
    </fieldset>
</div>
<div style="padding-left: 7px; float:left">
	<table>
	<tr height='10'><td></td></tr>
	<tr>
	<td width='20'></td>
	<td>
	<fieldset>
        <form id="blocking" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_allocation_bm/display-grid" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td colspan="3" style="padding-left: 40px;" align="right">
					<input type="submit" value=" Set " id="set_block">
			</tr>

		</table>
	</form>
            <br />
	<form id="db_link__" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_allocation_bm/index" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td>Alokasikan Bongkar  : <input type="submit" id="db_link" value="Alokasi Bongkar" >
                                    <input type="hidden" name="status" id="status_" value="BONGKAR" >
				</td>
                                
			</tr>
			
		</table>
	</form>
	<br />
	<form id="db_link_" enctype="multipart/form-data" action="<?=HOME?>maintenance.yard_allocation_bm/index" method="post">
		<table style="font-size: 12px; font-weight: bold;">
			<tr>
				<td>
					Alokasikan Muat : <input type="submit" id="db_link" value="Alokasi Muat" >
                                        <input type="hidden" name="status" id="status_" value="MUAT" >
					</td>
			</tr>
			
		</table>
	</form>
	</fieldset>
	</td>
	</tr>
	<tr height='20'><td></td></tr>
</table>
</div>
</body>

<script>

        $("#set_block").click(function(event) {
                 event.preventDefault();
		  //alert($("#result").html());
		  var selected = $("#select-result").html();
		  var array_s  = selected.split(",");
                  
		  var status_   = $("#status_").val();
                  alert(status_);
		  console.log("++"+selected+"++");
                  
		  for (var i = 0; i < (array_s.length-1); i++)
		  {
			//console.log("--"+cell[i]+"--");
			cell[array_s[i]].stack = 1;
			$("#selectable li").eq(array_s[i]).attr( "class", "ui-stacking-default");
		  }

		//console.log("++"+selected+"++");
		var p = 0;
		for (var i = 0; i < count_block; i++)
		{
			if(block_name[i] == name)
				p = 1;
		}

		if(p == 0)
		{
			status[count_block]	 = status;
			count_block++;
		}
		
	});
        
	$("#db_link").click(function(event) {
		event.preventDefault();
		//build array of blocking area
		var status = new Array();
                var index_block = new Array();
		var p = 0;
                var status_  = $("#status_").val();
                
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
			block_str += "<block><status>"+status[j]+"</status><cell>"+index_block[j].join(",")+"</cell></block>";
		}

		//complete xml string
		var xml_str = "\<\?xml version=\"1.0\" encoding=\"UTF-8\"\?\><yard>"+block_str+"</yard>";
		console.log(xml_str);
		 var url = "<?=HOME?>maintenance.yard_allocation_bm.ajax/yard_dblink";
		$.post( url, { xml_: xml_str},
			      function(data) {
					alert(data);
		      		console.log(data);
			      }
			    );
	});
		
	
</script>

</html>



<?php 
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
								$query_count_content    = "SELECT b.STATUS_BM FROM YD_BLOCKING_AREA a, YD_BLOCKING_CELL b WHERE a.ID = b.ID_BLOCKING_AREA AND a.ID_YARD_AREA = '$yard_id' AND b.INDEX_CELL = '$cell_idx'";
								$res_content            = $db->query($query_count_content);
								$row_block		= $res_content->fetchRow();
								$status 		= $row_block['STATUS_BM'];
								if ($status == 'BONGKAR'){
                                                                        $stat = 'B'; ?>
                                                                        <script> cell[<?php echo $cell_idx?>].stack = 1;</script>
								<li class="ui-hijau-default"  <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><font size="1px"><?php echo $stat?></font></li>							
                                                               <? } else {
                                                                        $stat = 'M';
                                                                        ?>
                                                                        <script> cell[<?php echo $cell_idx?>].stack = 1;</script>
								<li class="ui-placement-default"  <?php if (($i%$m) == 0){ $j++;	?>style="clear: both;"<?php }?>><font size="1px"><?php echo $stat?></font></li>							
                                                                         <?
                                                                }
				
					?>			
								
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