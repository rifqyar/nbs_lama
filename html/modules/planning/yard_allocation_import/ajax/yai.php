<link rel="stylesheet" href="./yard/src/css/excite-bike/jquery-ui-1.8.16.custom.css">
<script src="./yard/src/js/jquery-1.7.min.js" type="text/javascript"></script>
<script src="./yard/src/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="./yard/src/css/main_yai2.css">
<script type="text/javascript" src="./js/multi/jquery.blockUI.js"></script>

<?php
    $db			   = getDb();;
    $nama_ya       = '';
	$yard_id       = 23;
	$kategori	= $_GET['kategori'];
	$hei		= $_GET['height'];
	$tipe		= $_GET['tipe'];
	$hz			= $_GET['hz'];
	$idb		= $_GET['idb'];
    $id_bg   	  = 1;
	$tier		= $_GET['tier'];
	$noukk		= $_SESSION['NO_UKK'];
	$id_book	= $_SESSION['ID_BOOK'];

?>
<script>
$(document).ready(function() 
{
	$('#block_id').change(function(){
	var kate = $('#kategori').val();
	
		var blid = $('#block_id').val();
		$.ajaxSetup ({
		// Disable caching of AJAX responses
		cache: false
		});
		
		$('#info').load("<?=HOME?>planning.yard_allocation_import.ajax/load_info?id=<?=$yard_id?>&idb=<?=$idb?>&id_block="+blid+"&kategori="+kate+"&type=first");   
		//$('#detail_slot').load("<?=HOME?>planning.yard_allocation_import.ajax/detail_slot?id=<?=$yard_id?>&id_block="+blid); 		 
		$('#lp<?=$id_bg?>').load("<?=HOME?>planning.yard_allocation_import.ajax/load_lapangan?id=<?=$yard_id?>&id_bg=<?=$id_bg?>&id_block="+blid+"&kategori="+kate+"&no_ukk=<?=$noukk;?>");      
		//$('#detail_slot').load("<?=HOME?>planning.yard_allocation_import.ajax/detail_slot?id=<?=$yard_id?>&id_block="+blid);   
		
	});
});

function load_lp()
{
    var blid=$('#block_id').val();
    $.ajaxSetup ({
    // Disable caching of AJAX responses
    
    cache: false
    });
	
    $('#info').load("<?=HOME?>planning.yard_allocation_import.ajax/load_info?id=<?=$yard_id?>&id_block="+blid+"&type=first");   
    $('#lp<?=$id_bg?>').load("<?=HOME?>planning.yard_allocation_import.ajax/load_lapangan?id=<?=$yard_id?>&id_block="+blid+"&id_bg=<?=$id_bg?>");   
}

function cancel()	{	

var url="<?=HOME?>planning.yard_allocation_import.ajax/cancel";		
	
$.post(url,{},function(data){	
alert(data);
	window.location = "<?=HOME?>planning.yard_allocation_import";
});

}
</script>
 <fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; padding: 5px">
<legend><font color="blue"> &nbsp   <b>Grouping Category</b>   &nbsp</font></legend>
  <table style="font-size: 12px; font-weight: bold; " border="0">
            <tr>
				<td>No</td>
                <td>Size</td>
				   <td>Type</td>
				  <td>Status</td>
				   <td>Height</td>
				   <td> BOX</td>
					<td> TEUS</td>
				 <td>ID Book</td>
				 <td>Hz</td>
			</tr>
			<?php 
					$i = 1;
					$db = getDB();
                    $query_get_block2  = "SELECT a.* FROM TB_BOOKING_CONT_AREA a, TB_BOOKING_CONT_AREA_GR b WHERE TRIM(a.ID_KATEGORI) = TRIM(b.ID_KATEGORI)  AND b.ID_KATEGORI = '$kategori'";
                    $result_block2	= $db->query($query_get_block2);
                    $row_block2		= $result_block2->getAll();
                    foreach($row_block2 as $row)
                    {
            ?>
			<tr>
				<td><input type="hidden" name="kategori" id="kategori" value="<?=$kategori?>"><?=$i;?></td>
				<td><?=$row['SIZE_CONT'];?></td>
				   <td><?=$row['TYPE_CONT'];?></td>
				  <td><?=$row['STATUS_CONT'];?></td>
				   <td><?=$row['HEIGHT'];?></td>
				   <td> <?=$row['BOX'];?> BOX</td>
					<td> <?=$row['TEUS'];?> TEUS</td>
				 <td><?=$row['ID_BOOK'];?></td>
				 <td><?=$row['HZ'];?></td>
			</tr>
			<?$i++;}?>
			
	</table>
			
<br>			


<table>
<tr>
<td><fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; padding: 5px">
<legend><font color="blue"> &nbsp   <b>Insert Area</b>   &nbsp</font></legend>
<font color="red"> Choose Block </font>  
<input type="hidden" id="block_name" value="<?=$block_id?>" >
: <select name="block_id" id="block_id">
                <option value="" selected> -- choose --</option>
				<?php 
					$db = getDB();
                    $query_get_block     = "SELECT a.* FROM YD_BLOCKING_AREA a, YD_YARD_AREA b WHERE a.NAME <> 'NULL' AND a.ID_YARD_AREA = b.ID AND b.STATUS = 'AKTIF'";
                    $result_block	= $db->query($query_get_block);
                    $row_block		= $result_block->getAll();
                    foreach($row_block as $row)
                    {
                ?>
				<option value="<?php echo $row['ID']?>"><?php echo $row['NAME'] ?></option>
                <?php 
					}
                ?>
                </select><br><br>
<font color="red"> Insert Max Tier</font>
: <input type="text" name="tier_c" id="tier_c"    value="<?=$tier?>" size="6"/><br><br>
</fieldset></td>
<td><fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; padding: 5px">
<legend><font color="blue"> &nbsp   <b>Allocated Area</b>   &nbsp</font></legend>
<font color="red"><font size="1">Allocated Cell  </font>
: <input type="button" value="Alocated" onclick="cek_alokasi()"><br><br>
</fieldset></td>
<td><fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; padding: 5px">
<legend><font color="blue"> &nbsp   <b>Delete Area</b>   &nbsp</font></legend>
<font color="red"><font size="1">Select cell to delete </font>
: <input type="button" value="Select" onclick="onstack_delete()"><br>

<font color="red"><font size="1">Delete </font>
: <input type="button" value="DELETE" onclick="delete_alokasi()">
</fieldset></td>
<td><fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; padding: 5px">
<legend><font color="blue"> &nbsp   <b>Final Save</b>   &nbsp</font></legend>
<font color="red"><font size="4"><blink> FINAL SAVE  </blink></font> 
:<input type="button" value=" Save Alocation " onclick="oncl()">
</fieldset></td>
<td><fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; padding: 5px">
<legend><font color="blue"> &nbsp   <b>Cancel</b>   &nbsp</font></legend>
<font color="red"><font size="4"><blink> CANCEL </blink></font>
:<input type="button" value=" CANCEL " onclick="cancel()">
</fieldset></td>
</tr>






</tr>
</table>

</fieldset>

<table>
	<tr><td>
	<div id="info"></div></td>
	<td rowspan="2"><!--<div id="detail_slot"></div>--></td></tr>
	<tr height="10"><td></td></tr>
	<tr><td><div id="lp<?=$id_bg?>"></div></td></tr>
</table>
