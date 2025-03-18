<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
$id_yard		= $_SESSION["IDYARD_STORAGE"];
	

$query			= "select gate_out.no_container, size_, type_, gate_out.status, gate_out.no_seal, gate_out.keterangan, to_date(tgl_in,'dd-mm-rrrr') tgl_in,  gate_out.no_request, nm_pbm, nama_lengkap, gate_out.nopol
from master_container, gate_out, request_delivery, v_mst_pbm, master_user where master_container.no_container = gate_out.no_container 
and gate_out.no_request = request_delivery.no_request
and request_delivery.kd_emkl = v_mst_pbm.kd_pbm
and gate_out.id_user = master_user.id and gate_out.no_container like '%$no_cont%'";
							
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
//print_r($row);

echo json_encode($row);


?>