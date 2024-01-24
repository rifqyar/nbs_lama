<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('nota_list.htm');

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
		$query_list		= "SELECT REQUEST_RECEIVING.*, emkl.NAMA AS NAMA_EMKL, pnmt.NAMA AS NAMA_PNMT FROM REQUEST_RECEIVING INNER JOIN MASTER_PBM emkl ON REQUEST_RECEIVING.ID_EMKL = emkl.ID JOIN MASTER_PBM pnmt ON REQUEST_RECEIVING.ID_PEMILIK = pnmt.ID WHERE REQUEST_RECEIVING.PERALIHAN IS NULL ORDER BY REQUEST_RECEIVING.NO_REQUEST DESC";
	}
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	
	function cek_nota($no_req)
	{
		$db 		= getDB("storage");
		$query_cek	= "SELECT * FROM NOTA_RECEIVING WHERE NO_REQUEST = '$no_req'";	
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->getAll();
		
		if(count($row_cek) > 0)
		{
			$cetak		= $row_cek[0]["CETAK_NOTA"];
			$no_nota	= $row_cek[0]["NO_NOTA"];
			
			//'print/print_pdf?no_req='.$no_req.'&no_nota='.$no_nota.
			
			if(($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'NO'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_pdf?no_req='.$no_req.'&no_nota='.$no_nota.'&n='.$cetak.'" target="_blank"> CETAK ULANG </a> <br>';
				 echo '<a href="'.HOME.APPID.'/set_lunas?no_nota='.$no_nota.'" ><style:"font-color=red"> Set LUNAS</style> </a> ';
				
			}
			else if(($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'YES'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_pdf?no_req='.$no_req.'&no_nota='.$no_nota.'&n='.$cetak.'" target="_blank"> CETAK ULANG </a> <br>';
				echo '<style:"font-color=red">SDH LUNAS</style>';		
			}
		}
		else
		{
			echo '<a href="'.HOME.APPID.'/cetak_nota?no_req='.$no_req.'&n=999" > CETAK </a> ';
			//header('Location:'.HOME.APPID);	
		}
	}
	
	
	/*
	function cek_nota($no_req)
	{
		$db 		= getDB("storage");
		$query_cek	= "SELECT * FROM NOTA_RECEIVING WHERE NO_REQUEST = '$no_req'";	
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->getAll();
		
		if(count($row_cek) > 0)
		{
			$cetak	= $row_cek[0]["CETAK_NOTA"];
			
			if($row_cek[0]["CETAK_NOTA"] > 0)
			{
				echo '<a href="'.HOME.APPID.'/cetak_nota?no_nota='.$no_nota.'&n='.$cetak.'" target="_blank"> CETAK ULANG </a> ';					
				//echo '<a href="'.HOME.APPID.'/cetak_nota?no_nota='.$no_nota.'&n='.$cetak.'" target="_blank" onClick="window.location.reload()"> CETAK ULANG </a> ';
			}
			else
			{
				//echo '<a href="'.HOME.APPID.'/cetak_nota?no_req='.$no_req.'&n=0" target="_blank"> CETAK </a> ';	
				echo '<a href="'.HOME.APPID.'/cetak_nota?no_nota='.$no_nota.'&n=0" target="_blank" onClick="window.location.reload()"> CETAK </a> ';
			}
		}
		else
		{
			//echo '<a href="'.HOME.APPID.'/example_001" target="_blank"> CETAK </a> ';
			echo '<a href="'.HOME.APPID.'/cetak_nota?no_req='.$no_req.'&n=999" target="_blank" onClick="window.location.reload()"> CETAK </a> ';
		}
	} */
?>
