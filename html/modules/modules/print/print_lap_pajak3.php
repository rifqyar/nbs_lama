<?php
$p1=$_GET['p1'];
$p2=$_GET['p2'];

$filename = 'PAJAK_NOTA_PNPKLOLO_'.date('Ymd');
header('Content-Type: application/force-download');  
header('Content-disposition: attachment; filename='.$filename .'.xls');  
// Fix for crappy IE bug in download.  
header("Pragma: ");  
header("Cache-Control: ");  

echo 	 "<table border='1' bordercolor='#000000'>
			  <tr>
			    <th>NO</th>
			    <th>NPWP</th>
			    <th>NO ACCOUNT</th>
				<th>NAMA PERUSAHAAN</th>
				<th>NOMOR NOTA</th>
				<th>NOMOR SERI FAKTUR PAJAK</th>
				<th>TGL NOTA</th>
				<th>TAGIHAN</th>
				<th>PPN</th>
				<th>TOTAL TAGIHAN</th>
				<th>KETERANGAN</th>
				<th>NO JKM</th>
				<th>TGL JKM</th>
			  </tr>";
$db = getDB();
$query="SELECT * FROM VW_FAKTUR_BPRPLOLO A WHERE A.TGL_NOTA BETWEEN TO_DATE('$p1','DD-MM-YYYY') AND TO_DATE('$p2','DD-MM-YYYY') ORDER BY TGL_NOTA, TAX_NUMBER";
$res = $db->query($query);
$i=0;
while ($row = $res->fetchRow()) {
	$i++;
	echo 	   "<tr>
					<td align='right'>$i</td>
					<td>".$row[NPWP]."</td>
					<td align='center'>".$row[ID_CUST]."</td>
					<td>".$row[NM_CUST]."</td>
					<td>".$row[ID_NOTA]."</td>
					<td>".$row[TAX_NUMBER]."</td>
					<td align='center'>".$row[TGL_NOTA]."</td>
					<td align='right'>".$row[TAGIHAN]."</td>
					<td align='right'>".$row[PPN]."</td>
					<td align='right'>".$row[TOTAL]."</td>
					<td>".$row[KETERANGAN]."</td>
					<td>".$row[NO_JKM]."</td>
					<td align='right'>".$row[TGL_JKM]."</td>
				</tr>";
}
echo	"</table>";
?>