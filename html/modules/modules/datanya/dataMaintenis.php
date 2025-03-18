<?php
$q = $_GET['q'];
//$id_group = $_SESSION["ID_GROUP"];
if(isset($q)) {
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:1; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	$db = getDB();
	if($q=='disableContainer')
		$query ="SELECT COUNT(1) AS NUMBER_OF_ROWS  from disable_container";
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
	if($q=='disableContainer') //ambil data header
		$query="select IDX,NO_CONTAINER, VESSEL||' '||VOYAGE_IN||' - '||VOYAGE_OUT AS VESSELN, DISABLEDATE, USERID, NAME_USER,
				ISOCODE||' '||STATUS_ AS CONTAINER_SPEC, ID_REQANNE||' '||ID_NOTA AS TRANSAKSI, ALASAN from disable_container order by IDX DESC";

	$res = $db->query($query);
	//debug($res);die;	
	while ($row = $res->fetchRow()) {

		if($q == 'disableContainer') 
		{
			$responce->rows[$i]['id']=$row[IDX];
			$responce->rows[$i]['cell']=array($row[DISABLEDATE],$row[NAME_USER],$row[NO_CONTAINER],$row[CONTAINER_SPEC],$row[VESSELN],$row[TRANSAKSI],$row[ALASAN]);
		}
		
		$i++;
	}
	echo json_encode($responce);
}
?>