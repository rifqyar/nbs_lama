<?php
$filename = 'Cont_move_ekspor'.date('Ymd');
header('Content-Type: application/force-download');  
header('Content-disposition: attachment; filename='.$filename .'.xls');  
// Fix for crappy IE bug in download.  
header("Pragma: ");  
header("Cache-Control: ");  

echo 	 "<table border='1' bordercolor='#000000'>
			  <tr>
				<th>No UKK </th>
				<th>No Container</th>
				<th>Size</th>
				<th>Type</th>
				<th>Status</th>
				<th>Weight</th>
				<th>Class</th>
				<th>Temp</th>
				<th>POD</th>
				<th>Status Code</th>
				<th>Gate IN</th>
				<th>Gate Out</th>
				<th>Placement</th>
				<th>Plug In</th>
				<th>Plug Out</th>
				<th>Red Seal</th>
				<th>Consignee</th>
				<th>EMKL</th>
				<th>POS CY</th>
				<th>NPE</th>
				<th>No Seal</th>
				<th>Operator Ship</th>
			    
			  </tr>";
$db = getDB();
$query="SELECT NO_UKK, ID_CONT , SIZE_, TYPE_, STATUS, WEIGHT, CLASS_, TEMP, POD, ID_STATUS, GATE_IN, GATE_OUT, PLACEMENT, PLUG_IN, PLUG_OUT, STAT_SEGEL, CONSIGNEE, 
		EMKL, POS_CY, 	NPE, NO_SEAL, OPERATOR_SHIP 
		FROM MX_RBM_DETAIL
		WHERE REMARK_ ='R'
		ORDER BY ID_CONT";
$res = $db->query($query);
$i=0;
while ($row = $res->fetchRow()) {
	$i++;
	echo 	   "<tr>
					<td align='right'>$i</td>
					<td>".$row[NO_UKK]."</td>
					<td align='center'>".$row[ID_CONT]."</td>
					<td>".$row[SIZE_]."</td>
					<td>".$row[TYPE_]."</td>
					<td>".$row[STATUS]."</td>
					<td align='center'>".$row[WEIGHT]."</td>
					<td align='right'>".$row[CLASS_]."</td>
					<td align='right'>".$row[TEMP]."</td>
					<td align='right'>".$row[POD]."</td>
					<td align='right'>".$row[GATE_IN]."</td>
					<td align='right'>".$row[GATE_OUT]."</td>
					<td align='right'>".$row[PLACEMENT]."</td>
					<td align='right'>".$row[PLUG_IN]."</td>
					<td align='right'>".$row[PLUG_OUT]."</td>
					<td align='right'>".$row[STAT_SEGEL]."</td>
					<td align='right'>".$row[CONSIGNEE]."</td>
					<td align='right'>".$row[EMKL]."</td>
					<td align='right'>".$row[POS_CY]."</td>
					<td align='right'>".$row[NPE]."</td>
					<td align='right'>".$row[NO_SEAL]."</td>
					<td align='right'>".$row[OPERTOR_SHIP]."</td>
				</tr>";
}
echo	"</table>";
?>