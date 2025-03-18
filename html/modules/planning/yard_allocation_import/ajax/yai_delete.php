<link rel="stylesheet" href="./yard/src/css/excite-bike/jquery-ui-1.8.16.custom.css">
<script src="./yard/src/js/jquery-1.7.min.js" type="text/javascript"></script>
<script src="./yard/src/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="./yard/src/css/main_yai.css">
<script type="text/javascript" src="./js/multi/jquery.blockUI.js"></script>

<?php
    $db			   = getDb();;
    $nama_ya       = '';
	$yard_id       = 81;
	//$kategori	= $_GET['kategori'];
	$block		= $_GET['id_block'];
    $id_bg   	  = 1;
	$noukk		= $_SESSION['NO_UKK'];
	//$id_book	= $_SESSION['ID_BOOK'];

?>
<script>
$(document).ready(function() 
{
		$('#info').load("<?=HOME?>planning.yard_allocation_import.ajax/load_info?id=<?=$yard_id?>&id_block=<?=$block?>");   
		$('#lp<?=$id_bg?>').load("<?=HOME?>planning.yard_allocation_import.ajax/load_lapangan?id=<?=$yard_id?>&id_bg=<?=$id_bg?>&id_block=<?=$block?>");   
		$('#detail_slot').load("<?=HOME?>planning.yard_allocation_import.ajax/detail_slot?id=<?=$yard_id?>&id_block=<?=$block?>");  
	
});

</script>
 <fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; padding: 5px">
<legend><font color="blue"> &nbsp   <b>Grouping Category</b>   &nbsp </font></legend>
  <table style="font-size: 12px; font-weight: bold; " border="0">
            <tr>
				<td>No</td>
                <td>Size</td>
				   <td>Type</td>
				  <td>Status</td>
				   <td>Height</td>
					<td>Allocation </td>
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
					<td> <?=$row['TEUS'];?> TEUS</td>
				 <td><?=$row['ID_BOOK'];?></td>
				 <td><?=$row['HZ'];?></td>
			</tr>
			<?$i++;}?>
				  <div id="info"></div>
				   <div id="lp<?=$id_bg?>"></div>
	</table>
			
<br>			
<font color="red"><blink> Insert Tier </blink></font>

                    <input type="text" name="tier_c" id="tier_c"    value="<?=$tier?>" size="6"/>

				<font color="red"><blink> Choose Block </blink></font>  
<input type="hidden" id="block_name" value="<?=$block_id?>" >
                   
				<select name="block_id" id="block_id">
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
                </select>
Allocated <input type="button" value="Alocated" onclick="cek_alokasi()">
			<!--	Dealocation</font>  

					 <input type="button" value="Dealocated" onclick="cek_alokasi()"> &nbsp-->
						<font color="red"><blink> FINAL SAVE  </blink></font>   

                    <input type="button" value=" Save Alocation " onclick="oncl()">

</fieldset>

<table>
	<tr><td>
	<div id="info"></div></td>
	<td rowspan="2"><div id="detail_slot"></div></td></tr>
	<tr><td><div id="lp<?=$id_bg?>"></div></td></tr>
</table>


 <fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; padding: 5px">		
        <table align="center" border="0">
                <tr align="center"><td colspan="39"class="grid-header">Legend</td></tr>
                <tr align="center" bgcolor="#ffffff">
                    <td class="grid-cell"><img src="yard/src/css/excite-bike/images/ui-bg_diagonals-thick_22_1484e6_40x40.png" width="20px" height="20px">
                    </td>
                    <td class="grid-cell">20 &nbsp DRY</td>
					<td></td>
					<td class="grid-cell"><img src="yard/src/css/excite-bike/images/ungu.png" width="20px" height="20px"></td><td class="grid-cell">40 &nbsp HQ</td>
					<td></td>
					<td class="grid-cell"><img src="yard/src/css/excite-bike/images/ui-bg_diagonals-thick_18_b81900_40x40green.png" width="20px" height="20px"></td><td class="grid-cell">45 &nbsp HQ</td>
					<td></td>
					 <td class="grid-cell">
                        <img src="yard/src/css/excite-bike/images/40rfr.png" width="20px" height="20px"></td><td class="grid-cell">20,40
                    &nbsp TNK</td>
					<td></td>
						 <td class="grid-cell">
                        <img src="yard/src/css/excite-bike/images/abu2.png" width="20px" height="20px"></td><td class="grid-cell">20,40
                    &nbsp OT</td>
					<td></td>
                    <td class="grid-cell">
                        <img src="yard/src/css/excite-bike/images/ui-bg_diagonals-thick_95_ffdc2e_40x40.png" width="20px" height="20px"></td><td class="grid-cell">40
                    &nbsp DRY</td>
					<td></td>
					<td class="grid-cell"><img src="yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png" width="20px" height="20px"></td><td class="grid-cell">20,40,45 &nbsp DG</td>
					<td></td>
					<td class="grid-cell">
                        <img src="yard/src/css/excite-bike/images/20flt.png" width="20px" height="20px"></td><td class="grid-cell">20,40
                   &nbsp RFR</td>
					<td></td>
					  <td class="grid-cell">
                        <img src="yard/src/css/excite-bike/images/OVD.png" width="20px" height="20px"></td><td class="grid-cell">20,40
                    &nbsp OVD</td>
                </tr>
            
               
       </table>
	</fieldset>

   