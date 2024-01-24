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

	$from	= $_POST["FROM"]; 
	$to		= $_POST["TO"]; 
	debug($from);
	
	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
		$query_list		= " SELECT a.*, 
                                  b.NAMA AS NAMA_EMKL, 
                                  b.NAMA AS NAMA_PNMT 
                           FROM storage.REQUEST_RECEIVING a,
                                    storage.MASTER_PBM b
                           WHERE a.ID_EMKL = b.ID
                           AND a.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'DD-Mon-YY ')
                                                 AND TO_DATE (  CONCAT('$to', '23:59:59'), 'DD-Mon-YY HH24:MI:SS')
                           ORDER BY a.NO_REQUEST DESC";
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
			// <a href="{$HOME}{$APPID}/view?no_req={$rows.NO_REQUEST}"> LIHAT </a>
			if($row_cek[0]["CETAK_NOTA"] > 0)
			{
				echo 'NOTA SUDAH CETAK';
				//echo '<a href="'.HOME.APPID.'/cetak_nota?no_nota='.$no_nota.'&n='.$cetak.'" target="_blank"> CETAK ULANG </a> ';		
			}
			else
			{
				echo '<a href="'.HOME.APPID.'/view?no_nota='.$no_nota.'" target="_blank"> LIHAT </a> ';		
			}
		}
		else
		{
			echo '<a href="'.HOME.APPID.'/view?no_req='.$no_req.'" target="_blank">LIHAT </a> ';
		}
	}
?>
