<?php
//utk menon-aktifkan template default
outputRaw();
$shipper		= strtoupper($_GET["term"]);
$db = getDB("dbint");

$query = "SELECT 
                ID_VSB_VOYAGE AS NO_UKK, 
                TRIM(VESSEL) NM_KAPAL, 
                VOYAGE_IN||'-'||VOYAGE_OUT AS VOYAGE, 
                OPERATOR_NAME AS 
                NM_PEMILIK, 
                TO_DATE(OPEN_STACK,'YYYYMMDDHH24MISS') AS OPEN_STACK, 
                TO_DATE(ETD,'YYYYMMDDHH24MISS') AS TGL_JAM_BERANGKAT,
                VOYAGE_IN,
                VOYAGE_OUT
          FROM 
                M_VSB_VOYAGE
          WHERE 
                VESSEL LIKE '%$shipper%' OR VOYAGE_IN LIKE '%$shipper%'
          ORDER BY 
                ETA DESC";
			
$result	= $db->query($query);
$row = $result->getAll();	

echo json_encode($row);
?>