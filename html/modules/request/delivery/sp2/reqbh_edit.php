<script type="text/javascript">
var e_i;
$(document).ready(function(){
	$("#btnClose").click(function(){
		$('#reqbh_insert').dialog('destroy').remove();
		$('#mainform').append('<div id="add_reqbh"></div>');
	});
});

function cek()
{
	var e = document.getElementById("ei");

	e_i = e.options[e.selectedIndex].value;
	 //alert(e_i); 
	 $("#no_ex_cont").autocomplete({
		minLength: 4,
		source: "maintenance.rename_container.auto/container?tipe="+e_i+"",
		focus: function( event, ui ) 
		{
			$( "#no_ex_cont" ).val( ui.item.NO_CONTAINER);
			return false;
		},
		select: function( event, ui ) 
		{
		
			
			$("#no_ex_cont" ).val(ui.item.NO_CONTAINER);
			$("#ukk").val(ui.item.NO_UKK);
			$("#size_").val(ui.item.SIZE_);
			$("#type_").val(ui.item.TYPE_);
			$("#status").val(ui.item.STATUS);
			$("#no_ukk").val(ui.item.NO_UKK);
			$("#vessel").val(ui.item.NM_KAPAL);
			$("#voyage_in").val(ui.item.VOYAGE_IN);
			$("#voyage_out").val(ui.item.VOYAGE_OUT);
			$("#emkl").val(ui.item.NM_PEMILIK);
			$("#aemkl").val(ui.item.ALAMAT);
			$("#anpwp").val(ui.item.NO_NPWP);
			$("#iemkl").val(ui.item.COA);
			
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a><b>" + item.NO_CONTAINER + "</b> || " + item.NO_UKK + "</a>")
		.appendTo( ul );
	};
}
</script>	

	<table>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Jenis Kegiatan</td>
			<td class="form-field-caption" align="right"> : </td>
			<td>
				<?php if ($hr['DEV_VIA']=='TONGKANG'){?>
				<select name="dev_via" id="dev_via" value="<?=$hr['DEV_VIA'];?>">
				<option value="TONGKANG">Tongkang</option>
				<option value="TRUCK">Truck</option>
				</select><?php } else {?>
				<select name="dev_via" id="dev_via" value="<?=$hr['DEV_VIA'];?>">
				<option value="TRUCK">Truck</option>
				<option value="TONGKANG">Tongkang</option>
				</select> <?php } ?> </td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">No Ex Container</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="no_ex_cont" id="no_ex_cont" size="20" placeholder="Autocomplete"/>
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">No Container</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="no_cont" id="no_cont" size="20" />
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
					<option value="">Pilih</option>
					<option value="Y"> Ya </option>
					<option value="N"> Tidak </option>
				</select>
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