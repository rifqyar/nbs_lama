<?php

$db	= getDB("storage");

$yard_id = $_SESSION['IDYARD_STORAGE'];

$query_yard_area = "SELECT * FROM YARD_AREA WHERE ID LIKE '$yard_id'";
$result 		 = $db->query($query_yard_area);
$yard_area		 = $result->fetchRow();

$yard_name	= $yard_area['NAMA_YARD'];
$width  	= $yard_area['WIDTH'];
$height 	= $yard_area['HEIGHT'];
//echo "-----$yard_id---------";

		
$tl =  xliteTemplate('home.htm');

$tl->assign("yard_id", $yard_id);
$tl->assign("yard_name", $yard_name);
$tl->assign("width", $width);
$tl->assign("height", $height);

$L	= $width * $height;

if($width < 40) $m_div = 15;
else $m_div = 20;

$s	= round((900 / $width) - (($m_div/100)*(900/$width)));


$tl->assign("L", $L);
$tl->assign("s", $s);


$query_yard_cell = "SELECT * FROM YARD_CELL WHERE ID_YARD_AREA = '$yard_id' ORDER BY INDEX_CELL ASC";
$result			 = $db->query($query_yard_cell);
$row_cell_		 = $result->getAll();

$tl->assign("row_cell_", $row_cell_);

$query_blocking_area = "SELECT * FROM BLOCKING_AREA WHERE ID_YARD_AREA = '$yard_id'";
$result_block	 	 = $db->query($query_blocking_area);
$block__			 = $result_block->getAll();

$tl->assign("block__", $block__);

$query_blocking_area = "SELECT COUNT( DISTINCT BLOCKING_CELL.ROW_) JUM_ROW,  (SELECT COUNT(1) FROM PLACEMENT INNER JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID WHERE PLACEMENT.ID_BLOCKING_AREA = B.ID) AS CONT, COUNT(DISTINCT BLOCKING_CELL.SLOT_) JUM_SLOT, (COUNT(DISTINCT BLOCKING_CELL.ROW_) * COUNT(DISTINCT BLOCKING_CELL.SLOT_) * 4) AS KAPASITAS, B.COLOR, B.NAME FROM BLOCKING_AREA B INNER JOIN BLOCKING_CELL ON B.ID = BLOCKING_CELL.ID_BLOCKING_AREA WHERE B.ID_YARD_AREA = '$yard_id' GROUP BY B.ID, B.NAME, B.COLOR";
$result_block2  = $db->query($query_blocking_area);
$block__2		= $result_block2->getAll();

//echo $query_blocking_area;
$tl->assign("block__2", $block__2);

$query_data_yard 	= "SELECT YARD_AREA.NAMA_YARD,(SELECT COUNT(1) FROM PLACEMENT LEFT OUTER JOIN BLOCKING_AREA ON PLACEMENT.ID_BLOCKING_AREA = BLOCKING_AREA.ID WHERE BLOCKING_AREA.ID_YARD_AREA = '$yard_id') AS CONT, (SELECT SUM(COUNT(DISTINCT BLOCKING_CELL.ROW_) * COUNT(DISTINCT BLOCKING_CELL.SLOT_) * 4) FROM BLOCKING_CELL INNER JOIN BLOCKING_AREA ON BLOCKING_CELL.ID_BLOCKING_AREA = BLOCKING_AREA.ID WHERE BLOCKING_AREA.ID_YARD_AREA = '$yard_id' GROUP BY BLOCKING_AREA.ID) AS KAPASITAS FROM YARD_AREA INNER JOIN BLOCKING_AREA ON BLOCKING_AREA.ID_YARD_AREA = YARD_AREA.ID JOIN BLOCKING_CELL ON BLOCKING_AREA.ID = BLOCKING_CELL.ID_BLOCKING_AREA WHERE YARD_AREA.ID = '$yard_id' GROUP BY YARD_AREA.NAMA_YARD";
$result_yard		= $db->query($query_data_yard);
$yard_				= $result_yard->fetchRow(); 
//echo $query_data_yard;
$tl->assign("yard_", $yard_);

//echo date('dmy');
$tl->renderToScreen();
?>
<div style="color:grey"