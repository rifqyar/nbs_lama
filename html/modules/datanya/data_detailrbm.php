<?php
$q = $_GET['q'];
$idvsb = $_GET['s'];
$opid = $_GET['r'];
if(isset($q)) {
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:1; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	$id = $_GET['id'];
	
	$db = getDB();
	if($q=='drbm') 
	{
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (select NO_CONTAINER, SIZE_CONT, TYPE_CONT, STATUS_CONT,
ISO_CODE, GROSS, E_I, HZ,UN_NUMBER, IMO_CLASS,
SPECIALTYPE, ALAT, HEIGHT_CONT,UC, WEIGHT, DIMENSION  
    from bil_stv_list WHERE ID_VSB_VOYAGE='$idvsb' AND OPERATOR_ID= '$opid' ORDER BY SIZE_CONT, TYPE_CONT, STATUS_CONT, E_I, NO_CONTAINER)";
	}
	else if($q=='dsf')
	{
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (select NO_CONTAINER, STATUS_CONT, ISO_CODE, E_I, JENIS_SHIFT,BRT_FROM, BRT_TO, SHIFTING_TIME  from bil_stv_sf 
		WHERE ID_VSB_VOYAGE='$idvsb' AND OPERATOR_ID= '$opid')";
	}
	else if($q=='dhm')
	{
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT ALAT, ACTIVITY, JUMLAH, START_OPEN, FINISH_OPEN, START_CLOSED, FINISH_CLOSEDT 
		FROM bil_stv_hm WHERE ID_VSB_VOYAGE='$idvsb')";
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
	if($q=='drbm') 
	{
		$query="select NO_CONTAINER, SIZE_CONT, TYPE_CONT, STATUS_CONT,
ISO_CODE, GROSS, E_I, HZ,UN_NUMBER, IMO_CLASS,
SPECIALTYPE, ALAT, HEIGHT_CONT,UC, WEIGHT, DIMENSION, ID_VSB_VOYAGE, EXTRA_TOOLS
    from bil_stv_list WHERE ID_VSB_VOYAGE='$idvsb' AND OPERATOR_ID= '$opid'";
	}
	else if($q=='dsf')
	{
		$query="select NO_CONTAINER, STATUS_CONT, ISO_CODE, HZ, E_I, JENIS_SHIFT,BRT_FROM, BRT_TO, to_char(to_date(SHIFTING_TIME,'yyyymmddhh24miss'),'dd-mm-yyyy hh24:mi') SHIFTING_TIME from bil_stv_sf 
		WHERE ID_VSB_VOYAGE='$idvsb' AND OPERATOR_ID= '$opid'";
	}
	else if($q=='dhm')
	{
		$query="SELECT ALAT, ACTIVITY, JUMLAH,to_char(to_date(START_OPEN,'yyyymmddhh24miss'),'dd-mm-yyyy hh24:mi') START_OPEN, 
		to_char(to_date(FINISH_OPEN,'yyyymmddhh24miss'),'dd-mm-yyyy hh24:mi') FINISH_OPEN,
		to_char(to_date(START_CLOSED,'yyyymmddhh24miss'),'dd-mm-yyyy hh24:mi') START_CLOSED,
		to_char(to_date(FINISH_CLOSEDT,'yyyymmddhh24miss'),'dd-mm-yyyy hh24:mi') FINISH_CLOSEDT FROM bil_stv_hm WHERE ID_VSB_VOYAGE='$idvsb'";
	}
	
	$res = $db->query($query);								
	while ($row = $res->fetchRow()) 
	{
		$aksi = "";
		if($q=='drbm'){
			
			$aksi="<button onclick='del(\"$no_req\",\"$ctx\",\"$ves\",\"$voy\",\"$carrier\")'><img src=".HOME."images/del2.png></button>";
			$aksi2="<input type='checkbox' onclick='exttools(\"$row[ID_VSB_VOYAGE]\",\"$row[NO_CONTAINER]\")'>";
			if ($row[EXTRA_TOOLS] == 'Y') {
				$aksi2="<input type='checkbox' onclick='exttools(\"$row[ID_VSB_VOYAGE]\",\"$row[NO_CONTAINER]\")' checked='checked'>";
			}

			$aksi3="<input type='checkbox' onclick='imoclass(\"$row[ID_VSB_VOYAGE]\",\"$row[NO_CONTAINER]\")'>";
			if ($row[HZ] == 'Y' && $row[IMO_CLASS] == NULL) {
				$aksi3="<input type='checkbox' onclick='imoclass(\"$row[ID_VSB_VOYAGE]\",\"$row[NO_CONTAINER]\")' checked='checked'>";
			}

			$ct=$row[SIZE_CONT].' '.$row[TYPE_CONT].' '.$row[STATUS_CONT].' '.$row[SPECIALTYPE].' ('.$row[HEIGHT_CONT].')';
			$hx=$row[HZ].' - '.$row[IMO_CLASS];
			$responce->rows[$i]['id']=$row[NO_CONTAINER];
			$responce->rows[$i]['cell']=array($aksi,$aksi2,$aksi3,$row[NO_CONTAINER],$row[ISO_CODE],$ct,$row[ALAT],$hx,$row[E_I],$row[UN_NUMBER],$row[UC], $row[WEIGHT], $row[DIMENSION]);
		} else if($q=='dsf'){
			
			$aksi="<button onclick='del(\"$no_req\",\"$ctx\",\"$ves\",\"$voy\",\"$carrier\")'><img src=".HOME."images/del2.png></button>";
			
			$responce->rows[$i]['id']=$row[NO_CONTAINER];
			$responce->rows[$i]['cell']=array($aksi,$row[NO_CONTAINER],$row[ISO_CODE],$row[STATUS_CONT],$row[HZ],$row[E_I],$row[JENIS_SHIFT],$row[BRT_FROM], $row[BRT_TO], $row[SHIFTING_TIME]);
		} else if($q=='dhm'){
			
			$aksi="<button onclick='del(\"$no_req\",\"$ctx\",\"$ves\",\"$voy\",\"$carrier\")'><img src=".HOME."images/del2.png></button>";
			
			$responce->rows[$i]['id']=$row[ALAT];
			$responce->rows[$i]['cell']=array($aksi,$row[ALAT],$row[ACTIVITY],$row[JUMLAH],$row[START_OPEN],$row[FINISH_OPEN],$row[START_CLOSED], $row[FINISH_CLOSEDT]);
		}
	
		$i++;
	}
	echo json_encode($responce);
}
?>