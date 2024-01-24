<?php
 $tl = xliteTemplate('home_master_user.htm');
 $db = getDB(); 
 $query = "SELECT * FROM TB_USER A,TB_GROUP B WHERE A.ID_GROUP = B.ID_GROUP";
 //$query = "SELECT * FROM TB_USER";
 $result = $db->query($query);
 $row = $result->getAll();
 $tl->assign('nota',$row);
 $tl->renderToScreen();
?>

