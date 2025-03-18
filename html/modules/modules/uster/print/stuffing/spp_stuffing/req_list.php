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

	$from		= $_POST["from"]; 
	$to			= $_POST["to"];
	$no_req		= $_POST["no_req"];
	$id_yard	= $_SESSION["IDYARD_STORAGE"]; 	

	if(isset($_POST["cari"]))
	{
		if((isset($_POST["no_req"])) && ($from == NULL) && ($to == NULL))
		{
			$query_list		= "SELECT REQUEST_STUFFING.NO_REQUEST,
							         REQUEST_STUFFING.TGL_REQUEST,
							         NVL (REQUEST_STUFFING.NO_REQUEST_RECEIVING, ' - ') AS REQ_REC,
							         emkl.NM_PBM AS NAMA_CONSIGNEE,
							         NVL (NOTA_STUFFING.LUNAS, 'NO') AS LUNAS,
							         COUNT (container_stuffing.NO_CONTAINER) JML
							    FROM REQUEST_STUFFING
							         INNER JOIN KAPAL_CABANG.MST_PBM emkl
							            ON REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM
							               AND emkl.KD_CABANG = '05'
							         LEFT JOIN NOTA_STUFFING
							            ON REQUEST_STUFFING.NO_REQUEST = NOTA_STUFFING.NO_REQUEST
							         INNER JOIN container_stuffing
							            ON container_stuffing.NO_REQUEST = request_stuffing.no_request
							   WHERE REQUEST_STUFFING.NO_REQUEST LIKE '%$no_req%'
							         AND REQUEST_STUFFING.STUFFING_DARI NOT IN ('AUTO')
							GROUP BY REQUEST_STUFFING.NO_REQUEST,
							         REQUEST_STUFFING.TGL_REQUEST,
							         NVL (REQUEST_STUFFING.NO_REQUEST_RECEIVING, ' - '),
							         emkl.NM_PBM,
							         NVL (NOTA_STUFFING.LUNAS, 'NO')
							ORDER BY REQUEST_STUFFING.TGL_REQUEST DESC";
		}
		else if((isset($_POST["from"]))&& (isset($_POST["to"])) && ($no_req == NULL))
		{
			$query_list		= " SELECT REQUEST_STUFFING.NO_REQUEST, REQUEST_STUFFING.TGL_REQUEST, 
                                NVL(REQUEST_STUFFING.NO_REQUEST_RECEIVING,' - ') AS REQ_REC, 
                                emkl.NM_PBM AS NAMA_CONSIGNEE,
                                NVL(NOTA_STUFFING.LUNAS,'NO') AS LUNAS,
                                COUNT(container_stuffing.NO_CONTAINER) JML
                            FROM REQUEST_STUFFING 
                            INNER JOIN V_MST_PBM emkl ON REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM 
                               LEFT OUTER  JOIN NOTA_STUFFING ON REQUEST_STUFFING.NO_REQUEST = NOTA_STUFFING.NO_REQUEST
                            INNER JOIN container_stuffing ON container_stuffing.NO_REQUEST = request_stuffing.no_request
                            WHERE request_stuffing.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
                                GROUP BY REQUEST_STUFFING.NO_REQUEST,
								REQUEST_STUFFING.TGL_REQUEST, 
                                NVL(REQUEST_STUFFING.NO_REQUEST_RECEIVING,' - ') , 
                                emkl.NM_PBM,
                                NVL(NOTA_STUFFING.LUNAS,'NO')
                            ORDER BY REQUEST_STUFFING.NO_REQUEST DESC";
		} else if((isset($_POST["from"]))&& (isset($_POST["to"])) && (isset($_POST["no_req"])))
		{
			$query_list		= " SELECT REQUEST_STUFFING.NO_REQUEST, REQUEST_STUFFING.TGL_REQUEST, 
                                NVL(REQUEST_STUFFING.NO_REQUEST_RECEIVING,' - ') AS REQ_REC, 
                                emkl.NM_PBM AS NAMA_CONSIGNEE,
                                NVL(NOTA_STUFFING.LUNAS,'NO') AS LUNAS,
                                COUNT(container_stuffing.NO_CONTAINER) JML
                            FROM REQUEST_STUFFING 
                            INNER JOIN V_MST_PBM emkl ON REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM 
                               LEFT OUTER  JOIN NOTA_STUFFING ON REQUEST_STUFFING.NO_REQUEST = NOTA_STUFFING.NO_REQUEST
                            INNER JOIN container_stuffing ON container_stuffing.NO_REQUEST = request_stuffing.no_request
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
		$query_list = "SELECT REQUEST_STUFFING.NO_REQUEST,
				         REQUEST_STUFFING.TGL_REQUEST,
				         NVL (REQUEST_STUFFING.NO_REQUEST_RECEIVING, ' - ') AS REQ_REC,
				         emkl.NM_PBM AS NAMA_CONSIGNEE,
				         NVL (NOTA_STUFFING.LUNAS, 'NO') AS LUNAS,
				         COUNT (container_stuffing.NO_CONTAINER) JML
				    FROM REQUEST_STUFFING
				         INNER JOIN KAPAL_CABANG.MST_PBM emkl
				            ON REQUEST_STUFFING.KD_CONSIGNEE = emkl.KD_PBM
				               AND emkl.KD_CABANG = '05'
				         LEFT JOIN NOTA_STUFFING
				            ON REQUEST_STUFFING.NO_REQUEST = NOTA_STUFFING.NO_REQUEST
				         INNER JOIN container_stuffing
				            ON container_stuffing.NO_REQUEST = request_stuffing.no_request
				   WHERE REQUEST_STUFFING.TGL_REQUEST BETWEEN SYSDATE - INTERVAL '15' DAY
				                                          AND LAST_DAY (SYSDATE)
				         AND REQUEST_STUFFING.STUFFING_DARI NOT IN ('AUTO')
				GROUP BY REQUEST_STUFFING.NO_REQUEST,
				         REQUEST_STUFFING.TGL_REQUEST,
				         NVL (REQUEST_STUFFING.NO_REQUEST_RECEIVING, ' - '),
				         emkl.NM_PBM,
				         NVL (NOTA_STUFFING.LUNAS, 'NO')
				ORDER BY REQUEST_STUFFING.TGL_REQUEST DESC";
	}
	
	/* $result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll();  */
	
		
	if(isset($_GET['pp'])){
		$pp = $_GET['pp'];
	}else{
		$pp = 1;
	}
	
	$item_per_page = 20;
	
	$totalNum = $db->query($query_list)->RecordCount();
	$maxPage   = ceil($totalNum / $item_per_page)-1; 
	if ($maxPage<0) $maxPage = 0;
		
	$page   = ( $pp <= $maxPage+1 && $pp > 0 )?$pp-1:0;
	$__offset = $page * $item_per_page;
	
	$rs 	= $db->selectLimit( $query_list,$__offset,$item_per_page );
	$rows 	= array();
	if ($rs && $rs->RecordCount()>0) {
		
		for ($__rowindex = 1 + $__offset; $row=$rs->FetchRow(); $__rowindex++) {
			$row["__no"] = $__rowindex;
			$rows[] = $row;
		}
		$rs->close();
	}
	$row_list = & $rows;
	## navigator
	#
	//echo $maxPage;die;
	if ($maxPage>0) {
		$multipage = true;
		
		## begin create nav
		$pages = array();
		for ($i=0;$i<=$maxPage;$i++)
			$pages[] = array($i+1,$i+1);
		$nav['pages'] = $pages;
				
		if ($page>0) {
			$nav['prev'] = array( 'label'=>'Prev', 'p'=>$page-1 );
		} else {
			$nav['prev'] = false;
		}
		
		if ($page < $maxPage) {
			$nav['next'] = array( 'label'=>'Next', 'p'=>$page+1 );
		} else {
			$nav['next'] = false;
		}
		## end create nav
		
		$navlist = $nav['pages'];
		$navpage = $page+1;

		if ($pp <= $maxPage) {
			$nextvisible 	= true;
			$navnext		= $nav['next'];
		}	
		if ($pp > 1) {
			$prevvisible	= true;
			$navprev		= $nav['prev'];
		}	
	}
	
	$tl->assign("prevvisible",$prevvisible);	
	$tl->assign("navpage",$navpage);	
	$tl->assign("navlist",$navlist);	
	$tl->assign("nextvisible",$nextvisible);	
	$tl->assign("multipage",$multipage);
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
