<?$id_bg =1;?><font color="red"><blink> Choose Block  : </blink></font>  
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
				
				  <button onclick="del_alokasi()"> Submit </button>
				  <div id="yai"></div>
<script>

function del_alokasi()
{
		var blid = $('#block_id').val();

		$('#yai').load("<?=HOME?>planning.yard_allocation_import.ajax/yai_delete/?id_block="+blid).dialog({modal:true, height:900,width:1000,title: "Yard Allocation Planning Import"});
	
}


</script>