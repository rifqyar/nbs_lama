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
    $no_cont     = $_GET['no_cont'];
    
	
	$query_get_container	= "SELECT CONTAINER_STRIPPING.*, MASTER_CONTAINER.SIZE_, REQUEST_STRIPPING.TGL_REQUEST FROM CONTAINER_STRIPPING INNER JOIN REQUEST_STRIPPING ON CONTAINER_STRIPPING.NO_REQUEST = REQUEST_STRIPPING.NO_REQUEST JOIN MASTER_CONTAINER ON CONTAINER_STRIPPING.NO_CONTAINER = MASTER_CONTAINER.NO_CONTAINER WHERE CONTAINER_STRIPPING.NO_REQUEST = '$no_req' AND CONTAINER_STRIPPING.NO_CONTAINER = '$no_cont'";
	$result_container		= $db->query($query_get_container);
	
	$row_cont				= $result_container->fetchRow();
	
	//insert satu satu ke kartu stripping, masing-masing 4 kali
	$tgl_request	= $row_cont["TGL_REQUEST"];
	$size			= $row_cont["SIZE_"];
	
	//---------------- cek apakah sudah pernah dicetak sebelumnya atau belum

	$query_cek	= "SELECT COUNT(1) AS CEK FROM KARTU_STRIPPING WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont'";
	$result_cek	= $db->query($query_cek);
	
	$row_cek	= $result_cek->fetchRow();
		//echo $query_cek;
	if($row_cek["CEK"] > 0)
	{
		// sudah pernah di insert
	}
	else
	{
		// belum pernah di insert, insert kartu stripping		
		if($size == 20)
		{
			$j = 4;
		}
		else if($size == 40)
		{
			$j = 8;
		}
		
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
																'$no_cont',
																TO_DATE('$tgl_request','dd-mm-yyyy') + 3,
																'Y'
																)
																";	
			$db->query($query_insert_kartu);
		}
	}

	$query_list = "SELECT * FROM KARTU_STRIPPING WHERE NO_REQUEST = '$no_req' AND NO_CONTAINER = '$no_cont' ORDER BY NO_CONTAINER, NO_KARTU ASC";
	
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
	 $name 		= $_SESSION["NAME"];
	$tl->assign("name",$name);
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
