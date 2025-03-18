<?php
 $tl = xliteTemplate('home.htm');
 $db=  getDB();
 $query="SELECT * from REQ_DELIVERY_H";
 $result_q=$db->query($query);
 $row=$result_q->getAll();
 $tl->assign("table",$row);
 $tl->renderToScreen();
?>