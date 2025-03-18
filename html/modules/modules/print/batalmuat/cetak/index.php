<?php
 $tl = xliteTemplate('print_sp2.htm');
 //PRINT_R('COBA');DIE;
 $id_nota = $_GET['pl'];
 $db = getDB();
 $row = $db->query("SELECT B.ID_NOTA,A.ID_REQ,C.ID_CONTAINER,A.EMKL,A.VESSEL,A.VOYAGE,D.UKURAN,D.TYPE,D.STATUS,A.TGL_START_STACK,A.TGL_END_STACK
                    FROM TB_REQ_DELIVERY_H A, TB_NOTA_DELIVERY_H B, TB_REQ_DELIVERY_CONT C, MASTER_BARANG D
					WHERE A.ID_REQ=B.ID_REQ AND B.ID_NOTA='$id_nota' AND C.ID_REQ=A.ID_REQ AND C.ID_BARANG=D.KODE_BARANG");
	$rowd=$row->getAll();
	$tl->assign('req',$rowd);
 
 $tl->renderToScreen();
?>

