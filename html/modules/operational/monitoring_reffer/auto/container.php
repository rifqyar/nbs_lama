<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB();
	
$query 			= "SELECT a.nm_kapal,
			       CONCAT (CONCAT (a.voyage_in, '-'), a.voyage_out) voyage,
			       a.nm_pelabuhan_asal,
			       a.nm_pelabuhan_tujuan,
			       b.berat,
			       b.seal_id,
			       b.no_container,
			       b.size_,
			       b.type_,
			       b.status,
			       CONCAT (
			          CONCAT (
			             CONCAT (CONCAT (CONCAT (CONCAT (b.BLOCK, '-'), b.SLOT), '-'),
			                     b.ROW_),
			             '-'),
			          b.TIER)
			          LOKASI,
			       TO_CHAR (tgl_gate_in, 'dd Mon RRRR hh24:mi:ss') TGL_GATE
			  FROM rbm_h a, isws_list_container b
			 WHERE     b.no_ukk = a.no_ukk
			       AND b.kode_status = '51'
				   AND b.TYPE_ = 'RFR'
			       AND b.no_container LIKE '$no_cont%'";
//print_r($query);die;
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($query);die;

echo json_encode($row);


?>