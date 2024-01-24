<?
	$no_cont = $_GET['nocont'];
	$spec = $_GET['spec'];
	$no_ukk = $_GET['ukk'];
	$brt = $_GET['brt'];
	$st = $_GET['st'];
	$alat = $_GET['alat'];
	$op_alat = $_GET['op_alat'];
	
	$db = getDb();
	$query	="SELECT NM_KAPAL||' '||VOYAGE_IN||'-'||VOYAGE_OUT AS VESVOY 
			  FROM RBM_H 
			  WHERE TRIM(NO_UKK) = '$no_ukk'";
	$result_ukk = $db->query($query);
	$ukk1 = $result_ukk->fetchRow();
	$ukkno = $ukk1['VESVOY'];
?>
		<table>
			<tr height='20'>
				<td class="form-field-caption" align="right"></td>
				<td class="form-field-caption" align="right"></td>
				<td></td>			
			</tr>
			<tr>
				<td class="form-field-caption" align="right">NO CONTAINER</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="no_cont" id="no_cont" size="10" value="<?=$no_cont?>" readonly="readonly"/>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">SZ/TY/ST</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="spec" id="spec" size="12" value="<?=$spec?>" readonly="readonly"/>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Vessel/Voyage</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="vesvoy" id="vesvoy" size="30" value="<?=$ukkno?>" readonly="readonly"/>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Bay Row Tier</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="brt" id="brt" size="8" value="<?=$brt?>" readonly="readonly"/>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">SEAL</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<select id="seal_status" name="seal_status">
					 <?
						if($st=='FCL')
						{
					 ?>
							<option value='Y' selected>Yes</option>
							<option value='N'>No</option>
					 <?
						}
						else
						{
					 ?>
							<option value='Y'>Yes</option>
							<option value='N' selected>No</option>
					 <?
						}
					 ?>						
					</select>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Damage</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<select name="eir" id="eir">
						<option value="N" selected>No</option>
						<option value="bent">Bent (Bengkok)</option>
						<option value="broken">Broken (Pecah)</option>
						<option value="hole">Hole (Berlobang)</option>
						<option value="cut">Cut (Terpotong)</option>
						<option value="dented">Dented (Penyok)</option>
						<option value="missing">Missing (Hilang)</option>
						<option value="scratch">Scratch (Tergores)</option>
						<option value="tom">Tom (Robek)</option>
						<option value="leaking">Leaking (Bocor)</option>
						<option value="flat">Flat (Ringsek)</option>
						<option value="bruised">Bruised (Menggembung)</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">No Truck</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="no_truck" id="no_truck" size="10"/>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Remark</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="remark" id="remark" size="10"/>
				</td>
			</tr>
			<tr>
				<td colspan="3">				
				&nbsp;
				</td>
			</tr>
			<tr>
				<td colspan="3" align="center">				
				<input type="button" name="Confirm" value="&nbsp;Confirm&nbsp;" onclick="disch_cont('<?=$no_ukk?>','<?=$no_cont?>','<?=$alat?>','<?=$op_alat?>')"/>
				</td>
			</tr>
			<tr height='20'>
				<td class="form-field-caption" align="right"></td>
				<td class="form-field-caption" align="right"></td>
				<td></td>			
			</tr>
		</table>		