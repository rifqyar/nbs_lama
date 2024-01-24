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

        $from  = $_POST['from'];
        $to    = $_POST['to'];
        $no_req = $_POST['no_req'];
        $id_yard    = 	$_SESSION["IDYARD_STORAGE"];
	
         
            	if($no_req != NULL && $to == NULL && $from == NULL ) 
            	{   
					$query_list ="SELECT REQUEST_RELOKASI.NO_REQUEST NO_REQUEST,
									 TO_CHAR (REQUEST_RELOKASI.TGL_REQUEST, 'dd Mon rrrr') TGL_REQUEST,
									 YARD_AREA1.NAMA_YARD YARD_TUJUAN,
									 YARD_AREA2.NAMA_YARD YARD_ASAL,
									 CASE
										WHEN V_MST_PBM.NM_PBM IS NULL THEN 'USTER'
										ELSE V_MST_PBM.NM_PBM
									 END
										NM_PBM,
									 (SELECT COUNT (CONTAINER_RELOKASI.NO_CONTAINER)
										FROM CONTAINER_RELOKASI
									   WHERE CONTAINER_RELOKASI.NO_REQUEST = REQUEST_RELOKASI.NO_REQUEST
											 AND CONTAINER_RELOKASI.AKTIF = 'Y')
										BOX,
									 REQUEST_RELOKASI.TIPE_RELOKASI,
									 REQUEST_RELOKASI.NO_REQUEST_DELIVERY,
									 REQUEST_RELOKASI.NO_REQUEST_RECEIVING
								FROM REQUEST_RELOKASI, YARD_AREA YARD_AREA1,  YARD_AREA YARD_AREA2, V_MST_PBM V_MST_PBM
								WHERE REQUEST_RELOKASI.YARD_TUJUAN = YARD_AREA1.ID AND
								REQUEST_RELOKASI.YARD_ASAL = YARD_AREA2.ID
								AND REQUEST_RELOKASI.KD_EMKL = V_MST_PBM.KD_PBM(+)
								AND REQUEST_RELOKASI.NO_REQUEST LIKE '%$no_req%'
								ORDER BY REQUEST_RELOKASI.NO_REQUEST DESC"; 
				} else if($no_req == NULL && $to != NULL && $from != NULL ){
					
						$query_list ="SELECT REQUEST_RELOKASI.NO_REQUEST NO_REQUEST,
									 TO_CHAR (REQUEST_RELOKASI.TGL_REQUEST, 'dd Mon rrrr') TGL_REQUEST,
									 YARD_AREA1.NAMA_YARD YARD_TUJUAN,
									 YARD_AREA2.NAMA_YARD YARD_ASAL,
									 CASE
										WHEN V_MST_PBM.NM_PBM IS NULL THEN 'USTER'
										ELSE V_MST_PBM.NM_PBM
									 END
										NM_PBM,
									 (SELECT COUNT (CONTAINER_RELOKASI.NO_CONTAINER)
										FROM CONTAINER_RELOKASI
									   WHERE CONTAINER_RELOKASI.NO_REQUEST = REQUEST_RELOKASI.NO_REQUEST
											 AND CONTAINER_RELOKASI.AKTIF = 'Y')
										BOX,
									 REQUEST_RELOKASI.TIPE_RELOKASI,
									 REQUEST_RELOKASI.NO_REQUEST_DELIVERY,
									 REQUEST_RELOKASI.NO_REQUEST_RECEIVING
								FROM REQUEST_RELOKASI, YARD_AREA YARD_AREA1,  YARD_AREA YARD_AREA2, V_MST_PBM V_MST_PBM
								WHERE REQUEST_RELOKASI.YARD_TUJUAN = YARD_AREA1.ID AND
								REQUEST_RELOKASI.YARD_ASAL = YARD_AREA2.ID
								AND REQUEST_RELOKASI.KD_EMKL = V_MST_PBM.KD_PBM(+)
								AND REQUEST_RELOKASI.TGL_REQUEST BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
								ORDER BY REQUEST_RELOKASI.NO_REQUEST DESC";
						
				} else if($no_req != NULL && $to != NULL && $from != NULL ){
					$query_list ="
									 SELECT REQUEST_RELOKASI.NO_REQUEST NO_REQUEST,
									 TO_CHAR (REQUEST_RELOKASI.TGL_REQUEST, 'dd Mon rrrr') TGL_REQUEST,
									 YARD_AREA1.NAMA_YARD YARD_TUJUAN,
									 YARD_AREA2.NAMA_YARD YARD_ASAL,
									 CASE
										WHEN V_MST_PBM.NM_PBM IS NULL THEN 'USTER'
										ELSE V_MST_PBM.NM_PBM
									 END
										NM_PBM,
									 (SELECT COUNT (CONTAINER_RELOKASI.NO_CONTAINER)
										FROM CONTAINER_RELOKASI
									   WHERE CONTAINER_RELOKASI.NO_REQUEST = REQUEST_RELOKASI.NO_REQUEST
											 AND CONTAINER_RELOKASI.AKTIF = 'Y')
										BOX,
									 REQUEST_RELOKASI.TIPE_RELOKASI,
									 REQUEST_RELOKASI.NO_REQUEST_DELIVERY,
									 REQUEST_RELOKASI.NO_REQUEST_RECEIVING
								FROM REQUEST_RELOKASI, YARD_AREA YARD_AREA1,  YARD_AREA YARD_AREA2, V_MST_PBM V_MST_PBM
								WHERE REQUEST_RELOKASI.YARD_TUJUAN = YARD_AREA1.ID AND
								REQUEST_RELOKASI.YARD_ASAL = YARD_AREA2.ID
								AND REQUEST_RELOKASI.KD_EMKL = V_MST_PBM.KD_PBM(+)
								AND REQUEST_RELOKASI.NO_REQUEST LIKE '%$no_req%'
								AND REQUEST_RELOKASI.TGL_REQUEST BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
								ORDER BY REQUEST_RELOKASI.NO_REQUEST DESC
									";
				} else {
                /* $query_list     = "SELECT * FROM (SELECT  REQUEST_DELIVERY.NO_REQUEST, REQUEST_RECEIVING.NO_REQUEST RECEIVING, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY,a.NAMA_YARD ASAL, b.NAMA_YARD tujuan, request_delivery.PERALIHAN, request_delivery.DELIVERY_KE
				FROM REQUEST_DELIVERY, yard_area a, yard_area b, REQUEST_RECEIVING
				WHERE   REQUEST_DELIVERY.ID_YARD = a.ID
				AND REQUEST_RECEIVING.ID_YARD =  b.ID
				AND REQUEST_DELIVERY.NO_REQUEST = REQUEST_RECEIVING.EX_REQ_DELIVERY
				AND request_delivery.PERALIHAN = 'RELOKASI'				
				ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC)
				WHERE rownum<=20 "; */
				$query_list ="   SELECT REQUEST_RELOKASI.NO_REQUEST NO_REQUEST,
									 TO_CHAR (REQUEST_RELOKASI.TGL_REQUEST, 'dd Mon rrrr') TGL_REQUEST,
									 YARD_AREA1.NAMA_YARD YARD_TUJUAN,
									 YARD_AREA2.NAMA_YARD YARD_ASAL,
									 CASE
										WHEN V_MST_PBM.NM_PBM IS NULL THEN 'USTER'
										ELSE V_MST_PBM.NM_PBM
									 END
										NM_PBM,
									 (SELECT COUNT (CONTAINER_RELOKASI.NO_CONTAINER)
										FROM CONTAINER_RELOKASI
									   WHERE CONTAINER_RELOKASI.NO_REQUEST = REQUEST_RELOKASI.NO_REQUEST
											 AND CONTAINER_RELOKASI.AKTIF = 'Y')
										BOX,
									 REQUEST_RELOKASI.TIPE_RELOKASI,
									 REQUEST_RELOKASI.NO_REQUEST_DELIVERY,
									 REQUEST_RELOKASI.NO_REQUEST_RECEIVING
								FROM REQUEST_RELOKASI, YARD_AREA YARD_AREA1,  YARD_AREA YARD_AREA2, V_MST_PBM V_MST_PBM
								WHERE REQUEST_RELOKASI.YARD_TUJUAN = YARD_AREA1.ID AND
								REQUEST_RELOKASI.YARD_ASAL = YARD_AREA2.ID
								AND REQUEST_RELOKASI.KD_EMKL = V_MST_PBM.KD_PBM(+)
								ORDER BY REQUEST_RELOKASI.TGL_REQUEST DESC"; 
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
	
	function cek_cetak($no_req,$jenis)
	{
		$db 		= getDB("storage");
		$query_cek	= "SELECT CETAK_KARTU, NOTA FROM REQUEST_RELOKASI WHERE NO_REQUEST = '$no_req'";	
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->fetchRow();
		if($jenis == 'INTERNAL'){
			if($row_cek["CETAK_KARTU"] > 0){
				echo '<a href="'.HOME.APPID.'.print/cetak?no_req='.$no_req.'" target="_blank"><img src="images/printer.png"> Cetak Ulang </a> ';
			}
			else {
				echo '<a href="'.HOME.APPID.'.print/cetak?no_req='.$no_req.'" target="_blank"><img src="images/printer.png"> Cetak Kartu SP2 </a> ';
			}
		}
		else if ($jenis == 'EXTERNAL'){		
			if($row_cek["CETAK_KARTU"] > 0 && $row_cek["NOTA"] == 'Y')
			{
					//echo 'KARTU SP2 SUDAH DICETAK';
					echo '<a href="'.HOME.APPID.'.print/cetak?no_req='.$no_req.'" target="_blank"><img src="images/printer.png"> Cetak Ulang</a> ';		
			}
			else if($row_cek["CETAK_KARTU"] == 0 && $row_cek["NOTA"] != 'Y')
			{
				echo '<a href="'.HOME.APPID.'.print/cetak?no_req='.$no_req.'" target="_blank"> Nota Belum Lunas </a> ';
			}
			else if($row_cek["CETAK_KARTU"] == 0 && $row_cek["NOTA"] == 'Y'){
				echo '<a href="'.HOME.APPID.'.print/cetak?no_req='.$no_req.'" target="_blank"><img src="images/printer.png"> Cetak Kartu SP2 </a> ';
			}
		}
	}
?>
