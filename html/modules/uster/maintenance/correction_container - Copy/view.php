<?php
	//header('Location: '.HOME .'static/error.htm');		
	if(isset($_GET["no_req"]))
	{
		$no_req	= $_GET["no_req"];
		$kegiatan = $_GET["keg"];
	}
	else
	{
		header('Location: '.HOME.APPID);		
	}
	$db = getDB("storage");
	if($kegiatan == 'DELIVERY'){
		$q_cek_delivery = "SELECT DELIVERY_KE FROM REQUEST_DELIVERY WHERE NO_REQUEST = '$no_req'";
		$res_cek_delivery = $db->query($q_cek_delivery);
		$rw_cek_del = $res_cek_delivery->fetchRow();
		
		if($rw_cek_del["DELIVERY_KE"] ==  'LUAR'){
			$tl 	=  xliteTemplate('view_delivery_luar.htm');
			$query_request	= "SELECT REQUEST_DELIVERY.KETERANGAN, request_delivery.DELIVERY_KE, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR(REQUEST_DELIVERY.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.KD_PBM AS KD_PBM, emkl.NM_PBM AS NAMA_EMKL, emkl.ALMT_PBM, emkl.NO_NPWP_PBM, request_delivery.VESSEL, request_delivery.VOYAGE
				FROM REQUEST_DELIVERY INNER JOIN v_mst_pbm emkl ON REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
				WHERE REQUEST_DELIVERY.NO_REQUEST = '$no_req'";
			$result_request	= $db->query($query_request);
			$row_request	= $result_request->fetchRow();
		}
		else{
			$tl 	=  xliteTemplate('view_delivery_tpk.htm');
			$query_request	= "SELECT REQUEST_DELIVERY.KETERANGAN, request_delivery.DELIVERY_KE, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR(REQUEST_DELIVERY.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, REQUEST_DELIVERY.VESSEL, REQUEST_DELIVERY.VOYAGE, REQUEST_DELIVERY.PEB, REQUEST_DELIVERY.NPE
				FROM REQUEST_DELIVERY INNER JOIN V_MST_PBM emkl ON REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
				WHERE REQUEST_DELIVERY.NO_REQUEST = '$no_req'";
			$result_request	= $db->query($query_request);
			$row_request	= $result_request->fetchRow();
		}		
	
		//debug($row_request);
	
		$tl->assign("row_request", $row_request);

	}
	else if($kegiatan == 'RECEIVING'){
		$q_cek_receiving = "SELECT RECEIVING_DARI FROM REQUEST_RECEIVING WHERE NO_REQUEST = '$no_req'";
		$res_cek_receiving= $db->query($q_cek_receiving);
		$rw_cek_rec = $res_cek_receiving->fetchRow();
		if($rw_cek_rec["RECEIVING_DARI"] == "TPK"){
			$tl 	=  xliteTemplate('view_receiving_tpk.htm');
			$query_request	= "SELECT a.NO_REQUEST AS NO_REQUEST,
							  a.KETERANGAN AS KETERANGAN,
							  a.RECEIVING_DARI AS RECEIVING_DARI,
							  a.KD_CONSIGNEE AS KD_CONSIGNEE,
							  a.NO_DO AS NO_DO,
							  a.NO_BL AS NO_BL,
							  a.NO_SPPB AS NO_SPPB,
							  a.TGL_SPPB AS TGL_SPPB,
							  a.KD_PENUMPUKAN_OLEH AS KD_PENUMPUKAN_OLEH,
							  d.NM_PBM AS CONSIGNEE,
							  e.NM_PBM AS PENUMPUKAN_OLEH
					   FROM   REQUEST_RECEIVING a INNER JOIN
							  V_MST_PBM d ON a.KD_CONSIGNEE = d.KD_PBM JOIN
							  V_MST_PBM e ON a.KD_PENUMPUKAN_OLEH = e.KD_PBM
					   WHERE 
							 a.NO_REQUEST = '$no_req'
						";
			$result_request	= $db->query($query_request);
			$row_request	= $result_request->fetchRow();			
		}
		else{
			$tl 	=  xliteTemplate('view_receiving_luar.htm');
			$query_request	= "SELECT a.NO_REQUEST AS NO_REQUEST,
							  a.KETERANGAN AS KETERANGAN,
							  a.RECEIVING_DARI AS RECEIVING_DARI,
							  a.KD_CONSIGNEE AS KD_CONSIGNEE,
							  d.NM_PBM AS CONSIGNEE,
							  d.NO_NPWP_PBM AS NO_NPWP_PBM,
							  d.ALMT_PBM AS ALMT_PBM
					   FROM   REQUEST_RECEIVING a INNER JOIN
							  V_MST_PBM d ON a.KD_CONSIGNEE = d.KD_PBM
					   WHERE a.NO_REQUEST = '$no_req'
						";
			$result_request	= $db->query($query_request);
			$row_request	= $result_request->fetchRow();
		}
		
		//debug($row_request);
		
		$tl->assign("row_request", $row_request);

	}
	else if($kegiatan == 'STRIPPING'){
		$tl 	=  xliteTemplate('view_stripping.htm');
		$query_request = "SELECT REQUEST_STRIPPING.*, PLAN_REQUEST_STRIPPING.NO_SPPB, PLAN_REQUEST_STRIPPING.TGL_SPPB,
						   emkl.NM_PBM AS NAMA_PEMILIK,
						   pnmt.NM_PBM AS NAMA_PENUMPUK
					  FROM REQUEST_STRIPPING 
						   LEFT JOIN V_MST_PBM emkl
							  ON REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM
						   LEFT JOIN V_MST_PBM pnmt
							  ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM
							   LEFT JOIN PLAN_REQUEST_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST
                      = REPLACE (PLAN_REQUEST_STRIPPING.NO_REQUEST,'P','S')
					 WHERE REQUEST_STRIPPING.NO_REQUEST = '$no_req'";
		$result_request	= $db->query($query_request);
		$row_request	= $result_request->fetchRow();
		$type_stripp  = '<select id="TYPE_S" name="TYPE_S">';
				if($row_request["TYPE_STRIPPING"] == "DOMESTIC") {
				$type_stripp  .= '<option value="DOMESTIC" selected="selected"> DALAM NEGERI </option>
									<option value="INTERNATIONAL"> LUAR NEGERI </option>';
				} else if($row_request["TYPE_STRIPPING"] == "INTERNATIONAL") {
				$type_stripp  .= '<option value="DOMESTIC"> DALAM NEGERI </option>
				<option value="INTERNATIONAL" selected="selected"> LUAR NEGERI </option>';
				} else {
					$type_stripp  .= '<option value="" selected="selected"> --pilih-- </option> <option value="DOMESTIC"> DALAM NEGERI </option>
							<option value="INTERNATIONAL"> LUAR NEGERI </option>';
				}
			$type_stripp  .= '</select>';
	//debug($row_request);
	
		$tl->assign("type_stripp", $type_stripp);
		$tl->assign("row_request", $row_request);

	}
	else if($kegiatan == 'STUFFING'){
		$tl 	=  xliteTemplate('view_stuffing.htm');
		$query_request	= "SELECT PLAN_REQUEST_STUFFING.NO_REQUEST, PLAN_REQUEST_STUFFING.NO_REQUEST_RECEIVING, PLAN_REQUEST_STUFFING.NO_REQUEST_DELIVERY,
                        EMKL.NM_PBM AS NAMA_EMKL,
                        EMKL.KD_PBM AS ID_EMKL,
                        PLAN_REQUEST_STUFFING.NO_DOKUMEN,
                        PLAN_REQUEST_STUFFING.NO_JPB,
                        PLAN_REQUEST_STUFFING.BPRP,
                        PLAN_REQUEST_STUFFING.NO_NPE,
                        PLAN_REQUEST_STUFFING.NO_PEB,
                        PLAN_REQUEST_STUFFING.KETERANGAN,
                        PLAN_REQUEST_STUFFING.KD_PENUMPUKAN_OLEH,
                        PLAN_REQUEST_STUFFING.NO_SPPB,
                        PLAN_REQUEST_STUFFING.TGL_SPPB,
                        PLAN_REQUEST_STUFFING.NO_DO,
                        PLAN_REQUEST_STUFFING.NO_BL,
                        REQUEST_DELIVERY.TGL_MUAT,
                        REQUEST_DELIVERY.TGL_STACKING,
                        REQUEST_DELIVERY.TGL_BERANGKAT,
                        REQUEST_DELIVERY.KD_PELABUHAN_ASAL,
                        REQUEST_DELIVERY.KD_PELABUHAN_TUJUAN,
                        BOOK.NM_AGEN,
                        BOOK.NM_KAPAL,
                        BOOK.VOYAGE_IN,
                        BOOK.NO_BOOKING,
                        BOOK.NM_PELABUHAN_ASAL,
                        BOOK.NM_PELABUHAN_TUJUAN,
                        TPK.KD_PMB,
                        TPK.SHIFT_RFR AS SHIFT_RFR,
                        TPK.NO_UKK
            --          pnmt.NM_PBM AS NAMA_PNMT
               FROM PLAN_REQUEST_STUFFING
               JOIN V_MST_PBM EMKL
                    ON PLAN_REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM
               JOIN V_BOOKING_STACK_TPK BOOK
                    ON  PLAN_REQUEST_STUFFING.NM_KAPAL = BOOK.NM_KAPAL 
                    AND PLAN_REQUEST_STUFFING.VOYAGE = BOOK.VOYAGE_IN,
               REQUEST_DELIVERY 
               JOIN PETIKEMAS_CABANG.TTH_CONT_EXBSPL TPK
                   ON REQUEST_DELIVERY.NO_REQ_ICT = TPK.KD_PMB
            -- JOIN V_MST_PBM pnmt ON REQUEST_STUFFING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM 
               WHERE PLAN_REQUEST_STUFFING.NO_REQUEST = REPLACE('$no_req','S','P')
              AND PLAN_REQUEST_STUFFING.NO_REQUEST_DELIVERY = REQUEST_DELIVERY.NO_REQUEST";
								
			$query_list		= "SELECT DISTINCT PLAN_CONTAINER_STUFFING.*, PLAN_CONTAINER_STUFFING.COMMODITY COMMO, A.KD_SIZE, A.KD_TYPE
								   FROM PLAN_CONTAINER_STUFFING INNER JOIN PETIKEMAS_CABANG.TTD_BP_CONT A			
								   ON PLAN_CONTAINER_STUFFING.NO_CONTAINER = A.CONT_NO_BP
								   WHERE PLAN_CONTAINER_STUFFING.NO_REQUEST = REPLACE('$no_req','S','P')";
								   
			$result_request	= $db->query($query_request);
			$row_request	= $result_request->fetchRow();
			
			$query_ict_deliver = "SELECT A.*, to_char(A.TGL_SPPB,'YYYY-MM-DD') TGL_SPPB FROM PETIKEMAS_CABANG.TTM_DEL_REQ A WHERE NO_REQ_DEL = '$no_req2'";
			$result_ict_deliver = $db->query($query_ict_deliver);
			$row_ict_deliver = $result_ict_deliver->fetchRow();
			
			$sql_no  		= "select AUTO_NO+1 AS AUTO_NO  from PETIKEMAS_CABANG.MST_AUTO_NO WHERE CODE='7'";
			$rs 	 		= $db->query( $sql_no );
			$row	 		= $rs->FetchRow();
			$sp2		 	= $row['AUTO_NO'];
			
			$result_list	= $db->query($query_list);
			$row_list		= $result_list->getAll();
			$jum = count($row_list);
			
			//debug($row_request);
			
			$tl->assign("jum", $jum);	
			$tl->assign("row_list", $row_list);	
			$tl->assign("row_request", $row_request);	
			$tl->assign("row_ict", $row_ict_deliver);
		
	}
	else if($kegiatan == 'RELOKASI'){
	   $tl 	=  xliteTemplate('view_relokasi.htm');
		$query_rel = "SELECT REQUEST_RELOKASI.*, YARD_AREA2.NAMA_YARD NAMA_YARD_ASAL, YARD_AREA1.NAMA_YARD NAMA_YARD_TUJUAN, V_MST_PBM.NM_PBM FROM REQUEST_RELOKASI 
                      INNER JOIN YARD_AREA YARD_AREA1 ON REQUEST_RELOKASI.YARD_TUJUAN = YARD_AREA1.ID
                      INNER JOIN YARD_AREA YARD_AREA2 ON REQUEST_RELOKASI.YARD_ASAL = YARD_AREA2.ID
					  LEFT JOIN V_MST_PBM ON REQUEST_RELOKASI.KD_EMKL = V_MST_PBM.KD_PBM
                      WHERE NO_REQUEST = '$no_req'";
		$res_rel = $db->query($query_rel);
		$row_request = $res_rel->fetchRow();
		$tl->assign('row_rel',$row_request);
	}
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
