<?php
$awal = $_GET['awal'];
$akhir = $_GET['akhir'];

$page = isset($_POST['page']) ? $_POST['page'] : 1;  // get the requested page
$limit = isset($_POST['rows']) ? $_POST['rows'] : 10; // get how many rows we want to have into the grid
$sidx = isset($_POST['sidx']) ? $_POST['sidx'] : 'id_bprp'; // get index row - i.e. user click to sort
//$sord = $_GET['sord']; // get the direction

if (!$sidx)
    $sidx = 1;
$db = getDB();
$query = "SELECT COUNT(1)AS NUMBER_OF_ROWS FROM (
            select 
    no_request,
    cust_name,
    no_faktur_pajak,
    sign_currency,
    kredit,
    date_paid,
    vessel,
    voyage_in,
    voyage_out,
    bongkar_muat
from tth_nota_all2 where rownum < 10000
and to_char(date_paid, 'yyyymmdd') <= '$akhir' and to_char(date_paid, 'yyyymmdd') >= '$awal' 
and status_nota <> 'X'
)";

$res = $db->query($query)->fetchRow();
$count = $res[NUMBER_OF_ROWS];
//echo $count; 
//die();


if ($count > 0) {
    $total_pages = ceil($count / $limit);
		
} else {
    $total_pages = 0;
}
if ($page > $total_pages)
    $page = $total_pages;
$start = $limit * $page - $limit; // do not put $limit*($page - 1)	

$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;

//Datanya
$query1 = "select 
    no_request,
    cust_name,
    no_faktur_pajak,
    sign_currency,
    kredit,
    date_paid,
    vessel,
    voyage_in,
    voyage_out,
    bongkar_muat
from tth_nota_all2 where rownum < 10000
and to_char(date_paid, 'yyyymmdd') <= '$akhir' and to_char(date_paid, 'yyyymmdd') >= '$awal' 
and status_nota <> 'X'
order by date_paid desc";

$result1 = $db->query($query1);
$baris = $result1->getAll();

//print_r($baris);die;

$i=0;
foreach ($baris as $rownya) {
    //if ($rownya[TYPE_] == ''){}
    //else{    
    
    $responce->rows[$i]['id'] = $rownya2[NO_REQUEST];
    $responce->rows[$i]['cell'] = array(
        $rownya[NO_REQUEST],
        $rownya[CUST_NAME],
        $rownya[NO_FAKTUR_PAJAK],
        $rownya[SIGN_CURRENCY],
        $rownya[KREDIT],
        $rownya[DATE_PAID],
        $rownya[VESSEL],
        $rownya[VOYAGE_IN],
        $rownya[VOYAGE_OUT], 
        $rownya[BONGKAR_MUAT]         
        );
    
    $i++;   
    //}
}

echo json_encode($responce);
die();
?>