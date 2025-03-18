
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
			<td class="form-field-caption" align="right">Printed By</td>
			<td class="form-field-caption" align="right"> : </td>
			<td colspan='4'>
				<select name="print_act" id="print_act">
					<option value="WEIGHT">WEIGHT</option>
					<option value="CONTAINER">CONTAINER</option>
				</select>
			</td>			
		</tr>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
		<tr>
			<td colspan='6' align="right"><input type="button" name=" print " onclick="cetak_vs('<?=$no_ukk?>')" value="print" /></td>			
		</tr>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
	</table>