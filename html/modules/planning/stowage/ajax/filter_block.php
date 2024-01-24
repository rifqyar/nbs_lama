<?
	$id_vs = $_GET['id_vs'];
	$yard = $_GET['yard'];
?>
<div align='center'>
<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; background-color:#ffffff; vertical-align:middle; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;">
<form id="form" method="POST" action="<?=HOME?>planning.stowage.ajax/stow_bay?id_vs=<?=$id_vs?>&yard=<?=$yard?>&filter=0">
				<table border="0">
					<tr height="20">
						<td>Block</td>
						<td>&nbsp;:&nbsp;</td>
						<td><select name="block_id" id="block_id">
							<option value="" selected> -- choose --</option>
							<?php 
								$db = getDB();
								$query_get_block     = "SELECT a.* 
														FROM YD_BLOCKING_AREA a, YD_YARD_AREA b 
														WHERE a.NAME <> 'NULL' AND a.ID_YARD_AREA = b.ID AND b.STATUS = 'AKTIF'";
								$result_block	= $db->query($query_get_block);
								$row_block		= $result_block->getAll();
								foreach($row_block as $row)
								{
							?>
							<option value="<?php echo $row['ID']?>,<?php echo $row['NAME'] ?>,1"><?php echo $row['NAME'] ?></option>
							<?php 
								}
							?>
							</select>&nbsp;&nbsp;&nbsp;<input type="submit" value=" Proses " />
						</td>
					</tr>
				</table>
				</form>
				</fieldset>

</div>