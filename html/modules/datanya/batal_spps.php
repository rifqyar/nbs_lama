<?php
	
	$db 			= getDB("storage");

	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:1; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;

	$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM REQ_BATAL_SPPS)";
	//print_r($query);die;
	$res = $db->query($query)->fetchRow();
	$count = $res[NUMBER_OF_ROWS];
	
	/*oci_define_by_name($query, 'NUMBER_OF_ROWS', $count);
	oci_execute($query);
	oci_fetch($query);*/

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

	$query="SELECT
				NO_BA,
				ID_REQ_SPPS,
				NO_CONTAINER,
				TANGGAL_PEMBUATAN,
				VESSEL,
				VOYAGE_IN 
			FROM REQ_BATAL_SPPS 
			ORDER BY TANGGAL_PEMBUATAN DESC";

	$res = $db->query($query);
	//debug($res);die;	

	while ($row = $res->fetchRow()) {
		$aksi = "";
		$responce->rows[$i]['cell']=array($aksi,$row[NO_BA],$row[ID_REQ_SPPS],$row[NO_CONTAINER],$row[TANGGAL_PEMBUATAN],$row[VESSEL], $row[VOYAGE_IN]);

		$i++;
	}
	echo json_encode($responce);
?>