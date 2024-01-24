<?php
 $u = $_GET['id_vessel'];
 $tl = xliteTemplate('add_hatch.htm');
 
  $db = getDB();
 $query = "SELECT NO_UKK, ID_VS AS KD_KAPAL, NM_KAPAL, VOYAGE_IN, VOYAGE_OUT, NM_PEMILIK, TO_CHAR(TGL_JAM_TIBA, 'dd Mon rrrr hh24:ii:ss') TGL_JAM_TIBA, TO_CHAR(TGL_JAM_BERANGKAT, 'dd Mon rrrr hh24:ii:ss') TGL_JAM_BERANGKAT  FROM RBM_H WHERE NO_UKK = '$u'";
 $query_ = $db->query($query);
 $data   = $query_->fetchRow();
 
 $tl->assign("data",$data);
  
 $tl->assign("id_vs",$u);
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->renderToScreen();
?>

