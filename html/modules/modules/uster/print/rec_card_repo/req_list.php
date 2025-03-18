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
			$query_list		= "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST, 
							TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST, 
							TO_CHAR(REQUEST_DELIVERY.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, 
							emkl.NM_PBM as NAMA_EMKL ,request_delivery.VESSEL as NAMA_VESSEL, 
							request_delivery.VOYAGE, yard_area.NAMA_YARD, request_delivery.NO_REQ_ICT,
							REQUEST_DELIVERY.JN_REPO
							FROM REQUEST_DELIVERY, NOTA_DELIVERY, KAPAL_CABANG.MST_PBM emkl, yard_area
							WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
							AND emkl.KD_CABANG = '05'
							AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
							AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
							AND request_delivery.DELIVERY_KE = 'TPK'
							AND request_delivery.NO_REQUEST = '$no_req'
							--AND NOTA_DELIVERY.STATUS <> 'BATAL' 
							AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING') 
							ORDER BY REQUEST_DELIVERY.TGL_REQUEST DESC";
		}
		else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && ($no_req == NULL))
		{
			$query_list		= " SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST, 
							TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST, 
							TO_CHAR(REQUEST_DELIVERY.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, 
							emkl.NM_PBM as NAMA_EMKL ,request_delivery.VESSEL as NAMA_VESSEL, 
							request_delivery.VOYAGE, yard_area.NAMA_YARD, request_delivery.NO_REQ_ICT,
							REQUEST_DELIVERY.JN_REPO
							FROM REQUEST_DELIVERY, NOTA_DELIVERY, KAPAL_CABANG.MST_PBM emkl, yard_area
							WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
							AND emkl.KD_CABANG = '05'
							AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
							AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
							AND request_delivery.DELIVERY_KE = 'TPK'
							AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING') 
							AND request_delivery.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                AND TO_DATE (CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
							ORDER BY REQUEST_DELIVERY.TGL_REQUEST DESC";
								
		} else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && (isset($_POST["NO_REQ"])))
		{
			$query_list		= " SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST, 
							TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST, 
							TO_CHAR(REQUEST_DELIVERY.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, 
							emkl.NM_PBM as NAMA_EMKL ,request_delivery.VESSEL as NAMA_VESSEL, 
							request_delivery.VOYAGE, yard_area.NAMA_YARD, request_delivery.NO_REQ_ICT,
							REQUEST_DELIVERY.JN_REPO
							FROM REQUEST_DELIVERY, NOTA_DELIVERY, KAPAL_CABANG.MST_PBM emkl, yard_area
							WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
							AND emkl.KD_CABANG = '05'
							AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
							AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
							AND request_delivery.DELIVERY_KE = 'TPK'
							AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING') 
							AND request_delivery.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                AND TO_DATE (CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
							AND request_delivery.NO_REQUEST = '$no_req'
							ORDER BY REQUEST_DELIVERY.TGL_REQUEST DESC
							   ";
		}
	}
	else
	{
		$query_list		= "SELECT * FROM (
						  SELECT NVL (NOTA_DELIVERY.LUNAS, 0) LUNAS,
						         REQUEST_DELIVERY.NO_REQUEST,
						         TO_CHAR (REQUEST_DELIVERY.TGL_REQUEST, 'dd Mon yyyy') TGL_REQUEST,
						         TO_CHAR (REQUEST_DELIVERY.TGL_REQUEST_DELIVERY, 'dd/mm/yyyy')
						            TGL_REQUEST_DELIVERY,
						         emkl.NM_PBM AS NAMA_EMKL,
						         request_delivery.VESSEL AS NAMA_VESSEL,
						         request_delivery.VOYAGE,
						         request_delivery.NO_REQ_ICT,
						         REQUEST_DELIVERY.JN_REPO
						    FROM REQUEST_DELIVERY,
						         NOTA_DELIVERY,
						         KAPAL_CABANG.MST_PBM emkl
						   WHERE     REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
						         AND emkl.KD_CABANG = '05'
						         AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
						         AND request_delivery.DELIVERY_KE = 'TPK'
						         AND request_delivery.PERALIHAN NOT IN
						                ('RELOKASI', 'STUFFING', 'STRIPPING')
						ORDER BY REQUEST_DELIVERY.TGL_REQUEST DESC)
						WHERE ROWNUM <= 100
						";
		
	}
	
/* 	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll();  */
		
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

	function cek_nota($no_request)
	{
		$db			= getDB("storage");
		$query		= "SELECT * FROM NOTA_DELIVERY WHERE NO_REQUEST = '$no_request'";
		$result		= $db->query($query);
		$row		= $result->getAll();
		
		
		$query_req1	= "SELECT * FROM REQUEST_DELIVERY WHERE NO_REQUEST = '$no_request' ";
		$result_req1	= $db->query($query_req1);
		$row_req1		= $result_req1->fetchRow();
		
		if($row_req1["DELIVERY_KE"] == "TPK")
		{
			if(count($row) > 0)
			{
				$query_nota		= "SELECT * FROM NOTA_DELIVERY WHERE NO_REQUEST = '$no_request' ORDER BY NO_NOTA DESC";
				$result_nota	= $db->query($query);
				$row_nota		= $result_nota->fetchRow();
				
				$query_req		= "SELECT * FROM REQUEST_DELIVERY WHERE NO_REQUEST = '$no_request' ";
				$result_req	= $db->query($query_req);
				$row_req		= $result_req->fetchRow();
				
				if($row_nota["LUNAS"] == "YES" && $row_req["CETAK_KARTU"] == 0)
				{
					$no_req	= $row_nota["NO_REQUEST"];
					echo  '<a href="'.HOME.APPID.'.print/print_receiving_card?no_req='.$no_req.'" target="_blank"> CETAK KARTU </a> | ';
					$petik = "'";
					echo  '<a onclick="print_by_container('.$petik.''.$no_req.''.$petik.')"> Cetak Per Container </a>';
				}
				else if($row_req["CETAK_KARTU"] > 0 && $row_nota["LUNAS"] == "YES")
				{
					$no_req	= $row_nota["NO_REQUEST"];
					echo  '<a href="'.HOME.APPID.'.print/print_receiving_card?no_req='.$no_req.'" target="_blank"> CETAK ULANG </a> |';
					$petik = "'";
					echo  '<a onclick="print_by_container('.$petik.''.$no_req.''.$petik.')"> Cetak Per Container </a>';
				}
				else
				{
					//echo $row_nota["LUNAS"];
					echo " BELUM LUNAS";	
				}
			}
			else if ($row_req1["STATUS"] == 'AUTO_REPO'){
				$no_bmu  = $db->query("SELECT BIAYA FROM REQUEST_BATAL_MUAT WHERE NO_REQ_BARU = '$no_req'");
				$rbmu = $no_bmu->fetchRow();
				if($rbmu["BIAYA"] == "Y"){
					if($row_req1["NOTA"] == 'Y'){
						$no_req	= $row_req1["NO_REQUEST"];
						echo  '<a href="'.HOME.APPID.'.print/print_receiving_card?no_req='.$no_req.'" target="_blank"> CETAK KARTU </a> | ';
						$petik = "'";
						echo  '<a onclick="print_by_container('.$petik.''.$no_req.''.$petik.')"> Cetak Per Container </a>';
					}
					else {
						echo ' BELUM BAYAR BATAL MUAT ';
					}
				}
				else{
					$no_req	= $row_req1["NO_REQUEST"];
					echo  '<a href="'.HOME.APPID.'.print/print_receiving_card?no_req='.$no_req.'" target="_blank"> CETAK KARTU </a> | ';
					$petik = "'";
					echo  '<a onclick="print_by_container('.$petik.''.$no_req.''.$petik.')"> Cetak Per Container </a>';
				}
					
			}
			else
			{
				echo ' BELUM LUNAS ';
			}
			
		}
	}
?>
 