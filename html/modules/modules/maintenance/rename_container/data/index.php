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
$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM RENAME_CONTAINER ".$wh;
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

//No Rename','Tanggal Rename','No Ex Container','No Container New','Vessel / Voyage'

$query="SELECT * FROM (SELECT A.*, ROWNUM rnum FROM RENAME_CONTAINER A ".$wh." AND ROWNUM <= ".$limit." ORDER BY ".$sidx." ".$sord.") where rnum > ".$start;
//echo $query; die;
$result = $db->query($query);
$rows = $result->GetAll();

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;

$i=0;
foreach ($rows as $row){
	if($row[STATUS]=='P' || $row[STATUS]=='T')
	{
		$aksi = "<blink><b><font color='red'><i>sudah lunas</i></font></b></blink>";
	}
	else
	{
		//$aksi = "<a onclick='edit_reqbh(\"".$row[ID_REQUEST]."\")'><img border='0' src='images/edit.png' title='edit request'></a> &nbsp;&nbsp; <a onclick='send_keu(\"".$row[ID_REQUEST]."\")'><img border='0' src='images/save_peb.gif' width='14' height='14' title='SIMPAN FINAL'></a>";	
		$aksi = "<a onclick='edit_reqbh(\"".$row[NO_RENAME]."\")'><img border='0' src='images/edit.png' title='edit request'></a>";	
	}
	$responce->rows[$i]['id']=$row[NO_RENAME];
	$responce->rows[$i]['cell']=array($aksi,$row[NO_RENAME],$row[TGL_RENAME],$row[NO_EX_CONTAINER],$row[NO_CONTAINER],$row[VESSEL].' / '.$row[VOYAGE]);
	$i++;
}
echo json_encode($responce);
?>