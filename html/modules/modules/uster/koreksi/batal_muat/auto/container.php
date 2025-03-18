<?php
$no_cont		= strtoupper($_GET["term"]);
$jenis 			= $_GET["jns"];
$db 			= getDB("storage");
	
if($jenis == 1){ //after stuffing
$query 			= "SELECT *
                           FROM (SELECT *
                                   FROM (  SELECT mc.no_container,
                                                  cs.no_request,
                                                  mc.type_,
                                                  mc.size_,
                                                  hc.status_cont,
                                                  mc.no_booking,
                                                  vb.nm_kapal,
                                                  cs.hz,
                                                  cs.tgl_realisasi,
                                                  cs.aktif,
                                                  hc.kegiatan
                                             FROM master_container mc
                                                  INNER JOIN container_stuffing cs
                                                     ON mc.no_container = cs.no_container
                                                  INNER JOIN history_container hc
                                                     ON cs.no_container = hc.no_container
                                                  INNER JOIN v_pkk_cont vb
                                                     ON mc.no_booking = vb.no_booking
                                            WHERE     cs.tgl_realisasi IS NOT NULL
                                                  AND cs.aktif = 'T'
                                                  AND mc.no_container LIKE '$no_cont%'
                                         ORDER BY hc.tgl_update DESC) q
                                  WHERE ROWNUM < 2) j
                          WHERE j.kegiatan IN ('REALISASI STUFFING', 'REQUEST BATALMUAT')";

} else if($jenis == 2) { //ex repo
$query			= "SELECT mc.no_container,
                           rd.no_req_ict,
                           RD.TGL_REQUEST,
                           cd.no_request,
                           mc.type_,
                           mc.size_,
                           hc.status_cont,
                           mc.no_booking,
                           vb.nm_kapal,
                           hc.kegiatan,
                           cd.hz,
                           cd.komoditi,
                           cd.start_stack
                      FROM master_container mc
                           INNER JOIN container_delivery cd
                              ON mc.no_container = cd.no_container
                           INNER JOIN history_container hc
                              ON cd.no_request = hc.no_request
                                 AND cd.no_container = hc.no_container
                           INNER JOIN request_delivery rd
                              ON cd.no_request = rd.no_request
                           INNER JOIN v_pkk_cont vb
                              ON mc.no_booking = vb.no_booking
                     WHERE     cd.aktif = 'Y'
                           AND mc.location = 'IN_YARD'
                           AND cd.no_container LIKE '$no_cont%'
                           AND rd.delivery_ke = 'TPK'
                           AND hc.kegiatan IN ('REQUEST DELIVERY', 'PERP DELIVERY')";

} else if($jenis == 3){ //before stuffing
$query 			= "SELECT mc.no_container,
                                cs.no_request,
                                mc.type_,
                                mc.size_,
                                hc.status_cont,
                                mc.no_booking,
                                vb.nm_kapal,
                                cs.end_stack_pnkn,
                                cs.commodity,
                                cs.hz
                           FROM master_container mc
                                INNER JOIN container_stuffing cs
                                   ON mc.no_container = cs.no_container
                                INNER JOIN history_container hc
                                   ON cs.no_request = hc.no_request
                                      AND cs.no_container = hc.no_container
                                INNER JOIN v_pkk_cont vb
                                   ON mc.no_booking = vb.no_booking
                          WHERE     mc.no_container LIKE '$no_cont%'
                                AND cs.tgl_realisasi IS NULL
                                AND cs.aktif = 'Y'";
}
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>