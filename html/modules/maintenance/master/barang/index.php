<?php
 $tl = xliteTemplate('home_master_barang.htm');
 $db = getDB(); 
 $query = "SELECT * FROM MASTER_BARANG";
 $result = $db->query($query);
 $row = $result->getAll();
 $tl->assign('nota',$row);
 $tl->renderToScreen();
?>

