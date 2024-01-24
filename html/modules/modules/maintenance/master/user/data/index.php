<?php
//utk menon-aktifkan template default
outputRaw();

$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
$sidx = isset($_POST['sidx'])?$_POST['sidx']:1; // get index row - i.e. user click to sort
//$sord = $_GET['sord']; // get the direction

$db = getDB();
$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TB_USER)";
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

$query="SELECT A.*, DECODE(A.ENABLED,'1','Y','0','T') AS AKTIF, B.NAME_GROUP FROM TB_USER A LEFT JOIN TB_GROUP B ON A.ID_GROUP=B.ID_GROUP ORDER BY A.ID";
if($res = $db->query($query)) {
	$i=0;
	while ($row = $res->fetchRow()) {
		$aksi = "<a onclick='edit_user(".$row[ID].")'><img border='0' src='images/edit.png' title='edit user'></a>";
		$responce->rows[$i]['id']=$row[ID];
		$responce->rows[$i]['cell']=array($aksi,$row[ID],$row[NAME],$row[NIPP],$row[DIVISI],$row[JABATAN],$row[USERNAME],"****",$row[NAME_GROUP],$row[AKTIF]);
		$i++;
	}
}
echo json_encode($responce);
?>