<?php
 $tl = xliteTemplate('home_master_pbm.htm');
 $db = getDB(); 
 $query = "SELECT * FROM master_pbm";
 $result = $db->query($query);
 $row = $result->getAll();
 $tl->assign('nota',$row);
 $tl->renderToScreen();
?>

