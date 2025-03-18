<?php
$no_cont		= strtoupper($_GET["term"]);
 
$db 			= getDB("storage");
$db2 			= getDB("ora");

$id_yard		= $_SESSION["IDYARD_STORAGE"];
$query			= "SELECT DISTINCT b.NO_CONTAINER AS NO_CONTAINER,
                          b.NO_REQUEST AS NO_REQUEST,
                          a.SIZE_ AS SIZE_,
                          a.TYPE_ AS TYPE_,
                          b.STATUS AS STATUS,
                          d.NM_PBM AS NM_PBM,                          
                          TD.BP_ID,
                          TD.NO_REQ AS NO_REQ_TPK,
                          c.tgl_request,
						  b.tgl_in,
                          b.nopol,
						   b.no_seal,
                          b.keterangan
                    FROM PETIKEMAS_CABANG.TTD_BP_CONT TD,
                         MASTER_CONTAINER a INNER JOIN 
                         border_gate_in b ON a.NO_CONTAINER = b.NO_CONTAINER JOIN
                         REQUEST_RECEIVING c ON  b.NO_REQUEST = c.NO_REQUEST JOIN
                         V_MST_PBM d ON  c.KD_CONSIGNEE = d.KD_PBM               
                    WHERE c.RECEIVING_DARI = 'TPK'
                    and td.status_cont = '09' 
                    and b.NO_CONTAINER = TD.CONT_NO_BP
                    and b.NO_CONTAINER LIKE '%$no_cont%'
                    and td.no_req = C.NO_REQ_ICT";

$result			= $db->query($query);
$row			= $result->getAll();	

echo json_encode($row);


?>