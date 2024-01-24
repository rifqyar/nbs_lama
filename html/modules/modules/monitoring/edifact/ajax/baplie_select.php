<?
	$no_ukk = $_GET['no_ukk'];
	
?>	
	<table>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
		<tr>				
			<td class="form-field-caption" align="right">Activity</td>
			<td class="form-field-caption" align="right"> : </td>
			<td colspan='4'>
				<select name="baplie_act" id="baplie_act">
					<option value="">-pilih-</option>
					<option value="BONGKAR">Disch</option>
					<option value="MUAT">Load</option>
				</select>
			</td>			
		</tr>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
		<tr>
			<td colspan='6' align="right"><input type="button" name=" proses " onclick="baplie('<?=$no_ukk?>')" value="proses" /></td>			
		</tr>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
	</table>