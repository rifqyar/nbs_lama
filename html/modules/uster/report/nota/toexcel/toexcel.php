<?php
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=ReportNota-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

	$db 	= getDB("storage");
	
    $tgl_awal			= $_GET["tgl_awal"]; 
	$tgl_akhir			= $_GET["tgl_akhir"]; 
	$status_pembayaran	= $_GET["status_pembayaran"]; 
	$status_pelunasan	= $_GET["status_pelunasan"];
	$id_emkl			= $_GET["id_emkl"]; 
	$status_transfer	= $_GET["status_transfer"]; 
	$status_nota		= $_GET["status_nota"];
	//echo $tgl_awal;die;
	
	if($status_pembayaran == 1){
		$ket = 'LUNAS';
	} else 	if($status_pembayaran == 2){
		$ket = 'BELUM LUNAS';
	} else {
		$ket = 'BATAL NOTA';
	}
	 
	if ($status_pembayaran == NULL){
		$query_status_pembayaran = '';
	} else {
		if ($status_pembayaran == 1){
			$query_status_pembayaran = "and lunas = 'YES' and status_nota not in ('BATAL')";
		} else if ($status_pembayaran == 2){
			$query_status_pembayaran = "and lunas = 'NO' and status_nota not in ('BATAL')";
		} else if ($status_pembayaran == 3){
			$query_status_pembayaran = "and status_nota = 'BATAL'";
		} else {
			$query_status_pembayaran = '';
		}
	}
	
	if ($status_pelunasan == NULL){
		$query_status_pelunasan = '';
	} else {
		if ($status_pelunasan == 1){
			$query_status_pelunasan = "and bayar = 'BANK'";
			$lunas = 'BANK';
		} else if ($status_pelunasan == 2){
			$query_status_pelunasan = "and bayar = 'CASH'";
			$lunas = 'CASH';
		} else if ($status_pelunasan == 3){
			$query_status_pelunasan = "and bayar = 'AUTODB'";
			$lunas = 'AUTO DB';
		} else if ($status_pelunasan == 4){
			$query_status_pelunasan = "and bayar = 'DEPOSIT'";
			$lunas = 'DEPOSIT';
		} else {
			$query_status_pelunasan = '';
		}
	}
	
	if ($id_emkl == NULL){
		$query_id_emkl = '';
	} else {
		$query_id_emkl = "and NOMOR_ACCOUNT_PELANGGAN = '$id_emkl'";
	}
	
	if ($status_transfer == NULL){
		$query_status_transfer = '';
	} else {
		if ($status_transfer == 1){
			$query_status_transfer = "and simkeu_proses = 'S'";
		} else if ($status_transfer == 2){
			$query_status_transfer = "and simkeu_proses <> 'S' ";
			$query_2 = " and simkeu_proses IS NULL ";
		} else {
			$query_status_transfer = '';
		}
	}
	
	if ($status_nota == NULL){
		$query_status_nota = '';
	} else {
		if ($status_nota == 1){
			$query_status_nota = "and ready_transfer = 0";
		} else if ($status_nota == 2){
			$query_status_nota = "and ready_transfer <> 0";
		} else {
			$query_status_nota = '';
		}
	}
	//echo $tgl_awal;die;
	
	
	if ($status_transfer <> 2){
		$query_list_ 	= "SELECT LAP_KEUANGAN.*, TO_CHAR(TAGIHAN,'999,999,999,999') TAGIHAN_, TO_CHAR(PPN,'999,999,999,999') PPN_, TO_CHAR(TOTAL_TAGIHAN,'999,999,999,999') TOTAL_TAGIHAN_ FROM LAP_KEUANGAN WHERE
                        to_date(TGL_NOTA,'dd/mm/rrrr') between to_date('$tgl_awal', 'dd/mm/rrrr') and to_date('$tgl_akhir', 'dd/mm/rrrr') ". $query_status_pembayaran.' '.$query_status_pelunasan.' '.$query_id_emkl. ' ' .$query_status_transfer . ' ' . $query_status_nota."
                         order by TGL_NOTA desc";
	} else {
		$query_list_ 	= "select * from (SELECT LAP_KEUANGAN.*, TO_CHAR(TAGIHAN,'999,999,999,999') TAGIHAN_, TO_CHAR(PPN,'999,999,999,999') PPN_, TO_CHAR(TOTAL_TAGIHAN,'999,999,999,999') TOTAL_TAGIHAN_ FROM LAP_KEUANGAN WHERE
                        to_date(TGL_NOTA,'dd/mm/rrrr') between to_date('$tgl_awal', 'dd/mm/rrrr') and to_date('$tgl_akhir', 'dd/mm/rrrr') ". $query_status_pembayaran.' '.$query_status_pelunasan.' '.$query_id_emkl. ' ' .$query_status_transfer . ' ' . $query_status_nota."
                        UNION SELECT LAP_KEUANGAN.*, TO_CHAR(TAGIHAN,'999,999,999,999') TAGIHAN_, TO_CHAR(PPN,'999,999,999,999') PPN_, TO_CHAR(TOTAL_TAGIHAN,'999,999,999,999') TOTAL_TAGIHAN_ FROM LAP_KEUANGAN WHERE
                        to_date(TGL_NOTA,'dd/mm/rrrr') between to_date('$tgl_awal', 'dd/mm/rrrr') and to_date('$tgl_akhir', 'dd/mm/rrrr') ". $query_status_pembayaran.' '.$query_status_pelunasan.' '.$query_id_emkl. ' ' .$query_2 . ' ' . $query_status_nota.")
                         order by TGL_NOTA desc";
	}		 
	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll(); 


?>

 <div id="list">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="10"><div align="left">PT. (PERSERO) PELABUHAN INDONESIA II </div></td>
    </tr>
    <tr>
      <td colspan="10"><div align="left">CABANG PONTIANAK</div></td>
    <tr>
      <td colspan="10">TGL PROSES : <?=date('d M Y h:i:s')?></td>
    </tr>
	<tr>
      <td colspan="10"></td>
    </tr>
  </table>
  <br />
  <hr style="border: B dashed;"/>
  <div style="font-size:9.5pt" align="center"><b><i>Laporan Penerbitan Nota SIMOP <?=$ket?> <?=$lunas?>
  </div>
  <hr style="border: B dashed;"/>
  <br />
  <table width="100%" border="2" bordercolor="white" cellspacing="2" cellpadding="2">
    <tr align="center" bgcolor="#0268AB"  style="color:#eeeeee;">
      <td  width="20" align="center">NO</td>
      <td  width="55" align="center">Invoice</td>
      <td  width="65" align="center">No. Dokumen</td>
      <td  width="150" align="center">Customer</td>
      <td  width="25" align="center">Curr</td>
      <td  width="50" align="center">Tgl Lunas</td>
      <td  width="110" align="center">Layanan</td>
      <td  width="100" align="center">No Pajak</td>	  
	  <td  width="50" align="center">User Lunas</td>
      <td  width="60" align="center">Pendapatan</td>
      <td  width="50" align="center">Ppn</td>
      <td  width="50" align="center">Total</td>
	  <td  width="50" align="center">Proses Simkeu</td>
	  <td  width="50" align="center">Pelunasan</td>
	  <td  width="50" align="center">Ready Transfer</td>
	  <td  width="100" align="center">Keterangan</td>
    </tr>
   <? $i=1;
   foreach ($row_list as $row) {?>
      <tr>
        <td width="20" align="center"><?=$i?></td>
        <td width="55" align="left"><?=$row['NO_NOTA']?></td>
        <td width="70" align="left"><?=$row['NO_REQUEST']?></td>
        <td width="150" align="left"><?=$row['EMKL']?></td>
        <td width="25" align="left"><?=$row['CURR']?></td>
        <td width="50" align="left"><?=$row['TANGGAL_LUNAS']?></td>
        <td width="100" align="left" ><?=$row['TIPE_LAYANAN']?></td>
        <td width="100" align="left"><?=$row['NO_FAKTUR']?></td>		
	    <td  width="50" align="center"><?=$row['USERNAME']?></td>
        <?php if ($row["CURR"]=='IDR')
				{	?>
        <td width="60" align="right"><?php echo $row['TAGIHAN_']?></td>
        <td width="50" align="right"><?php echo $row['PPN_']?></td>
        <td width="50" align="right"><?php echo $row['TOTAL_TAGIHAN_']?></td>
        <?php	} else { ?>
        <td width="60" align="right"><?php echo $row['TAGIHAN_'] ?></td>
        <td width="50" align="right"><?php echo $row['PPN_'] ?></td>
        <td width="50" align="right"><?php echo $row['TOTAL_TAGIHAN_'] ?></td>
        <?php	}
			?>
		<td  width="50" align="center"><?=$row['SIMKEU_PROSES']?></td>
		<td  width="50" align="center"><?=$row['PELUNASAN']?></td>
		<td  width="50" align="center"><? if ($row['READY_TRANSFER'] == 0) {$status = 'READY TO TRANSFER'; echo $status;} ELSE {$status = 'NOT READY TO TRANSFER';  echo $status;}?></td>
		<td width="100" align="left"><?=$row['KETERANGAN']?></td>
      </tr>
    <? $i++;}?> 
 
  </table>
 </div>