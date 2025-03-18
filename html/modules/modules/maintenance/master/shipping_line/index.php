<?php
 $tl = xliteTemplate('home_master_pbm.htm');
 $db = getDB(); 
 $query = "SELECT * FROM tb_master_ship_line";
 $result = $db->query($query);
 $row = $result->getAll();
 $tl->assign('nota',$row);
 $tl->renderToScreen();
?>

