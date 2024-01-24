<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('cetak.htm');

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
					
					$query_list = "SELECT DISTINCT CONTAINER_DELIVERY.ASAL_CONT, TO_CHAR(container_delivery.START_PERP) START_PERP, 
								TO_CHAR(CONTAINER_DELIVERY.START_STACK) TGL_START, 
								request_delivery.STATUS STATUS_REQ, REQUEST_DELIVERY.NO_REQUEST, emkl.NM_PBM AS NAMA_EMKL, CONTAINER_DELIVERY.STATUS, MASTER_CONTAINER.NO_CONTAINER,
								MASTER_CONTAINER.SIZE_, MASTER_CONTAINER.TYPE_, NOTA_DELIVERY.NO_NOTA,
								container_delivery.via, TO_CHAR(CONTAINER_DELIVERY.TGL_DELIVERY) TGL_END, 
								REQUEST_DELIVERY.NO_RO, CONTAINER_DELIVERY.KETERANGAN, CONTAINER_DELIVERY.EX_BP_ID
								FROM request_delivery inner join nota_delivery on REQUEST_DELIVERY.NO_REQUEST = NOTA_DELIVERY.NO_REQUEST 
								inner join container_delivery on CONTAINER_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST 
								inner join master_container on MASTER_CONTAINER.NO_CONTAINER = CONTAINER_DELIVERY.NO_CONTAINER                   
								left join v_mst_pbm emkl on REQUEST_DELIVERY.KD_EMKL = emkl.KD_PBM
								WHERE REQUEST_DELIVERY.NO_REQUEST = '$no_req' 
								and nota_delivery.TGL_NOTA = (SELECT MAX(e.TGL_NOTA) FROM NOTA_DELIVERY e WHERE e.NO_REQUEST = request_delivery.NO_REQUEST)";
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
	
        $query_list_ = "UPDATE REQUEST_DELIVERY set CETAK_KARTU = CETAK_KARTU+1 WHERE NO_REQUEST = '$no_req'";
	$db->query($query_list_);
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
