<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");

	

	$result  = "SELECT MASTER_CONTAINER.NO_CONTAINER AS NO_CONTAINER, 
                          MASTER_CONTAINER.SIZE_ AS KD_SIZE, 
                          MASTER_CONTAINER.TYPE_ AS KD_TYPE,                      
                         TO_CHAR( CONTAINER_RECEIVING.TGL_BONGKAR, 'DD-MM-YYYY') TGL_BONGKAR,
                          CONTAINER_RECEIVING.STATUS KD_STATUS_CONT, '' BP_ID, '' NO_UKK, 'DEPO' ASAL_CONT, '' BLOK_, 0 ROW_, 0 SLOT_, 0 TIER_, 
              '' VOYAGE_IN, '' NM_KAPAL, '' NM_AGEN, '' TGL_STACK
                   FROM USTER.MASTER_CONTAINER  MASTER_CONTAINER
                   INNER JOIN PLACEMENT ON MASTER_CONTAINER.NO_CONTAINER = PLACEMENT.NO_CONTAINER
                   JOIN BLOCKING_AREA ON BLOCKING_AREA.ID = PLACEMENT.ID_BLOCKING_AREA
                   JOIN YARD_AREA ON YARD_AREA.ID = BLOCKING_AREA.ID_YARD_AREA
                   JOIN CONTAINER_RECEIVING ON PLACEMENT.NO_REQUEST_RECEIVING = CONTAINER_RECEIVING.NO_REQUEST                   
                   AND PLACEMENT.NO_CONTAINER = CONTAINER_RECEIVING.NO_CONTAINER
                   WHERE  MASTER_CONTAINER.NO_CONTAINER LIKE '%$no_cont%' AND MASTER_CONTAINER.LOCATION LIKE 'IN_YARD' AND MASTER_CONTAINER.NO_CONTAINER NOT IN (SELECT NO_CONTAINER FROM USTER.CONTAINER_DELIVERY WHERE AKTIF = 'Y') AND MASTER_CONTAINER.NO_CONTAINER NOT IN (SELECT NO_CONTAINER FROM USTER.CONTAINER_STRIPPING WHERE AKTIF = 'Y')
                   GROUP BY MASTER_CONTAINER.NO_CONTAINER, SIZE_, TYPE_, YARD_AREA.ID, YARD_AREA.NAMA_YARD, CONTAINER_RECEIVING.TGL_BONGKAR, CONTAINER_RECEIVING.STATUS, 
                   '' , '' , 'DEPO' , '' , 0 , 0 , 0 , '' , '' , '' , '' 
            UNION
            SELECT TTD_BP_CONT.CONT_NO_BP NO_CONTAINER,
                     TTD_BP_CONT.KD_SIZE,
                     TTD_BP_CONT.KD_TYPE,
                     TO_CHAR (TTD_BP_CONFIRM.CONFIRM_DATE, 'DD-MM-YYYY') AS TGL_BONGKAR,
                     TTD_BP_CONT.KD_STATUS_CONT,
                     TTD_BP_CONT.BP_ID,     
                     V_PKK_CONT.NO_UKK, 'TPK' ASAL_CONT,                     
                        YARD.ARE_BLOK BLOK_,            
                        YARD.ARE_SLOT SLOT_,  
                        YARD.ARE_ROW ROW_,
                        YARD.ARE_TIER TIER_ ,
                        V_PKK_CONT.VOYAGE_IN,
                        V_PKK_CONT.NM_KAPAL,
                        V_PKK_CONT.NM_AGEN,
                        To_Char(TTD_BP_CONT.TGL_STACK,'DD-MM-YYYY') AS TGL_STACK
                FROM PETIKEMAS_CABANG.TTM_BP_CONT TTM_BP_CONT,
                     PETIKEMAS_CABANG.TTD_BP_CONT TTD_BP_CONT,
                     PETIKEMAS_CABANG.V_PKK_CONT V_PKK_CONT,
                     PETIKEMAS_CABANG.TTD_BP_CONFIRM TTD_BP_CONFIRM,
                     PETIKEMAS_CABANG.MST_YARD_CONT_LAPANGAN YARD
               WHERE     TTM_BP_CONT.BP_ID = TTD_BP_CONT.BP_ID
                     AND ROWNUM <= 7
                     AND TTM_BP_CONT.NO_UKK = V_PKK_CONT.NO_UKK
                     AND TTD_BP_CONT.CONT_NO_BP = TTD_BP_CONFIRM.CONT_NO_BP
                     AND TTM_BP_CONT.NO_UKK = TTD_BP_CONFIRM.NO_UKK
                     AND TTD_BP_CONT.BP_ID = TTD_BP_CONFIRM.BP_ID
                     AND TTM_BP_CONT.KD_CABANG = '05'
                     AND TTD_BP_CONT.STATUS_CONT = '03'
                     AND YARD.ARE_ID = TTD_BP_CONT.ARE_ID
                     AND TTD_BP_CONT.CONT_NO_BP LIKE '%$no_cont%'
                     AND TTD_BP_CONT.KD_STATUS_CONT = 'FCL'
                     ORDER BY ASAL_CONT ASC
                      
";      
//echo $result;
//print_r($result); exit();
$rs  = $db->query($result);
$rowxcont=$rs->getAll();

// End Receive DARI TPK //

echo json_encode($rowxcont);


?>