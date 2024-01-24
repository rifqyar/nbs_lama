<?php
?>
<style>
.content{
	width:95%;
	margin-left:auto;
	margin-right:auto;
	margin-bottom: 10px;
	margin-top:20px;

}
.ui-jqgrid .ui-jqgrid-htable th div {
    height:auto;
    overflow:hidden;
    padding-right:4px;
    padding-top:2px;
    position:relative;
    vertical-align:text-top;
    white-space:normal !important;
}
</style>
<script>
$(document).ready(function() 
	
{
	//======================================= autocomplete container==========================================//
	$( "#NO_CONTAINER" ).autocomplete({
		minLength: 3,
		source: "monitoring.history_cont2.auto/container",
		focus: function( event, ui ) 
		{
			$( "#NO_CONTAINER" ).val( ui.item.NO_CONTAINER);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#no_cont" ).val( ui.item.NO_CONTAINER);
			$( "#no_ukk" ).val( ui.item.NO_UKK);
			$( "#no_req" ).val( ui.item.NO_REQUEST);
			$( "#status_code" ).val( ui.item.KODE_STATUS);
			$( "#bp" ).val( ui.item.LOKASI_BP);
			$( "#size" ).val( ui.item.SIZE_);
			$( "#tipe" ).val( ui.item.TYPE_);
			$( "#status" ).val( ui.item.STATUS);
			$( "#hz" ).val( ui.item.HZ);
			$( "#berat" ).val( ui.item.BERAT);
			$( "#iso_code" ).val( ui.item.ISO_CODE);
			$( "#height_c" ).val( ui.item.HEIGHT);
			$( "#vessel" ).val( ui.item.NM_KAPAL);
			$( "#voyage" ).val( ui.item.VOYAGE_IN+' - '+ui.item.VOYAGE_OUT);
			$( "#pol" ).val( ui.item.POL);
			$( "#pod" ).val( ui.item.POD);
			$( "#js" ).val( ui.item.ID_JOBSLIP);
			$( "#imo" ).val( ui.item.IMO);
			$( "#ei" ).val( ui.item.E_I);
			$( "#pi" ).val( ui.item.PLUG_IN);
			$( "#po" ).val( ui.item.PLUG_OUT);
            $( "#emkl" ).val( ui.item.EMKL);
			$( "#hold" ).val( ui.item.HOLD_STATUS);
			$( "#hold_date" ).val( ui.item.HOLD_DATE);
			$( "#obx" ).val( ui.item.NAME_YD);
			if(ui.item.E_I=='I')
			{
				var urlobx="<?=HOME?>monitoring.history_cont2.ajax/cekStatusObx";
				$.post(urlobx, {NC:ui.item.NO_CONTAINER, VES:ui.item.NM_KAPAL, VIN:ui.item.VOYAGE_IN, VOT:ui.item.VOYAGE_OUT}, function (data){
					$( "#obxremarks" ).val(data);
				});
			}
			$('#loadx').html('<img src="<?=HOME?>images/loadingF.gif" />');
			$('#loadx').load("<?=HOME?>monitoring.history_cont2.ajax/detail_handling?id_ct="+ui.item.NO_CONTAINER+"&voyin="+ui.item.VOYAGE_IN+"&voyout="+ui.item.VOYAGE_OUT+"&ei="+ui.item.E_I);
			$('#loadx2').load("<?=HOME?>monitoring.history_cont2.ajax/detail_billing?id_ct="+ui.item.NO_CONTAINER+"&voyin="+ui.item.VOYAGE_IN+"&voyout="+ui.item.VOYAGE_OUT+"&ei="+ui.item.E_I+"&ukk="+ui.item.NO_UKK);
			$('#loadx3').load("<?=HOME?>monitoring.history_cont2.ajax/detail_behandle?id_ct="+ui.item.NO_CONTAINER+"&voyin="+ui.item.VOYAGE_IN+"&voyout="+ui.item.VOYAGE_OUT);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.NO_CONTAINER + " [" +item.E_I +"] <br> " + item.SIZE_ + " / "+item.TYPE_
		+" / "+item.STATUS+"<BR>"+item.NM_KAPAL+" / "+item.VOYAGE_IN+" "+item.VOYAGE_OUT+"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete container==========================================//
	
	//tabs kesukaan
	$( "#tabs" ).tabs();
	$( "#tabspage" ).tabs();
	$( "#tabspage1" ).tabs();
});

</script>


<div class="content" id="finds">
	<p>
	<h2> <img src="<?=HOME?>images/cont_gate.png" height="5%" width="5%" style="vertical-align:middle">&nbsp;<font color="#0378C6">History </font>Container</h2></p>
	
	<p><br/>
	  </p>
	<table>
	
		<tr>
				<td class="form-field-caption" valign="middle" align="right" width="125">NO CONTAINER</td>
				<td valign="middle" class="form-field-input">: </TD>
				<td valign="middle" class="form-field-input">
				<input style="font-size:26px; font-weight:bold;" id="NO_CONTAINER" name="NO_CONTAINER" size="15" titl="entry" class="suggestuwriter" type="text" maxlength="16" value="" /> &nbsp;</TD>
		</tr>
		<TR>
			<TD colspan="3" align="right"></TD>
		</TR>
	</table>
	<hr width="870" color="#e1e0de"></hr><p><br/></p>
	
	<table>
	<tr>
		<td class="form-field-caption" align="right">No Container</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="15" maxlength="11" name="no_cont" id="no_cont" readonly="readonly" style="background-color:#6ccff0; color:BLACK;font-weight:bold;text-align:center;font-size:15pt;"/>
		</td>
		
		<td></td>
		
	</tr>
	<tr>
		<td class="form-field-caption" align="right">No UKK</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="15" maxlength="11" name="no_ukk" id="no_ukk" readonly="readonly" />
		</td>
		
		<td></td>
		<td class="form-field-caption" align="right"><!--No Request--></td>
		<td class="form-field-caption" align="right"><!--:--></td>
		<td><!--<input type="text" size="15" maxlength="11" name="no_req" id="no_req" readonly="readonly" />-->
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Status Code</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="20" maxlength="20" name="status_code" id="status_code" readonly="readonly" style="background-color:#f9151f; color:white;font-weight:bold;text-align:center"/>
		</td>
		<td></td>
		<td class="form-field-caption" align="right">Bay Plan</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="10" name="bp" id="bp" readonly="readonly"/> 
		</td>
	</tr>
	 <tr>
		<td class="form-field-caption" align="right">Size / Type / Status</td><td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="size" id="size" readonly="readonly" />
			<input type="text" size="5" name="tipe" id="tipe" readonly="readonly" />
			<input type="text" size="5" name="status" id="status" readonly="readonly" />&nbsp;
			
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Berat</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="berat" id="berat" readonly="readonly"/> Kgs
		</td>
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Hz - IMO Class</td>
		<td class="form-field-caption" align="right"> : </td>
		<td><input type="text" size="5" name="hz" id="hz" readonly="readonly" /> - <input type="text" size="10"  id="imo" readonly="readonly" /></td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">E I</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="ei" id="ei" readonly="readonly"/> 
		</td>
	 </tr>
	 <tr>
		<td class="form-field-caption" align="right">ISO CODE - Height</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="iso_code" id="iso_code" readonly="readonly" />&nbsp;<input type="text" size="5" readonly="readonly" id="height_c" /></td>
		<td></td>
		<td class="form-field-caption" align="right">Plug In/Out</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="18" name="pi" id="pi" readonly="readonly"/> / <input type="text" size="18" name="po" id="po" readonly="readonly"/> 
		</td>
	 </tr>
	 <tr>
		<td class="form-field-caption" align="right">Vessel / Voyage</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="25" name="vessel" id="vessel" readonly="readonly" /> / 
			<input type="text" size="8" name="voyage" id="voyage" readonly="readonly" />
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Temp</td>
		<td class="form-field-caption" align="right">:</td>
		<td> 
			<input type="text" size="8"  id="temp" readonly="readonly" />
		</td>
     </tr>
	 <tr>
		<td class="form-field-caption" align="right">Pelabuhan Asal ~ Tujuan</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="15" name="pol" id="pol" readonly="readonly" />~<input type="text" size="15" name="pod" id="pod" readonly="readonly" />
		</td>
		<td>&nbsp;</td>
		<td class="form-field-caption" align="right">Segel Merah</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="5" name="hold" id="hold" readonly="readonly"/><input type="text" size="10" name="hold_date" id="hold_date" readonly="readonly"/>	 </td>
     </tr>
     
     <tr>
		<td class="form-field-caption" align="right">Customer</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="36" name="emkl" id="emkl" readonly="readonly"/>		
		</td>
		<td>&nbsp;</td>	
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Status OBX / TPFT</td>
		<td class="form-field-caption" align="right">:</td>
		<td><input type="text" size="50" name="obx" id="obx" readonly="readonly"/>		
			<br>
			<input type="text" size="50" name="obxremarks" id="obxremarks" readonly="readonly"/>		
		</td>
		<td>&nbsp;</td>	
	</tr>
     
	 <tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	 </tr>
	 
	</table>

	
	<br>
	<hr width="870" color="#e1e0de"></hr>
	<br>
	<div id="tabs">
		<ul>
		<li><a href="#tabs-1">Handling</a></li>
		<li><a href="#tabs-2">Billing</a></li>
		<li><a href="#tabs-3">Behandle</a></li>
		</ul>
		<div id="tabs-1">	
			<div id="loadx" align='center'></div>
			<br>
		</div>
		<div id="tabs-2">
			<div id="loadx2" align='center'></div>
		</div>
		<div id="tabs-3">
			<div id="loadx3" align='center'></div>
		</div>
	</div>
</div>
