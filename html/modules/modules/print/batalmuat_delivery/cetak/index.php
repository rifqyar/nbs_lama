<?php
 $tl = xliteTemplate('print_sp2.htm');
 //PRINT_R('COBA');DIE;
 $id_nota = $_GET['pl'];
 $db = getDB();
 
 $row = $db->query("SELECT NO_NOTA ID_NOTA,
       A.ID_BATALMUAT ID_REQ,
       B.NO_CONTAINER ID_CONTAINER,
       A.EMKL,
       A.VESSEL,
       A.VOYAGE,
       '' VOYAGE_IN,
       '' VOYAGE_OUT,
       '' UKURAN,
       JNS_CONT,
       B.STATUS STATUS,
       '' DISCH_DATE,
       '' NO_BL,
       '' NO_DO,
      '' PLUG_OUT,
       (SELECT C.TGL_JAM_TIBA
          FROM rbm_h C
         WHERE TRIM (C.NO_UKK) = TRIM (A.NO_UKK))
          TGL_START_STACK,
       A.TGL_BERANGKAT2 TGL_END_STACK
  FROM TB_BATALMUAT_D B, NOTA_BATALMUAT_H A, TB_BATALMUAT_H C
 WHERE TRIM (A.ID_BATALMUAT) = TRIM (B.ID_BATALMUAT)
        AND A.ID_BATALMUAT = C.ID_BATALMUAT
       AND TRIM (A.ID_BATALMUAT) ='$id_nota'");
	$rowd=$row->getAll();
	$tl->assign('req',$rowd);
	
	$db2 = getDB("dbint");
	/*echo("select OPERATOR_NAME, TO_CHAR(TO_DATE(ATA,'YYYYMMDDHH24MISS'),'DD-MM-YYYY') TGL_TIBA, TO_CHAR(TO_DATE(ETD,'YYYYMMDDHH24MISS'),'DD-MM-YYYY') TGL_BERANGKAT from m_vsb_voyage WHERE VESSEL='".$rowd[0]['VESSEL']."' AND (VOYAGE_IN='".$rowd[0]['VOYAGE']."' or VOYAGE_OUT='".$rowd[0]['VOYAGE']."')");
	die();*/
	$row = $db2->query("select OPERATOR_NAME, TO_CHAR(TO_DATE(ATA,'YYYYMMDDHH24MISS'),'DD-MM-YYYY') TGL_TIBA, TO_CHAR(TO_DATE(ETD,'YYYYMMDDHH24MISS'),'DD-MM-YYYY') TGL_BERANGKAT from m_vsb_voyage WHERE VESSEL='".$rowd[0]['VESSEL']."' AND (VOYAGE_IN='".$rowd[0]['VOYAGE']."' or VOYAGE_OUT='".$rowd[0]['VOYAGE']."')");
	$rowd=$row->fetchRow();
	$tl->assign('vessel',$rowd);
 
 $tl->renderToScreen();
?>

