<?php
$db 	= getDB();
//$key    = $_POST["KD_PELUNASAN"]; 
if (($_POST['KD_PELUNASAN'])=="1") {
	$jns_bayar="BANK";
//}elseif (($_POST['KD_PELUNASAN'])=="2") {
//	$jns_bayar="CASH";
}elseif (($_POST['KD_PELUNASAN'])=="3") {
	$jns_bayar="BANK";
}elseif (($_POST['KD_PELUNASAN'])=="4") {
	$jns_bayar="BANK";
}

$nota = $_POST['idn'];

$db2=getDb();
$getorgid = "select org_id from TTH_NOTA_ALL2 where trim(NO_NOTA)=trim('".$nota."')";
	$rorg 	= $db2->query($getorgid)->fetchRow();
	$org_id = $rorg['ORG_ID'];
//$sql_bank = "SELECT *
//			  FROM xpi2_ar_receipt_method_v
//			 WHERE kode_cabang = 'TPK' AND CURRENCY_CODE = 'IDR'
//			 AND receipt_account IN ('TPK BRI IDR 86901000008303','TPK MANDIRI IDR 1200073000031','TPK BNI IDR 8064331') ORDER BY RECEIPT_ACCOUNT ASC";

$query_nota 	= "SELECT bank_account_name receipt_account, bank_id,
                           CASE
                              WHEN bank_account_name = 'IPTK CASH' THEN 'IPTK CASH'
                              ELSE 'IPTK BANK'
                           END
                              receipt_method
                      FROM mst_bank_simkeu
					  where org_id = '88'";
$result			= $db2->query($query_nota);
$rwsql_bank			= $result->getAll(); 

?>
<select id="via">
    <?php 
		foreach ($rwsql_bank as $key) { ?>
		<option value="<?=$key['RECEIPT_ACCOUNT']?>"><?=$key['RECEIPT_ACCOUNT']?></option>	
	<?php } ?>		
</select>