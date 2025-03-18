<?php
	$no_cont = $_GET["NO_CONT"];
	$act = $_GET["ACT"];
	$db = getDB("storage");
	$tl = xliteTemplate("get_detail.htm");
				
	if($act == "handling"){
		$q_detail = "SELECT MC.NO_CONTAINER, PL.STATUS, HC.ID_YARD ,YAR.NAMA_YARD, HC.KEGIATAN, HC.TGL_UPDATE, HC.NO_BOOKING NO_BOOKING, HC.ID_USER, MU.NAMA_LENGKAP, RR.NO_REQUEST NO_REQ_REC, 
				RST.NO_REQUEST NO_REQ_STR, RD.NO_REQUEST NO_REQ_DEL, RSF.NO_REQUEST NO_REQ_STF
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
                       INNER JOIN  V_MST_PBM d ON a.KD_CONSIGNEE = d.KD_PBM
                       INNER JOIN CONTAINER_RECEIVING cr ON A.NO_REQUEST = CR.NO_REQUEST                       
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
					INNER JOIN v_mst_pbm emkl ON REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
					WHERE REQUEST_DELIVERY.NO_REQUEST = '$no_req_del'
					AND CONTAINER_DELIVERY.NO_REQUEST = '$no_cont'";
		$r_del = $db->query($q_del);
		$r_de = $r_del->getAll();
		$tl->assign('r_del',$r_de);
	}
	else if($act == "placement"){
		$q_placement = "SELECT PLACEMENT.*, MASTER_USER.NAMA_LENGKAP, BLOCKING_AREA.NAME, YARD_AREA.NAMA_YARD FROM 
						PLACEMENT INNER JOIN BLOCKING_AREA
						ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID
						INNER JOIN YARD_AREA ON BLOCKING_AREA.ID_YARD_AREA = YARD_AREA.ID
						INNER JOIN MASTER_USER ON PLACEMENT.USER_NAME = MASTER_USER.ID WHERE NO_CONTAINER = '$no_cont'
						ORDER BY PLACEMENT.TGL_PLACEMENT ASC";
		$r_place = $db->query($q_placement);
		$r_pl = $r_place->getAll();
		$tl->assign('r_pl',$r_pl);		
	}
	
	$tl->renderToScreen();
?>