<?php 
$awal = $_GET['awal'];
$akhir = $_GET['akhir'];

$db = getDB();

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
                sum(PENUMPUKAN_MASA_I1) AS PENUMPUKAN_MASAI1,
                sum(PENUMPUKAN_MASA_I2) AS PENUMPUKAN_MASA_I2,    
                sum(PENUMPUKAN_MASA_II) AS PENUMPUKAN_MASA_II,
                sum(BATAL_MUAT) AS BATAL_MUAT, 
                sum(BATAL_DOKUMEN) AS BATAL_DOKUMEN,
                sum(LIFT_ON ) AS LIFT_ON,              
                SUM(TOTHARI) AS TOTHARI,
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
                    case when uraian = 'PENUMPUKAN MASA I.1' then TOTTARIF end as PENUMPUKAN_MASA_I1,  
                    case when uraian = 'PENUMPUKAN MASA I.2' then TOTTARIF end as PENUMPUKAN_MASA_I2,    
                    case when uraian = 'PENUMPUKAN MASA II' then TOTTARIF end as PENUMPUKAN_MASA_II,                    
                    case when uraian = 'BATAL MUAT' then TOTTARIF end as BATAL_MUAT, 
                    case when uraian = 'BATAL DOKUMEN' then TOTTARIF end as BATAL_DOKUMEN,
                    case when uraian = 'LIFT ON' then TOTTARIF end as LIFT_ON                 
             FROM 
                    ttr_nota_all a left join tth_nota_all2 b  on (a.kd_permintaan= b.no_request)
             WHERE 
                    kd_permintaan like '%BM%'
                    and to_char(date_paid, 'yyyymmdd') <= '$akhir' and to_char(date_paid, 'yyyymmdd') >= '$awal'             
             ) 
group by 
         kd_permintaan, size_, type_,status_, qty, date_paid, administrasi, no_nota, cust_name, vessel, voyage_in, voyage_out 
ORDER BY 
        kd_permintaan asc";

$result1 = $db->query($query1);
$baris = $result1->getAll();
?>

<?php 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=pendapatan_batalmuat.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<h1>Laporan Pendapatan Batal Muat</h1>
<table border="1">
    <tr bgcolor="#6699FF">
    <th>No Request</th>
    <th>No Nota</th>
    <th>Tgl Bayar</th>
    <th>Cust Name</th>
    <th>Vessel</th>
    <th>Voyage In</th>
    <th>Voyage Out</th>
    <th>Administrasi</th>
    <th>Size</th>
    <th>Type</th>
    <th>Status</th>
    <th>Qty</th>
    <th>Tot Hari</th>
    <th>Tgl Awal</th>
    <th>Tgl Akhir</th>   
    <th>Pen Masa I.1</th>
    <th>Pen Masa I.2</th>    
    <th>Pen Masa II</th>
    <th>Batal Muat</th>
    <th>Batal Dokumen</th>
    <th>Lift On</th>    
    </tr>
    
    <?php foreach ($baris as $rownya) 
        if ($rownya[TYPE_] == ''){}
    else{?>
    
    <tr>
        <td style="text-align: left"><?=$rownya[KD_PERMINTAAN]?></td>
        <td style="text-align: left"><?=$rownya[NO_NOTA]?></td>
        <td style="text-align: left"><?=$rownya[DATE_PAID]?></td>
        <td style="text-align: left"><?=$rownya[CUST_NAME]?></td>
        <td style="text-align: left"><?=$rownya[VESSEL]?></td>
        <td style="text-align: left"><?=$rownya[VOYAGE_IN]?></td>
        <td style="text-align: left"><?=$rownya[VOYAGE_OUT]?></td>
        <td style="text-align: right"><?=$rownya[ADMINISTRASI]?></td>
        <td><?=$rownya[SIZE_]?></td>
        <td><?=$rownya[TYPE_]?></td>
        <td><?=$rownya[STATUS_]?></td>
        <td><?=$rownya[QTY]?></td>        
        <td><?=$rownya[TOTHARI]?></td>
        <td><?=$rownya[TGL_AWAL]?></td>
        <td><?=$rownya[TGL_AKHIR]?></td>        
        <td><?=$rownya[PENUMPUKAN_MASA_I1]?></td>
        <td><?=$rownya[PENUMPUKAN_MASA_I2]?></td>        
        <td><?=$rownya[PENUMPUKAN_MASA_II]?></td>        
        <td><?=$rownya[BATAL_MUAT]?></td>
        <td><?=$rownya[BATAL_DOKUMEN]?></td>        
        <td><?=$rownya[LIFT_ON]?></td>       
    </tr>
    <?php }?>
    
</table>


<?php die(); ?>