<script>
$(document).ready(function() 
{
	//======================================= autocomplete container==========================================//
	$( "#nc" ).autocomplete({
		minLength: 6,
		source: "monitoring.perbaikan_disc.Auto/container",
		focus: function( event, ui ) 
		{
			$( "#nc" ).val( ui.item.NO_CONTAINER);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#nc" ).val( ui.item.NO_CONTAINER);
			$( "#vessel" ).val( ui.item.VESSEL);
			$( "#voyin" ).val( ui.item.VOYAGE_IN);
			$( "#voyout" ).val( ui.item.VOYAGE_OUT);
			$( "#status" ).val( ui.item.STATUS);
			$( "#disc_date" ).val( ui.item.DISCHARGE_CONFIRM);
			return false;						            
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NO_CONTAINER + "<br>" +item.VESSEL+"<br>" +item.VOYAGE_IN+"<br>" +item.VOYAGE_OUT+"<br>" +item.DISCHARGE_CONFIRM+"</a>")
		.appendTo( ul );	
	};
	document.getElementById("nc").focus();
});


function hap(){

var update_date = $("#disc_date").val();
var vessel = $("#vessel").val();
var voyage_in = $("#voyin").val();
var voyage_out = $("#voyout").val();
var no_container = $("#nc").val();

var url="<?=HOME;?>monitoring.perbaikan_disc.Ajax/update";

$.post(url,{UPDATE_DATE:update_date,
			VESSEL:vessel,
			VOYAGE_IN:voyage_in,
			VOYAGE_OUT:voyage_out,
			NO_CONTAINER:no_container}, 
		function(data){ alert(data); });	
}
    
    
</script>

<div style="margin: 15 15 15 50">

	<h1>PERBAIKAN TANGGAL DISCHARGE</h1>

	<br><br>

	Masukan Nomor Container
	<input type="text" size="11" id="nc" name="nc" style="background-color:#FFFFFF; font-weight:bold;font-size:24px;text-align:center"/> 

	<br><br>

	<font><i>Discharge Date</i></font>

	<input type="text" size="11" id="disc_date" name="disc_date" style="background-color:#FFFFFF; font-weight:bold;font-size:12px;text-align:center; width:150px"/> 

	
	<font><i>Sts Cont</i></font>

	<input type="text" size="11" id="status" name="status" style="background-color:#FFFFCC; font-weight:bold;font-size:12px;text-align:center"/> 

	<font><i>Vessel</i></font>

	<input type="text" size="11" id="vessel" name="vessel" style="background-color:#FFFFCC; font-weight:bold;font-size:12px;text-align:center"/> 	


	<font><i>Voyage IN</i></font>

	<input type="text" size="11" id="voyin" name="voyin" style="background-color:#FFFFCC; font-weight:bold;font-size:12px;text-align:center"/> 

	<font><i>Voyage Out</i></font>

	<input type="text" size="11" id="voyout" name="voyout" style="background-color:#FFFFCC; font-weight:bold;font-size:12px;text-align:center"/> 

	<br>
	<br>
	<br>

	<b>Update Tgl Discharge</b> 

	<input type="text" size="11" id="updisc" name="updisc" style="background-color:#FFFFFF; font-weight:bold;font-size:24px;text-align:center; width:250px; color:red"/>

<br><br>

<button onclick="hap()">Upate Disc Date</button>
</p>

</div>
