<?php
$no_truck		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
/*$query 			= "SELECT a.NO_CONTAINER, a.NO_REQUEST, b.SIZE_, a.STATUS , b.TYPE_, c.NO_NOTA, TO_CHAR(c.END_STACK, 'dd/mm/yyyy') END_STACK , e.NAMA
                            FROM MASTER_CONTAINER b, CONTAINER_DELIVERY a, NOTA_DELIVERY c, REQUEST_DELIVERY d, MASTER_PBM e
                            WHERE a.NO_CONTAINER = b.NO_CONTAINER
                            AND a.NO_REQUEST = c.NO_REQUEST
                            AND a.NO_REQUEST = d.NO_REQUEST
                            AND d.ID_PEMILIK = e.ID
                            AND b.LOCATION = 'IN_YARD'
                            AND b.NO_CONTAINER LIKE '%$no_cont%'
							AND a.AKTIF = 'Y'
                            ORDER BY a.NO_REQUEST";
*/
$query			= "SELECT NO_CONTAINER, NO_REQUEST FROM GATE_OUT WHERE NOPOL LIKE '%$no_truck%' AND TGL_IN = (SELECT MAX(TGL_IN) FROM gate_out WHERE NOPOL LIKE '%$no_truck%')";
$result			= $db->query($query);
$row			= $result->fetchRow();	
$no_cont		= $row["NO_CONTAINER"];
$no_req			= $row["NO_REQUEST"];



$query			= " SELECT a.NO_CONTAINER, a.NO_REQUEST, b.SIZE_, a.STATUS , b.TYPE_, c.NO_NOTA, TO_CHAR(c.END_STACK, 'dd/mm/yyyy') END_STACK , e.NM_PBM NAMA, TO_CHAR(d.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, f.NOPOL, f.NO_SEAL, f.KETERANGAN
                            FROM MASTER_CONTAINER b, CONTAINER_DELIVERY a, NOTA_DELIVERY c, REQUEST_DELIVERY d, v_mst_pbm e, gate_out f
                            WHERE a.NO_CONTAINER = b.NO_CONTAINER
                            AND d.NO_REQUEST = c.NO_REQUEST(+)
                            AND a.NO_REQUEST = d.NO_REQUEST
                            AND d.KD_EMKL = e.KD_PBM
                            AND a.NO_CONTAINER = f.NO_CONTAINER
                            AND a.NO_REQUEST = f.NO_REQUEST
                            AND b.LOCATION = 'GATO'
                            AND b.NO_CONTAINER LIKE '%$no_cont%'
                            AND d.NO_REQUEST IN (SELECT MAX(d.NO_REQUEST) FROM container_delivery d WHERE d.NO_CONTAINER LIKE '%$no_cont%' GROUP BY d.NO_CONTAINER)
                            AND a.NO_CONTAINER NOT IN (SELECT NO_CONTAINER FROM border_gate_in WHERE NO_CONTAINER LIKE '%$no_cont%' AND NO_REQUEST = '$no_req' )
                            ORDER BY a.NO_REQUEST";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
//print_r($row);

echo json_encode($row);


?>