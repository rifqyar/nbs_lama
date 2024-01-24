<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('view_detail.htm');

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
	
	
	// "select 	a.NM_AGEN, a.KD_AGEN, a.KD_KAPAL, a.NM_KAPAL,a.VOYAGE_IN,a.VOYAGE_OUT,a.NO_UKK, a.NO_BOOKING, 	   
							// TO_DATE(a.tgl_jam_berangkat,'dd/mm/RRRR') TGL_BERANGKAT,TO_DATE(a.tgl_stacking) TGL_STACKING, TO_DATE(a.tgl_muat,'DD/MM/RRRR') TGL_MUAT  
					// from v_booking_stack_tpk a
					// where a.NM_KAPAL LIKE '%$nama_kapal%' 
						// and sysdate between to_timestamp (a.DOC_CLOSING_DATE_DRY-4)
									// and to_timestamp (a.DOC_CLOSING_DATE_DRY-2)"
		//Hari yg bener tgl close -2
		$query_list		= "SELECT 	a.NM_KAPAL,a.NO_UKK, a.NO_BOOKING,
									a.DOC_CLOSING_DATE_DRY-2 CLOSING_STUFF,
									a.DOC_CLOSING_DATE_DRY-4 OPEN_STUFF
								FROM V_BOOKING_STACK_TPK a
								WHERE to_timestamp(sysdate) BETWEEN to_timestamp (a.TGL_STACKING)
									and to_timestamp (a.DOC_CLOSING_DATE_DRY)";
	
		// $query_list		= "SELECT 	a.NM_KAPAL,a.NO_UKK, a.NO_BOOKING,a.TGL_JAM_CLOSE, TO_TIMESTAMP (a.TGL_JAM_CLOSE-3) TGL_JAM_OPEN
								// FROM PETIKEMAS_CABANG.V_BOOKING_STACK_TPK a
								// WHERE SYSDATE BETWEEN TO_TIMESTAMP (a.TGL_JAM_CLOSE-3)
								// AND TO_TIMESTAMP (a.TGL_JAM_CLOSE)";
	
	#paging_function
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
	
	
	
	
	/* $result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll();  */
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
