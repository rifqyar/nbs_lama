<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('cont_list.htm');
	
//	/$no_req	= $_GET["no_req"]; 
	$db         = getDB('storage');

	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
		$komp_nota = "SELECT a.ID_NOTA,a.KODE_NOTA, a.KATEGORI_NOTA JENIS_NOTA
                                FROM MASTER_NOTA a";
                
                $nota = "SELECT a.ID_NOTA, a.NAMA_NOTA FROM MASTER_NOTA a";
	}
	
	$result_list	= $db->query($komp_nota);
	$row_list	= $result_list->getAll(); 
        
        $nota_      	= $db->query($nota);
	$notaa           = $nota_->fetchRow(); 
		
	$tl->assign("nota",$notaa);
	$tl->assign("komp_nota",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
