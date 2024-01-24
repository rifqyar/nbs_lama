<?php
	$no_cont = $_GET["NO_CONT"];
	$no_book = $_GET["NO_BOOK"];
	$db = getDB("storage");
	
	$q_detail = "SELECT MC.NO_CONTAINER, HC.KEGIATAN, HC.NO_REQUEST
				FROM MASTER_CONTAINER MC INNER JOIN HISTORY_CONTAINER HC
				ON MC.NO_CONTAINER = HC.NO_CONTAINER 
				AND MC.NO_BOOKING = HC.NO_BOOKING
				AND MC.COUNTER = HC.COUNTER 
				WHERE MC.NO_CONTAINER = '$no_cont'";
	$res_detail = $db->query($q_detail);
	$r_det = $res_detail->getAll();
	foreach($r_det as $rows){
		
		if($rows["NO_REQUEST"] != NULL && $rows["KEGIATAN"] == "REQUEST RECEIVING"){
			$no_req_rec = $rows["NO_REQUEST"];
			$q_req = "SELECT a.NO_REQUEST AS NO_REQUEST,
                              a.KETERANGAN AS KETERANGAN,
                              a.RECEIVING_DARI AS RECEIVING_DARI,
                              A.PERALIHAN AS PERALIHAN,
                              a.KD_CONSIGNEE AS KD_CONSIGNEE,
                              A.NO_DO, A.NO_BL, A.NO_SPPB, A.TGL_SPPB,                              
                              d.NM_PBM AS CONSIGNEE,
                              d.NO_NPWP_PBM AS NO_NPWP_PBM,
                              d.ALMT_PBM AS ALMT_PBM
							FROM  REQUEST_RECEIVING a INNER JOIN
								  V_MST_PBM d ON a.KD_CONSIGNEE = d.KD_PBM
							WHERE a.NO_REQUEST = '$no_req_rec'";
		}
		else if($rows["NO_REQUEST"] != NULL && $rows["KEGIATAN"] == "REQUEST DELIVERY"){
			$no_req_del = $rows["NO_REQUEST"];
			$q_req = "SELECT REQUEST_STRIPPING.NO_REQUEST, 
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
                       WHERE REQUEST_STRIPPING.NO_REQUEST = 'PTR1012000012'
                       AND CONTAINER_STRIPPING.NO_CONTAINER = 'TEGU2978550'";
		}
	}
	echo json_encode($r_det); 
	//exit();

?>