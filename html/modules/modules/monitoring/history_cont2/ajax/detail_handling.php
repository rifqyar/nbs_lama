<?php
	$voyin	= $_GET['voyin'];
	$voyout	= $_GET['voyout'];
	$ct		= $_GET['id_ct'];
	$ei		= $_GET['ei'];
	
	$db=getDb('dbint');
	
	$sq		=	"/* Formatted on 28-Apr-14 4:34:23 PM (QP5 v5.163.1008.3004) */
				  SELECT ACTIVITY AS KEGIATAN,
						 E_I,
						 TO_CHAR (TO_DATE (ACTIVITY_DATE, 'yyyymmddhh24miss'),
								  'dd-mm-yyyy hh24:mi')
							AS DATE_STATUS,
						 USER_OPR AS NM_USER,
						 '' AS KODE_STATUS
					FROM M_CYC_CONT_HISTORY
				   WHERE     no_container = '$ct'
						 AND VOYAGE_IN = '$voyin'
						 AND VOYAGE_OUT = '$voyout'
						 AND ACTIVITY IS NOT NULL
						 AND E_I = '$ei'
				ORDER BY ACTIVITY_DATE ASC";
	//print_r($sq);die;
	
	$eq=$db->query($sq);
	$rq=$eq->getAll();
?>
<style>
.std{
	
	font:italic bold 12px Helvetica;
	color:#6d6b6b;
	text-align:center;
	background-color:#ffffff;
	height:30px;
	border-bottom-style:solid;
	border-bottom-width:0.5px;
}
.std2{
	
	font:italic bold 12px Helvetica;
	color:#f91550;
	text-align:center;
	background-color:#ffffff;
	height:30px;
	border-bottom-style:solid;
	border-bottom-width:0.5px;
}

</style>

<table >
	<tr>
		<th class="grid-header" width="20">No</th>   
		<th class="grid-header" width="200">Handling</th>
		<th class="grid-header" width="100">Status code</th>
		<th class="grid-header" width="200">Update Time</th>
		<th class="grid-header" width="100">User</th>
    </tr>
	<?php
	$no=1;
	
	foreach ($rq as $row) {
	?>
	<tr>
		<td class="std"><?=$no;?></td>
		<td  class="std"><?=$row['KEGIATAN'];?></td>
		<td  class="std2"><?=$row['KODE_STATUS'];?></td>
		<td  class="std"><?=$row['DATE_STATUS'];?></td>
		<td  class="std"><?=$row['NM_USER'];?></td>
	</tr>
	<?php $no++; }?>
</table>