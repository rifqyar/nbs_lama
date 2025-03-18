<?PHP	

$id_vs=$_GET['id_vessel'];		

$db=getDb();	
$header=$db->query("SELECT NM_KAPAL, VOYAGE_IN, VOYAGE_OUT, NO_UKK, NM_PEMILIK, TO_CHAR(TGL_JAM_TIBA,'DD-MM-YYYY HH24:mi') TGL_JAM_TIBA, TO_CHAR(TGL_JAM_BERANGKAT,'DD-MM-YYYY HH24:MI')TGL_JAM_BERANGKAT, TO_CHAR(OPEN_STACK,'DD-MM-YYYY HH24:MI') OPEN_STACK
FROM RBM_H WHERE NO_UKK='$id_vs'");
$header_r=$header->fetchRow();?>
<script>	
$("#tgl_op_stck").datetimepicker({			
dateFormat: 'dd-mm-yy',			            
timeFormat: 'hh:mm'            
});				

function set_ostc()	{		
var date_op=$('#tgl_op_stck').val();
var url="<?=HOME?>planning.booking.ajax/save_op";		
var no_ukk='<?=$id_vs?>';		
$.post(url,{ID_VS: no_ukk,DATE_OP:date_op 
},
function(data){	
		
		$('#open_stack').dialog('destroy').remove();	
			$('#dialog-form').append('<div id="open_stack"></div>');
		
			$("#booking").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=booking", datatype:"json"}).trigger("reloadGrid");	
		});	
}
</script><div align='left'><p>
	<img src="<?=HOME?>images/add_book.png" style="vertical-align:middle"> 	<b>
	<font color='#69b3e2' size='4px'>Open </font> </b>	 <font color='#888b8d' size='4px'>	 Stack	 </font>	</p></div>
	<br><br><table>	<tr>		<td>No. UKK</td>		<td>:</td>		
	<td><?=$header_r['NO_UKK']?></td>	</tr>	<tr>		
	<td>Vessel - voyage</td>		<td>:</td>	
	<td><?=$header_r['NM_KAPAL']?> &nbsp; <?=$header_r['VOYAGE_IN']?>-<?=$header_r['VOYAGE_OUT']?></td>
	</tr>	<tr>		<td>Shipping Line</td>		<td>:</td>		<td><?=$header_r['NM_PEMILIK']?></td>
	</tr>	<tr>		<td>ETA</td>		<td>:</td>		<td><?=$header_r['TGL_JAM_TIBA']?></td>	</tr>
	<tr>		<td>ETD</td>		<td>:</td>		<td><?=$header_r['TGL_JAM_BERANGKAT']?></td>	</tr>
	<tr>		<td>Open Stack</td>		<td>:</td>	
	<td><input type="text" id="tgl_op_stck" value='<?=$header_r['OPEN_STACK']?>' />
	<button onclick="set_ostc()">Set Open Stack</button></td>	</tr></table>