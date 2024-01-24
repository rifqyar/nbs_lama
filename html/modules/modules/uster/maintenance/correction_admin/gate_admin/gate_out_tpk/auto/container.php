<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
	
	$query =	"SELECT a.NO_CONTAINER, 
                            a.NO_REQUEST, 
                            b.SIZE_, 
                            a.STATUS, 
                            b.TYPE_, 
                            --e.nm_pbm NAMA,
                            --td.kd_pmb_dtl KD_PMB_DTL,
                            'AUTO' CEK,
                            d.NO_REQ_ICT,
                            a.TGL_IN,
                            a.NOPOL,
                            a.KETERANGAN,
                            a.NO_SEAL
        FROM border_gate_out a,
        container_delivery c,
        master_container b,
        request_delivery d
        where a.no_container = c.no_container
        and a.no_request = c.no_request
        and c.no_container = b.no_container
        and c.no_request = d.no_request
        and c.aktif = 'T'
        and b.location = 'GATO'
        and a.no_container = '$no_cont'";
   
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
//print_r($row);

echo json_encode($row);


?>