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

	$from		= $_POST["FROM"]; 
	$to			= $_POST["TO"];
	$no_req		= $_POST["NO_REQ"];
	$id_yard	= $_SESSION["IDYARD_STORAGE"]; 	

	if(isset($_POST["CARI"]))
	{
		if($_POST["NO_REQ"] != NULL && $_POST["FROM"] == NULL && $_POST["TO"] == NULL)
		{
			$query_list		= " SELECT REQUEST_STUFFING.NO_REQUEST, REQUEST_STUFFING.TGL_REQUEST, 
                                NVL(REQUEST_STUFFING.NO_REQUEST_RECEIVING,' - ') AS REQ_REC, 
                                emkl.NM_PBM AS NAMA_CONSIGNEE,
                                NVL(NOTA_STUFFING.LUNAS,'NO') AS LUNAS,
                                COUNT(container_stuffing.NO_CONTAINER) JML
                            FROM REQUEST_STUFFING 
                            INNER JOIN V_MST_PBM emkl ON REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM 
                               LEFT OUTER  JOIN NOTA_STUFFING ON REQUEST_STUFFING.NO_REQUEST = NOTA_STUFFING.NO_REQUEST
                            INNER JOIN container_stuffing 
                            	ON container_stuffing.NO_REQUEST = request_stuffing.no_request
                            	AND container_stuffing.NO_CONTAINER NOT LIKE '%rename%'
                            WHERE request_stuffing.no_request = '$no_req'
							and REQUEST_STUFFING.TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') 
                                AND LAST_DAY(SYSDATE) 
                                GROUP BY REQUEST_STUFFING.NO_REQUEST,
								REQUEST_STUFFING.TGL_REQUEST, 
                                NVL(REQUEST_STUFFING.NO_REQUEST_RECEIVING,' - ') , 
                                emkl.NM_PBM,
                                NVL(NOTA_STUFFING.LUNAS,'NO')
                            ORDER BY REQUEST_STUFFING.NO_REQUEST DESC";
		}
		else if ($_POST["NO_REQ"] == NULL && $_POST["FROM"] != NULL && $_POST["TO"] != NULL)
		{
			$query_list		= " SELECT REQUEST_STUFFING.NO_REQUEST, REQUEST_STUFFING.TGL_REQUEST, 
                                NVL(REQUEST_STUFFING.NO_REQUEST_RECEIVING,' - ') AS REQ_REC, 
                                emkl.NM_PBM AS NAMA_CONSIGNEE,
                                NVL(NOTA_STUFFING.LUNAS,'NO') AS LUNAS,
                                COUNT(container_stuffing.NO_CONTAINER) JML
                            FROM REQUEST_STUFFING 
                            INNER JOIN V_MST_PBM emkl ON REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM 
                               LEFT OUTER  JOIN NOTA_STUFFING ON REQUEST_STUFFING.NO_REQUEST = NOTA_STUFFING.NO_REQUEST
                            INNER JOIN container_stuffing 
                            	ON container_stuffing.NO_REQUEST = request_stuffing.no_request
                            	AND container_stuffing.NO_CONTAINER NOT LIKE '%rename%'
                            WHERE request_stuffing.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'yy-mm-dd ')
                                AND  TO_DATE ( '$to', 'yy-mm-dd ')
                                GROUP BY REQUEST_STUFFING.NO_REQUEST,
								REQUEST_STUFFING.TGL_REQUEST, 
                                NVL(REQUEST_STUFFING.NO_REQUEST_RECEIVING,' - ') , 
                                emkl.NM_PBM,
                                NVL(NOTA_STUFFING.LUNAS,'NO')
                            ORDER BY REQUEST_STUFFING.NO_REQUEST DESC";
		}		
		else if ($_POST["NO_REQ"] == NULL && $_POST["FROM"] == NULL && $_POST["TO"] == NULL)
		{
			$query_list = " SELECT REQUEST_STUFFING.NO_REQUEST, REQUEST_STUFFING.TGL_REQUEST, 
                                NVL(REQUEST_STUFFING.NO_REQUEST_RECEIVING,' - ') AS REQ_REC, 
                                emkl.NM_PBM AS NAMA_CONSIGNEE,
                                NVL(NOTA_STUFFING.LUNAS,'NO') AS LUNAS,
                                COUNT(container_stuffing.NO_CONTAINER) JML
                            FROM REQUEST_STUFFING 
                            INNER JOIN V_MST_PBM emkl ON REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM 
                            LEFT OUTER  JOIN NOTA_STUFFING ON REQUEST_STUFFING.NO_REQUEST = NOTA_STUFFING.NO_REQUEST
                            INNER JOIN container_stuffing 
                            	ON container_stuffing.NO_REQUEST = request_stuffing.no_request
                            	AND container_stuffing.NO_CONTAINER NOT LIKE '%rename%'
                            WHERE REQUEST_STUFFING.TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') 
                                AND LAST_DAY(SYSDATE) 
                                GROUP BY REQUEST_STUFFING.NO_REQUEST,
								REQUEST_STUFFING.TGL_REQUEST, 
                                NVL(REQUEST_STUFFING.NO_REQUEST_RECEIVING,' - ') , 
                                emkl.NM_PBM,
                                NVL(NOTA_STUFFING.LUNAS,'NO')
                            ORDER BY REQUEST_STUFFING.NO_REQUEST DESC";
		}
		else
		{
			$query_list		= " SELECT REQUEST_STUFFING.NO_REQUEST, REQUEST_STUFFING.TGL_REQUEST, 
                                NVL(REQUEST_STUFFING.NO_REQUEST_RECEIVING,' - ') AS REQ_REC, 
                                emkl.NM_PBM AS NAMA_CONSIGNEE,
                                NVL(NOTA_STUFFING.LUNAS,'NO') AS LUNAS,
                                COUNT(container_stuffing.NO_CONTAINER) JML
                            FROM REQUEST_STUFFING 
                            INNER JOIN V_MST_PBM emkl ON REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM 
                               LEFT OUTER  JOIN NOTA_STUFFING ON REQUEST_STUFFING.NO_REQUEST = NOTA_STUFFING.NO_REQUEST
                            INNER JOIN container_stuffing 
                            	ON container_stuffing.NO_REQUEST = request_stuffing.no_request
                            	AND container_stuffing.NO_CONTAINER NOT LIKE '%rename%'
                            WHERE request_stuffing.no_request = '$no_req'
							AND request_stuffing.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
                                GROUP BY REQUEST_STUFFING.NO_REQUEST,
								REQUEST_STUFFING.TGL_REQUEST, 
                                NVL(REQUEST_STUFFING.NO_REQUEST_RECEIVING,' - ') , 
                                emkl.NM_PBM,
                                NVL(NOTA_STUFFING.LUNAS,'NO')
                            ORDER BY REQUEST_STUFFING.NO_REQUEST DESC";
		}
		
	}
	else
	{
		/*
		$query_list = "SELECT REQUEST_STUFFING.*, emkl.NAMA AS NAMA_EMKL, pnmt.NAMA AS NAMA_PNMT, NOTA_STUFFING.LUNAS FROM REQUEST_STUFFING INNER JOIN MASTER_PBM emkl ON REQUEST_STUFFING.ID_EMKL = emkl.ID JOIN MASTER_PBM pnmt ON REQUEST_STUFFING.ID_PEMILIK = pnmt.ID  JOIN NOTA_STUFFING ON REQUEST_STUFFING.NO_REQUEST = NOTA_STUFFING.NO_REQUEST WHERE REQUEST_STUFFING.TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ORDER BY REQUEST_STUFFING.NO_REQUEST DESC";
		*/
		$query_list = " SELECT REQUEST_STUFFING.NO_REQUEST, REQUEST_STUFFING.TGL_REQUEST, 
                                NVL(REQUEST_STUFFING.NO_REQUEST_RECEIVING,' - ') AS REQ_REC, 
                                emkl.NM_PBM AS NAMA_CONSIGNEE,
                                NVL(NOTA_STUFFING.LUNAS,'NO') AS LUNAS,
                                COUNT(container_stuffing.NO_CONTAINER) JML
                            FROM REQUEST_STUFFING 
                            INNER JOIN V_MST_PBM emkl ON REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM 
                               LEFT OUTER  JOIN NOTA_STUFFING ON REQUEST_STUFFING.NO_REQUEST = NOTA_STUFFING.NO_REQUEST
                            INNER JOIN container_stuffing 
                            	ON container_stuffing.NO_REQUEST = request_stuffing.no_request
                            	AND container_stuffing.NO_CONTAINER NOT LIKE '%rename%'
                            WHERE REQUEST_STUFFING.TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') 
                                AND LAST_DAY(SYSDATE) 
                                GROUP BY REQUEST_STUFFING.NO_REQUEST,
								REQUEST_STUFFING.TGL_REQUEST, 
                                NVL(REQUEST_STUFFING.NO_REQUEST_RECEIVING,' - ') , 
                                emkl.NM_PBM,
                                NVL(NOTA_STUFFING.LUNAS,'NO')
                            ORDER BY REQUEST_STUFFING.NO_REQUEST DESC";
	}
	
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
