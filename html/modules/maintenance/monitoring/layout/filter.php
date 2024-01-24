<head>
<title> Filter Lapangan </title>
</head><script src="<?=HOME?>js_chart/js/highcharts.js"></script>
<script src="<?=HOME?>js_chart/js/modules/exporting.js"></script>
<link rel="stylesheet" href="<?=HOME?>yard/src/css/excite-bike/jquery-ui-1.8.16.custom.css">
	<script src="<?=HOME?>yard/src/js/jquery-1.7.min.js" type="text/javascript"></script>
	<script src="<?=HOME?>yard/src/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
<h2><image src="<?=HOME?>images/filter.png" width="50" height="50"> &nbsp Filter Lapangan</h2>
<div align='center'>
<fieldset class="form-fieldset" style="margin: 5px 5px 5px 5px; background-color:#ffffff; vertical-align:middle; border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px;">
<form id="form" method="POST" action="<?=HOME?>maintenance.monitoring.layout/load_layout">
				<table border="0">
					<tr height="20">
						<td>Size</td><td>  &nbsp &nbsp  :  &nbsp &nbsp <select name="size" id="size">
							<option value="">--</option>
							<option value="20">20"</option>
							<option value="40">40"</option>
							<option value="45">45"</option>
						</select></td>
						<td></td>
						<td>Type</td><td>  &nbsp &nbsp  :  &nbsp &nbsp <select name="type" id="type">
							<option value="">--</option>
							<option value="DRY">DRY</option>
							<option value="RFR">RFR</option>
							<option value="HQ">HQ</option>
							<option value="DG">DG</option>
						</select></td>
					</tr>
					<tr>
						<td>Status</td><td>  &nbsp &nbsp :  &nbsp &nbsp <select name="status" id="status">
							<option value="">--</option>
							<option value="FCL">FCL</option>
							<option value="MTY">MTY</option>
						</select></td>
						<td></td>
						<td>Tonase</td><td>  &nbsp &nbsp :  &nbsp &nbsp <select name="tonase" id="tonase">
							<option value="">--</option>
							<option value="L2">L2 </option>
							<option value="L1">L1</option>
							<option value="M">M</option>
							<option value="H">H</option>
							<option value="XH">XH</option>
						</select></td>
					</tr>
					<tr>
						<td>Tujuan </td><td colspan="4">  &nbsp &nbsp :  &nbsp &nbsp
						<input type="text" name="pel_tuj" id="pel_tuj" size="20"> 
						<input type="text" name="id_pel_tuj" size="5" id="id_pel_tuj"></td>
					</tr>
					<tr>
						<td>
							Vessel / Voy</td><td colspan="4">  &nbsp &nbsp :  &nbsp &nbsp 
							<input type="text" name="vessel" size="20" id="vessel">
							<input type="text" name="voyage" size="5" id="voyage">
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