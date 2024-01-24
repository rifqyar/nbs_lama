<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
$id_yard		= 23;
	
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

$query_ck = "SELECT A.NO_CONTAINER, B.TIPE_RELOKASI FROM CONTAINER_RELOKASI A, REQUEST_RELOKASI B WHERE A.NO_REQUEST = B.NO_REQUEST AND A.NO_CONTAINER = '$no_cont'";
$result_ck = $db->query($query_ck);
$row_ck = $result_ck->fetchRow();
if($row_ck["TIPE_RELOKASI"] == 'INTERNAL'){
	$query			= " SELECT a.NO_CONTAINER, a.NO_REQUEST, b.SIZE_, P.STATUS , b.TYPE_, d.TGL_REQUEST_DELIVERY
                            FROM MASTER_CONTAINER b, CONTAINER_DELIVERY a, REQUEST_DELIVERY d, PLACEMENT P
                            WHERE a.NO_CONTAINER = b.NO_CONTAINER
                            AND a.NO_REQUEST = d.NO_REQUEST
                            AND a.NO_CONTAINER (+) = P.NO_CONTAINER
                            AND b.LOCATION = 'IN_YARD'
                            AND a.AKTIF = 'Y'
                            AND a.NO_CONTAINER LIKE '$no_cont%'
                            AND a.ID_YARD = '$id_yard'
                            ORDER BY a.NO_REQUEST";
}
else{
	$query			= " SELECT a.NO_CONTAINER, a.NO_REQUEST, b.SIZE_, P.STATUS , b.TYPE_, d.TGL_REQUEST_DELIVERY, e.NM_PBM, f.NO_NOTA
								FROM MASTER_CONTAINER b, CONTAINER_DELIVERY a, REQUEST_DELIVERY d, PLACEMENT P, V_MST_PBM e, NOTA_DELIVERY f
								WHERE d.NO_REQUEST = f.NO_REQUEST
								AND d.KD_EMKL = e.KD_PBM
								AND f.TGL_NOTA = (SELECT MAX(g.TGL_NOTA) FROM NOTA_DELIVERY g WHERE g.NO_REQUEST = d.NO_REQUEST)
								AND a.NO_CONTAINER = b.NO_CONTAINER
								AND a.NO_REQUEST = d.NO_REQUEST
								AND a.NO_CONTAINER (+) = P.NO_CONTAINER
								AND b.LOCATION = 'IN_YARD'
								AND a.AKTIF = 'Y'
								AND a.NO_CONTAINER LIKE '$no_cont%'
								AND a.ID_YARD = '$id_yard'
								ORDER BY a.NO_REQUEST";
}							
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
//print_r($row);

echo json_encode($row);


?>