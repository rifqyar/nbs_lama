<?php
$no_cont		= strtoupper($_GET["NO_CONTAINER"]);

$db 			= getDB("storage");
$voy            = $_GET["VOYAGE"];
$vessel         = $_GET["VESSEL"];
$idvsb          = $_GET["ID_VSB"]; 
	

	$result  = "SELECT DISTINCT MASTER_CONTAINER.NO_CONTAINER,
									TO_NUMBER(MASTER_CONTAINER.SIZE_) KD_SIZE,
									MASTER_CONTAINER.TYPE_ KD_TYPE,
									'' TGL_BONGKAR,
									'' TGL_MASAI,
									HISTORY_CONTAINER.STATUS_CONT KD_STATUS_CONT,
									'' BP_ID,
									0 NO_UKK,
									'DEPO' ASAL_CONT,
									'' VOYAGE_IN,
									MASTER_CONTAINER.NO_BOOKING NM_KAPAL,
									'' NM_AGEN,
									'' TGL_STACK,
									'' NO_BOOKING,
                                    BLOCKING_AREA.NAME YD_BLOCK,
                                    to_char(placement.SLOT_) YD_SLOT,
                                    to_char(placement.ROW_) YD_ROW,
                                    to_char(placement.TIER_) YD_TIER
					  FROM MASTER_CONTAINER
						   INNER JOIN HISTORY_CONTAINER
							  ON MASTER_CONTAINER.NO_CONTAINER = HISTORY_CONTAINER.NO_CONTAINER
								 AND HISTORY_CONTAINER.TGL_UPDATE =
										(SELECT MAX (HISTORY_CONTAINER.TGL_UPDATE)
										   FROM HISTORY_CONTAINER
										  WHERE NO_CONTAINER LIKE '$no_cont%')
						   LEFT JOIN PLACEMENT
							  ON placement.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
						   LEFT JOIN BLOCKING_AREA
							  ON blocking_area.ID = placement.ID_BLOCKING_AREA
					 WHERE MASTER_CONTAINER.LOCATION = 'IN_YARD'
						   AND HISTORY_CONTAINER.STATUS_CONT = 'FCL'
						   AND master_container.NO_CONTAINER NOT IN
								  (SELECT container_stuffing.NO_CONTAINER
									 FROM container_stuffing
									WHERE container_stuffing.no_container LIKE '$no_cont%'
										  AND container_stuffing.AKTIF = 'Y')
						   AND MASTER_CONTAINER.NO_CONTAINER LIKE '$no_cont%'
						   AND HISTORY_CONTAINER.KEGIATAN IN
								  ('REQUEST BATALMUAT', 'REALISASI STUFFING')
                UNION                
                SELECT A.NO_CONTAINER,
               A.SIZE_CONT KD_SIZE,
               DECODE(NVL(A.OVER_LENGTH,0)+NVL(A.OVER_WIDTH,0)+NVL(A.OVER_HEIGHT,0)+NVL(A.OVER_FRONT,0)+NVL(A.OVER_REAR,0)+NVL(A.OVER_LEFT,0)+NVL(A.OVER_RIGHT,0),0,A.TYPE_CONT,'OVD') KD_TYPE,
               TO_CHAR (TO_DATE (NVL(A.VESSEL_CONFIRM, A.GATE_IN_DATE), 'YYYYMMDDHH24MISS'),
                        'DD-MM-YYYY')
                  VESSEL_CONFIRM,
                CASE WHEN TO_DATE (A.VESSEL_CONFIRM, 'YYYYMMDDHH24MISS')+4 >= SYSDATE THEN  TO_CHAR (TO_DATE (A.VESSEL_CONFIRM, 'YYYYMMDDHH24MISS')+4,
                        'DD-MM-YYYY')
                               ELSE
                               TO_CHAR(SYSDATE,'DD-MM-YYYY') END AS TGL_MASAI,
               CASE WHEN A.STATUS = 'FULL' THEN 'FCL'
               ELSE 'MTY'
               END  AS KD_STATUS_CONT,
               'BP' || A.VESSEL_CODE || B.ID_VSB_VOYAGE AS BP_ID,
               B.ID_VSB_VOYAGE NO_UKK,
               'TPK' ASAL_CONT,
               B.VOYAGE_IN,
               B.VESSEL NM_KAPAL,
               B.OPERATOR_NAME NM_AGEN,
               TO_CHAR (TO_DATE (A.YARD_CONFIRM, 'YYYYMMDDHH24MISS'),
                        'DD-MM-YYYY')
                  TGL_STACK,
               'BP' || A.VESSEL_CODE || B.ID_VSB_VOYAGE AS NO_BOOKING,
               A.YD_BLOCK,
               A.YD_SLOT,
               A.YD_ROW,
               A.YD_TIER
          FROM    M_CYC_CONTAINER@DBINT_LINK A
               LEFT JOIN
                  M_VSB_VOYAGE@DBINT_LINK B
               ON     A.VESSEL_CODE = B.VESSEL_CODE
                  AND A.VOYAGE_IN = B.VOYAGE_IN
                  AND A.VOYAGE_OUT = B.VOYAGE_OUT
         WHERE     A.VOYAGE_IN = '$voy'
                AND A.E_I = 'I'
               and B.ID_VSB_VOYAGE ='$idvsb'
               AND A.STATUS IN ('FULL','L')
               AND A.NO_CONTAINER LIKE '$no_cont%'
               AND A.ACTIVE = 'Y'
               AND (UPPER (A.CONT_LOCATION) = UPPER ('YARD')
                    OR UPPER (A.CONT_LOCATION) = UPPER ('CHASSIS'))
               AND (HOLD_STATUS <> 'Y' OR hold_status IS NULL)";      
//echo $result;
//print_r($result); exit();
$rs  = $db->query($result);
$rowxcont=$rs->getAll();
/* if(count($rowxcont) == 0) {
	$result = "select * from (select 
				c.no_ukk as pkk,
				c.nm_kapal as kapal,
				c.voyage_in as voyage,
				c.nm_agen as agen,
				a.bp_id as bp_id,
				b.cont_no_bp as no_container,
				b.kd_size as size_cont,
				b.kd_type as type_cont,
				b.kd_status_cont as status_cont,
				b.status_cont as position,
				d.confirm_date as start_stack
				from 
				ttm_bp_cont a,
				ttd_bp_cont b,
				v_pkk_cont c,
				ttd_bp_confirm d
				where 
				a.kd_cabang = '05'
				and a.bp_id = b.bp_id
				and a.no_ukk = c.no_ukk
				and a.kd_cabang = c.kd_cabang 
				and b.status_cont = '03'
				and d.bp_id = b.bp_id
				and d.cont_no_bp = b.cont_no_bp
				and b.kd_status_cont = 'FCL'
				and b.cont_no_bp = '$no_cont'
				--and to_date (d.confirm_date,'DD/MM/YYYY') < to_date ('04/01/2013','DD/MM/YYYY') 
				UNION ALL -- RECEIVING KAPAL NOTHING DI CY, gate sebelum 1 April 2013
				select c.no_ukk as pkk,
				c.nm_kapal as kapal,
				c.voyage_in as voyage,
				c.nm_agen as nama_agen,
				a.kd_pmb as bp_id,
				b.no_container as no_container,
				b.kd_size as size_cont,
				b.kd_type_cont as type_cont,
				b.kd_jenis_pemilik as status_cont,
				b.status_pmb_dtl as position,
				b.tgl_gate as  start_stack
				from 
				tth_cont_exbspl a,
				ttd_cont_exbspl b,
				v_pkk_cont c
				where
				a.kd_cabang = '05' 
				and a.kd_pmb = b.kd_pmb
				and a.kd_cabang = c.kd_cabang
				and a.no_ukk = c.no_ukk
				and a.no_ukk = 'NTH05000001'
				and b.status_pmb_dtl not in ('0','4')
				and b.status_pp = 'T'
				and b.kd_jenis_pemilik = 'FCL'
				and b.no_container = '$no_cont'
				and b.no_container is not null)";
	
	$rs  = $db->query($result);
	$rowxcont=$rs->getAll();

} */


// End Receive DARI TPK //

echo json_encode($rowxcont);


?>