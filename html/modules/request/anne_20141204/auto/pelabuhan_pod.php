<?php
$pel		= strtoupper($_GET["term"]);
$vessel = $_GET["vessel"];
$voy = $_GET["voy"];
$voyo = $_GET["voyo"];
$db 			= getDB(dbint);

//$query			= "SELECT CDG_PORT_CODE as ID_PEL, CDG_PORT_NAME as PELABUHAN, ' ' as NAMA_NEG FROM CDG_PORT WHERE (CDG_PORT_CODE LIKE '%$pel%' OR CDG_PORT_name LIKE '%$pel%') AND ROWNUM < 4";
$query = "SELECT CDG_PORT_CODE AS ID_PEL, CDG_PORT_NAME AS PELABUHAN, ' ' AS NAMA_NEG
  FROM CDG_PORT
 WHERE     (CDG_PORT_CODE LIKE '%$pel%' OR CDG_PORT_name LIKE '%$pel%')
       AND ROWNUM < 4
       AND CDG_PORT_CODE IN (SELECT VSB_VOYP_PORT
                               FROM    M_VSB_VOYAGE_PORT A
                                    INNER JOIN
                                       M_VSB_VOYAGE B
                                    ON (A.VSB_VOYP_VESSEL = B.VESSEL_CODE AND A.VSB_VOYP_VOYAGE = B.VOYAGE)
                              WHERE TRIM (UPPER (B.VESSEL)) = TRIM (UPPER ('$vessel'))
									AND TRIM (UPPER (B.VOYAGE_IN)) = TRIM (UPPER ('$voy'))
                                    AND TRIM (UPPER (B.VOYAGE_OUT)) = TRIM (UPPER ('$voyo'))
							  )";
//$query 			= "SELECT PELABUHAN, ID_PEL, NAMA_NEG FROM MASTER_PELABUHAN WHERE PELABUHAN LIKE '%$pel%' OR ID_PEL LIKE '%$pel%' AND ROWNUM = 4";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>