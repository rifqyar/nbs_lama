<?php
//utk menon-aktifkan template default
outputRaw();
$plpno		= strtoupper($_GET["term"]);

	$db = getDB();
	$query = "select VESSEL, 
					 VOYAGE_IN AS VOY_IN,
					 VOYAGE_OUT AS VOY_OUT,
					 TPL1,
					 TPL2,
					 BC_NUMB,
					 TO_CHAR(BC_DATE,'DD-MM-YYYY') BC_DATE,
					 ID_PLP,
					 TO_CHAR(ATA,'DD-MM-YYYY') ATA,
					 TO_CHAR(ATD,'DD-MM-YYYY') ATD,
					 TRIM(NO_PLP) PLP_NUMB
			   from OBX_APPROVAL_H 
			   WHERE FLAG_USED = '0' 
			   AND TRIM(ID_PLP) LIKE '%$plpno%'
			   ORDER BY ID_PLP DESC";

$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>