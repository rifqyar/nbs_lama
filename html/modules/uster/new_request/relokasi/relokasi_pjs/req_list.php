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
	$cari = $_POST["cari"];
	$no_req = $_POST["no_req"];
	$from = $_POST["from"];
	$to = $_POST["to"];
	
	if($cari == "cari"){
		if($no_req != NULL && $from == NULL && $to == NULL){
			$query_list = "SELECT REQUEST_RELOKASI.NO_REQUEST, TO_CHAR(REQUEST_RELOKASI.TGL_REQUEST,'dd/mm/yy') TGL_REQUEST,
					YARD_AREA1.NAMA_YARD YARD_TUJUAN, YARD_AREA2.NAMA_YARD YARD_ASAL,
					(SELECT COUNT(CONTAINER_RELOKASI.NO_CONTAINER) FROM CONTAINER_RELOKASI 
					WHERE CONTAINER_RELOKASI.NO_REQUEST = REQUEST_RELOKASI.NO_REQUEST AND CONTAINER_RELOKASI.AKTIF = 'Y') BOX
					FROM REQUEST_RELOKASI  
					INNER JOIN YARD_AREA YARD_AREA1 ON REQUEST_RELOKASI.YARD_TUJUAN = YARD_AREA1.ID
					INNER JOIN YARD_AREA YARD_AREA2 ON REQUEST_RELOKASI.YARD_ASAL = YARD_AREA2.ID 
					WHERE REQUEST_RELOKASI.NO_REQUEST = '$no_req' AND REQUEST_RELOKASI.TIPE_RELOKASI = 'EXTERNAL'
					ORDER BY REQUEST_RELOKASI.TGL_REQUEST DESC";
		}
		else if($no_req == NULL && $from != NULL && $to != NULL){
			$query_list = "SELECT REQUEST_RELOKASI.NO_REQUEST, TO_CHAR(REQUEST_RELOKASI.TGL_REQUEST,'dd/mm/yy') TGL_REQUEST,
					YARD_AREA1.NAMA_YARD YARD_TUJUAN, YARD_AREA2.NAMA_YARD YARD_ASAL,
					(SELECT COUNT(CONTAINER_RELOKASI.NO_CONTAINER) FROM CONTAINER_RELOKASI 
					WHERE CONTAINER_RELOKASI.NO_REQUEST = REQUEST_RELOKASI.NO_REQUEST AND CONTAINER_RELOKASI.AKTIF = 'Y') BOX
					FROM REQUEST_RELOKASI  
					INNER JOIN YARD_AREA YARD_AREA1 ON REQUEST_RELOKASI.YARD_TUJUAN = YARD_AREA1.ID
					INNER JOIN YARD_AREA YARD_AREA2 ON REQUEST_RELOKASI.YARD_ASAL = YARD_AREA2.ID 
					WHERE REQUEST_RELOKASI.TGL_REQUEST BETWEEN TO_DATE('$from','yy-mm-dd') AND TO_DATE('$to','yy-mm-dd')
					AND REQUEST_RELOKASI.TIPE_RELOKASI = 'EXTERNAL'
					ORDER BY REQUEST_RELOKASI.TGL_REQUEST DESC";
		}
		else if($no_req != NULL && $from != NULL && $to != NULL){
			$query_list = "SELECT REQUEST_RELOKASI.NO_REQUEST, TO_CHAR(REQUEST_RELOKASI.TGL_REQUEST,'dd/mm/yy') TGL_REQUEST,
					YARD_AREA1.NAMA_YARD YARD_TUJUAN, YARD_AREA2.NAMA_YARD YARD_ASAL,
					(SELECT COUNT(CONTAINER_RELOKASI.NO_CONTAINER) FROM CONTAINER_RELOKASI 
					WHERE CONTAINER_RELOKASI.NO_REQUEST = REQUEST_RELOKASI.NO_REQUEST AND CONTAINER_RELOKASI.AKTIF = 'Y') BOX
					FROM REQUEST_RELOKASI  
					INNER JOIN YARD_AREA YARD_AREA1 ON REQUEST_RELOKASI.YARD_TUJUAN = YARD_AREA1.ID
					INNER JOIN YARD_AREA YARD_AREA2 ON REQUEST_RELOKASI.YARD_ASAL = YARD_AREA2.ID 
					WHERE REQUEST_RELOKASI.TGL_REQUEST BETWEEN TO_DATE('$from','yy-mm-dd') AND TO_DATE('$to','yy-mm-dd')
					AND REQUEST_RELOKASI.NO_REQUEST = '$no_req' AND REQUEST_RELOKASI.TIPE_RELOKASI = 'EXTERNAL'
					ORDER BY REQUEST_RELOKASI.TGL_REQUEST DESC";
		}
		else{
			$query_list = "SELECT REQUEST_RELOKASI.NO_REQUEST, TO_CHAR(REQUEST_RELOKASI.TGL_REQUEST,'dd/mm/yy') TGL_REQUEST,
					YARD_AREA1.NAMA_YARD YARD_TUJUAN, YARD_AREA2.NAMA_YARD YARD_ASAL,
					(SELECT COUNT(CONTAINER_RELOKASI.NO_CONTAINER) FROM CONTAINER_RELOKASI 
					WHERE CONTAINER_RELOKASI.NO_REQUEST = REQUEST_RELOKASI.NO_REQUEST AND CONTAINER_RELOKASI.AKTIF = 'Y') BOX
					FROM REQUEST_RELOKASI  
					INNER JOIN YARD_AREA YARD_AREA1 ON REQUEST_RELOKASI.YARD_TUJUAN = YARD_AREA1.ID
					INNER JOIN YARD_AREA YARD_AREA2 ON REQUEST_RELOKASI.YARD_ASAL = YARD_AREA2.ID 
					WHERE REQUEST_RELOKASI.TIPE_RELOKASI = 'EXTERNAL'
					ORDER BY REQUEST_RELOKASI.TGL_REQUEST DESC";
		}
	}
	
	else{
	
    
	/* $query_list = "SELECT REQUEST_RECEIVING.NO_REQUEST AS NO_REQ_REC, 
					TO_CHAR( REQUEST_RECEIVING.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST_REC, 
					y_asal.NAMA_YARD AS YARD_ASAL, y_dest.NAMA_YARD AS YARD_DEST, 
					REQUEST_DELIVERY.NO_REQUEST AS NO_REQ_DEL, 
					TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST_DEL, 
					(SELECT COUNT(1) FROM CONTAINER_DELIVERY WHERE NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST AND AKTIF = 'Y') AS BOX 
					FROM REQUEST_DELIVERY INNER JOIN REQUEST_RECEIVING ON REQUEST_RECEIVING.EX_REQ_DELIVERY = REQUEST_DELIVERY.NO_REQUEST 
					JOIN YARD_AREA y_asal ON REQUEST_DELIVERY.ID_YARD = y_asal.ID 
					JOIN YARD_AREA y_dest ON REQUEST_RECEIVING.ID_YARD = y_dest.ID 
					WHERE REQUEST_DELIVERY.PERALIHAN = 'RELOKASI' 
					AND rownum <= 10
					ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC"; */
	$query_list = "SELECT REQUEST_RELOKASI.NO_REQUEST, TO_CHAR(REQUEST_RELOKASI.TGL_REQUEST,'dd/mm/yy') TGL_REQUEST,
					YARD_AREA1.NAMA_YARD YARD_TUJUAN, YARD_AREA2.NAMA_YARD YARD_ASAL,
					(SELECT COUNT(CONTAINER_RELOKASI.NO_CONTAINER) FROM CONTAINER_RELOKASI 
					WHERE CONTAINER_RELOKASI.NO_REQUEST = REQUEST_RELOKASI.NO_REQUEST AND CONTAINER_RELOKASI.AKTIF = 'Y') BOX
					FROM REQUEST_RELOKASI  
					INNER JOIN YARD_AREA YARD_AREA1 ON REQUEST_RELOKASI.YARD_TUJUAN = YARD_AREA1.ID
					INNER JOIN YARD_AREA YARD_AREA2 ON REQUEST_RELOKASI.YARD_ASAL = YARD_AREA2.ID 
					WHERE REQUEST_RELOKASI.TIPE_RELOKASI = 'EXTERNAL'
					ORDER BY REQUEST_RELOKASI.TGL_REQUEST DESC";
	
	}
		
	/* $result_list	= $db->query($query_list);
	$row_list       = $result_list->getAll();  */
		
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
		$query_cek	= "SELECT NOTA, KOREKSI FROM request_relokasi WHERE NO_REQUEST = '$no_req'";	
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->getAll();
		
		if(count($row_cek) > 0)
		{
			$nota		= $row_cek[0]["NOTA"];
			$koreksi	= $row_cek[0]["KOREKSI"];
			
			//'print/print_pdf?no_req='.$no_req.'&no_nota='.$no_nota.
			// <a href="{$HOME}{$APPID}/view?no_req={$rows.NO_REQUEST}"> LIHAT </a>
			if(($row_cek[0]["NOTA"] <> 'Y') AND ($row_cek[0]["KOREKSI"] <> 'Y'))
			{
				echo '<a href="'.HOME.APPID.'/view?no_req='.$no_req.'" target="_blank"> EDIT </a> ';
				//echo '<a href="'.HOME.APPID.'/cetak_nota?no_nota='.$no_nota.'&n='.$cetak.'" target="_blank"> CETAK ULANG </a> ';		
			}
			else if(($row_cek[0]["NOTA"] <> 'Y') AND ($row_cek[0]["KOREKSI"] <> 'Y'))
			{
				echo '<a href="'.HOME.APPID.'/view?no_nota='.$no_nota.'" target="_blank"> EDIT </a> ';		
			}
			else if(($row_cek[0]["NOTA"] == 'Y') AND ($row_cek[0]["KOREKSI"] <> 'Y'))
			{
				echo 'Nota sudah cetak';	
			}
			else if(($row_cek[0]["NOTA"] == 'Y') AND ($row_cek[0]["KOREKSI"] == 'Y'))
			{
				echo 'Nota sudah cetak';	
			}
			else if(($row_cek[0]["NOTA"] <> 'Y') AND ($row_cek[0]["KOREKSI"] == 'Y'))
			{
				echo '<a href="'.HOME.APPID.'/view?no_nota='.$no_nota.'" target="_blank"> EDIT </a> ';		
			}

	}}
?>
