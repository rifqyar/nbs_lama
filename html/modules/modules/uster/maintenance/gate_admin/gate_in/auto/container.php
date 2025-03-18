<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");
$id_yard		= $_SESSION["IDYARD_STORAGE"];
/*
$query 			= "SELECT CONTAINER_RECEIVING.NO_REQUEST AS NO_REQUEST, 
						  CONTAINER_RECEIVING.STATUS AS STATUS, 
						  MASTER_CONTAINER.NO_CONTAINER AS NO_CONTAINER, 
						  MASTER_CONTAINER.SIZE_ AS SIZE_, 
						  MASTER_CONTAINER.TYPE_ AS TYPE_ 
				   FROM MASTER_CONTAINER 
				   INNER JOIN CONTAINER_RECEIVING ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_RECEIVING.NO_CONTAINER 
				   WHERE CONTAINER_RECEIVING.NO_CONTAINER LIKE '%$no_cont%' 
				   AND LOCATION LIKE 'GATO' AND CONTAINER_RECEIVING.AKTIF = 'Y'
				   AND CONTAINER_RECEIVING.DEPO_TUJUAN = '$id_yard'
				   ";
*/				   
$cek_mlo = "SELECT MLO FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
	$rmlo = $db->query($cek_mlo);
	$rwl = $rmlo->fetchRow();
	$mlo = $rwl["MLO"];
	if($mlo == "MLO"){
		$query			="SELECT CONTAINER_RECEIVING.NO_REQUEST AS NO_REQUEST, 
                              --CONTAINER_RECEIVING.STATUS AS STATUS,
                              HISTORY_CONTAINER.STATUS_CONT AS STATUS, 
                              MASTER_CONTAINER.NO_CONTAINER AS NO_CONTAINER, 
                              MASTER_CONTAINER.SIZE_ AS SIZE_, 
                              MASTER_CONTAINER.TYPE_ AS TYPE_,
                              EMKL.NM_PBM AS EMKL,
                              NVL(NOTA_RECEIVING.NO_NOTA,'') AS NO_NOTA 
                       FROM MASTER_CONTAINER 
                       INNER JOIN CONTAINER_RECEIVING 
                            ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_RECEIVING.NO_CONTAINER                           
                       JOIN REQUEST_RECEIVING
                            ON CONTAINER_RECEIVING.NO_REQUEST = REQUEST_RECEIVING.NO_REQUEST
                       INNER JOIN HISTORY_CONTAINER ON CONTAINER_RECEIVING.NO_CONTAINER = HISTORY_CONTAINER.NO_CONTAINER
                       AND HISTORY_CONTAINER.NO_REQUEST = CONTAINER_RECEIVING.NO_REQUEST
                       LEFT OUTER JOIN V_MST_PBM EMKL
                            ON REQUEST_RECEIVING.KD_CONSIGNEE = EMKL.KD_PBM
                       LEFT OUTER JOIN NOTA_RECEIVING
                            ON CONTAINER_RECEIVING.NO_REQUEST = NOTA_RECEIVING.NO_REQUEST
                       WHERE CONTAINER_RECEIVING.NO_CONTAINER LIKE '%$no_cont%' AND REQUEST_RECEIVING.RECEIVING_DARI = 'LUAR'
                       AND MLO LIKE 'MLO' AND CONTAINER_RECEIVING.AKTIF = 'Y'
					   AND NOTA_RECEIVING.STATUS  <> 'BATAL'
					   --AND CONTAINER_RECEIVING.DEPO_TUJUAN = '$id_yard'
						";
	} else {
						   
						$query			="SELECT * FROM (SELECT CONTAINER_RECEIVING.NO_REQUEST AS NO_REQUEST, 
                              --CONTAINER_RECEIVING.STATUS AS STATUS,
                              HISTORY_CONTAINER.STATUS_CONT AS STATUS, 
                              MASTER_CONTAINER.NO_CONTAINER AS NO_CONTAINER, 
                              MASTER_CONTAINER.SIZE_ AS SIZE_, 
                              MASTER_CONTAINER.TYPE_ AS TYPE_,
                              EMKL.NM_PBM AS EMKL,
                              NVL(NOTA_RECEIVING.NO_NOTA,'') AS NO_NOTA,
                              REQUEST_RECEIVING.RECEIVING_DARI,
                              REQUEST_RECEIVING.PERALIHAN
                       FROM MASTER_CONTAINER 
                       INNER JOIN CONTAINER_RECEIVING 
                            ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_RECEIVING.NO_CONTAINER                           
                       JOIN REQUEST_RECEIVING
                            ON CONTAINER_RECEIVING.NO_REQUEST = REQUEST_RECEIVING.NO_REQUEST
                       INNER JOIN HISTORY_CONTAINER ON CONTAINER_RECEIVING.NO_CONTAINER = HISTORY_CONTAINER.NO_CONTAINER
                       AND HISTORY_CONTAINER.NO_REQUEST = CONTAINER_RECEIVING.NO_REQUEST
                       LEFT OUTER JOIN V_MST_PBM EMKL
                            ON REQUEST_RECEIVING.KD_CONSIGNEE = EMKL.KD_PBM
                       LEFT JOIN NOTA_RECEIVING
                            ON CONTAINER_RECEIVING.NO_REQUEST = NOTA_RECEIVING.NO_REQUEST
                       WHERE CONTAINER_RECEIVING.NO_CONTAINER LIKE '%$no_cont%' AND LOCATION LIKE 'GATO' AND CONTAINER_RECEIVING.AKTIF = 'Y'
					   AND NOTA_RECEIVING.STATUS  <> 'BATAL'
                       ) CEK WHERE (CEK.PERALIHAN IS NULL AND CEK.RECEIVING_DARI = 'LUAR') OR (CEK.PERALIHAN = 'STUFFING' AND CEK.RECEIVING_DARI = 'TPK')
						";				   
	} 
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>