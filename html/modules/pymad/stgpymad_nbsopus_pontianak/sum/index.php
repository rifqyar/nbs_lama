<?php
//utk menon-aktifkan template default
outputRaw();

$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
$sidx = isset($_POST['sidx'])?$_POST['sidx']:1; // get index row - i.e. user click to sort
//$sord = $_GET['sord']; // get the direction

$db = getDB();
//$KD_CAB = $_SESSION['SESS_KD_CABANG'];
$CORPORATE_NAME = 'IPTK';
$qc = "select count(distinct(org_id)) as corg_id from mst_org_id where corporate_name = '$CORPORATE_NAME'";
$crow = $db->query($qc)->fetchRow();
$count = $crow['CORG_ID'];
if($count > 0){
$no = 1;
$q = "select distinct(org_id) as org_id from mst_org_id where corporate_name = '$CORPORATE_NAME'";
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

if($_GET['year']){
$YEAR = $_GET['year'];
$querycount = "SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (select a.nota_name as nota_name, currency, 
	(select count(distinct(b.trx_number)) 
	from billing.pyma_staging b where b.nota_name = a.nota_name 
	and b.confirmation_status = 'Y' and b.trx_number IS NOT NULL and to_char(b.trx_date,'yyyy')='$YEAR' and b.org_id in $ORG_ID) as count_yes, 
	(select nvl(sum(c.amount),0) 
	from billing.pyma_staging c where c.nota_name = a.nota_name 
	and c.confirmation_status = 'Y' and c.trx_number IS NOT NULL and to_char(c.trx_date,'yyyy')='$YEAR' and c.org_id in $ORG_ID) as amount_yes,
	(select count(distinct(d.trx_number)) 
	from billing.pyma_staging d where d.nota_name = a.nota_name 
	and d.confirmation_status = 'X' and d.trx_number IS NOT NULL and to_char(d.trx_date,'yyyy')='$YEAR' and d.org_id in $ORG_ID) as count_no,
	(select nvl(sum(f.amount),0) 
	from billing.pyma_staging f where f.nota_name = a.nota_name 
	and f.confirmation_status = 'X' and f.trx_number IS NOT NULL and to_char(f.trx_date,'yyyy')='$YEAR' and f.org_id in $ORG_ID) as amount_no
	from billing.pyma_staging a where a.trx_number IS NOT NULL 
	and a.org_id in $ORG_ID and to_char(a.trx_date,'yyyy')='$YEAR' group by a.nota_name, a.currency order by a.nota_name asc)";
$querydata  = "select a.nota_name as nota_name, currency, 
	(select count(distinct(b.trx_number)) 
	from billing.pyma_staging b where b.nota_name = a.nota_name 
	and b.confirmation_status = 'Y' and b.trx_number IS NOT NULL and to_char(b.trx_date,'yyyy')='$YEAR' and b.org_id in $ORG_ID) as count_yes, 
	(select nvl(sum(c.amount),0) 
	from billing.pyma_staging c where c.nota_name = a.nota_name 
	and c.confirmation_status = 'Y' and c.trx_number IS NOT NULL and to_char(c.trx_date,'yyyy')='$YEAR' and c.org_id in $ORG_ID) as amount_yes,
	(select count(distinct(d.trx_number)) 
	from billing.pyma_staging d where d.nota_name = a.nota_name 
	and d.confirmation_status = 'X' and d.trx_number IS NOT NULL and to_char(d.trx_date,'yyyy')='$YEAR' and d.org_id in $ORG_ID) as count_no,
	(select nvl(sum(f.amount),0) 
	from billing.pyma_staging f where f.nota_name = a.nota_name 
	and f.confirmation_status = 'X' and f.trx_number IS NOT NULL and to_char(f.trx_date,'yyyy')='$YEAR' and f.org_id in $ORG_ID) as amount_no
	from billing.pyma_staging a where a.trx_number IS NOT NULL 
	and a.org_id in $ORG_ID and to_char(a.trx_date,'yyyy')='$YEAR' group by a.nota_name, a.currency order by a.nota_name asc";
}
elseif($_GET['col'] && $_GET['key']){
$COL = $_GET['col'];
$KEY = strtoupper($_GET['key']);
$querycount = "SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (select a.nota_name as nota_name, currency, 
	(select count(distinct(b.trx_number)) 
	from billing.pyma_staging b where b.nota_name = a.nota_name 
	and b.confirmation_status = 'Y' and b.trx_number IS NOT NULL and upper(b.$COL) like '%$KEY%' and b.org_id in $ORG_ID) as count_yes, 
	(select nvl(sum(c.amount),0) 
	from billing.pyma_staging c where c.nota_name = a.nota_name 
	and c.confirmation_status = 'Y' and c.trx_number IS NOT NULL and upper(c.$COL) like '%$KEY%' and c.org_id in $ORG_ID) as amount_yes,
	(select count(distinct(d.trx_number)) 
	from billing.pyma_staging d where d.nota_name = a.nota_name 
	and d.confirmation_status = 'X' and d.trx_number IS NOT NULL and upper(d.$COL) like '%$KEY%' and d.org_id in $ORG_ID) as count_no,
	(select nvl(sum(f.amount),0) 
	from billing.pyma_staging f where f.nota_name = a.nota_name 
	and f.confirmation_status = 'X' and f.trx_number IS NOT NULL and upper(f.$COL) like '%$KEY%' and f.org_id in $ORG_ID) as amount_no
	from billing.pyma_staging a where a.trx_number IS NOT NULL 
	and a.org_id in $ORG_ID and upper(a.$COL) like '%$KEY%' group by a.nota_name, a.currency order by a.nota_name asc)";
$querydata  ="select a.nota_name as nota_name, currency, 
	(select count(distinct(b.trx_number)) 
	from billing.pyma_staging b where b.nota_name = a.nota_name 
	and b.confirmation_status = 'Y' and b.trx_number IS NOT NULL and upper(b.$COL) like '%$KEY%' and b.org_id in $ORG_ID) as count_yes, 
	(select nvl(sum(c.amount),0) 
	from billing.pyma_staging c where c.nota_name = a.nota_name 
	and c.confirmation_status = 'Y' and c.trx_number IS NOT NULL and upper(c.$COL) like '%$KEY%' and c.org_id in $ORG_ID) as amount_yes,
	(select count(distinct(d.trx_number)) 
	from billing.pyma_staging d where d.nota_name = a.nota_name 
	and d.confirmation_status = 'X' and d.trx_number IS NOT NULL and upper(d.$COL) like '%$KEY%' and d.org_id in $ORG_ID) as count_no,
	(select nvl(sum(f.amount),0) 
	from billing.pyma_staging f where f.nota_name = a.nota_name 
	and f.confirmation_status = 'X' and f.trx_number IS NOT NULL and upper(f.$COL) like '%$KEY%' and f.org_id in $ORG_ID) as amount_no
	from billing.pyma_staging a where a.trx_number IS NOT NULL 
	and a.org_id in $ORG_ID and upper(a.$COL) like '%$KEY%' group by a.nota_name, a.currency order by a.nota_name asc";
	
}
//var_dump($querydata);exit;
//var_dump($querycount);exit;
$res = $db->query($querycount)->fetchRow();
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
 

if($res = $db->query($querydata)) {
	$i=0;
	while ($row = $res->fetchRow()) {
		//$id=$row[TRX_NUMBER];
		//$y = 'Y';
		//$x = 'X';
		//$aksi = $row[TRX_NUMBER]." ".$row[TRX_NUMBER];
		#$aksi2="<button onclick='fu_1(\"$id\",\"$aksi\",\"$row[TRX_DATE]\",\"$row[TRX_DATE]\")'>pick cargo</button>";
		//$aksi2="<button onclick='detail(\"$id\")'>Detail</button>";
		//$responce->rows[$i]['id']=$row[TRX_NUMBER];
		$responce->rows[$i]['cell']=array(
			$row[NOTA_NAME],
			$row[CURRENCY],
			$row[COUNT_YES],
			$row[AMOUNT_YES],
			$row[COUNT_NO],
			$row[AMOUNT_NO]
			);
		$i++;
	}
}
echo json_encode($responce);
?>