<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB();
	

	
$query	= "SELECT DISTINCT (A.NO_CONTAINER), 
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
			B.NM_KAPAL, 
			B.VOYAGE_IN,
            B.VOYAGE_OUT, 
			B.NM_PELABUHAN_ASAL, 
			B.NM_PELABUHAN_TUJUAN, 
			D.NO_REQUEST,
			D.TANGGAL_BERLAKU,
			TO_CHAR(D.TANGGAL_BERLAKU+1,'YYYYMMDDHH24MISS') AS CEK_BERLAKU,
			CASE
				WHEN A.R_BLOCK IS NOT NULL THEN A.R_BLOCK||' '||A.R_SLOT||'-'||A.R_ROW_||'-'||A.R_TIER
				ELSE A.BLOCK||' '||A.SLOT||'-'||A.ROW_||'-'||A.TIER
			 END
				YD_POSISI
		FROM ISWS_LIST_CONTAINER A, RBM_H B ,CONT_AFTER_REQDEL D
		WHERE TRIM (A.KODE_STATUS)IN ('03','03R','03P')
					AND A.E_I='I' 
					AND TGL_PLACEMENT IS NOT NULL 
					AND A.NO_UKK=B.NO_UKK 
					AND B.NO_UKK=D.NO_UKK
					AND A.NO_UKK=D.NO_UKK
                    AND A.NO_CONTAINER = D.NO_CONTAINER 
					AND A.NO_CONTAINER LIKE '$no_cont%'
					";					
										
//print_r($query);die;
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($query);die;

echo json_encode($row);


?>