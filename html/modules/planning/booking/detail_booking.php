<?php
 $u = $_GET['id'];
 $tl = xliteTemplate('detail_booking.htm');
 
  $db = getDB();
 $query = "SELECT TB_BOOKING_CONT_AREA.* , TR_VESSEL_SCHEDULE_ICT.NM_KAPAL NAMA_VESSEL, TR_VESSEL_SCHEDULE_ICT.VOYAGE_OUT VOYAGE
 FROM TB_BOOKING_CONT_AREA, TR_VESSEL_SCHEDULE_ICT
 WHERE TR_VESSEL_SCHEDULE_ICT.NO_UKK = TB_BOOKING_CONT_AREA.ID_VS
 AND TR_VESSEL_SCHEDULE_ICT.NO_UKK = '$u'";
 $query_ = $db->query($query);
 $data   = $query_->getAll();
 
 
  $tl->assign("data",$data);
 $tl->assign("id_vs",$u);
 $tl->assign("HOME",HOME);
 $tl->assign("APPID",APPID);
 $tl->renderToScreen();
?>

