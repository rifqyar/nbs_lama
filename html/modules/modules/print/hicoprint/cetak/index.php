<?php
 $tl = xliteTemplate('print_behandle.htm');
 //PRINT_R('COBA');DIE;
 $id_nota = $_GET['pl'];
 $db = getDB();
 $row = $db->query("SELECT A.ID_REQUEST,B.ID_NOTA,C.NO_CONTAINER,D.UKURAN,D.TYPE,D.STATUS,A.VESSEL,A.VOYAGE,A.EMKL,A.SHIPPING_LINE,A.NOMOR_INSTRUKSI,
					A.TGL_REQUEST,E.NAME,B.INVOICE_DATE	
                    FROM REQ_HICOSCAN A, NOTA_HICOSCAN_H B, REQ_HICOSCAN_D C, MASTER_BARANG D, TB_USER E
					WHERE A.ID_REQUEST=B.ID_REQUEST AND B.ID_NOTA='$id_nota' AND C.ID_REQUEST=A.ID_REQUEST AND C.ID_BARANG=D.KODE_BARANG AND E.ID=B.USER_ID_INVOICE ORDER BY C.ID");
	$rowd=$row->getAll();
	$tl->assign('req',$rowd);
 
 $tl->renderToScreen();
?>

