<?php
$tgl = date('d F Y H:i');
$tanggal = date("dmY");
$no_ukk = $_GET['no_ukk'];
$kg = $_GET['keg'];

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=List_".$no_ukk.".xls");
header("Pragma: no-cache");
header("Expires: 0");
$db = getDB();

$query_p = "SELECT NM_KAPAL,VOYAGE_IN, VOYAGE_OUT, NM_PEMILIK FROM RBM_H WHERE NO_UKK='$no_ukk'";	
$result_p = $db->query($query_p);
$rowe=$result_p->fetchRow();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- TemplateBeginEditable name="doctitle" -->
<title>Untitled Document</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>

<body>
<?php

 ?>		
		<table>
			<tr>
				<th colspan=16>
					LIST CONTAINER BY STATUS <?=$rowe['NM_KAPAL']?> <?=$rowe['VOYAGE_IN']?> - <?=$rowe['VOYAGE_OUT']?>
				</th>
				</tr>
				<tr>
				<th colspan=16>
					<?=$rowe['NM_PEMILIK']?>
				</th>
			</tr>
		</table>
		<br>
		<table border="1">
		  <tr>
			<th>No.</th>
			<th>Container Numb</th>
			<th colspan="4">Sz - Typ - Sts - Hz</th>
			<th>Kode Status</th>
			<th>Gate In - Out</th>
			<th>Yard Allocation</th>
			<th>Date Placement</th>
			<th colspan="4">Yard Placement</th>
			<th>Bay Plan</th>
			<th>Date Loading - Disch. Confirm</th>
		  </tr>
		  <!--  -->
		  <?php
			
				$query_h = "SELECT NO_CONTAINER, SIZE_, TYPE_, STATUS,HZ, KODE_STATUS, TO_CHAR(TGL_GATE_IN,'DD-MM-YYYY HH24:MI') 
				TGL_GATE_IN,ID_JOBSLIP,LOKASI,TO_CHAR(TGL_PLACEMENT,'DD-MM-YYYY HH24:MI') TGL_PLACEMENT, BLOCK,
				SLOT, ROW_,TIER, BAY, TO_CHAR(DATE_CONFIRM,'DD-MM-YYYY HH24:MI') DATE_CONFIRM FROM  ISWS_LIST_CONTAINER
				WHERE NO_UKK='$no_ukk' and E_I='$kg' and kode_status <> 'NA' ORDER BY TGL_PLACEMENT DESC";	
				$result_h = $db->query($query_h);
				$res=$result_h->getAll();
				$i=1;
				foreach ($res as $row){
				
				?>
				<tr>
					
					<td><?=$i?></td>
					<td><?=$row['NO_CONTAINER']?></td>
					<td><?=$row['SIZE_']?></td>
					<td><?=$row['TYPE_']?></td>
					<td><?=$row['STATUS']?></td>
					<td><?=$row['HZ']?></td>
					<td><?=$row['KODE_STATUS']?></td>
					<td><?=$row['TGL_GATE_IN']?>&nbsp;<?=$row['ID_JOBSLIP']?></td>
					<td><?=$row['LOKASI']?></td>
					<td><?=$row['TGL_PLACEMENT']?></td>
					<td><?=$row['BLOCK']?></td>
					<td><?=$row['SLOT']?></td>
					<td><?=$row['ROW_']?></td>
					<td><?=$row['TIER']?></td>
					<td><?=$row['BAY']?></td>
					<td><?=$row['DATE_CONFIRM']?></td>
				</tr>
				<?php
				$i++;
			}
			?>
		  <!--  -->		  
		</table>

</body>
</html>
