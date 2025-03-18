<h2>Container Synchronization</h2><br/><font color="red"><b><i>2 steps to synchronize unavailable container</i></b></font>
<br>
<br>
<br>
<table>
	<tr>
		<td colspan="5"><B><i><font color="#545555">1. Entry the container number</font></i></B></td>
	</tr>
	<tr>
		<td valign="middle" class="form-field-input">Container Numb</td>
		<td valign="middle" class="form-field-input">:</td>
		<td valign="middle" class="form-field-input"><input style="font-size:26px; font-weight:bold;" id="cont_numb" name="cont_numb" size="15" title="entry" class="suggestuwriter" type="text" maxlength="16" value="" /> &nbsp; </td>
		<td valign="middle" class="form-field-input"></td>
	</tr>
	<tr>
		<td valign="middle" class="form-field-input">Vessel / Voyage</td>
		<td valign="middle" class="form-field-input">:</td>
		<td valign="middle" class="form-field-input"><input style="font-size:18px; font-weight:bold;" id="pelabuhan" name="pelabuhan" size="20" title="entry" class="suggestuwriter" type="text" maxlength="16" value="" />
		<input style="font-size:18px; font-weight:bold;" id="voy_a" name="voy_a" size="10" title="entry" class="suggestuwriter" type="text" maxlength="16" value="" />	
		<input style="font-size:18px; font-weight:bold;" id="no_ukk" name="no_ukk" size="10" title="entry" class="suggestuwriter" type="hidden" maxlength="16" value="" />		</td>
		<td valign="middle" class="form-field-input"><input type="button" name="find_c" id="find_c" value="SYNC" onclick="sync_cont()" style="height:35px; width:50px;" class="context-menu-one	"></td>
	</tr>
	<tr>
		<td colspan="4" align="left"><B><i><font color="#00b5dc">2. Click Sync to Synchronize</font></i></B></td>
	</tr>
</table>

<script type='text/javascript'>
$(document).ready(function() 
{	
    $( "#pelabuhan" ).autocomplete({
		minLength: 3,
		source: "<?=HOME?>planning.booking.auto/vessel",
		focus: function( event, ui ) {
			$( "#pelabuhan" ).val( ui.item.NM_KAPAL);
			return false;
		},
		select: function( event, ui ) {
			$( "#pelabuhan" ).val( ui.item.NM_KAPAL);
            $( "#no_ukk" ).val( ui.item.NO_UKK);
			$( "#voy_a" ).val( ui.item.VOYAGE);
			return false;
		}
	})
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a>" + item.NM_KAPAL + " | " + item.VOYAGE + "</a>")
			.appendTo( ul );
	};  
});

</script>