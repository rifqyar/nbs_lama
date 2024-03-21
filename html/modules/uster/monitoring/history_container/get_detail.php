<?php
	$no_cont = $_GET["NO_CONT"];
	$act = $_GET["ACT"];
	$counter = $_GET["COUNTER"];
	$no_booking = $_GET["NO_BOOK"];
	$db = getDB("storage");
	$tl = xliteTemplate("get_detail.htm");
				 
	if($act == "handling"){
		if($no_booking == "VESSEL_NOTHING"){
			$q_detail = "SELECT MC.NO_CONTAINER, HC.STATUS_CONT, HC.ID_YARD ,YAR.NAMA_YARD, HC.KEGIATAN, 
				CASE WHEN HC.KEGIATAN = 'REALISASI STRIPPING'
                THEN (SELECT to_char(MAX(TGL_REALISASI),'DD-MM-YYYY hh24:mi')  FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = MC.NO_CONTAINER AND NO_REQUEST = RST.NO_REQUEST) 
                WHEN HC.KEGIATAN = 'REALISASI STUFFING'
                THEN (SELECT to_char(MAX(TGL_REALISASI),'DD-MM-YYYY hh24:mi') FROM CONTAINER_STUFFING WHERE NO_CONTAINER = MC.NO_CONTAINER AND NO_REQUEST = RSF.NO_REQUEST)
                WHEN HC.KEGIATAN = 'BORDER GATE IN'
                THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM BORDER_GATE_IN WHERE NO_CONTAINER = MC.NO_CONTAINER AND (NO_REQUEST = RST.NO_REQUEST OR NO_REQUEST = RSF.NO_REQUEST OR NO_REQUEST = RR.NO_REQUEST))
                 WHEN HC.KEGIATAN = 'BORDER GATE OUT'
                THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM BORDER_GATE_OUT WHERE NO_CONTAINER = MC.NO_CONTAINER AND (NO_REQUEST = RR.NO_REQUEST OR NO_REQUEST = RD.NO_REQUEST))
                WHEN HC.KEGIATAN = 'GATE OUT'
                THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM GATE_OUT WHERE NO_CONTAINER = MC.NO_CONTAINER AND (NO_REQUEST = RST.NO_REQUEST OR NO_REQUEST = RSF.NO_REQUEST OR NO_REQUEST = RR.NO_REQUEST OR NO_REQUEST = RD.NO_REQUEST))
                WHEN HC.KEGIATAN = 'GATE IN'
                THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM GATE_IN WHERE NO_CONTAINER = MC.NO_CONTAINER AND NO_REQUEST = RR.NO_REQUEST)
                WHEN HC.KEGIATAN = 'REQUEST DELIVERY'
                THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_DELIVERY WHERE NO_REQUEST = RD.NO_REQUEST)
                WHEN HC.KEGIATAN = 'REQUEST RECEIVING'
                THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_RECEIVING WHERE NO_REQUEST = RR.NO_REQUEST)
                WHEN HC.KEGIATAN = 'REQUEST STRIPPING'
                THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_STRIPPING WHERE NO_REQUEST = RST.NO_REQUEST)
                WHEN HC.KEGIATAN = 'REQUEST STUFFING'
                THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_STUFFING WHERE NO_REQUEST = RSF.NO_REQUEST)
                WHEN HC.KEGIATAN = 'REQUEST BATALMUAT'
                THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_BATAL_MUAT WHERE NO_REQUEST = RBM.NO_REQUEST)
                ELSE to_char(HC.TGL_UPDATE,'DD-MM-YYYY hh24:mi')
                END TGL_UPDATE, HC.NO_BOOKING NO_BOOKING, HC.ID_USER, MU.NAME NAMA_LENGKAP, RR.NO_REQUEST NO_REQ_REC, 
				RST.NO_REQUEST NO_REQ_STR, RD.NO_REQUEST NO_REQ_DEL, RSF.NO_REQUEST NO_REQ_STF, REL.NO_REQUEST NO_REQ_REL, RBM.NO_REQUEST NO_REQ_RBM
				FROM MASTER_CONTAINER MC INNER JOIN HISTORY_CONTAINER HC
				ON MC.NO_CONTAINER = HC.NO_CONTAINER 
				--AND MC.NO_BOOKING = HC.NO_BOOKING
				--AND MC.COUNTER = HC.COUNTER
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
                LEFT JOIN REQUEST_BATAL_MUAT RBM
                ON RBM.NO_REQUEST = HC.NO_REQUEST
				--LEFT JOIN PLACEMENT PL ON PL.NO_CONTAINER = HC.NO_CONTAINER
				LEFT JOIN YARD_AREA YAR ON HC.ID_YARD = YAR.ID
				LEFT JOIN BILLING.TB_USER MU ON to_char(MU.ID) = HC.ID_USER
				WHERE MC.NO_CONTAINER = '$no_cont'
				AND HC.NO_BOOKING = '$no_booking'
				AND HC.COUNTER = '$counter'
				ORDER BY HC.TGL_UPDATE DESC";
		} else {
		$q_detail = "SELECT MC.NO_CONTAINER, HC.STATUS_CONT, HC.ID_YARD ,YAR.NAMA_YARD, HC.KEGIATAN, 
				CASE WHEN HC.KEGIATAN = 'REALISASI STRIPPING'
                THEN (SELECT to_char(MAX(TGL_REALISASI),'DD-MM-YYYY hh24:mi')  FROM CONTAINER_STRIPPING WHERE NO_CONTAINER = MC.NO_CONTAINER AND NO_REQUEST = RST.NO_REQUEST) 
                WHEN HC.KEGIATAN = 'REALISASI STUFFING'
                THEN (SELECT to_char(MAX(TGL_REALISASI),'DD-MM-YYYY hh24:mi') FROM CONTAINER_STUFFING WHERE NO_CONTAINER = MC.NO_CONTAINER AND NO_REQUEST = RSF.NO_REQUEST)
                WHEN HC.KEGIATAN = 'BORDER GATE IN'
                THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM BORDER_GATE_IN WHERE NO_CONTAINER = MC.NO_CONTAINER AND (NO_REQUEST = RST.NO_REQUEST OR NO_REQUEST = RSF.NO_REQUEST OR NO_REQUEST = RR.NO_REQUEST))
                 WHEN HC.KEGIATAN = 'BORDER GATE OUT'
                THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM BORDER_GATE_OUT WHERE NO_CONTAINER = MC.NO_CONTAINER AND (NO_REQUEST = RR.NO_REQUEST OR NO_REQUEST = RD.NO_REQUEST))
                WHEN HC.KEGIATAN = 'GATE OUT'
                THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM GATE_OUT WHERE NO_CONTAINER = MC.NO_CONTAINER AND (NO_REQUEST = RST.NO_REQUEST OR NO_REQUEST = RSF.NO_REQUEST OR NO_REQUEST = RR.NO_REQUEST OR NO_REQUEST = RD.NO_REQUEST))
                WHEN HC.KEGIATAN = 'GATE IN'
                THEN (SELECT to_char(MAX(TGL_IN),'DD-MM-YYYY hh24:mi') FROM GATE_IN WHERE NO_CONTAINER = MC.NO_CONTAINER AND NO_REQUEST = RR.NO_REQUEST)
                WHEN HC.KEGIATAN = 'REQUEST DELIVERY'
                THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_DELIVERY WHERE NO_REQUEST = RD.NO_REQUEST)
                WHEN HC.KEGIATAN = 'REQUEST RECEIVING'
                THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_RECEIVING WHERE NO_REQUEST = RR.NO_REQUEST)
                WHEN HC.KEGIATAN = 'REQUEST STRIPPING'
                THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_STRIPPING WHERE NO_REQUEST = RST.NO_REQUEST)
                WHEN HC.KEGIATAN = 'REQUEST STUFFING'
                THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_STUFFING WHERE NO_REQUEST = RSF.NO_REQUEST)
                WHEN HC.KEGIATAN = 'REQUEST BATALMUAT'
                THEN (SELECT to_char(TGL_REQUEST,'DD-MM-YYYY hh24:mi') FROM REQUEST_BATAL_MUAT WHERE NO_REQUEST = RBM.NO_REQUEST)
                ELSE to_char(HC.TGL_UPDATE,'DD-MM-YYYY hh24:mi')
                END TGL_UPDATE, HC.NO_BOOKING NO_BOOKING, HC.ID_USER, MU.NAME NAMA_LENGKAP, RR.NO_REQUEST NO_REQ_REC, 
				RST.NO_REQUEST NO_REQ_STR, RD.NO_REQUEST NO_REQ_DEL, RSF.NO_REQUEST NO_REQ_STF, REL.NO_REQUEST NO_REQ_REL, RBM.NO_REQUEST NO_REQ_RBM
				FROM MASTER_CONTAINER MC INNER JOIN HISTORY_CONTAINER HC
				ON MC.NO_CONTAINER = HC.NO_CONTAINER 
				--AND MC.NO_BOOKING = HC.NO_BOOKING
				--AND MC.COUNTER = HC.COUNTER
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
                LEFT JOIN REQUEST_BATAL_MUAT RBM
                ON RBM.NO_REQUEST = HC.NO_REQUEST
				--LEFT JOIN PLACEMENT PL ON PL.NO_CONTAINER = HC.NO_CONTAINER
				LEFT JOIN YARD_AREA YAR ON HC.ID_YARD = YAR.ID
				LEFT JOIN BILLING.TB_USER MU ON TO_CHAR(MU.ID) = HC.ID_USER
				WHERE MC.NO_CONTAINER = '$no_cont'
				AND HC.NO_BOOKING = '$no_booking'
				AND HC.COUNTER = '$counter'
				ORDER BY HC.TGL_UPDATE DESC";
		}
		$res_detail = $db->query($q_detail);
		$r_det = $res_detail->getAll();		
		
		/*  foreach($r_det as $r_det){
			
			if($r_det["KEGIATAN"] == 'BORDER GATE OUT'){
				$qr = $db->query("SELECT TGL_IN FROM BORDER_GATE_OUT WHERE NO_CONTAINER = '".$r_det[NO_CONTAINER]."' AND NO_REQUEST = '".$r_det[NO_REQ_DEL]."'");
				$qre  = $qr->fetchRow();
				$rdt = $qre["TGL_IN"];
				$r_det["TGL_UPDATE"] = $rdt;
			}
			else {
				$r_det["TGL_UPDATE"] = $r_det["TGL_UPDATE"];
			}
		} */
		
		
		$tl->assign('r_det',$r_det);
	}
	else if($act == "stripping"){
		$q_get_req_str = "SELECT HC.NO_REQUEST FROM HISTORY_CONTAINER HC
				WHERE HC.NO_CONTAINER = '$no_cont' AND HC.NO_BOOKING = '$no_booking' AND HC.KEGIATAN = 'REQUEST STRIPPING' AND HC.COUNTER = '$counter'";
		$res_detail1 = $db->query($q_get_req_str);
		$r_req = $res_detail1->fetchRow();
		$no_req_str = $r_req["NO_REQUEST"];
        //echo $q_get_req_str; die();
		if($no_req_str == NULL){
			exit();
		}
		$q_strip = "SELECT DISTINCT REQUEST_STRIPPING.NO_REQUEST, 
                REQUEST_STRIPPING.TGL_REQUEST,
                REQUEST_STRIPPING.NO_DO,
                REQUEST_STRIPPING.NO_BL,
                REQUEST_STRIPPING.PERP_KE,
                REQUEST_STRIPPING.STATUS_REQ,
                REQUEST_STRIPPING.TYPE_STRIPPING,
                CONTAINER_STRIPPING.TGL_BONGKAR TGL_MULAI,
                CASE WHEN CONTAINER_STRIPPING.TGL_SELESAI IS NULL
                THEN CONTAINER_STRIPPING.TGL_BONGKAR+4
                 ELSE CONTAINER_STRIPPING.TGL_SELESAI
                 END  TGL_SELESAI,
                 CONTAINER_STRIPPING.TGL_APPROVE,
                 CONTAINER_STRIPPING.TGL_APP_SELESAI,
                 CONTAINER_STRIPPING.START_PERP_PNKN,
                 CONTAINER_STRIPPING.END_STACK_PNKN,
                CONTAINER_STRIPPING.TGL_REALISASI,
                CONTAINER_STRIPPING.ID_USER_REALISASI,
                MUS.NAME NAMA_LKP,
                emkl.NM_PBM AS NAMA_CONSIGNEE,
                pnmt.NM_PBM AS NAMA_PENUMPUK,
                NOTA_STRIPPING.NO_NOTA,
                NOTA_STRIPPING.NO_FAKTUR,
                CASE WHEN NOTA_STRIPPING.STATUS = 'BATAL'
					THEN 'BATAL'
					ELSE NOTA_STRIPPING.LUNAS                 
				END LUNAS
                FROM REQUEST_STRIPPING INNER JOIN v_mst_pbm emkl ON REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM AND emkl.KD_CABANG = '05'
                JOIN v_mst_pbm pnmt ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM AND pnmt.KD_CABANG = '05'
                JOIN REQUEST_RECEIVING ON REQUEST_RECEIVING.NO_REQUEST = REQUEST_STRIPPING.NO_REQUEST_RECEIVING
                JOIN CONTAINER_STRIPPING ON CONTAINER_STRIPPING.NO_REQUEST = REQUEST_STRIPPING.NO_REQUEST
                LEFT JOIN BILLING.TB_USER MUS ON CONTAINER_STRIPPING.ID_USER_REALISASI = MUS.ID
                LEFT JOIN NOTA_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST
                LEFT JOIN HISTORY_CONTAINER HC ON CONTAINER_STRIPPING.NO_CONTAINER = HC.NO_CONTAINER
                AND CONTAINER_STRIPPING.NO_REQUEST = HC.NO_REQUEST 
                WHERE CONTAINER_STRIPPING.NO_CONTAINER = '$no_cont' AND HC.NO_BOOKING = '$no_booking' AND HC.COUNTER = '$counter'
                order by REQUEST_STRIPPING.TGL_REQUEST desc";
        
		$r_strip = $db->query($q_strip);
		$r_s = $r_strip->getAll();
		$tl->assign('r_str',$r_s);
	}
	
	else if($act == "receiving"){
		if($no_booking == "VESSEL_NOTHING"){
		$q_get_req_rec = "SELECT HC.NO_REQUEST FROM HISTORY_CONTAINER HC
				WHERE HC.NO_CONTAINER = '$no_cont' AND HC.NO_BOOKING = '$no_booking' AND HC.COUNTER = '$counter' AND HC.KEGIATAN = 'REQUEST RECEIVING'";
		} else {
			$q_get_req_rec = "SELECT HC.NO_REQUEST FROM HISTORY_CONTAINER HC
				WHERE HC.NO_CONTAINER = '$no_cont' AND HC.NO_BOOKING = '$no_booking' AND  HC.KEGIATAN = 'REQUEST RECEIVING' AND HC.COUNTER = '$counter'";
		}
		$res_detail2 = $db->query($q_get_req_rec);
		$r_req2 = $res_detail2->fetchRow();
		$no_req_rec = $r_req2["NO_REQUEST"];
		//$no_req_rec = "REC1112000036";
		if($no_booking != "VESSEL_NOTHING"){
			$q_rec = "SELECT DISTINCT a.NO_REQUEST AS NO_REQUEST,
									a.TGL_REQUEST,
								  a.KETERANGAN AS KETERANGAN,
								  a.RECEIVING_DARI AS RECEIVING_DARI,
								  A.PERALIHAN AS PERALIHAN,
								  a.KD_CONSIGNEE AS KD_CONSIGNEE,
								  A.NO_DO, A.NO_BL, cr.AKTIF,
								  d.NM_PBM AS CONSIGNEE,
								  d.NO_NPWP_PBM AS NO_NPWP_PBM,
								  d.ALMT_PBM AS ALMT_PBM,
								  CASE WHEN A.PERALIHAN = 'STRIPPING' OR A.PERALIHAN = 'STUFFING'
								  THEN 'AUTO_RECEIVE'
								  ELSE nr.NO_NOTA 
								  END NO_NOTA,
								  CASE WHEN A.PERALIHAN = 'STRIPPING' OR A.PERALIHAN = 'STUFFING'
								  THEN 'AUTO_RECEIVE'
								  ELSE nr.NO_FAKTUR 
								  END NO_FAKTUR,
								  CASE WHEN A.PERALIHAN = 'STRIPPING' OR A.PERALIHAN = 'STUFFING'
								  THEN 'AUTO_RECEIVE'
								  ELSE CASE WHEN nr.STATUS = 'BATAL' THEN 'BATAL' ELSE nr.LUNAS END
								  END LUNAS,
								  cr.STATUS_REQ
						   FROM   REQUEST_RECEIVING a
						   INNER JOIN CONTAINER_RECEIVING cr ON A.NO_REQUEST = CR.NO_REQUEST                       
						   LEFT JOIN NOTA_RECEIVING nr ON nr.NO_REQUEST = a.NO_REQUEST
						   LEFT JOIN  V_MST_PBM d ON a.KD_CONSIGNEE = d.KD_PBM
						   JOIN master_container on MASTER_CONTAINER.NO_CONTAINER = cr.no_container
						   join history_container on history_container.no_container = cr.no_container and history_container.NO_REQUEST = cr.NO_REQUEST
						   WHERE history_container.no_booking = '$no_booking' 
						   --CR.NO_REQUEST = '$no_req_rec' 
						   AND CR.NO_CONTAINER = '$no_cont'
						   AND history_container.COUNTER = '$counter'";
		}
		else {
			$q_rec = "SELECT DISTINCT a.NO_REQUEST AS NO_REQUEST,
									a.TGL_REQUEST,
								  a.KETERANGAN AS KETERANGAN,
								  a.RECEIVING_DARI AS RECEIVING_DARI,
								  A.PERALIHAN AS PERALIHAN,
								  a.KD_CONSIGNEE AS KD_CONSIGNEE,
								  A.NO_DO, A.NO_BL, cr.AKTIF,
								  d.NM_PBM AS CONSIGNEE,
								  d.NO_NPWP_PBM AS NO_NPWP_PBM,
								  d.ALMT_PBM AS ALMT_PBM,
								  CASE WHEN A.PERALIHAN = 'STRIPPING' OR A.PERALIHAN = 'STUFFING'
								  THEN 'AUTO_RECEIVE'
								  ELSE nr.NO_NOTA 
								  END NO_NOTA,
								  CASE WHEN A.PERALIHAN = 'STRIPPING' OR A.PERALIHAN = 'STUFFING'
								  THEN 'AUTO_RECEIVE'
								  ELSE nr.NO_FAKTUR 
								  END NO_FAKTUR,
								  CASE WHEN A.PERALIHAN = 'STRIPPING' OR A.PERALIHAN = 'STUFFING'
								  THEN 'AUTO_RECEIVE'
								  ELSE CASE WHEN nr.STATUS = 'BATAL' THEN 'BATAL' ELSE nr.LUNAS END
								  END LUNAS,
								  cr.STATUS_REQ
						   FROM   REQUEST_RECEIVING a
						   INNER JOIN CONTAINER_RECEIVING cr ON A.NO_REQUEST = CR.NO_REQUEST                       
						   LEFT JOIN NOTA_RECEIVING nr ON nr.NO_REQUEST = a.NO_REQUEST
						   LEFT JOIN  V_MST_PBM d ON a.KD_CONSIGNEE = d.KD_PBM
						   JOIN master_container on MASTER_CONTAINER.NO_CONTAINER = cr.no_container
						   --join history_container on history_container.no_container = cr.no_container and history_container.NO_REQUEST = cr.NO_REQUEST
						   WHERE --history_container.no_booking = '$no_booking' 
						   CR.NO_REQUEST = '$no_req_rec' AND CR.NO_CONTAINER = '$no_cont'";
		
		}
		//echo $q_rec;
		$r_rec = $db->query($q_rec);
		$r_r = $r_rec->getAll();
		$tl->assign('r_rec',$r_r);
	}
	
	else if($act == "stuffing"){
		$q_get_req_stu = "SELECT HC.NO_REQUEST FROM HISTORY_CONTAINER HC
				WHERE HC.NO_CONTAINER = '$no_cont' AND HC.NO_BOOKING = '$no_booking' AND HC.KEGIATAN = 'REQUEST STUFFING' AND HC.COUNTER = '$counter'";
		$res_detail3 = $db->query($q_get_req_stu);
		$r_req3 = $res_detail3->fetchRow();
		$no_req_stu = $r_req3["NO_REQUEST"];
		
		$q_stu = "SELECT DISTINCT CS.NO_CONTAINER, CS.ASAL_CONT, CS.TGL_REALISASI, CS.ID_USER_REALISASI, MUS.NAMA_LENGKAP NAMA_LKP, rs.*,
                        EMKL.NM_PBM, cs.TGL_APPROVE, 
						case when rs.STUFFING_DARI = 'AUTO' and rs.status_req is null
							THEN cs.START_STACK
							WHEN rs.status_req = 'PERP' then cs.START_PERP_PNKN	
							WHEN cs.REMARK_SP2 = 'Y' then cs.START_PERP_PNKN							
						else cs.START_STACK
						END AS START_STACK,
						case when rs.STUFFING_DARI = 'AUTO' and rs.status_req is null
							THEN cs.START_PERP_PNKN
							WHEN rs.status_req = 'PERP' then cs.END_STACK_PNKN
							WHEN cs.REMARK_SP2 = 'Y' then cs.END_STACK_PNKN  
						else cs.START_PERP_PNKN
						END AS START_PERP_PNKN,
						case when rs.STUFFING_DARI = 'AUTO' and rs.status_req is null
							THEN 'ALIH_KAPAL'
							WHEN rs.status_req = 'PERP' then ns.NO_NOTA						
						else ns.NO_NOTA
						END AS NO_NOTA,
						case when rs.STUFFING_DARI = 'AUTO' and rs.status_req is null
							THEN 'ALIH_KAPAL'
							WHEN rs.status_req = 'PERP' then ns.NO_FAKTUR						
						else ns.NO_FAKTUR
						END AS NO_FAKTUR,						
                         case when rs.STUFFING_DARI = 'AUTO' and rs.status_req is null
                            THEN (SELECT LUNAS FROM NOTA_BATAL_MUAT, REQUEST_BATAL_MUAT 
                         WHERE NOTA_BATAL_MUAT.NO_REQUEST = REQUEST_BATAL_MUAT.NO_REQUEST AND REQUEST_BATAL_MUAT.NO_REQ_BARU = RS.NO_REQUEST)
                            WHEN rs.status_req = 'PERP' then ns.LUNAS                      
                        else ns.LUNAS
                        END AS LUNAS,
                         case when rs.STUFFING_DARI = 'AUTO' and rs.status_req is null
                            THEN (SELECT STATUS FROM NOTA_BATAL_MUAT, REQUEST_BATAL_MUAT 
                         WHERE NOTA_BATAL_MUAT.NO_REQUEST = REQUEST_BATAL_MUAT.NO_REQUEST AND REQUEST_BATAL_MUAT.NO_REQ_BARU = RS.NO_REQUEST)
                            WHEN rs.status_req = 'PERP' then ns.STATUS                 
                        else ns.STATUS
                        END AS STATUS
                       FROM REQUEST_STUFFING rs
                       INNER JOIN CONTAINER_STUFFING cs ON RS.NO_REQUEST = CS.NO_REQUEST 
                       LEFT JOIN V_MST_PBM emkl ON rs.KD_CONSIGNEE = EMKL.KD_PBM 
                       AND rs.KD_PENUMPUKAN_OLEH = EMKL.KD_PBM
					   LEFT JOIN MASTER_USER MUS ON cs.ID_USER_REALISASI = MUS.ID
					   LEFT JOIN NOTA_STUFFING ns ON rs.NO_REQUEST = ns.NO_REQUEST
					   JOIN master_container on MASTER_CONTAINER.NO_CONTAINER = cs.no_container
					   left join history_container on history_container.no_container = cs.no_container and history_container.no_request = cs.no_request
                       WHERE history_container.no_booking = '$no_booking' and
					   CS.NO_CONTAINER = '$no_cont' and history_container.COUNTER = '$counter'
					   order by rs.tgl_request desc, rs.no_request";
		$r_stu = $db->query($q_stu);
		$r_st = $r_stu->getAll();
		$tl->assign('r_stu',$r_st);
	}
	
	else if($act == "delivery"){
		if($no_booking == "VESSEL_NOTHING"){
			$q_get_req_del = "SELECT HC.NO_REQUEST FROM HISTORY_CONTAINER HC
				WHERE HC.NO_CONTAINER = '$no_cont' AND HC.NO_BOOKING = '$no_booking' AND HC.COUNTER = '$counter' AND HC.KEGIATAN = 'REQUEST DELIVERY'";
			$add_q = "AND HISTORY_CONTAINER.COUNTER = '$counter'";
		} else {
			$q_get_req_del = "SELECT HC.NO_REQUEST FROM HISTORY_CONTAINER HC
				WHERE HC.NO_CONTAINER = '$no_cont' AND HC.NO_BOOKING = '$no_booking' AND HC.KEGIATAN = 'REQUEST DELIVERY' AND HC.COUNTER = '$counter'";
			$add_q = "";
		}
		$res_detail4 = $db->query($q_get_req_del);
		$r_req4 = $res_detail4->fetchRow();
		$no_req_del = $r_req4["NO_REQUEST"];
		
		$q_del = " SELECT distinct 
                    case when request_delivery.status = 'PERP'
                        THEN container_delivery.start_perp+1
                        else container_delivery.start_stack
                     end start_stack, container_delivery.tgl_delivery, container_delivery.komoditi, container_delivery.aktif, container_delivery.hz, request_delivery.peralihan, request_delivery.delivery_ke,
                    request_delivery.DELIVERY_KE, REQUEST_DELIVERY.NO_REQUEST, request_delivery.tgl_request,
                    emkl.NM_PBM AS NAMA_EMKL, emkl.ALMT_PBM, emkl.NO_NPWP_PBM, request_delivery.VESSEL, request_delivery.VOYAGE,
                    case when request_delivery.status = 'AUTO_REPO' then 'AUTO_REPO_EX_BATALMUAT'
                    ELSE nota_delivery.no_nota END no_nota,
					case when request_delivery.status = 'AUTO_REPO' then 'AUTO_REPO_EX_BATALMUAT'
                    ELSE nota_delivery.no_faktur END no_faktur,
                    CASE when request_delivery.status = 'AUTO_REPO' THEN nota_batal_muat.lunas
                    ELSE
                    CASE when nota_delivery.status = 'BATAL' THEN 'BATAL'
                    ELSE nota_delivery.lunas END END lunas, request_delivery.jn_repo, request_delivery.PERP_KE
                    FROM REQUEST_DELIVERY INNER JOIN CONTAINER_DELIVERY ON REQUEST_DELIVERY.NO_REQUEST = CONTAINER_DELIVERY.NO_REQUEST
                    left join nota_delivery on request_delivery.no_request = nota_delivery.no_request
                    LEFT JOIN v_mst_pbm emkl ON REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM and emkl.kd_cabang = '05'
                    JOIN master_container on MASTER_CONTAINER.NO_CONTAINER = container_delivery.no_container
                    left join request_batal_muat bmu on  request_delivery.no_request = bmu.no_req_baru
                    left join nota_batal_muat on bmu.no_request = nota_batal_muat.no_request
                    left join history_container on history_container.no_container = CONTAINER_DELIVERY.no_container and history_container.no_request = CONTAINER_DELIVERY.no_request
                    WHERE CONTAINER_DELIVERY.NO_CONTAINER = '$no_cont' AND 
                    history_container.COUNTER = '$counter' AND history_container.no_booking = '$no_booking' ".$add_q." 
                    --AND CONTAINER_DELIVERY.NO_REQUEST = '$no_req_del'
                    order by request_delivery.tgl_request desc";
		//echo $q_del;
		//die;
		$r_del = $db->query($q_del);
		$r_de = $r_del->getAll();
		$tl->assign('r_del',$r_de);
	}
	else if($act == "placement"){
		if($no_booking == "VESSEL_NOTHING"){
			$q_get_req_pl = "SELECT HC.NO_REQUEST FROM HISTORY_CONTAINER HC
				WHERE HC.NO_CONTAINER = '$no_cont' AND HC.NO_BOOKING = '$no_booking' AND HC.COUNTER = '$counter' AND HC.KEGIATAN = 'REQUEST RECEIVING'";
		}else {
			$q_get_req_pl = "SELECT HC.NO_REQUEST FROM HISTORY_CONTAINER HC
				WHERE HC.NO_CONTAINER = '$no_cont' AND HC.NO_BOOKING = '$no_booking' AND HC.KEGIATAN = 'REQUEST RECEIVING' AND HC.COUNTER = '$counter'";
		}
		$res_detail5 = $db->query($q_get_req_pl);
		$r_req5 = $res_detail5->fetchRow();
		$no_req_pl = $r_req5["NO_REQUEST"];
		$q_placement = "SELECT CEK.*,  CASE WHEN CEK.INSERT_VIA = 'H' THEN 'HANDHELD' ELSE 'DESKTOP' END VIA FROM (SELECT HISTORY_PLACEMENT.*, MASTER_USER.NAME NAMA_LENGKAP, BLOCKING_AREA.NAME, YARD_AREA.NAMA_YARD FROM 
                        HISTORY_PLACEMENT INNER JOIN BLOCKING_AREA
                        ON HISTORY_PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID
                        LEFT JOIN YARD_AREA ON BLOCKING_AREA.ID_YARD_AREA = YARD_AREA.ID
                        LEFT JOIN BILLING.TB_USER MASTER_USER ON (HISTORY_PLACEMENT.NIPP_USER = TO_CHAR(MASTER_USER.ID) OR HISTORY_PLACEMENT.NIPP_USER = MASTER_USER.NIPP) WHERE NO_CONTAINER = '$no_cont'
                        AND HISTORY_PLACEMENT.INSERT_VIA IS NULL
                        UNION ALL
                        SELECT HISTORY_PLACEMENT.*, MASTER_USER.NAME NAMA_LENGKAP, BLOCKING_AREA.NAME, YARD_AREA.NAMA_YARD FROM 
                        HISTORY_PLACEMENT INNER JOIN BLOCKING_AREA
                        ON HISTORY_PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID
                        LEFT JOIN YARD_AREA ON BLOCKING_AREA.ID_YARD_AREA = YARD_AREA.ID
                        LEFT JOIN BILLING.TB_USER MASTER_USER ON HISTORY_PLACEMENT.NIPP_USER = MASTER_USER.ID WHERE NO_CONTAINER = '$no_cont'
                        AND HISTORY_PLACEMENT.INSERT_VIA = 'H'
						UNION ALL
                        SELECT HISTORY_PLACEMENT.*, MASTER_USER.NAME NAMA_LENGKAP, BLOCKING_AREA.NAME, YARD_AREA.NAMA_YARD FROM 
                        HISTORY_PLACEMENT INNER JOIN BLOCKING_AREA
                        ON HISTORY_PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID
                        LEFT JOIN YARD_AREA ON BLOCKING_AREA.ID_YARD_AREA = YARD_AREA.ID
                        LEFT JOIN BILLING.TB_USER MASTER_USER ON HISTORY_PLACEMENT.NIPP_USER = MASTER_USER.ID WHERE NO_CONTAINER = '$no_cont'
                        AND HISTORY_PLACEMENT.INSERT_VIA = 'DB') CEK                                                 
                        ORDER BY CEK.TGL_UPDATE DESC";
		$r_place = $db->query($q_placement);
		$r_pl = $r_place->getAll();
		$tl->assign('r_pl',$r_pl);		
	}
	
	$tl->renderToScreen();
?>