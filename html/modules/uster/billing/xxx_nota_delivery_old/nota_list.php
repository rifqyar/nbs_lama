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
		$query_list  = "SELECT REQUEST_DELIVERY.*, emkl.NAMA AS NAMA_EMKL, pnmt.NAMA AS NAMA_PNMT FROM REQUEST_DELIVERY INNER JOIN MASTER_PBM emkl ON REQUEST_DELIVERY.ID_EMKL = emkl.ID JOIN MASTER_PBM pnmt ON REQUEST_DELIVERY.ID_PEMILIK = pnmt.ID ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";
	}
	
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	
	function cek_nota($no_req)
	{
		$db 		= getDB("storage");
		$query_cek	= "SELECT * FROM NOTA_DELIVERY WHERE NO_REQUEST = '$no_req'";
                //echo $query_cek;
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->getAll();
		
		if(count($row_cek) > 0)
		{
			$cetak		= $row_cek[0]["CETAK_NOTA"];
			$no_nota	= $row_cek[0]["NO_NOTA"];
			
			if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'NO'))
			{
				echo '<a href="'.HOME.'print_nota/print_nota_lunas?no_nota='.$no_nota.'" target="_blank""> CETAK ULANG </a><br> ';	
                                echo '<a href="'.HOME.APPID.'/set_lunas?no_nota='.$no_nota.'" target="_blank""><style:"font-color=red"> Set LUNAS</style> </a> ';	
			}
			else if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'YES'))
			{
				echo '<a href="'.HOME.'print_nota/print_nota_lunas?no_nota='.$no_nota.'" target="_blank""> CETAK ULANG </a> <br>';
                                 echo '<style:"font-color=red">SDH LUNAS</style>';
			}
		}
		else
		{
			echo '<a href="'.HOME.APPID.'/cetak_nota?no_req='.$no_req.'&n=999" target="_blank"> CETAK </a> ';
		}
	}
?>
