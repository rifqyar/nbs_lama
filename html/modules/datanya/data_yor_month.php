<?php
$q = $_GET['q'];
$list_det_ukk = $_GET['list_det_ukk'];
$no_ukks = $_GET['no_ukks'];
$no_bundle = $_GET['no_bundle'];
$id_group = $_SESSION["ID_GROUP"];

$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
$sidx = isset($_POST['sidx'])?$_POST['sidx']:'id_bprp'; // get index row - i.e. user click to sort

if(!$sidx) $sidx =1;

	$db = getDB();
	$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM YD_BLOCKING_AREA B WHERE B.ID_YARD_AREA=81 AND B.TIER != 0";
	//print_r($query);die;
	$res = $db->query($query)->fetchRow();
	$count = $res[NUMBER_OF_ROWS];
	
	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	}
	else { 
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)	

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	
	for($i=0;$i<31;$i++){
		
	}
	
	$query="SELECT B.NAME,
         COUNT (DISTINCT (A.SLOT_)) AS SLOT,
         COUNT (DISTINCT (A.ROW_)) AS ROW_CONT,
         B.TIER,
         (COUNT (DISTINCT (A.INDEX_CELL)) * (B.TIER)) AS CAPACITY,
         COUNT (D.ID_CELL) AS USED,
         round(( (COUNT (D.ID_CELL)) / (COUNT (DISTINCT (A.INDEX_CELL)) * B.TIER))
         * 100,2)
            AS YOR,
         COUNT (DISTINCT (D.NO_CONTAINER)) BOX
    FROM YD_BLOCKING_CELL A
         LEFT JOIN YD_PLACEMENT_YARD D
            ON (A.INDEX_CELL = D.ID_CELL)
         INNER JOIN YD_BLOCKING_AREA B
            ON (A.ID_BLOCKING_AREA = B.ID)
         INNER JOIN YD_YARD_AREA C
            ON (B.ID_YARD_AREA = C.ID)
   WHERE NAMA_YARD LIKE 'LAPANGAN 300' AND B.TIER != 0
	GROUP BY C.ID,
         B.ID,
         B.NAME,
         B.TIER
	ORDER BY C.ID, B.NAME, B.ID";
	$res = $db->query($query);
	
	while ($row = $res->fetchRow()) {
		$responce->rows[$i]['id']=$row[NAME];
		$responce->rows[$i]['cell']=array($row[NAME],$row[SLOT],$row[ROW_CONT],$row[TIER],$row[CAPACITY],$row[USED],$row[BOX],$row[YOR]);
		$i++;
	}
	
	echo json_encode($responce);
?>