<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('edit.htm');

	

	if(isset($_GET["no_req"]))
	{
		$no_req	 = $_GET["no_req"];
		$no_req2 = $_GET["no_req2"];
		$kd_cbg  = 5;
		
		//echo $no_req2; exit;
	}
	else
	{
		header('Location: '.HOME.APPID);		
	}
	$db = getDB("storage");

	//$no_req	= $_GET["no_req"];
	

	//select join ke view, tapi viewnya error
	
	//cek_nota
	
	$q_ceknota = "SELECT NO_REQUEST, STATUS, LUNAS FROM NOTA_DELIVERY WHERE NO_REQUEST = '$no_req' AND STATUS <> 'BATAL'";
	
	$r_ceknota = $db->query($q_ceknota);
	
	$rwc	   = $r_ceknota->fetchRow();
	
	if($rwc["NO_REQUEST"] != NULL && $rwc["LUNAS"] == 'YES'){
		
		header('Location: '.HOME.APPID);		
	}
	else {
	
	$query_request	= "SELECT 
							request_delivery.NO_REQ_STUFFING,
							request_delivery.PEB, 
							request_delivery.REQUEST_BY, 
							request_delivery.NPE, 
							request_delivery.TGL_MUAT, 
							request_delivery.TGL_STACKING, 
							request_delivery.KETERANGAN, 
							request_delivery.NO_REQUEST, 
							request_delivery.NO_RO, 
							request_delivery.JN_REPO,
							TO_CHAR(request_delivery.TGL_REQUEST_DELIVERY,'yyyy/mm/dd') TGL_REQUEST_DELIVERY, 
							emkl.NM_PBM AS NAMA_PBM, 
							emkl.KD_PBM KD_PBM, 
							pmb.NM_PBM as NAMA_PBM2, 
							pmb.KD_PBM as KD_PBM2, 
							ves.NM_AGEN, 
								ves.KD_AGEN, 
								ves.KD_KAPAL, 
								ves.NM_KAPAL,
								ves.VOYAGE_IN,
								ves.VOYAGE_OUT,
								ves.VOYAGE,
								ves.NO_UKK, 
								ves.NO_BOOKING,  
								TO_char(ves.tgl_jam_berangkat,'dd/mm/rrrr') TGL_BERANGKAT,
								TO_DATE(ves.tgl_jam_tiba,'dd/mm/rrrr') TGL_TIBA,
								ves.PELABUHAN_ASAL KD_PELABUHAN_ASAL,
								ves.PELABUHAN_TUJUAN KD_PELABUHAN_TUJUAN
								--pel_asal.KD_PELABUHAN as KD_PELABUHAN_ASAL, 
								--pel_asal.NM_PELABUHAN as NM_PELABUHAN_ASAL,
								--pel_tujuan.KD_PELABUHAN as KD_PELABUHAN_TUJUAN, 
								--pel_tujuan.NM_PELABUHAN as NM_PELABUHAN_TUJUAN
                            FROM request_delivery INNER JOIN v_mst_pbm emkl ON request_delivery.KD_EMKL = emkl.KD_PBM 
                                INNER JOIN v_mst_pbm pmb ON request_delivery.KD_EMKL = pmb.KD_PBM 
                                INNER JOIN v_pkk_cont ves ON request_delivery.NO_BOOKING = ves.NO_BOOKING
                                --LEFT JOIN v_mst_pelabuhan pel_asal ON request_delivery.KD_PELABUHAN_ASAL = pel_asal.KD_PELABUHAN 
                                --LEFT JOIN v_mst_pelabuhan pel_tujuan ON request_delivery.KD_PELABUHAN_TUJUAN = pel_tujuan.KD_PELABUHAN
                            WHERE REQUEST_DELIVERY.NO_REQUEST = '$no_req'";
	$result_request	= $db->query($query_request);
	$row_request	= $result_request->fetchRow();
	
	//debug($row_request);die;
	$tl->assign("kd_cbg",$kd_cbg);
	$tl->assign("no_req",$no_req);
	$tl->assign("no_req2",$no_req2);
	$tl->assign("row", $row_request);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	}
?>
