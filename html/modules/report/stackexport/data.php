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
            SELECT   
                date_paid,
                administrasi,
                kd_permintaan, size_, 
                type_,status_, qty, 
                SUM(KEBERSIHAN) AS KEBERSIHAN,               
                sum(MONITORING_LISTRIK ) AS MONITORING_LISTRIK,
                sum(PENUMPUKAN_MASA_I1) AS PENUMPUKAN_MASA_I1,
                sum(PENUMPUKAN_MASA_I2) AS PENUMPUKAN_MASA_I2,    
                sum(PENUMPUKAN_MASA_II) AS PENUMPUKAN_MASA_II ,
                sum(EARLY_STACKING) AS EARLY_STACKING,
                sum(LIFT_OFF) AS LIFT_OFF,                  
                SUM(TOTHARI) AS TOTHARI ,
                MIN(TGL_AWAL) TGL_AWAL,
                MAX(TGL_AKHIR) TGL_AKHIR
FROM (
            SELECT
                    b.date_paid,
                    b.administrasi, 
                    kd_permintaan,
                    SIZE_,
                    TYPE_,
                    STATUS_,
                    QTY,
                    TOTHARI,
                    TGL_AWAL,
                    TGL_AKHIR,
                    case when uraian = 'KEBERSIHAN' then TOTTARIF end as KEBERSIHAN,                   
                    case when uraian = 'MONITORING DAN LISTRIK' then TOTTARIF end as MONITORING_LISTRIK,
                    case when uraian = 'PENUMPUKAN MASA I.1' then TOTTARIF end as PENUMPUKAN_MASA_I1,
                    case when uraian = 'PENUMPUKAN MASA I.2' then TOTTARIF end as PENUMPUKAN_MASA_I2,    
                    case when uraian = 'PENUMPUKAN MASA II' then TOTTARIF end as PENUMPUKAN_MASA_II,
                    case when uraian = 'EARLY_STACKING' then TOTTARIF end as EARLY_STACKING,
                    case when uraian = 'LIFT OFF' then TOTTARIF end as LIFT_OFF                    
             FROM 
                    ttr_nota_all a left join tth_nota_all2 b  on (a.kd_permintaan= b.no_request)
             WHERE 
                    kd_permintaan like '%A%' 
                    and to_char(date_paid, 'yyyymmdd') <= '$akhir' and to_char(date_paid, 'yyyymmdd') >= '$awal'      
                     ) 
group by 
         kd_permintaan, size_, type_,status_, qty, date_paid, administrasi)";

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
$query1 = "SELECT
                vessel, 
                voyage_in, 
                voyage_out,
                no_nota,
                date_paid,
                cust_name,
                administrasi,
                kd_permintaan, size_, 
                type_,status_, qty, 
                SUM(KEBERSIHAN) AS KEBERSIHAN,               
                sum(MONITORING_LISTRIK ) AS MONITORING_LISTRIK,
                sum(PENUMPUKAN_MASA_I1) AS PENUMPUKAN_MASA_I1,
                sum(PENUMPUKAN_MASA_I2) AS PENUMPUKAN_MASA_I2,    
                sum(PENUMPUKAN_MASA_II) AS PENUMPUKAN_MASA_II ,
                sum(EARLY_STACKING) AS EARLY_STACKING,
                sum(LIFT_OFF) AS LIFT_OFF,                
                SUM(TOTHARI) AS TOTHARI ,
                MIN(TGL_AWAL) TGL_AWAL,
                MAX(TGL_AKHIR) TGL_AKHIR
FROM (
            select
                    b.vessel,
                    b.voyage_in, 
                    b.voyage_out,
                    b.no_nota,
                    b.date_paid,
                    b.cust_name,
                    b.administrasi, 
                    kd_permintaan,
                    SIZE_,
                    TYPE_,
                    STATUS_,
                    QTY,
                    TOTHARI,
                    TGL_AWAL,
                    TGL_AKHIR,
                    case when uraian = 'KEBERSIHAN' then TOTTARIF end as KEBERSIHAN,                   
                    case when uraian = 'MONITORING DAN LISTRIK' then TOTTARIF end as MONITORING_LISTRIK,
                    case when uraian = 'PENUMPUKAN MASA I.1' then TOTTARIF end as PENUMPUKAN_MASA_I1,
                    case when uraian = 'PENUMPUKAN MASA I.2' then TOTTARIF end as PENUMPUKAN_MASA_I2,    
                    case when uraian = 'PENUMPUKAN MASA II' then TOTTARIF end as PENUMPUKAN_MASA_II,
                    case when uraian = 'EARLY STACKING' then TOTTARIF end as EARLY_STACKING,
                    case when uraian = 'LIFT OFF' then TOTTARIF end as LIFT_OFF                    
             from 
                    ttr_nota_all a left join tth_nota_all2 b  on (a.kd_permintaan= b.no_request)
             where 
                    kd_permintaan like '%A%'
                    and to_char(date_paid, 'yyyymmdd') <= '$akhir' and to_char(date_paid, 'yyyymmdd') >= '$awal'      
                     ) 
group by 
         kd_permintaan, size_, type_,status_, qty, date_paid, administrasi, no_nota, cust_name, vessel, voyage_in, voyage_out";

$result1 = $db->query($query1);
$baris = $result1->getAll();

$i=0;
foreach ($baris as $rownya) {
    if ($rownya[TYPE_] == ''){}
    else{    
    
    $responce->rows[$i]['id'] = $rownya2[NO_REQUEST];
    $responce->rows[$i]['cell'] = array(
        $rownya[KD_PERMINTAAN],
        $rownya[NO_NOTA],
        $rownya[DATE_PAID],
        $rownya[CUST_NAME],
        $rownya[VESSEL],
        $rownya[VOYAGE_IN],
        $rownya[VOYAGE_OUT],
        $rownya[ADMINISTRASI],
        $rownya[SIZE_], 
        $rownya[TYPE_], 
        $rownya[STATUS_],
        $rownya[QTY], 
        $rownya[TOTHARI], 
        $rownya[TGL_AWAL], 
        $rownya[TGL_AKHIR], 
        $rownya[KEBERSIHAN],      
        $rownya[MONITORING_LISTRIK],
        $rownya[PENUMPUKAN_MASA_I1],
        $rownya[PENUMPUKAN_MASA_I2],    
        $rownya[PENUMPUKAN_MASA_II],
        $rownya[EARLY_STACKING],
        $rownya[LIFT_OFF]          
        );
    
    $i++;   
    }
}

echo json_encode($responce);
die();
?>