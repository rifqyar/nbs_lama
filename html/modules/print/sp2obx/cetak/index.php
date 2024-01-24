<?php
 $tl = xliteTemplate('print_sp2.htm');
 $id_del = $_GET['pl'];
 
 $db = getDB();
 
 $row = $db->query(
			"SELECT A.NO_REQUEST ID_REQ,
					B.NO_CONT ID_CONTAINER,
					A.CUST_NAME EMKL,
					A.VESSEL,
					A.VOY_IN VOYAGE,
					B.SIZE_CONT UKURAN,
					B.TYPE_CONT TYPE,
					B.STS_CONT STATUS,
					TO_CHAR(A.ATA,'DD-MM-YYYY') TGL_START_STACK,
					TO_CHAR(A.EXP_DATE,'DD-MM-YYYY') TGL_END_STACK,
					A.ID_YD
			 FROM BIL_DELOB_H A, BIL_DELOB_D B 
			 WHERE TRIM(A.ID_DEL) = TRIM(B.ID_DEL) 
				AND TRIM(A.ID_DEL) = TRIM('$id_del')");
 $rowd=$row->getAll();
 $tl->assign('req',$rowd);
 
 $tl->renderToScreen();
?>

