<?php
	$db 	= getDB();
	$blok = $_GET["blok"];
	$ei = $_GET["e_i"];
	$query_BlokEI="SELECT C.NAME, B.E_I, B.SIZE_, B.TYPE_, B.STATUS, COUNT (*) AS JUMLAH
		FROM YD_PLACEMENT_YARD A
			 INNER JOIN ISWS_LIST_CONTAINER B
				ON (A.NO_CONTAINER = B.NO_CONTAINER AND A.ID_VS = B.NO_UKK)
			 INNER JOIN YD_BLOCKING_AREA C
				ON (A.ID_BLOCKING_AREA = C.ID)
			 INNER JOIN YD_YARD_AREA D
				ON (C.ID_YARD_AREA = D.ID)
		WHERE 
		--NAMA_YARD LIKE 'LAPANGAN 300' AND 
		C.NAME='$blok' AND B.E_I='$ei'
		GROUP BY C.NAME, B.E_I, B.SIZE_, B.TYPE_, B.STATUS
		ORDER BY NAME";
        $numeric;
		$res_BlokEI = $db->query($query_BlokEI);
		while ($row = $res_BlokEI->fetchRow()) {
			$numeric = $numeric.$row["SIZE_"] . "-" . $row["TYPE_"] . "-" . $row["STATUS"].'#' . $row["JUMLAH"] . ';';
		}
		echo($numeric);
?>