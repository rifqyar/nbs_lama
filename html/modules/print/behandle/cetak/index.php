<?php
 $tl = xliteTemplate('print_behandle.htm');
 //PRINT_R('COBA');DIE;
 $id_req = $_GET['pl'];
 $db = getDB();
 //INVOICE DATE, NAME, ID_NOTA
 $row = $db->query("SELECT A.ID_REQ,C.NO_CONTAINER,D.UKURAN,D.TYPE,D.STATUS,A.VESSEL,A.VOYAGE_IN, A.VOYAGE_OUT,A.EMKL,A.SHIPPING_LINE,A.NOMOR_INSTRUKSI,
					A.TGL_REQUEST	
                    FROM REQ_BEHANDLE_H A, REQ_BEHANDLE_D C, MASTER_BARANG D
					WHERE A.ID_REQ='$id_req' AND C.ID_REQ=A.ID_REQ AND C.ID_BARANG=D.KODE_BARANG ORDER BY C.ID");
	$rowd=$row->getAll();
	$tl->assign('req',$rowd);
 
 $tl->renderToScreen();
?>

