<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('req_list.htm');

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

	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
		$query_list		= "SELECT REQUEST_RECEIVING.*, emkl.NAMA AS NAMA_EMKL, pnmt.NAMA AS NAMA_PNMT FROM REQUEST_RECEIVING INNER JOIN MASTER_PBM emkl ON REQUEST_RECEIVING.ID_EMKL = emkl.ID JOIN MASTER_PBM pnmt ON REQUEST_RECEIVING.ID_PEMILIK = pnmt.ID WHERE REQUEST_RECEIVING.TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ORDER BY REQUEST_RECEIVING.NO_REQUEST DESC";
	}
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();

	function cek_nota($no_request)
	{
		$db		= getDB("storage");
		$query	= "SELECT * FROM NOTA_RECEIVING WHERE NO_REQUEST = '$no_request'";
		$result	= $db->query($query);
		$row	= $result->getAll();
		
		if(count($row) > 0)
		{
			$query_nota		= "SELECT * FROM NOTA_RECEIVING WHERE NO_REQUEST = '$no_request' ORDER BY NO_NOTA DESC";
			$result_nota	= $db->query($query);
			$row_nota		= $result_nota->fetchRow();
			
			$query_req		= "SELECT CETAK_KARTU FROM REQUEST_RECEIVING WHERE NO_REQUEST = '$no_request' ";
			$result_req	= $db->query($query_req);
			$row_req		= $result_req->fetchRow();
			
			if($row_nota["LUNAS"] == "YES" && $row_req["CETAK_KARTU"] == 0)
			{
				$no_req	= $row_nota["NO_REQUEST"];
				echo  '<a href="'.HOME.APPID.'.print/print_pdf?no_req='.$no_req.'" target="_blank"> CETAK KARTU </a>'; 
			}
			else if($row_req["CETAK_KARTU"] >= 0 && $row_nota["LUNAS"] == "YES")
			{
				$no_req	= $row_nota["NO_REQUEST"];
				echo  '<a href="'.HOME.APPID.'.print/print_pdf?no_req='.$no_req.'" target="_blank"> CETAK ULANG </a>';
			}
			else
			{
				//echo $row_nota["LUNAS"];
				echo " BELUM LUNAS";	
			}
		}
		else
		{
         	echo ' BELUM LUNAS ';
		}
	}
	
?>
