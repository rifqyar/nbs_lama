<?php
 $tl = xliteTemplate('print_bay.htm');
 
 $id_vs = $_GET['id'];
 $db = getDB();
 $row = $db->query("SELECT ID_VS, BAY FROM STW_BAY_AREA WHERE ID_VS = '$id_vs' ORDER BY BAY ASC")->fetchRow();
 $tl->assign('req',$row);
 $tl->renderToScreen();
 
 ?>

