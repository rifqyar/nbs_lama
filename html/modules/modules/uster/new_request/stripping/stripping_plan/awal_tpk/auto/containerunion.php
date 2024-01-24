<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");

	

	$result  = "SELECT DISTINCT MASTER_CONTAINER.NO_CONTAINER,
									MASTER_CONTAINER.SIZE_ KD_SIZE,
									MASTER_CONTAINER.TYPE_ KD_TYPE,
									'' TGL_BONGKAR,
									'' TGL_MASAI,
									HISTORY_CONTAINER.STATUS_CONT KD_STATUS_CONT,
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
									'' TGL_STACK,
									'' NO_BOOKING
					  FROM MASTER_CONTAINER
						   INNER JOIN HISTORY_CONTAINER
							  ON MASTER_CONTAINER.NO_CONTAINER = HISTORY_CONTAINER.NO_CONTAINER
								 AND HISTORY_CONTAINER.TGL_UPDATE =
										(SELECT MAX (HISTORY_CONTAINER.TGL_UPDATE)
										   FROM HISTORY_CONTAINER
										  WHERE NO_CONTAINER LIKE '%$no_cont%')
						   LEFT JOIN PLACEMENT
							  ON placement.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
						   LEFT JOIN BLOCKING_AREA
							  ON blocking_area.ID = placement.ID_BLOCKING_AREA
					 WHERE MASTER_CONTAINER.LOCATION = 'IN_YARD'
						   AND HISTORY_CONTAINER.STATUS_CONT = 'FCL'
						   AND master_container.NO_CONTAINER NOT IN
								  (SELECT container_stuffing.NO_CONTAINER
									 FROM container_stuffing
									WHERE container_stuffing.no_container LIKE '%$no_cont%'
										  AND container_stuffing.AKTIF = 'Y')
						   AND MASTER_CONTAINER.NO_CONTAINER LIKE '%$no_cont%'
						   AND HISTORY_CONTAINER.KEGIATAN IN
								  ('REQUEST BATALMUAT', 'REALISASI STUFFING')
                UNION                
                SELECT TTD_BP_CONT.CONT_NO_BP NO_CONTAINER,
                        TTD_BP_CONT.KD_SIZE,
                        TTD_BP_CONT.KD_TYPE,
                        --TO_CHAR (TTD_BP_CONFIRM.CONFIRM_DATE, 'DD-MM-YYYY') AS TGL_BONGKAR,
                        --TO_CHAR (TTD_BP_CONFIRM.CONFIRM_DATE+2, 'DD-MM-YYYY') AS TGL_MASAI,
                         --CASE WHEN TO_DATE(TTD_BP_CONFIRM.CONFIRM_DATE, 'DD-MM-YYYY') < TO_DATE('01/04/2013', 'DD-MM-YYYY')
                          --THEN '01/04/2013'
                           --ELSE 
						   TO_CHAR(TTD_BP_CONFIRM.CONFIRM_DATE,'DD-MM-YYYY') AS TGL_BONGKAR,
                           --CASE WHEN TO_DATE(TTD_BP_CONFIRM.CONFIRM_DATE, 'DD-MM-YYYY') < TO_DATE('01/04/2013', 'DD-MM-YYYY')
                          --THEN  '05/04/2013' 
                           --ELSE                           
						   CASE WHEN TTD_BP_CONFIRM.CONFIRM_DATE+4 >= SYSDATE THEN  TO_CHAR(TTD_BP_CONFIRM.CONFIRM_DATE+4,'DD-MM-YYYY')
						   ELSE
						   TO_CHAR(SYSDATE,'DD-MM-YYYY') END AS TGL_MASAI, 
                        --'01/04/2013' AS TGL_BONGKAR,
                        --'03/04/2013' AS TGL_MASAI,
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
                        --,V_BOOKING_STACK_TPK.NO_BOOKING
                        ,PETIKEMAS_CABANG.TTM_BP_CONT.BP_ID NO_BOOKING
                        FROM PETIKEMAS_CABANG.TTM_BP_CONT TTM_BP_CONT,
                        PETIKEMAS_CABANG.TTD_BP_CONT TTD_BP_CONT,
                        PETIKEMAS_CABANG.V_PKK_CONT V_PKK_CONT,
                        PETIKEMAS_CABANG.TTD_BP_CONFIRM TTD_BP_CONFIRM,
                        PETIKEMAS_CABANG.MST_YARD_CONT_LAPANGAN YARD
                        -- PETIKEMAS_CABANG.V_BOOKING_STACK_TPK
                        WHERE TTM_BP_CONT.BP_ID = TTD_BP_CONT.BP_ID
                        AND TTM_BP_CONT.NO_UKK = V_PKK_CONT.NO_UKK
                        AND TTD_BP_CONT.CONT_NO_BP = TTD_BP_CONFIRM.CONT_NO_BP
                        AND TTM_BP_CONT.NO_UKK = TTD_BP_CONFIRM.NO_UKK
                        AND TTD_BP_CONT.BP_ID = TTD_BP_CONFIRM.BP_ID
                        AND TTM_BP_CONT.KD_CABANG = '05'
                        AND TTD_BP_CONT.STATUS_CONT = '03'
                        AND YARD.ARE_ID = TTD_BP_CONT.ARE_ID
                        --AND V_PKK_CONT.KD_KAPAL = V_BOOKING_STACK_TPK.KD_KAPAL
                        --AND V_PKK_CONT.VOYAGE_IN = V_BOOKING_STACK_TPK.VOYAGE_IN
                        --AND V_PKK_CONT.VOYAGE_OUT = V_BOOKING_STACK_TPKK_TPK.VOYAGE_OUT
                        AND TTD_BP_CONT.CONT_NO_BP LIKE '%$no_cont%'
                        AND TTD_BP_CONT.KD_STATUS_CONT = 'FCL'
                        ORDER BY ASAL_CONT ASC
                      
";      
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