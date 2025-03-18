<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('detail_list.htm');
        
	$db 	= getDB("storage");
	$id_group_tarif	= $_GET["no_peraturan"]; 

	if(isset($_POST["cari"]))
	{
		
	}
	else
	{
		$query_list = "SELECT CONCAT('CONTAINER ', TO_CHAR(a.SIZE_)) KATEGORI_TARIF, a.TYPE_, a.STATUS, b.TARIF,  b.ID_ISO
FROM ISO_CODE a, MASTER_TARIF b, GROUP_TARIF c
WHERE a.ID_ISO = b.ID_ISO
AND c.ID_GROUP_TARIF = b.ID_GROUP_TARIF
AND b.ID_GROUP_TARIF = '$id_group_tarif'
ORDER BY a.SIZE_, a.TYPE_, a.STATUS DESC";
                
                $kategori = "SELECT NO_PERATURAN FROM MASTER_MATERAI WHERE NO_PERATURAN = '$id_group_tarif'";
	}

        $kategori	= $db->query($kategori);
	$kategori_	= $kategori->fetchRow(); 
        
	$result_list	= $db->query($query_list);
	$row_list	= $result_list->getAll(); 
	
	$tl->assign("id_group_tarif",$id_group_tarif);
	$tl->assign("kategori",$kategori_);
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
