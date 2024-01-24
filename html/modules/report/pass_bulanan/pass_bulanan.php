<?php 
outputRaw();
$db = getDB();
$periode_awal = $_GET[periode_awal];

//echo $status;
//echo $periode_awal;
//echo $periode_akhir;
//die();


$query = "select kd_permintaan, qty,uraian, tipe_layanan,date_paid,tanggal_bayar
from(
select 
                a.kd_permintaan,
                a.qty, 
                a.uraian,
                 CASE WHEN a.tipe_layanan = 'DELIVERY PASS_TRUCK' THEN 'DELIVERY'
                     WHEN a.tipe_layanan = 'RECEIVING PASS_TRUCK' THEN 'RECEIVING'
                     WHEN a.tipe_layanan = 'BATAL MUAT PASS_TRUCK' THEN 'BATAL MUAT'
                END AS tipe_layanan,
                b.date_paid,
                to_char(b.date_paid, 'yyyymmdd hh24:mi:ss') as tanggal_bayar 
        from 
                ttr_nota_all a left join tth_nota_all2 b 
                on (a.kd_permintaan = b.no_request)
        where 
                a.uraian = 'PASS TRUCK')
where tanggal_bayar like '%$periode_awal%'";
				
$res = $db->query($query); ?>

<div style="margin:20 20 20 20">

<h1>Laporan Pass Bulanan <?php echo $periode_awal;?></h1>
    <table class="table_alumni">
	<tr>
	<th>No</th>
	<th>Tanggal Lunas</th>
	<th>Kegiatan</th>
	<th>No Request</th>
	<th>Jumlah Pass</th>	
	</tr>
</div>
<?php 
$c=1;
while ($row = $res->fetchRow()){?>
<tr>
	<td class="td-tengah"><?=$c++;?></td>
	<td class="td-kiri"><?=$row[TANGGAL_BAYAR];?></td>
	<td class="td-kiri"><?=$row[TIPE_LAYANAN];?></td>
	<td class="td-kiri"><?=$row[KD_PERMINTAAN];?></td>
	<td class="td-kiri"><?=$row[QTY];?></td>
		
</tr>
<?php } ?>

</table>

<br></br>
<h1>SUMMARY</h1>
<?php 

//query untuk total
$query_total = "select 
						sum(qty) as jumlahnya
				from(
						select 
								a.kd_permintaan,
								a.qty, 
								a.uraian,
								CASE WHEN a.tipe_layanan = 'DELIVERY PASS_TRUCK' THEN 'DELIVERY'
									 WHEN a.tipe_layanan = 'RECEIVING PASS_TRUCK' THEN 'RECEIVING'
									 WHEN a.tipe_layanan = 'BATAL MUAT PASS_TRUCK' THEN 'BATAL MUAT'
								END AS tipe_layanan, 
								b.date_paid,
								to_char(b.date_paid, 'yyyymmddhh24miss') as tanggal_bayar 
						from 
								ttr_nota_all a left join tth_nota_all2 b 
								on (a.kd_permintaan = b.no_request)
						where 
								a.uraian = 'PASS TRUCK')
				where 
						tanggal_bayar like '%$periode_awal%'";
						
$result_total = $db->query($query_total);
$total = $result_total->FetchRow();
$total_box = $total[JUMLAHNYA];
//echo "jumlah box".$total_box;


$query_resume = "select sum(qty) as jumlah, tipe_layanan
from(
select 
                a.kd_permintaan,
                a.qty, 
                a.uraian,
                CASE WHEN a.tipe_layanan = 'DELIVERY PASS_TRUCK' THEN 'DELIVERY'
                     WHEN a.tipe_layanan = 'RECEIVING PASS_TRUCK' THEN 'RECEIVING'
                     WHEN a.tipe_layanan = 'BATAL MUAT PASS_TRUCK' THEN 'BATAL MUAT'
                END AS tipe_layanan, 
                b.date_paid,
                to_char(b.date_paid, 'yyyymmddhh24miss') as tanggal_bayar 
        from 
                ttr_nota_all a left join tth_nota_all2 b 
                on (a.kd_permintaan = b.no_request)
        where 
                a.uraian = 'PASS TRUCK')
where tanggal_bayar like '%$periode_awal%'
group by tipe_layanan";

$resume = $db->query($query_resume); ?>

<table class="table_alumni">
	<tr>
	<th>No</th>
	<th>KEGIATAN</th>
	<th>JUMLAH</th>		
	</tr>
<?php 
$d=1;
while ($resum = $resume->fetchRow()){?>
<tr>
	<td class="td-tengah"><?=$d++;?></td>
	<td class="td-kiri"><?=$resum[TIPE_LAYANAN];?></td>
	<td class="td-kiri"><?=$resum[JUMLAH];?></td>		
</tr>

<?php } ?>
<tr>
<td></td>
<td></td>
<td class="td-kiri"><?php echo $total_box; ?></td>

</tr>

</table>

<?php 

?>



