<?php
	$db=getDb('dbint');


?>
<script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.20.custom.min.js"> </script>

<script>
	function sbmDisable()
	{
		if($( "#point" ).val()=='')
		{
			alert('Container invalid, point number kosong');	
		}
		else if($( "#remarks" ).val()=='')
		{
			alert('Mohon isi remarks sebagai dasar Disable Container');
		}
		else 
		{
			alert('ok');	
			$('#kaliKalian').load("<?=HOME?>maintenance.disableContainer.ajax/kaliKalian").dialog({ modal:true, height:100,width:200, title : "Validasi", open: function(event,ui){$(".ui-dialog-titlebar-close",ui.dialog).hide();}});	
		}
	}

	function sbmKaliKalian(a,b)
	{
		var c=a*b;
		var isian=$("#isiannya").val();
		if(isian==c){
			alert('ok');

		}
		else
		{
			$('#kaliKalian').load("<?=HOME?>maintenance.disableContainer.ajax/kaliKalian").dialog({ modal:true, height:100,width:200, title : "Validasi", open: function(event,ui){$(".ui-dialog-titlebar-close",ui.dialog).hide();}});
		}


	}

	$(document).ready(function(){
		$( "#idcont" ).autocomplete({
		minLength: 3,
		source: "maintenance.disableContainer.ajax/autoContainer",
		focus: function( event, ui ) 
		{
			$( "#idcont" ).val( ui.item.NO_CONTAINER);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#idcont" ).val( ui.item.NO_CONTAINER);
			$( "#isocode" ).val( ui.item.ISO_CODE);
			$( "#status" ).val( ui.item.STATUS_CONT);
			$( "#vessel" ).val( ui.item.VESSEL);
			$( "#vin" ).val( ui.item.VOY_IN);
			$( "#vot" ).val( ui.item.VOY_OUT);
			$( "#noreq" ).val( ui.item.NO_REQUEST);
			$( "#nonota" ).val( ui.item.NO_TRX);
			$( "#customer" ).val( ui.item.CUSTOMER_NAME);
			$( "#point" ).val( ui.item.POINT);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.NO_CONTAINER + " [" +item.POINT +"] <br> " + item.ISO_CODE + " - "+item.STATUS_CONT
		+"<BR>"+item.VESSEL+" / "+item.VOY_IN+" "+item.VOY_OUT+"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete container==========================================//
	
	});

</script>
<style>
.bigText {
    height:30px;
}
</style>

<table>
	<tr>
		<td>No. Container</td>
		<td>: <input type="text" size="12" name="idcont" id="idcont" maxlength="11" class="bigText" style="font-size:23px; font-weight:bold;"/></td>
	</tr>
	<tr>
		<td>Point</td>
		<td>: <input type="text" size="10" name="point" id="point"/></td>
	</tr>
	<tr>
		<td>ISO CODE</td>
		<td>: <input type="text" size="10" name="isocode" id="isocode"/> <input type="text" size="10" name="status" id="status"/></td>
	</tr>
	<tr>
		<td>Vessel</td>
		<td>: <input type="text" size="20" name="vessel" id="vessel"/> <input type="text" size="5" name="vin" id="vin"/> <input type="text" size="5" name="vot" id="vot"/></td>
	</tr>
	<tr>
		<td>Nomor Transaksi</td>
		<td>: <input type="text" size="20" name="noreq" id="noreq"/> - <input type="text" size="30" name="nonota" id="nonota"/></td>
	</tr>
	<tr>
		<td>Customer</td>
		<td>: <input type="text" size="30" name="customer" id="customer"/></td>
	</tr>
	<tr>
		<td>Remarks</td>
		<td>: <textarea cols="50" rows="5" id="remarks" name="remarks"></textarea></td>
	</tr>
</table>
<p align="center"><button onclick="sbmDisable()">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S A V E&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></p>
	<div id="kaliKalian"></div>