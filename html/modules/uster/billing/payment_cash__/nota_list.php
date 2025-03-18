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
	$id_yard = $_SESSION["IDYARD_STORAGE"];
        
        $no_nota = $_POST["no_nota"];
		$no_req	 = $_POST["no_req"]; 
		$keg	 = $_POST["kegiatan"]; 
		$cari	 = $_POST["cari"]; 

	$db = getDB("storage");

	IF (isset($cari)) {
		if ($keg == 'RECEIVING'){
			$query_list     = "SELECT * FROM nota_receiving WHERE NO_NOTA = '$no_nota' AND NO_REQUEST = '$no_req'";
		} else if ($keg == 'STUFFING'){
			$query_list     = "SELECT * FROM nota_stuffing WHERE NO_NOTA = '$no_nota' AND NO_REQUEST = '$no_req'";
		} else if ($keg == 'STRIPPING'){
			$query_list     = "SELECT * FROM nota_stripping WHERE NO_NOTA = '$no_nota' AND NO_REQUEST = '$no_req'";
		} else if ($keg == 'DELIVERY'){
			$query_list     = "SELECT * FROM nota_delivery WHERE NO_NOTA = '$no_nota' AND NO_REQUEST = '$no_req'";
		}
	} ELSE {
		$query_list     = "SELECT * FROM nota_delivery WHERE NO_NOTA = '0' AND NO_REQUEST = '0'";
	}
	
	
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); 
		
	
	$tl->assign("row_list",$row_list);
	$tl->assign("keg",$keg);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	
	function cek_nota($no_req, $keg)
	{
		$db 		= getDB("storage");
		if ($keg == 'RECEIVING'){
			$query_list     = "SELECT * FROM nota_receiving WHERE NO_NOTA = '$no_nota' AND NO_REQUEST = '$no_req'";
		} else if ($keg == 'STUFFING'){
			$query_list     = "SELECT * FROM nota_stuffing WHERE NO_NOTA = '$no_nota' AND NO_REQUEST = '$no_req'";
		} else if ($keg == 'STRIPPING'){
			$query_list     = "SELECT * FROM nota_stripping WHERE NO_NOTA = '$no_nota' AND NO_REQUEST = '$no_req'";
		} else if ($keg == 'DELIVERY'){
			$query_list     = "SELECT * FROM nota_delivery WHERE NO_NOTA = '$no_nota' AND NO_REQUEST = '$no_req'";
		}
                //echo $query_cek;
		$result_cek	= $db->query($query_list);
		$row_cek 	= $result_cek->getAll();
		
		if(count($row_cek) > 0)
		{
			$cetak		= $row_cek[0]["CETAK_NOTA"];
			$no_nota	= $row_cek[0]["NO_NOTA"];
			
			if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'NO'))
			{
                                echo '<a href="'.HOME.APPID.'/set_lunas?no_nota='.$no_nota.'&keg='.$keg.'"><style:"font-color=red"> SET LUNAS</style> </a> ';	
			}
			else if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'YES'))
			{
                                 echo '<font color="green"><i>SUDAH LUNAS</i></font>';
			}
                        else if (($row_cek[0]["CETAK_NOTA"] > 0) && ($row_cek[0]["LUNAS"] == 'PIUTANG'))
			{
				echo '<a href="'.HOME.APPID.'.print/print_nota_lunas?no_nota='.$no_nota.'&no_req='.$no_req.'" target="_blank"> CETAK ULANG </a> <br>';
                                 echo '<font color="red"><i>PIUTANG</i></font>';
			}
		}
		else
		{
			echo '<a href="'.HOME.APPID.'/print_nota?no_req='.$no_req.'&n=999" target="_blank"> Preview Nota </a> ';
		}
	}
?>
