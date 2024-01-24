<?
	$id_vs = $_GET['id_vs'];
	$yard = $_GET['yard'];
	$block = $_GET['block'];
	$id_block = $_GET['id_block'];
	$slot = $_GET['slot'];
?>
<script type="text/javascript" src="<?=HOME;?>js/stickytooltip.js"></script>
<link rel="stylesheet" type="text/css" href="<?=HOME;?>css/stickytooltip.css" />
<div align='center'>
<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; background-color:#ffffff; vertical-align:middle; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;">
<form id="form" method="POST" action="<?=HOME?>planning.stowage.ajax/stow_bay?block=<?=$block?>&slot=<?=$slot?>&id_vs=<?=$id_vs?>&yard=<?=$yard?>&id_block=<?=$id_block?>&filter=1">
				<table border="0">
					<tr height="20">
						<td>Size</td><td><select name="size" id="size">
							<option value="">--</option>
							<option value="20">20"</option>
							<option value="40">40"</option>
							<option value="45">45"</option>
						</select></td>
						<td>&nbsp;&nbsp;&nbsp;</td>
						<td>Type</td><td><select name="type" id="type">
							<option value="">--</option>
							<option value="DRY">DRY</option>
							<option value="RFR">RFR</option>
							<option value="HQ">HQ</option>
							<option value="DG">DG</option>
						</select></td>
					</tr>
					<tr>
						<td>Status</td><td><select name="statusx" id="statusx">
							<option value="">--</option>
							<option value="FCL">FCL</option>
							<option value="MTY">MTY</option>
						</select></td>
						<td>&nbsp;&nbsp;&nbsp;</td>
						<td>Tonase</td><td><select name="tonase" id="tonase" data-tooltip="weight_class">
							<option value="">--</option>
							<option value="L2">L2 </option>
							<option value="L1">L1</option>
							<option value="M">M</option>
							<option value="H">H</option>
							<option value="XH">XH</option>
						</select></td>
					</tr>
					<tr>
						<td>Tujuan </td><td colspan="4">
						<input type="text" name="pel_tuj" id="pel_tuj" size="20"> 
						<input type="text" name="id_pel_tuj" size="3" id="id_pel_tuj"></td>
					</tr>
					<tr>
						<td>
							Vessel / Voy</td><td colspan="4"> 
							<input type="text" name="vessel" size="20" id="vessel">
							<input type="text" name="voyage" size="10" id="voyage">
							<input type="hidden" name="id_vs" size="10" id="id_vs">
						</td>
					</tr>
					<tr>
						<td colspan="5" align="right"><input type="submit" value=" Proses " /> </a>
						</td>
					</tr>
				</table>
				</form>
				</fieldset>
				
<div id="mystickytooltip" class="stickytooltip">
                  <div style="padding:5px"> 
                       <div id="weight_class" class="atip">                                    
                         <table class="grid-table" border='0' cellpadding="1" cellspacing="1" width="40%">
							<tr>
								<th colspan='4' class="grid-header" width='20' align="center"><b><font size="1px">Container 20"</font></th>
							    <th colspan='4' class="grid-header" width='20' align="center"><font size="1px"><b>Container 40"</b></font></th>
							</tr>
							<tr>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">L2</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">1500</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">9999</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>L2</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>1500</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td><td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>9999</td>
							</tr>
							<tr>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">L1</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">10000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">14999</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>L1</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>10000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>14999</td>
							</tr>
							<tr>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">M</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">15000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">19999</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>M</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>15000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>19999</td>
							</tr>
							<tr>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">H</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">20000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">24999</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>H</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>20000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>24999</td>
							</tr>
							<tr>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor="#12e0fa">XH</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">25000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor="#12e0fa">35000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' align="center" bgcolor='#fab912'>XH</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>25000</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>S/D</td>
								<td class="grid-cell" valign="top" style="font-size:8px;color:#000000" width='20' bgcolor='#fab912'>35000</td>
							</tr>
						 </table>
                       </div>                        
                  </div>
                <div class="stickystatus"></div>
</div>
</div>

<script type="text/javascript">
function reset_flt(){
	$('#size').val();
	$('#type').val();
	$('#status').val();
	$('#vessel_21').val();
	$('#tonase').val();
	$('#tuj').val();

	if (($('#size').val() == '') && ($('#type').val() == '') && ($('#status').val() == '') && ($('#vessel_21').val() == '') && ($('#tonase').val() == '') && ($('#tuj').val() == '')){
		alert('kategori filter belum dipilih..');
	} else {
		$('#form').submit();
	}
}

$(function() {
	
	<!------------------- autocomplete Vessel ------------>
	$( "#vessel" ).autocomplete({
		minLength: 3,
		source: "<?=HOME?>maintenance.monitoring.auto/parameter",
		focus: function( event, ui ) {
			$( "#vessel" ).val( ui.item.NM_KAPAL );
			return false;
		},
		select: function( event, ui ) {
			$( "#vessel" ).val( ui.item.NM_KAPAL);
			$( "#voyage" ).val( ui.item.VOYAGE);
			$( "#id_vs" ).val( ui.item.ID_VS);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NM_KAPAL + " [" + item.VOYAGE + "] " + "</a>" )
			.appendTo( ul );
	};
	<!------------------- autocomplete Vessel ------------>
	
		$( "#pel_tuj" ).autocomplete({
		minLength: 3,
		source: "<?=HOME?>maintenance.monitoring.auto/pel_tuj",
		focus: function( event, ui ) {
			$( "#pel_tuj" ).val( ui.item.PEL_TUJ );
			return false;
		},
		select: function( event, ui ) {
			$( "#pel_tuj" ).val( ui.item.PEL_TUJ);
			$( "#id_pel_tuj" ).val( ui.item.ID_PEL_TUJ );
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.PEL_TUJ + " [" + item.ID_PEL_TUJ + "] " + "</a>" )
			.appendTo( ul );
	};
	
});

</script>