<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('req_list.htm');

        $cari	= $_POST["cari"];
		$no_req	= $_POST["no_req"]; 
		$from   = $_POST["from"];
		$to     = $_POST["to"];
        $id_yard    = 	$_SESSION["IDYARD_STORAGE"];
	
        $db = getDB("storage");

            	if(isset($_POST["cari"]) ) 
            	{   
                                        if((isset($_POST["no_req"])) && ($from == NULL) && ($to == NULL))
                                        {
                                                $query_list = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST,  TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST,  TO_CHAR(REQUEST_DELIVERY.TGL_REQUEST_DELIVERY,'dd Mon yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM as NAMA_EMKL ,request_delivery.VESSEL as NAMA_VESSEL, request_delivery.VOYAGE, yard_area.NAMA_YARD, request_delivery.NO_REQ_ICT
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, V_MST_PBM emkl, yard_area
                        WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID 
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        AND REQUEST_DELIVERY.NO_REQUEST LIKE '%$no_req%'
                        AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING')     
					    AND request_delivery.DELIVERY_KE = 'TPK'
                        ORDER BY REQUEST_DELIVERY.TGL_REQUEST DESC";

                                        }
                                        else if(($no_req == NULL) && (isset($_POST["from"])) && (isset($_POST["to"])))
                                        {
                                                $query_list = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST,  TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST,  TO_CHAR(REQUEST_DELIVERY.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM as NAMA_EMKL ,request_delivery.VESSEL as NAMA_VESSEL, request_delivery.VOYAGE, yard_area.NAMA_YARD, request_delivery.NO_REQ_ICT
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, V_MST_PBM emkl, yard_area
                        WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        AND request_delivery.TGL_REQUEST BETWEEN TO_DATE('$from','yy-mm-dd') AND TO_DATE('$to','yy-mm-dd')
                       AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING') 
					   AND request_delivery.DELIVERY_KE = 'TPK'
                        ORDER BY REQUEST_DELIVERY.TGL_REQUEST DESC";

                                        } else if((isset($_POST["no_req"]))&& (isset($_POST["from"])) && (isset($_POST["to"])))
                                        {
                                                $query_list = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST,  TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST,  TO_CHAR(REQUEST_DELIVERY.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM as NAMA_EMKL ,request_delivery.VESSEL as NAMA_VESSEL, request_delivery.VOYAGE, yard_area.NAMA_YARD, request_delivery.NO_REQ_ICT
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, V_MST_PBM emkl, yard_area
                        WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        AND request_delivery.NO_REQUEST = '$no_req'
                        AND request_delivery.TGL_REQUEST BETWEEN TO_DATE('$from','yy-mm-dd') AND TO_DATE('$to','yy-mm-dd')
                       AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING') 
					   AND request_delivery.DELIVERY_KE = 'TPK'
                        ORDER BY REQUEST_DELIVERY.TGL_REQUEST DESC";
                        } 
                } else {
                                        $query_list     = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd Mon yyyy') TGL_REQUEST, TO_CHAR(REQUEST_DELIVERY.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM as NAMA_EMKL ,request_delivery.VESSEL as NAMA_VESSEL, request_delivery.VOYAGE, yard_area.NAMA_YARD, request_delivery.NO_REQ_ICT
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, V_MST_PBM emkl, yard_area
                        WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
						AND emkl.KD_CABANG = '05'
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
						AND request_delivery.DELIVERY_KE = 'TPK'
                        AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING') 
                        ORDER BY REQUEST_DELIVERY.TGL_REQUEST DESC";
                                } 
                             
    /*       } else {
                    if(isset($_POST["cari"]) ) 
            	{   
                                                if((isset($_POST["no_req"])) && ($from == NULL) && ($to == NULL))
                                        {
                                                $query_list = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST, TO_DATE( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM as NAMA_EMKL ,request_delivery.VESSEL as NAMA_VESSEL, request_delivery.VOYAGE, yard_area.NAMA_YARD

                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, MASTER_PBM emkl, VESSEL, VOYAGE, yard_area
                        WHERE  REQUEST_DELIVERY.ID_EMKL = emkl.ID
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID 
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        AND REQUEST_DELIVERY.NO_REQUEST LIKE '%$no_req%'
                        AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING') 
                        AND request_delivery.ID_YARD = '$id_yard'       
                        ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";

                                        }
                                        else if(($no_req == NULL) && (isset($_POST["from"])) && (isset($_POST["to"])))
                                        {
                                                $query_list = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST, TO_DATE( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY,  emkl.NM_PBM as NAMA_EMKL ,request_delivery.VESSEL as NAMA_VESSEL, request_delivery.VOYAGE, yard_area.NAMA_YARD

                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, MASTER_PBM emkl, VESSEL, VOYAGE, yard_area
                        WHERE  REQUEST_DELIVERY.ID_EMKL = emkl.ID
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        AND request_delivery.TGL_REQUEST_DELIVERY BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
                        AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING') 
                        AND request_delivery.ID_YARD = '$id_yard'  
                        ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";

                                        } else if((isset($_POST["no_req"]))&& (isset($_POST["from"])) && (isset($_POST["to"])))
                                        {
                                                $query_list = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST, TO_DATE( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_DATE(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM as NAMA_EMKL ,request_delivery.VESSEL as NAMA_VESSEL, request_delivery.VOYAGE, yard_area.NAMA_YARD

                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, MASTER_PBM emkl, VESSEL, VOYAGE, yard_area
                        WHERE  REQUEST_DELIVERY.ID_EMKL = emkl.ID
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        AND request_delivery.NO_REQUEST = '$no_req'
                        AND request_delivery.TGL_REQUEST_DELIVERY BETWEEN TO_DATE('$from','yyyy/mm/dd') AND TO_DATE('$to','yyyy/mm/dd')
                       AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING') 
                        AND request_delivery.ID_YARD = '$id_yard'  
                        ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";
                                        } 
        } else {
                $query_list     = "SELECT NVL(NOTA_DELIVERY.LUNAS, 0) LUNAS, REQUEST_DELIVERY.NO_REQUEST, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, TO_CHAR(TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, emkl.NM_PBM as NAMA_EMKL ,request_delivery.VESSEL as NAMA_VESSEL, request_delivery.VOYAGE, yard_area.NAMA_YARD
                        FROM REQUEST_DELIVERY, NOTA_DELIVERY, V_MST_PBM emkl, yard_area
                        WHERE  REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                        AND REQUEST_DELIVERY.ID_YARD = YARD_AREA.ID
                        AND NOTA_DELIVERY.NO_REQUEST(+) = REQUEST_DELIVERY.NO_REQUEST
                        AND request_delivery.PERALIHAN NOT IN ('RELOKASI','STUFFING','STRIPPING') 
						AND request_delivery.ID_YARD = '$id_yard' 
                        AND rownum<=10 
                        ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";
        }}  
	*/
	/* $result_list	= $db->query($query_list);
	$row_list       = $result_list->getAll(); */ 
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