<?php
	$no_cont = $_GET["NO_CONT"];
	$kd_pbm = $_GET["KD_PBM"];
	$act = $_GET["ACT"];
	$db = getDB("storage");
	$tl = xliteTemplate("get_detail.htm");
				
	if($act == "handling"){
		$q_detail = "SELECT MC.NO_CONTAINER, PL.STATUS, HC.ID_YARD ,YAR.NAMA_YARD, HC.KEGIATAN, HC.TGL_UPDATE, HC.NO_BOOKING NO_BOOKING, HC.ID_USER, MU.NAMA_LENGKAP, RR.NO_REQUEST NO_REQ_REC, 
				RST.NO_REQUEST NO_REQ_STR, RD.NO_REQUEST NO_REQ_DEL, RSF.NO_REQUEST NO_REQ_STF, REL.NO_REQUEST NO_REQ_REL
				FROM MASTER_CONTAINER MC INNER JOIN HISTORY_CONTAINER HC
				ON MC.NO_CONTAINER = HC.NO_CONTAINER 
				AND MC.NO_BOOKING = HC.NO_BOOKING
				AND MC.COUNTER = HC.COUNTER
				LEFT JOIN REQUEST_RECEIVING RR
				ON RR.NO_REQUEST = HC.NO_REQUEST
				LEFT JOIN REQUEST_STRIPPING RST
				ON RST.NO_REQUEST = HC.NO_REQUEST
				LEFT JOIN REQUEST_DELIVERY RD
				ON RD.NO_REQUEST = HC.NO_REQUEST
				LEFT JOIN REQUEST_STUFFING RSF
				ON RSF.NO_REQUEST = HC.NO_REQUEST
				LEFT JOIN REQUEST_RELOKASI REL
				ON REL.NO_REQUEST = HC.NO_REQUEST
				LEFT JOIN PLACEMENT PL ON PL.NO_CONTAINER = HC.NO_CONTAINER
				LEFT JOIN YARD_AREA YAR ON HC.ID_YARD = YAR.ID
				LEFT JOIN MASTER_USER MU ON MU.ID = HC.ID_USER
				WHERE MC.NO_CONTAINER = '$no_cont'";
		$res_detail = $db->query($q_detail);
		$r_det = $res_detail->getAll();
		$tl->assign('r_det',$r_det);
	}
	else if($act == "stripping"){
		$q_get_req_str = "SELECT MC.NO_CONTAINER, HC.KEGIATAN, HC.NO_REQUEST
				FROM MASTER_CONTAINER MC INNER JOIN HISTORY_CONTAINER HC
				ON MC.NO_CONTAINER = HC.NO_CONTAINER 
				AND MC.NO_BOOKING = HC.NO_BOOKING
				AND MC.COUNTER = HC.COUNTER 
				WHERE MC.NO_CONTAINER = '$no_cont' AND HC.KEGIATAN = 'REQUEST STRIPPING'";
		$res_detail1 = $db->query($q_get_req_str);
		$r_req = $res_detail1->fetchRow();
		$no_req_str = $r_req["NO_REQUEST"];
		
		$q_strip = "SELECT REQUEST_STRIPPING.NO_REQUEST, 
				REQUEST_STRIPPING.TGL_REQUEST,
				REQUEST_STRIPPING.NO_DO,
				REQUEST_STRIPPING.NO_BL,
				REQUEST_STRIPPING.PERP_KE,                                                                
				REQUEST_STRIPPING.TYPE_STRIPPING,
			   CONTAINER_STRIPPING.TGL_APPROVE TGL_APPROVE,
			   CONTAINER_STRIPPING.TGL_REALISASI,
			   emkl.NM_PBM AS NAMA_CONSIGNEE,
			   pnmt.NM_PBM AS NAMA_PENUMPUK,
			   REQUEST_RECEIVING.NO_SPPB AS NO_SPPB,
			   REQUEST_RECEIVING.TGL_SPPB AS TGL_SPPB
				FROM REQUEST_STRIPPING INNER JOIN V_MST_PBM emkl ON REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM 
				JOIN V_MST_PBM pnmt ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM
				JOIN REQUEST_RECEIVING ON REQUEST_RECEIVING.NO_REQUEST = REQUEST_STRIPPING.NO_REQUEST_RECEIVING
				JOIN CONTAINER_STRIPPING ON CONTAINER_STRIPPING.NO_REQUEST = REQUEST_STRIPPING.NO_REQUEST
				WHERE REQUEST_STRIPPING.NO_REQUEST = '$no_req_str'
                AND CONTAINER_STRIPPING.NO_CONTAINER = '$no_cont'";
		$r_strip = $db->query($q_strip);
		$r_s = $r_strip->getAll();
		$tl->assign('r_str',$r_s);
	}
	
	else if($act == "receiving"){
		$q_get_req_rec = "SELECT MC.NO_CONTAINER, HC.KEGIATAN, HC.NO_REQUEST
				FROM MASTER_CONTAINER MC INNER JOIN HISTORY_CONTAINER HC
				ON MC.NO_CONTAINER = HC.NO_CONTAINER 
				AND MC.NO_BOOKING = HC.NO_BOOKING
				AND MC.COUNTER = HC.COUNTER 
				WHERE MC.NO_CONTAINER = '$no_cont' AND HC.KEGIATAN = 'REQUEST RECEIVING'";
		$res_detail2 = $db->query($q_get_req_rec);
		$r_req2 = $res_detail2->fetchRow();
		$no_req_rec = $r_req2["NO_REQUEST"];
		//$no_req_rec = "REC1112000036";
		
		$q_rec = "SELECT DISTINCT a.NO_REQUEST AS NO_REQUEST,
								a.TGL_REQUEST,
                              a.KETERANGAN AS KETERANGAN,
                              a.RECEIVING_DARI AS RECEIVING_DARI,
                              A.PERALIHAN AS PERALIHAN,
                              a.KD_CONSIGNEE AS KD_CONSIGNEE,
                              A.NO_DO, A.NO_BL, A.NO_SPPB, A.TGL_SPPB,                              
                              d.NM_PBM AS CONSIGNEE,
                              d.NO_NPWP_PBM AS NO_NPWP_PBM,
                              d.ALMT_PBM AS ALMT_PBM
                       FROM   REQUEST_RECEIVING a
                       INNER JOIN CONTAINER_RECEIVING cr ON A.NO_REQUEST = CR.NO_REQUEST                       
                       LEFT JOIN  V_MST_PBM d ON a.KD_CONSIGNEE = d.KD_PBM
                       WHERE a.NO_REQUEST = '$no_req_rec' AND
                       CR.NO_CONTAINER = '$no_cont'";
		$r_rec = $db->query($q_rec);
		$r_r = $r_rec->getAll();
		$tl->assign('r_rec',$r_r);
	}
	
	else if($act == "stuffing"){
		$q_get_req_stu = "SELECT MC.NO_CONTAINER, HC.KEGIATAN, HC.NO_REQUEST
				FROM MASTER_CONTAINER MC INNER JOIN HISTORY_CONTAINER HC
				ON MC.NO_CONTAINER = HC.NO_CONTAINER 
				AND MC.NO_BOOKING = HC.NO_BOOKING
				AND MC.COUNTER = HC.COUNTER 
				WHERE MC.NO_CONTAINER = '$no_cont' AND HC.KEGIATAN = 'REQUEST STUFFING'";
		$res_detail3 = $db->query($q_get_req_stu);
		$r_req3 = $res_detail3->fetchRow();
		$no_req_stu = $r_req3["NO_REQUEST"];
		
		$q_stu = "SELECT CS.NO_CONTAINER, CS.ASAL_CONT, rs.*,
                        EMKL.NM_PBM
                       FROM REQUEST_STUFFING rs
                       INNER JOIN CONTAINER_STUFFING cs ON RS.NO_REQUEST = CS.NO_REQUEST 
                       INNER JOIN V_MST_PBM emkl ON rs.KD_CONSIGNEE = EMKL.KD_PBM 
                       AND rs.KD_PENUMPUKAN_OLEH = EMKL.KD_PBM
                       WHERE rs.NO_REQUEST = '$no_req_stu'
                       AND CS.NO_CONTAINER = '$no_cont'";
		$r_stu = $db->query($q_stu);
		$r_st = $r_stu->getAll();
		$tl->assign('r_stu',$r_st);
	}
	
	else if($act == "delivery"){
		$q_get_req_del = "SELECT MC.NO_CONTAINER, HC.KEGIATAN, HC.NO_REQUEST
				FROM MASTER_CONTAINER MC INNER JOIN HISTORY_CONTAINER HC
				ON MC.NO_CONTAINER = HC.NO_CONTAINER 
				AND MC.NO_BOOKING = HC.NO_BOOKING
				AND MC.COUNTER = HC.COUNTER 
				WHERE MC.NO_CONTAINER = '$no_cont' AND HC.KEGIATAN = 'REQUEST DELIVERY'";
		$res_detail4 = $db->query($q_get_req_del);
		$r_req4 = $res_detail4->fetchRow();
		$no_req_del = $r_req4["NO_REQUEST"];
		
		$q_del = "SELECT CONTAINER_DELIVERY.*, REQUEST_DELIVERY.*, request_delivery.DELIVERY_KE, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR(REQUEST_DELIVERY.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, emkl.ALMT_PBM, emkl.NO_NPWP_PBM, request_delivery.VESSEL, request_delivery.VOYAGE
					FROM REQUEST_DELIVERY INNER JOIN CONTAINER_DELIVERY ON REQUEST_DELIVERY.NO_REQUEST = CONTAINER_DELIVERY.NO_REQUEST
					LEFT JOIN v_mst_pbm emkl ON REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
					WHERE REQUEST_DELIVERY.NO_REQUEST = '$no_req_del'
					AND CONTAINER_DELIVERY.NO_CONTAINER = '$no_cont'";
		$r_del = $db->query($q_del);
		$r_de = $r_del->getAll();
		$tl->assign('r_del',$r_de);
	}
	else if($act == "placement"){
		$q_placement = "SELECT HISTORY_PLACEMENT.*, MASTER_USER.NAMA_LENGKAP, BLOCKING_AREA.NAME, YARD_AREA.NAMA_YARD FROM 
						HISTORY_PLACEMENT INNER JOIN BLOCKING_AREA
						ON HISTORY_PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID
						INNER JOIN YARD_AREA ON BLOCKING_AREA.ID_YARD_AREA = YARD_AREA.ID
						INNER JOIN MASTER_USER ON HISTORY_PLACEMENT.NIPP_USER = MASTER_USER.NIPP WHERE NO_CONTAINER = '$no_cont'
						ORDER BY HISTORY_PLACEMENT.TGL_UPDATE ASC";
		$r_place = $db->query($q_placement);
		$r_pl = $r_place->getAll();
		$tl->assign('r_pl',$r_pl);		
	} else if($act == "detail_emkl"){
		$q_detail= "SELECT CONTAINER_STRIPPING.NO_CONTAINER, MASTER_CONTAINER.SIZE_, MASTER_CONTAINER.TYPE_, CONTAINER_STRIPPING.TGL_REALISASI,--HISTORY_CONTAINER.STATUS_CONT,
	BLOCKING_AREA.NAME BLOK_, PLACEMENT.SLOT_ SLOT, PLACEMENT.ROW_ ROW_, PLACEMENT.TIER_ TIER,
                    CONTAINER_STRIPPING.NO_REQUEST, REQUEST_STRIPPING.KD_CONSIGNEE, REQUEST_STRIPPING.CONSIGNEE_PERSONAL, V_MST_PBM.NM_PBM ,CONTAINER_STRIPPING.TGL_APPROVE, 
                    CONTAINER_STRIPPING.TGL_REALISASI FROM REQUEST_STRIPPING JOIN CONTAINER_STRIPPING
                    ON REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST
					JOIN MASTER_CONTAINER ON CONTAINER_STRIPPING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
					--LEFT JOIN HISTORY_CONTAINER ON MASTER_CONTAINER.NO_CONTAINER = HISTORY_CONTAINER.NO_CONTAINER AND MASTER_CONTAINER.COUNTER = HISTORY_CONTAINER.COUNTER
					--AND MASTER_CONTAINER.NO_BOOKING = HISTORY_CONTAINER.NO_BOOKING
                    LEFT JOIN V_MST_PBM ON REQUEST_STRIPPING.KD_CONSIGNEE = V_MST_PBM.KD_PBM
                    LEFT JOIN PLACEMENT ON CONTAINER_STRIPPING.NO_CONTAINER = PLACEMENT.NO_CONTAINER
                    LEFT JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID
                    WHERE CONTAINER_STRIPPING.TGL_APPROVE IS NOT NULL
                    AND REQUEST_STRIPPING.KD_CONSIGNEE = '$kd_pbm' ";
		$r_detail = $db->query($q_detail);
		$r_de = $r_detail->getAll();
		$tl->assign('r_de',$r_de);	
		$q_detail1= "SELECT a.JUMLAH BLM, V_MST_PBM.NM_PBM , V_MST_PBM.KD_PBM FROM REQUEST_STRIPPING, V_MST_PBM , (SELECT COUNT(CONTAINER_STRIPPING.NO_CONTAINER) JUMLAH FROM REQUEST_STRIPPING JOIN CONTAINER_STRIPPING
                    ON REQUEST_STRIPPING.NO_REQUEST = CONTAINER_STRIPPING.NO_REQUEST
                    LEFT JOIN V_MST_PBM ON REQUEST_STRIPPING.KD_CONSIGNEE = V_MST_PBM.KD_PBM
                    WHERE CONTAINER_STRIPPING.TGL_APPROVE IS NOT NULL
                    AND CONTAINER_STRIPPING.TGL_REALISASI IS NULL
                    AND REQUEST_STRIPPING.KD_CONSIGNEE = '$kd_pbm' ) a WHERE REQUEST_STRIPPING.KD_CONSIGNEE = V_MST_PBM.KD_PBM
                      AND REQUEST_STRIPPING.KD_CONSIGNEE = '$kd_pbm' GROUP BY a.JUMLAH,REQUEST_STRIPPING.CONSIGNEE_PERSONAL, V_MST_PBM.NM_PBM,V_MST_PBM.KD_PBM";
		$r_detail1 = $db->query($q_detail1);
		$r_de11 = $r_detail1->FetchRow();
		$tl->assign('r_de11',$r_de11);	
	}
	
	$tl->renderToScreen();
?>