<script type="text/javascript">
var tipe_req;
var glob_vessel="";
var glob_emkl="";

var v_idplp = "";
var v_noukk = "";
var v_vessel = "";
var v_voy_in = "";
var v_voy_out = "";
var v_tpl1 = "";
var v_tpl2 = "";
var v_nobc = "";
var v_bcdate = "";
var v_ata = "";
var v_atd = "";

$("#emkl").val("MULTI TERMINAL INDONESIA PT.");
glob_emkl = "MULTI TERMINAL INDONESIA PT.";
$("#alamat").val("JL.PULAU PAYUNG 1 PELABUHAN TANJUNG PRIOK,JAKARTA UTARA");
$("#npwp").val("02.106.620.4-093.000");
$("#coa").val("106659");

$(document).ready(function(){
	$("#btnClose").click(function(){
		
		$('#add_reqspjm').dialog('destroy').remove();
		$('#mainform').append('<div id="add_reqspjm"></div>');
	});
	
	$("#tgl_spjm").datepicker({
		dateFormat: 'dd-mm-yy'
	});
	
	$("#vessel").autocomplete({		
					minLength: 3,
					source: "request.delivery.sp2spjm.auto/vessel",
					focus: function( event, ui ) 
					{
						$("vessel").val( ui.item.NM_KAPAL);
						return false;
					},
					select: function( event, ui ) 
					{					
						$("#vessel").val(ui.item.NM_KAPAL);
						$("#voyage").val(ui.item.VOYAGE_IN);
						$("#voyage_out").val(ui.item.VOYAGE_OUT);
						
						//v_idplp = ui.item.CAR;
						v_vessel = ui.item.NM_KAPAL;
						v_voy_in = ui.item.VOYAGE_IN;
						v_voy_out = ui.item.VOYAGE_OUT;
						v_noukk = ui.item.NO_UKK;
						//v_tpl1 = ui.item.TPL1;
						//v_tpl2 = ui.item.TPL2;
						//v_nobc = ui.item.BC_NUMB;
						//v_bcdate = ui.item.BC_DATE;
						//v_ata = ui.item.ATA;
						//v_atd = ui.item.ATD;
						
						return false;
					}
				})
					.data( "autocomplete" )._renderItem = function( ul, item ) 
					{
						return $( "<li></li>" )
						.data("item.autocomplete", item )
						.append("<a>" + item.NM_KAPAL + "<br/>" + item.VOYAGE_IN + " " + item.VOYAGE_OUT + "</a>")
						.appendTo(ul);
					};
});

function addrowToTable(tableid)
{
	if($("#vessel").val()=="") {
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
		
		/*cell = row.insertCell(4);
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
		cell.appendChild(el);*/
		
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
		source: "request.delivery.sp2spjm.auto/container_manual?no_ukk="+v_noukk+"",
		focus: function( event, ui ) 
		{
			$( "#no_cont"+index ).val( ui.item.NO_CONTAINER);
			return false;
		},
		select: function( event, ui ) 
		{
			$("#no_cont"+index ).val(ui.item.NO_CONTAINER);
			$("#jns_cont"+index ).val(ui.item.JENIS);
			//$("#id_brg"+index).val(ui.item.KD_BARANG);
			//$("#hz"+index).val(ui.item.HZ);
			//$("#ukk"+index).val(ui.item.NO_UKK);
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
		//id_brg = document.getElementById('id_brg' + indexnya);
		//id_brg.id="id_brg"+index;
		//id_brg.name="id_brg"+index;
		//hz = document.getElementById('hz' + indexnya);
		//hz.id="hz"+index;
		//hz.name="hz"+index;
		//ukk = document.getElementById('ukk' + indexnya);
		//ukk.id="ukk"+index;
		//ukk.name="ukk"+index;
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

function valid_entry(tableid)
{
	//alert('test');
	var cek = true;
	var tbl = document.getElementById(tableid);
	var lastRow = tbl.rows.length - 1;
	var i=0;
	
	if($('#emkl').val()=="" || $('#npwp').val()=="") {
		alert("Pelanggan harap diisi!");
		cek = false;
	}
	else if(lastRow > 0) {
		for (i=1; i<=lastRow; i++) {		  
		  if ($('#no_cont'+i).val()=="") {
			alert('Data Container ke-' + i + ' kosong / tidak valid, harap periksa kembali!');
			cek = false;
			break;
		  }
		}			
	}
	else if($("#no_ukk").val()=='')
	{
		alert('no_ukk kosong');
	}
	else {
		alert('Data Container kosong!');
		cek = false;
	}
	if(cek) {		
		question = confirm("data akan disimpan, cek apakah data sudah benar?")
		if (question != "0")	spjm_entry();
	}
}

function spjm_entry()
{
	//alert('coba');
	var url = "<?=HOME?>request.delivery.sp2spjm.ajax/add_spjm_manual";
	var jum_detail = $("#jum_detail").val();
	var ukk = $("#no_ukk").val();
	var counter = "";
	var no_cont = "";
	var id_brg = "";
	var hz = "";
	var jns_cont = "";
    var size_ = "";
	var type_ ="";
    var status = ""; 	
	for (var i=1; i<=jum_detail; i++) {
		if(counter=="")	counter = $("#counter"+i).val();
		else			counter = counter + "&" + $("#counter"+i).val();
		if(no_cont=="")	no_cont = $("#no_cont"+i).val();
		else			no_cont = no_cont + "&" + $("#no_cont"+i).val();
		if(jns_cont=="")		jns_cont = $("#jns_cont"+i).val();
		else			jns_cont = jns_cont + "&" + $("#jns_cont"+i).val();
		if(size_=="")	size_ = $("#size_"+i).val();
		else			size_ = size_ + "&" + $("#size_"+i).val();
		if(type_=="")		type_ = $("#type_"+i).val();
		else			type_ = type_ + "&" + $("#type_"+i).val();
		if(status=="")		status = $("#status"+i).val();
		else			status = status + "&" + $("#status"+i).val();
	}

	$.blockUI({ message: '<h1><br>Please wait...</h1><br><img src=<?=HOME;?>images/103.gif width="30" height="30" /><br><br>' });
	$.post(url,{ID_PLP: $("#plp_no").val(), TGL_SPJM : $("#tgl_spjm").val(), CUSTOM_NUMBER : $("#custom_number").val(), CUST_NAME : $("#emkl").val(), CUST_NO : $("#coa").val(),CUST_ADDR : $("#alamat").val(), CUST_TAX : $("#npwp").val(), KET : $("#ket").val(), JUM_DETAIL : jum_detail, COUNTER : counter, LIST_CONT : no_cont, JNS_CONT : jns_cont, NO_UKK : v_noukk, VESSEL:$("#vessel").val(), VOYAGE:$("#voyage").val(), VOYAGE_OUT:$("#voyage_out").val()},function(data){
		//alert(data);
		//console.log(data);
		if(data == "OK")
		{
			$.unblockUI({
			onUnblock: function(){ }
			});
			alert("Success...");
			window.location = "<?=HOME?>request.delivery.sp2spjm/";
		}
		else
		{
			$.unblockUI({
			onUnblock: function(){ }
			});
			alert(data);
			alert("Failed...");
			window.location = "<?=HOME?>request.delivery.sp2spjm/";
		}
	});	
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
			<td class="form-field-caption" align="right">NO SPJM</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="plp_no" id="plp_no" size="15" />&nbsp;&nbsp;
			</td>			
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Tgl SPJM</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="tgl_spjm" id="tgl_spjm" size="10" />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">VESSEL / VOY</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="vessel" id="vessel" size="30" placeholder="Autocomplete"/> / <input type="text" name="voyage" id="voyage" size="5" ReadOnly /><input type="text" name="voyage_out" id="voyage_out" size="5" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Nama Pelanggan</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="emkl" id="emkl" size="30" placeholder="Autocomplete"/> )* MULTI TERMINAL INDONESIA PT.
				<input type="hidden" name="coa" id="coa" />
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
		<!--<tr>
			<td class="form-field-caption" align="right">Tgl Delivery</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="tgl_del" id="tgl_del" size="10" />
			</td>
		</tr>-->
		<tr>
			<td class="form-field-caption" align="right" valign="top">KETERANGAN</td>
			<td class="form-field-caption" align="right" valign="top"> : </td>
			<td class="form-field-caption" align="left" valign="top">
				<textarea id="ket" cols="25" rows="2" name="ket" /></textarea>
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
							<!--<td class="grid-header">ID BARANG</td>
							<td class="grid-header">HZ</td>
							<td class="grid-header">NO UKK</td>-->
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
				<input type="button" name="Insert Request SP2 TPFT Manual" value="&nbsp;Insert&nbsp;" onclick="valid_entry('tableedit')"/>
			</td>
		</tr>
	</table>		