<?php
	$req=$_GET['id'];
	$ship=$_GET['ship'];
?>

<script>
$(document).ready(function() 
{
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
	//======================================= autocomplete commodity==========================================//
	
	//======================================= autocomplete container==========================================//
	$( "#nc" ).autocomplete({
		minLength: 3,
		source: "request.delivery.sp2.auto/container?ship=<?=$ship;?>",
		focus: function( event, ui ) 
		{
			$( "#nc" ).val( ui.item.NO_CONTAINER);
			return false;
		},
		select: function( event, ui ) 
		{
			$( "#nc" ).val( ui.item.NO_CONTAINER);
			$( "#sc" ).val( ui.item.SIZE_);
			$( "#tc" ).val( ui.item.TYPE_);
			$( "#stc" ).val( ui.item.STATUS);
			$( "#iso" ).val( ui.item.ISO_CODE);
			$( "#hgc" ).val( ui.item.HEIGHT);
			$( "#car" ).val( ui.item.CARRIER);
			$( "#imo" ).val( ui.item.IMO);
			$( "#temp" ).val( ui.item.TEMP);
			$( "#hc" ).val( ui.item.HZ);
			$( "#comm" ).val( ui.item.COMODITY);
			$( "#ukk" ).val( ui.item.NO_UKK);
			
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) 
	{
		return $( "<li></li>" )
		.data( "item.autocomplete", item )
		.append( "<a align='center'>" + item.NO_CONTAINER + "<br>" +item.SIZE_+"<br>" +item.TYPE_+"<br>" +item.STATUS_+"<br>" +item.HZ+"</a>")
		.appendTo( ul );
	
	};
	//======================================= autocomplete container==========================================//
});
</script>

<table>
<tr>
		<td class="form-field-caption" align="right">No. Container</td>
		<td class="form-field-caption" align="right">:</td>
		<td colspan="13" valign="middle">
		<input type="text" size="11" id="nc" onblur="cek_iso()" name="nc" style="background-color:#FFFFCC; font-weight:bold;font-size:24px;text-align:center"/> 
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
		<td class="form-field-caption" align="right">Hazzard - IMO</td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="15" id="hc" name="hc"/>
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Temp </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="15" id="temp" name="temp"/>
			<input type="hidden" size="15" id="ukk" name="ukk"/>
		</td>
	</tr>
	<tr>
		<td class="form-field-caption" align="right">Commodity </td>
		<td class="form-field-caption" align="right">:</td>
		<td>
			<input type="text" size="15" id="comm" name="comm"/>
			<input type="hidden" size="15" id="icomm"/>
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
		<td colspan="13">
			<input type="text" size="15" id="car" name="car"/>
		</td>
	</tr>
	 
	</table>