<?php
	$db = getDB("storage");
	$trx = $_POST['TRX'];
	$query = "SELECT COUNT(*) FLAG FROM ITPK_NOTA_HEADER where TRX_NUMBER = '$trx'";
	$rq 	= $db->query($query)->fetchRow();
	if ($rq[FLAG] == 0) {
		echo "YES";
	}
	else {
		echo "NO";
	}
	die();
?>