<?php
	$ukk=$_GET['id'];
	$q_h="SELECT NM_KAPAL, NO_UKK, VOYAGE_IN, VOYAGE_OUT, NM_PEMILIK,TO_CHAR(RTA,'DD-MM-YYYY HH24:MI') RTA,
			TO_CHAR(RTD,'DD-MM-YYYY HH24:MI') RTD, ID_POL, ID_POD,
			TO_CHAR(TGL_JAM_TIBA,'DD-MM-YYYY HH24:MI') ETA,
			TO_CHAR(TGL_JAM_BERANGKAT,'DD-MM-YYYY HH24:MI') ETD
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
	$ipol=$rh['ID_POL'];
	$ipod=$rh['ID_POD'];
	$rtd=$rh['RTD'];
	$rta=$rh['RTA'];
	$etd=$rh['ETD'];
	$eta=$rh['ETA'];
?>
<script>

$(document).ready(function() 
{		
	//date time picker for eta etd
	
	$("#rtat").datetimepicker({
		dateFormat: 'dd-mm-yy'
		});
		
});

function closex3()
{
	//alert('coba');
	$('#rta').dialog('destroy').remove();
	$('#mainform').append('<div id="rta"></div>');
}

function update_rta()
{
	var url="<?=HOME;?>planning.vessel_schedule.ajax/edit_rta";
	var id_ukk='<?=$nukk;?>';
	var rta=$( "#rtat" ).val();
	
	if(rta=='')
	{
		alert ('Please Insert RTA');
		return false;
	}
	else
	{
		$.post(url,{UKK: id_ukk,RTA:rta},function(data){	
			//alert(data);
			
			$('#rta').dialog('destroy').remove();	
			$('#mainform').append('<div id="rta"></div>');
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
		<td><B><?=$nk;?></b></td>
	</tr>
	<tr>
		<td>Voy in - voy out</td>
		<td>:</td>
		<td><B><?=$vin;?> - <?=$vout;?></B></td>
	</tr>
	<tr>
		<td>Shipping Line</td>
		<td>:</td>
		<td><B><?=$npm;?></B>
		</td>
	</tr>
	<tr>
		<td>POL - POD</td>
		<td>:</td>
		<td><B><?=$ipol;?> - <?=$ipod;?></B></td>
	</tr>
	<tr>
		<td>ETA - ETD</td>
		<td>:</td>
		<td><B><?=$eta;?> - <?=$etd;?></B></td>
	</tr>
	<tr>
		<td>RTA</td>
		<td>:</td>
		<td><input type='text' id='rtat' size='15' value="<?=$rta;?>"/></td>
	</tr>
	<tr>
		<td>RTD</td>
		<td>:</td>
		<td><b><?=$rtd;?></b></td>
	</tr>
		<tr>
		
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td></td>
		<td></td>
		<td><button onclick='update_rta()'>Save</button>
		<button onclick='closex3()'>Cancel</button></td>
	</tr>
</table>