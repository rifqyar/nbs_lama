<script type="text/javascript">
var e_i;
$(document).ready(function(){
	$("#btnClose").click(function(){
		$('#add_reqbh').dialog('destroy').remove();
		$('#mainform').append('<div id="add_reqbh"></div>');
	});
	
	$("#custname").autocomplete({
		minLength: 4,
		source: "request.rename_container.auto/pelanggan",
		focus: function( event, ui ) 
		{
			$( "#custname" ).val( ui.item.NAMA_PERUSAHAAN);
			return false;
		},
		select: function( event, ui ) 
		{
			$("#custname" ).val(ui.item.NAMA_PERUSAHAAN);
			$("#custid").val(ui.item.KD_PELANGGAN);
			$("#custadd").val(ui.item.ALAMAT_PERUSAHAAN);
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

	e_i = e.options[e.selectedIndex].value;
	 //alert(e_i); 
	 $("#no_ex_cont").autocomplete({
		minLength: 4,
		source: "request.rename_container.auto/container?tipe="+e_i+"",
		focus: function( event, ui ) 
		{
			$( "#no_ex_cont" ).val( ui.item.OLD_CONT);
			return false;
		},
		select: function( event, ui ) 
		{
			$("#no_cont" ).val(ui.item.NEW_CONTNO);
			$("#no_ex_cont" ).val(ui.item.OLD_CONT);
			$("#ukk").val(ui.item.NO_UKK);
			$("#size_").val(ui.item.SIZE_CONT);
			$("#type_").val(ui.item.TYPE_CONT);
			$("#status").val(ui.item.STATUS);
			$("#no_ukk").val(ui.item.NO_UKK);
			$("#vessel").val(ui.item.NM_KAPAL);
			$("#voyage_in").val(ui.item.VOYAGE_IN);
			$("#voyage_out").val(ui.item.VOYAGE_OUT);
			$("#carrier").val(ui.item.CARRIER);
			$("#pod").val(ui.item.POD);
			$("#fpod").val(ui.item.FPOD);
			$("#pol").val(ui.item.POL);
			$("#do").val(ui.item.DO);
			$("#custno").val(ui.item.CUSTNO);
			$("#booksl").val(ui.item.BOOKING_SL);
			$("#kd_barang").val(ui.item.KD_BARANG);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a><b>" + item.OLD_CONT + "</b> || " + item.NO_UKK + "</a>")
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
			<td class="form-field-caption" align="right">No Ex Container</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="no_ex_cont" id="no_ex_cont" size="20" placeholder="Autocomplete"/>
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">No Container (<i>New</i>)</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="no_cont" id="no_cont" size="20" readonly />
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
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="left">
				<input type="text" name="kd_barang" id="kd_barang" size="8" readonly /> 
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Vessel / Voyage in - out</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="vessel" id="vessel" size="30" readonly /> / <input type="text" name="voyage_in" id="voyage_in" size="5" ReadOnly /> - <input type="text" name="voyage_out" id="voyage_out" size="5" ReadOnly />
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
			<td class="form-field-caption" align="right">Carrier</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="carrier" id="carrier" size="10" readonly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">POD</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="pod" id="pod" size="10" readonly />
			</td>
		</tr>		
		<tr>
			<td class="form-field-caption" align="right">FPOD</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="fpod" id="fpod" size="10" readonly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">POL</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="pol" id="pol" size="10" readonly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Booking Shipping Line</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="booking_sl" id="booking_sl" size="10"  readonly/>
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Custom Number</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="custno" id="custno" size="20" readonly />
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
			<td class="form-field-caption" align="right">Dikenakan Biaya</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<select name="biaya" id="biaya">
					<option value="Y"> Ya </option>
					<option value="N"> Tidak </option>
				</select>
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
				<input type="text" name="custadd" id="custadd" size="30"  />
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
				<input type="button" id="btnClose" value="&nbsp;Close&nbsp;"/>&nbsp;
				<input type="button" name="Insert Rename Container" value="&nbsp;Insert&nbsp;" onclick="reqbh_entry()"/>
			</td>
		</tr>
	</table>		