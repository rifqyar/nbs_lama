<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('blok_list.htm');
	
	$no_req	= $_GET["no_req"]; 
	$db 	= getDB("storage");

	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
		$query_list = "SELECT d.NAMA_YARD,
							  a.NAME,
							  b.KEGIATAN,
							  c.KD_SHIPPING_LINE
						FROM BLOCKING_AREA a
							JOIN BLOCKING_ALLOCATION b
								ON b.ID_BLOCKING_AREA = a.ID
							JOIN BLOCKING_BOOKING c
								ON c.ID_BLOCKING_AREA = a.ID
							JOIN YARD_AREA d
								ON d.ID = a.ID_YARD_AREA";
	}
	
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
