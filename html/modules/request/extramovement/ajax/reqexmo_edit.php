<script type="text/javascript">
var tipe_req=$("#tipe_req").val();
// var glob_vessel=$("#vessel").val();
var glob_emkl=$("#emkl").val()
$(document).ready(function(){
	$("#btnClose").click(function(){
		$('#edit_reqexmo').dialog('destroy').remove();
		$('#mainform').append('<div id="edit_reqexmo"></div>');
	});
	
	/*$("#vessel").autocomplete({
		minLength: 3,
		source: "request.behandle.auto/vesvoy",
		focus: function( event, ui ) 
		{
			$("#vessel").val( ui.item.NM_KAPAL);
			return false;
		},
		select: function( event, ui ) 
		{
			$("#vessel").val(ui.item.NM_KAPAL);
			glob_vessel = ui.item.NM_KAPAL;
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
	$("#vessel").blur(function(){
		if ($(this).val() != glob_vessel){			
			$("#voyage").val('');
			$("#no_ukk").val('');
			$("#shipping").val('');
			removeAllRow('tableedit');
		}
	});*/
	
	$("#emkl").autocomplete({
		minLength: 3,
		source: "request.behandle.auto/pelanggan",
		focus: function( event, ui ) 
		{
			$("#emkl").val( ui.item.NAMA_PERUSAHAAN);
			return false;
		},
		select: function( event, ui ) 
		{
			$("#emkl").val(ui.item.NAMA_PERUSAHAAN);
			glob_emkl = ui.item.NAMA_PERUSAHAAN;
			$("#alamat").val(ui.item.ALAMAT_PERUSAHAAN);
			$("#npwp").val(ui.item.NO_NPWP);
			$("#coa").val(ui.item.KD_PELANGGAN);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data("item.autocomplete", item )
		.append("<a><b>" + item.NAMA_PERUSAHAAN + "</b><br>" + item.ALAMAT_PERUSAHAAN + "<br>" + item.NO_NPWP + "</a>")
		.appendTo(ul);
	};
	$("#emkl").blur(function(){
		if ($(this).val() != glob_emkl){
			$("#alamat").val('');
			$("#npwp").val('');
			$("#coa").val('');
		}
	});
});

function addRowToTable(tableid)
{
	if($("#tipe_req").val()=="") {
		alert("pilih jenis perdagangan terlebih dahulu!!!");
	}	
	else {
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
		el = document.createElement('input');
		el.type = 'text';
		el.name = 'no_cont' + iteration;
		el.id = 'no_cont' + iteration;
		el.title='Autocomplete (min 4 karakter)';
		el.size = 15;
		cell.appendChild(el);
		
		cell = row.insertCell(3);
		el = document.createElement('input');
		el.type = 'text';
		el.name = 'id_brg' + iteration;
		el.id = 'id_brg' + iteration;
		el.size = 10;
		el.readOnly = true;
		cell.appendChild(el);
		
		cell = row.insertCell(4);
		el = document.createElement('input');
		el.type = 'text';
		el.name = 'size' + iteration;
		el.id = 'size' + iteration;
		el.size = 2;
		el.readOnly = true;
		cell.appendChild(el);
		
		cell = row.insertCell(5);
		el = document.createElement('input');
		el.type = 'text';
		el.name = 'type' + iteration;
		el.id = 'type' + iteration;
		el.size = 5;
		el.readOnly = true;
		cell.appendChild(el);
		
		cell = row.insertCell(6);
		el = document.createElement('input');
		el.type = 'text';
		el.name = 'stat' + iteration;
		el.id = 'stat' + iteration;
		el.size = 5;
		el.readOnly = true;
		cell.appendChild(el);
		
		cell = row.insertCell(7);
		el = document.createElement('input');
		el.type = 'text';
		el.name = 'hz' + iteration;
		el.id = 'hz' + iteration;
		el.size = 2;
		el.readOnly = true;
		cell.appendChild(el);
		
		cell = row.insertCell(8);
		el = document.createElement('input');
		el.type = 'text';
		el.name = 'vessel' + iteration;
		el.id = 'vessel' + iteration;
		el.size = 20;
		el.readOnly = true;
		var txtNode = document.createTextNode(" / ");
		var el2 = document.createElement('input');
		el2.type = 'text';
		el2.name = 'voyage' + iteration;
		el2.id = 'voyage' + iteration;
		el2.size = 5;
		el2.readOnly = true;
		var el3 = document.createElement('input');
		el3.type = 'hidden';
		el3.name = 'ukk' + iteration;
		el3.id = 'ukk' + iteration;
		cell.appendChild(el);
		cell.appendChild(txtNode);
		cell.appendChild(el2);
		cell.appendChild(el3);
		
		cell = row.insertCell(9);
		el = document.createElement('input');
		el.type = 'checkbox';
		el.name = 'lift_on' + iteration;
		el.id = 'lift_on' + iteration;
		cell.appendChild(el);
		
		cell = row.insertCell(10);
		el = document.createElement('input');
		el.type = 'checkbox';
		el.name = 'lift_off' + iteration;
		el.id = 'lift_off' + iteration;
		cell.appendChild(el);
		
		cell = row.insertCell(11);
		el = document.createElement('input');
		el.type = 'checkbox';
		el.name = 'ex_mo' + iteration;
		el.id = 'ex_mo' + iteration;
		cell.appendChild(el);
		
		var x = tbl.rows;
		x[iteration].vAlign="top";
		
		document.getElementById('jum_detail').value = iteration;
		autocompletenya(iteration);
	}
}

function autocompletenya(index) {
	var watermark = 'Autocomplete';
	<!------------------- watermark no_cont ------------> 
	//init, set watermark text and class
	if ($("#no_cont"+index).val() == ''){
		$("#no_cont"+index).val(watermark).addClass('watermark');
	}
	//if blur and no value inside, set watermark text and class again.
	$("#no_cont"+index).blur(function(){
		if ($(this).val().length == 0){
			$(this).val(watermark).addClass('watermark');
			$("#id_brg"+index).val('');
			$("#size"+index).val('');
			$("#type"+index).val('');
			$("#stat"+index).val('');
			$("#hz"+index).val('');
			$("#vessel"+index).val('');
			$("#voyage"+index).val('');
			$("#ukk"+index).val('');
		}
	});
	
	//if focus and text is watermrk, set it to empty and remove the watermark class
	$("#no_cont"+index).focus(function(){
		if ($(this).val() == watermark){
			$(this).val('').removeClass('watermark');
		}
	});
	<!------------------- watermark no_cont ------------>
	
	tipe_req = $("#tipe_req").val();
	$( "#no_cont"+index ).autocomplete({
		minLength: 4,
		source: "request.extramovement.auto/container?tipe="+tipe_req+"",
		focus: function( event, ui ) 
		{
			$( "#no_cont"+index ).val( ui.item.NO_CONTAINER);
			return false;
		},
		select: function( event, ui ) 
		{
			$("#no_cont"+index ).val(ui.item.NO_CONTAINER);
			$("#id_brg"+index ).val(ui.item.KD_BARANG);
			$("#size"+index ).val(ui.item.SIZE_);
			$("#type"+index).val(ui.item.TYPE_);
			$("#stat"+index).val(ui.item.STATUS);					
			$("#hz"+index).val(ui.item.HZ);
			$("#vessel"+index).val(ui.item.NM_KAPAL);
			$("#voyage"+index).val(ui.item.VOY);
			$("#ukk"+index).val(ui.item.NO_UKK);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a><b>" + item.NO_CONTAINER + "</b><br/>" + item.SIZE_ + "/" + item.TYPE_ + "/" + item.STATUS + "<br/>" + item.NM_KAPAL + " || " + item.VOY + " || " + item.NO_UKK + "</a>")
		.appendTo( ul );
	};
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
		no_cont = document.getElementById('no_cont' + indexnya);
		no_cont.id="no_cont"+index;
		no_cont.name="no_cont"+index;
		id_brg = document.getElementById('id_brg' + indexnya);
		id_brg.id="id_brg"+index;
		id_brg.name="id_brg"+index;
		size = document.getElementById('size' + indexnya);
		size.id="size"+index;
		size.name="size"+index;
		type = document.getElementById('type' + indexnya);
		type.id="type"+index;
		type.name="type"+index;
		stat = document.getElementById('stat' + indexnya);
		stat.id="stat"+index;
		stat.name="stat"+index;		
		hz = document.getElementById('hz' + indexnya);
		hz.id="hz"+index;
		hz.name="hz"+index;
		vessel = document.getElementById('vessel' + indexnya);
		vessel.id="vessel"+index;
		vessel.name="vessel"+index;
		voyage = document.getElementById('voyage' + indexnya);
		voyage.id="voyage"+index;
		voyage.name="voyage"+index;
		ukk = document.getElementById('ukk' + indexnya);
		ukk.id="ukk"+index;
		ukk.name="ukk"+index;
		lift_on = document.getElementById('lift_on' + indexnya);
		lift_on.id="lift_on"+index;
		lift_on.name="lift_on"+index;
		lift_off = document.getElementById('lift_off' + indexnya);
		lift_off.id="lift_off"+index;
		lift_off.name="lift_off"+index;
		ex_mo = document.getElementById('ex_mo' + indexnya);
		ex_mo.id="ex_mo"+index;
		ex_mo.name="ex_mo"+index;		
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

function cek()
{
	// alert($('#tipe_req').val());
	if($('#no_cont1').length) {
		question = confirm("Penggantian Jenis Request akan menghapus data detail container (bagian bawah)\napa anda yakin??")
		if (question != "0")	removeAllRow('tableedit');
		else {
			if(tipe_req=='O')	$('#tipe_req').val('O');
			else if(tipe_req=='I')	$('#tipe_req').val('I');
		}
	}
}

// function isNumberKey(evt)
// {
	// var charCode = (evt.which) ? evt.which : event.keyCode;
	
	// if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		// /*if(charCode=="46")	return true;
		// else*/				return false;
	// }
		
	// return true;
// }
</script>
<?php
$id = $_GET['id'];
$db = getDB();
$query="SELECT ID_REQUEST, NOMOR_INSTRUKSI, EMKL, ALAMAT, NPWP, KETERANGAN, OI, COA, STATUS FROM EXMO_REQUEST WHERE ID_REQUEST='".$id."'";
// echo $query;die;
if($res = $db->query($query)) {
	$row = $res->fetchRow();
	if($row['STATUS']=='P' || $row['STATUS']=='T') {	//cek status terakhir, sudah payment apa belum?
		echo "<script>
				alert('Request ini sudah dilunasi, tidak bisa di-edit!');
				ReloadPage();
			  </script>";
	}
}

unset($prop1,$prop2);
if($row['OI']=='O')	$prop1="selected";
else				$prop2="selected";
?>	
	<br>
	<table>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
		<tr>
			<td class="form-field-caption" align="right">No Request</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="no_req" id="no_req" size="20" value="<?=$id?>" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Jenis Perdagangan</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<select name="tipe_req" id="tipe_req" onchange="cek()">
					<option value="O" <?=$prop1?>> Ocean Going </option>
					<option value="I" <?=$prop2?>> Intersuler </option>
				</select>
			</td>
		</tr>
		<!--<tr>
			<td class="form-field-caption" align="right">Vessel / Voyage</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="vessel" id="vessel" size="30" placeholder="Autocomplete" value="<?=$row['VESSEL']?>" /> / <input type="text" name="voyage" id="voyage" size="10" value="<?=$row['VOYAGE']?>" ReadOnly />
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
				<input type="text" name="shipping" id="shipping" size="30" value="<?=$row['SHIPPING_LINE']?>" ReadOnly />
			</td>
		</tr>-->
		<tr>
			<td class="form-field-caption" align="right">Nama Pelanggan</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="emkl" id="emkl" size="30" placeholder="Autocomplete" value="<?=$row['EMKL']?>" />
				<input type="hidden" name="coa" id="coa" value="<?=$row['COA']?>" />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Alamat</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="alamat" id="alamat" size="50" value="<?=$row['ALAMAT']?>" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">NPWP</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="npwp" id="npwp" size="30" value="<?=$row['NPWP']?>" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">No. Instruksi</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="no_instruksi" id="no_instruksi" size="30" value="<?=$row['NOMOR_INSTRUKSI']?>" />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right" valign="top">Keterangan</td>
			<td class="form-field-caption" align="right" valign="top"> : </td>
			<td class="form-field-caption" align="left" valign="top">
				<textarea id="ket" cols="25" rows="2" name="ket"><?=$row['KETERANGAN']?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<div id="tableinput" style="width:850px; overflow-x: scroll ;padding-top:10px; margin-left:10px; margin-bottom:10px">
					<table border="0" cellpadding="2" cellspacing="2" id="tableedit" width="850px">
						<tr height="20" bgcolor="#0066FF" align="center"> 
							<td class="grid-header" width="20">&nbsp;</td>
							<td class="grid-header" width="20">No</td>
							<td class="grid-header">NO CONTAINER</td>
							<td class="grid-header">ID BARANG</td>
							<td class="grid-header">SIZE</td>							
							<td class="grid-header">TYPE</td>							
							<td class="grid-header">STATUS</td>							
							<td class="grid-header">HZ</td>
							<td class="grid-header">VESSEL / VOYAGE</td>
							<td class="grid-header">LIFT<br/>ON</td>
							<td class="grid-header">LIFT<br/>OFF</td>
							<td class="grid-header">EXTRA<br/>MOVE</td>
						</tr>
<?php
$sql2 = "SELECT * FROM EXMO_DETAIL_REQUEST WHERE ID_REQUEST='".$id."' ORDER BY ID";
$rs2 = $db->query( $sql2 );
// echo $sql2;
$j=0;
while ($row2=$rs2->FetchRow()) {
	unset($check1,$check2,$check3);
	if($row2["LIFT_ON"]=='Y')	$check1="checked";
	if($row2["LIFT_OFF"]=='Y')	$check2="checked";
	if($row2["EX_MO"]=='Y')	$check3="checked";
	$j++;
?>
						<tr height="20"> 
							<td valign="top"><input type="button" value=" - " onclick="removeRowIndex(this,'tableedit');" title="hapus" /></td>
							<td valign="top"><input type="text" id="counter<?=$j?>" name="counter<?=$j?>" size="2" value="<?=$j?>" readonly /></td>
							<td valign="top">
								<input type="text" id="no_cont<?=$j?>" name="no_cont<?=$j?>" value="<?=$row2["NO_CONTAINER"]?>" size="15" title="Autocomplete (min 4 karakter)" onFocus="autocompletenya(<?=$j?>);" />
							</td>
							<td valign="top">
								<input type="text" id="id_brg<?=$j?>" name="id_brg<?=$j?>" value="<?=$row2["ID_CONT"]?>" size="10" readonly />
							</td>
							<td valign="top">
								<input type="text" id="size<?=$j?>" name="size<?=$j?>" value="<?=$row2["SIZE_"]?>" size="2" readonly />
							</td>
							<td valign="top">
								<input type="text" id="type<?=$j?>" name="type<?=$j?>" value="<?=$row2["TYPE"]?>" size="5" readonly />
							</td>
							<td valign="top">
								<input type="text" id="stat<?=$j?>" name="stat<?=$j?>" value="<?=$row2["STATUS"]?>" size="5" readonly />
							</td>
							<td valign="top">
								<input type="text" id="hz<?=$j?>" name="hz<?=$j?>" value="<?=$row2["HZ"]?>" size="2" readonly />
							</td>
							<td valign="top">
								<input type="text" id="vessel<?=$j?>" name="vessel<?=$j?>" value="<?=$row2["VESSEL"]?>" size="20" readonly /> / <input type="text" id="voyage<?=$j?>" name="voyage<?=$j?>" value="<?=$row2["VOYAGE"]?>" size="5" readonly /> <input type="hidden" id="ukk<?=$j?>" name="ukk<?=$j?>" value="<?=$row2["NO_UKK"]?>" readonly />
							</td>
							<td valign="top">
								<input type="checkbox" id="lift_on<?=$j?>" name="lift_on<?=$j?>" <?=$check1?> />
							</td>
							<td valign="top">
								<input type="checkbox" id="lift_off<?=$j?>" name="lift_off<?=$j?>" <?=$check2?> />
							</td>
							<td valign="top">
								<input type="checkbox" id="ex_mo<?=$j?>" name="ex_mo<?=$j?>" <?=$check3?> />
							</td>
							
						</tr>
<?php
}
?>
					</table>					
					<input type="hidden" name="jum_detail" id="jum_detail" value="<?=$j?>" />
				</div>
				<div style="margin-left:10px;">
					<a class="link-button" style="height:25" onclick="addRowToTable('tableedit')">
						<img border="0" src="images/tambah.png" />Tambah Detail
					</a>&nbsp; &nbsp;
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
				<input type="button" name="Edit User" value="&nbsp;Edit&nbsp;" onclick="validation('tableedit','<?=$id?>')"/>
			</td>
		</tr>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
	</table>