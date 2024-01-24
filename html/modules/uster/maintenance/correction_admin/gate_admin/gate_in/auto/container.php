<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
$id_yard		= $_SESSION["IDYARD_STORAGE"];
$query			="select gate_in.no_container, size_, type_, gate_in.status, gate_in.no_seal, gate_in.keterangan, to_date(tgl_in,'dd-mm-rrrr') tgl_in,  gate_in.no_request, nm_pbm, nama_lengkap, gate_in.nopol
from master_container, gate_in, request_receiving, v_mst_pbm, master_user where master_container.no_container = gate_in.no_container 
and gate_in.no_request = request_receiving.no_request
and request_receiving.kd_consignee = v_mst_pbm.kd_pbm
and gate_in.id_user = master_user.id and gate_in.no_container like '%$no_cont%'";
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>