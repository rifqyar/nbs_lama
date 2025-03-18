<?php
	$ukk=$_GET['id'];
	$q_h="SELECT NM_KAPAL, NO_UKK, VOYAGE_IN, VOYAGE_OUT, NM_PEMILIK, ALAMAT, TO_CHAR(TGL_JAM_TIBA,'DD-MM-YYYY HH24:MI') ETA,
			TO_CHAR(TGL_JAM_BERANGKAT,'DD-MM-YYYY HH24:MI') ETD,NM_PELABUHAN_ASAL POL, NM_PELABUHAN_TUJUAN POD, ID_POL, NO_NPWP, ID_POD, COA, ID_VS
			FROM RBM_H WHERE TRIM(NO_UKK)=TRIM('$ukk')";
			
	//print_r($q_h);die;
	$db=getDb();
	$select_h=$db->query($q_h);
	$rh=$select_h->fetchRow();
	$nukk=$rh['NO_UKK'];
	$nk=$rh['NM_KAPAL'];
	$vin=$rh['VOYAGE_IN'];
	$vout=$rh['VOYAGE_OUT'];
	$npm=$rh['NM_PEMILIK'];
	$apm=$rh['ALAMAT'];
	$eta=$rh['ETA'];
	$etd=$rh['ETD'];
	$pol=$rh['POL'];
	$pod=$rh['POD'];
	$ipol=$rh['ID_POL'];
	$ipod=$rh['ID_POD'];
	$npwp=$rh['NO_NPWP'];
	$coa=$rh['COA'];
	$id_vs=$rh['ID_VS'];
?>
<script>

$(document).ready(function() 
{		
	//======================================= autocomplete shipping line==========================================//		
	$( "#sl" ).autocomplete({
		minLength: 2,
		source: "planning.vessel_schedule.auto/shipping_line",
		focus: function( event, ui ) 
		{
			$( "#sl" ).val( ui.item.NAMA_PERUSAHAAN);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#sl" ).val( ui.item.NAMA_PERUSAHAAN);
			$( "#asl" ).val( ui.item.ALAMAT_PERUSAHAAN);
			$( "#isl" ).val( ui.item.KD_PELANGGAN);
			$( "#nsl" ).val( ui.item.NO_NPWP);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.NAMA_PERUSAHAAN + "<br>" + item.ALAMAT_PERUSAHAAN + "<br>"+item.KD_PELANGGAN+" - "+item.NO_NPWP+"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete shipping line==========================================//
	//======================================= autocomplete pol pod==========================================//
	$( "#pol" ).autocomplete({
		minLength: 3,
		source: "planning.vessel_schedule.auto/pelabuhan",
		focus: function( event, ui ) 
		{
			$( "#pol" ).val( ui.item.PELABUHAN);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#pol" ).val( ui.item.PELABUHAN);
			$( "#ipol" ).val( ui.item.ID_PEL);
		
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.PELABUHAN + " <br> " + item.ID_PEL + " </a>")
		.appendTo( ul );
	
	};
	
	$( "#pod" ).autocomplete({
	minLength: 3,
	source: "planning.vessel_schedule.auto/pelabuhan",
	focus: function( event, ui ) 
	{
		$( "#pod" ).val( ui.item.PELABUHAN);
		return false;
	},
	select: function( event, ui ) 
	{
		$( "#pod" ).val( ui.item.PELABUHAN);
		$( "#ipod" ).val( ui.item.ID_PEL);
	
		return false;
	}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.PELABUHAN + " <br> " + item.ID_PEL + " </a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete pol pod==========================================//
	
	
	//date time picker for eta etd
	
	$("#eta").datetimepicker({
		dateFormat: 'dd-mm-yy'
		});
		
	$("#etd").datetimepicker({
		dateFormat: 'dd-mm-yy'
		});
});

function closex2()
{
	//alert('coba');
	$('#edit_vessel').dialog('destroy').remove();
	$('#mainform').append('<div id="edit_vessel"></div>');
}

function update_vessel()
{
	var url="<?=HOME;?>planning.vessel_schedule.ajax/edit_vs_sc";
	var id_ukk='<?=$nukk;?>';
	var sl=$( "#sl" ).val();
	var asl=$( "#asl" ).val();
	var isl=$( "#isl" ).val();
	var nsl=$( "#nsl" ).val();
	var pol=$( "#pol" ).val();
	var pod=$( "#pod" ).val();
	var ipol=$( "#ipol" ).val();
	var ipod=$( "#ipod" ).val();
	var eta=$( "#eta" ).val();
	var etd=$( "#etd" ).val();
	
	if(ipod=='')
	{
		alert ('Please Insert Port Of Load');
		return false;
	}
	else if(ipod=='')
	{
		alert ('Please Insert Port Of Destination');
		return false;
	}
	else if(isl=='')
	{
		alert ('Please Insert Shipping Line');
		return false;
	}
	else if(eta=='')
	{
		alert ('Please Insert ETA');
		return false;
	}
	else if(etd=='')
	{
		alert ('Please Insert ETD');
		return false;
	}
	else
	{
		$.post(url,{UKK: id_ukk,SL:sl,ASL:asl, ISL:isl, NSL:nsl, POL:pol, POD:pod, IPOD:ipod, IPOL:ipol, ETA:eta, ETD:etd  },function(data){	
			//alert(data);
			
			$('#edit_vessel').dialog('destroy').remove();	
			$('#mainform').append('<div id="edit_vessel"></div>');
			//document.location.reload(true);
			$("#l_vessel").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=vs_fix", datatype:"json"}).trigger("reloadGrid");		
		});	
	}
}
</script>

<table>
	<tr>
		<td>NO UKK</td>
		<td>:</td>
		<td><?=$nukk;?></td>
	</tr>
	<tr>
		<td>Vessel</td>
		<td>:</td>
		<td><input type='text' id='ves' readonly='readonly' value="<?=$nk;?>"/> <input type='text' id='idves' size='5' readonly='readonly' value="<?=$id_vs;?>"/></td>
	</tr>
	<tr>
		<td>Voy in - voy out</td>
		<td>:</td>
		<td><input type='text' id='vin' size='5' readonly='readonly' value="<?=$vin;?>"/> - <input type='text' id='vout' size='5' readonly='readonly' value="<?=$vout;?>"/></td>
	</tr>
	<tr>
		<td>Shipping Line</td>
		<td>:</td>
		<td><input type='text' id='sl' size='20' value="<?=$npm;?>"/><br /><input type='text' id='asl' size='40' readonly='readonly' value="<?=$apm;?>"/>
			<input type='hidden' id='isl' size='25' value="<?=$coa;?>"/> <input type='hidden' id='nsl' size='25' value="<?=$npwp;?>"/>
		</td>
	</tr>
	<tr>
		<td>POL</td>
		<td>:</td>
		<td><input type='text' id='pol' size='20' value="<?=$pol;?>"/> <input type='text' id='ipol' size='5' readonly='readonly' value="<?=$ipol;?>"/></td>
	</tr>
	<tr>
		<td>POD</td>
		<td>:</td>
		<td><input type='text' id='pod' size='20' value="<?=$pod;?>"/> <input type='text' id='ipod' size='5' readonly='readonly' value="<?=$ipod;?>"/></td>
	</tr>
	<tr>
		<td>ETA</td>
		<td>:</td>
		<td><input type='text' id='eta' size='15' value="<?=$eta;?>"/></td>
	</tr>
	<tr>
		<td>ETD</td>
		<td>:</td>
		<td><input type='text' id='etd' size='15' value="<?=$etd;?>"/></td>
	</tr>
		<tr>
		
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td><button onclick='update_vessel()'>Save</button>
		<button onclick='closex2()'>Cancel</button></td>
	</tr>
</table>