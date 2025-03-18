<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('nota_list.htm');

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
	$id_yard = $_SESSION["IDYARD_STORAGE"];
        
        $cari	= $_POST["cari"];
		$no_req	= $_POST["no_req"]; 
		$from   = $_POST["from"];
		$to     = $_POST["to"];
        $no_nota  = $_POST["no_nota"];
        $id_yard  = $_SESSION["IDYARD_STORAGE"];
	
        
		$db = getDB("storage");
		if(isset($_POST["cari"]) ) 
		{   
			if($_POST["no_nota"] != NULL && $_POST["no_req"] == NULL && $_POST["from"] == NULL && $_POST["to"] == NULL){
				$query_list     = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, NVL(NOTA_DELIVERY.NO_NOTA, '-') NO_NOTA, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL NAMA_VESSEL, yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, v_mst_pbm emkl, yard_area, container_delivery
                        WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                        AND REQUEST_DELIVERY.NO_REQUEST = container_delivery.NO_REQUEST
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        and request_delivery.PERALIHAN <> 'RELOKASI'
                        and request_delivery.STATUS = 'NEW'
						and request_delivery.DELIVERY_KE = 'TPK'
						and request_delivery.PERALIHAN = 'T'
						AND NOTA_DELIVERY.NO_NOTA = '$no_nota'
                     GROUP BY  NVL(NOTA_DELIVERY.LUNAS, 0), NVL(NOTA_DELIVERY.NO_NOTA, '-'),REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD
                     ORDER BY TGL_REQUEST DESC";
			}
			else if($_POST["no_req"] != NULL && $_POST["from"] == NULL && $_POST["to"] == NULL){
				$query_list     = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, NVL(NOTA_DELIVERY.NO_NOTA, '-') NO_NOTA, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL NAMA_VESSEL, yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, v_mst_pbm emkl, yard_area, container_delivery
                        WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                        AND REQUEST_DELIVERY.NO_REQUEST = container_delivery.NO_REQUEST
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        and request_delivery.PERALIHAN <> 'RELOKASI'
                        and request_delivery.STATUS = 'NEW'
						and request_delivery.DELIVERY_KE = 'TPK'
						and request_delivery.PERALIHAN = 'T'
						AND request_delivery.NO_REQUEST = '$no_req'
                     GROUP BY  NVL(NOTA_DELIVERY.LUNAS, 0), NVL(NOTA_DELIVERY.NO_NOTA, '-'),REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD
                     ORDER BY TGL_REQUEST DESC";
			}
			else if($_POST["no_req"] == NULL && $_POST["from"] != NULL && $_POST["to"] != NULL){
				$query_list     = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, NVL(NOTA_DELIVERY.NO_NOTA, '-') NO_NOTA, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL NAMA_VESSEL, yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, v_mst_pbm emkl, yard_area, container_delivery
                        WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                        AND REQUEST_DELIVERY.NO_REQUEST = container_delivery.NO_REQUEST
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        and request_delivery.PERALIHAN <> 'RELOKASI'
                        and request_delivery.STATUS = 'NEW'
						and request_delivery.DELIVERY_KE = 'TPK'
						and request_delivery.PERALIHAN = 'T'
						AND request_delivery.TGL_REQUEST BETWEEN TO_DATE('$from', 'yy-mm-dd') AND TO_DATE('$to', 'yy-mm-dd')
                     GROUP BY  NVL(NOTA_DELIVERY.LUNAS, 0), NVL(NOTA_DELIVERY.NO_NOTA, '-'),REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD
                     ORDER BY TGL_REQUEST DESC";
			}
			else if($_POST["no_req"] != NULL && $_POST["from"] != NULL && $_POST["to"] != NULL){
				$query_list     = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, NVL(NOTA_DELIVERY.NO_NOTA, '-') NO_NOTA, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL NAMA_VESSEL, yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, v_mst_pbm emkl, yard_area, container_delivery
                        WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                        AND REQUEST_DELIVERY.NO_REQUEST = container_delivery.NO_REQUEST
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        and request_delivery.PERALIHAN <> 'RELOKASI'
                        and request_delivery.STATUS = 'NEW'
						and request_delivery.DELIVERY_KE = 'TPK'
						and request_delivery.PERALIHAN = 'T'
						AND request_delivery.TGL_REQUEST BETWEEN TO_DATE('$from', 'yy-mm-dd') AND TO_DATE('$to', 'yy-mm-dd')
						AND request_delivery.NO_REQUEST = '$no_req'
                     GROUP BY  NVL(NOTA_DELIVERY.LUNAS, 0), NVL(NOTA_DELIVERY.NO_NOTA, '-'),REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD
                     ORDER BY TGL_REQUEST DESC";
			}
			else{
				$query_list     = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, NVL(NOTA_DELIVERY.NO_NOTA, '-') NO_NOTA, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL NAMA_VESSEL, yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, v_mst_pbm emkl, yard_area, container_delivery
                        WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                        AND REQUEST_DELIVERY.NO_REQUEST = container_delivery.NO_REQUEST
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        and request_delivery.PERALIHAN <> 'RELOKASI'
                        and request_delivery.STATUS = 'NEW'
						and request_delivery.DELIVERY_KE = 'TPK'
						and request_delivery.PERALIHAN = 'T'
                     GROUP BY  NVL(NOTA_DELIVERY.LUNAS, 0), NVL(NOTA_DELIVERY.NO_NOTA, '-'),REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD
                     ORDER BY TGL_REQUEST DESC";
			}
			
								
		}
		else{
		$query_list     = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, NVL(NOTA_DELIVERY.NO_NOTA, '-') NO_NOTA, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM AS NAMA_EMKL, request_delivery.VOYAGE, request_delivery.VESSEL NAMA_VESSEL, yard_area.NAMA_YARD, COUNT(container_delivery.NO_CONTAINER) JML_CONT
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, v_mst_pbm emkl, yard_area, container_delivery
                        WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                        AND REQUEST_DELIVERY.NO_REQUEST = container_delivery.NO_REQUEST
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        and request_delivery.PERALIHAN <> 'RELOKASI'
                        and request_delivery.STATUS = 'NEW'
						and request_delivery.DELIVERY_KE = 'TPK'
						and request_delivery.PERALIHAN = 'T'
                     GROUP BY  NVL(NOTA_DELIVERY.LUNAS, 0), NVL(NOTA_DELIVERY.NO_NOTA, '-'),REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy'), TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy'), emkl.NM_PBM, request_delivery.VOYAGE, request_delivery.VESSEL, yard_area.NAMA_YARD
                     ORDER BY TGL_REQUEST DESC";
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
	
	function cek_nota($no_req)
	{
		$db 		= getDB("storage");
		$query_cek	= "SELECT * FROM NOTA_DELIVERY WHERE NO_REQUEST = '$no_req'";
                //echo $query_cek;
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->getAll();
		
		if(count($row_cek) > 0)
		{
			$cetak		= $row_cek[0]["CETAK_NOTA"];
			$no_nota	= $row_cek[0]["NO_NOTA"];
			
			if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'NO'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank""><b><i> CETAK ULANG</i></b> </a><br> ';	
                            //    echo '<a href="'.HOME.APPID.'/set_lunas?no_nota='.$no_nota.'"><style:"font-color=red"> Set LUNAS</style> </a> ';	
			}
			else if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'YES'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank"><b><i> CETAK ULANG </i></b></a> <br>';
                             //    echo '<font color="red"><i>SDH LUNAS</i></font>';
			}
                        else if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'PIUTANG'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank"><b><i> CETAK ULANG </i></b> </a> <br>';
                             //    echo '<font color="red"><i>PIUTANG</i></font>';
			}
		}
		else
		{
			echo '<a href="'.HOME.APPID.'/print_nota?no_req='.$no_req.'&n=999" target="_blank"><b><i> Preview Nota </i></b></a> ';
		}
	}
?>
