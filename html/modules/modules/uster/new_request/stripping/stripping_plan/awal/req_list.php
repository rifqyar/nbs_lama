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

	$from		= $_POST["FROM"]; 
	$to			= $_POST["TO"];
	$no_req		= $_POST["NO_REQ"];
	$id_yard	= $_SESSION["IDYARD_STORAGE"]; 	
	
	if(isset($_POST["CARI"]))
	{
		$query_list		= " SELECT a.*, 
                                  b.NAMA AS NAMA_PEMILIK 
                           FROM storage.REQUEST_STRIPPING a,
                                storage.MASTER_PBM b
                           WHERE a.ID_PEMILIK = b.ID
                           AND a.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                                 AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
                           ORDER BY a.NO_REQUEST DESC";
						   
	
		
	}
	
	else if(isset($_POST["NO_REQ"]))
	{
		$query_list		= " SELECT a.*, 
                                  b.NAMA AS NAMA_PEMILIK
                           FROM storage.REQUEST_STRIPPING a,
                                storage.MASTER_PBM b
                           WHERE a.ID_PEMILIK = b.ID
                           AND a.NO_REQUEST = '$no_req'
                           ORDER BY a.NO_REQUEST DESC";
	}
	/*
	else if(isset($_POST["NO_REQ"] && $_POST["CARI"]))
	{
	}*/
	else
	{
		$query_list		= "SELECT REQUEST_STRIPPING.*, NVL(REQUEST_STRIPPING.NO_REQUEST_RECEIVING,'PERPANJANGAN') AS REQ_REC, emkl.NAMA AS NAMA_EMKL FROM REQUEST_STRIPPING INNER JOIN MASTER_PBM emkl ON REQUEST_STRIPPING.ID_PEMILIK = emkl.ID WHERE REQUEST_STRIPPING.TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) AND REQUEST_STRIPPING.ID_YARD = '$id_yard' ORDER BY REQUEST_STRIPPING.NO_REQUEST DESC";
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
		$query_cek	= "SELECT * FROM NOTA_STRIPPING WHERE NO_REQUEST = '$no_req'";	
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
				echo '<a href="'.HOME.APPID.'/view?no_nota='.$no_nota.'" target="_blank"><img src="images/cont_edit.gif" border="0" />&nbsp; LIHAT </a> ';		
			}
		}
		else
		{
			echo '<a href="'.HOME.APPID.'/view?no_req='.$no_req.'" target="_blank"><img src="images/cont_edit.gif" border="0" />&nbsp;LIHAT </a> ';
		}
	}
?>
