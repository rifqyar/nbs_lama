<script type="text/javascript">
        $(document).ready(function() 
		{
			$( "#kategori" ).autocomplete({
				minLength: 3,
				source: "maintenance.viewer.auto/parameter",
				focus: function( event, ui ) {
					$( "#kategori" ).val( ui.item.KODE);
					return false;
				},
				select: function( event, ui ) {
					$( "#kategori" ).val( ui.item.NAMA);
					$( "#id_kategori" ).val( ui.item.ID);
					$( "#id_kategori2" ).val( ui.item.INFO);
					return false;
				}
			})
			.data( "autocomplete" )._renderItem = function( ul, item ) {
				return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + item.NAMA + "<br />" + item.INFO + "</a>")
					.appendTo( ul );
			};
	});
</script>


<br /><br />
<h2 align="center">Layout Lapangan Ex TBB</h2>
<fieldset>
<table>
     <tr height="20"><td colspan="2"></td></tr>

    <tr>
			<td class="grid-cell" valign="top" style="font-size:12px;color:#000000" width='200' align="right" >
			Preview lap berdasarkan
			</td>
			<td>
				 : <select name="based" id='based'>
				<option value="">-- Pilih -- </option>
                  <option value="kapal">Kapal</option>
				  <option value="consignee">Consignee</option>
				  <option value="size">Ukuran</option>
                </select>
			</td>
		</tr>
		<tr>
			<td class="grid-cell" valign="top" style="font-size:12px;color:#000000" width='200' align="right" >
			Masukkan parameter 
			</td>
			<td>
                            <input type="text" name="kategori" id="kategori" size="20"/>
                             <input type="hidden" name="id_kategori" id="id_kategori"/>
                              <input type="hidden" name="id_kategori2" id="id_kategori2"/>		
                          <input type="submit" onclick="hasil()" value=" View " />

			</td>		</tr>
  <tr height="20"><td colspan="2"></td></tr>
</table>
</fieldset>
 <div id="layout">
    
    </div>


<script>

$("#layout").load('<?=HOME?>maintenance.viewer/layout_lapangan #list', function(data) {        	  
	}); 
</script>