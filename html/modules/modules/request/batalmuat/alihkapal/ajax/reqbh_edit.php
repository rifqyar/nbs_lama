<script type="text/javascript">
var tipe_req=$("#tipe_req").val();
var glob_vessel=$("#vessel").val();
var glob_emkl=$("#emkl").val();
$(document).ready(function(){
	$("#btnClose").click(function(){
		$('#edit_reqbh').dialog('destroy').remove();
		$('#mainform').append('<div id="edit_reqbh"></div>');
	});
	
	$("#vessel").autocomplete({
		minLength: 3,
		source: "request.batalmuat.alihkapal.auto/vesvoy",
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
			$("#stack").val(ui.item.OPEN_STACK);
			$("#etd").val(ui.item.TGL_JAM_BERANGKAT);
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
			$("#stack").val('');
			$("#etd").val('');
			removeAllRow('tableedit');
		}
	});
	
	$("#emkl").autocomplete({
		minLength: 3,
		source: "request.batalmuat.alihkapal.auto/pelanggan",
		focus: function( event, ui ) 
		{
			$("#emkl").val( ui.item.NM_KAPAL);
			return false;
		},
		select: function( event, ui ) 
		{
			$("#emkl").val(ui.item.NAMA_PERUSAHAAN);
			glob_emkl = ui.item.NAMA_PERUSAHAAN;
			$("#alamat").val(ui.item.ALAMAT_PERUSAHAAN);
			$("#npwp").val(ui.item.NO_NPWP);
			$("#kode_pbm").val(ui.item.KD_PELANGGAN);
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
			$("#kode_pbm").val('');
		}
	});
});

function addrowToTable(tableid)
{
	if($("#tipe_req").val()=="") {
		alert("pilih jenis request batalmuat terlebih dahulu!!!");
	}
	else if($("#vessel").val()=="" || $("#no_ukk").val()=="") {
		alert("isi vessel terlebih dahulu / data vessel tidak valid!!!");
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
		el.name = 'jns_cont' + iteration;
		el.id = 'jns_cont' + iteration;
		el.size = 20;
		el.readOnly = true;
		cell.appendChild(el);
		
		cell = row.insertCell(4);
		el = document.createElement('input');
		el.type = 'text';
		el.name = 'id_brg' + iteration;
		el.id = 'id_brg' + iteration;
		el.size = 10;
		el.readOnly = true;
		cell.appendChild(el);
		
		cell = row.insertCell(5);
		el = document.createElement('input');
		el.type = 'text';
		el.name = 'hz' + iteration;
		el.id = 'hz' + iteration;
		el.size = 2;
		el.readOnly = true;
		cell.appendChild(el);
		
		cell = row.insertCell(6);
		el = document.createElement('input');
		el.type = 'text';
		el.name = 'ukk' + iteration;
		el.id = 'ukk' + iteration;
		el.size = 20;
		el.readOnly = true;
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
			$("#jns_cont"+index).val('');
			$("#id_brg"+index).val('');
			$("#hz"+index).val('');
			$("#ukk"+index).val('');
			$("#size_"+index).val('');
			$("#type_"+index).val('');
			$("#status"+index).val('');
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
		source: "request.batalmuat.alihkapal.auto/container?tipe="+tipe_req+"",
		focus: function( event, ui ) 
		{
			$( "#no_cont"+index ).val( ui.item.NO_CONTAINER);
			return false;
		},
		select: function( event, ui ) 
		{
			$("#no_cont"+index ).val(ui.item.NO_CONTAINER);
			$("#jns_cont"+index ).val(ui.item.JENIS);
			$("#id_brg"+index).val(ui.item.KD_BARANG);
			$("#hz"+index).val(ui.item.HZ);
			$("#ukk"+index).val(ui.item.NO_UKK);
			$("#size_"+index).val(ui.item.SIZE_);
			$("#type_"+index).val(ui.item.TYPE_);
			$("#status"+index).val(ui.item.STATUS);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a><b>" + item.NO_CONTAINER + "</b> || " + item.JENIS + "</a>")
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
		jns_cont = document.getElementById('jns_cont' + indexnya);
		jns_cont.id="jns_cont"+index;
		jns_cont.name="jns_cont"+index;
		id_brg = document.getElementById('id_brg' + indexnya);
		id_brg.id="id_brg"+index;
		id_brg.name="id_brg"+index;
		hz = document.getElementById('hz' + indexnya);
		hz.id="hz"+index;
		hz.name="hz"+index;
		ukk = document.getElementById('ukk' + indexnya);
		ukk.id="ukk"+index;
		ukk.name="ukk"+index;
		size_ = document.getElementById('size_' + indexnya);
		size_.id="size_"+index;
		size_.name="size_"+index;
		type_ = document.getElementById('type_' + indexnya);
		type_.id="type_"+index;
		type_.name="type_"+index;
		status = document.getElementById('status' + indexnya);
		status.id="status"+index;
		status.name="status"+index;
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
			if(tipe_req=='A')	$('#tipe_req').val('A')
			else if(tipe_req=='B')	$('#tipe_req').val('B')
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
$query="SELECT DISTINCT VOYAGE, VESSEL, EMKL, SHIPPING_LINE, TGL_BERANGKAT2, ALAMAT, NPWP, JENIS, A.STATUS, KODE_PBM, B.TGL_STACK, B.NO_UKK, KET, A.CUSTOM_NUMBER,NPE,PEB,BOOKING_NUMB FROM TB_BATALMUAT_H A LEFT JOIN TB_BATALMUAT_D B ON A.ID_BATALMUAT=B.ID_BATALMUAT WHERE A.ID_BATALMUAT='".$id."'"; 
// echo $query;die;
if($res = $db->query($query))
	$row = $res->fetchRow();

unset($prop1,$prop2);
if($row['JENIS']=='A')	$prop1="selected";
else					$prop2="selected";
?>	

	<br />
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
				<input type="text" name="no_req" id="no_req" size="16" value="<?=$id?>" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Jenis Request Batalmuat</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<select name="tipe_req" id="tipe_req" onchange="cek()">
					<option value="A" <?=$prop1?>> After Gate In </option>
					<option value="B" <?=$prop2?>> Before Gate In </option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Custom Number</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="custom_number" id="custom_number" value = "<?=$row['CUSTOM_NUMBER']?>" size="15" maxlength="25" />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Vessel / Voyage</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="vessel" id="vessel" size="30" placeholder="Autocomplete" value="<?=$row['VESSEL']?>" /> / <input type="text" name="voyage" id="voyage" size="10"  value="<?=$row['VOYAGE']?>"ReadOnly />
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
				<input type="text" name="shipping" id="shipping" size="30"  value="<?=$row['SHIPPING_LINE']?>"ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">ETD</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="etd" id="etd" size="30" value="<?=$row['TGL_BERANGKAT2']?>" ReadOnly />
				<input type="hidden" name="stack" id="stack" size="30" value="<?=$row['TGL_STACK']?>" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">NPE</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="npe" id="npe" size="15" maxlength="9" value="<?=$row['NPE']?>" />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">PEB</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="peb" id="peb" size="15" maxlength="9" value="<?=$row['PEB']?>" />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">No Booking</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="no_booking" id="no_booking" size="15" maxlength="9" value="<?=$row['BOOKING_NUMB']?>" />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Nama Pelanggan</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="emkl" id="emkl" size="30" value="<?=$row['EMKL']?>" placeholder="Autocomplete"/>
				<input type="hidden" name="kode_pbm" id="kode_pbm" size="50" value="<?=$row['KODE_PBM']?>" ReadOnly />
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
			<td class="form-field-caption" align="right" valign="top">Keterangan</td>
			<td class="form-field-caption" align="right" valign="top"> : </td>
			<td class="form-field-caption" align="left" valign="top">
				<textarea id="ket" cols="25" rows="2" name="ket"><?=$row['KET']?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<div id="tableinput" style="width:550px; overflow-x: scroll ;padding-top:10px; margin-left:10px; margin-bottom:10px">
					<table border="0" cellpadding="2" cellspacing="2" id="tableedit" width="550px">
						<tr height="20" bgcolor="#0066FF" align="center"> 
							<td class="grid-header" width="20">&nbsp;</td>
							<td class="grid-header" width="20">No</td>
							<td class="grid-header">NO CONTAINER</td>
							<td class="grid-header">JENIS CONTAINER</td>
							<td class="grid-header">ID BARANG</td>
							<td class="grid-header">HZ</td>
							<td class="grid-header">NO UKK</td>
						</tr>
<?php
$sql2 = "SELECT * FROM TB_BATALMUAT_D WHERE ID_BATALMUAT='".$id."'";
$rs2 = $db->query( $sql2 );
// echo $sql2;
$j=0;
while ($row2=$rs2->FetchRow()) {
	$j++;
?>
						<tr height="20"> 
							<td valign="top"><input type="button" value=" - " onclick="removeRowIndex(this,'tableedit');" title="hapus" /></td>
							<td valign="top"><input type="text" id="counter<?=$j?>" name="counter<?=$j?>" size="2" value="<?=$j?>" readonly /></td>
							<td valign="top">
								<input type="text" id="no_cont<?=$j?>" name="no_cont<?=$j?>" value="<?=$row2["NO_CONTAINER"]?>" size="15" title="Autocomplete (min 4 karakter)" onFocus="autocompletenya(<?=$j?>);" />
							</td>
							<td valign="top">
								<input type="text" id="jns_cont<?=$j?>" name="jns_cont<?=$j?>" value="<?=$row2["JNS_CONT"]?>" size="20" readonly />
							</td>
							<td valign="top">
								<input type="text" id="id_brg<?=$j?>" name="id_brg<?=$j?>" value="<?=$row2["ID_CONT"]?>" size="10" readonly />
							</td>
							<td valign="top">
								<input type="text" id="hz<?=$j?>" name="hz<?=$j?>" value="<?=$row2["HZ"]?>" size="2" readonly />
							</td>
							<td valign="top">
								<input type="text" id="ukk<?=$j?>" name="ukk<?=$j?>" value="<?=$row2["NO_UKK"]?>" size="20" readonly />
							</td>
						</tr>
<?php
}
?>
					</table>
					<input type="hidden" name="jum_detail" id="jum_detail" value="<?=$j?>" />
				</div>
				<div style="margin-left:10px;">
					<a class="link-button" style="height:25" onclick="addrowToTable('tableedit')">
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
				<input type="button" id="btnClose" value="&nbsp;Close&nbsp;"/>&nbsp;
				<input type="button" name="Edit Request Batalmuat Alih Kapal" value="&nbsp;Edit&nbsp;" onclick="valid_edit('tableedit','<?=$id?>')"/>
			</td>
		</tr>
	</table>		