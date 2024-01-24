<?php
$q = $_GET['q'];
$no_ukks = $_GET['noukk'];
$id_group = $_SESSION["ID_GROUP"];

if(isset($q)) {
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:'id_bprp'; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	$db = getDB();
	if($q=='list_stowage') {		
		$query = "SELECT NO_CONTAINER, 
						   TRIM(SIZE_) AS SIZE_, 
						   TRIM(TYPE_) AS TYPE_, 
						   TRIM(STATUS) AS STATUS, 
						   NVL(TRIM(HZ),'T') AS HZ, 
						   BERAT,
						   HANDLING_INSTRUCTION,
						   TRIM(POD) AS POD ,
						   CASE WHEN TRIM(HZ) = 'Y' AND TRIM(IMO) IS NULL THEN 'NOT'
									WHEN TRIM(HZ) = 'Y' AND TRIM(IMO) IS NOT NULL THEN TRIM(IMO) 
										 ELSE '-' END IMO,
						   CASE WHEN TRIM(CARRIER) IS NULL THEN 'NOT'
										 ELSE TRIM(CARRIER) END CARRIER,
						   BLOCK,
						   SLOT, 
						   ROW_,
						   TIER, 
						   CASE WHEN TRIM(BAYPLAN) IS NULL THEN 'NOT'
										 ELSE TRIM(BAYPLAN) END BAY,
						   LOKASI_BP AS BAY_REAL, 
						   CASE WHEN TRIM(HZ) = 'Y' AND TRIM(UN_NUMBER) IS NULL THEN 'NOT'
									WHEN TRIM(HZ) = 'Y' AND TRIM(UN_NUMBER) IS NOT NULL THEN TRIM(IMO) 
										 ELSE '-' END UN_NUMBER
						   FROM ISWS_LIST_CONTAINER
							 WHERE NO_UKK='$no_ukks' 
								 AND E_I='E'
								 AND kode_status > '50' 
							 ORDER BY BERAT DESC"; 
				
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
	if($q=='list_stowage') 		
		$query ="SELECT A.NO_CONTAINER, 
						   TRIM(A.SIZE_) AS SIZE_, 
						   TRIM(A.TYPE_) AS TYPE_, 
						   TRIM(A.STATUS) AS STATUS, 
						   NVL(TRIM(A.HZ),'T') AS HZ, 
						   A.BERAT,
						   A.HEIGHT,
						   TRIM(A.TEMP) AS TEMP,
						   B.H_ISO,
						   NVL(A.HANDLING_INSTRUCTION,'NOT') AS HI,
						   TRIM(A.POD) AS POD,
						   CASE WHEN TRIM(A.HZ) = 'Y' AND TRIM(A.IMO) IS NULL THEN 'NOT'
									WHEN TRIM(A.HZ) = 'Y' AND TRIM(A.IMO) IS NOT NULL THEN TRIM(A.IMO) 
										 ELSE '-' END IMO,
						   CASE WHEN TRIM(A.CARRIER) IS NULL THEN 'NOT'
										 ELSE TRIM(A.CARRIER) END CARRIER,
						   A.BLOCK,
						   A.SLOT, 
						   A.ROW_,
						   A.TIER, 
						   CASE WHEN TRIM(A.BAYPLAN) IS NULL THEN 'NOT'
										 ELSE TRIM(A.BAYPLAN) END BAY,
						   CASE WHEN TRIM(A.LOKASI_BP) IS NULL THEN 'NOT'
										 ELSE TRIM(A.LOKASI_BP) END BAY_REAL, 
						   CASE WHEN TRIM(A.HZ) = 'Y' AND TRIM(A.UN_NUMBER) IS NULL THEN 'NOT'
									WHEN TRIM(A.HZ) = 'Y' AND TRIM(A.UN_NUMBER) IS NOT NULL THEN TRIM(A.UN_NUMBER) 
										 ELSE '-' END UN_NUMBER
						   FROM ISWS_LIST_CONTAINER A, MASTER_ISO_CODE B
							 WHERE A.NO_UKK='$no_ukks' 
								 AND A.E_I='E'
								 AND A.KODE_STATUS > '50'
								 AND TRIM(A.ISO_CODE) = TRIM(B.ISO_CODE)
							 ORDER BY A.BERAT DESC";
		
	$res = $db->query($query);
	//debug($res);die;
	//ociexecute($query);
	
	while ($row = $res->fetchRow()) {
		
		if($q == 'list_stowage') 
		{
			if($row[IMO]=='NOT')
			{
				$im = "<font color='red'>Data Not Found</font>";
			}
			else
			{
				$im = $row[IMO];
			}
			
			if($row[TEMP]=='')
			{
				$tmp = "<font color='red'>Data Not Found</font>";
			}
			else
			{
				$tmp = $row[TEMP].":CEL";
			}
			
			if($row[HI]=='NOT')
			{
				$hi = "<font color='red'>Data Not Found</font>";
			}
			else
			{
				$hi = $row[HI];
			}
			
			if($row[HEIGHT]==NULL)
			{
				$hg = $row[H_ISO];
			}
			else
			{
				$hg = $row[H_ISO]."/".$row[HEIGHT];
			}
			
			if($row[UN_NUMBER]=='NOT')
			{
				$un = "<font color='red'>Data Not Found</font>";
			}
			else
			{
				$un = $row[UN_NUMBER];
			}
			
			if($row[CARRIER]=='NOT')
			{
				$cr = "<font color='red'>Data Not Found</font>";
			}
			else
			{
				$cr = $row[CARRIER];
			}
			
			if($row[BAY]=='NOT')
			{
				$by = "<font color='red'>Data Not Found</font>";
			}
			else
			{
				$by = $row[BAY];
			}
			
			if($row[BAY_REAL]=='NOT')
			{
				$br = "<font color='red'>Data Not Found</font>";
			}
			else
			{
				$br = $row[BAY_REAL];
			}
			
			$act = "<button onclick='edit_cont(\"$no_ukks\",\"$row[NO_CONTAINER]\")' title='edit container'><img src='images/edit.png'></button>";
		    $yd = $row[BLOCK]." - ".$row[SLOT]." - ".$row[ROW_]." - ".$row[TIER];
			$responce->rows[$i]['id']=$i;
			$responce->rows[$i]['cell']=array($act,$row[NO_CONTAINER],$row[SIZE_],$row[TYPE_],$row[STATUS],$row[HZ],$row[BERAT],$tmp,$hg,$hi,$row[POD],$im,$un,$cr,$yd,$by,$br);
		}
				
		$i++;
	}
	echo json_encode($responce);
}
?>