<style>
.content {
    margin: 20px auto 10px;
    width: 95%;
}
</style>
<script>
var point="";
$(document).ready(function() 
{
	//======================================= autocomplete container==========================================//
	$( "#id_contnumb" ).autocomplete({
		minLength: 6,
		source: "report.reefermon.auto/container",
		focus: function( event, ui ) 
		{
			$( "#id_contnumb" ).val( ui.item.NO_CONTAINER);
			return false;
		},
		select: function( event, ui ) 
		{
			document.getElementById('id_contnumb').innerHTML=ui.item.NO_CONTAINER;
			document.getElementById('id_contspek').innerHTML=ui.item.CONTSPEK;
			document.getElementById('id_contvvd').innerHTML=ui.item.CONTVVD;
			document.getElementById('id_conttemp').innerHTML=ui.item.TEMPS;
			document.getElementById('id_contplugin').innerHTML=ui.item.CONTPLUGIN;
			document.getElementById('id_contplugout').innerHTML=ui.item.CONTPLUGOUT;
			document.getElementById('id_expimp').innerHTML=ui.item.E_I;
			document.getElementById('id_shift').innerHTML=ui.item.SHIFT;
			document.getElementById('customer').innerHTML=ui.item.CUSTOMER_NAME;
			point=ui.item.POINT;
			$.post("<?=HOME?><?=APPID?>.ajax/get_payment_status",{NO_CONT:ui.item.NO_CONTAINER, VESSEL:ui.item.VESSEL, VOYAGE_IN: ui.item.VOYAGE_IN, VOYAGE_OUT:ui.item.VOYAGE_OUT, SHIFT_REAL:ui.item.SHIFT_REAL},function(data){
					var parsed = JSON.parse(data);
					console.log(parsed.STATUS_PAYMENT); // logs "b1"
					//console.log(parsed[1].a2); // logs "b2"
					document.getElementById('shift_rec').innerHTML=parsed.SHIFT_REEFER;
					document.getElementById('paystat').innerHTML=parsed.STATUS_PAYMENT;
					document.getElementById('nominal').innerHTML=parsed.NOMINAL;
			});
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NO_CONTAINER + "<br>" +item.CONTVVD+"<br>" +item.CONTSPEK+"<br>" +item.E_I+"</a>")
		.appendTo( ul );
	
	};
});

function genReport()
{
	var contNumb=$('#id_contnumb').val();
	var url="report.reefermon.ajax/generate";
	
	/*
	$.post(url,{CONTNUMB:contNumb,POINT:point},function(data){
		ids=data;
	});*/
	var ids=contNumb+'^'+point;
	var win = window.open("report.reefermon.ajax/print_reefer?segment="+ids, '_blank');
	win.focus();
}
</script>
<div class="content">
<div class="main_side">
	<p>
	<img src="<?=HOME?>images/20.png" height="7%" width="7%" style="vertical-align:middle">
	<b> <font color='#69b3e2' size='4px'>Reefer Monitoring</font> </b>
	 <font color='#888b8d' size='4px'>
	 Report
	 </font>
	</p>
	<hr color="#acacac"/>
<table>
	<tr>
		<td>Container Number</td>
		<td>: <input type="text" id="id_contnumb" name="nm_contnumb" SIZE="20" placeholder="autocomplete"/>
	</tr>
	<tr>
		<td>Spesifikasi Container</td>
		<td>: <label id="id_contspek" name="nm_contspek" />
		</td>
	</tr>
	<tr>
		<td>Vessel / Voyage</td>
		<td>: <label id="id_contvvd" name="nm_contvvd" />
		</td>
	</tr>
	<tr>
		<td>Temp (C)</td>
		<td>: <label id="id_conttemp" name="nm_conttemp" />
		</td>
	</tr>
	<tr>
		<td>Plug in</td>
		<td>: <label id="id_contplugin" name="nm_contplugin" />
		</td>
	</tr>
	<tr>
		<td>Plug out</td>
		<td>: <label id="id_contplugout" name="nm_contplugout" />
		</td>
	</tr>
	<tr>
		<td>Export / Import</td>
		<td>: <label id="id_expimp" name="nm_expimp" />
		</td>
	</tr>
	<tr>
		<td>Hours / Shift</td>
		<td>: <label id="id_shift" name="nm_shift" />
		</td>
	</tr>
	<tr>
		<td>Sudah Bayar (Shift)</td>
		<td>: <label id="shift_rec" name="shift_rec" />
		</td>
	</tr>
	<!--tr>
		<td>Status Payment </td>
		<td>: <label id="paystat" name="paystat" />
		</td>
	</tr>
	<tr>
		<td>Nominal (Rp)</td>
		<td>: <label id="nominal" name="nominal" />
		</td>
	</tr-->
	<tr>
		<td>Customer Name</td>
		<td>: <label id="customer" name="customer" />
		</td>
	</tr>

	<tr>
		<td>&nbsp;</td>
		<td><button id="but2" onclick="genReport()">&nbsp;&nbsp;&nbsp;&nbsp;Generate Report&nbsp;&nbsp;&nbsp;&nbsp;</button>
		</td>
	</tr>
</table>
</div>	

</div>