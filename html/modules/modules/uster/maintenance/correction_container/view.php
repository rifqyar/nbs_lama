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
		$q_cek_delivery = "SELECT DELIVERY_KE FROM REQUEST_DELIVERY WHERE NO_REQUEST = '$no_req'";
		$res_cek_delivery = $db->query($q_cek_delivery);
		$rw_cek_del = $res_cek_delivery->fetchRow();
		
		if($rw_cek_del["DELIVERY_KE"] ==  'LUAR'){
			$tl 	=  xliteTemplate('view_delivery_luar.htm');
			$query_request	= "SELECT REQUEST_DELIVERY.KETERANGAN,
							       request_delivery.DELIVERY_KE,
							       REQUEST_DELIVERY.NO_REQUEST,
							       TO_CHAR (REQUEST_DELIVERY.TGL_REQUEST_DELIVERY, 'dd/mm/yyyy')
							          TGL_REQUEST_DELIVERY,
							       emkl.KD_PBM AS KD_PBM,
							       emkl.NM_PBM AS NAMA_EMKL,
							       emkl.ALMT_PBM,
							       emkl.NO_NPWP_PBM,
							       request_delivery.VESSEL,
							       request_delivery.VOYAGE
							  FROM    REQUEST_DELIVERY
							       INNER JOIN
							          KAPAL_CABANG.MST_PBM emkl
							       ON REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM AND emkl.KD_CABANG = '05'
							 WHERE REQUEST_DELIVERY.NO_REQUEST = '$no_req' AND DELIVERY_KE = 'LUAR'";
			$result_request	= $db->query($query_request);
			$row_request	= $result_request->fetchRow();
		}
		else{
			$tl 	=  xliteTemplate('view_delivery_tpk.htm');
			$query_request	= "SELECT REQUEST_DELIVERY.KETERANGAN,
								       request_delivery.DELIVERY_KE,
								       REQUEST_DELIVERY.NO_REQUEST,
								       TO_CHAR (REQUEST_DELIVERY.TGL_REQUEST_DELIVERY, 'dd/mm/yyyy')
								          TGL_REQUEST_DELIVERY,
								       emkl.NM_PBM AS NAMA_EMKL,
								       REQUEST_DELIVERY.VESSEL,
								       REQUEST_DELIVERY.VOYAGE,
								       REQUEST_DELIVERY.PEB,
								       REQUEST_DELIVERY.NPE,
								       REQUEST_DELIVERY.JN_REPO,
								       REQUEST_DELIVERY.NO_RO,
								       ASAL.NM_PELABUHAN ASAL,
								       TUJUAN.NM_PELABUHAN TUJUAN
								  FROM    REQUEST_DELIVERY
								       INNER JOIN
								          KAPAL_CABANG.MST_PBM emkl
								       ON REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
								       AND emkl.KD_CABANG = '05'
								       INNER JOIN KAPAL_CABANG.MST_PELABUHAN ASAL
								       ON KD_PELABUHAN_ASAL = ASAL.KD_PELABUHAN
								       INNER JOIN KAPAL_CABANG.MST_PELABUHAN TUJUAN
								       ON KD_PELABUHAN_TUJUAN = TUJUAN.KD_PELABUHAN
								 WHERE REQUEST_DELIVERY.NO_REQUEST = '$no_req' AND DELIVERY_KE = 'TPK'";
			$result_request	= $db->query($query_request);
			$row_request	= $result_request->fetchRow();
		}		
	
		//debug($row_request);
	
		$tl->assign("row_request", $row_request);

	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
