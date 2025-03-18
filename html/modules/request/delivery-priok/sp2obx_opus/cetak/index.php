<?php
 $tl = xliteTemplate('print_sp2.htm');
 //PRINT_R('COBA');DIE;
 $id_nota = $_GET['pl'];
 $db = getDB();
 $row = $db->query("SELECT A.ID_NOTA,A.ID_REQ,B.NO_CONTAINER ID_CONTAINER,A.EMKL,A.VESSEL,A.VOYAGE_IN,B.SIZE_CONT UKURAN,B.TYPE_CONT TYPE,B.STATUS_CONT STATUS,(select C.TGL_JAM_TIBA from rbm_h C WHERE TRIM (C.NO_UKK)=TRIM(A.NO_UKK)) TGL_START_STACK,A.TGL_SP2 TGL_END_STACK FROM REQ_DELIVERY_D B, NOTA_DELIVERY_H A WHERE TRIM(A.ID_REQ)=TRIM(B.NO_REQ_DEV) AND TRIM(A.ID_NOTA)='$id_nota'");
	$rowd=$row->getAll();
	$tl->assign('req',$rowd);
 
 $tl->renderToScreen();
?>

