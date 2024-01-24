<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('view_perp.htm');
	$db 	= getDB("storage");
	//$db2 	= getDB("ora");
	
	//echo $_GET["no_req"];die;
	
	if(isset($_GET["no_req"]))
	{
		$no_req	= $_GET["no_req"];
		$query_r = "SELECT NO_REQUEST_RECEIVING,NO_REQUEST_DELIVERY, NO_BOOKING
					FROM REQUEST_STUFFING
					WHERE NO_REQUEST = '$no_req'";		
		$results 	= $db->query($query_r);
		$row_result	= $results->fetchRow();
		
		$no_req_rec		= $row_result["NO_REQUEST_RECEIVING"];
		$no_req_deli	= $row_result["NO_REQUEST_DELIVERY"];
		$no_booking_cur	= $row_result["NO_BOOKING"];
		
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
                        BOOK.NM_AGEN,
                        BOOK.NM_KAPAL,
                        BOOK.VOYAGE_IN,
                        BOOK.NO_BOOKING,
                        BOOK.PELABUHAN_ASAL NM_PELABUHAN_ASAL,
                        BOOK.PELABUHAN_TUJUAN NM_PELABUHAN_TUJUAN
               FROM REQUEST_STUFFING
               JOIN v_mst_pbm EMKL
                    ON REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM and EMKL.kd_cabang = '05'
                   JOIN V_PKK_CONT BOOK
                    ON  REQUEST_STUFFING.NO_BOOKING = BOOK.NO_BOOKING
               WHERE REQUEST_STUFFING.NO_REQUEST ='$no_req'";
								
	$query_list		= " SELECT DISTINCT e.NM_PBM NAMA_EMKL,
                        request_stuffing.KD_CONSIGNEE ID_EMKL,
                        request_stuffing.NM_KAPAL,
                        request_stuffing.VOYAGE VOYAGE_IN,
                        request_stuffing.NO_BOOKING,
                        request_stuffing.NO_PEB,
                        request_stuffing.NO_NPE,
                        request_stuffing.NO_DOKUMEN,
                        request_stuffing.NO_JPB,
                       request_stuffing.BPRP,
                        container_stuffing.KETERANGAN,
                        container_stuffing.COMMODITY COMMO 
                           FROM CONTAINER_STUFFING
                                    INNER JOIN REQUEST_STUFFING            
                                   ON CONTAINER_STUFFING.NO_REQUEST = REQUEST_STUFFING.NO_REQUEST
                                    INNER JOIN v_mst_pbm e     
                                   ON REQUEST_STUFFING.KD_CONSIGNEE =e.KD_PBM AND e.KD_CABANG = '05'
                                   WHERE CONTAINER_STUFFING.NO_REQUEST = '$no_req'";
						   
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	
	
	
	/* $query_ict_deliver = "SELECT A.NO_DO, A.NO_BL, A.NO_SPPB, A.TGL_SPPB, to_char(A.TGL_SPPB,'YYYY-MM-DD') TGL_SPPB FROM PETIKEMAS_CABANG.TTM_DEL_REQ A WHERE NO_REQ_DEL = '$no_req2'";
	$result_ict_deliver = $db2->query($query_ict_deliver);
	$row_ict_deliver = $result_ict_deliver->fetchRow(); */
	
	/* $sql_no  		= "select AUTO_NO+1 AS AUTO_NO  from PETIKEMAS_CABANG.MST_AUTO_NO WHERE CODE='7'";
	$rs 	 		= $db2->query( $sql_no );
	$row	 		= $rs->FetchRow();
	$sp2		 	= $row['AUTO_NO']; */
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll();
	$jum = count($row_list);
	
	
	$get_tgl 		= "SELECT a.NO_CONTAINER,
                           TO_DATE (a.START_PERP_PNKN, 'dd/mm/rrrr')START_PERP_PNKN,
                           rs.NO_BOOKING
                          -- to_date(b.DOC_CLOSING_DATE_DRY,'dd/mm/rrrr') AS CLOSE_TIME
                      FROM CONTAINER_STUFFING a, request_stuffing rs
                       --v_booking_stack_tpk b
                     WHERE     a.NO_REQUEST = '$no_req'
                           AND AKTIF = 'Y'
                           AND a.no_request = rs.no_request 
                     --rs.no_booking = b.no_booking 
                     ORDER BY a.NO_CONTAINER";								
					
	$result_tgl		= $db->query($get_tgl);
	$row_tgl		= $result_tgl->getAll();
	
	
	$count  		= "SELECT COUNT(a.NO_CONTAINER) JUMLAH FROM container_stuffing a WHERE a.NO_REQUEST = '$no_req' AND a.AKTIF = 'Y'";
	$result_count	= $db->query($count);
	$row_count		= $result_count->fetchRow();
	
	
	//debug($row_request);
	
	$tl->assign("row_count", $row_count);	
	$tl->assign("no_booking_cur", $no_booking_cur);	
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
