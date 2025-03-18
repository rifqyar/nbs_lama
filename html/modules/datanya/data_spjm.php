<?php
$q = $_GET['q'];
$idplp = $_GET['id_plp'];

if(isset($q)) {
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:1; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	$id = $_GET['id'];
	
	$db = getDB();
	if($q=='del_spjm') {
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (
								SELECT * FROM BIL_DELSPJM_H 
								WHERE TRIM(STS_DELSPJM) = 'A'
								)";
	} else if($q=='print_spjm') {
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (
								SELECT * FROM BIL_DELSPJM_H 
								WHERE TRIM(STS_DELSPJM) = 'A'
								)";
	}
		
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
	if($q=='del_spjm') 
	{
		$query="SELECT * FROM (SELECT A.CUST_NAME, 
					   A.NO_PIB,
					   A.NO_REQUEST,
					   TO_CHAR(A.APP_DATE,'DD-MM-YYYY') APP_DATE,
				       A.VESSEL||' '||A.VOY_IN||'-'||A.VOY_OUT AS VESVOY, 
					   A.ORIGIN_TERM, 
					   A.ID_YD,
					   A.ID_DEL,
					   (SELECT COUNT(*) FROM BIL_DELSPJM_D WHERE TRIM(ID_DEL) = TRIM(A.ID_DEL)) AS JML_CONT,
					   A.APP_BY,
					   A.IS_MANUAL
			    FROM BIL_DELSPJM_H A
				WHERE TRIM(A.STS_DELSPJM) = 'A'
					ORDER BY ID_DEL DESC) WHERE ROWNUM < 1000";
	} else if($q=='print_spjm') 
	{
		$query="SELECT * FROM (SELECT A.CUST_NAME, 
					   A.NO_REQUEST,
					   TO_CHAR(A.APP_DATE,'DD-MM-YYYY') APP_DATE,
				       A.VESSEL||' '||A.VOY_IN||'-'||A.VOY_OUT AS VESVOY, 
					   A.ORIGIN_TERM, 
					   A.ID_YD, 
					   (SELECT COUNT(*) FROM BIL_DELSPJM_D WHERE TRIM(ID_DEL) = TRIM(A.ID_DEL)) AS JML_CONT,
					   A.ID_DEL
			    FROM BIL_DELSPJM_H A
				WHERE TRIM(A.STS_DELSPJM) = 'A'
					ORDER BY ID_DEL DESC) WHERE ROWNUM < 1000";
	}
	$res = $db->query($query);								
	while ($row = $res->fetchRow()) 
	{
	if($q=='del_spjm'){
			$aksi="<button onclick='detail_sp2spjm(\"$row[ID_DEL]\")'><img src=".HOME."images/expand.png height='15px' width='15px' title='detail sp2 spjm'></button>";
			$jenis="";
			if($row[IS_MANUAL]=='Y'){
				$jenis="Manual";
			} else {
				$jenis='TPS Online';
			}
			$ct=$row[SIZE_CONT].' '.$row[TYPE_CONT].' '.$row[STATUS_CONT];
			$responce->rows[$i]['id']=$row[NO_CONTAINER];
			$responce->rows[$i]['cell']=array($aksi,$row[NO_REQUEST],$row[NO_PIB],$jenis,$row[APP_DATE],$row[VESVOY],$row[JML_CONT],$row[APP_BY]);
		} else if($q=='print_spjm'){
			$aksi="<a href=".HOME."print.sp2spjm.cetak/?pl=".$row[ID_DEL]." target='_blank'><img src='images/printer.png' title='cetak sp2 spjm'></a>";
			$responce->rows[$i]['id']=$row[ID_DEL];
			$responce->rows[$i]['cell']=array($aksi,$row[NO_REQUEST],$row[CUST_NAME],$row[VESVOY],$row[JML_CONT],$row[ORIGIN_TERM],$row[ID_YD]);
		}
		$i++;
	}
	echo json_encode($responce);
}
?>