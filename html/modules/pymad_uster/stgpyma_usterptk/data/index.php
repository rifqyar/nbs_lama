<?php
//utk menon-aktifkan template default
outputRaw();

$db = getDB('storage');
//$KD_CAB = $_SESSION['SESS_KD_CABANG'];
$KD_CAB = '05';
$ACC_KD_CAB = '05A';
$qc = "select count(distinct(org_id)) as corg_id from kapal_prod.mst_org_id@dbint_uster where kd_cabang = '$KD_CAB' AND KD_ACCOUNT_CABANG = '$ACC_KD_CAB'";
$crow = $db->query($qc)->fetchRow();
$count = $crow['CORG_ID'];
if($count > 0){
$no = 1;
$q = "select distinct(org_id) as org_id from kapal_prod.mst_org_id@dbint_uster where kd_cabang = '$KD_CAB' AND KD_ACCOUNT_CABANG = '$ACC_KD_CAB'";
$R = $db->query($q)->getAll();
$ORG_ID = '(';
foreach($R as $row){
	if($no!=$count){
		$ORG_ID .= $row['ORG_ID'].',';
	}else{
		$ORG_ID .= $row['ORG_ID'];
	}
$no++; }
$ORG_ID .= ')';
$where_cab = "AND ORG_ID in ".$ORG_ID."";
}else{
$where_cab = '';
}

$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
$sidx = isset($_POST['sidx'])?$_POST['sidx']:1; // get index row - i.e. user click to sort
//$sord = $_GET['sord']; // get the direction
if($_GET['col'] && $_GET['key']){
	$col = $_GET['col'];
	$key = strtoupper($_GET['key']);
$queryc ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (select trx_number,trx_date,customer_name,nota_name,confirmation_status, sum(nvl(amount,0)) amount 
from uster.PYMA_STAGING where TRX_NUMBER IS NOT NULL AND upper($col) like '%$key%' $where_cab
group by trx_number,trx_date, customer_name,nota_name,confirmation_status)";
//print_r($query);die;
$res = $db->query($queryc)->fetchRow();
$count = $res[NUMBER_OF_ROWS];
}else{
	$YEAR = $_GET['year'];
$queryc ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (select trx_number,trx_date,customer_name,nota_name,confirmation_status, sum(nvl(amount,0)) amount 
from uster.PYMA_STAGING where TRX_NUMBER IS NOT NULL  and to_char(trx_date,'yyyy') = '$YEAR' $where_cab
group by trx_number,trx_date, customer_name,nota_name,confirmation_status)";
//print_r($query);die;
$res = $db->query($queryc)->fetchRow();
$count = $res[NUMBER_OF_ROWS];
}

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
if($_GET['col'] && $_GET['key']){
	$col = $_GET['col'];
	$key = strtoupper($_GET['key']); 
$querys="select nota_name,trx_number,to_char(trx_date,'YYYY-MM-DD') as trx_date,customer_name,confirmation_status, sum(nvl(amount,0)) amount 
from uster.PYMA_STAGING where TRX_NUMBER IS NOT NULL AND upper($col) like '%$key%' $where_cab
group by nota_name,trx_number,trx_date, customer_name,confirmation_status order by nota_name asc";
}else{
	$YEAR = $_GET['year'];
$querys="select nota_name,trx_number,to_char(trx_date,'YYYY-MM-DD') as trx_date,customer_name,confirmation_status, sum(nvl(amount,0)) amount 
from uster.PYMA_STAGING where TRX_NUMBER IS NOT NULL and to_char(trx_date,'yyyy') = '$YEAR' $where_cab
group by nota_name,trx_number,trx_date, customer_name,confirmation_status order by nota_name asc";	
}
if($res = $db->query($querys)) {
	$i=0;
	while ($row = $res->fetchRow()) {
		$id=$row[TRX_NUMBER];
		$y = 'Y';
		$x = 'X';
		$aksi = $row[TRX_NUMBER]." ".$row[TRX_NUMBER];
		#$aksi2="<button onclick='fu_1(\"$id\",\"$aksi\",\"$row[TRX_DATE]\",\"$row[TRX_DATE]\")'>pick cargo</button>";
		$aksi2="<button onclick='detail(\"$id\")' class='newButton'>Detail</button>";
		$responce->rows[$i]['id']=$row[TRX_NUMBER];
		$responce->rows[$i]['cell']=array($row[NOTA_NAME],$row[TRX_NUMBER],$row[TRX_DATE],$row[CUSTOMER_NAME],$row[AMOUNT],$row[CONFIRMATION_STATUS],$aksi2);
		$i++;
	}
}
echo json_encode($responce);
?>