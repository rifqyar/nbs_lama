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
    $name 		= $_SESSION["NAME"];
	
	$query_get_container	= "SELECT CONTAINER_STUFFING.*, MASTER_CONTAINER.SIZE_, TO_CHAR(REQUEST_STUFFING.TGL_REQUEST+3,'dd/mm/yyyy') TGL_REQUEST FROM CONTAINER_STUFFING INNER JOIN REQUEST_STUFFING ON CONTAINER_STUFFING.NO_REQUEST = REQUEST_STUFFING.NO_REQUEST JOIN MASTER_CONTAINER ON CONTAINER_STUFFING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER WHERE CONTAINER_STUFFING.NO_REQUEST = '$no_req'";
	$result_container		= $db->query($query_get_container);
	
	$row_cont				= $result_container->getAll();
	foreach($row_cont as $row)
	{
		//insert satu satu ke kartu stripping, masing-masing 4 kali
		$no_container	= $row["NO_CONTAINER"];
		$tgl_request	= $row["TGL_REQUEST"];
		$size			= $row["SIZE_"];
		
		//---------------- cek apakah sudah pernah dicetak sebelumnya atau belum
	
		$query_cek	= "SELECT COUNT(1) AS CEK FROM KARTU_STUFFING WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_container'";
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
				$query_insert_kartu	= "INSERT INTO KARTU_STUFFING(
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
																	TO_DATE('$tgl_request','dd/mm/yyyy') +3,
																	'Y'
																	)
																	";	
				
				$db->query($query_insert_kartu);
			}
		}
	}
	
	/* $query_list = "    SELECT b.NM_PBM AS EMKL,
              a.NO_REQUEST AS NO_REQUEST,
              c.NO_CONTAINER AS NO_CONTAINER,
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
                 JOIN MASTER_CONTAINER d 
                    ON c.NO_CONTAINER = d.NO_CONTAINER
                    LEFT JOIN container_receiving i
                        ON c.NO_CONTAINER = i.NO_CONTAINER
                        AND i.NO_REQUEST = a.NO_REQUEST_RECEIVING
                WHERE a.NO_REQUEST = '$no_req'"; */
	$query_list = "SELECT * FROM KARTU_STUFFING WHERE NO_REQUEST = '$no_req' ORDER BY NO_CONTAINER, NO_KARTU ASC";
	
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
	
	
	$tl->assign("name",$name);
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
