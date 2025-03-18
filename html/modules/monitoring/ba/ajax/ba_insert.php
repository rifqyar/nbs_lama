<script>
	$(document).ready(function()
        {
			document.getElementById('agen').style.display = 'none';
			document.getElementById('input_agen').style.display = 'none';
			document.getElementById('plg').style.display = 'inline';
			document.getElementById('input_plg').style.display = 'inline';
			
			var watermark = 'Autocomplete';						
			<!------------------- watermark pelanggan ------------>
			$('#pj').val(watermark).addClass('watermark');
			//if blur and no value inside, set watermark text and class again.
			$('#pj').blur(function(){
				if ($(this).val().length == 0){
					$(this).val(watermark).addClass('watermark');
				}
			});
		 
			//if focus and text is watermrk, set it to empty and remove the watermark class
			$('#pj').focus(function(){
				if ($(this).val() == watermark){
					$(this).val('').removeClass('watermark');
				}
		    });
			<!------------------- watermark pelanggan ------------>
			
			<!------------------- watermark agen ------------>
			$('#agen_kpl').val(watermark).addClass('watermark');
			//if blur and no value inside, set watermark text and class again.
			$('#agen_kpl').blur(function(){
				if ($(this).val().length == 0){
					$(this).val(watermark).addClass('watermark');
				}
			});
		 
			//if focus and text is watermrk, set it to empty and remove the watermark class
			$('#agen_kpl').focus(function(){
				if ($(this).val() == watermark){
					$(this).val('').removeClass('watermark');
				}
		    });
			<!------------------- watermark agen ------------>
			
		});
			
	$(function() {
			
			<!------------------- autocomplete pelanggan ------------>
			$( "#pj" ).autocomplete({
				minLength: 5,
				source: "<?=HOME?>monitoring.ba.auto/pelanggan",
				focus: function( event, ui ) {
					$( "#pj" ).val( ui.item.NAMA_PELANGGAN );
					return false;
				},
				select: function( event, ui ) {
					$( "#pj" ).val( ui.item.NAMA_PELANGGAN );
					$( "#kd_pj" ).val( ui.item.ACCOUNT_KEU );
					return false;
				}
			})
			.data( "autocomplete" )._renderItem = function( ul, item ) {
				return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + item.NAMA_PELANGGAN + " | " + item.KODE_PELANGGAN + " | " + item.ACCOUNT_KEU + "</a>" )
					.appendTo( ul );
			};
			<!------------------- autocomplete pelanggan ------------>
			
			
			<!------------------- autocomplete agen ------------>
			$( "#agen_kpl" ).autocomplete({
				minLength: 5,
				source: "<?=HOME?>monitoring.ba.auto/agen_kapal",
				focus: function( event, ui ) {
					$( "#agen_kpl" ).val( ui.item.NM_AGEN );
					return false;
				},
				select: function( event, ui ) {
					$( "#agen_kpl" ).val( ui.item.NM_AGEN );
					$( "#no_akun" ).val( ui.item.NO_ACCOUNT );
					return false;
				}
			})
			.data( "autocomplete" )._renderItem = function( ul, item ) {
				return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + item.NM_AGEN + " | " + item.KD_AGEN + " | " + item.NO_ACCOUNT + "</a>" )
					.appendTo( ul );
			};
			<!------------------- autocomplete agen ------------>
			
		});

	function set_dropdown()
		{
			var simop = $("#simop").val();
			
			if(simop=='kapal')
			{
				document.getElementById('agen').style.display = 'inline';
				document.getElementById('input_agen').style.display = 'inline';
				document.getElementById('plg').style.display = 'none';
				document.getElementById('input_plg').style.display = 'none';
			}
			else
			{
				document.getElementById('agen').style.display = 'none';
				document.getElementById('input_agen').style.display = 'none';
				document.getElementById('plg').style.display = 'inline';
				document.getElementById('input_plg').style.display = 'inline';
			}
		}
		
</script>

<br>
		<table>
			<tr height='20'>
				<td class="form-field-caption" align="right"></td>
				<td class="form-field-caption" align="right"></td>
				<td></td>			
			</tr>
			<tr>
				<td class="form-field-caption" align="right">NO REF HUMAS</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<input type="text" name="no_ref_humas" id="no_ref_humas" size="8"/>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">SIMOP</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<select id="simop" name="simop" onChange="set_dropdown()">
						<option value=''>-pilih-</option>
						<option value='kapal'>Kapal</option>
						<option value='barang'>Barang</option>
						<option value='petikemas'>Petikemas</option>
						<option value='rupa-rupa'>Rupa-Rupa</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right">Terminal</td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
					<select id="terminal" name="terminal">
						<option value=''>-pilih-</option>
						<option value='1'>1</option>
						<option value='2'>2</option>
						<option value='3'>3</option>
						<option value='nonterminal'>Non Terminal</option>
						<option value='-'>None</option>
					</select>
					&nbsp;<i>pilih <b>None</b> jika simop kapal</i>
				</td>
			</tr>
			<tr>
				<td class="form-field-caption" align="right"><span id="plg">PENGGUNA JASA</span><span id="agen">AGEN KAPAL</span></td>
				<td class="form-field-caption" align="right"> : </td>
				<td class="form-field-caption" align="left">
				<span id="input_plg">
					<input type="text" name="pj" id="pj" title="Autocomplete" size="30"/>
					<input type="hidden" name="kd_pj" id="kd_pj"/>
				</span>
				<span id="input_agen">
					<input type="text" name="agen_kpl" id="agen_kpl" title="Autocomplete" size="30"/>
					<input type="hidden" name="no_akun" id="no_akun"/>
				</span>
				</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3" align="right">				
				<input type="button" name="Insert BA" value="&nbsp;Insert&nbsp;" onclick="ba_entry()"/>
				&nbsp;
				<button onclick="sync_pelanggan()" title="sync master pelanggan"><img src='<?=HOME;?>images/Refresh2.png' width=15px height=15px border='0'></button>&nbsp;&nbsp;
				<button onclick="sync_agen()" title="sync master agen"><img src='<?=HOME;?>images/Refresh3.png' width=15px height=15px border='0'></button>
				</td>
			</tr>
			<tr height='20'>
				<td class="form-field-caption" align="right"></td>
				<td class="form-field-caption" align="right"></td>
				<td></td>			
			</tr>
		</table>		