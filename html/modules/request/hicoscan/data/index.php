<?php
//utk menon-aktifkan template default
outputRaw();

$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
$sidx = isset($_POST['sidx'])?$_POST['sidx']:1; // get index row - i.e. user click to sort
$sord = $_POST['sord']; // get the direction

$wh = " WHERE 1=1";
$filters = json_decode($_REQUEST['filters']);
//echo '<pre>';print_r($_REQUEST);
$searchOn = $_REQUEST['_search'];
if($searchOn=='true') {	
	$sarr = $filters->rules;
	foreach( $sarr as $k) {
		$wh .= " AND UPPER(".$k->field.") LIKE UPPER('%".$k->data."%')";
	}
}

$db = getDB();
$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM REQ_HICOSCAN ".$wh;
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
$limit = $limit*$page;
// if($sidx=="")	$sidx=1;
$query="SELECT * FROM (SELECT * FROM (SELECT A.*, (SELECT COUNT(B.NO_CONTAINER) FROM REQ_HICOSCAN_D B WHERE A.ID_REQUEST=B.ID_REQUEST ) JUMLAH_CONT, ROWNUM rnum FROM REQ_HICOSCAN A ".$wh." ORDER BY ".$sidx." ".$sord.") where ROWNUM <= ".$limit." ) where rnum > ".$start;
//echo $query; die;
$result = $db->query($query);
$rows = $result->GetAll();

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;

$i=0;
foreach ($rows as $row){
	
	if(($row[STATUS]=='S')||($row[STATUS]==''))
	{
		//$aksi = "<a onclick='edit_reqbh(\"".$row[ID_REQUEST]."\")'><img border='0' src='images/edit.png' title='edit request'></a> &nbsp;&nbsp; <a onclick='send_keu(\"".$row[ID_REQUEST]."\")'><img border='0' src='images/save_peb.gif' width='14' height='14' title='SIMPAN FINAL'></a>";	
		$aksi = "<a onclick='edit_reqbh(\"".$row[ID_REQUEST]."\")'><img border='0' src='images/edit.png' title='edit request'></a>";	
	}
	else
	
	{
		$aksi = "<blink><b><font color='red'><i>".$row[STATUS]."</i></font></b></blink>";
	}
	$responce->rows[$i]['id']=$row[ID_REQUEST];
	$responce->rows[$i]['cell']=array($aksi,$row[ID_REQUEST],$row[EMKL],$row[TGL_REQUEST],$row[VESSEL].' / '.$row[VOYAGE],$row[TIPE_REQ],$row[NOMOR_INSTRUKSI],$row[JUMLAH_CONT]);
	$i++;
}
echo json_encode($responce);
?>