<?php

$nama_kapal		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
//$query 		= "select a.NAMA_VESSEL, b.NO_BOOKING, b.VOYAGE from vessel a, voyage b WHERE a.KODE_VESSEL = b.KODE_VESSEL AND a.NAMA_VESSEL LIKE '%$nama%' ";

$query			= "/* Formatted on 13/01/2014 09:39:54 (QP5 v5.163.1008.3004) */
    SELECT
      a.VOYAGE,
      a.O_VOYIN VOYAGE_IN,
      a.O_VOYOUT,
      a.VESSEL NM_KAPAL
    FROM request_delivery a,
      container_delivery b,
      master_container mc,
      border_gate_out c,
      nota_delivery n
    WHERE
      a.no_request = b.no_request
      AND b.no_request = c.no_request(+)
      AND b.no_container = c.no_container(+)
      AND b.no_container = mc.no_container
      --and C.VIA is null
      AND a.no_request = n.no_request(+)
      AND n.status NOT IN ('BATAL')
      AND VESSEL LIKE '%$nama_kapal%'
      AND a.O_VOYIN IS NOT NULL
    GROUP BY
      a.VOYAGE,
      a.O_VOYIN,
      a.O_VOYOUT,
      a.VESSEL
    ORDER BY O_VOYIN DESC";

$result		= $db->query($query);
$row		= $result->getAll();	 
//echo $query;
echo json_encode($row);


?>