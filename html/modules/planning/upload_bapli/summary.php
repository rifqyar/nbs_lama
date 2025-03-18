<?php
 $u = $_GET['id'];
 $tl = xliteTemplate('summary_.htm');
 
  $db = getDB();
 $query = "SELECT COUNT(ID_BAPLIE) JUMLAH FROM UPLOAD_BAPLIE WHERE NO_UKK = '$u'";
 $query_ = $db->query($query);
 $data   = $query_->fetchRow();
 $jumlah = $data['JUMLAH'];
 
 $query2 = "SELECT NM_KAPAL, VOYAGE_OUT FROM TR_VESSEL_SCHEDULE_ICT WHERE NO_UKK = '$u'";
 $query_ = $db->query($query2);
 $data2   = $query_->fetchRow();
 $nama_kapal = $data2['NM_KAPAL'];
 $voyage 	 = $data2['VOYAGE_OUT'];
 
 $tl->assign("jumlah",$jumlah);
 $tl->assign("id_vs",$u);
 $tl->assign("nama_kapal",$nama_kapal);
 $tl->assign("voyage",$voyage);
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->renderToScreen();
?>

