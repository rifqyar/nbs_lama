	<script>	
		$(document).ready(function()
        {		
			document.getElementById('block_options').style.display = 'none';
		});

		$(document).ready(function()
        {		
			var watermark = 'Autocomplete';						
			<!------------------- watermark vesvoy ------------>
			$('#nm_kapal').val(watermark).addClass('watermark');
			//if blur and no value inside, set watermark text and class again.
			$('#nm_kapal').blur(function(){
				if ($(this).val().length == 0){
					$(this).val(watermark).addClass('watermark');
				}
			});
		 
			//if focus and text is watermrk, set it to empty and remove the watermark class
			$('#nm_kapal').focus(function(){
				if ($(this).val() == watermark){
					$(this).val('').removeClass('watermark');
				}
		    });
			<!------------------- watermark vesvoy ------------>
			
		});
		
		$(function() {
			
			<!------------------- autocomplete vesvoy ------------>
			$( "#nm_kapal" ).autocomplete({
				minLength: 3,
				source: "<?=HOME?>maintenance.allo_monitoring.auto/parameter",
				focus: function( event, ui ) {
					$( "#nm_kapal" ).val( ui.item.NM_KAPAL );
					return false;
				},
				select: function( event, ui ) {
					$( "#nm_kapal" ).val( ui.item.NM_KAPAL );
					$( "#id_kapal" ).val( ui.item.NO_UKK );
					$( "#voyage" ).val( ui.item.VOYAGE );
					return false;
				}
			})
			.data( "autocomplete" )._renderItem = function( ul, item ) {
				return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + item.NM_KAPAL + " | " + item.VOYAGE + "</a>" )
					.appendTo( ul );
			};
			<!------------------- autocomplete vesvoy ------------>
			
});	
			
		function set_filter()
		{
			var yd_filter = $("#yard_select").val();
			
			if(yd_filter=='block')
			{
				document.getElementById('block_options').style.display = 'inline';
			}
			else
			{
				document.getElementById('block_options').style.display = 'none';
			}
		}	
		
	</script>
	<table>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
		<tr>				
			<td class="form-field-caption" align="right">Vessel/Voy</td>
			<td class="form-field-caption" align="right"> : </td>
			<td colspan='4'>
				<input type="text" name="nm_kapal" id="nm_kapal" title="Autocomplete"/>
				<input type="hidden" name="id_kapal" id="id_kapal"/>
				<input type="hidden" name="voyage" id="voyage"/>
			</td>			
		</tr>
		<tr>				
			<td class="form-field-caption" align="right">Yard</td>
			<td class="form-field-caption" align="right"> : </td>
			<td colspan='4'>
				<select name="yard_select" id="yard_select" onChange="set_filter()">
					<option value="">-Pilih-</option>
					<option value="all1">ALL I</option>
					<option value="all2">ALL II</option>
					<option value="block">Per Block</option>				
				</select>&nbsp;&nbsp;&nbsp;
				<span id="block_options">
					<select name="block_select" id="block_select">
					<option value="">-Pilih-</option>				
					<?
						$db = getDB();
						$query_get_block = "SELECT ID,NAME FROM YD_BLOCKING_AREA WHERE ID_YARD_AREA = '81' ORDER BY ID ASC";
						$result_block    = $db->query($query_get_block);
						$row_block       = $result_block->getAll();
						
						foreach ($row_block as $row5) {
							$nm_block = $row5['NAME'];;
					?>				
							<option value="<?=$row5['ID'];?>,<?=$nm_block?>"><?=$nm_block?></option>
							
					<? } ?>					
				</select>
				</span>
			</td>			
		</tr>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
		<tr>
			<td colspan='6' align="center"><input type="button" name=" cetak " onclick="print()" value="cetak" /></td>			
		</tr>
		<tr height='20'>
			<td class="form-field-caption" align="right"></td>
			<td class="form-field-caption" align="right"></td>
			<td></td>			
		</tr>
	</table>