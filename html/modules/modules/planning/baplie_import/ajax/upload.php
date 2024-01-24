<?php
	$no_ukk=$_GET['id_vessel'];
	$db=getDb('dbint');
	$rb=$db->query("select vessel as NM_KAPAL,VESSEL_CODE,CALL_SIGN, OPERATOR_ID,OPERATOR_NAME, VOYAGE_IN, VOYAGE_OUT from m_vsb_voyage WHERE id_vsb_voyage='$no_ukk'");
	$rt=$rb->fetchRow();
?>
<script language="JavaScript">
function cek_upload() {
	var fileup = document.getElementById("uploadfile").value;
	var upp    = fileup.toUpperCase();
	var noukk  = document.getElementById("NO_UKK").value;
	if(fileup=="") {
		alert("File Upload harus diisi!!!");
		return false;
	}
	else if (upp.substr(-3)!="CSV"){
		alert("File Upload harus berupa file csv!!!");
		return false;
	}
	else {
		return konfirmasi();
	}
}

function konfirmasi() {	

	question    = confirm("data akan diupload, cek apakah file sudah benar?")
	
	if (question != "0") {
		return true;
	}
	else					
		return false;
}
</script>
<div>
	</div>

    <div>

	<TABLE>
		<TR>
			<td><b>Vessel - Voyage</b></td>
			<td><b>:</b></td>
			<td><b><?=$rt['NM_KAPAL']?></b></td>
		</TR>
		<TR>
			<td><b>UKK</b></td>
			<td><b>:</b></td>
			<td><b><?=$no_ukk?></b></td>
		</TR>
		<TR>
			<td></td>
		</TR>
	</TABLE>
	<br>
	<hr width="100%">
	<br>
	<form name="form" id="form" enctype="multipart/form-data" method="POST" action="<?=$HOME?>planning.baplie_import.ajax/save_upload" onSubmit="cek_upload()">	
    <table>
		<tr>
			<td>Vessel</td>
			<td> : </td>
			<td><input type="text"  id="ves" name="ves" size="20" value="<?=$rt[NM_KAPAL]?>"/><BR>
				<input type="text"  id="vi" name="vi" size="5" value="<?=$rt[VOYAGE_IN]?>"/>
				<input type="text"  id="vo" name="vo" size="5" value="<?=$rt[VOYAGE_OUT]?>"/>
			</td>
		</tr>
		<tr>
			<td>Vessel Code</td>
			<td> : </td>
			<td><input type="text"  id="vsc" name="vsc" size="10" value="<?=$rt[VESSEL_CODE]?>"/></td>
		</tr>
		<tr>
			<td>Call Sign</td>
			<td> : </td>
			<td><input type="text"  id="csg" name="csg" size="10" value="<?=$rt[CALL_SIGN]?>"/></td>
		</tr>
		<tr>
			<td>Operator ID</td>
			<td> : </td>
			<td><input type="text"  id="opid" name="opid" size="10" value="<?=$rt[OPERATOR_ID]?>"/> <input type="text"  id="opnm" name="opnm" size="20" value="<?=$rt[OPERATOR_NAME]?>"/></td>
		</tr>
        <tr>
			<td class="form-field-caption" align="right">Input File (Format .csv)</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
				<input type="file"  id="uploadfile" name="uploadfile" size="20"></td>
         </tr>
		 	<tr>
				<td class="form-field-caption" align="right">Status Data</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left"><select id="modus" name="modus"><option value='-'> -- Pilih --</option>
				<option value='overwrite'>Overwrite</option><option value='append'>Append</option></select></td>
			</tr>
			<tr>

			<td colspan="3">
			<input type="hidden" id="NO_UKK" name="NO_UKK" value="<? echo $no_ukk; ?>"  />
			<input type="submit" value="Upload File">
			</td>
        </table>
		
		</FORM>
    </div>