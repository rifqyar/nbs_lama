<?php
$tgl = date('d F Y H:i');
$tanggal = date("dmY");
$trxdate = $_GET['trxdate'];
$kg = $_GET['keg'];

$db = getDB();

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=LH_NBS_LUNAS_" . $trxdate . ".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>

<?php
	/*ipctpk*/
	$qnama = "select nm_perusahaan from date_reference where cut_date <= to_date('$trxdate','dd/mm/rrrr') order by cut_date desc";
	$rnama = $db->query($qnama)->fetchRow();
	$corporate_name = $rnama["NM_PERUSAHAAN"];
	/**/
?>

<table>
<tr>
	<!--ipctpk
	<td colspan=6 align="left">PT. PELABUHAN INDONESIA II (Persero)</td>-->
	<td colspan=6 align="left"><?=$corporate_name;?></td>
	<td colspan=5 align="right">Tgl Proses : <?=date('d-M-Y H:i');?></td>
</tr>
<tr>
	<!--ipctpk
	<td colspan=6 align="left">CABANG TANJUNG PRIOK</td>-->
	<td colspan=6 align="left"></td>
	
</tr>

			<tr>
				<th colspan=11>
					<i>Laporan Harian Penerbitan Nota NBS BANK LUNAS</i>
				</th>
				</tr>
				<tr>
				<th colspan=11>
					TERMINAL OPERASI III
				</th>
				</tr>
		</table>
		<br>
		<table border="1">
		  <tr>
			<th>No.</th>
			<th>Invoice</th>
			<th>No. Dokumen</th>
			<th>Customer</th>
			<th>Curr</th>
			<th>Tgl Lunas</th>
			<th>Layanan</th>
			<th>No. Pajak</th>
			<th>Pendapatan</th>
			<th>PPN</th>
			<th>Total</th>
			
		  </tr>
                <!--  -->
<?php
		if($kg=='ALL')
        {
            $keg ="";
        }
        else
        {
            $keg ="AND a.kd_modul='".$kg."'";
        }
		$query ="select NO_NOTA, NO_REQUEST, CUST_NAME, SIGN_CURRENCY,DATE_PAID,DESCRIPTION, NO_FAKTUR_PAJAK,
TO_CHAR(TOTAL,'9,999,999,999,999') TOTAL , TO_CHAR(PPN,'9,999,999,999,999') PPN, TO_CHAR(KREDIT,'9,999,999,999,999') KREDIT  from tth_nota_all2 a join master_modul_simkeu b on a.kd_modul=b.DESCRIPTIVE_FLEX_CONTEXT_CODE WHERE TO_CHAR(DATE_PAID,'DD-MM-YYYY')='$trxdate' ".$keg." ORDER BY KD_MODUL ASC";

//print_r($query);die;
$result_h = $db->query($query);
$res = $result_h->getAll();
$i = 1;
?>
<?php foreach ($res as $row) {
    ?>
                    <tr>

                        <td><?=$i ?></td>
                        <td><?=$row['NO_NOTA'] ?></td>
                        <td><?=$row['NO_REQUEST'] ?></td>
                        <td><?=$row['CUST_NAME'] ?></td>
                        <td><?=$row['SIGN_CURRENCY'] ?></td>
                        <td><?=$row['DATE_PAID'] ?></td>
                        <td><?=$row['DESCRIPTION'] ?></td>
                        <td><?=$row['NO_FAKTUR_PAJAK'] ?></td>
                        <td><?=$row['TOTAL'] ?></td>
                        <td><?=$row['PPN'] ?></td>
                        <td><?=$row['KREDIT'] ?></td>
                        
                    </tr>
    <?php
    $i++;
}
?>
</table>