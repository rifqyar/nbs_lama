<script type="text/javascript">
$(document).ready(function(){
	$("#btnClose").click(function(){
		$('#edit_uper').dialog('destroy').remove();
		$('#mainform').append('<div id="edit_uper"></div>');
	});
});

function cek_kegiatan(obj)
{
	var par=obj.parentNode;
	while(par.nodeName.toLowerCase()!='tr'){
		par=par.parentNode;
	}
	var index = par.rowIndex;

	$("#subkeg"+index).html("");
	if(obj.value=="BM") {
		for(i=1; i<=$('#jum_bm').val(); i++) {
			id_subkeg = $('#id_bm'+i).val();
			ket_subkeg = $('#ket_bm'+i).val();
			$("#subkeg"+index).append("<option value='"+id_subkeg+"'>"+ket_subkeg+"</option>")

		}
	}
	else if(obj.value=="TRANS") {
		for(i=1; i<=$('#jum_trans').val(); i++) {
			id_trans = $('#id_trans'+i).val();
			ket_trans = $('#ket_trans'+i).val();
			$("#subkeg"+index).append("<option value='"+id_trans+"'>"+ket_trans+"</option>")

		}
	}
	else if(obj.value=="SHIFT") {
		for(i=1; i<=$('#jum_shift').val(); i++) {
			id_shift = $('#id_shift'+i).val();
			ket_shift = $('#ket_shift'+i).val();
			$("#subkeg"+index).append("<option value='"+id_shift+"'>"+ket_shift+"</option>")

		}
	}
}

function addRowToTable(tableid, perdagangan)
{
	var tbl = document.getElementById(tableid);
	var lastRow = tbl.rows.length;
	// if there's no header row in the table, then iteration = lastRow + 1
	var iteration = lastRow;
	var row = tbl.insertRow(lastRow);
	
	var cell = row.insertCell(0);
	var el = document.createElement('input');
	el.type = 'button';
	el.value = ' - ';
	el.title = 'Hapus';
	el.onclick = function() {removeRowIndex(this,tableid)};
	cell.appendChild(el);
	
	cell = row.insertCell(1);
	el = document.createElement('input');
	el.type = 'text';
	el.name = 'counter' + iteration;
	el.id = 'counter' + iteration;
	el.size = 2;
	el.value = iteration;
	el.readOnly = true;
	cell.appendChild(el);
	
	cell = row.insertCell(2);
	var sel = document.createElement('select');
	sel.name = 'perdagangan' + iteration;
	sel.id = 'perdagangan' + iteration;
	sel.disabled = true;
	if(perdagangan == 'O') {
		sel.options[0] = new Option('Ocean Going', 'O');
		sel.options[1] = new Option('Intersuler', 'I');
	}
	else {
		sel.options[0] = new Option('Intersuler', 'I');
		sel.options[1] = new Option('Ocean Going', 'O');
	}
	cell.appendChild(sel);
	
	cell = row.insertCell(3);
	sel = document.createElement('select');
	sel.name = 'size' + iteration;
	sel.id = 'size' + iteration;
	sel.options[0] = new Option('20', '20');
	sel.options[1] = new Option('40', '40');
	sel.options[2] = new Option('45', '45');
	cell.appendChild(sel);
	
	cell = row.insertCell(4);
	sel = document.createElement('select');
	sel.name = 'type' + iteration;
	sel.id = 'type' + iteration;
	sel.options[0] = new Option('DRY', 'DRY');
	sel.options[1] = new Option('REFEER', 'RFR');
	sel.options[2] = new Option('FLAT TRACK', 'FLT');
	sel.options[3] = new Option('HIGH CUBE', 'HQ');
	sel.options[4] = new Option('OPEN TOP', 'OT');
	sel.options[5] = new Option('TANKER', 'TNK');
	sel.options[6] = new Option('OVD', 'OVD');
	cell.appendChild(sel);
	
	cell = row.insertCell(5);
	sel = document.createElement('select');
	sel.name = 'status' + iteration;
	sel.id = 'status' + iteration;
	sel.options[0] = new Option('FCL', 'FCL');
	sel.options[1] = new Option('LCL', 'LCL');
	sel.options[2] = new Option('MTY', 'MTY');
	sel.options[3] = new Option('UC', 'UC');
	cell.appendChild(sel);
	
	cell = row.insertCell(6);
	sel = document.createElement('select');
	sel.name = 'height' + iteration;
	sel.id = 'height' + iteration;
	sel.options[0] = new Option('8.6 s/d 9.6', 'BIASA');
	sel.options[1] = new Option('OOG', 'OOG');
	cell.appendChild(sel);
	
	cell = row.insertCell(7);
	sel = document.createElement('select');
	sel.name = 'bahaya' + iteration;
	sel.id = 'bahaya' + iteration;
	sel.options[0] = new Option('Tidak', 'T');
	sel.options[1] = new Option('Ya', 'Y');
	cell.appendChild(sel);
	
	cell = row.insertCell(8);
	el = document.createElement('input');
	var txtNode = document.createTextNode("Box"); 
	el.type = 'text';
	el.name = 'bongkar' + iteration;
	el.id = 'bongkar' + iteration;
	el.size = 4;
	el.onkeypress = isNumberKey;
	cell.appendChild(el);
	var br = document.createElement("br");
	cell.appendChild(br);
	cell.appendChild(txtNode);
	
	cell = row.insertCell(9);
	el = document.createElement('input');
	txtNode = document.createTextNode("Box"); 
	el.type = 'text';
	el.name = 'muat' + iteration;
	el.id = 'muat' + iteration;
	el.size = 4;
	el.onkeypress = isNumberKey;
	cell.appendChild(el);
	br = document.createElement("br");
	cell.appendChild(br);
	cell.appendChild(txtNode);
	
	cell = row.insertCell(10);
	sel = document.createElement('select');
	sel.name = 'keg' + iteration;
	sel.id = 'keg' + iteration;
	sel.onchange = function() {cek_kegiatan(this)};
	for(var i=1; i<=$('#jum_keg').val(); i++) {
		id_keg = $('#id_keg'+i).val();
		ket_keg = $('#ket_keg'+i).val();
		sel.options[i-1] = new Option(ket_keg, id_keg);
	}
	cell.appendChild(sel);
	var sel2 = document.createElement('select');
	sel2.name = 'subkeg' + iteration;
	sel2.id = 'subkeg' + iteration;
	for(i=1; i<=$('#jum_bm').val(); i++) {
		id_subkeg = $('#id_bm'+i).val();
		ket_subkeg = $('#ket_bm'+i).val();
		sel2.options[i-1] = new Option(ket_subkeg, id_subkeg);
	}
	br = document.createElement("br");
	cell.appendChild(br);
	cell.appendChild(sel2);
	
	var x = tbl.rows;
	x[iteration].vAlign="top";
	
	document.getElementById('jum_detail').value = iteration;
}

function removeRowIndex(obj,tableid)
{
	var par=obj.parentNode;
	while(par.nodeName.toLowerCase()!='tr'){
		par=par.parentNode;
	}
	var index = par.rowIndex;
	
	var tbl = document.getElementById(tableid);
	var lastRow = tbl.rows.length;
	tbl.deleteRow(index);
	while(index < tbl.rows.length) {
		indexnya = index+1;
		counter = document.getElementById('counter' + indexnya);
		counter.id="counter"+index;
		counter.name="counter"+index;
		counter.value=counter.value-1;
		perdagangan = document.getElementById('perdagangan' + indexnya);
		perdagangan.id="perdagangan"+index;
		perdagangan.name="perdagangan"+index;
		size = document.getElementById('size' + indexnya);
		size.id="size"+index;
		size.name="size"+index;
		type = document.getElementById('type' + indexnya);
		type.id="type"+index;
		type.name="type"+index;
		status = document.getElementById('status' + indexnya);
		status.id="status"+index;
		status.name="status"+index;
		height = document.getElementById('height' + indexnya);
		height.id="height"+index;
		height.name="height"+index;
		bahaya = document.getElementById('bahaya' + indexnya);
		bahaya.id="bahaya"+index;
		bahaya.name="bahaya"+index;
		bongkar = document.getElementById('bongkar' + indexnya);
		bongkar.id="bongkar"+index;
		bongkar.name="bongkar"+index;
		muat = document.getElementById('muat' + indexnya);
		muat.id="muat"+index;
		muat.name="muat"+index;
		keg = document.getElementById('keg' + indexnya);
		keg.id="keg"+index;
		keg.name="keg"+index;
		subkeg = document.getElementById('subkeg' + indexnya);
		subkeg.id="subkeg"+index;
		subkeg.name="subkeg"+index;
		index++;
	}		
	
	document.getElementById('jum_detail').value = (tbl.rows.length-1);
}
	
function removeAllRow(tableid)
{
	var tbl = document.getElementById(tableid);
	var lastRow = tbl.rows.length;
	while (lastRow > 1)	tbl.deleteRow(--lastRow);
	document.getElementById('jum_detail').value = 0;	
}

function isNumberKey(evt)
{
	var charCode = (evt.which) ? evt.which : event.keyCode;
	
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		/*if(charCode=="46")	return true;
		else*/				return false;
	}
		
	return true;
}
</script>
<?php
$id = $_GET['id'];
$db = getDB();
$query="SELECT A.*, B.NM_KAPAL, B.VOYAGE_IN||'-'||B.VOYAGE_OUT AS VOYAGE, B.NM_PEMILIK FROM UPER_H A
		LEFT JOIN RBM_H B ON B.NO_UKK=A.NO_UKK
		WHERE NO_UPER='".$id."'";
// echo $query;die;
if($res = $db->query($query)) {
	$row = $res->fetchRow();
	if($row['LUNAS']=='P') {	//cek status terakhir, sudah payment apa belum?
		echo "<script>
				alert('Uper ini sudah dilunasi, tidak bisa di-edit!');
				ReloadPage();
			  </script>";
	}
}

//== buat dikirim ke javascript==//
$i=1;
$q_keg = "SELECT ID_KEG, KET FROM TR_KEG ORDER BY URUT";
$res = $db->query($q_keg);
while($data_keg = $res->fetchRow()) {
?>
	<input type="hidden" id="id_keg<?=$i?>" name="id_keg<?=$i?>" value="<?=$data_keg[ID_KEG]?>">
	<input type="hidden" id="ket_keg<?=$i?>" name="ket_keg<?=$i?>" value="<?=$data_keg[KET]?>">
<?php
	$i++;
}
?>
	<input type="hidden" id="jum_keg" name="jum_keg" value="<?=($i-1)?>">
	
<?php
$i=1;
$q_subkeg = "SELECT ID_SUBKEG, KET FROM TR_SUBKEG WHERE ID_KEG='BM' ORDER BY URUT";
$res = $db->query($q_subkeg);
while($data_sub1 = $res->fetchRow()) {
?>
	<input type="hidden" id="id_bm<?=$i?>" name="id_bm<?=$i?>" value="<?=$data_sub1[ID_SUBKEG]?>">
	<input type="hidden" id="ket_bm<?=$i?>" name="ket_bm<?=$i?>" value="<?=$data_sub1[KET]?>">
<?php
	$i++;
}
?>
	<input type="hidden" id="jum_bm" name="jum_bm" value="<?=($i-1)?>">

<?php
$i=1;
$q_subkeg = "SELECT ID_SUBKEG, KET FROM TR_SUBKEG WHERE ID_KEG='TRANS' ORDER BY URUT";
$res = $db->query($q_subkeg);
while($data_sub2 = $res->fetchRow()) {
?>
	<input type="hidden" id="id_trans<?=$i?>" name="id_trans<?=$i?>" value="<?=$data_sub2[ID_SUBKEG]?>">
	<input type="hidden" id="ket_trans<?=$i?>" name="ket_trans<?=$i?>" value="<?=$data_sub2[KET]?>">
<?php
	$i++;
}
?>
	<input type="hidden" id="jum_trans" name="jum_trans" value="<?=($i-1)?>">

<?php
$i=1;
$q_subkeg = "SELECT ID_SUBKEG, KET FROM TR_SUBKEG WHERE ID_KEG='SHIFT' ORDER BY URUT";
$res = $db->query($q_subkeg);
while($data_sub3 = $res->fetchRow()) {
?>
	<input type="hidden" id="id_shift<?=$i?>" name="id_shift<?=$i?>" value="<?=$data_sub3[ID_SUBKEG]?>">
	<input type="hidden" id="ket_shift<?=$i?>" name="ket_shift<?=$i?>" value="<?=$data_sub3[KET]?>">
<?php
	$i++;
}
?>
	<input type="hidden" id="jum_shift" name="jum_shift" value="<?=($i-1)?>">
	
	<br>
	<table>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Vessel / Voyage</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="vessel" id="vessel" size="30" value="<?=$row['NM_KAPAL']?>" ReadOnly /> / <input type="text" name="voyage" id="voyage" size="10" value="<?=$row['VOYAGE']?>" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">No UKK</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="no_ukk" id="no_ukk" size="15" maxlength="9" value="<?=$row['NO_UKK']?>" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Shipping Line</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="shipping" id="shipping" size="30" value="<?=$row['NM_PEMILIK']?>" ReadOnly />
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<div id="tableinput" style="width:850px; overflow-x: scroll ;padding-top:10px; margin-left:10px; margin-bottom:10px">
					<table border="0" cellpadding="2" cellspacing="2" id="tableedit" width="850px">
						<tr height="20" bgcolor="#0066FF" align="center"> 
							<td class="grid-header" width="20">&nbsp;</td>
							<td class="grid-header" width="20">No</td>
							<td class="grid-header">Perdagangan</td>
							<td class="grid-header">Size</td>
							<td class="grid-header">Type</td>
							<td class="grid-header">Status</td>
							<td class="grid-header">Height</td>
							<td class="grid-header">Hz</td>
							<td class="grid-header">Bongkar</td>
							<td class="grid-header">Muat</td>								
							<td class="grid-header">Kegiatan</td>
						</tr>
<?php
$sql2 = "SELECT * FROM UPER_D WHERE NO_UPER='".$id."' ORDER BY NO_URUT";
$rs2 = $db->query( $sql2 );
// echo $sql2;
$j=0;
while ($row2=$rs2->FetchRow()) {
	$j++;
	unset($pSize1,$pSize2,$pSize3);
	if($row2['SIZE_']==20)	$pSize1="selected";
	else if($row2['SIZE_']==40)	$pSize2="selected";
	else	$pSize3="selected";
	
	unset($pType1,$pType2,$pType3,$pType4,$pType5,$pType6,$pType7);
	if($row2['TYPE_']=='DRY')	$pType1="selected";
	else if($row2['TYPE_']=='RFR')	$pType2="selected";
	else if($row2['TYPE_']=='FLT')	$pType3="selected";
	else if($row2['TYPE_']=='HQ')	$pType4="selected";
	else if($row2['TYPE_']=='OT')	$pType5="selected";
	else if($row2['TYPE_']=='TNK')	$pType6="selected";
	else	$pType7="selected";
	
	unset($pStatus1,$pStatus2,$pStatus3);
	if($row2['STATUS']=='FCL')	$pStatus1="selected";
	else if($row2['STATUS']=='LCL')	$pStatus2="selected";
	else	$pStatus3="selected";

	unset($pHeight1,$pHeight2);
	if($row2['HEIGHT_CONT']=='BIASA')	$pHeight1="selected";		
	else	$pHeight2="selected";
	
	unset($pBahaya1,$pBahaya2);
	if($row2['HZ']=='T')	$pBahaya1="selected";		
	else	$pBahaya2="selected";
	
	unset($pPerdagangan1,$pPerdagangan2);
	if($row2['FLAG_OI']=='O')	$pPerdagangan1="selected";		
	else	$pPerdagangan2="selected";
	$perdagangan = $row2['FLAG_OI'];
?>
						<tr height="20"> 
							<td valign="top"><input type="button" value=" - " onclick="removeRowIndex(this,'tableedit');" title="hapus" /></td>
							<td valign="top"><input type="text" id="counter<?=$j?>" name="counter<?=$j?>" size="2" value="<?=$j?>" readonly /></td>
							<td valign="top">
								<select disabled>
									<option name="perdagangan<?=$j?>" id="perdagangan<?=$j?>" value="O" <?=$pPerdagangan1?>>Ocean Going</option>
									<option value="I" <?=$pPerdagangan2?>>Intersuler</option>						
								</select>
							</td>
							<td valign="top">
								<select name="size<?=$j?>" id="size<?=$j?>">
									<option value="20" <?=$pSize1?>>20</option>
									<option value="40" <?=$pSize2?>>40</option>
									<option value="45" <?=$pSize3?>>45</option>
								</select>
							</td>
							<td valign="top">
								<select name="type<?=$j?>" id="type<?=$j?>">
									<option value="DRY" <?=$pType1?>>DRY</option>
									<option value="RFR" <?=$pType2?>>REFEER</option>
									<option value="FLT" <?=$pType3?>>FLAT TRACK</option>
									<option value="HQ" <?=$pType4?>>HIGH CUBE</option>
									<option value="OT" <?=$pType5?>>OPEN TOP</option>
									<option value="TNK" <?=$pType6?>>TANKER</option>
									<option value="OVD" <?=$pType7?>>OVD</option>
								</select>
							</td>
							<td valign="top">
								<select name="status<?=$j?>" id="status<?=$j?>">
									<option value="FCL" <?=$pStatus1?>>FCL</option>
									<option value="LCL" <?=$pStatus2?>>LCL</option>
									<option value="MTY" <?=$pStatus3?>>MTY</option>						
								</select>
							</td>
							<td valign="top">
								<select name="height<?=$j?>" id="height<?=$j?>">
									<option value="BIASA" <?=$pHeight1?>>8.6 s/d 9.6</option>
									<option value="OOG" <?=$pHeight2?>>OOG</option>	
								</select>
							</td>
							<td valign="top">
								<select name="bahaya<?=$j?>" id="bahaya<?=$j?>">
									<option value="T" <?=$pBahaya1?>>Tidak</option>
									<option value="Y" <?=$pBahaya2?>>Ya</option>						
								</select>
							</td>
							<td valign="top">
								<input type="text" id="bongkar<?=$j?>" name="bongkar<?=$j?>" value="<?=$row2["BONGKAR"]?>" size="4" onkeypress="return isNumberKey(event)" /> Box
							</td>
							<td valign="top">
								<input type="text" id="muat<?=$j?>" name="muat<?=$j?>" value="<?=$row2["MUAT"]?>" size="4" onkeypress="return isNumberKey(event)" /> Box
							</td>
							<td valign="top">
								<select name="keg<?=$j?>" id="keg<?=$j?>" onChange="cek_kegiatan(this)">
<?php
	$sql_keg = "SELECT * FROM TR_KEG ORDER BY URUT";
	$rs_keg = $db->query( $sql_keg );
	while ($row_keg=$rs_keg->FetchRow()) {
		if($row_keg['ID_KEG']==$row2['KEGIATAN']) {
?>
									<option value="<?=$row_keg['ID_KEG']?>" selected><?=$row_keg['KET']?></option>
<?php
		}
		else {
?>
									<option value="<?=$row_keg['ID_KEG']?>"><?=$row_keg['KET']?></option>
<?php
		}
	}
?>
								</select>
								<br />
								<select name="subkeg<?=$j?>" id="subkeg<?=$j?>">
<?php
	$sql_subkeg = "SELECT * FROM TR_SUBKEG WHERE ID_KEG='".$row2['KEGIATAN']."' ORDER BY URUT";
	$rs_subkeg = $db->query( $sql_subkeg );
	while ($row_subkeg=$rs_subkeg->FetchRow()) {
		if($row_subkeg['ID_SUBKEG']==$row2['SUBKEG']) {
?>
									<option value="<?=$row_subkeg['ID_SUBKEG']?>" selected><?=$row_subkeg['KET']?></option>
<?php
		}
		else {
?>
									<option value="<?=$row_subkeg['ID_SUBKEG']?>"><?=$row_subkeg['KET']?></option>
<?php
		}
	}
?>
								</select>
							</td>
						</tr>
<?php
}
?>
					</table>
					<input type="hidden" name="perdagangan" id="perdagangan" value="<?=$perdagangan?>" />
					<input type="hidden" name="jum_detail" id="jum_detail" value="<?=$j?>" />
				</div>
				<div style="margin-left:10px;">
					<!--<a onClick="addRowToTable('tableedit');"><img src="images/tambah.png" /></a>
					<input type="button" value="Tambah Detail" onClick="addRowToTable('tableedit');" class="coffemix" />-->
					<a class="link-button" style="height:25" onclick="addRowToTable('tableedit','<?=$perdagangan?>')">
						<img border="0" src="images/tambah.png" />Tambah Detail
					</a>&nbsp; &nbsp;
					<!--<a onClick="removeAllRow('tableedit');"><img src="images/batal.png" /></a>
					<input type="button" value="Hapus Semua" onClick="removeAllRow('tableedit');" class="coffemix" />-->
					<a class="link-button" style="height:25" onclick="removeAllRow('tableedit')">
						<img border="0" src="images/batal.png" />Hapus Semua
					</a>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" align="center">
				<input type="button" id="btnClose" value="&nbsp;Close&nbsp;"/>
				<input type="button" name="Edit User" value="&nbsp;Edit&nbsp;" onclick="valid_edit('tableedit','<?=$id?>')"/>
			</td>
		</tr>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
	</table>