<?php
$no_cont		= strtoupper($_GET["term"]);
$remark_sp2		= strtoupper($_GET["remark_sp2"]);

$db 			= getDB("storage");


if($remark_sp2 == 'Y'){
			$result = "SELECT cd.no_container,
		       mc.size_ kd_size,
		       mc.type_ kd_type,
		       TO_CHAR (cd.tgl_delivery, 'DD-MM-YYYY') tgl_bongkar,
		       TO_CHAR (cd.tgl_delivery+4, 'DD-MM-YYYY') EMPTY_SD,
		       cd.status status_cont,
		       mc.no_booking bp_id,
		       '' no_ukk,
		       'DEPO' asal_cont,
		       ba.name blok_,
		       pl.slot_,
		       pl.row_,
		       pl.tier_,
		       '' voyage_in,
		       '' nm_kapal,
		       '' nm_agen,
		       CASE
		          WHEN rd.status = 'PERP' THEN TO_CHAR (cd.start_perp, 'DD-MM-YYYY')
		          ELSE TO_CHAR (cd.start_stack, 'DD-MM-YYYY')
		       END
		          tgl_stack,
		       cd.no_request
		  FROM container_delivery cd
		       JOIN master_container mc
		          ON cd.no_container = mc.no_container
		       JOIN request_delivery rd
		          ON cd.no_request = rd.no_request
		       LEFT JOIN placement pl
		          ON mc.no_container = pl.no_container
		       LEFT JOIN blocking_area ba
		          ON pl.id_blocking_area = ba.id
		 WHERE     cd.remark_batal = 'Y'
		       AND cd.aktif = 'T'
		       AND cd.no_container = '$no_cont'";
} else {

$result =   /* " SELECT MASTER_CONTAINER.NO_CONTAINER NO_CONTAINER, MASTER_CONTAINER.SIZE_ KD_SIZE, MASTER_CONTAINER.TYPE_ KD_TYPE, 
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
                     AND CONTAINER_RECEIVING.NO_CONTAINER LIKE '%$no_cont%'" */
					 
			"SELECT DISTINCT MASTER_CONTAINER.NO_CONTAINER, 
					MASTER_CONTAINER.SIZE_ KD_SIZE, 
					MASTER_CONTAINER.TYPE_ KD_TYPE,
					'' TGL_BONGKAR, '' EMPTY_SD,
					HISTORY_CONTAINER.STATUS_CONT, 
					'' BP_ID, 
					'' NO_UKK, 
					'DEPO' ASAL_CONT, 
					blocking_area.NAME BLOK_, 
					placement.ROW_ ROW_, 
					placement.SLOT_ SLOT_, 
					placement.TIER_ TIER_,
					'' VOYAGE_IN, 
					MASTER_CONTAINER.NO_BOOKING NM_KAPAL, 
					'' NM_AGEN, 
					'' TGL_STACK
			FROM MASTER_CONTAINER
			INNER JOIN HISTORY_CONTAINER
			ON MASTER_CONTAINER.NO_CONTAINER = HISTORY_CONTAINER.NO_CONTAINER
			AND HISTORY_CONTAINER.TGL_UPDATE = (SELECT MAX(HISTORY_CONTAINER.TGL_UPDATE) FROM HISTORY_CONTAINER WHERE NO_CONTAINER LIKE '%$no_cont%' )
			--AND MASTER_CONTAINER.COUNTER = HISTORY_CONTAINER.COUNTER
			left JOIN 
			PLACEMENT
			ON placement.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
			left JOIN
			BLOCKING_AREA 
			ON blocking_area.ID = placement.ID_BLOCKING_AREA
			WHERE 
			MASTER_CONTAINER.LOCATION = 'IN_YARD'
			AND HISTORY_CONTAINER.STATUS_CONT = 'MTY'
			AND master_container.NO_CONTAINER NOT IN (SELECT container_stuffing.NO_CONTAINER FROM container_stuffing where container_stuffing.no_container LIKE '%$no_cont%' AND container_stuffing.AKTIF = 'Y' )
			AND MASTER_CONTAINER.NO_CONTAINER LIKE '%$no_cont%'
			AND HISTORY_CONTAINER.KEGIATAN IN ('REALISASI STRIPPING', 'GATE IN', 'REQUEST DELIVERY', 'REQUEST BATALMUAT')
			-- UNION ALL
			 --select NO_CONTAINER, KD_SIZE,KD_TYPE,
			--TGL_BONGKAR, TO_CHAR(TO_DATE(TGL_STACK,'dd-mm-rrrr')+4,'dd-mm-rrrr') EMPTY_SD, STATUS_CONT, BP_ID, NO_UKK, ASAL_CONT,
			--BLOK_, SLOT_, ROW_, TIER_, VOYAGE_IN, NM_KAPAL, NM_AGEN, 
			--CASE SIKLUS WHEN 'BONGKAR'
			--THEN TGL_STACK
		--	ELSE
		--		TGL_STACK
		--	END AS TGL_STACK
		--	from V_MTY_AREA_TPK_NEW
		--	WHERE NO_CONTAINER LIKE '%$no_cont%' ";
}	

/* $result =   " SELECT NO_CONTAINER, 
                          SIZE_ AS SIZE_, 
                          TYPE_ AS TYPE_
                   FROM MASTER_CONTAINER  
                   WHERE NO_CONTAINER LIKE '%$no_cont%' AND LOCATION LIKE 'GATO' 
                   AND NO_CONTAINER NOT IN (SELECT NO_CONTAINER FROM history_container WHERE  NO_CONTAINER LIKE '%$no_cont%') 
                   AND NO_CONTAINER NOT IN (SELECT NO_CONTAINER FROM PLAN_CONTAINER_STUFFING WHERE AKTIF = 'Y' AND NO_CONTAINER LIKE '%$no_cont%')";
   SELECT TTD_BP_CONT.CONT_NO_BP NO_CONTAINER,
                     TTD_BP_CONT.KD_SIZE,
                     TTD_BP_CONT.KD_TYPE,
                     TO_CHAR (TTD_BP_CONFIRM.CONFIRM_DATE, 'DD-MM-YYYY') AS TGL_BONGKAR,
                     TTD_BP_CONT.KD_STATUS_CONT STATUS_CONT,
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
                     AND TTD_BP_CONT.KD_STATUS_CONT = 'MTY' */      	
			
//echo $result;
//print_r($result); exit();
$rs  = $db->query($result);
$rowxcont=$rs->getAll();

// End Receive DARI TPK //

echo json_encode($rowxcont);


?>