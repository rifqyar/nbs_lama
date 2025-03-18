<!-- dual list box-->
<script type="text/javascript" src="<?=HOME?>js/jquery.dualListBox-1.3.min.js"></script>
<script type="text/javascript">
$(function() {
	$.configureBoxes();
})

$(document).ready(function(){

	$("#tgl_perp").datepicker({
			dateFormat: 'dd-mm-yy'
            });
	
});
</script>

<?
	$db = getDB();
	$db8 = getDB('ora');
	$iddel = $_GET['id_del'];
	
	$cek_plp = "SELECT NO_REQUEST AS ID_REQ, VESSEL, VOY_IN||'-'|| VOY_OUT AS VOYAGE FROM BIL_DELSPJM_H WHERE TRIM(ID_DEL) = TRIM('$iddel')";
	$plp_hsl = $db->query($cek_plp);
	$rowplp = $plp_hsl->fetchRow();
?>

		<table>
			<tr height='20'>
				<td class="form-field-caption" align="right"></td>
				<td class="form-field-caption" align="right"></td>
				<td></td>			
			</tr>
			<tr>
				<td class="form-field-caption" align="right">No Request Delivery</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left"><b><?=$rowplp['ID_REQ']?></b></td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Vessel</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left"><b><?=$rowplp['VESSEL']." ".$rowplp['VOYAGE'];?></b></td>
			</tr>
			<tr height='10'>
				<td class="form-field-caption" align="right"></td>
				<td class="form-field-caption" align="right"></td>
				<td class="form-field-caption" align="left"></td>
			</tr>
			<tr>
				<td class="form-field-caption" align="center" colspan="3">
				<hr/>
				<p>
					<br/>
				</p>
					<table>
						<tr>
							<td align="center">
								Daftar Petikemas<br/>
								Filter: <input type="text" id="box1Filter" /><button type="button" id="box1Clear">X</button><br/><br/>
								<select id="box1View" name="listnya" multiple="multiple" style="height:120px;width:200px;">
						<?								
								$query = "SELECT NO_CONT, LINE_NUMBER FROM BIL_DELSPJM_D WHERE TRIM(ID_DEL) = TRIM('$iddel')";
								if($res = $db->query($query))
									while ($row = $res->fetchRow()) {
										$nocont = $row['NO_CONT'];
										$cek_op = "SELECT COUNT(*) JML 
													FROM BIL_DELSPJM_D
													WHERE TRIM(NO_CONT) = TRIM('$nocont')
														AND ACTIVE = 1";
										$op_hsl = $db8->query($cek_op);
										$rowop = $op_hsl->fetchRow();
										
										if($rowop['JML']>0)
										{
						?>
									<option value="<?=$row['LINE_NUMBER']?>"><?=$row['NO_CONT']?></option>
						<?
										}
									}
						?>
								</select><br />
								<span id="box1Counter" class="countLabel"></span><select id="box1Storage"></select>
							</td>
							<td align="center"><br/><br/>
								<button id="to2" type="button">&nbsp;>&nbsp;</button><br/>
								<button id="allTo2" type="button">&nbsp;>>&nbsp;</button><br/>
								<button id="allTo1" type="button">&nbsp;<<&nbsp;</button><br/>
								<button id="to1" type="button">&nbsp;<&nbsp;</button>
							</td>
							<td align="center">
								Extension Petikemas<br/>
								Filter: <input type="text" id="box2Filter" /><button type="button" id="box2Clear">X</button><br/><br/>
								<select id="box2View" name="approval" multiple="multiple" style="height:120px;width:200px;"></select><br/>
								<span id="box2Counter" class="countLabel"></span><select id="box2Storage"></select>
							</td>
						</tr>
					</table>
				<p>
					<br/>
				</p>
				<hr/>
				</td>
			</tr>
			<tr height='10'>
				<td class="form-field-caption" align="right"></td>
				<td class="form-field-caption" align="right"></td>
				<td class="form-field-caption" align="left"></td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">TGL DELIVERY</td>
				<td class="form-field-caption" align="center">&nbsp;:&nbsp;</td>
				<td class="form-field-caption" align="left"><input type="text" name="tgl_perp" id="tgl_perp" size="10"/></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" align="center">
					<input type="button" name="Insert User" value="&nbsp;Proses&nbsp;" onclick="perp_entry('<?=$iddel?>')"/>
				</td>
			</tr>
			<tr height='20'>
				<td class="form-field-caption" align="right"></td>
				<td class="form-field-caption" align="right"></td>
				<td></td>			
			</tr>
		</table>		