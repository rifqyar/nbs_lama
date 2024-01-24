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
	
	$cari	= $_POST["CARI"];
	$from	= $_POST["FROM"]; 
	$to		= $_POST["TO"];
	$no_req	= $_POST["NO_REQ"];
	
	if(isset($_POST["CARI"]) ) 
	{
		if((isset($_POST["NO_REQ"])) && ($from == NULL) && ($to == NULL))
		{
			$query_list		= " SELECT a.*, 
                                  b.NAMA AS NAMA_EMKL
                           FROM storage.REQUEST_RECEIVING a,
                                storage.MASTER_PBM b
                           WHERE a.ID_EMKL = b.ID
                           AND a.NO_REQUEST = '$no_req'
                           ORDER BY a.TGL_REQUEST DESC";
		}
		else if((isset($_POST["FROM"]))&& (isset($_POST["TO"])) && ($no_req == NULL))
		{
			$query_list		= " SELECT a.*, 
                                  b.NAMA AS NAMA_EMKL
                           FROM storage.REQUEST_RECEIVING a,
                                storage.MASTER_PBM b
                           WHERE a.ID_EMKL = b.ID
                           AND a.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                                 AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
                           ORDER BY a.TGL_REQUEST DESC";
		}
		
	}
	else
	{
		
		$query_list		= "   SELECT  a.*, 
									  b.NAMA AS NAMA_EMKL
								FROM REQUEST_RECEIVING a,
									 MASTER_PBM b
								WHERE a.TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) 
								AND a.RECEIVING_DARI = 'LUAR'
							    AND a.ID_EMKL = b.ID 
							    ORDER BY a.TGL_REQUEST DESC";
		
	}
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
	//debug($row_list);
	//debug($row_trf);
	
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
				echo '<a href="'.HOME.APPID.'/view?no_nota='.$no_nota.'" target="_blank"> EDIT </a> ';		
			}
		}
		else
		{
			echo '<a href="'.HOME.APPID.'/view?no_req='.$no_req.'" target="_blank"> EDIT </a> ';
		}
	}
?>
