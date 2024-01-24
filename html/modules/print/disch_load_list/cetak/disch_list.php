<?php
$p1=$_GET['p1'];

$filename = 'DISCHARGE_LIST_'.$p1;
header('Content-Type: application/force-download');  
header('Content-disposition: attachment; filename='.$filename .'.xls');  
// Fix for crappy IE bug in download.  
header("Pragma: ");  
header("Cache-Control: ");  

echo 	 "<table>
			  <tr>
				<th colspan='10' align='center'>DISCHARGING LIST</th>
			  </tr>
		  </table>";
echo 	 "<table border='1' bordercolor='#000000'>
			  <tr>
			    <th>NO</th>
			    <th>NO. CONTAINER</th>
			    <th>SIZE</th>
				<th>TYPE</th>
				<th>STATUS</th>
				<th>HZ</th>
				<th>IMO_CLASS</th>
				<th>BAYPLAN</th>
				<th>BAY</th>				
				<th>KETERANGAN</th>				
			  </tr>";
$db = getDB();
$query="SELECT NO_CONTAINER, SIZE_, TYPE_, STATUS, HZ, IMO, BAYPLAN, BAY
		FROM ISWS_LIST_CONTAINER WHERE NO_UKK='".$p1."' AND E_I='I'
		ORDER BY NO_CONTAINER";
$res = $db->query($query);
$i=0;
while ($row = $res->fetchRow()) {
	$i++;
	echo 	   "<tr>
					<td align='right'>$i</td>
					<td>".$row[NO_CONTAINER]."</td>
					<td align='center'>".$row[SIZE_]."</td>
					<td align='center'>".$row[TYPE_]."</td>
					<td align='center'>".$row[STATUS]."</td>
					<td align='center'>".$row[HZ]."</td>
					<td align='center'>".$row[IMO]."</td>
					<td align='right'>".$row[BAYPLAN]."</td>
					<td align='right'>".$row[BAY]."</td>					
					<td></td>					
				</tr>";
}
echo	"</table>";
?>