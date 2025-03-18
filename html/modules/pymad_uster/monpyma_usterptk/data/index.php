<?php
//utk menon-aktifkan template default
outputRaw();
$NOTANAME = $_GET['notaname'];
$STATUS	  = $_GET['status'];
$YEAR	  = $_GET['year'];

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

if($_GET['print']){
outputRaw();
$filename = "MONITORING_PYMAD_USTER_PONTIANAK.XLS";
header("Content-type: application/xls");
header("Content-Disposition: filename=$filename");
$query="select nota_name,currency,trx_number,trx_date,customer_name,transfer_status,transfer_message,status_ar,ar_date,sum(nvl(amount,0)) amount 
from uster.PYMA_STAGING where TRX_NUMBER IS NOT NULL and nota_name = '$NOTANAME' and transfer_status = '$STATUS' and to_char(trx_date,'yyyy') = '$YEAR' $where_cab
group by nota_name,currency,trx_number,trx_date, customer_name,transfer_status,transfer_message,status_ar,ar_date order by nota_name asc";
$res = $db->query($query);
$getas = $res->getAll(); 
?>
<h1>MONITORING PYMAD USTER PONTIANAK</h1>
<table border="1" borderspacing=0 borderpadding=0>
<tr>
	<th style="padding:5px">No</th>
	<th style="padding:5px;width:200px">Nama Pranota</th>
	<th style="padding:5px;width:200px">Currency</th>
	<th style="padding:5px;width:150px">No Pranota</th>
	<th style="padding:5px;width:100px">Tgl Pranota</th>
	<th style="padding:5px;width:230px">Nama Pelanggan</th>
	<th style="padding:5px;width:100px">Amount</th>
	<th style="padding:5px;width:80px">Transfer Status</th>
	<th style="padding:5px;width:150px">Transfer Message</th>
	<th style="padding:5px;width:150px">Status AR</th>
	<th style="padding:5px;width:150px">AR DATE</th>
</tr>
<?php $no=1; foreach($getas as $row){?>
	<tr>
	<td style="padding:3px"><?php echo $no;?></td>
	<td style="padding:3px"><?php echo $row['NOTA_NAME'];?></td>
	<td style="padding:3px"><?php echo $row['CURRENCY'];?></td>
	<td style="padding:3px"><?php echo "'".$row['TRX_NUMBER'].'';?></td>
	<td style="padding:3px"><?php echo $row['TRX_DATE'];?></td>
	<td style="padding:3px"><?php echo $row['CUSTOMER_NAME'];?></td>
	<td style="padding:3px"><?php echo $row['AMOUNT'];?></td>
	<td style="padding:3px"><?php echo $row['TRANSFER_STATUS'];?></td>
	<td style="padding:3px"><?php echo $row['TRANSFER_MESSAGE'];?></td>
	<td style="padding:3px"><?php echo $row['STATUS_AR'];?></td>
	<td style="padding:3px"><?php echo $row['AR_DATE'];?></td>
	</tr>
<?php $no++; } ?>
</table>
<?php
}else{

$db = getDB('storage');

$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
$sidx = isset($_POST['sidx'])?$_POST['sidx']:1; // get index row - i.e. user click to sort

$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (select trx_number,trx_date,customer_name,nota_name,confirmation_status,status_ar,ar_date, sum(nvl(amount,0)) amount 
from uster.PYMA_STAGING where TRX_NUMBER IS NOT NULL and nota_name = '$NOTANAME' and transfer_status = '$STATUS' $where_cab and to_char(trx_date,'yyyy') = '$YEAR' $where_cab
group by trx_number,trx_date, customer_name,nota_name,confirmation_status,status_ar,ar_date)";
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

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;

$query="select nota_name,trx_number,trx_date,customer_name,transfer_status,transfer_message,status_ar,ar_date,sum(nvl(amount,0)) amount 
from uster.PYMA_STAGING where TRX_NUMBER IS NOT NULL and nota_name = '$NOTANAME' and transfer_status = '$STATUS' $where_cab and to_char(trx_date,'yyyy') = '$YEAR' $where_cab
group by nota_name,trx_number,trx_date, customer_name,transfer_status,transfer_message,status_ar,ar_date order by nota_name asc";	

if($res = $db->query($query)) {
	$i=0;
	while ($row = $res->fetchRow()) {
		$id=$row[TRX_NUMBER];
		$y = 'Y';
		$x = 'X';
		$aksi = $row[TRX_NUMBER]." ".$row[TRX_NUMBER];
		#$aksi2="<button onclick='fu_1(\"$id\",\"$aksi\",\"$row[TRX_DATE]\",\"$row[TRX_DATE]\")'>pick cargo</button>";
		//$aksi2="<button onclick='detail(\"$id\")' class='newButton'>Detail</button>";
		//$aksi2="<a class='button button-orange' onclick='detail(\"$id\")'><i class='fa fa-exclamation-triangle'></i>Watch <strong>out!</strong></a>";
		$responce->rows[$i]['id']=$row[TRX_NUMBER];
		$responce->rows[$i]['cell']=array($row[NOTA_NAME],$row[TRX_NUMBER],$row[TRX_DATE],$row[CUSTOMER_NAME],$row[AMOUNT],$row[TRANSFER_STATUS],$row[TRANSFER_MESSAGE],$row[STATUS_AR],$row[AR_DATE]);
		$i++;
	}
}
echo json_encode($responce);
}
?>