<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('relokasi_list.htm');

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
	$id_yard		= $_SESSION["IDYARD_STORAGE"];

	$query_list		= "SELECT MASTER_CONTAINER.*, NVL(BLOCKING_AREA.NAME,'BELUM PLACEMENT') AS BLOCK, NVL(BLOCKING_AREA.ID,'0') AS BLOCK_ID, NVL(PLACEMENT.SLOT_,'0') AS SLOT_, NVL(PLACEMENT.ROW_,'0') AS ROW_, NVL(PLACEMENT.TIER_,'0') AS TIER_ FROM PLACEMENT RIGHT OUTER JOIN MASTER_CONTAINER ON MASTER_CONTAINER.NO_CONTAINER = PLACEMENT.NO_CONTAINER FULL OUTER JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID WHERE BLOCKING_AREA.ID_YARD_AREA = '$id_yard' AND MASTER_CONTAINER.LOCATION NOT LIKE 'GATO'";
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
