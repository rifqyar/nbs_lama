<?PHP	

$id_vs=$_GET['id_vessel'];		
$db=getDb();	
$header=$db->query("SELECT NM_KAPAL, VOYAGE_IN, VOYAGE_OUT, NO_UKK, NM_PEMILIK, TO_CHAR(TGL_JAM_TIBA,'DD-MM-YYYY HH24:mi') TGL_JAM_TIBA, TO_CHAR(TGL_JAM_BERANGKAT,'DD-MM-YYYY HH24:MI')TGL_JAM_BERANGKAT, TO_CHAR(CLOSING_TIME,'DD-MM-YYYY HH24:MI') OPEN_STACK,
TO_CHAR(CLOSING_TIME_DOC,'DD-MM-YYYY HH24:MI') CL_DOC
FROM RBM_H WHERE NO_UKK='$id_vs'");
$header_r=$header->fetchRow();?>
<script>	

$("#tgl_op_stck").datetimepicker({
			dateFormat: 'dd-mm-yy',			
            timeFormat: 'hh:mm'          
});				
$("#tgl_cl_doc").datetimepicker({			
dateFormat: 'dd-mm-yy',			            
timeFormat: 'hh:mm'            
});	
function set_ostc()	{		

var date_op=$('#tgl_op_stck').val();	
var cl_doc=$('#tgl_cl_doc').val();	
var url="<?=HOME?>planning.booking.ajax/save_cl";		

var no_ukk='<?=$id_vs?>';		

if(date_op=='')
{
	alert ('Entry closing time, please..');
	return false;
}
else if(cl_doc=='')
{
	alert ('Entry closing time for document, please..');
	return false;
}
else{
	$.post(url,{ID_VS: no_ukk,DATE_OP:date_op,CL_DOC:cl_doc },function(data){			
		$('#closing_time').dialog('destroy').remove();	
			$('#dialog-form').append('<div id="closing_time"></div>');		
		//document.location.reload(true);			
		$("#booking").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=booking", datatype:"json"}).trigger("reloadGrid");		
	});	
	}
}
</script>
<div align='left'><p>	<img src="<?=HOME?>images/add_book.png" style="vertical-align:middle"> 	<b> <font color='#69b3e2' size='4px'>Closing </font> </b>	 <font color='#888b8d' size='4px'>	 Time	 </font>	</p></div><br><br><table>	<tr>		<td>No. UKK</td>		<td>:</td>		<td><?=$header_r['NO_UKK']?></td>	</tr>	<tr>		<td>Vessel - voyage</td>		<td>:</td>		<td><?=$header_r['NM_KAPAL']?> &nbsp; <?=$header_r['VOYAGE_IN']?>-<?=$header_r['VOYAGE_OUT']?></td>	</tr>	<tr>		<td>Shipping Line</td>		<td>:</td>		<td><?=$header_r['NM_PEMILIK']?></td>	</tr>	<tr>		<td>ETA</td>		<td>:</td>		<td><?=$header_r['TGL_JAM_TIBA']?></td>	</tr>	<tr>		<td>ETD</td>		<td>:</td>		<td><?=$header_r['TGL_JAM_BERANGKAT']?></td>	</tr>	
<tr>
<td>Closing time</td>
<td>:</td>
<td><input type="text" id="tgl_op_stck" value='<?=$header_r['OPEN_STACK']?>' />
</td>
</tr>
<tr>
<td>Closing time doc.</td>
<td>:</td>
<td><input type="text" id="tgl_cl_doc" value='<?=$header_r['CL_DOC']?>' />
</td>
</tr>
<tr>
<td>
<button onclick="set_ostc()">Set closing time</button></td>
</tr></table>
</tr></table>