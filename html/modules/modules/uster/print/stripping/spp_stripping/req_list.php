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
			$query_list	= "SELECT DISTINCT REQUEST_STRIPPING.*, 
                                NVL(REQUEST_STRIPPING.NO_REQUEST_RECEIVING,' - ') AS REQ_REC, 
                                emkl.NM_PBM AS NAMA_CONSIGNEE,
                                pnmt.NM_PBM AS NAMA_PENUMPUK,
                                NVL(NOTA_STRIPPING.LUNAS,'NO') AS LUNAS
                            FROM REQUEST_STRIPPING INNER JOIN V_MST_PBM emkl ON REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM 
                                JOIN V_MST_PBM pnmt ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM
                               LEFT OUTER  JOIN NOTA_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST
                            WHERE 
							--REQUEST_STRIPPING.TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) 
								--AND 
								REQUEST_STRIPPING.NO_REQUEST = '$no_request'
                            ORDER BY REQUEST_STRIPPING.NO_REQUEST DESC";
		}
		else if ($_POST["no_req"] == NULL && $_POST["from"] != NULL && $_POST["to"] != NULL){
			$query_list	= "SELECT DISTINCT REQUEST_STRIPPING.*, 
                                NVL(REQUEST_STRIPPING.NO_REQUEST_RECEIVING,' - ') AS REQ_REC, 
                                emkl.NM_PBM AS NAMA_CONSIGNEE,
                                pnmt.NM_PBM AS NAMA_PENUMPUK,
                                NVL(NOTA_STRIPPING.LUNAS,'NO') AS LUNAS
                            FROM REQUEST_STRIPPING INNER JOIN V_MST_PBM emkl ON REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM 
                                JOIN V_MST_PBM pnmt ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM
                               LEFT OUTER  JOIN NOTA_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST
                            WHERE REQUEST_STRIPPING.TGL_REQUEST BETWEEN TO_DATE('$from', 'yy-mm-dd') AND TO_DATE('$to','yy-mm-dd') 								
                            ORDER BY REQUEST_STRIPPING.NO_REQUEST DESC";
		}
		else if ($_POST["no_req"] == NULL && $_POST["from"] == NULL && $_POST["to"] == NULL) {
			$query_list	= "SELECT DISTINCT REQUEST_STRIPPING.*, 
                                NVL(REQUEST_STRIPPING.NO_REQUEST_RECEIVING,' - ') AS REQ_REC, 
                                emkl.NM_PBM AS NAMA_CONSIGNEE,
                                pnmt.NM_PBM AS NAMA_PENUMPUK,
                                NVL(NOTA_STRIPPING.LUNAS,'NO') AS LUNAS
                            FROM REQUEST_STRIPPING INNER JOIN V_MST_PBM emkl ON REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM 
								AND emkl.KD_CABANG = '05'
                                JOIN V_MST_PBM pnmt ON REQUEST_STRIPPING.KD_PENUMPUKAN_OLEH = pnmt.KD_PBM
								AND pnmt.KD_CABANG = '05'
                               LEFT OUTER  JOIN NOTA_STRIPPING ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST
                            WHERE REQUEST_STRIPPING.TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') 
                                AND LAST_DAY(SYSDATE) AND ROWNUM <= 20
                            ORDER BY REQUEST_STRIPPING.TGL_REQUEST DESC";
		}
		else{
			$query_list	= "SELECT DISTINCT REQUEST_STRIPPING.*, 
                                NVL(REQUEST_STRIPPING.NO_REQUEST_RECEIVING,' - ') AS REQ_REC, 
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
		$query_list	= " SELECT DISTINCT
					         REQUEST_STRIPPING.*,
					         NVL (REQUEST_STRIPPING.NO_REQUEST_RECEIVING, ' - ') AS REQ_REC,
					         emkl.NM_PBM AS NAMA_CONSIGNEE,
					         emkl.NM_PBM AS NAMA_PENUMPUK,
					         NVL (NOTA_STRIPPING.LUNAS, 'NO') AS LUNAS
					    FROM REQUEST_STRIPPING
					         INNER JOIN KAPAL_CABANG.MST_PBM emkl
					            ON REQUEST_STRIPPING.KD_CONSIGNEE = emkl.KD_PBM
					               AND emkl.KD_CABANG = '05'
					         LEFT OUTER JOIN NOTA_STRIPPING
					            ON REQUEST_STRIPPING.NO_REQUEST = NOTA_STRIPPING.NO_REQUEST
					   WHERE REQUEST_STRIPPING.TGL_REQUEST BETWEEN SYSDATE - INTERVAL '15' DAY
					                                           AND LAST_DAY (SYSDATE)
					ORDER BY REQUEST_STRIPPING.TGL_REQUEST DESC";
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
