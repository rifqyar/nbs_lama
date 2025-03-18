<?php
outputRaw();
$filename = "PYMA_STAGING_USTER_PONTIANAK.XLS";
header("Content-type: application/xls");
header("Content-Disposition: filename=$filename");


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

//barang_prod = $_SESSION['_siteconf']['name'];
/*$KD_CAB = $_SESSION['SESS_KD_CABANG'];
$QORGID = "SELECT ORG_ID FROM KAPAL_PROD.MST_ORG_ID WHERE KD_CABANG='$KD_CAB'";
$R = $db->query($QORGID)->fetchRow();
$ORG_ID = $R[ORG_ID];*/
$YEAR = $_GET['year'];
$COL  = $_GET['col'];
$KEY  = strtoupper($_GET['key']);
if($COL!='' && $KEY!=''){
	$querysum = "select a.nota_name as nota_name, currency, 
	(select count(distinct(b.trx_number)) 
	from uster.pyma_staging b where b.nota_name = a.nota_name 
	and b.confirmation_status = 'Y' and b.trx_number IS NOT NULL and upper(b.$COL) like '%$KEY%') as count_yes, 
	(select nvl(sum(c.amount),0) 
	from uster.pyma_staging c where c.nota_name = a.nota_name 
	and c.confirmation_status = 'Y' and c.trx_number IS NOT NULL and upper(c.$COL) like '%$KEY%') as amount_yes,
	(select count(distinct(d.trx_number)) 
	from uster.pyma_staging d where d.nota_name = a.nota_name 
	and d.confirmation_status = 'X' and d.trx_number IS NOT NULL and upper(d.$COL) like '%$KEY%') as count_no,
	(select nvl(sum(f.amount),0) 
	from uster.pyma_staging f where f.nota_name = a.nota_name 
	and f.confirmation_status = 'X' and f.trx_number IS NOT NULL and upper(f.$COL) like '%$KEY%') as amount_no
	from uster.pyma_staging a where a.trx_number IS NOT NULL 
	and a.org_id in $ORG_ID and upper(a.$COL) like '%$KEY%' group by a.nota_name, a.currency order by a.nota_name asc
	";
	$querydata="select nota_name,trx_number,trx_date,customer_name,currency,confirmation_status,amount,service_type,line_desc
	from uster.PYMA_STAGING where TRX_NUMBER IS NOT NULL and upper($COL) like '%$KEY%' and org_id in $ORG_ID
	order by nota_name,trx_number,trx_date";
	//var_dump($querysum);exit;
	}elseif($YEAR!=''){
	$querysum  = "select a.nota_name as nota_name, currency, 
	(select count(distinct(b.trx_number)) 
	from uster.pyma_staging b where b.nota_name = a.nota_name 
	and b.confirmation_status = 'Y' and b.trx_number IS NOT NULL and to_char(b.trx_date,'yyyy')='$YEAR') as count_yes, 
	(select nvl(sum(c.amount),0) 
	from uster.pyma_staging c where c.nota_name = a.nota_name 
	and c.confirmation_status = 'Y' and c.trx_number IS NOT NULL and to_char(c.trx_date,'yyyy')='$YEAR') as amount_yes,
	(select count(distinct(d.trx_number)) 
	from uster.pyma_staging d where d.nota_name = a.nota_name 
	and d.confirmation_status = 'X' and d.trx_number IS NOT NULL and to_char(d.trx_date,'yyyy')='$YEAR') as count_no,
	(select nvl(sum(f.amount),0) 
	from uster.pyma_staging f where f.nota_name = a.nota_name 
	and f.confirmation_status = 'X' and f.trx_number IS NOT NULL and to_char(f.trx_date,'yyyy')='$YEAR') as amount_no
	from uster.pyma_staging a where a.trx_number IS NOT NULL 
	and a.org_id in $ORG_ID and to_char(a.trx_date,'yyyy')='$YEAR' group by a.nota_name, a.currency order by a.nota_name asc
	";
	$querydata="select nota_name,trx_number,trx_date,customer_name,currency,confirmation_status,amount,service_type,line_desc
	from uster.PYMA_STAGING where TRX_NUMBER IS NOT NULL and to_char(trx_date,'yyyy') = '$YEAR' and org_id in $ORG_ID
	order by nota_name,trx_number,trx_date";
	//var_dump($querysum);exit;
}else{
	die('No Data To Be Populated');
}
$rsum = $db->query($querysum);
$getsum = $rsum->getAll();
$res = $db->query($querydata);
$getas = $res->getAll(); 
?>
<body style="font-family:segoe ui">
<h1>PYMA STAGING USTER PONTIANAK</h1>
<table border="1" borderspacing=0 borderpadding=0>
<tr style="background:#2868AB;color:white;font-size:10pt">
	<th style="padding:5px" rowspan=2>No</th>
	<th style="padding:5px;width:200px" rowspan=2>Kegiatan</th>
	<th style="padding:5px;width:100px" rowspan=2>Currency</th>
	<th style="padding:5px" colspan=2>PYMAD</th>
	<th style="padding:5px" colspan=2>NON PYMAD</th>
</tr>
<tr style="background:#2868AB;color:white;font-size:10pt">
	<th style="padding:5px;width:100px">Quantity</th>
	<th style="padding:5px;width:100px">Amount</th>
	<th style="padding:5px;width:100px">Quantity</th>
	<th style="padding:5px;width:100px">Amount</th>
</tr>
<?php $n = 1; foreach($getsum as $rsum){?>
	<tr>
		<td><?php echo $n;?></td>
		<td><?php echo $rsum['NOTA_NAME'];?></td>
		<td><?php echo $rsum['CURRENCY'];?></td>
		<td><?php echo $rsum['COUNT_YES'];?></td>
		<td><?php echo $rsum['AMOUNT_YES'];?></td>
		<td><?php echo $rsum['COUNT_NO'];?></td>
		<td><?php echo $rsum['AMOUNT_NO'];?></td>
	</tr>
<?php $n++; } ?>
</table>
<br>
<table border="1" borderspacing=0 borderpadding=0>
<tr style="background:#2868AB;color:white">
	<th style="padding:5px">No</th>
	<th style="padding:5px;width:200px">Nama Nota</th>
	<th style="padding:5px;width:100px">No Nota</th>
	<th style="padding:5px;width:100px">Tgl Nota</th>
	<th style="padding:5px;width:230px">Nama Pelanggan</th>
	<th style="padding:5px;width:80px">Currency</th>
	<th style="padding:5px;width:100px">Amount</th>
	<th style="padding:5px;width:80px">Status PYMA</th>
	<th style="padding:5px;width:150px">Jenis Layanan</th>
	<th style="padding:5px;width:150px">Keterangan</th>
</tr>
	<?php
	$no = 1;
	foreach($getas as $rows){
	?>
	<?php 
	if($rows['CONFIRMATION_STATUS']=='X'){?>
	<tr style="background:pink">
	<?php }else{ ?>
	<tr>
	<?php } ?>
	<td style="padding:3px"><?php echo $no;?></td>
	<td style="padding:3px"><?php echo $rows['NOTA_NAME'];?></td>
	<td style="padding:3px"><?php echo "'".$rows['TRX_NUMBER'];?></td>
	<td style="padding:3px"><?php echo $rows['TRX_DATE'];?></td>
	<td style="padding:3px"><?php echo $rows['CUSTOMER_NAME'];?></td>
	<td style="padding:3px"><?php echo $rows['CURRENCY'];?></td>
	<td style="padding:3px"><?php echo $rows['AMOUNT'];?></td>
	<td style="padding:3px"><?php echo $rows['CONFIRMATION_STATUS'];?></td>
	<td style="padding:3px"><?php echo $rows['SERVICE_TYPE'];?></td>
	<td style="padding:3px"><?php echo $rows['LINE_DESC'];?></td>
	</tr>
<?php $no++; } ?>
</table>
</body>