<?php
$id = $_GET['id'];
$db = getDB();
$query="SELECT NOMOR_INSTRUKSI, to_char(PAID_THRU, 'DD-MM-YYYY') AS PAID_THRU,to_char(TGL_SPJM, 'DD-MM-YYYY') AS TGL_SPJM,TYPE_SPJM,ID_USER, VOYAGE_IN, VESSEL, EMKL, ALAMAT_EMKL, NPWP, TIPE_REQ, STATUS, SHIPPING_LINE, KET, NO_UKK, COA, VOYAGE_OUT FROM REQ_BEHANDLE_H WHERE ID_REQ='".$id."'";
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
if($row['TIPE_REQ']=='Export')	$prop1="selected";
else							$prop2="selected";
?>

<script type="text/javascript">
var tipe_req=$("#tipe_req").val();
var glob_vessel=$("#vessel").val();
var glob_emkl=$("#emkl").val();
var glob_vin=$("#voyage").val();
$(document).ready(function(){
	$("#btnClose").click(function(){
		$('#edit_reqbh').dialog('destroy').remove();
		$('#mainform').append('<div id="edit_reqbh"></div>');
	});
	$("#paid_thru").datepicker({
			dateFormat: 'dd-mm-yy'
            });
	$("#tgl_spjm").datepicker({
			dateFormat: 'dd-mm-yy'
            });
	$("#vessel").autocomplete({
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
	});
	
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
		alert("pilih jenis request behandle terlebih dahulu!!!");
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
	var ukk = $("#no_ukk").val();
	$( "#no_cont"+index ).autocomplete({
		minLength: 10,
		source: "request.behandle.auto/container?vess="+glob_vessel+"&vin="+glob_vin+"&tipe="+tipe_req,
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
			if(tipe_req=='Export')	$('#tipe_req').val('Export')
			else if(tipe_req=='Import')	$('#tipe_req').val('Import')
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
				<input type="text" name="no_req" id="no_req" size="16" value="<?=$id?>" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Jenis Request Behandle</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<select name="tipe_req" id="tipe_req" onchange="cek()">
					<option value="Export" <?=$prop1?>> Export </option>
					<option value="Import" <?=$prop2?>> Import </option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Vessel / Voyage</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="vessel" id="vessel" size="30" placeholder="Autocomplete" value="<?=$row['VESSEL']?>" /> / <input type="text" name="voyage" id="voyage" size="5" value="<?=$row['VOYAGE_IN']?>" ReadOnly /><input type="text" name="voyage_out" id="voyage_out" size="5" value="<?=$row['VOYAGE_OUT']?>" ReadOnly />
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
			<td class="form-field-caption" align="right">Paid Thru</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="paid_thru" id="paid_thru" size="15" maxlength="9" ReadOnly value="<?=$row['PAID_THRU']?>"/>
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Shipping Line</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="shipping" id="shipping" size="30" value="<?=$row['SHIPPING_LINE']?>" ReadOnly />
			</td>
		</tr>
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
				<input type="text" name="alamat" id="alamat" size="50" value="<?=$row['ALAMAT_EMKL']?>" ReadOnly />
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
			<td class="form-field-caption" align="right">No. Instruksi Pemeriksaan</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="no_bc_i" id="no_bc_i" size="30" value="<?=$row['NOMOR_INSTRUKSI']?>" />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Tanggal SPJM</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="tgl_spjm" id="tgl_spjm" ReadOnly size="30" value="<?=$row['TGL_SPJM']?>"/>
			</td>
		</tr>
                
        <tr>
			<td class="form-field-caption" align="right">Type SPJM</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
                <select id="type_spjm">
                    <option value="">pilih</option>
					<?php if($row['TYPE_SPJM']=='nhi'){?>
						<option value="nhi" selected>NHI</option>
						<option value="spjm">SPJM</option>
					<?php } else { ?>
						<option value="nhi">NHI</option>
						<option value="spjm" selected>SPJM</option>
					<?php } ?>
                </select>                                                       
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
						</tr>
<?php
$sql2 = "SELECT * FROM REQ_BEHANDLE_D WHERE ID_REQ='".$id."' ORDER BY ID";
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
								<input type="text" id="id_brg<?=$j?>" name="id_brg<?=$j?>" value="<?=$row2["ID_BARANG"]?>" size="10" readonly />
							</td>
							<td valign="top">
								<input type="text" id="hz<?=$j?>" name="hz<?=$j?>" value="<?=$row2["HZ"]?>" size="2" readonly />
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
				<input type="button" name="Edit Request Behandle" value="&nbsp;Edit&nbsp;" onclick="validation('tableedit','<?=$id?>')"/>
			</td>
		</tr>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
	</table>