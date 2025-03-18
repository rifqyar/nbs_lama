<?php
	 $tl = xliteTemplate('detail_yard2.htm');
	 $db = getDB();
	
	 $id_blok   = $_POST['id_blok'];
     $id_vessel = $_POST['id_vessel'];
   	 $blok2 	= $_POST['blok'];
	 $id_vessel2 = $_POST['id_vessel2'];
echo $blok2;die;
 if($blok2 != NULL){
	$query_blok = " AND a.ID = '$blok2'";
 } else {
	$query_blok = "";
 }
 
  if($id_vessel2 != NULL){
	$query_vessel = " AND b.ID_VS = '$id_vessel2'";
 } else {
	$query_vessel = "";
 }
 
 
echo "SELECT a.NAME,
         a.ID,
       (MAX(d.SLOT_)*MAX(d.ROW_)*a.TIER) KAPASITAS,
          (MAX(d.SLOT_)*MAX(d.ROW_)*a.TIER)-COUNT(DISTINCT(b.NO_CONTAINER)) AVA,
          COUNT(DISTINCT(b.NO_CONTAINER)) USED
    FROM yd_blocking_area a, yd_placement_yard b, yd_yard_area c, yd_blocking_cell d
   WHERE     a.id = b.id_blocking_area(+)
        AND a.ID = d.ID_BLOCKING_AREA
         AND a.id_yard_area = c.id
         AND c.status = 'AKTIF'
         AND a.NAME <> 'NULL'
		 ".$query_vessel. $query_blok"
GROUP BY a.NAME, a.ID,a.TIER, b.ID_VS";
	$query 	= "SELECT a.NAME,
         a.ID,
       (MAX(d.SLOT_)*MAX(d.ROW_)*a.TIER) KAPASITAS,
          (MAX(d.SLOT_)*MAX(d.ROW_)*a.TIER)-COUNT(DISTINCT(b.NO_CONTAINER)) AVA,
          COUNT(DISTINCT(b.NO_CONTAINER)) USED
    FROM yd_blocking_area a, yd_placement_yard b, yd_yard_area c, yd_blocking_cell d
   WHERE     a.id = b.id_blocking_area(+)
        AND a.ID = d.ID_BLOCKING_AREA
         AND a.id_yard_area = c.id
         AND c.status = 'AKTIF'
         AND a.NAME <> 'NULL'
		 ".$query_vessel. $query_blok"
GROUP BY a.NAME, a.ID,a.TIER, b.ID_VS";
	$result = $db->query($query);
	$row 	= $result->getAll();
	
	$query2 = "SELECT COUNT(DISTINCT(b.NO_CONTAINER)) USED
    FROM yd_placement_yard b";
	$result2 = $db->query($query2);
	$row2 	 = $result2->fetchRow();
	$grand   = $row2['USED'];

 $tl->assign('header',$row);
  $tl->assign('id_blok',$id_blok);
  $tl->assign('id_vs',$id_vessel);
 $tl->assign('grand',$grand);
  $tl->assign('blok2',$blok2);
   $tl->assign('id_vessel2',$id_vessel2);
 $tl->renderToScreen();
?>

