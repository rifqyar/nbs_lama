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
$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM VW_UPER ".$wh;
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
$query="SELECT * FROM (SELECT A.*, DECODE(A.LUNAS,'T','belum lunas','P','sudah lunas') AS STATUS_LUNAS, ROWNUM rnum FROM VW_UPER A ".$wh." AND ROWNUM <= ".$limit." ORDER BY ".$sidx." ".$sord.") where rnum > ".$start;
//echo $query; die;
$result = $db->query($query);
$rows = $result->GetAll();

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;

$i=0;
foreach ($rows as $row){
	if($row[LUNAS]=='P') {
		$color="green";
		$aksi = "<i><b><font color='#929393'>paid</b></i><img src='images/yes.png'>";
	}
	else {
		$aksi = "<button title='Payment' onclick='pay(\"".$row[NO_UPER]."\",\"".$row[NO_UKK]."\",\"".$row[VALUTA]."\",\"".number_format($row[TOTAL],2)."\")'><img src='images/invo.png'></button>";
		$color="red";
	}
	$responce->rows[$i]['id']=$row[NO_UPER];
	$responce->rows[$i]['cell']=array($aksi,$row[NO_UPER],$row[NO_UKK],$row[NM_KAPAL],$row[VOYAGE_IN]." - ".$row[VOYAGE_OUT],$row[NM_PEMILIK],number_format($row[TOTAL],2),$row[VALUTA],$row[TGL_LUNAS],$row[PAYMENT_VIA]);
	$i++;
}
echo json_encode($responce);
?>