<script type="text/javascript">
$(document).ready(function(){
	$("#btnClose").click(function(){
		$('#add_uper').dialog('destroy').remove();
		$('#mainform').append('<div id="add_uper"></div>');
	});
	
	$("#vessel").autocomplete({
		minLength: 3,
		source: "request.uper_bm.auto/vesvoy",
		focus: function( event, ui ) 
		{
			$("#vessel").val( ui.item.NM_KAPAL);
			return false;
		},
		select: function( event, ui ) 
		{
			$("#vessel").val(ui.item.NM_KAPAL);
			$("#voyage").val(ui.item.VOYAGE);
			$("#no_ukk").val(ui.item.NO_UKK);
			$("#shipping").val(ui.item.NM_PEMILIK);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data("item.autocomplete", item )
		.append("<a><b>" + item.NM_KAPAL + "</b> || " + item.VOYAGE + "<br>" + item.NO_UKK + "<br>" + item.NM_PEMILIK + "</a>")
		.appendTo(ul);
	};
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

function addrowToTable(tableid)
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
	sel.options[0] = new Option('--Pilih--', '');
	sel.options[1] = new Option('Ocean Going', 'O');
	sel.options[2] = new Option('Intersuler', 'I');
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
//== buat dikirim ke javascript==//
$db = getDB();
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
				<input type="text" name="vessel" id="vessel" size="30" placeholder="Autocomplete"/> / <input type="text" name="voyage" id="voyage" size="10" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">No UKK</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="no_ukk" id="no_ukk" size="15" maxlength="9" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Shipping Line</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="shipping" id="shipping" size="30" ReadOnly />
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
					</table>
					<input type="hidden" name="jum_detail" id="jum_detail" />
				</div>
				<div style="margin-left:10px;">
					<!--<a onClick="addrowToTable('tableedit');"><img src="images/tambah.png" /></a>
					<input type="button" value="Tambah Detail" onClick="addrowToTable('tableedit');" class="coffemix" />-->
					<a class="link-button" style="height:25" onclick="addrowToTable('tableedit')">
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
				<input type="button" id="btnClose" value="&nbsp;Close&nbsp;"/>&nbsp;
				<input type="button" name="Insert Uper" value="&nbsp;Insert&nbsp;" onclick="valid_entry('tableedit')"/>
			</td>
		</tr>
	</table>		