<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('edit.htm');
	
//	/$no_req	= $_GET["no_req"]; 
	$db         = getDB('storage');
	
	$id_nota    = $_GET["id_nota"];
	$komp_nota 	= $_GET["komp_nota"];

	$komp_nota = "SELECT a.ID_KOMP_NOTA,a.STATUS, a.KOMPONEN_NOTA, a.ID_NOTA
                                FROM MASTER_KOMP_NOTA a
								WHERE a.ID_NOTA = '$id_nota' AND a.ID_KOMP_NOTA = '$komp_nota'";

	$result_list	= $db->query($komp_nota);
	$row_list		= $result_list->fetchRow(); 
	
	$nama_nota = "SELECT NAMA_NOTA FROM MASTER_NOTA WHERE ID_NOTA = '$id_nota'";

	$result_list2	= $db->query($nama_nota);
	$row_list2		= $result_list2->fetchRow(); 
	
	$nama_nota 		= $row_list2['NAMA_NOTA'];
        
	$tl->assign("nama_nota",$nama_nota);
	$tl->assign("row",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
