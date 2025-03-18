<?php
 $tl = xliteTemplate('home_master_vessel.htm');
 $db = getDB(); 
 $query = "SELECT * FROM MASTER_VESSEL";
 $result = $db->query($query);
 $row = $result->getAll();
 $tl->assign('nota',$row);
 $tl->renderToScreen();
?>

