<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB();
	

	
$query	= "SELECT A.NO_CONTAINER, 
            A.NO_UKK,
            A.SIZE_, 
            A.TYPE_, 
            A.STATUS, 
            A.ISO_CODE, 
            A.HEIGHT, 
            A.CARRIER, 
            A.TEMP,
            A.BERAT GROSS,
            A.HZ ,
            C.NM_KAPAL, 
            C.VOYAGE_IN,
            C.VOYAGE_OUT, 
            C.NM_PELABUHAN_ASAL, 
            C.NM_PELABUHAN_TUJUAN, 
            B.ID_REQ NO_REQUEST,
            B.TGL_SP2 TANGGAL_BERLAKU,
            TO_CHAR(B.TGL_SP2+1,'YYYYMMDDHH24MISS') AS CEK_BERLAKU,
            CASE
                WHEN A.R_BLOCK IS NOT NULL THEN A.R_BLOCK||' '||A.R_SLOT||'-'||A.R_ROW_||'-'||A.R_TIER
                ELSE A.BLOCK||' '||A.SLOT||'-'||A.ROW_||'-'||A.TIER
             END
                YD_POSISI
        FROM ISWS_LIST_CONTAINER A, NOTA_DELIVERY_H B, RBM_H C
        WHERE TRIM (A.KODE_STATUS)IN ('03')
                    AND A.E_I='I' AND 
                    A.NO_UKK=B.NO_UKK
                    AND A.NO_CONTAINER LIKE '$no_cont%'
					";					
										
//print_r($query);die;
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($query);die;

echo json_encode($row);


?>