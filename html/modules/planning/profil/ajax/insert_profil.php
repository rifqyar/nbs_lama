<script>
	$(document).ready(function()
        {	
			document.getElementById('vs_a').style.display = 'none';
			document.getElementById('vs_ax').style.display = 'none';
			document.getElementById('tombol_vs').style.display = 'none';
			document.getElementById('uploads').style.display = 'inline';
			document.getElementById('uploadsx').style.display = 'inline';
			document.getElementById('tombol_upload').style.display = 'inline';
			
			var watermark = 'Autocomplete';						
			<!------------------- watermark vessel ------------>
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
			<!------------------- watermark vessel ------------>
		});
			
	$(function() {
			
			<!------------------- autocomplete vessel ------------>
			$( "#nm_kapal" ).autocomplete({
				minLength: 3,
				source: "<?=HOME?>planning.profil.auto/parameter",
				focus: function( event, ui ) {
					$( "#nm_kapal" ).val( ui.item.NM_KAPAL );
					return false;
				},
				select: function( event, ui ) {
					$( "#nm_kapal" ).val( ui.item.NM_KAPAL );
					$( "#kd_kapal" ).val( ui.item.KD_KAPAL );
					return false;
				}
			})
			.data( "autocomplete" )._renderItem = function( ul, item ) {
				return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + item.NM_KAPAL + " | " + item.KD_KAPAL + "</a>" )
					.appendTo( ul );
			};
			<!------------------- autocomplete vessel ------------>
			
		});
		
	function set_dropdown()
		{
			var profil_vs = $("#modus").val();
			
			if(profil_vs=='vs_assign')
			{
				document.getElementById('vs_a').style.display = 'inline';
				document.getElementById('vs_ax').style.display = 'inline';
				document.getElementById('tombol_vs').style.display = 'inline';
				document.getElementById('uploads').style.display = 'none';
				document.getElementById('uploadsx').style.display = 'none';
				document.getElementById('tombol_upload').style.display = 'none';
			}
			else
			{
				document.getElementById('vs_a').style.display = 'none';
				document.getElementById('vs_ax').style.display = 'none';
				document.getElementById('tombol_vs').style.display = 'none';
				document.getElementById('uploads').style.display = 'inline';
				document.getElementById('uploadsx').style.display = 'inline';
				document.getElementById('tombol_upload').style.display = 'inline';
			}
		}
	
</script>
<br>
<?
	$no_ukk = $_GET['id'];
?>
<form method="post" enctype="multipart/form-data" action="<?=HOME?>planning.profil/upload_save?id_vs=<?=$no_ukk?>">
		<table>
			<tr height='20'>
				<td class="form-field-caption" align="right"></td>
				<td class="form-field-caption" align="right"></td>
				<td></td>			
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Status</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<select id="modus" name="modus" onChange="set_dropdown()">
						<option value='new'>New</option>
						<option value='overwrite'>Overwrite</option>
						<option value='vs_assign'>Vessel Assign</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">
				<span id="uploads">Upload <i>(.xls)</i></span>
				<span id="vs_a">Vessel</span>
				</td>
				<td class="form-field-caption" align="right"> : </td>
				<td colspan='4'>
				<span id="uploadsx"><input type="file" name="uploadfile" size="20" />
                <input type="hidden" name="id_vs" value="<?=$no_ukk?>" />
				</span>				
				<span id="vs_ax">
					<input type="text" name="nm_kapal" id="nm_kapal" title="Autocomplete"/>
				<input type="hidden" name="kd_kapal" id="kd_kapal"/>
				</span>
				</td>		
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" align="right"> <span id="tombol_upload"><input type="submit" name="upload" value="&nbsp;Upload&nbsp;" /></span> 
											<span id="tombol_vs"><input type="button" name="proses" value="&nbsp;Proses&nbsp;" onclick="vessel_assign('<?=$no_ukk?>')"/>
				</td>
			</tr>
			<tr height='20'>
				<td class="form-field-caption" align="right"></td>
				<td class="form-field-caption" align="right"></td>
				<td></td>			
			</tr>
		</table>
	</form>