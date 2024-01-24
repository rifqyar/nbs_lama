<?php
$param		= strtoupper($_GET["term"]);

$db 			= getDB();
	
$query 			= "SELECT A.NO_CONTAINER, 
                          A.NO_UKK, 
                          A.SIZE_, 
                          A.TYPE_, 
                          A.STATUS AS STATUS_, 
                          (SELECT C.ID FROM YD_BLOCKING_AREA C WHERE C.NAME=(SELECT * FROM TABLE (CAST (explode(',',A.LOKASI) AS listtable)) WHERE ROWNUM=1) AND ID_YARD_AREA='23') AS ID_BLOCK,
                       (SELECT MIN(SLOT_) SLOT_ FROM YD_YARD_ALLOCATION_PLANNING WHERE ID_BLOCKING_AREA = (SELECT C.ID FROM YD_BLOCKING_AREA C WHERE C.NAME=(SELECT * FROM TABLE (CAST (explode(',',A.LOKASI) AS listtable)) WHERE ROWNUM=1) AND ID_YARD_AREA='23') )  AS SLOT_,
                         (SELECT MIN(ROW_) ROW_ FROM YD_YARD_ALLOCATION_PLANNING WHERE ID_BLOCKING_AREA = (SELECT C.ID FROM YD_BLOCKING_AREA C WHERE C.NAME=(SELECT * FROM TABLE (CAST (explode(',',A.LOKASI) AS listtable)) WHERE ROWNUM=1) AND ID_YARD_AREA='23') )  AS ROW_,
                        (SELECT MAX(TIER_) TIER_ FROM YD_YARD_ALLOCATION_PLANNING WHERE ID_BLOCKING_AREA = (SELECT C.ID FROM YD_BLOCKING_AREA C WHERE C.NAME=(SELECT * FROM TABLE (CAST (explode(',',A.LOKASI) AS listtable)) WHERE ROWNUM=1) AND ID_YARD_AREA='23') )  AS TIER_, 
                          A.BERAT, 
                          B.NM_KAPAL AS VESSEL, 
                          B.VOYAGE_IN AS VOYAGE
                    FROM ISWS_LIST_CONTAINER A, RBM_H B 
                    WHERE TRIM(A.NO_UKK)=TRIM(B.NO_UKK) 
                        AND A.NO_CONTAINER LIKE '$param%' 
                        AND A.E_I='I'
						AND A.KODE_STATUS = '02'
						";
						
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($row);

echo json_encode($row);


?>