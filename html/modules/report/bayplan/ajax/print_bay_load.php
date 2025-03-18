<script>
	$(document).ready(function()
        {	
			document.getElementById('bay_slct').style.display = 'none';
			
		});
		
	function set_dropdown()
		{
			var print = $("#print_act").val();
			
			if(print=='BAY')
			{
				document.getElementById('bay_slct').style.display = 'inline';
			}
			else
			{
				document.getElementById('bay_slct').style.display = 'none';
			}
		}
	
</script>
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
				<select name="print_act" id="print_act" onChange="set_dropdown()">
					<option value="ALL">ALL</option>
					<option value="BAY">BAY</option>
				</select>
				&nbsp;&nbsp;&nbsp;				
				<span id="bay_slct">
					<select name="bay_act" id="bay_act">
					<?
						$db = getDB();
						$list_bay = "SELECT ID,BAY FROM STW_BAY_AREA WHERE ID_VS = '$no_ukk' AND BAY > 0 ORDER BY BAY ASC";
						$result = $db->query($list_bay);
						$bays = $result->getAll();
						
						foreach($bays as $rows)
						{
							$bay_no = $rows['BAY'];
							
							if(($bay_no==1)||(($bay_no-1)%4==0))
							{
								$bay_label = $bay_no."(".($bay_no+1).")";
							}
							else
							{
								$bay_label = $bay_no;
							}
					?>	
							<option value="<?=$rows['ID'];?>,<?=$bay_label;?>"><?=$bay_label;?></option>
					<?	
						}
					?>
					</select>&nbsp;&nbsp;&nbsp;
					<select name="posisi_bay" id="posisi_bay">
						<option value="ABOVE">ABOVE</option>
						<option value="BELOW">BELOW</option>
					</select>
				</span>
			</td>			
		</tr>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
		<tr>
			<td colspan='6' align="right"><input type="button" name=" proses " onclick="cetak_bay_load('<?=$no_ukk?>')" value="proses" /></td>			
		</tr>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
	</table>