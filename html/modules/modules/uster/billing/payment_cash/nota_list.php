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
		$no_faktur	 = $_POST["no_faktur"]; 
		$keg	 = $_POST["kegiatan"]; 
		$cari	 = $_POST["cari"]; 

	$db = getDB("storage");
	$db2 = getDB("ora");


	IF (isset($cari)) {
		if ($keg == 'RECEIVING'){
			$query_list     = "SELECT * FROM nota_receiving WHERE NO_NOTA = '$no_nota' AND NO_REQUEST = '$no_req'";
		} else if ($keg == 'STUFFING'){
			$query_list     = "SELECT * FROM nota_stuffing WHERE NO_NOTA = '$no_nota' AND NO_REQUEST = '$no_req'";
		} else if ($keg == 'STRIPPING'){
			$query_list     = "SELECT * FROM nota_stripping WHERE NO_NOTA = '$no_nota' AND NO_REQUEST = '$no_req'";
		} else if ($keg == 'DELIVERY'){
			$query_list     = "SELECT * FROM nota_delivery WHERE NO_NOTA = '$no_nota' AND NO_REQUEST = '$no_req'";
		} else if ($keg == 'RELOKASI'){
			$query_list     = "SELECT * FROM nota_relokasi WHERE NO_NOTA = '$no_nota' AND NO_REQUEST = '$no_req'";
		} else if ($keg == 'BATALMUAT'){
			$query_list     = "SELECT * FROM nota_batal_muat WHERE NO_NOTA = '$no_nota' AND NO_REQUEST = '$no_req'";
		} else if ($keg == 'RELOKASI_MTY'){
			$query_list     = "SELECT * FROM nota_relokasi_mty WHERE NO_NOTA = '$no_nota' AND NO_REQUEST = '$no_req'";
		}else if ($keg == 'PNKN_DELIVERY'){
			$query_list     = "SELECT * FROM nota_pnkn_del WHERE NO_NOTA = '$no_nota' AND NO_REQUEST = '$no_req'";
		}else if ($keg == 'PNKN_STUFFING'){
			$query_list     = "SELECT * FROM nota_pnkn_stuf WHERE NO_NOTA = '$no_nota' AND NO_REQUEST = '$no_req'";
		}
	} ELSE {
		$query_list     = "SELECT * FROM nota_delivery WHERE NO_NOTA = '0' AND NO_REQUEST = '0'";
	}
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); 
	
	$qcek = "SELECT count(*) jum FROM ITPK_NOTA_HEADER WHERE TRX_NUMBER ='$no_faktur'";
	$rcek = $db->query($qcek)->fetchRow();
	if ($rcek[JUM] > 0) {
		$query_nota 	= "SELECT bank_account_name receipt_account,
						       bank_id,
						       CASE WHEN no = 3 THEN 'CASH' ELSE 'BANK' END receipt_method
						  FROM kapal_cabang.mst_bank_simkeu where kd_cabang = 5";
		$result			= $db2->query($query_nota);
		$nota			= $result->getAll(); 
	}
	else {
		$query_nota 	= "select replace(RECEIPT_ACCOUNT, ' ', '_') ID, RECEIPT_ACCOUNT,BANK_ID 
								from apps.xpi2_ar_receipt_method_v@simkeu_link 
								WHERE kode_cabang = 'PTK' AND RECEIPT_ACCOUNT IN ('PTK BNI IDR 76051004','PTK MANDIRI IDR 1460091007257')";
		$result			= $db2->query($query_nota);
		$nota			= $result->getAll(); 
	}
	
						
	//echo $satu;die;
	//$i = 1;
	foreach ($nota as $row){
		$dua .= "<option value='".$row['BANK_ID']."'>".$row['RECEIPT_ACCOUNT']."</option>";
		//$i++;
	}
	$satu			= "<select name='receipt_account' id='receipt_account'>
		                <option value='0'>--- PILIH ---</option>".$dua."</select>";
	
	//echo $nota_;die;
	
	$tl->assign("nota",$satu);
	$tl->assign("row_list",$row_list);
	$tl->assign("keg",$keg);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
