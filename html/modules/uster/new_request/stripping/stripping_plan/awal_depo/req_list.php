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
	
	$cari	= $_POST["CARI"];
	$from	= $_POST["FROM"]; 
	$to		= $_POST["TO"];
	$no_req	= $_POST["NO_REQ"];
	
	if(isset($_POST["CARI"]) ) 
	{
		if((isset($_POST["NO_REQ"])) && ($from == NULL) && ($to == NULL))
		{
			$query_list		= " SELECT PLAN_REQUEST_STRIPPING.*, 
								emkl.NM_PBM AS NAMA_PEMILIK, 
								pnmt.NM_PBM AS NAMA_PENUMPUK 
								FROM PLAN_REQUEST_STRIPPING 
								INNER JOIN V_MST_PBM emkl ON PLAN_REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM 
								JOIN V_MST_PBM pnmt ON PLAN_REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM 
								WHERE								
								PLAN_REQUEST_STRIPPING.NO_REQUEST = '$no_req'
								AND STRIPPING_DARI = 'DEPO' 
								ORDER BY PLAN_REQUEST_STRIPPING.TGL_REQUEST DESC";
		}
		else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && ($no_req == NULL))
		{
			$query_list		= " SELECT PLAN_REQUEST_STRIPPING.*, 
								emkl.NM_PBM AS NAMA_PEMILIK, 
								pnmt.NM_PBM AS NAMA_PENUMPUK 
								FROM PLAN_REQUEST_STRIPPING 
								INNER JOIN V_MST_PBM emkl ON PLAN_REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM 
								JOIN V_MST_PBM pnmt ON PLAN_REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM 
								WHERE								
								PLAN_REQUEST_STRIPPING.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                AND TO_DATE(CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
								AND STRIPPING_DARI = 'DEPO' 
								ORDER BY PLAN_REQUEST_STRIPPING.TGL_REQUEST DESC";
		} else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && (isset($_POST["NO_REQ"])))
		{
			$query_list		= " SELECT PLAN_REQUEST_STRIPPING.*, 
								emkl.NM_PBM AS NAMA_PEMILIK, 
								pnmt.NM_PBM AS NAMA_PENUMPUK 
								FROM PLAN_REQUEST_STRIPPING 
								INNER JOIN V_MST_PBM emkl ON PLAN_REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM 
								JOIN V_MST_PBM pnmt ON PLAN_REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM 
								WHERE
								PLAN_REQUEST_STRIPPING.NO_REQUEST = '$no_req'								
								AND PLAN_REQUEST_STRIPPING.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                AND TO_DATE(CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
								AND STRIPPING_DARI = 'DEPO' 
								ORDER BY PLAN_REQUEST_STRIPPING.TGL_REQUEST DESC";		
		}
		
	} else
	{
		$query_list		= "SELECT PLAN_REQUEST_STRIPPING.*, emkl.NM_PBM AS NAMA_PEMILIK, pnmt.NM_PBM AS NAMA_PENUMPUK FROM PLAN_REQUEST_STRIPPING INNER JOIN V_MST_PBM emkl ON PLAN_REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM JOIN V_MST_PBM pnmt ON PLAN_REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM WHERE PLAN_REQUEST_STRIPPING.TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) AND STRIPPING_DARI = 'DEPO' ORDER BY PLAN_REQUEST_STRIPPING.TGL_REQUEST DESC";
	}
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
		
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	
	
?>
