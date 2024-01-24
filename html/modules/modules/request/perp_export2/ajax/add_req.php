<script>

$(document).ready(function() 
{		
	//===== auto complete vessel //
	$( "#vessel" ).autocomplete({
		minLength: 3,
		source: "<?=HOME?>request.perp_export2.auto/vessel",
		focus: function( event, ui ) {
			$( "#vessel" ).val( ui.item.VESSEL);
			return false;
	},
		select: function( event, ui ) {
			$( "#vessel" ).val( ui.item.VESSEL);
			$( "#voyin" ).val( ui.item.VOYAGE_IN);
			$( "#voyout" ).val( ui.item.VOYAGE_OUT);
			$( "#shipping_line" ).val( ui.item.OPERATOR_NAME);
			$( "#eta" ).val( ui.item.ETA);
			if(ui.item.STRING_FIRST_ETD == null || ui.item.STRING_FIRST_ETD == ''){
				$( "#etd" ).val( ui.item.ETD);
			} else {
				$( "#etd" ).val( ui.item.FIRST_ETD);
			}
			$( "#rta" ).val( ui.item.ATA);
			$( "#rtd" ).val( ui.item.ATD);
			$( "#pol" ).val( ui.item.POL);
			$( "#pod" ).val( ui.item.POL);
			$( "#kdpol" ).val( ui.item.ID_POL);
			$( "#kdpod" ).val( ui.item.ID_POD);
			return false;
		}
		
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.VESSEL + " " + item.VOYAGE_IN + "/ "+item.VOYAGE_OUT+"</a>")
			.appendTo( ul );
	}; 
	
	//===== auto complete pelanggan //

	$( "#pelanggan" ).autocomplete({
		minLength: 3,
		source: "<?=HOME?>request.perp_export2.auto/pelanggan",
		focus: function( event, ui ) {
			$( "#pelanggan" ).val( ui.item.NAMA_PERUSAHAAN);
			return false;
	},
		select: function( event, ui ) {
			$( "#pelanggan" ).val( ui.item.NAMA_PERUSAHAAN);
			$( "#alamat" ).val( ui.item.ALAMAT_PERUSAHAAN);
			$( "#npwp" ).val( ui.item.NO_NPWP);
			$( "#kd_pelanggan" ).val( ui.item.KD_PELANGGAN);
			
			return false;
		}
		
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NAMA_PERUSAHAAN + " <br> " + item.ALAMAT_PERUSAHAAN + "<br>"+item.NO_NPWP+"</a>")
			.appendTo( ul );
	}; 	
});

function closex3()
{
	//alert('coba');
	$('#stack_ext').dialog('destroy').remove();
	$('#mainform').append('<div id="stack_ext"></div>');
}

function sync_pelanggan(){
	var url="<?=HOME;?>request.delivery.sp2.ajax/sync_pelanggan";
	var tees='tes';
	$.post(url,{tes:tees},function(data){	
		alert('sukses');
	});
}

function save()
{
	var vessel_ = $("#vessel").val();
	var voyin_  = $("#voyin").val();
	var voyout_ = $("#voyout").val();
	var kd_pelanggan_ = $("#kd_pelanggan").val();
	var url="<?=HOME;?>request.perp_export2.ajax/save_req";
	var c=confirm("Are you Sure?");
	if (c==true)
	{
		$.post(url,{vessel:vessel_,voyin:voyin_,voyout:voyout_, kd_pelanggan:kd_pelanggan_},function(data){
		if (data =='EXIST'){
			alert("failed, vessel already exist");
		} else {
			//alert(data);
			alert("success");
			$('#stack_ext').dialog('destroy').remove();	
			$('#mainform').append('<div id="stack_ext"></div>');
			
			$("#l_vessel").jqGrid('setGridParam',{url:"<?=HOME?>request.perp_export2.data/", datatype:"json"}).trigger("reloadGrid");		
		}
		});
	} else
		{
			return false;
		}
}
</script>
<div id="stack_ext">
<table>
	<tr>
		<td>Vessel</td>
		<td>:</td>
		<td><input style="font-size:15px; font-weight:bold;" id="vessel" name="vessel" size="15" title="entry" class="suggestuwriter" type="text" maxlength="16" value="" />
		<input style="font-size:15px; font-weight:bold;" id="voyin" name="voyin" size="4" title="entry" class="suggestuwriter" type="text" maxlength="16" />/<input style="font-size:15px; font-weight:bold;" id="voyout" name="voyout" size="4" title="entry" class="suggestuwriter" type="text" maxlength="16" />		
		<input type="hidden" id="no_ukk" ></td>
	</tr>
	<tr>
		<td>Shipping Line</td>
		<td>:</td>
		<td><input type="text" id="shipping_line" size="30" readonly="readonly"></B>
		</td>
	</tr>
	<tr>
		<td>Port Of Loading</td>
		<td>:</td>
		<td><input type="text" id="kdpol" size="5" readonly="readonly"><input type="text" id="pol" size="20" readonly="readonly"></td>
	</tr>
	<tr>
		<td>Port Of Discharge</td>
		<td>:</td>
		<td><input type="text" id="kdpod" size="5" readonly="readonly"><input type="text" id="pod" size="20" readonly="readonly"></td>
	</tr>
	<tr>
		<td>ETA - ETD</td>
		<td>:</td>
		<td><input type="text" id="eta" size="20" readonly="readonly"> s/d <input type="text" id="etd" size="20" readonly="readonly"></td>
	</tr>
	<tr>
		<td>RTA</td>
		<td>:</td>
		<td><input type='text' id='rta' size='20' readonly="readonly"/> s/d <input type='text' id='rtd' size='20' readonly="readonly"/></td>
	</tr>
	<tr>
		<td>Nama Pelanggan</td>
		<td>:</td>
		<td><input style="font-size:15px; font-weight:bold;" id="pelanggan" name="pelanggan" size="30" title="entry" class="suggestuwriter" type="text" maxlength="16" value="" />
		<input id="kd_pelanggan" name="kd_pelanggan" size="20" type="hidden" readonly="readonly" />
		<button onclick="sync_pelanggan()" title="sync data pelanggan"><img src="<?=HOME;?>images/sync.png" width="10" height="10"/></button>
		</td>
	</tr>
	<tr>
		<td>Alamat</td>
		<td>:</td>
		<td><input id="alamat" name="alamat" size="40" type="text" readonly="readonly" /></td>
	</tr>
	<tr>
		<td>NPWP</td>
		<td>:</td>
		<td><input id="npwp" name="npwp" size="25" type="text" readonly="readonly" /></td>
	</tr>
	<tr>
		<td colspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3"><button onclick='save()'><img src="<?=HOME?>images/save.png" width="15" height="15">Save</button> &nbsp &nbsp <button onclick='closex3()'>Cancel</button></td>
	</tr>
</table>
</div>