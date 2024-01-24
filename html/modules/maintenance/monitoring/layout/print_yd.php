<table>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
		<tr>				
			<td class="form-field-caption" align="right">Block</td>
			<td class="form-field-caption" align="right"> : </td>
			<td colspan='4'>
				<select name="block_select" id="block_select">
						<option value="">-Pilih-</option>				
				<?
					$db = getDB();
					$query_get_block = "SELECT ID,NAME FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA = '23' ORDER BY ID ASC";
					$result_block    = $db->query($query_get_block);
					$row_block       = $result_block->getAll();
					
					foreach ($row_block as $row5) {
						$nm_block = $row5['NAME'];;
				?>				
						<option value="<?=$row5['ID'];?>,<?=$nm_block?>"><?=$nm_block?></option>
						
				<? } ?>		
					  </select>
			</td>			
		</tr>
		<tr>				
			<td class="form-field-caption" align="right">Slot</td>
			<td class="form-field-caption" align="right"> : </td>
			<td colspan='4'>
				<input type="text" name="slot_yd" id="slot_yd" size="3"/>
			</td>			
		</tr>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
		<tr>
			<td colspan='6' align="center"><input type="button" name="cetak" onclick="cetak_yard()" value=" cetak " /></td>			
		</tr>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
	</table>