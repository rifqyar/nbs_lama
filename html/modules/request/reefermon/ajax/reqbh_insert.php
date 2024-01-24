<script type="text/javascript">
var e_i;
$(document).ready(function(){
	$("#btnClose").click(function(){
		$('#add_reqbh').dialog('destroy').remove();
		$('#mainform').append('<div id="add_reqbh"></div>');
	});
	
	$("#custname").autocomplete({
		minLength: 4,
		source: "request.reefermon.auto/pelanggan",
		focus: function( event, ui ) 
		{
			$( "#custname" ).val( ui.item.NAMA_PERUSAHAAN);
			return false;
		},
		select: function( event, ui ) 
		{
			$("#custname" ).val(ui.item.NAMA_PERUSAHAAN);
			$("#custid").val(ui.item.KD_PELANGGAN);
			$("#custaddr").val(ui.item.ALAMAT_PERUSAHAAN);
			$("#npwp").val(ui.item.NO_NPWP);	
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a><b>" + item.NAMA_PERUSAHAAN + "</b><br>" + item.ALAMAT_PERUSAHAAN + "</a>")
		.appendTo( ul );
	};
});

function cek()
{
	var e = document.getElementById("ei");
	var t_y = document.getElementById("typereq");

	e_i = e.options[e.selectedIndex].value;
	ty = t_y.options[t_y.selectedIndex].value;
	 //alert(e_i); 
	 $("#nocont").autocomplete({
		minLength: 4,
		source: "request.reefermon.auto/container?tipe="+e_i+"&reqtype="+ty+"",
		focus: function( event, ui ) 
		{
			$( "#nocont" ).val( ui.item.NO_CONTAINER);
			return false;
		},
		select: function( event, ui ) 
		{
			$("#no_cont" ).val(ui.item.NO_CONTAINER);;
			$("#ukk").val(ui.item.NO_UKK);
			$("#size_").val(ui.item.SIZE_CONT);
			$("#type_").val(ui.item.TYPE_CONT);
			$("#status").val(ui.item.STATUS);
			$("#no_ukk").val(ui.item.NO_UKK);
			$("#vessel").val(ui.item.VESSEL);
			$("#voyin").val(ui.item.VOYAGE_IN);
			$("#voyout").val(ui.item.VOYAGE_OUT);
			$("#plugin").val(ui.item.PLUG_IN);
			$("#plugout").val(ui.item.PLUG_OUT);
			$("#plugoutext").val(ui.item.PLUG_OUT_EXT);
			$("#oldreq").val(ui.item.OLD_REQ);
			$("#reefertemp").val(ui.item.REEFER_TEMP);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a><b>" + item.NO_CONTAINER + "</b> || " + item.NO_UKK + "</b> || " + item.VESSEL + "</b> - " + item.VOYAGE_IN + "</b> - " + item.VOYAGE_OUT + "</a>")
		.appendTo( ul );
	};
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

	<br />
	<table>
		<tr>
			<td class="form-field-caption" align="right">Jenis Kegiatan</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<select name="ei" id="ei" onchange="cek()">
					<option value="">Pilih</option>
					<option value="E"> Export </option>
					<option value="I"> Import </option>
				</select>
			</td>
		</tr>
        <tr>
			<td class="form-field-caption" align="right">Type Request</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<select name="typereq" id="typereq" onchange="cek()">
					<option value="NEW">New</option>
					<option value="EXT"> Extention </option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Jenis Perdagangan</td>
			<td class="form-field-caption" align="right">:</td>
					<td class="form-field-caption" align="left">
				<select id="tipe_oi" name="tipe_oi">
					<option value="">Pilih</option>
					<option value="O">Ocean Going</option>
					<option value="I">Intersuler</option>
				</select>		
			</td>
        </tr>    
		<tr>
			<td class="form-field-caption" align="right">No Container</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="nocont" id="nocont" size="20" placeholder="Autocomplete"/>
				<input type="hidden" name="oldreq" id="oldreq" size="20" placeholder="Autocomplete"/>
				<input type="hidden" name="reefertemp" id="reefertemp" size="20" placeholder="Autocomplete"/>
			</td>
		</tr>
		
		<tr>
			<td class="form-field-caption" align="right">Size - Type - Status</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="size_" id="size_" size="8" readonly /> - <input type="text" name="type_" id="type_" size="8" readonly /> - <input type="text" name="status" id="status" size="8" readonly /> 
			</td>
		</tr>
        <tr>
			<td class="form-field-caption" align="right">Vessel</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="vessel" id="vessel" size="20" readonly />
                <br/>
                <input type="text" name="voyin" id="voyin" size="20" readonly /> - <input type="text" name="voyout" id="voyout" size="20" readonly />
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
			<td class="form-field-caption" align="right">Plug In</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="plugin" id="plugin" size="17" maxlength="9" ReadOnly /> 
			</td>
		</tr>
        <tr>
			<td class="form-field-caption" align="right">Plug Out</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="plugout" id="plugout" size="17" maxlength="9" ReadOnly /> 
			</td>
		</tr>
        <tr>
			<td class="form-field-caption" align="right">Plug Out Ext</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="plugoutext" id="plugoutext" size="17" maxlength="9" ReadOnly />
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
			<td class="form-field-caption" align="right" valign="top">Customer Name - Cust. ID</td>
			<td class="form-field-caption" align="right" valign="top"> : </td>
			<td class="form-field-caption" align="left" valign="top">
				<input type="text" name="custname" id="custname" size="20"  /> - <input type="text" name="custid" id="custid" size="10"  />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right" valign="top">Customer Address</td>
			<td class="form-field-caption" align="right" valign="top"> : </td>
			<td class="form-field-caption" align="left" valign="top">
				<input type="text" name="custaddr" id="custaddr" size="30"  />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right" valign="top">Customer Tax Number</td>
			<td class="form-field-caption" align="right" valign="top"> : </td>
			<td class="form-field-caption" align="left" valign="top">
				<input type="text" name="npwp" id="npwp" size="20"  />
			</td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" align="center">
				<input type="button" id="btnClose" value="&nbsp;Close&nbsp;" class='link-button'/>&nbsp;
				<input type="button" class='link-button' name="Insert Monitoring Reefer" value="&nbsp;Insert&nbsp;" onclick="reqbh_entry()"/>
			</td>
		</tr>
	</table>		