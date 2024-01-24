
<fieldset style="border-radius: 10px 10px 10px 10px;-moz-border-radius: 10px 10px 10px 10px; 
-webkit-border-radius: 10px 10px 10px 10px; padding: 5px">
<legend><font color="blue"> &nbsp   <b>Grouping Category</b>   &nbsp</font></legend>
<form name="frm1" action="<?=HOME?>planning.yard_allocation_import.ajax/save_master">
  <table style="font-size: 12px; font-weight: bold;"  border="0">
  <?$ukk = $_GET['NO_UKK'];?>
            <tr align='center'>
				<td>NO</td>
				<td width='10'>SIZE</td>
				<td width='10'>TYPE</td>
				<td width='10'>STATUS</td>
				<td width='10'>HEIGHT</td>
				<td width='10'>HZ</td>
				<td width='10'>PLUG</td>
				<td width='20'>REQUIRED</td>
				<td width='40'>ACTION</td>
			</tr>
			<input type='hidden' name='noukk' value='<?=$ukk;?>'>
		   <? $db = getDB();
			   
		      $query 	= "SELECT * FROM TB_BOOKING_CONT_AREA WHERE ID_VS = '$ukk' AND E_I = 'I'";
			  $result	= $db->query($query);
			  $data		= $result->getAll();
		   $i = 1;
			foreach($data as $row){?>
				<tr align='center'>
				<td><?=$i?></td>
				<td><?=$row['SIZE_CONT'];?></td>
				<td><?=$row['TYPE_CONT'];?></td>
				<td><?=$row['STATUS_CONT'];?></td>
				<td><?=$row['HEIGHT'];?></td>
				<td><?=$row['HZ'];?></td>
				<td><?=$row['PLUG'];?></td>
				<td><?=$row['TEUS'];?> TEUS</td>
				<td><select name="kategori">
					  <option value="0<?=$row['ID_BOOK'];?>">--PILIH KATEGORI--</option>
					  <option value="1_<?=$row['ID_BOOK'];?>">Kategori 1</option>
					  <option value="2_<?=$row['ID_BOOK'];?>">Kategori 2</option>
					  <option value="3_<?=$row['ID_BOOK'];?>">Kategori 3</option>
					  <option value="4_<?=$row['ID_BOOK'];?>">Kategori 4</option>
					  <option value="5_<?=$row['ID_BOOK'];?>">Kategori 5</option>
					  <option value="6_<?=$row['ID_BOOK'];?>">Kategori 6</option>
					  <option value="7_<?=$row['ID_BOOK'];?>">Kategori 7</option>
					  <option value="8_<?=$row['ID_BOOK'];?>">Kategori 8</option>
					  <option value="9_<?=$row['ID_BOOK'];?>">Kategori 9</option>
					  <option value="10_<?=$row['ID_BOOK'];?>">Kategori 10</option>
					</select>  
				</td>
			</tr>
			
			<?$i++;}?>
			
				<tr align='center'>
				<td colspan='9'><input type="submit" value="Save">
				</td>
			</tr>
			
		</table>
		</form>
		</fieldset>
<script>
function save_master()	{	
var kat  = new Array();
var kat  = $('#kategori').val();
var noukk  = '<?=$ukk?>';
var url="<?=HOME?>planning.yard_allocation_import.ajax/save_master?kate="+kat+"&noukk="+noukk;		
	
$.post(url,{kategori: kat},function(data){	
		$(this).dialog("close"); 	
		//alert(kat);
		//document.location.reload(true);
		$("#master_grouping").jqGrid('setGridParam',{url:"<?=HOME?>datanya/data?q=master_grouping", datatype:"json"}).trigger("reloadGrid");	
		});	
}
</script>
   