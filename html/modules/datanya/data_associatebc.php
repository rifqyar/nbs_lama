<?php

$q = $_GET['q'];

if(isset($q)) {
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:'id_bprp'; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	
	if(!$sidx) $sidx =1;
	$db = getDB();
	
	if($q=='assbc11')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM M_VSB_VOYAGE@dbint_link)";
	
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
	
	if($q=='assbc11')		
		$query ="SELECT * FROM (SELECT VESSEL, VOYAGE_IN, VOYAGE_OUT, TO_CHAR(to_date(ETA,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:Mi') TGL_JAM_TIBA,
		NOBC11, to_date(TGLBC11, 'YYYYMMDD') as TGLBC11 FROM M_VSB_VOYAGE@dbint_link ORDER BY ETA DESC) WHERE ROWNUM <=200";
		
	$res = $db->query($query);

	
	while ($row = $res->fetchRow()) {
		$aksi = "";
		
		if($q == 'assbc11') 
		{
			if ($row['NOBC11']==''){
				$aksi = "<font color='red'><i>not assigned</i></font>";
			} else {
				$aksi = "<font color='blue'><i>assigned</i></font>";
			}
			$responce->rows[$i]['id']=$row[ID_VSB_VOYAGE];
			$responce->rows[$i]['cell']=array($aksi, $row[VESSEL], $row[VOYAGE_IN]."/".$row[VOYAGE_OUT], $row[TGL_JAM_TIBA], $row[NOBC11], $row[TGLBC11]);
		}

		$i++;
	}
	echo json_encode($responce);
}
?>