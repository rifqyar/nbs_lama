<?php
$tgl = date('d F Y H:i');
$tanggal = date("dmY");
$tahun = $_GET['tahun'];
$inv = $_GET['inv'];
$curr = $_GET['curr'];


header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=SIMOPKPL2012_AR_".$tahun.".xls");
header("Pragma: no-cache");
header("Expires: 0");
$db = getDB('pyma');

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
					LAPORAN PYMA 2012 - KEGIATAN SIMOP 2012 - AR <?=$tahun?>
				</th>
				</tr>
		</table>
		<br>
		<table border="1">
		  <tr>
			<th>NO_NOTA</th>
			<th>PKK</th>
			<th>KETERANGAN</th>
			<th>AWAL_KEG</th>
			<th>AKHIR_KEG</th>
			<th>TGL_ENTRY_SIMOP</th>
			<th>TGL_PRANOTA_SIMOP</th>
			<th>AMOUNT_SIMOP (exclude PPN)</th>
			<th>CURRENCY</th>
			<th>TGL_NOTA_SIMKEU</th>
			<th>NOTA_SIMKEU</th>
			<th>FAKTUR_SIMKEU</th>
			<th>FAKTUR_BEBAS_SIMKEU</th>
			<th>AMOUNT_LINE_SIMKEU</th>
			<th>AMOUNT_PPN_SIMKEU</th>
			<th>TOTAL_SIMKEU</th>
			<th>YEAR_UP</th>
			<th>MONTH_UP</th>
		  </tr>
		  <!--  -->
		  <?php
				if($inv=='NI')
				{
					/*$query_h = "select a.NO_PRANOTA, a.PKK, a.KETERANGAN, a.AWAL_KEG,a.AKHIR_KEG,a.TGL_INPUT AS TGL_ENTRY_SIMOP,a.TGL_PRANOTA AS TGL_PRANOTA_SIMOP,a.AMOUNT AS AMOUNT_SIMOP, a.CURRENCY,
null AS TGL_NOTA_SIMKEU, null AS NOTA_SIMKEU, null AS FAKTUR_SIMKEU,  null AS FAKTUR_BEBAS_SIMKEU,null AS AMOUNT_LINE_SIMKEU,
null AS AMOUNT_PPN_SIMKEU, null AS TOTAL_SIMKEU , null as YEAR_UP, null as MONTH_UP
from rka_tab_exist_kpl a where a.currency='$curr'";*/			
					$query_h = "select a.NO_PRANOTA, a.PKK, a.KETERANGAN, a.AWAL_KEG,a.AKHIR_KEG,a.TGL_INPUT AS TGL_ENTRY_SIMOP,a.TGL_PRANOTA AS TGL_PRANOTA_SIMOP,a.JUMLAH AS AMOUNT_SIMOP, a.CURRENCY,
null AS TGL_NOTA_SIMKEU, null AS NOTA_SIMKEU, null AS FAKTUR_SIMKEU,  null AS FAKTUR_BEBAS_SIMKEU,null AS AMOUNT_LINE_SIMKEU,
null AS AMOUNT_PPN_SIMKEU, null AS TOTAL_SIMKEU , null as YEAR_UP, null as MONTH_UP
from rka_tab_exist_kpl a where a.currency='$curr'";
				}
				else if($inv=='I')
				{
					/*$query_h = "select a.NO_PRANOTA, a.PKK, a.KETERANGAN, a.AWAL_KEG,a.AKHIR_KEG,a.TGL_INPUT AS TGL_ENTRY_SIMOP,a.TGL_PRANOTA AS TGL_PRANOTA_SIMOP,a.AMOUNT AS AMOUNT_SIMOP, a.CURRENCY,
b.TRX_DATE AS TGL_NOTA_SIMKEU, b.TRX_NUMBER AS NOTA_SIMKEU, b.FAKTUR_KENA AS FAKTUR_SIMKEU,  b.FAKTUR_BEBAS AS FAKTUR_BEBAS_SIMKEU,b.AMOUNT_LINE AS AMOUNT_LINE_SIMKEU,
b.AMOUNT_PPN AS AMOUNT_PPN_SIMKEU, b.TOTAL AS TOTAL_SIMKEU , b.YEAR_UP, b.MONTH_UP
from RKA_TAB_ALL_KPL a, AR_SIMKEU_KPL b where trim(a.NO_PRANOTA)=trim(b.TRX_NUMBER) and a.status_akhir in ('4','5') and b.year_up='$tahun' and a.currency='$curr'";*/
					$query_h = "select a.NO_PRANOTA, a.PKK, a.KETERANGAN, a.AWAL_KEG,a.AKHIR_KEG,a.TGL_INPUT AS TGL_ENTRY_SIMOP,a.TGL_PRANOTA AS TGL_PRANOTA_SIMOP,a.JUMLAH AS AMOUNT_SIMOP, a.CURRENCY,
b.TRX_DATE AS TGL_NOTA_SIMKEU, b.TRX_NUMBER AS NOTA_SIMKEU, b.FAKTUR_KENA AS FAKTUR_SIMKEU,  b.FAKTUR_BEBAS AS FAKTUR_BEBAS_SIMKEU,b.AMOUNT_LINE AS AMOUNT_LINE_SIMKEU,
b.AMOUNT_PPN AS AMOUNT_PPN_SIMKEU, b.TOTAL AS TOTAL_SIMKEU , b.YEAR_UP, b.MONTH_UP
from RKA_TAB_ALL_KPL a, AR_SIMKEU_KPL b where trim(a.NO_PRANOTA)=trim(b.TRX_NUMBER) and a.status_akhir in ('4','5') and b.year_up='$tahun' and a.currency='$curr'";
				}
				else
				{
					/*$query_h = "select a.NO_PRANOTA, a.PKK, a.KETERANGAN, a.AWAL_KEG,a.AKHIR_KEG,a.TGL_INPUT AS TGL_ENTRY_SIMOP,a.TGL_PRANOTA AS TGL_PRANOTA_SIMOP,a.AMOUNT AS AMOUNT_SIMOP, a.CURRENCY,
null AS TGL_NOTA_SIMKEU, null AS NOTA_SIMKEU, null AS FAKTUR_SIMKEU,  null AS FAKTUR_BEBAS_SIMKEU,null AS AMOUNT_LINE_SIMKEU,
null AS AMOUNT_PPN_SIMKEU, null AS TOTAL_SIMKEU , null as YEAR_UP, null as MONTH_UP
from rka_tab_exist_kpl a where to_number(status_akhir)>0 and a.NO_PRANOTA is null";*/
					$query_h = "select a.NO_PRANOTA, a.PKK, a.KETERANGAN, a.AWAL_KEG,a.AKHIR_KEG,a.TGL_INPUT AS TGL_ENTRY_SIMOP,a.TGL_PRANOTA AS TGL_PRANOTA_SIMOP,a.JUMLAH AS AMOUNT_SIMOP, a.CURRENCY,
null AS TGL_NOTA_SIMKEU, null AS NOTA_SIMKEU, null AS FAKTUR_SIMKEU,  null AS FAKTUR_BEBAS_SIMKEU,null AS AMOUNT_LINE_SIMKEU,
null AS AMOUNT_PPN_SIMKEU, null AS TOTAL_SIMKEU , null as YEAR_UP, null as MONTH_UP
from rka_tab_exist_kpl a where to_number(status_akhir)>0 and a.NO_PRANOTA is null";	
				}
				
				//print_r($query_h);die;
				$result_h = $db->query($query_h);
				$res=$result_h->getAll();
				$i=1;
				foreach ($res as $row){
				
				?>
				<tr>
					<td><?=$row['NO_PRANOTA']?></td>
					<td><?=$row['PKK']?></td>
					<td><?=$row['KETERANGAN']?></td>
					<td><?=$row['AWAL_KEG']?></td>
					<td><?=$row['AKHIR_KEG']?></td>
					<td><?=$row['TGL_ENTRY_SIMOP']?></td>
					<td><?=$row['TGL_PRANOTA_SIMOP']?></td>
					<td><?=$row['AMOUNT_SIMOP']?></td>
					<td><?=$row['CURRENCY']?></td>
					<td><?=$row['TGL_NOTA_SIMKEU']?></td>
					<td><?=$row['NOTA_SIMKEU']?></td>
					<td><?=$row['FAKTUR_SIMKEU']?></td>
					<td><?=$row['FAKTUR_BEBAS_SIMKEU']?></td>
					<td><?=$row['AMOUNT_LINE_SIMKEU']?></td>
					<td><?=$row['AMOUNT_PPN_SIMKEU']?></td>
					<td><?=$row['TOTAL_SIMKEU']?></td>
					<td><?=$row['YEAR_UP']?></td>
					<td><?=$row['MONTH_UP']?></td>
				</tr>
				<?php
				$i++;
			}
			?>
		  <!--  -->		  
		</table>

</body>
</html>
