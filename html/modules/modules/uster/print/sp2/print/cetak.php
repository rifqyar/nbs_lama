<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('print_pontianak_sp2.html');

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

	$name = $_SESSION["NAME"]; 

	
        $no_req     = $_GET['no_req'];
        $peralihan = $_GET['peralihan'];	
		if($peralihan == 'NOTA_KIRIM'){
			$query_list = "SELECT DISTINCT TO_CHAR(container_delivery.START_PERP) START_PERP, TO_CHAR(request_delivery.TGL_REQUEST) TGL_START, 
            request_delivery.STATUS STATUS_REQ, REQUEST_DELIVERY.NO_REQUEST, emkl.NM_PBM AS NAMA_EMKL, CONTAINER_DELIVERY.STATUS, MASTER_CONTAINER.NO_CONTAINER,
            MASTER_CONTAINER.SIZE_, MASTER_CONTAINER.TYPE_, PLACEMENT.ROW_, PLACEMENT.TIER_, NOTA_DELIVERY.NO_NOTA, BLOCKING_AREA.NAME , yard_area.NAMA_YARD,
            container_delivery.via, TO_CHAR(CONTAINER_DELIVERY.TGL_DELIVERY) TGL_END, REQUEST_DELIVERY.NO_RO, CONTAINER_DELIVERY.KETERANGAN
            FROM request_delivery left join nota_delivery on REQUEST_DELIVERY.NO_REQUEST = NOTA_DELIVERY.NO_REQUEST 
            inner join container_delivery on CONTAINER_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST 
            inner join master_container on MASTER_CONTAINER.NO_CONTAINER = CONTAINER_DELIVERY.NO_CONTAINER 
            left join placement on PLACEMENT.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER 
            left join blocking_area on BLOCKING_AREA.ID = PLACEMENT.ID_BLOCKING_AREA  
            left join v_mst_pbm emkl on REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
            left join yard_area on blocking_area.ID_YARD_AREA = yard_area.ID
            WHERE REQUEST_DELIVERY.NO_REQUEST = '$no_req' 
            --and nota_delivery.TGL_NOTA = (SELECT MAX(e.TGL_NOTA) FROM NOTA_DELIVERY e WHERE e.NO_REQUEST = request_delivery.NO_REQUEST)
            --AND rownum <= 10
            ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC ";
		}
		else{
		
		//cek apakah perpanjangan atau bukan, karena berbeda ambil tanggal awal penumpukkannya 
		// $query_cek_perp="SELECT PERP_KE 
					// FROM REQUEST_DELIVERY 
					// WHERE NO_REQUEST='$no_req'";
					
		// $result_cek_perp		= $db->query($query_cek_perp);
        // $row_cek_perp   = $result_cek_perp->fetchRow();
		// $cek_perp			= $row_cek_perp['PERP_KE'];
		
			// if($cek_perp == '')
			// {	
				/* $query_list = "SELECT DISTINCT TO_CHAR(container_delivery.START_PERP) START_PERP, 
                    TO_CHAR(CONTAINER_DELIVERY.START_STACK) TGL_START, 
                    request_delivery.STATUS STATUS_REQ, REQUEST_DELIVERY.NO_REQUEST, emkl.NM_PBM AS NAMA_EMKL, CONTAINER_DELIVERY.STATUS, MASTER_CONTAINER.NO_CONTAINER,
                    MASTER_CONTAINER.SIZE_, MASTER_CONTAINER.TYPE_, PLACEMENT.ROW_, PLACEMENT.TIER_, NOTA_DELIVERY.NO_NOTA, BLOCKING_AREA.NAME , yard_area.NAMA_YARD,
                    container_delivery.via, TO_CHAR(CONTAINER_DELIVERY.TGL_DELIVERY) TGL_END, 
                    REQUEST_DELIVERY.NO_RO, CONTAINER_DELIVERY.KETERANGAN, PLACEMENT.TGL_UPDATE
                    FROM request_delivery inner join nota_delivery on REQUEST_DELIVERY.NO_REQUEST = NOTA_DELIVERY.NO_REQUEST 
                    inner join container_delivery on CONTAINER_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST 
                    inner join master_container on MASTER_CONTAINER.NO_CONTAINER = CONTAINER_DELIVERY.NO_CONTAINER 
                    left join placement on PLACEMENT.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER 
                    left join blocking_area on BLOCKING_AREA.ID = PLACEMENT.ID_BLOCKING_AREA  
                    left join v_mst_pbm emkl on REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
                    left join yard_area on blocking_area.ID_YARD_AREA = yard_area.ID
                    WHERE REQUEST_DELIVERY.NO_REQUEST = '$no_req' 
                    and nota_delivery.TGL_NOTA = (SELECT MAX(e.TGL_NOTA) FROM NOTA_DELIVERY e WHERE e.NO_REQUEST = request_delivery.NO_REQUEST)
                    and PLACEMENT.tgl_update = (SELECT MAX(F.TGL_UPDATE) FROM PLACEMENT f WHERE F.NO_CONTAINER = container_delivery.no_container)
                    --AND rownum <= 10
                    ORDER BY PLACEMENT.TGL_UPDATE DESC "; */
					
					$query_list ="SELECT DISTINCT CONTAINER_DELIVERY.ASAL_CONT,
						                TO_CHAR (container_delivery.START_PERP) START_PERP,
						                TO_CHAR (CONTAINER_DELIVERY.START_STACK) TGL_START,
						                TO_CHAR (REQUEST_DELIVERY.TGL_REQUEST) TGL_REQUEST,
						                request_delivery.STATUS STATUS_REQ,
						                REQUEST_DELIVERY.NO_REQUEST,
						                emkl.NM_PBM AS NAMA_EMKL,
						                CONTAINER_DELIVERY.STATUS,
						                MASTER_CONTAINER.NO_CONTAINER,
						                emkl2.NM_PBM NAMA_PNMT,
						                MASTER_CONTAINER.SIZE_,
						                MASTER_CONTAINER.TYPE_,
						                NOTA_DELIVERY.NO_NOTA,
						                container_delivery.via,
						                TO_CHAR (CONTAINER_DELIVERY.TGL_DELIVERY) TGL_END,
						                container_delivery.BERAT,
						                REQUEST_DELIVERY.NO_RO,
						                CONTAINER_DELIVERY.KETERANGAN,
						                CONTAINER_DELIVERY.EX_BP_ID,
						                '' NAME,
						                '' SLOT_,
						                '' ROW_,
						                '' TIER_
						  FROM request_delivery
						       INNER JOIN nota_delivery
						          ON REQUEST_DELIVERY.NO_REQUEST = NOTA_DELIVERY.NO_REQUEST
						       INNER JOIN container_delivery
						          ON CONTAINER_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST
						       INNER JOIN master_container
						          ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_DELIVERY.NO_CONTAINER
						       LEFT JOIN KAPAL_CABANG.MST_PBM emkl
						          ON REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM AND emkl.KD_CABANG = '05'
						       LEFT JOIN KAPAL_CABANG.MST_PBM emkl2 
						          ON REQUEST_DELIVERY.KD_AGEN = emkl2.KD_PBM AND emkl2.KD_CABANG = '05'
						 WHERE REQUEST_DELIVERY.NO_REQUEST = '$no_req'
						       AND nota_delivery.TGL_NOTA =
						              (SELECT MAX (e.TGL_NOTA)
						                 FROM NOTA_DELIVERY e
						                WHERE e.NO_REQUEST = request_delivery.NO_REQUEST)";
			// }
			// else
			// {
				// $query_list = "SELECT DISTINCT TO_CHAR(container_delivery.START_PERP) START_PERP, 
					// --TO_CHAR(request_delivery.TGL_REQUEST) TGL_START, 
					// TO_CHAR(container_delivery.START_PERP) TGL_START,
					// request_delivery.STATUS STATUS_REQ, REQUEST_DELIVERY.NO_REQUEST, emkl.NM_PBM AS NAMA_EMKL, CONTAINER_DELIVERY.STATUS, MASTER_CONTAINER.NO_CONTAINER,
					// MASTER_CONTAINER.SIZE_, MASTER_CONTAINER.TYPE_, PLACEMENT.ROW_, PLACEMENT.TIER_, NOTA_DELIVERY.NO_NOTA, BLOCKING_AREA.NAME , yard_area.NAMA_YARD,
					// container_delivery.via, TO_CHAR(CONTAINER_DELIVERY.TGL_DELIVERY) TGL_END, 
					// REQUEST_DELIVERY.NO_RO, CONTAINER_DELIVERY.KETERANGAN
					// FROM request_delivery inner join nota_delivery on REQUEST_DELIVERY.NO_REQUEST = NOTA_DELIVERY.NO_REQUEST 
					// inner join container_delivery on CONTAINER_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST 
					// inner join master_container on MASTER_CONTAINER.NO_CONTAINER = CONTAINER_DELIVERY.NO_CONTAINER 
					// left join placement on PLACEMENT.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER 
					// left join blocking_area on BLOCKING_AREA.ID = PLACEMENT.ID_BLOCKING_AREA  
					// left join v_mst_pbm emkl on REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
					// left join yard_area on blocking_area.ID_YARD_AREA = yard_area.ID
					// WHERE REQUEST_DELIVERY.NO_REQUEST = '$no_req' 
					// and nota_delivery.TGL_NOTA = (SELECT MAX(e.TGL_NOTA) FROM NOTA_DELIVERY e WHERE e.NO_REQUEST = request_delivery.NO_REQUEST)
					// --AND rownum <= 10
					// ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC ";
			// }
		}

	
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); 
	
	for($i=0; $i<count($row_list); $i++){
		$nocont = $row_list[$i]["NO_CONTAINER"];
		$ex_bp = $row_list[$i]["EX_BP_ID"];
		if($ex_bp == NULL){
			$sp_id = "select b.no_container, b.NAME, B.SLOT_, B.ROW_, B.TIER_ from (select placement.no_container,blocking_area.NAME, placement.SLOT_, 		placement.ROW_, placement.TIER_ from placement left join blocking_area on BLOCKING_AREA.ID = PLACEMENT.ID_BLOCKING_AREA 
			left join yard_area on blocking_area.ID_YARD_AREA = yard_area.ID order by tgl_update desc) b where  rownum <= 1 and no_container = '$nocont'";
			$rw_id = $db->query($sp_id);
			$r = $rw_id->fetchRow();
			$row_list[$i]["NAME"] = $r["NAME"];
			$row_list[$i]["SLOT_"] = $r["SLOT_"];
			$row_list[$i]["ROW_"] = $r["ROW_"];
			$row_list[$i]["TIER_"] = $r["TIER_"];		
		}
	}
	
        $query_list_ = "UPDATE REQUEST_DELIVERY set CETAK_KARTU = CETAK_KARTU+1 WHERE NO_REQUEST = '$no_req'";
	$db->query($query_list_);
	
	$tl->assign("row_list",$row_list);
	$tl->assign("name",$name);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
