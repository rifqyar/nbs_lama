<script type="text/javascript">
var tipe_req;
//var glob_vessel="";
var glob_emkl="";
var glob_vessel;
var glob_voyin;
var glob_voyout;
$(document).ready(function(){
	$("#btnClose").click(function(){
		$('#add_reqexmo').dialog('destroy').remove();
		$('#mainform').append('<div id="add_reqexmo"></div>');
	});
	
	$("#vessel").autocomplete({
		minLength: 3,
		source: "request.extramovement.auto/vesvoy",
		focus: function( event, ui ) 
		{
			$("#vessel").val( ui.item.NM_KAPAL);
			return false;
		},
		select: function( event, ui ) 
		{
			$("#vessel").val(ui.item.NM_KAPAL);

			$("#voyage").val(ui.item.VOYAGE);
			$("#voyage_out").val(ui.item.VOYAGE_OUT);
			$("#no_ukk").val(ui.item.NO_UKK);
			$("#shipping").val(ui.item.OPERATOR_NAME);
			glob_vessel=ui.item.NM_KAPAL;
			glob_voyin=ui.item.VOYAGE;
			glob_voyout=ui.item.VOYAGE_OUT;
			
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data("item.autocomplete", item )
		.append("<a><b>" + item.NM_KAPAL + "</b> <br> " + item.VOYAGE + " - " + item.VOYAGE_OUT + " " + item.NO_UKK  + "</a>")
		.appendTo(ul);
	};	
	
	
	$("#emkl").autocomplete({
		minLength: 3,
		source: "request.extramovement.auto/pelanggan",
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

function sync_pelanggan(){
	var url="<?=HOME;?>request.delivery.sp2.ajax/sync_pelanggan";
	var tees='tes';
	$.post(url,{tes:tees},function(data){	
		alert('sukses');
	});
}

function addrowToTable(tableid)
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
		el.type = 'checkbox';
		el.name = 'lift_on' + iteration;
		el.id = 'lift_on' + iteration;
		cell.appendChild(el);
		
		cell = row.insertCell(9);
		el = document.createElement('input');
		el.type = 'checkbox';
		el.name = 'lift_off' + iteration;
		el.id = 'lift_off' + iteration;
		cell.appendChild(el);
		
		cell = row.insertCell(10);
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
		source: "request.extramovement.auto/container?tipe="+tipe_req+"&vessel="+glob_vessel+"&voyin="+glob_voyin+"&voyout="+glob_voyout,
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


</script>

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
				<input type="text" name="no_req" id="no_req" size="16" value="AUTO" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Jenis Perdagangan</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<select name="tipe_req" id="tipe_req" onchange="cek()">
					<option value="">-Pilih-</option>
					<option value="O"> Ocean Going </option>
					<option value="I"> Intersuler </option>
				</select>
			</td>
		</tr>		
		<tr>
			<td class="form-field-caption" align="right">Vessel / Voyage</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="vessel" id="vessel" size="30" placeholder="Autocomplete"/> / <input type="text" name="voyage" id="voyage" size="5" ReadOnly /> <input type="text" name="voyage_out" id="voyage_out" size="5" ReadOnly />
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
			<td class="form-field-caption" align="right">Nama Pelanggan</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="emkl" id="emkl" size="30" placeholder="Autocomplete"/>
				<input type="hidden" name="coa" id="coa" />
				<button onclick="sync_pelanggan()" title="sync data pelanggan"><img src="<?=HOME;?>images/sync.png" width="10" height="10"/></button>
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Alamat</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="alamat" id="alamat" size="50" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">NPWP</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="npwp" id="npwp" size="30" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">No. Instruksi</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="no_instruksi" id="no_instruksi" size="30" />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right" valign="top">Keterangan</td>
			<td class="form-field-caption" align="right" valign="top"> : </td>
			<td class="form-field-caption" align="left" valign="top">
				<textarea id="ket" cols="25" rows="2" name="ket" /></textarea>
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
							
							<td class="grid-header">LIFT<br/>ON</td>
							<td class="grid-header">LIFT<br/>OFF</td>
							<td class="grid-header">EXTRA<br/>MOVE</td>
						</tr>
					</table>
					<input type="hidden" name="jum_detail" id="jum_detail" />
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
				<input type="button" name="Insert Request Extra Movement" value="&nbsp;Insert&nbsp;" onclick="validation('tableedit')"/>
			</td>
		</tr>
	</table>		