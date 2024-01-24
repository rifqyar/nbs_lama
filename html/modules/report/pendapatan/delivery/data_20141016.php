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
$query = "select count(*) as NUMBER_OF_ROWS from (select a.kd_permintaan, a.uraian, a.tottarif, b.date_paid, to_char(date_paid, 'yyyymmdd') as tgl_bayar from ttr_nota_all a left join tth_nota_all2 b on (a.kd_permintaan = b.no_request and a.kd_uper = b.no_nota))
where tgl_bayar < '$akhir' and tgl_bayar > '$awal'";

//echo $query;
$res = $db->query($query)->fetchRow();
$count = $res[NUMBER_OF_ROWS];


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
$i = 0;


$query1 = "SELECT 
                * 
           FROM 
                (SELECT 
                        a.kd_permintaan, 
                        a.uraian, 
                        SUM(a.tottarif) TOTTARIF, 
                        b.date_paid, 
                        to_char(date_paid, 'yyyymmdd') as tgl_bayar, 
                        SUM(tothari) TOTHARI
                 FROM 
                        ttr_nota_all a left join tth_nota_all2 b 
                        on (TRIM(a.kd_permintaan) = TRIM(b.no_request) and TRIM(a.kd_uper) = TRIM(b.no_nota))
                 WHERE                         
                        (TRIM(no_request) like 'REQ%' or TRIM(no_request) like 'SP2%')                        
                 GROUP BY 
                        KD_PERMINTAAN, URAIAN, DATE_PAID)
            WHERE 
                tgl_bayar < '$akhir' and tgl_bayar > '$awal'";
//echo $query1;
//DIE();

$result1 = $db->query($query1);
$row = $result1->getAll();

//print_r($row);
//die();

foreach ($row as $rownya) {
    $datanya[$rownya[KD_PERMINTAAN]][$rownya[URAIAN]] = $rownya[TOTTARIF];
    $datanya2[$rownya[KD_PERMINTAAN]] = $datanya2[$rownya[KD_PERMINTAAN]] + $rownya[TOTHARI];
    
    
}

//print_r ($datanya2);
//die();
$query2 = " select 
                * 
            from 
                (select 
                        no_request, 
                        no_nota, 
                        cust_name, 
                        cust_npwp, 
                        total, 
                        ppn, 
                        kredit, 
                        vessel, 
                        voyage_in, 
                        voyage_out,
                        date_paid,						
                        to_char(date_paid, 'yyyymmdd') as tgl_bayar
                        
                FROM 
                        tth_nota_all2
                WHERE 
                        (no_request like 'REQ%' or no_request like 'SP2%'))
            where 
                        tgl_bayar < '$akhir' and tgl_bayar > '$awal'
			 
                ";

$result2 = $db->query($query2);
$row2 = $result2->getAll();

$i=0;
foreach ($row2 as $rownya2) {
    $responce->rows[$i]['id'] = $rownya2[NO_REQUEST];
    $responce->rows[$i]['cell'] = array(
        $rownya2[NO_REQUEST], 
        $rownya2[NO_NOTA], 
        $rownya2[CUST_NAME],        
        $rownya2[TOTAL],         
        $rownya2[KREDIT], 
        $rownya2[VESSEL], 
        $rownya2[VOYAGE_IN],
        $rownya2[VOYAGE_OUT], 
        $rownya2[DATE_PAID],
        $datanya2[$rownya2[NO_REQUEST]],
        $datanya[$rownya2[NO_REQUEST]]['PENUMPUKAN MASA I.2'], 
        $datanya[$rownya2[NO_REQUEST]]['PENUMPUKAN MASA II'],
        
        $datanya[$rownya2[NO_REQUEST]]['TAMB. SP2 MASA I.2'],        
        $datanya[$rownya2[NO_REQUEST]]['TAMB. SP2 MASA II'],      
        $datanya[$rownya2[NO_REQUEST]]['TAMB. SPPB MASA I.2'],
         $datanya[$rownya2[NO_REQUEST]]['TAMB. SPPB MASA II'],
        
        $datanya[$rownya2[NO_REQUEST]]['KEBERSIHAN'],  
        $datanya[$rownya2[NO_REQUEST]]['MONITORING DAN LISTRIK'],       
       
        $datanya[$rownya2[NO_REQUEST]]['LIFT ON'],
        $datanya[$rownya2[NO_REQUEST]]['ADM'], 
               
        
    );
    $i++;    
}

echo json_encode($responce);
die();
?>