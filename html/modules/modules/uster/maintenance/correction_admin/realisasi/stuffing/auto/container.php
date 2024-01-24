<?php
if($_SESSION["ID_ROLE"] != 1 && $_SESSION["ID_ROLE"] != 41)
{
  exit();
}
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");


			
	$query = "SELECT MASTER_CONTAINER.NO_CONTAINER, 
                          CONTAINER_STUFFING.NO_REQUEST AS NO_REQ_STUFF,
                          CONTAINER_STUFFING.TYPE_STUFFING AS VIA,
                          CONTAINER_STUFFING.COMMODITY AS KOMODITI,                          
                          CONTAINER_STUFFING.HZ AS HZ,
                          MASTER_CONTAINER.SIZE_ AS SIZE_, 
                          MASTER_CONTAINER.TYPE_ AS TYPE_,
                          REQUEST_STUFFING.TGL_REQUEST AS TGL_REQUEST,
                          REQUEST_STUFFING.NO_BOOKING,
                          CONTAINER_STUFFING.NO_SEAL AS NO_SEAL,
                          CONTAINER_STUFFING.BERAT AS BERAT,
                          CONTAINER_STUFFING.KETERANGAN AS KETERANGAN,
						  TO_DATE(CONTAINER_STUFFING.TGL_REALISASI,'DD-MM-RRRR') TGL_REALISASI
                   FROM MASTER_CONTAINER 
                   INNER JOIN CONTAINER_STUFFING 
                        ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_STUFFING.NO_CONTAINER 
                   JOIN REQUEST_STUFFING 
                        ON CONTAINER_STUFFING.NO_REQUEST = REQUEST_STUFFING.NO_REQUEST
                   JOIN V_MST_PBM EMKL
                        ON REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM AND EMKL.KD_CABANG = '05'
                    JOIN MASTER_USER ON CONTAINER_STUFFING.ID_USER_REALISASI = MASTER_USER.ID AND ID_ROLE IN (1,41)
                   WHERE MASTER_CONTAINER.NO_CONTAINER LIKE '%$no_cont%' 
                   AND  AKTIF = 'T' AND CONTAINER_STUFFING.TGL_REALISASI IS NOT NULL";
	
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>