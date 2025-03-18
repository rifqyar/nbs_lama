<?php
	//echo 'adad';die;
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

		/* $r_q = $db->query("SELECT NO_REQUEST_DELIVERY FROM REQUEST_RELOKASI WHERE NO_REQUEST = '$no_req1'");
		$row_q = $r_q->fetchRow();
		$no_req = $row_q["NO_REQUEST_DELIVERY"]; */
		//echo $no_req;die;
        
	$query_list = " /* Formatted on 12/1/2012 10:15:14 AM (QP5 v5.163.1008.3004) */
					  SELECT CONTAINER_RELOKASI.NO_CONTAINER,
							 MASTER_CONTAINER.SIZE_,
							 MASTER_CONTAINER.TYPE_,
							 CONTAINER_RELOKASI.STATUS,
							 HISTORY_PLACEMENT.ROW_,
							 HISTORY_PLACEMENT.SLOT_,
							 HISTORY_PLACEMENT.TIER_,
							 BLOCKING_AREA.NAME BLOK_,
							 REQUEST_RELOKASI.NO_REQUEST NO_REQUEST,
							 V_MST_PBM.NM_PBM,
							 TO_CHAR (REQUEST_RELOKASI.TGL_REQUEST, 'dd/mm/yy') TGL_REQUEST,
							 YARD_AREA1.NAMA_YARD YARD_TUJUAN,
							 YARD_AREA2.NAMA_YARD YARD_ASAL,
							 REQUEST_RELOKASI.NO_RO,
							 (SELECT COUNT (CONTAINER_RELOKASI.NO_CONTAINER)
								FROM CONTAINER_RELOKASI
							   WHERE CONTAINER_RELOKASI.NO_REQUEST = REQUEST_RELOKASI.NO_REQUEST
									 AND CONTAINER_RELOKASI.AKTIF = 'Y')
								BOX,
							 REQUEST_RELOKASI.TIPE_RELOKASI,
							 REQUEST_RELOKASI.NO_REQUEST_DELIVERY,
							 REQUEST_RELOKASI.NO_REQUEST_RECEIVING,
							 NOTA_RELOKASI.NO_NOTA
						FROM REQUEST_RELOKASI
							 JOIN CONTAINER_RELOKASI
								ON REQUEST_RELOKASI.NO_REQUEST = CONTAINER_RELOKASI.NO_REQUEST
							 LEFT JOIN NOTA_RELOKASI
								ON REQUEST_RELOKASI.NO_REQUEST = NOTA_RELOKASI.NO_REQUEST
							 LEFT JOIN V_MST_PBM
								ON REQUEST_RELOKASI.KD_EMKL = V_MST_PBM.KD_PBM
							 INNER JOIN YARD_AREA YARD_AREA1
								ON REQUEST_RELOKASI.YARD_TUJUAN = YARD_AREA1.ID
							 INNER JOIN YARD_AREA YARD_AREA2
								ON REQUEST_RELOKASI.YARD_ASAL = YARD_AREA2.ID
							 INNER JOIN MASTER_CONTAINER
								ON CONTAINER_RELOKASI.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER
							 LEFT JOIN HISTORY_PLACEMENT
								ON CONTAINER_RELOKASI.NO_CONTAINER = HISTORY_PLACEMENT.NO_CONTAINER
							 LEFT JOIN BLOCKING_AREA
								ON HISTORY_PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID
					   WHERE REQUEST_RELOKASI.NO_REQUEST = '$no_req'
							 AND HISTORY_PLACEMENT.TGL_UPDATE =
									(SELECT MAX (a.TGL_UPDATE)
									   FROM HISTORY_PLACEMENT a, CONTAINER_RELOKASI b
									  WHERE a.NO_CONTAINER = b.NO_CONTAINER
											AND b.NO_REQUEST = '$no_req')
					ORDER BY REQUEST_RELOKASI.NO_REQUEST DESC";

	$result_list	= $db->query($query_list);
      //  echo $query_list;
	$row_list	= $result_list->getAll();
       
     $no_req_delivery = $row_list["NO_REQUEST_DELIVERY"];
     $no_req_receiving = $row_list["NO_REQUEST_RECEIVING"];
        
        $query_list_ = "UPDATE REQUEST_DELIVERY set CETAK_KARTU = CETAK_KARTU+1 WHERE NO_REQUEST = '$no_req_delivery'";
		$db->query($query_list_); 
		$query_list_rec = "UPDATE REQUEST_RECEIVING set CETAK_KARTU = CETAK_KARTU+1 WHERE NO_REQUEST = '$no_req_receiving'";
		$db->query($query_list_rec);
	$query_list_r = "UPDATE REQUEST_RELOKASI set CETAK_KARTU = CETAK_KARTU+1 WHERE NO_REQUEST = '$no_req'";
	$db->query($query_list_r);
	
	$tl->assign("row_list",$row_list);
	$tl->assign("no_req",$no_req);
        $tl->assign("yard_asal",$yard_asal1);
        $tl->assign("yard_tujuan",$yard_tujuan1);
        
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
