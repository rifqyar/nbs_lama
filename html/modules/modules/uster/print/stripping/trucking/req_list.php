<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('req_list.htm');

//-----------------paging
/*
	if(isset($_GET["page"]))
	{
		$page = $_GET["page"];	
	}
	else
	{
		$page = 1;	
	}
*/
//------------------------	
	
	$db = getDB("storage");
	$no_request = $_POST["no_req"];
	$from = $_POST["from"];
	$to = $_POST["to"];
	
	if(isset($_POST["cari"]))
	{
		if($_POST["no_req"] != NULL && $_POST["from"] == NULL && $_POST["to"] == NULL){
			$query_list	= "SELECT REQUEST_STRIPPING.*,                                 
                                emkl.NM_PBM AS NAMA_CONSIGNEE,
                                pnmt.NM_PBM AS NAMA_PENUMPUK,
                                NVL(NOTA_STRIPPING.LUNAS,'NO') AS LUNAS
                            FROM REQUEST_STRIPPING INNER JOIN V_MST_PBM emkl ON REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM 
                                JOIN V_MST_PBM pnmt ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM
                               LEFT OUTER  JOIN NOTA_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST
                            WHERE REQUEST_STRIPPING.TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') 
                                AND LAST_DAY(SYSDATE)                                 
								AND REQUEST_STRIPPING.NO_REQUEST = '$no_request'
                            ORDER BY REQUEST_STRIPPING.NO_REQUEST DESC";
		}
		else if ($_POST["no_req"] == NULL && $_POST["from"] != NULL && $_POST["to"] != NULL){
			$query_list	= "SELECT REQUEST_STRIPPING.*,                                 
                                emkl.NM_PBM AS NAMA_CONSIGNEE,
                                pnmt.NM_PBM AS NAMA_PENUMPUK,
                                NVL(NOTA_STRIPPING.LUNAS,'NO') AS LUNAS
                            FROM REQUEST_STRIPPING INNER JOIN V_MST_PBM emkl ON REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM 
                                JOIN V_MST_PBM pnmt ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM
                               LEFT OUTER  JOIN NOTA_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST
                            WHERE REQUEST_STRIPPING.TGL_REQUEST BETWEEN TO_DATE('$from', 'yy-mm-dd') AND TO_DATE('$to','yy-mm-dd')
                            ORDER BY REQUEST_STRIPPING.NO_REQUEST DESC";
		}
		else{
			$query_list	= "SELECT REQUEST_STRIPPING.*, 
                                emkl.NM_PBM AS NAMA_CONSIGNEE,
                                pnmt.NM_PBM AS NAMA_PENUMPUK,
                                NVL(NOTA_STRIPPING.LUNAS,'NO') AS LUNAS
                            FROM REQUEST_STRIPPING INNER JOIN V_MST_PBM emkl ON REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM 
                                JOIN V_MST_PBM pnmt ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM
                               LEFT OUTER  JOIN NOTA_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST
                            WHERE REQUEST_STRIPPING.TGL_REQUEST BETWEEN TO_DATE('$from', 'yy-mm-dd') AND TO_DATE('$to','yy-mm-dd')
								AND REQUEST_STRIPPING.NO_REQUEST = '$no_request'
                            ORDER BY REQUEST_STRIPPING.NO_REQUEST DESC";
		}
	}
	else
	{
		//$query_list = "SELECT REQUEST_STRIPPING.*, emkl.NAMA AS NAMA_EMKL, pnmt.NAMA AS NAMA_PNMT, NOTA_STRIPPING.LUNAS FROM REQUEST_STRIPPING INNER JOIN MASTER_PBM emkl ON REQUEST_STRIPPING.ID_EMKL = emkl.ID JOIN MASTER_PBM pnmt ON REQUEST_STRIPPING.ID_PEMILIK = pnmt.ID  JOIN NOTA_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST WHERE REQUEST_STRIPPING.TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ORDER BY REQUEST_STRIPPING.NO_REQUEST DESC";
		$query_list = "SELECT REQUEST_STRIPPING.*,
						         emkl.NM_PBM AS NAMA_CONSIGNEE,
						         emkl.NM_PBM AS NAMA_PENUMPUK,
						         NVL (NOTA_STRIPPING.LUNAS, 'NO') AS LUNAS
						    FROM REQUEST_STRIPPING
						         INNER JOIN KAPAL_CABANG.MST_PBM emkl
						            ON REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM
						         LEFT  JOIN NOTA_STRIPPING
						            ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST
						   WHERE REQUEST_STRIPPING.TGL_REQUEST BETWEEN SYSDATE - INTERVAL '15' DAY
						                                           AND LAST_DAY (SYSDATE)
						ORDER BY REQUEST_STRIPPING.TGL_REQUEST DESC";
	}
	
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
