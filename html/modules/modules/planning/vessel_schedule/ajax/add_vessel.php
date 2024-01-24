<?php

?>
<script>

$(document).ready(function() 
{	
	//======================================= autocomplete vessel==========================================//
	$( "#ves" ).autocomplete({
		minLength: 3,
		source: "planning.vessel_schedule.auto/vessel",
		focus: function( event, ui ) 
		{
			$( "#ves" ).val( ui.item.NM_KAPAL);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#ves" ).val( ui.item.NM_KAPAL);
			$( "#idves" ).val( ui.item.KD_KAPAL);
		
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.NM_KAPAL + " <br> " + item.KD_KAPAL + " </a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete vessel==========================================//
	
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

function closex()
{
	//alert('coba');
	$('#add_vessel').dialog('destroy').remove();
	$('#mainform').append('<div id="add_vessel"></div>');
}

function save_vessel()
{
	var url="<?=HOME;?>planning.vessel_schedule.ajax/save_vs_sc";
	var ves=$( "#ves" ).val();
	var idves=$( "#idves" ).val();
	var vin=$( "#vin" ).val();
	var vout=$( "#vout" ).val();
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
	var ship2=$( "#ship" ).val();
	
	if(idves=='')
	{
		alert ('Please Insert Vessel');
		return false;
	}
	else if(vin=='')
	{
		alert ('Please Insert Voyage In');
		return false;
	}
	else if(vout=='')
	{
		alert ('Please Insert Voyage Out');
		return false;
	}
	else if(ipod=='')
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
	else if(ship2=='')
	{
		alert ('Please Choose Ship');
		return false;
	}
	else
	{
		$.post(url,{VES: ves,IDVES:idves, VIN:vin, VOUT:vout, SL:sl,ASL:asl, ISL:isl, NSL:nsl, POL:pol, POD:pod, IPOD:ipod, IPOL:ipol, ETA:eta, ETD:etd , SHIP:ship2 },function(data){	
			//alert(data);
			
			$('#add_vessel').dialog('destroy').remove();	
			$('#mainform').append('<div id="add_vessel"></div>');
			//document.location.reload(true);
			$("#l_vessel").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=vs_fix", datatype:"json"}).trigger("reloadGrid");	
		});	
	}
}
</script>

<table>
	<tr>
		<td>Vessel</td>
		<td>:</td>
		<td><input type='text' id='ves' /> <input type='text' id='idves' size='5' readonly='readonly'/></td>
	</tr>
	<tr>
		<td>Voy in - voy out</td>
		<td>:</td>
		<td><input type='text' id='vin' size='5'/> - <input type='text' id='vout' size='5' /></td>
	</tr>
	<tr>
		<td>Shipping Line</td>
		<td>:</td>
		<td><input type='text' id='sl' size='20'/><br /><input type='text' id='asl' size='40' readonly='readonly'/>
			<input type='hidden' id='isl' size='25'/> <input type='hidden' id='nsl' size='25'/>
		</td>
	</tr>
	<tr>
		<td>POL</td>
		<td>:</td>
		<td><input type='text' id='pol' size='20'/> <input type='text' id='ipol' size='5' readonly='readonly'/></td>
	</tr>
	<tr>
		<td>POD</td>
		<td>:</td>
		<td><input type='text' id='pod' size='20'/> <input type='text' id='ipod' size='5' readonly='readonly'/></td>
	</tr>
	<tr>
		<td>ETA</td>
		<td>:</td>
		<td><input type='text' id='eta' size='15'/></td>
	</tr>
	<tr>
		<td>ETD</td>
		<td>:</td>
		<td><input type='text' id='etd' size='15'/></td>
	</tr>
	<tr>
		<td>Ship</td>
		<td>:</td>
		<td><select id="ship">
			<option value='T'>Tongkang</option>
			<option value='F'>Feeder</option>
			<option value='M'>Mothervessel</option>
		</select></td>
	</tr>
		<tr>
		
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td><button onclick='save_vessel()'>Save</button>
		<button onclick='closex()'>Cancel</button></td>
	</tr>
</table>