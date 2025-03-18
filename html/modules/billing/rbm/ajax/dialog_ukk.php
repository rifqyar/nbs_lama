<style>
table 
{
border-collapse:collapse;
}
table, td, th
{
border:1px solid #ffffff;
}
</style>
<?//=$_GET['id_vsb_voyage'];?>
<script>
$(document).ready(function() 
{	
	//======================================= autocomplete EMKL==========================================//
	$( "#ictukk" ).autocomplete({
		minLength: 3,
		source: "billing.rbm.auto/ukksimopkapal",
		focus: function( event, ui ) 
		{
			$( "#ictukk" ).val( ui.item.NO_UKK);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#ictvessel" ).val( ui.item.NM_KAPAL);
			$( "#ictvin" ).val( ui.item.VOYAGE_IN);
			$( "#ictvot" ).val( ui.item.VOYAGE_OUT);
			$( "#ictagent" ).val( ui.item.NM_AGEN);
			$( "#icteta" ).val( ui.item.TGL_JAM_TIBA);
			$( "#ictetd" ).val( ui.item.TGL_JAM_BERANGKAT);
			$( "#ictinfoppn" ).val( ui.item.INFO_69_74);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NM_KAPAL + " "+item.VOYAGE_IN+" - "+item.VOYAGE_OUT+"<br>" +item.NO_UKK+"<br>ETA:"+item.TGL_JAM_TIBA+" ETD:"+item.TGL_JAM_BERANGKAT+"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete EMKL==========================================//
	$( "#nama_pelanggan" ).autocomplete({
		minLength: 3,
		source: "planning.ppn_list.all.auto/pelanggan",
		focus: function( event, ui ) 
		{
			$( "#nama_pelanggan" ).val( ui.item.NAMA_PERUSAHAAN);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#nama_pelanggan" ).val( ui.item.NAMA_PERUSAHAAN);
			$( "#kd_pelanggan" ).val( ui.item.KD_PELANGGAN);
			$( "#npwp" ).val( ui.item.NO_NPWP);
			$( "#alamat" ).html( ui.item.ALAMAT_PERUSAHAAN);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NAMA_PERUSAHAAN + " (" +item.KD_PELANGGAN+") <br>" 
		+ item.NO_NPWP+" <br>"
		+ item.ALAMAT_PERUSAHAAN +"</a>")
		.appendTo( ul );
	
	};								  
});

function SetUkk()
{
	var kd_pel = $("#kd_pelanggan").val();
	

	if(kd_pel == ''){
		alert('silahkan input nama pelanggan secara auto complete!');
		$('#nama_pelanggan').focus();
		return;
	}								   
	//window.open('<?=HOME;?>billing.rbm.ajax/print_pranota_rbm_new?id=<?=$_GET['id'];?>&kategori=<?=$_GET['kategori'];?>&ictukk='+$('#ictukk').val(),'_blank'); 
	window.open('<?=HOME;?>billing.rbm.ajax/print_pranota_rbm_new?id=<?=$_GET['id'];?>&kategori=<?=$_GET['kategori'];?>&ictukk='+$('#ictukk').val()+'&kd_pel='+kd_pel,'_self');
	//$('#hasil').load("<?=HOME?>billing.rbm.ajax/dhasil?id=<?=$id_vsb;?>");
	$('#dialog_ukk').dialog("close");
}
</SCRIPT>
<table id="tsd">
	<tr>
		<td>UKK (ICT SIMOP KAPAL)</td>
		<td>: <input type="text" size="25" id="ictukk"></td>
	</tr>
	<tr>
		<td>Vessel / Voyage</td>
		<td>: <input type="text" size="25" id="ictvessel">&nbsp;<input type="text" size="5" id="ictvin">&nbsp;
		<input type="text" size="5" id="ictvot"></td>
	</tr>
	<tr>
		<td>Shipping Agent</td>
		<td>: <input type="text" size="25" id="ictagent"></td>
	</tr>
	<tr>
		<td>ETA / ETD</td>
		<td>: <input type="text" size="10" id="icteta"> / <input type="text" size="10" id="ictetd"></td>
	</tr>
	<tr>
		<td>Info PPN</td>
		<td>: <input type="text" size="40" id="ictinfoppn" /></td>
	</tr>
	<!-- penambahan pp 74 -->
	<tr>
		<td>PELANGGAN</td>
		<td >: <input type="text" id='nama_pelanggan' name="nama_pelanggan" size="40" placeholder='Autocomplete' /></td>
	</tr>
	<tr>
		<td>KODE PELANGGAN </td>
		<td >:<input type="text" id='kd_pelanggan' readonly name="kd_pelanggan" size="40"/></td>
	</tr>
	<tr>
		<td>NPWP</td>
		<td >:<input type="text" id='npwp' readonly name="npwp" size="40"/></td>
	</tr>
	<tr>
		<td >ALAMAT</td>
		<td>:
			<textarea type="text" id='alamat' name="alamat" disabled size="40" style="width: 260px; height: 84px;"></textarea>
		</td>
	</tr>
	<!-- penambahan pp 74 -->					  
	<tr>
		<td>&nbsp;</td>
		<td><button id="butSave" onclick="SetUkk()">Save</button></td>
	</tr>
</table>