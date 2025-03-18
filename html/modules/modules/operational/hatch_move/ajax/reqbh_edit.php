<script type="text/javascript">
var tipe_req=$("#tipe_req").val();
var glob_vessel=$("#vessel").val();
var glob_emkl=$("#emkl").val()
$(document).ready(function(){
	$("#btnClose").click(function(){
		$('#edit_reqbh').dialog('destroy').remove();
		$('#mainform').append('<div id="edit_reqbh"></div>');
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
		el.name = 'bay' + iteration;
		el.id = 'bay' + iteration;
		el.title='Autocomplete (min 4 karakter)';
		el.size = 4;
		cell.appendChild(el);
		
		cell = row.insertCell(2);
		el = document.createElement('input');
		el.type = 'text';
		el.name = 'alat' + iteration;
		el.id = 'alat' + iteration;
		el.title='Autocomplete (min 4 karakter)';
		el.size = 15;
		cell.appendChild(el);
		
		cell = row.insertCell(3);
		var sel = document.createElement('select');
		sel.name = 'open' + iteration;
		sel.id = 'open' + iteration;
		sel.options[0] = new Option('--Pilih--', '');
		sel.options[1] = new Option('OPEN', 'OPEN');
		sel.options[2] = new Option('CLOSE', 'CLOSE');
		cell.appendChild(sel);
		
		cell = row.insertCell(4);
		el = document.createElement('input');
		el.type = 'text';
		el.name = 'move_date' + iteration;
		el.id = 'move_date' + iteration;
		el.size = 15;
		//el.readOnly = true;
		cell.appendChild(el);
		
		cell = row.insertCell(5);
		el = document.createElement('input');
		el.type = 'text';
		el.name = 'opr' + iteration;
		el.id = 'opr' + iteration;
		el.title='Autocomplete (min 4 karakter)';
		el.size = 15;
		cell.appendChild(el);
		
		cell = row.insertCell(6);
		var sel = document.createElement('select');
		sel.name = 'oi' + iteration;
		sel.id = 'oi' + iteration;
		sel.options[0] = new Option('--Pilih--', '');
		sel.options[1] = new Option('OCEAN', 'O');
		sel.options[2] = new Option('INTER', 'I');
		cell.appendChild(sel);
		
		cell = row.insertCell(7);
		el = document.createElement('input');
		el.type = 'text';
		el.name = 'jmlh' + iteration;
		el.id = 'jmlh' + iteration;
		el.size = 5;
		cell.appendChild(el);
		
		var x = tbl.rows;
		x[iteration].vAlign="top";
		
		document.getElementById('jum_detail').value = iteration;
		autocompletenya(iteration);
	}
}

function autocompletenya(index) {
	
	$("#move_date"+index).datepicker({
			dateFormat: 'dd-mm-yy'
            });
	
	var watermark = 'Autocomplete';
	<!------------------- watermark alat ------------> 
	//init, set watermark text and class
	if ($("#alat"+index).val() == ''){
		$("#alat"+index).val(watermark).addClass('watermark');
	}
	//if blur and no value inside, set watermark text and class again.
	$("#alat"+index).blur(function(){
		if ($(this).val().length == 0){
			$(this).val(watermark).addClass('watermark');
			$("#alat"+index).val('');
		}
	});
	
	//if focus and text is watermrk, set it to empty and remove the watermark class
	$("#alat"+index).focus(function(){
		if ($(this).val() == watermark){
			$(this).val('').removeClass('watermark');
		}
	});
	<!------------------- watermark alat ------------>
	
	<!------------------- watermark operator ------------> 
	//init, set watermark text and class
	if ($("#opr"+index).val() == ''){
		$("#opr"+index).val(watermark).addClass('watermark');
	}
	//if blur and no value inside, set watermark text and class again.
	$("#opr"+index).blur(function(){
		if ($(this).val().length == 0){
			$(this).val(watermark).addClass('watermark');
			$("#opr"+index).val('');
		}
	});
	
	//if focus and text is watermrk, set it to empty and remove the watermark class
	$("#opr"+index).focus(function(){
		if ($(this).val() == watermark){
			$(this).val('').removeClass('watermark');
		}
	});
	<!------------------- watermark operator ------------>
	

	$( "#alat"+index ).autocomplete({
		minLength: 4,
		source: "operational.hatch_move.auto/alat",
		focus: function( event, ui ) 
		{
			$( "#alat"+index ).val( ui.item.NAMA_ALAT);
			return false;
		},
		select: function( event, ui ) 
		{
			$("#alat"+index ).val(ui.item.NAMA_ALAT);				
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a><b>" + item.NAMA_ALAT)
		.appendTo( ul );
	};
	
	
	$( "#opr"+index ).autocomplete({
		minLength: 4,
		source: "operational.hatch_move.auto/opr",
		focus: function( event, ui ) 
		{
			$( "#opr"+index ).val( ui.item.NAME);
			return false;
		},
		select: function( event, ui ) 
		{
			$("#opr"+index ).val(ui.item.NAME);				
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a><b>" + item.NAME)
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


</script>
<?php
$id = $_GET['id'];
$db = getDB();
$query="SELECT NO_UKK, ID_VS AS KD_KAPAL, NM_KAPAL, VOYAGE_IN, VOYAGE_OUT, NM_PEMILIK, TO_CHAR(TGL_JAM_TIBA, 'dd Mon rrrr hh24:ii:ss') TGL_JAM_TIBA, TO_CHAR(TGL_JAM_BERANGKAT, 'dd Mon rrrr hh24:ii:ss') TGL_JAM_BERANGKAT  FROM RBM_H WHERE NO_UKK ='".$id."'";
// echo $query;die;
if($res = $db->query($query))
	$row = $res->fetchRow();
?>	
	<br>
	<table>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
		<tr>
			<td class="form-field-caption" align="right">No UKK</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="no_ukk" id="no_ukk" size="16" value="<?=$id?>" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Vessel / Voyage</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="vessel" id="vessel" size="30" placeholder="Autocomplete" value="<?=$row['NM_KAPAL']?>" /> / <input type="text" name="voyage" id="voyage" size="10" value="<?=$row['VOYAGE_IN'].'/'. $row['VOYAGE_OUT']?>" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Nama Pelanggan</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="emkl" id="emkl" size="30" placeholder="Autocomplete" value="<?=$row['NM_PEMILIK']?>" />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Tanggal Tiba</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="tgl_tiba" id="tgl_tiba" size="30" value="<?=$row['TGL_JAM_TIBA']?>" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Tanggal Berangkat</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="tgl_brgkt" id="tgl_brgkt" size="30" value="<?=$row['TGL_JAM_BERANGKAT']?>" ReadOnly />
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<div id="tableinput" style="width:550px; overflow-x: scroll ;padding-top:10px; margin-left:10px; margin-bottom:10px">
					<table border="0" cellpadding="2" cellspacing="2" id="tableedit" width="550px">
						<tr height="20" bgcolor="#0066FF" align="center"> 
							<td class="grid-header" width="20">&nbsp;</td>
							<td class="grid-header" width="20">NO BAY</td>
							<td class="grid-header">ALAT YANG DIGUNAKAN</td>
							<td class="grid-header">OPEN/CLOSE</td>
							<td class="grid-header">TANGGAL MOVE</td>
							<td class="grid-header">OPERATOR</td>
							<td class="grid-header">O/I</td>
							<td class="grid-header">TOTAL UNIT</td>
						</tr>
						
<?php
$sql2 = "SELECT * FROM HATCH_D WHERE NO_UKK='".$id."'";
$rs2 = $db->query( $sql2 );
// echo $sql2;

unset($prop1,$prop2);
if($row['OC']=='OPEN')			$prop1="selected";
else							$prop2="selected";

unset($prop3,$prop4);
if($row['OI']=='O')				$prop3="selected";
else							$prop4="selected";

$j=0;
while ($row2=$rs2->FetchRow()) {
	$j++;
?>
						<tr height="20"> 
							<td valign="top"><input type="button" value=" - " onclick="removeRowIndex(this,'tableedit');" title="hapus" />
							<input type="hidden" id="counter<?=$j?>" name="counter<?=$j?>" size="2" value="<?=$j?>" readonly /></td>
							<td valign="top">
								<input type="text" id="bay<?=$j?>" name="bay<?=$j?>" value="<?=$row2["BAY"]?>" size="4"/>
							</td>
							<td valign="top">
								<input type="text" id="alat<?=$j?>" name="alat<?=$j?>" value="<?=$row2["KODE_ALAT"]?>" size="15" title="Autocomplete (min 4 karakter)" onFocus="autocompletenya(<?=$j?>);" />
							</td>
							<td valign="top">
								<?php if ($row2['OC']=='OPEN'){?>
										<select name="open" id="open<?=$j?>" value="<?=$row2['OC'];?>">
										<option value="OPEN" selected >OPEN</option>
										<option value="CLOSE">CLOSE</option>
										</select><?php } else if ($row2['OC']=='CLOSE'){?>
										<select name="open" id="open<?=$j?>" value="<?=$row2['OC'];?>">
										<option value="OPEN">OPEN</option>
										<option value="CLOSE" selected>CLOSE</option>
										</select> <?php } else {?> 
										<select name="open" id="open<?=$j?>">
										<option value="OPEN">OPEN</option>
										<option value="CLOSE">CLOSE</option>
										<? } ?>
										</td>
							<td valign="top">
								<input type="text" id="move_date<?=$j?>" name="move_date<?=$j?>" value="<?=$row2["MOVE_TIME"]?>" size="15"/>
							</td>
							<td valign="top">
								<input type="text" id="opr<?=$j?>" name="opr<?=$j?>" value="<?=$row2["OPERATOR"]?>" size="15" title="Autocomplete (min 4 karakter)" onFocus="autocompletenya(<?=$j?>);" />
							</td>
							<td valign="top">
								<?php if ($row2['OI']=='O'){?>
										<select name="oi" id="oi<?=$j?>" value="<?=$row2['OI'];?>">
										<option value="O" selected>OCEAN</option>
										<option value="I">INTER</option>
										</select><?php } else if ($row2['OI']=='I') {?>
										<select name="oi" id="oi<?=$j?>" value="<?=$row2['OI'];?>">
										<option value="O">OCEAN</option>
										<option value="I" selected>INTER</option>
										</select> <?php } else { ?> 
										<select name="oi" id="oi<?=$j?>">
										<option value="O">OCEAN</option>
										<option value="I">INTER</option>
										<? } ?>
							</td>
							<td valign="top">
								<input type="text" id="jmlh<?=$j?>" name="jmlh<?=$j?>" value="<?=$row2["TOTAL_UNIT"]?>" size="5"/>
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
				<input type="button" name="Add Hatch Move" value="&nbsp;Insert&nbsp;" onclick="validation('tableedit','<?=$id?>')"/>
			</td>
		</tr>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
	</table>