<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");


$query	="  SELECT MASTER_CONTAINER.NO_CONTAINER, 
                          CONTAINER_STUFFING.NO_REQUEST AS NO_REQ_STUFF,
                          --REQUEST_DELIVERY.NO_REQUEST AS NO_REQ_DEL,
                          --TPK.KD_PMB AS NO_REQ_ICT,
                          CONTAINER_STUFFING.TYPE_STUFFING AS VIA,
                          CONTAINER_STUFFING.COMMODITY AS KOMODITI,
                          KOMODITI.KD_COMMODITY AS KD_KOMODITI,
                          CONTAINER_STUFFING.HZ AS HZ,
                          MASTER_CONTAINER.SIZE_ AS SIZE_, 
                          MASTER_CONTAINER.TYPE_ AS TYPE_,
                          REQUEST_STUFFING.TGL_REQUEST AS TGL_REQUEST,
						  MASTER_CONTAINER.NO_BOOKING,
                          --BOOK.NO_BOOKING AS NO_BOOKING,
                          --TPK.NO_UKK AS NO_UKK,
                          CONTAINER_STUFFING.NO_SEAL AS NO_SEAL,
                          CONTAINER_STUFFING.BERAT AS BERAT,
                          CONTAINER_STUFFING.KETERANGAN AS KETERANGAN,                        
                          CONTAINER_STUFFING.REMARK_SP2                 
                   FROM MASTER_CONTAINER 
                   INNER JOIN CONTAINER_STUFFING 
                        ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_STUFFING.NO_CONTAINER 
                   JOIN REQUEST_STUFFING 
                        ON CONTAINER_STUFFING.NO_REQUEST = REQUEST_STUFFING.NO_REQUEST
                   --JOIN V_MST_PBM EMKL
                        --ON REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM AND EMKL.KD_CABANG = '05'
                   --JOIN V_BOOKING_STACK_TPK BOOK
                       -- ON  REQUEST_STUFFING.NM_KAPAL = BOOK.NM_KAPAL 
                        --AND REQUEST_STUFFING.VOYAGE = BOOK.VOYAGE_IN
                   --JOIN REQUEST_DELIVERY  
                   --     ON REQUEST_STUFFING.NO_REQUEST_DELIVERY = REQUEST_DELIVERY.NO_REQUEST          
                   --JOIN PETIKEMAS_CABANG.TTH_CONT_EXBSPL TPK
                   --     ON REQUEST_DELIVERY.NO_REQ_ICT = TPK.KD_PMB
                   LEFT JOIN V_MST_COMMODITY KOMODITI
                        ON CONTAINER_STUFFING.COMMODITY = KOMODITI.NM_COMMODITY
                   WHERE MASTER_CONTAINER.NO_CONTAINER LIKE '%$no_cont%' --AND REQUEST_STUFFING.NOTA='Y' 
				   AND LOCATION = 'IN_YARD' 
				   AND  AKTIF = 'Y'
			"; /*GATO dilepas sementara*/
	
/*	
$query 			= "SELECT MASTER_CONTAINER.NO_CONTAINER, 
                          MASTER_CONTAINER.SIZE_ AS SIZE_, 
                          MASTER_CONTAINER.TYPE_ AS TYPE_,
						  CONTAINER_STUFFING.COMMODITY AS COMMODITY,
						  CONTAINER_STUFFING.HZ AS HZ,
						  CONTAINER_STUFFING.NO_REQUEST AS NO_REQUEST,
						  CONTAINER_STUFFING.TYPE_STUFFING AS TYPE,
						  REQUEST_STUFFING.TGL_REQUEST AS TGL_REQUEST						  
				   FROM MASTER_CONTAINER  INNER JOIN CONTAINER_STUFFING ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_STUFFING.NO_CONTAINER JOIN REQUEST_STUFFING ON CONTAINER_STUFFING.NO_REQUEST = REQUEST_STUFFING.NO_REQUEST
                   WHERE MASTER_CONTAINER.NO_CONTAINER LIKE '%$no_cont%' AND LOCATION NOT LIKE 'GATO' AND AKTIF = 'Y'";
*/
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>