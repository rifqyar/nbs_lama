<?php
	//header('Location: '.HOME .'static/error.htm');		
	
	$db         = getDB('storage');
	$tl 	    =  xliteTemplate('nota_list.htm');
	
    $id_nota    = $_GET["id_nota"]; 

	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
		$komp_nota = "SELECT ID_NOTA, ID_KOMP_NOTA,KOMPONEN_NOTA, STATUS FROM MASTER_KOMP_NOTA WHERE ID_NOTA = '$id_nota' ORDER BY KOMPONEN_NOTA DESC";
        $nota = "SELECT NAMA_NOTA, KATEGORI_NOTA FROM MASTER_NOTA WHERE ID_NOTA = '$id_nota'";
	}
	
	$result_list	= $db->query($komp_nota);
	$row_list		= $result_list->getAll(); 
        
    $nota_      	= $db->query($nota);
	$notaa          = $nota_->fetchRow(); 
		
        
    $tl->assign("id_nota",$id_nota);
	$tl->assign("nota",$notaa);
	$tl->assign("komp_nota",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
