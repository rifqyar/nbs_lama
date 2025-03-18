<?php
$q = $_GET['q'];
$kg = $_GET['kg'];
$list_det_ukk = $_GET['list_det_ukk'];
$no_ukks = $_GET['no_ukks'];
$no_cont = $_GET['no_container'];

$id_group = $_SESSION["ID_GROUP"];
if(isset($q)) {
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:'id_bprp'; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	$db = getDB();
	if($q=='gatein') {		
		$query = "SELECT COUNT(*) AS NUMBER_OF_ROWS FROM(
			SELECT * FROM 
				(
				    SELECT A.NO_UKK, 
				            A.NO_CONTAINER, 
				            A.NO_REQUEST,
				            A.SIZE_, 
				            A.TYPE_, 
				            A.STATUS, 
				            B.NM_KAPAL, 
				            B.VOYAGE_IN,
				            B.VOYAGE_OUT, 
				            TO_CHAR(TGL_GATE_IN,'DD-MM-YYYY HH24:MI') TGL_GATE_IN,
				            TO_CHAR(TGL_GATE_IN,'YYYYMMDDHH24MI') SEQ,
							CASE
								WHEN A.R_BLOCK IS NOT NULL THEN A.R_BLOCK||' '||A.R_SLOT||'-'||A.R_ROW_||'-'||A.R_TIER
								ELSE A.BLOCK||' '||A.SLOT||'-'||A.ROW_||'-'||A.TIER
							END
								YD_POSISI
				            FROM ISWS_LIST_CONTAINER A, RBM_H B 
				            WHERE TRIM(A.NO_UKK)=TRIM(B.NO_UKK) 
				                 AND A.STATUS_GATE_IN='Y' 
				                 AND A.E_I='I'
				            ORDER BY SEQ DESC
				) A
				WHERE ROWNUM < 11
		)"; 
				
	}
		
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
	if($q=='gatein') 		
		$query ="SELECT * FROM 
				(
				    SELECT A.NO_UKK, 
				            A.NO_CONTAINER, 
				            A.SIZE_, 
				            A.TYPE_, 
				            A.STATUS, 
				            B.NM_KAPAL, 
				            B.VOYAGE_IN,
				            B.VOYAGE_OUT, 
				            TO_CHAR(TGL_GATE_IN,'DD-MM-YYYY HH24:MI') TGL_GATE_IN,
				            TO_CHAR(TGL_GATE_IN,'YYYYMMDDHH24MI') SEQ,
							CASE
								WHEN A.BLOCK IS NOT NULL THEN A.BLOCK||' '||A.SLOT||'-'||A.ROW_||'-'||A.TIER
								ELSE A.BLOCK||' '||A.SLOT||'-'||A.ROW_||'-'||A.TIER
							END
								YD_POSISI
				            FROM ISWS_LIST_CONTAINER A, RBM_H B 
				            WHERE TRIM(A.NO_UKK)=TRIM(B.NO_UKK) 
				                 AND A.STATUS_GATE_IN='Y' 
				                 AND A.E_I='I'
				            ORDER BY SEQ DESC
				) A
				WHERE ROWNUM < 11
			";
		
	$res = $db->query($query);
	//debug($res);die;
	//ociexecute($query);
	
	while ($row = $res->fetchRow()) {
		
		if($q == 'gatein') 
		{
			$sts	= $row[SIZE_]."/".$row[TYPE_]."/".$row[STATUS];
			$voy	= $row[NM_KAPAL]."/".$row[VOYAGE_IN]."-".$row[VOYAGE_OUT];
			$responce->rows[$i]['id']=$row[NO_UKK];
			$responce->rows[$i]['cell']=array($row[NO_UKK],$row[NO_CONTAINER],$sts,$voy,$row[TGL_GATE_IN],$row[YD_POSISI]);
					
		}
				
		$i++;
	}
	echo json_encode($responce);
}
?>