<?php
	$req=$_GET['id'];
	$ship=$_POST['VES'];
	$call_sign=$_GET['call_sign'];
	$vin=$_GET['vin'];
	$tipe_req_cont=$_GET['tipe_req_cont'];
	
?>

<script>
$(document).ready(function() 
{
	//======================================= autocomplete container==========================================//
	$( "#nc" ).autocomplete({
		minLength: 3,
		source: "request.delivery.sp2.auto/container?tipe_req_cont=<?=$tipe_req_cont;?>&req=<?=$req;?>&ship=<?=$ship;?>&call_sign=<?=$call_sign;?>&vin=<?=$vin;?>",
		focus: function( event, ui ) 
		{
			$( "#nc" ).val( ui.item.NO_CONTAINER);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#nc" ).val( ui.item.NO_CONTAINER);
			$( "#sc" ).val( ui.item.SIZE_CONT);
			$( "#tc" ).val( ui.item.TYPE_CONT);
			$( "#stc" ).val( ui.item.STATUS);
			$( "#iso" ).val( ui.item.ISO_CODE);
			$( "#hgc" ).val( ui.item.HEIGHT);
			$( "#car" ).val( ui.item.CARRIER);
			$( "#imo" ).val( ui.item.IMO);
			$( "#temp" ).val( ui.item.TEMP);
			$( "#hc" ).val( ui.item.HZ);
			$( "#comm" ).val( ui.item.COMODITY);
			$( "#ukk" ).val( ui.item.NO_UKK);
			$( "#ow" ).val( ui.item.OVER_WIDTH);
			$( "#oh" ).val( ui.item.OVER_HEIGHT);
			$( "#ol" ).val( ui.item.OVER_LENGTH);
			$( "#unnumber" ).val( ui.item.UN_NUMBER);
			$( "#booksl" ).val( ui.item.BOOKING_SL);
			$( "#pod" ).val( ui.item.POD);
			$( "#pol" ).val( ui.item.POL);
			$( "#pli" ).val( ui.item.PLUG_IN);
			$( "#dischdate" ).val( ui.item.VESSEL_CONFIRM);
			$( "#contdeldate" ).val( ui.item.DATE_DISCH);
            $("#contdeldate").datepicker({
			     dateFormat: 'dd-mm-yy',
                 minDate: ui.item.DATE_DISCH
            });
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NO_CONTAINER + "<br>" +item.SIZE_CONT+"<br>" +item.TYPE_CONT+"<br>" +item.STATUS+"<br>" +item.HZ+"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete container==========================================//
	
	//======================================= autocomplete commodity==========================================//
	
	$( "#comm" ).autocomplete({
		minLength: 3,
		source: "request.delivery.sp2.auto/commodity",
		focus: function( event, ui ) 
		{
			$( "#comm" ).val( ui.item.NM_COMMODITY);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#comm" ).val( ui.item.NM_COMMODITY);
			$( "#icomm" ).val( ui.item.KD_COMMODITY);

			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NM_COMMODITY + "<br>" +item.KD_COMMODITY+"</a>")
		.appendTo( ul );
	
	};
	$("#plo").datetimepicker({
			dateFormat: 'dd-mm-yy'
            });
	$("#pli").datetimepicker({
			dateFormat: 'dd-mm-yy'
            });
	//======================================= end autocomplete commodity==========================================//
	document.getElementById("nc").focus();

});
    
    
    
</script>

<table>
<tr>
		<td class="form-field-caption" align="right">No. Container</td>
		<td class="form-field-caption" align="right">:</td>
		<td colspan="13" valign="middle">
		<input type="text" size="11" id="nc" name="nc" style="background-color:#FFFFCC; font-weight:bold;font-size:24px;text-align:center"/> 
		<button onclick="add_cont1('<?=$req;?>','<?=$ship;?>')"><img src="<?=HOME;?>images/add_ct.png"/></button>
		</td>
</tr>
<tr>
		<td class="form-field-caption" align="right">Size </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="15" id="sc" name="sc"/>
		</td>
		<td class="form-field-caption" align="right">Type </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="15" id="tc" name="tc"/>
		</td>
		<td class="form-field-caption" align="right">Status </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="15" id="stc" name="stc"/>
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Height </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="15" id="hgc" name="hgc"/>
		</td>
		<td class="form-field-caption" align="right">OW </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="5" id="ow" name="ow"/>
		</td>
		<td class="form-field-caption" align="right">OH </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="5" id="oh" name="oh"/>
		</td>
		<td class="form-field-caption" align="right">OL </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="5" id="ol" name="ol"/>
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Temp </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="15" id="temp" name="temp"/>
			<input type="hidden" size="15" id="ukk" name="ukk"/>
		</td>
		<td class="form-field-caption" align="right">Hazzard - IMO</td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="5" id="hc" name="hc"/>
			<input type="text" size="5" id="imo" name="imo"/>
		</td>
		<td class="form-field-caption" align="right">UN Number</td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="5" id="unnumber" name="unnumber"/>
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Commodity </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="15" id="comm" name="comm"/>
			<input type="hidden" size="15" id="icomm"/>
		</td><td class="form-field-caption" align="right">Booking SL </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="10" id="booksl" name="booksl"/>
			<input type="hidden" size="10" id="pod" name="pod"/>
			<input type="hidden" size="10" id="pol" name="pol"/>
		</td>
	</tr>
    <tr>
		<td class="form-field-caption" align="right">Confirm Date </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="15" id="dischdate" name="dischdate" readonly/>
		</td><td class="form-field-caption" align="right">Delivery Date </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="10" id="contdeldate" name="contdeldate"/>
		</td>
	</tr>
    
	<TR>
		<td class="form-field-caption" align="right">ISO Code </td>
		<td class="form-field-caption" align="right">:</td>
		<td colspan="13">
			<input type="text" size="15" id="iso" name="iso"/>
		</td>
	</tr>
	<TR>
		<td class="form-field-caption" align="right">Carrier </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="15" id="car" name="car"/>
		</td>
		
				<td class="form-field-caption" align="right">Plug in - Plug Out</td>
		<td class="form-field-caption" align="right">:</td>
		<td >
			<input type="text" size="15" id="pli" name="pli"/> - <input type="text" size="15" id="plo" name="plo"/>
		</td>

	</tr>
	 
	</table>