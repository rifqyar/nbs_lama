<?php
 $tl = xliteTemplate('upload.htm');
 $db=  getDB();
 
 $id_vessel 	= $_GET['id_vessel'];
 
 $query			= "SELECT a.ID_VS, b.NAMA_VESSEL , a.VOYAGE, a.ETA, a.ETD, a.RTA, a.RTD, a.STATUS, a.UKK FROM VESSEL_SCHEDULE a, MASTER_VESSEL b 
				   WHERE a.ID_VES = b.KODE_KAPAL AND a.ID_VS = '$id_vessel'";
 $result_q		= $db->query($query);
 $row			= $result_q->fetchRow();
 
 
 $tl->assign("vessel",$row);
 $tl->assign("id_vessel",$id_vessel);
 $tl->renderToScreen();
?>