<?php
	 $tl = xliteTemplate('detail_yard.htm');
	 $db             = getDB();
	
	 $id_blok   = $_POST['id_blok'];
     $id_vessel = $_POST['id_vessel'];


	if ($id_blok == NULL) {
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
GROUP BY a.NAME, a.ID,a.TIER";
	$result = $db->query($query);
	$row 	= $result->getAll();
	
	$query2 = "SELECT COUNT(DISTINCT(b.NO_CONTAINER)) USED
    FROM yd_placement_yard b";
	$result2 = $db->query($query2);
	$row2 	 = $result2->fetchRow();
	$grand   = $row2['USED'];
	
	} else {
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
         AND a.ID = '$id_blok'
GROUP BY a.NAME, a.ID,a.TIER";
	$result = $db->query($query);
	$row 	= $result->getAll();
	
	$query2 = "SELECT COUNT(DISTINCT(b.NO_CONTAINER)) USED
    FROM yd_placement_yard b
	WHERE b.ID_BLOCKING_AREA = '$id_blok'";
	$result2 = $db->query($query2);
	$row2 	 = $result2->fetchRow();
	$grand   = $row2['USED'];
	}

 $tl->assign('header',$row);
  $tl->assign('id_blok',$id_blok);
  $tl->assign('id_vs',$id_vessel);
 $tl->assign('grand',$grand);
 $tl->renderToScreen();
?>

