<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('cetak_dev.htm');

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
    
	$query_update = "UPDATE REQUEST_STUFFING SET CETAK_KARTU_SPPS = CETAK_KARTU_SPPS + 1 WHERE NO_REQUEST = '$no_req'";
	$db->query($query_update);
	    
	/*$query_list = "SELECT REQUEST_DELIVERY.NO_REQUEST, emkl.NAMA AS NAMA_EMKL, pnmt.NAMA AS NAMA_PNMT, CONTAINER_DELIVERY.STATUS, MASTER_CONTAINER.NO_CONTAINER, MASTER_CONTAINER.SIZE_, MASTER_CONTAINER.TYPE_, PLACEMENT.SLOT_, PLACEMENT.ROW_, PLACEMENT.TIER_, NOTA_DELIVERY.NO_NOTA, BLOCKING_AREA.NAME 
            FROM REQUEST_DELIVERY INNER JOIN MASTER_PBM emkl ON REQUEST_DELIVERY.ID_EMKL = emkl.ID 
            JOIN MASTER_PBM pnmt ON REQUEST_DELIVERY.ID_PEMILIK = pnmt.ID 
            JOIN NOTA_DELIVERY ON REQUEST_DELIVERY.NO_REQUEST = NOTA_DELIVERY.NO_REQUEST 
            JOIN CONTAINER_DELIVERY ON CONTAINER_DELIVERY.NO_REQUEST = REQUEST_DELIVERY.NO_REQUEST 
            JOIN MASTER_CONTAINER ON MASTER_CONTAINER.NO_CONTAINER = CONTAINER_DELIVERY.NO_CONTAINER
            JOIN PLACEMENT ON PLACEMENT.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER 
            JOIN BLOCKING_AREA ON BLOCKING_AREA.ID = PLACEMENT.ID_BLOCKING_AREA 
            WHERE REQUEST_DELIVERY.NO_REQUEST = '$no_req' 
            AND REQUEST_DELIVERY.TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE)
            ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC ";

	$result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);*/
	$no_req     = $_GET['no_req'];
    
	
	$query_get_container	= "SELECT CONTAINER_STRIPPING.*, MASTER_CONTAINER.SIZE_, TO_CHAR(REQUEST_STRIPPING.TGL_REQUEST+3,'dd/mm/yyyy') TGL_REQUEST 
								FROM CONTAINER_STRIPPING 
								INNER JOIN REQUEST_STRIPPING ON CONTAINER_STRIPPING.NO_REQUEST = REQUEST_STRIPPING.NO_REQUEST 	
								JOIN MASTER_CONTAINER ON CONTAINER_STRIPPING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER 
								WHERE CONTAINER_STRIPPING.NO_REQUEST = '$no_req'";
	$result_container		= $db->query($query_get_container);
	
	$row_cont				= $result_container->getAll();
	/*
	foreach($row_cont as $row)
	{
		//insert satu satu ke kartu stripping, masing-masing 4 kali
		$no_container	= $row["NO_CONTAINER"];
		$tgl_request	= $row["TGL_REQUEST"];
		$size			= $row["SIZE_"];
		
		//---------------- cek apakah sudah pernah dicetak sebelumnya atau belum
	
		$query_cek	= "SELECT COUNT(1) AS CEK FROM KARTU_STRIPPING WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_container'";
		$result_cek	= $db->query($query_cek);
		
		$row_cek	= $result_cek->fetchRow();
		
		if($row_cek["CEK"] > 0)
		{
			// sudah pernah di insert
		}
		else
		{
			// belum pernah di insert, insert kartu stripping		
			if($size == "20")
				$j = 4;
			else if($size == "40")
				$j = 8;
				
			for($i = 1; $i <= $j; $i++)
			{
				$query_insert_kartu	= "INSERT INTO KARTU_STRIPPING(
																	NO_KARTU,
																	NO_REQUEST,
																	NO_CONTAINER,
																	TGL_BERLAKU,
																	AKTIF
																	)
																VALUES(
																	CONCAT('$no_req','-$i'),
																	'$no_req',
																	'$no_container',
																	TO_DATE('$tgl_request','dd-mm-yyyy') + 3,
																	'Y'
																	)
																	";	
				
				$db->query($query_insert_kartu);
			}
		}
	}
	*/
	
	$name 		= $_SESSION["NAME"];
	$query_list = "SELECT b.NM_PBM AS EMKL,
              a.NO_REQUEST AS NO_REQUEST,
              c.NO_CONTAINER AS NO_CONTAINER,
              --pc.ASAL_CONT AS ASAL_CONT,
              d.SIZE_ AS SIZE_,
              d.TYPE_ AS TYPE_,
              a.NO_REQUEST_RECEIVING,
              a.NM_KAPAL, a.VOYAGE,
              i.BLOK_TPK,
              i.SLOT_TPK,
              i.ROW_TPK,
              i.TIER_TPK,
              c.TYPE_STUFFING,
              TO_DATE(c.TGL_APPROVE,'dd/mm/rrrr')+1 TGL_AWAL,
              TO_DATE(c.TGL_APPROVE,'dd/mm/rrrr')+5 TGL_AKHIR
       FROM REQUEST_STUFFING a 
                INNER JOIN V_MST_PBM b 
                    ON a.KD_CONSIGNEE = b.KD_PBM 
                JOIN CONTAINER_STUFFING c 
                    ON  a.NO_REQUEST = c.NO_REQUEST
                --JOIN PLAN_CONTAINER_STUFFING pc
                   --ON pc.NO_CONTAINER = c.NO_CONTAINER AND PC.NO_REQUEST = REPLACE(C.NO_REQUEST,'S','P') 
                 JOIN MASTER_CONTAINER d 
                    ON c.NO_CONTAINER = d.NO_CONTAINER
                    LEFT JOIN CONTAINER_RECEIVING i
                        ON c.NO_CONTAINER = i.NO_CONTAINER
                        AND i.NO_REQUEST = a.NO_REQUEST_RECEIVING
                WHERE a.NO_REQUEST = '$no_req' ";
	
	if(isset($_GET['no_cont'])){
		$no_cont = $_GET['no_cont'];
		$query_list .= "AND c.NO_CONTAINER = '$no_cont'";
	}
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
	
	$tl->assign("name",$name);
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	
	
	$tl->renderToScreen();
?>
