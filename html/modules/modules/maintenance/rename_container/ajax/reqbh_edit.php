<script type="text/javascript">
var e_i;
$(document).ready(function(){
	$("#btnClose").click(function(){
		$('#edit_reqbh').dialog('destroy').remove();
		$('#mainform').append('<div id="edit_reqbh"></div>');
	});
	$("#custname").autocomplete({
		minLength: 4,
		source: "maintenance.rename_container.auto/pelanggan",
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

<?php
$id = $_GET['id'];
$db = getDB();
$query="SELECT NO_RENAME,EI, NO_EX_CONTAINER, NO_CONTAINER, SIZE_, TYPE_, STATUS_CONT, STATUS, VESSEL, VOYAGE, NO_UKK, PBM,COA, ALAMAT, NPWP, REMARKS, BIAYA FROM RENAME_CONTAINER WHERE NO_RENAME ='$id'";
//echo $query;die;
if($res = $db->query($query)) {
	$row = $res->fetchRow();
	if($row['STATUS']=='P' || $row['STATUS']=='T') {	//cek status terakhir, sudah payment apa belum?
		echo "<script>
				alert('Request ini sudah dilunasi, tidak bisa di-edit!');
				ReloadPage();
			  </script>";
	}
}
?>	
	
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
				<?php if ($row['EI']=='E'){?>
				<select name="ei" id="ei" value="<?=$row['EI'];?>">
				<option value="E">Ekspor</option>
				<option value="I">Impor</option>
				</select><?php } else {?>
				<select name="ei" id="ei" value="<?=$row['EI'];?>">
				<option value="E">Ekspor</option>
				<option value="I">Impor</option>
				</select> <?php } ?> </td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">No Ex Container</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="no_ex_cont" id="no_ex_cont" value="<?=$row['NO_EX_CONTAINER'];?>" size="20" readonly />
				<input type="hidden" name="no_rename" id="no_rename" value="<?=$row['NO_RENAME'];?>" size="20" readonly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">No Container</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="no_cont" id="no_cont" size="20" value="<?=$row['NO_CONTAINER'];?>" />
			</td>
		</tr>
		
		<tr>
			<td class="form-field-caption" align="right">Size - Type - Status</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="size_" id="size_" size="8" value="<?=$row['SIZE_'];?>" readonly /> - <input type="text" name="type_" id="type_" value="<?=$row['TYPE_'];?>" size="8" readonly /> - <input type="text" name="status" id="status" size="8" value="<?=$row['STATUS_CONT'];?>" readonly /> 
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Vessel / Voyage in - out</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="vessel" id="vessel" size="30" value="<?=$row['VESSEL'];?>" readonly /> / <input type="text" name="voyage_in" id="voyage_in" size="10" value="<?=$row['VOYAGE'];?>" ReadOnly />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">No UKK</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<input type="text" name="no_ukk" id="no_ukk" size="15" maxlength="9" value="<?=$row['NO_UKK'];?>" ReadOnly />
			</td>
		</tr>
<tr>
			<td class="form-field-caption" align="right" valign="top">Customer Name - Cust. ID</td>
			<td class="form-field-caption" align="right" valign="top"> : </td>
			<td class="form-field-caption" align="left" valign="top">
				<input type="text" name="custname" id="custname" size="20" value="<?=$row['PBM'];?>"  /> - <input type="text" name="custid" id="custid" size="10"  value="<?=$row['COA'];?>"/>
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right" valign="top">Customer Address</td>
			<td class="form-field-caption" align="right" valign="top"> : </td>
			<td class="form-field-caption" align="left" valign="top">
				<input type="text" name="custadd" id="custadd" size="30" value="<?=$row['ALAMAT'];?>" />
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right" valign="top">Customer Tax Number</td>
			<td class="form-field-caption" align="right" valign="top"> : </td>
			<td class="form-field-caption" align="left" valign="top">
				<input type="text" name="npwp" id="npwp" size="20" value="<?=$row['NPWP'];?>" />
			</td>
		</tr>

		
		<tr>
			<td class="form-field-caption" align="right" valign="top">Keterangan</td>
			<td class="form-field-caption" align="right" valign="top"> : </td>
			<td class="form-field-caption" align="left" valign="top">
				<textarea id="ket" cols="25" rows="2" name="ket"/></textarea>
			</td>
		</tr>
		<tr>
			<td class="form-field-caption" align="right">Dikenakan Biaya</td>
			<td class="form-field-caption" align="right"> : </td>
			<td class="form-field-caption" align="left">
				<?php if ($row['BIAYA']=='Y'){?>
				<select name="biaya" id="biaya" value="<?=$row['BIAYA'];?>">
				<option value="Y">Ya</option>
				<option value="N">Tidak</option>
				</select><?php } else {?>
				<select name="biaya" id="biaya" value="<?=$row['BIAYA'];?>">
				<option value="Y">Ya</option>
				<option value="N">Tidak</option>
				</select> <?php } ?> </td>
		</tr>
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" align="center">
				<input type="button" id="btnClose" value="&nbsp;Close&nbsp;"/>&nbsp;
				<input type="button" name="Edit Rename Container" value="&nbsp;Edit&nbsp;" onclick="reqbh_edit(<?=$id?>)"/>
			</td>
		</tr>
	</table>	