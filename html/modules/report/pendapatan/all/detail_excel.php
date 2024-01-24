<?php 
$awal = $_GET['awal'];
$akhir = $_GET['akhir'];

$db = getDB();

$query1 = "
SELECT 
    NO_FAKTUR_PAJAK,
    NO_REQUEST,
    CUST_NAME,
    CASE KD_MODUL
    WHEN 'PTKM00' then 'RECEIVING'
    WHEN 'PTKM01' then 'DELIVERY'
    WHEN 'PTKM06' then 'PERPANJANGAN RECEIVING'
    WHEN 'PTKM07' then 'PERPANJANGAN DELIVERY'
    ELSE 'UNDEFINED'
    END KEGIATAN,
    'BANK' PEMBAYARAN,
    KREDIT TOTAL_TAGIHAN,
    'LUNAS' STATUS_LUNAS,
    'TIDAK' STATUS_BATAL,
    case STATUS_TRANSFER
    WHEN '0' THEN 'BELUM TRANSFER'
    WHEN '1' THEN 'SUDAH TRANSFER'
    END TRANSFER,
    RECEIPT_ACCOUNT BANK
from 
tth_nota_all2
where
	to_char(date_paid, 'yyyymmdd') <= '$akhir' and to_char(date_paid, 'yyyymmdd') >= '$awal'
	and status_nota <> 'X'
order by date_paid desc";

$result1 = $db->query($query1);
$baris = $result1->getAll();

?>

<?php 
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=pendapatan.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<h1> Laporan Pendaptan Receiving</h1>
<table border="1">
    <tr bgcolor="#6699FF">
	<th>NO FAKTUR PAJAK</th>
	<th>NO REQUEST</th>
    <th>CUSTOMER</th>
    <th>KEGIATAN</th>
    <th>PEMBAYARAN</th>
    <th>TOTAL TAGIHAN</th>
    <th>STATUS LUNAS</th>
    <th>STATUS BATAL</th>
    <th>TRANSFER</th>
    <th>BANK</th>
    </tr>
    
    <?php foreach ($baris as $rownya) 
        //if ($rownya[TYPE_] == ''){}
    //else
	{?>
    
    <tr>
        <td style="text-align: left"><?=$rownya[NO_FAKTUR_PAJAK]?></td>
        <td style="text-align: left"><?=$rownya[NO_REQUEST]?></td>
        <td style="text-align: left"><?=$rownya[CUST_NAME]?></td>
        <td style="text-align: left"><?=$rownya[KEGIATAN]?></td>
        <td style="text-align: left"><?=$rownya[PEMBAYARAN]?></td>
        <td style="text-align: left"><?=$rownya[TOTAL_TAGIHAN]?></td>
        <td style="text-align: left"><?=$rownya[STATUS_LUNAS]?></td>
        <td style="text-align: left"><?=$rownya[STATUS_BATAL]?></td>
        <td style="text-align: left"><?=$rownya[TRANSFER]?></td>
        <td style="text-align: right"><?=$rownya[BANK]?></td>   
    </tr>
    <?php }?>
    
</table>


<?php die(); ?>