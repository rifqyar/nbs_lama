<?php

$tl = xliteTemplate('edit.htm');
$id = $_GET['no_req'];
$db = getDB();
$query = "SELECT A.ID_BATALMUAT, 
				  B.NAMA, 
				  A.VESSEL, 
				  A.VOYAGE, 
				  TO_CHAR(A.TGL_BERANGKAT,'dd/mm/yyyy') TGL_BERANGKAT, 
				  TO_CHAR(A.TGL_KELUAR,'dd/mm/yyyy') TGL_KELUAR, 
				  A.JENIS,
				  A.STAT_GATE,
				  A.KODE_PBM
                    FROM TB_BATALMUAT_H A, MASTER_PBM B
                    WHERE A.KODE_PBM = B.KODE_PBM
                    AND A.ID_BATALMUAT = '$id'";
$result_q=$db->query($query);
$row=$result_q->fetchRow();

$query1="SELECT A.*, B.UKURAN, B.TYPE_ FROM TB_BATALMUAT_D A, MASTER_CONTAINER B WHERE A.NO_CONTAINER = B.NO_CONTAINER AND A.ID_BATALMUAT = '$id'";
$result_l=$db->query($query1);
$row1=$result_l->fetchRow();

$tl->assign("detail",$row);
$tl->assign("list",$row1);
$tl->assign("no",$id);
$tl->renderToScreen();
?>