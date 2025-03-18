<?php
 $tl = xliteTemplate('home_master_tarif.htm');
 $db = getDB(); 
 $query = "SELECT * FROM MASTER_TARIF_CONT A JOIN MASTER_BARANG B ON A.ID_CONT=B.KODE_BARANG";
 $result = $db->query($query);
 $row = $result->getAll();
 $tl->assign('tarif',$row);
 $tl->renderToScreen();
?>

