<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('view_perp.htm');
	$db 	= getDB("storage");
	if(isset($_GET["no_req"]))
	{
		$no_req	= $_GET["no_req"];
		$query_r = "SELECT NO_REQUEST_RECEIVING,NO_REQUEST_DELIVERY
					FROM REQUEST_STUFFING
					WHERE NO_REQUEST = '$no_req'";		
		$results 	= $db->query($query_r);
		$row_result	= $results->fetchRow();
		
		$no_req_rec		= $row_result["NO_REQUEST_RECEIVING"];
		$no_req_deli	= $row_result["NO_REQUEST_DELIVERY"];
		
		$row_result2 = substr($no_req_rec,3);	
		$no_req2	= "UREC".$row_result2;		
		
		$row_result3 = substr($no_req_deli,3);
		$no_req3	= "US".$row_result3;
	}
	else
	{
		header('Location: '.HOME.APPID);		
	}
	
	
	$query_request	= "SELECT REQUEST_STUFFING.NO_REQUEST, REQUEST_STUFFING.NO_REQUEST_RECEIVING, REQUEST_STUFFING.NO_REQUEST_DELIVERY,
                        EMKL.NM_PBM AS NAMA_EMKL,
                        EMKL.KD_PBM AS ID_EMKL,
                        REQUEST_STUFFING.NO_DOKUMEN,
                        REQUEST_STUFFING.NO_JPB,
                        REQUEST_STUFFING.BPRP,
                        REQUEST_STUFFING.NO_NPE,
                        REQUEST_STUFFING.NO_PEB,
                        REQUEST_STUFFING.KETERANGAN,
                        REQUEST_STUFFING.KD_PENUMPUKAN_OLEH,
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
               FROM REQUEST_STUFFING
               JOIN V_MST_PBM EMKL
                    ON REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM
               JOIN petikemas_cabang.V_BOOKING_STACK_TPK BOOK
                    ON  REQUEST_STUFFING.NM_KAPAL = BOOK.NM_KAPAL 
                    AND REQUEST_STUFFING.VOYAGE = BOOK.VOYAGE_IN,
               REQUEST_DELIVERY 
               JOIN PETIKEMAS_CABANG.TTH_CONT_EXBSPL TPK
                   ON REQUEST_DELIVERY.NO_REQ_ICT = TPK.KD_PMB
            -- JOIN V_MST_PBM pnmt ON REQUEST_STUFFING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM 
               WHERE REQUEST_STUFFING.NO_REQUEST = '$no_req'
           --   AND REQUEST_STUFFING.NO_REQUEST_DELIVERY = REQUEST_DELIVERY.NO_REQUEST";
								
	$query_list		= " SELECT DISTINCT e.NM_PBM NAMA_EMKL,
                        request_stuffing.KD_CONSIGNEE ID_EMKL,
                        request_stuffing.NM_KAPAL,
                        request_stuffing.VOYAGE VOYAGE_IN,
                      --  A.KD_AGEN,
                        request_delivery.KD_PELABUHAN_ASAL,
                        c.NM_PELABUHAN NM_PELABUHAN_ASAL,
                        request_delivery.KD_PELABUHAN_TUJUAN,
                        d.NM_PELABUHAN NM_PELABUHAN_TUJUAN,
                        request_stuffing.NO_BOOKING,
                        request_stuffing.NO_PEB,
                        request_stuffing.NO_NPE,
                        request_stuffing.NO_DOKUMEN,
                        request_stuffing.NO_JPB,
                       request_stuffing.BPRP,
                        container_stuffing.KETERANGAN,
                        container_stuffing.COMMODITY COMMO, A.KD_SIZE, A.KD_TYPE
                           FROM CONTAINER_STUFFING INNER JOIN PETIKEMAS_CABANG.TTD_BP_CONT A            
                           ON CONTAINER_STUFFING.NO_CONTAINER = A.CONT_NO_BP
                            INNER JOIN REQUEST_STUFFING            
                           ON CONTAINER_STUFFING.NO_REQUEST = REQUEST_STUFFING.NO_REQUEST
                             INNER JOIN REQUEST_DELIVERY            
                           ON REQUEST_STUFFING.NO_REQUEST_DELIVERY = REQUEST_DELIVERY.NO_REQUEST
                             INNER JOIN V_MST_PELABUHAN   c         
                           ON REQUEST_DELIVERY.KD_PELABUHAN_ASAL = c.KD_PELABUHAN
                              INNER JOIN V_MST_PELABUHAN   d      
                           ON REQUEST_DELIVERY.KD_PELABUHAN_TUJUAN = d.KD_PELABUHAN
                            INNER JOIN USTER.V_MST_PBM   e     
                           ON REQUEST_STUFFING.KD_CONSIGNEE =e.KD_PBM
						   WHERE CONTAINER_STUFFING.NO_REQUEST = '$no_req'";
						   
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	
	
	
	$query_ict_deliver = "SELECT A.NO_DO, A.NO_BL, A.NO_SPPB, A.TGL_SPPB, to_char(A.TGL_SPPB,'YYYY-MM-DD') TGL_SPPB FROM PETIKEMAS_CABANG.TTM_DEL_REQ A WHERE NO_REQ_DEL = '$no_req2'";
	$result_ict_deliver = $db->query($query_ict_deliver);
	$row_ict_deliver = $result_ict_deliver->fetchRow();
	
	$sql_no  		= "select AUTO_NO+1 AS AUTO_NO  from PETIKEMAS_CABANG.MST_AUTO_NO WHERE CODE='7'";
	$rs 	 		= $db->query( $sql_no );
	$row	 		= $rs->FetchRow();
	$sp2		 	= $row['AUTO_NO'];
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll();
	$jum = count($row_list);
	
	
	$get_tgl 		= "  SELECT a.NO_CONTAINER,
							 TO_DATE (a.TGL_APPROVE, 'dd/mm/rrrr') + 1 TGL_APPROVE
						FROM CONTAINER_STUFFING a
					   WHERE a.NO_REQUEST = '$no_req' AND AKTIF = 'Y'
					ORDER BY a.NO_CONTAINER";
	$result_tgl		= $db->query($get_tgl);
	$row_tgl		= $result_tgl->getAll();
	
	
	$count  		= "SELECT COUNT(a.NO_CONTAINER) JUMLAH FROM container_stuffing a WHERE a.NO_REQUEST = '$no_req' AND a.AKTIF = 'Y'";
	$result_count	= $db->query($count);
	$row_count		= $result_count->fetchRow();
	
	
	//debug($row_request);
	
	$tl->assign("row_count", $row_count);	
	$tl->assign("row_tgl", $row_tgl);	
	$tl->assign("jum", $jum);	
	$tl->assign("row_list", $row_list);	
	$tl->assign("row_request", $row_request);	
	$tl->assign("row_ict", $row_ict_deliver);
	$tl->assign("no_req2",$no_req2);
	$tl->assign("no_req3",$no_req3);
	$tl->assign("sp2",$sp2);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
