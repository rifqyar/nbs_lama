
<?php
$id=$_GET['id'];
$tgl = date('d F Y H:i');
$tanggal = date("dmY");

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=Report.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- TemplateBeginEditable name="doctitle" -->
<title>Report Reefer Revenue</title>
<!-- TemplateEndEditable -->
<!-- TemplateBeginEditable name="head" -->
<!-- TemplateEndEditable -->
</head>

<body>
		<table>
			<tr>
				<th colspan=14>
					REPORT REEFER REFENUE <?=$period;?>
				</th>
				</tr>
				<tr>
				<th colspan=14>
					TERMINAL OPERASI III
				</th>
				</tr>
		</table>
		<br>
		<table border="1">
		  <tr>
			<th>No.</th>
			<th>No. Container</th>
			<th>Size</th>
			<th>Type</th>
			<th>Status</th>
			<th>Vessel</th>
			<th>Voyage</th>
			<th>Plug in</th>
			<th>Plug out</th>
			<th>Jumlah Shift</th>
			<th>Uraian Biaya</th>
			<th>Jumlah Biaya</th>
			<th>No. Request</th>
			<th>No. Nota</th>
			<th>Tgl Cetak</th>
		  </tr>
		  <!--  -->
		  <?php
				$db=getDb();
				$query_h = "select NO_CONTAINER, SIZE_, TYPE_, E_I, VESSEL, VOYAGE, TO_CHAR(PLUGIN, 'MM-DD-YYYY HH24:MI:SS')PLUGIN, 
				TO_CHAR(PLUGOUT, 'MM-DD-YYYY HH24:MI:SS') PLUGOUT, JMLSHIFT,URAIAN, TOTTARIF, INVOICE_NUMB, INVOICE_PAYMENT, REQ_NUMBER  from rp_rfrrevenue where rp_rfrrevenueid=$id ORDER BY invoice_numb ASC";	
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
					<td><?=$row['E_I']?></td>
					<td><?=$row['VESSEL']?></td>
					<td><?=$row['VOYAGE']?></td>
					<td><?=$row['PLUGIN']?></td>
					<td><?=$row['PLUGOUT']?></td>
					<td><?=$row['JMLSHIFT']?></td>
					<td><?=$row['URAIAN']?></td>
					<td><?=$row['TOTTARIF']?></td>
					<td><?=$row['REQ_NUMBER']?></td>
					<td><?=$row['INVOICE_NUMB']?></td>
					<td><?=$row['INVOICE_PAYMENT']?></td>
					
				</tr>
				<?php
				$i++;
			}
			?>
		  <!--  -->		  
		</table>

</body>
</html>
