<?php
 $u = $_GET['id'];
 $tl = xliteTemplate('detail_booking.htm');
 
 $db = getDB();
 $query = "SELECT TB_BOOKING_CONT_AREA.* , VESSEL_SCHEDULE.VOYAGE, MASTER_VESSEL.NAMA_VESSEL 
 FROM TB_BOOKING_CONT_AREA, VESSEL_SCHEDULE, MASTER_VESSEL
 WHERE 
 VESSEL_SCHEDULE.ID_VS = TB_BOOKING_CONT_AREA.ID_VS
 AND VESSEL_SCHEDULE.ID_VS = '$u'
 AND VESSEL_SCHEDULE.ID_VES = MASTER_VESSEL.KODE_KAPAL";
 $query_ = $db->query($query);
 $data   = $query_->getAll();
 
  $tl->assign("data",$data);
 $tl->assign("id_vs",$u);
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->renderToScreen();
?>

