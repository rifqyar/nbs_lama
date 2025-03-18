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
		if($_POST["NO_REQ"] != NULL && $_POST["FROM"] == NULL && $_POST["TO"] == NULL)
		{
			$query_list		= "  SELECT  a.*, 
									  b.NM_PBM AS NAMA_EMKL
								FROM REQUEST_RECEIVING a,
									 V_MST_PBM b
								WHERE a.RECEIVING_DARI = 'LUAR'
							    AND a.KD_CONSIGNEE = b.KD_PBM 
								AND a.NO_REQUEST = '$no_req'
								AND a.KOREKSI = 'Y'
								AND rownum <= 20
							    ORDER BY a.TGL_REQUEST DESC";
		}
		else if ($_POST["NO_REQ"] == NULL && $_POST["FROM"] != NULL && $_POST["TO"] != NULL)
		{
			$query_list		= "  SELECT  a.*, 
								  b.NM_PBM AS NAMA_EMKL
								FROM REQUEST_RECEIVING a,
									 V_MST_PBM b
								WHERE a.RECEIVING_DARI = 'LUAR'
							    AND a.KD_CONSIGNEE = b.KD_PBM 
								AND a.KOREKSI = 'Y'
								AND rownum <= 20
								AND a.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
							    ORDER BY a.TGL_REQUEST DESC";
		} else
		{
			$query_list		= "  SELECT  a.*, 
									  b.NM_PBM AS NAMA_EMKL
								FROM REQUEST_RECEIVING a,
									 V_MST_PBM b
								WHERE a.RECEIVING_DARI = 'LUAR'
							    AND a.KD_CONSIGNEE = b.KD_PBM 
								AND a.NO_REQUEST = '$no_req'
								AND a.KOREKSI = 'Y'
								AND a.TGL_REQUEST BETWEEN TO_DATE ( '$from', 'YYYY-MM-DD ')
                                AND TO_DATE (  CONCAT('$to', '23:59:59'), 'YYYY-MM-DD HH24:MI:SS')
								AND rownum <= 20
							    ORDER BY a.TGL_REQUEST DESC";

		}
		
	}
	else
	{
		
		$query_list		= "   SELECT  a.*, 
									  b.NM_PBM AS NAMA_EMKL
								FROM REQUEST_RECEIVING a,
									 V_MST_PBM b
								WHERE a.RECEIVING_DARI = 'LUAR'
							    AND a.KD_CONSIGNEE = b.KD_PBM 
								AND a.KOREKSI = 'Y'
								AND rownum <= 20
							    ORDER BY a.TGL_REQUEST DESC";
								
								/*
								SELECT  a.*, 
									  b.NM_PBM AS NAMA_EMKL
								FROM REQUEST_RECEIVING a,
									 V_MST_PBM b
								WHERE a.TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) 
							    AND a.KD_CONSIGNEE = b.KD_PBM 
							    ORDER BY a.TGL_REQUEST DESC
								*/
		
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
		$query_cek	= "SELECT NOTA, KOREKSI FROM REQUEST_RECEIVING WHERE NO_REQUEST = '$no_req'";	
		$result_cek	= $db->query($query_cek);
		$row_cek 	= $result_cek->getAll();
		
		if(count($row_cek) > 0)
		{
			$cetak		= $row_cek[0]["NOTA"];
			$no_nota	= $row_cek[0]["KOREKSI"];
			
			//'print/print_pdf?no_req='.$no_req.'&no_nota='.$no_nota.
			// <a href="{$HOME}{$APPID}/view?no_req={$rows.NO_REQUEST}"> LIHAT </a>
			if($row_cek[0]["NOTA"] == 'Y')
			{
				echo 'NOTA SUDAH CETAK';
				//echo '<a href="'.HOME.APPID.'/cetak_nota?no_nota='.$no_nota.'&n='.$cetak.'" target="_blank"> CETAK ULANG </a> ';		
			}
			else
			{
				echo '<a href="'.HOME.APPID.'/view?no_req='.$no_req.'" target="_blank"> EDIT </a> ';		
			}
		}
	}
?>
