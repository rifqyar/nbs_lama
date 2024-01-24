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
      $cari = $_POST["cari"];
      $no_req = $_POST["no_req"];
      $from = $_POST["from"];
      $to  = $_POST["to"];
	
	$db = getDB("storage");
       
	if(isset($_POST["cari"])){
       	if(isset($_POST["no_req"]) && $from==NULL && $to==NULL){
		$query_list = "SELECT REQUEST_RELOKASI.NO_REQUEST, TO_CHAR(REQUEST_RELOKASI.TGL_REQUEST,'dd/mm/yy') TGL_REQUEST,
                    YARD_AREA1.NAMA_YARD YARD_TUJUAN, YARD_AREA2.NAMA_YARD YARD_ASAL,
                    (SELECT COUNT(CONTAINER_RELOKASI.NO_CONTAINER) FROM CONTAINER_RELOKASI 
                    WHERE CONTAINER_RELOKASI.NO_REQUEST = REQUEST_RELOKASI.NO_REQUEST AND CONTAINER_RELOKASI.AKTIF = 'Y') BOX
                    FROM REQUEST_RELOKASI  
                    INNER JOIN YARD_AREA YARD_AREA1 ON REQUEST_RELOKASI.YARD_TUJUAN = YARD_AREA1.ID
                    INNER JOIN YARD_AREA YARD_AREA2 ON REQUEST_RELOKASI.YARD_ASAL = YARD_AREA2.ID 
                    WHERE REQUEST_RELOKASI.NO_REQUEST LIKE '%$no_req%' AND REQUEST_RELOKASI.TIPE_RELOKASI = 'INTERNAL'
                    ORDER BY REQUEST_RELOKASI.TGL_REQUEST DESC";
		}
		else if($no_req==NULL && isset($_POST["from"]) && isset($_POST["to"])){
		$query_list = "SELECT REQUEST_RELOKASI.NO_REQUEST, REQUEST_RELOKASI.TGL_REQUEST,
                    YARD_AREA1.NAMA_YARD YARD_TUJUAN, YARD_AREA2.NAMA_YARD YARD_ASAL,
                    (SELECT COUNT(CONTAINER_RELOKASI.NO_CONTAINER) FROM CONTAINER_RELOKASI 
                    WHERE CONTAINER_RELOKASI.NO_REQUEST = REQUEST_RELOKASI.NO_REQUEST AND CONTAINER_RELOKASI.AKTIF = 'Y') BOX
                    FROM REQUEST_RELOKASI  
                    INNER JOIN YARD_AREA YARD_AREA1 ON REQUEST_RELOKASI.YARD_TUJUAN = YARD_AREA1.ID
                    INNER JOIN YARD_AREA YARD_AREA2 ON REQUEST_RELOKASI.YARD_ASAL = YARD_AREA2.ID 
                    WHERE REQUEST_RELOKASI.TGL_REQUEST BETWEEN TO_DATE('$from','yy-mm-dd') AND TO_DATE('$to','yy-mm-dd') AND REQUEST_RELOKASI.TIPE_RELOKASI = 'INTERNAL'
                    ORDER BY REQUEST_RELOKASI.TGL_REQUEST DESC";
		}
           else {
		$query_list = "SELECT REQUEST_RELOKASI.NO_REQUEST, REQUEST_RELOKASI.TGL_REQUEST,
                    YARD_AREA1.NAMA_YARD YARD_TUJUAN, YARD_AREA2.NAMA_YARD YARD_ASAL,
                    (SELECT COUNT(CONTAINER_RELOKASI.NO_CONTAINER) FROM CONTAINER_RELOKASI 
                    WHERE CONTAINER_RELOKASI.NO_REQUEST = REQUEST_RELOKASI.NO_REQUEST AND CONTAINER_RELOKASI.AKTIF = 'Y') BOX
                    FROM REQUEST_RELOKASI  
                    INNER JOIN YARD_AREA YARD_AREA1 ON REQUEST_RELOKASI.YARD_TUJUAN = YARD_AREA1.ID
                    INNER JOIN YARD_AREA YARD_AREA2 ON REQUEST_RELOKASI.YARD_ASAL = YARD_AREA2.ID 
                    WHERE REQUEST_RELOKASI.NO_REQUEST LIKE '%$no_req%' AND
			REQUEST_RELOKASI.TGL_REQUEST BETWEEN TO_DATE('$from','yy-mm-dd') AND TO_DATE('$to','yy-mm-dd') AND REQUEST_RELOKASI.TIPE_RELOKASI = 'INTERNAL'
                    ORDER BY REQUEST_RELOKASI.TGL_REQUEST DESC";
		}
            

       }
	else {
       $query_list = "SELECT REQUEST_RELOKASI.NO_REQUEST, TO_CHAR(REQUEST_RELOKASI.TGL_REQUEST,'dd/mm/yy') TGL_REQUEST,
                    YARD_AREA1.NAMA_YARD YARD_TUJUAN, YARD_AREA2.NAMA_YARD YARD_ASAL,
                    (SELECT COUNT(CONTAINER_RELOKASI.NO_CONTAINER) FROM CONTAINER_RELOKASI 
                    WHERE CONTAINER_RELOKASI.NO_REQUEST = REQUEST_RELOKASI.NO_REQUEST AND CONTAINER_RELOKASI.AKTIF = 'Y') BOX
                    FROM REQUEST_RELOKASI  
                    INNER JOIN YARD_AREA YARD_AREA1 ON REQUEST_RELOKASI.YARD_TUJUAN = YARD_AREA1.ID
                    INNER JOIN YARD_AREA YARD_AREA2 ON REQUEST_RELOKASI.YARD_ASAL = YARD_AREA2.ID 
                    WHERE REQUEST_RELOKASI.TIPE_RELOKASI = 'INTERNAL'
                    ORDER BY REQUEST_RELOKASI.TGL_REQUEST DESC";
	}
		
	/* $result_list	= $db->query($query_list);
	$row_list       = $result_list->getAll(); 
	echo $query_list;	 */
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
	
	function cek_cetak($no_req)
	{
		$db 		= getDB("storage");
		$query_cek	= "SELECT CETAK_KARTU FROM REQUEST_RELOKASI WHERE NO_REQUEST = '$no_req'";	
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->fetchRow();
		
		if($row_cek["CETAK_KARTU"] > 0)
		{
				//echo 'KARTU SP2 SUDAH DICETAK';
				echo '<a href="'.HOME.APPID.'/overview?no_req='.$no_req.'" target="_blank"> SP2 SUDAH DICETAK</a> ';		
		}
		else
		{
			echo '<a href="'.HOME.APPID.'/view?no_req='.$no_req.'" target="_self"> EDIT </a> ';
		}
	}
?>
