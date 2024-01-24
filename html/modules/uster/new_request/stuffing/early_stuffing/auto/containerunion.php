<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");


$result =   " SELECT MASTER_CONTAINER.NO_CONTAINER NO_CONTAINER, MASTER_CONTAINER.SIZE_ KD_SIZE, MASTER_CONTAINER.TYPE_ KD_TYPE, 
              TO_CHAR(CONTAINER_RECEIVING.TGL_BONGKAR, 'DD-MM-YYYY') TGL_BONGKAR,
              CONTAINER_RECEIVING.STATUS KD_STATUS_CONT, '' BP_ID, '' NO_UKK, 'DEPO' ASAL_CONT, blocking_area.NAME BLOK_, placement.ROW_ ROW_, placement.SLOT_ SLOT_, placement.TIER_ TIER_, 
              '' VOYAGE_IN, MASTER_CONTAINER.NO_BOOKING NM_KAPAL, '' NM_AGEN, '' TGL_STACK
			  --, NVL(TO_CHAR(CONTAINER_RECEIVING.TGL_BONGKAR+5, 'dd/mm/rrrr'),TO_DATE(get_tgl_start_stack('$no_cont'),'dd/mm/rrrr')) EMPTY_SD
                FROM CONTAINER_RECEIVING
                     INNER JOIN
                        MASTER_CONTAINER
                     ON CONTAINER_RECEIVING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
                        AND CONTAINER_RECEIVING.AKTIF = 'T'
                     INNER JOIN 
                        PLACEMENT
                        ON placement.NO_CONTAINER = container_receiving.NO_CONTAINER
                     INNER JOIN
                        BLOCKING_AREA 
                        ON blocking_area.ID = placement.ID_BLOCKING_AREA
               WHERE MASTER_CONTAINER.LOCATION = 'IN_YARD'
			   AND CONTAINER_RECEIVING.STATUS = 'MTY'
               AND master_container.NO_CONTAINER NOT IN (SELECT container_stuffing.NO_CONTAINER FROM container_stuffing where container_stuffing.no_container LIKE '%$no_cont%' AND container_stuffing.AKTIF = 'Y' )  
                     AND CONTAINER_RECEIVING.NO_CONTAINER LIKE '%$no_cont%'
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
						--, TO_CHAR (TTD_BP_CONFIRM.CONFIRM_DATE+5, 'DD-MM-YYYY') EMPTY_SD
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
                     AND TTD_BP_CONT.KD_STATUS_CONT = 'MTY'";
	

/* $result =   " SELECT NO_CONTAINER, 
                          SIZE_ AS SIZE_, 
                          TYPE_ AS TYPE_
                   FROM MASTER_CONTAINER  
                   WHERE NO_CONTAINER LIKE '%$no_cont%' AND LOCATION LIKE 'GATO' 
                   AND NO_CONTAINER NOT IN (SELECT NO_CONTAINER FROM history_container WHERE  NO_CONTAINER LIKE '%$no_cont%') 
                   AND NO_CONTAINER NOT IN (SELECT NO_CONTAINER FROM PLAN_CONTAINER_STUFFING WHERE AKTIF = 'Y' AND NO_CONTAINER LIKE '%$no_cont%')";
    */      	
			
//echo $result;
//print_r($result); exit();
$rs  = $db->query($result);
$rowxcont=$rs->getAll();

// End Receive DARI TPK //

echo json_encode($rowxcont);


?>