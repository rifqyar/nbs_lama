<?php
 $tl = xliteTemplate('home.htm');
 $db = getDB(); 
 $query = "SELECT * FROM RECIPIENT_EMAIL";
 $result = $db->query($query);
 $row = $result->getAll();
 $tl->assign('group',$row);
 $tl->renderToScreen();
?>

