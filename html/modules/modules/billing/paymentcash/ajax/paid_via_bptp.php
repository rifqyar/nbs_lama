<?php
$db 	= getDB('orafin');
//$key    = $_POST["KD_PELUNASAN"]; 
if (($_POST['KD_PELUNASAN'])=="1") {
	$jns_bayar="BANK";
}elseif (($_POST['KD_PELUNASAN'])=="2") {
	$jns_bayar="CASH";
}elseif (($_POST['KD_PELUNASAN'])=="3") {
	$jns_bayar="BANK";
}elseif (($_POST['KD_PELUNASAN'])=="4") {
	$jns_bayar="BANK";
}
//$sql_bank = "SELECT *
//			  FROM xpi2_ar_receipt_method_v
//			 WHERE kode_cabang = 'TPK' AND CURRENCY_CODE = 'IDR'
//			 AND receipt_account IN ('TPK BRI IDR 86901000008303','TPK MANDIRI IDR 1200073000031','TPK BNI IDR 8064331') ORDER BY RECEIPT_ACCOUNT ASC";


$sql_bank = "SELECT *
   FROM APPS.xpi2_ar_receipt_method_v
  WHERE kode_cabang = 'TPK' AND CURRENCY_CODE = 'IDR'
        AND receipt_account IN
                 ('TPK BRI IDR 86901000008303',
                  'TPK MANDIRI IDR 1200073000031',
                  'TPK BNI IDR 8064331')
ORDER BY RECEIPT_ACCOUNT ASC";


$rsql_bank = $db->query($sql_bank);
$rwsql_bank = $rsql_bank->getAll();
?>
<select id="via">
	<?php 
		foreach ($rwsql_bank as $key) { ?>
		<option value="<?=$key['RECEIPT_ACCOUNT']?>"><?=$key['RECEIPT_ACCOUNT']?></option>	
	<?php } ?>
</select>