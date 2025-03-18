<?php
$no_cont		= strtoupper($_GET["term"]);

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
/*
$query			= "  SELECT a.NO_CONTAINER, a.NO_REQUEST, b.SIZE_, a.STATUS , b.TYPE_, c.NO_NOTA, TO_CHAR(c.END_STACK, 'dd/mm/yyyy') END_STACK , e.NM_PBM NAMA, TO_CHAR(d.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, f.NOPOL, f.NO_SEAL, f.KETERANGAN
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
                            AND a.NO_CONTAINER NOT IN (SELECT NO_CONTAINER FROM border_gate_in WHERE NO_CONTAINER LIKE '%$no_cont%' AND NO_REQUEST IN(SELECT NO_REQUEST FROM container_delivery WHERE NO_REQUEST IN (select max(NO_REQUEST) from container_delivery where NO_CONTAINER LIKE '%$no_cont%' GROUP BY NO_CONTAINER)))
                          ";
*/						  
					  
$query = "SELECT a.no_container, a.no_request, b.size_, a.status, b.type_, c.no_nota,
       TO_CHAR (c.end_stack, 'dd/mm/yyyy') end_stack, e.nm_pbm nama,
       TO_CHAR (d.tgl_request_delivery, 'dd/mm/yyyy') tgl_request_delivery,
       f.nopol, f.no_seal, f.keterangan,td.kd_pmb_dtl
  FROM master_container b,
       container_delivery a,
       nota_delivery c,
       request_delivery d,
       v_mst_pbm e,
       border_gate_out f,
       petikemas_cabang.ttd_cont_exbspl td
 WHERE a.no_container = b.no_container
   AND d.no_request = c.no_request(+)
   AND a.no_request = d.no_request
   AND d.kd_emkl = e.kd_pbm AND e.kd_cabang = '05'
   AND a.no_container = f.no_container
   AND a.no_request = f.no_request
   AND b.LOCATION = 'GATO'
   and td.STATUS_PMB_DTL = '1U'
   and td.no_container = a.no_container
   AND b.no_container LIKE '%$no_cont%'
   AND d.no_request IN 
   (SELECT   MAX (d.no_request)
   FROM container_delivery d
   WHERE d.no_container LIKE '%$no_cont%'
   GROUP BY d.no_container)
   AND a.no_container NOT IN 
   (
   SELECT no_container
   FROM border_gate_in
   WHERE no_container LIKE '%$no_cont%'
   AND no_request IN (
   SELECT no_request
   FROM container_delivery
   WHERE no_request IN (
   SELECT   MAX (no_request)
   FROM container_delivery
   WHERE no_container LIKE
   '%$no_cont%'
   GROUP BY no_container)))";						  
   
//echo $query;die;
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
//print_r($row);

echo json_encode($row);


?>