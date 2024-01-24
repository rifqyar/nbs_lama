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

    $result =  "SELECT DISTINCT MASTER_CONTAINER.NO_CONTAINER, 
                    MASTER_CONTAINER.SIZE_ KD_SIZE, 
                    MASTER_CONTAINER.TYPE_ KD_TYPE,
                    '' TGL_BONGKAR, 
                    '' EMPTY_SD,
                    HISTORY_CONTAINER.STATUS_CONT, 
                    '' BP_ID, 
                    0 NO_UKK, 
                    'DEPO' ASAL_CONT, 
                    blocking_area.NAME BLOK_, 
                    TO_CHAR(placement.SLOT_) SLOT_, 
                    TO_CHAR(placement.ROW_) ROW_, 
                    TO_CHAR(placement.TIER_) TIER_,
                    '' VOYAGE_IN,
                    '' VOYAGE_OUT, 
                    '' NM_KAPAL, 
                    '' NM_AGEN, 
                    '' CALL_SIGN,
                    '' VESSEL_CODE,
                    '' TGL_STACK,
                    '' NO_BOOKING
            FROM MASTER_CONTAINER
            INNER JOIN HISTORY_CONTAINER
            ON MASTER_CONTAINER.NO_CONTAINER = HISTORY_CONTAINER.NO_CONTAINER
            AND HISTORY_CONTAINER.TGL_UPDATE = (SELECT MAX(HISTORY_CONTAINER.TGL_UPDATE) FROM HISTORY_CONTAINER WHERE NO_CONTAINER LIKE '$no_cont%' )
            left JOIN 
            PLACEMENT
            ON placement.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
            left JOIN
            BLOCKING_AREA 
            ON blocking_area.ID = placement.ID_BLOCKING_AREA
            WHERE 
            MASTER_CONTAINER.LOCATION = 'IN_YARD'
            AND HISTORY_CONTAINER.STATUS_CONT = 'MTY'
            AND master_container.NO_CONTAINER NOT IN (SELECT container_stuffing.NO_CONTAINER FROM container_stuffing where container_stuffing.no_container LIKE '$no_cont%' AND container_stuffing.AKTIF = 'Y' )
            AND MASTER_CONTAINER.NO_CONTAINER LIKE '$no_cont%'
            AND HISTORY_CONTAINER.KEGIATAN IN ('REALISASI STRIPPING', 'GATE IN', 'REQUEST DELIVERY', 'REQUEST BATALMUAT','BATAL STUFFING')
            AND placement.SLOT_ = (SELECT max(SLOT_) FROM PLACEMENT WHERE NO_CONTAINER LIKE '$no_cont%' ) 
         --    UNION ALL
         --    SELECT A.NO_CONTAINER,
         --       TO_CHAR(A.SIZE_CONT) KD_SIZE,
         --       A.TYPE_CONT KD_TYPE,
         --       TO_CHAR (TO_DATE (A.VESSEL_CONFIRM, 'YYYYMMDDHH24MISS'),
         --                'DD-MM-YYYY')
         --          VESSEL_CONFIRM,
         --        CASE WHEN TO_DATE (A.VESSEL_CONFIRM, 'YYYYMMDDHH24MISS')+4 >= SYSDATE THEN  TO_CHAR (TO_DATE (A.VESSEL_CONFIRM, 'YYYYMMDDHH24MISS')+4,
         --                'DD-MM-YYYY')
         --                       ELSE
         --                       TO_CHAR(SYSDATE,'DD-MM-YYYY') END AS TGL_MASAI,
         --       CASE WHEN A.STATUS = 'FULL' THEN 'FCL'
         --       ELSE 'MTY'
         --       END  AS KD_STATUS_CONT,
         --       'BP' || A.VESSEL_CODE || B.ID_VSB_VOYAGE AS BP_ID,
         --       B.ID_VSB_VOYAGE NO_UKK,
         --       'TPK' ASAL_CONT,
         --       A.YD_BLOCK BLOK_,
         --       A.YD_SLOT SLOT_,
         --       A.YD_ROW ROW_,
         --       A.YD_TIER TIER_,
         --       B.VOYAGE_IN,
         --       B.VOYAGE_OUT,
         --       B.VESSEL NM_KAPAL,
         --       B.OPERATOR_NAME NM_AGEN,
         --       B.CALL_SIGN,
         --       B.VESSEL_CODE,
         --       TO_CHAR (TO_DATE (A.YARD_CONFIRM, 'YYYYMMDDHH24MISS'),
         --                'DD-MM-YYYY')
         --          TGL_STACK,
         --       'BP' || A.VESSEL_CODE || B.ID_VSB_VOYAGE AS NO_BOOKING
         --  FROM    M_CYC_CONTAINER@DBINT_LINK A
         --       LEFT JOIN
         --          M_VSB_VOYAGE@DBINT_LINK B
         --       ON     A.VESSEL_CODE = B.VESSEL_CODE
         --          AND A.VOYAGE_IN = B.VOYAGE_IN
         --          AND A.VOYAGE_OUT = B.VOYAGE_OUT
         -- WHERE A.E_I = 'I'
         --       AND A.STATUS = 'EMPTY'
         --       AND A.NO_CONTAINER LIKE '$no_cont%'
         --       AND A.ACTIVE = 'Y'
         --       AND (UPPER (A.CONT_LOCATION) = UPPER ('YARD')
         --            OR UPPER (A.CONT_LOCATION) = UPPER ('CHASSIS'))
         --       AND (HOLD_STATUS <> 'Y' OR hold_status IS NULL)
         ";
}	
			
//echo $result;
//print_r($result); exit();
$rs  = $db->query($result);
$rowxcont=$rs->getAll();

// End Receive DARI TPK //

echo json_encode($rowxcont);


?>