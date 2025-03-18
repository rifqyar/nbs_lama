<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('komp_nota.htm');
	
    $id_nota    = $_GET["id_nota"]; 
	$db         = getDB("storage");

	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
		$komp_nota = "SELECT KOMPONEN_NOTA, STATUS FROM MASTER_KOMP_NOTA WHERE ID_NOTA = '$id_nota' ORDER BY KOMPONEN_NOTA DESC";
              //  $nota = "SELECT NAMA_NOTA, KATEGORI_NOTA FROM MASTER_NOTA WHERE ID_NOTA = '$id_nota'";
	}
	
	$result_list	= $db->query($komp_nota);
	$row_list		= $result_list->getAll(); 
        
//        $nota_      	= $db->query($nota);
//	$notaa          = $nota_->fetchRow(); 
		
	//$tl->assign("nota",$notaa);
	$tl->assign("komp_nota",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
