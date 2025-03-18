<?php
 $u = $_GET['id']; 
  $db = getDB();
 $query = "SELECT SIZE_CONT, TYPE_CONT, STATUS_CONT, (BOX+NVL(BOX_EXTRA,0)) AS BOX,ID_PEL_TUJ, ID_USER,CREATE_DATE,
 case when PLAN_STATUS = 0 then 'unplan'
 else 
	'already plan'
	end PLAN_STATUS
 , TEUS, PELABUHAN_TUJUAN, case when E_I = 'E' then 'Export'
 else 
	'Import'
	end E_I
 FROM TB_BOOKING_CONT_AREA WHERE ID_VS = '$u'";
 $query_ = $db->query($query);
 $data   = $query_->getAll();
?>
	<table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%">
	  <tr>
		<th class="grid-header">SIZE - TYPE - STATUS</th>   
		<th class="grid-header">TOTAL BOX-TEUS</th>
		<th class="grid-header">POD - CODE</th>
		<th class="grid-header">USER ID</th>
		<th class="grid-header">DATE PLAN</th>
		<th class="grid-header">PLAN STATUS</th>
     </tr>
	<? 
		foreach ($data as $row)
		{
	?>
	 <tr>
		<td align='center'><?=$row['SIZE_CONT']?> &nbsp; &nbsp;<?=$row['TYPE_CONT']?> &nbsp; &nbsp;<?=$row['STATUS_CONT']?></td>
		<td align='center'><?=$row['BOX']?>&nbsp; box &nbsp; <?=$row['TEUS']?> &nbsp; teus</td>
		<td align='center'><?=$row['ID_PEL_TUJ']?></td>
		<td align='center'><?=$row['ID_USER']?></td>
		<td align='center'><?=$row['CREATE_DATE']?></td>
		<td align='center'><?=$row['PLAN_STATUS']?></td>
	 </tr>
	<?	
		}	
	?>
	</table>
	
