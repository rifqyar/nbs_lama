<?php
 $tl = xliteTemplate('home_master_kegiatan.htm');
 $db = getDB(); 
 $query = "SELECT KEGIATAN, STATUS, ALAT, ID_ACT, TARIF FROM MASTER_KEGIATAN ORDER BY ID_ACT DESC";
 $result = $db->query($query);
 $row = $result->getAll();
 $tl->assign('keg',$row);
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->renderToScreen();
?>

