<?php
 $id_req = $_GET["id_req"];
 $id_user = $_SESSION["ID_USER"]; 
 $db = getDB();
 
 $cek_count = "SELECT MAX(COUNTER) AS MAX_COUNTER FROM GLC_HISTORY WHERE ID_REQ='$id_req' AND STATUS LIKE '%PERMINTAAN%'";
 $result1 = $db->query($cek_count);
 $row4 = $result1->fetchRow();			
 $max_count = $row4['MAX_COUNTER'];
   
 $count = $max_count+1;
 $urutan = $count-1;
 $update_req = "UBAH PERMINTAAN ".$urutan;
 $insert_history = "INSERT INTO GLC_HISTORY (ID_REQ,STATUS,TGL_UPDATE,USER_UPDATE,COUNTER) VALUES ('$id_req','$update_req',SYSDATE,'$id_user','$count')";
 $db->query($insert_history);

 $tl = xliteTemplate('home_req_glc.htm');
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->renderToScreen();
?>