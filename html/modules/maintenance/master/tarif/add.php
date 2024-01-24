<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;
}
.main_side{
	width:100%;
	float:left;
	text-align:left;
}
h2 {text-transform:uppercase;}
</style>
<?php
?>
<script type="text/javascript">

$(document).ready(function() 
{	
	$("#s_period").datepicker({
		dateFormat: 'dd-mm-yy'
		});
		
	$("#e_period").datepicker({
		dateFormat: 'dd-mm-yy'
		});
	
});
</script>
<div class="content">
<div class="main_side">


<h2>Add (Fare)</h2>
</br>

<form method="POST" action="<?=HOME?>maintenance.master.tarif/simpan">
<div class="form-fieldset" style="margin: 5px 5px 5px 5px">
<table>
		
		<tr>
			<td>SIZE</td>
			<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td>
				<select id="size_" name="size_"> 
					<option>-- choose --</option>
					<option value="20"> 20 </option>
					<option value="40"> 40 </option>
					<option value="45"> 45 </option>
				</select>
			
			</td>
		</tr>
		<tr>
			<td>TYPE</td>
			<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td>
				<select id="type_" name="type_"> 
					<option>-- choose --</option>
					<option value="HQ"> HQ </option>
					<option value="OVD"> OVD </option>
					<option value="TNK"> TNK </option>
					<option value="DRY"> DRY </option>
					<option value="RFR"> RFR </option>
					<option value="OT"> OT </option>
					<option value="FLT"> FLT </option>
				</select>
				
			</td>
		</tr>
		<tr>
			<td>STATUS</td>
			<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td>
				<select id="status_" name="status_"> 
					<option>-- choose --</option>
					<option value="FCL"> Full </option>
					<option value="MTY"> Empty </option>
				</select>
				
			</td>
		</tr>
		<tr>
			<td>HEIGHT</td>
			<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td>
				<select id="height_" name="height_"> 
					<option>-- choose --</option>
					<option value="BIASA"> 8.6 / 9.6</option>
					<option value="OOG"> OOG </option>
				</select>
				
			</td>
		</tr>
		<tr>
			<td>ID CONTAINER</td>
			<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td>
				<input type="text" id="kdbarang" name="kdbarang"/>
			</td>
		</tr>
		<tr>
			<td>TYPES OF FEE</td>
			<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td><input type="text" name="jenis_biaya" id="jenis_biaya"/>
				
			</td>
			
		</tr>
		<tr>
			<td>BIAYA LAINNYA</td>
			<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td><input type="text" name="jenis_biaya2" value={$ubah.JENIS_BIAYA2} id="jenis_biaya2"/>
				<block visible="error.jenis_biaya2"> <span class="form-field-error"> {$error.jenis_biaya2} </span> </block> 
			</td>
			
		</tr>
		<tr>
			<td>VALUE</td>
			<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td>
				<select id="id_VAL" name="id_VAL" selected="ubah.VAL" 
				list="combolist.VAL_ID" key="id" label="" > </select>
				<input type="text" name="pkVal" value={$ubah.VAL} id="pkVal" hidden="true"/>
				<block visible="error.id_VAL"> <span class="form-field-error"> {$error.id_VAL} </span> </block> 
			</td>
		</tr>
		<tr>
			<td>TARIF</td>
			<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td><input type="text" name="tarif" id="tarif" value={$ubah.TARIF} onkeypress='return isNumberKey(event)'/>
				<block visible="error.tarif"> <span class="form-field-error"> {$error.tarif} </span> </block> 
			</td>
		</tr>
		<tr>
			<td>START PERIOD</td>
			<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td><input type="text" name="s_period" id="s_period" value={$ubah.START_PERIOD} readonly="readonly" />
				<block visible="error.s_period"> <span class="form-field-error"> {$error.s_period} </span> </block> 
			</td>
		</tr>
		<tr>
			<td>END PERIOD</td>
			<td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
            <td><input type="text" name="e_period" id="e_period" value={$ubah.END_PERIOD} readonly="readonly"/>
				<block visible="error.e_period"> <span class="form-field-error"> {$error.e_period} </span> </block> 
			</td>
		</tr>
</table>
</div>
<br/>
<input type="submit" value="Simpan" name="Simpan" class="link-button"/>
</form>
<br/>

</div>
</div>