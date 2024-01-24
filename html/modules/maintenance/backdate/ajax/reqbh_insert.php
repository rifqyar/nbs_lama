<script type="text/javascript">
var e_i;
$(document).ready(function(){
	$("#btnClose").click(function(){
		$('#add_reqbh').dialog('destroy').remove();
		$('#mainform').append('<div id="add_reqbh"></div>');
	});
});

function cek()
{
	var jenis = document.getElementById("jenis");

	jenis = jenis.options[jenis.selectedIndex].value;
	 //alert(e_i); 
	 $("#nota").autocomplete({
		minLength: 4,
		source: "maintenance.backdate.auto/nota?tipe="+jenis+"",
		focus: function( event, ui ) 
		{
			$( "#nota" ).val( ui.item.NO_CONTAINER);
			return false;
		},
		select: function( event, ui ) 
		{
			//SELECT NO_NOTA ID_NOTA, ID_BATALMUAT ID_REQ,VESSEL, VOYAGE VOYAGE_IN, VOYAGE VOYAGE_OUT, NO_UKK,EMKL, KODE_PBM COA, ALAMAT, NPWP, BAYAR TOTAL
			
			$("#nota" ).val(ui.item.ID_NOTA);
			$("#id_req" ).val(ui.item.ID_REQ);
			$("#no_ukk").val(ui.item.NO_UKK);
			$("#vessel").val(ui.item.VESSEL);
			$("#voyage_in").val(ui.item.VOYAGE_IN);
			$("#voyage_out").val(ui.item.VOYAGE_OUT);
			$("#emkl").val(ui.item.EMKL);
			$("#aemkl").val(ui.item.ALAMAT);
			$("#anpwp").val(ui.item.NPWP);
			$("#iemkl").val(ui.item.COA);
			$("#total").val(ui.item.TOTAL);
			
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a><b>" + item.ID_NOTA + "</b> || " + item.ID_REQ + "</a>")
		.appendTo( ul );
	};
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
			<td class="form-field-caption" align="right">Jenis Kegiatan</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<select name="jenis" id="jenis" onchange="cek()">
					<option value="">Pilih</option>
					<option value="ANNE"> Anne </option>
					<option value="SP2"> Delivery </option>
					<option value="SP2"> Perpanjangan Delivery</option>
					<option value="SP2PEN"> Delivery Penumpukan</option>
					<option value="BM"> Batalmuat Before Gate In </option>
					<option value="BM"> Batalmuat After Gate In </option>
					<option value="BM"> Batalmuat Delivery </option>
					<option value="BMPEN"> Batalmuat Penumpukan </option>
					<option value="REEX"> Re-ekspor</option>
					<option value="TR"> Transhipment</option>
					<option value="EXMO"> Extramovement</option>
					<option value="BH"> Behandle</option>
					<option value="RBM"> Bongkar Muat</option>
					<option value="MON"> Monitoring Reefer</option>
					<option value="SEE"> Stacking Extention Export</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">No Nota</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="nota" id="nota" style="font-size:20px" size="20" placeholder="Autocomplete"/>
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">No Request</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="id_req" id="id_req" size="20" readonly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Vessel / Voyage in - out</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="vessel" id="vessel" size="30" readonly /> / <input type="text" name="voyage_in" id="voyage_in" size="10" ReadOnly /> - <input type="text" name="voyage_out" id="voyage_out" size="10" ReadOnly />
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
			<td class="form-field-caption" align="right">Nama Pelanggan</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="emkl" id="emkl" size="30" readonly />
				<input type="hidden" name="iemkl" id="iemkl" />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Alamat</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="aemkl" id="aemkl" size="50" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">NPWP</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="anpwp" id="anpwp" size="30" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Total</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="total" id="total" size="30" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right" valign="top">No BA</td>
			<td class="form-field-caption" align="right" valign="top"> : </td>
			<td class="form-field-caption" align="left" valign="top">
				<input type="text" name="ba" id="ba" size="30" />
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
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" align="center">
				<input type="button" id="btnClose" value="&nbsp;Close&nbsp;"/>&nbsp;
				<input type="button" name="Insert Cancel Invoice" value="&nbsp;Koreksi&nbsp;" onclick="reqbh_entry()"/>
			</td>
		</tr>
	</table>		