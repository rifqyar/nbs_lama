<?php
 $tl = xliteTemplate('print_sp2.htm');
 
 $id_nota = $_GET['pl'];
 $db = getDB();
 $rw="SELECT A.ID_NOTA,
       A.ID_REQ,
       B.NO_CONTAINER ID_CONTAINER,
       C.TGL_DO,
       A.EMKL,
       A.VESSEL,
       B.VOYAGE,
     a.VOYAGE_IN,
       a.VOYAGE_OUT,
       B.SIZE_CONT UKURAN,
       B.TYPE_CONT TYPE,
       B.STATUS_CONT STATUS,
       C.DISCH_DATE,
     A.NO_BL,
     A.NO_DO,
       TO_CHAR(CASE WHEN A.TIPE_REQ='EXT' THEN B.PLUG_OUT_EXT ELSE B.PLUG_OUT END,'DD/MM/RRRR HH24:MI:SS') PLUG_OUT,
       (SELECT C.TGL_JAM_TIBA
          FROM rbm_h C
         WHERE TRIM (C.NO_UKK) = TRIM (A.NO_UKK))
          TGL_START_STACK,
       A.TGL_SP2 TGL_END_STACK
  FROM REQ_DELIVERY_D B, NOTA_DELIVERY_H A, REQ_DELIVERY_H C
 WHERE TRIM (A.ID_REQ) = TRIM (B.NO_REQ_DEV)
        AND A.ID_REQ = C.ID_REQ
       AND TRIM (A.ID_NOTA) ='$id_nota'";
       //echo $rw;die;
 $row = $db->query($rw);

	$rowd=$row->getAll();

	$tl->assign('req',$rowd);
	
	$db2 = getDB("dbint");
	$row = $db2->query("select OPERATOR_NAME, TO_CHAR(TO_DATE(ATA,'YYYYMMDDHH24MISS'),'DD-MM-YYYY') TGL_TIBA, TO_CHAR(TO_DATE(ETD,'YYYYMMDDHH24MISS'),'DD-MM-YYYY') TGL_BERANGKAT from m_vsb_voyage WHERE VESSEL='".$rowd[0]['VESSEL']."' AND VOYAGE_IN='".$rowd[0]['VOYAGE_IN']."' AND VOYAGE_OUT='".$rowd[0]['VOYAGE_OUT']."'");
	$rowd=$row->fetchRow();
	$tl->assign('vessel',$rowd);

 $tl->renderToScreen();
?>

