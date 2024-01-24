<?php
$db = getDB();

$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM MST_PELANGGAN_PPN";

$res = $db->query($query)->fetchRow();
$count = $res[NUMBER_OF_ROWS];

$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	
if( $count >0 ) 
{
	$total_pages = ceil($count/$limit);
}
else 
{ 
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)	

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;

$query ="SELECT A.KD_PELANGGAN, A.REMARK, NAMA_PERUSAHAAN, ALAMAT_PERUSAHAAN, NO_NPWP  
FROM MST_PELANGGAN_PPN A
LEFT JOIN MST_PELANGGAN B ON A.KD_PELANGGAN = B.KD_PELANGGAN and B.PELANGGAN_AKTIF='1'";
$res = $db->query($query);
$i=0;
while ($row = $res->fetchRow()) 
{
	$kd_pelanggan=$row['KD_PELANGGAN'];
	$nama_perusahaan=$row['NAMA_PERUSAHAAN'];
	$alamat_perusahaan=$row['ALAMAT_PERUSAHAAN'];	
	$remark=$row['REMARK'];			
	$no_npwp=$row['NO_NPWP'];
	
	$act="<button onclick='del(\"$kd_pelanggan\")'><img src=".HOME."images/del2.png></button>";
	
	$responce->rows[$i]['id']=$row[ID_TRANS];	
	$responce->rows[$i]['cell']=array($act,$kd_pelanggan,$nama_perusahaan,$no_npwp,$alamat_perusahaan,$remark);
	$i++;
}

echo json_encode($responce);

?>