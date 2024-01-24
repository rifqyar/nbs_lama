<?php
	$tl 	=  xliteTemplate('home.htm');	
	$db 	= getDB();	
	
	$query="SELECT B.NAME,
			 COUNT (DISTINCT (A.SLOT_)) AS SLOT,
			 COUNT (DISTINCT (A.ROW_)) AS ROW_CONT,
			 B.TIER,
			 (COUNT (DISTINCT (A.INDEX_CELL)) * (B.TIER)) AS CAPACITY,
			 COUNT (D.ID_CELL) AS USED,
			 ( (COUNT (D.ID_CELL)) / (COUNT (DISTINCT (A.INDEX_CELL)) * B.TIER))
			 * 100
				AS YOR,
			 COUNT (DISTINCT (D.NO_CONTAINER)) BOX
		FROM YD_BLOCKING_CELL A
			 LEFT JOIN YD_PLACEMENT_YARD D
				ON (A.INDEX_CELL = D.ID_CELL)
			 INNER JOIN YD_BLOCKING_AREA B
				ON (A.ID_BLOCKING_AREA = B.ID)
			 INNER JOIN YD_YARD_AREA C
				ON (B.ID_YARD_AREA = C.ID)
	   WHERE NAMA_YARD LIKE 'LAPANGAN 300' AND B.TIER != 0
	GROUP BY C.ID,
			 B.ID,
			 B.NAME,
			 B.TIER
	ORDER BY C.ID, B.NAME, B.ID";

	//query
	$used = 0;
	$capacity=0;
	$res = $db->query($query);
	while ($row = $res->fetchRow()) {
		$used = $used + $row["USED"];
		$capacity = $capacity + $row["CAPACITY"];
	}
	$available = $capacity - $used;
	
	$query_EI="SELECT E_I, COUNT (E_I) AS JUMLAH
    FROM YD_PLACEMENT_YARD A
         INNER JOIN ISWS_LIST_CONTAINER B
            ON (A.NO_CONTAINER = B.NO_CONTAINER AND A.ID_VS = B.NO_UKK)
         INNER JOIN YD_BLOCKING_AREA C
            ON (A.ID_BLOCKING_AREA = C.ID)
         INNER JOIN YD_YARD_AREA D
            ON (C.ID_YARD_AREA = D.ID)
	WHERE NAMA_YARD LIKE 'LAPANGAN 300'
	GROUP BY E_I";
	$resEI = $db->query($query_EI);
	while ($row = $resEI->fetchRow()) {
		if(trim($row["E_I"])=="E"){
			$tl->assign("jumlah_E",$row["JUMLAH"]);
		} else {
			$tl->assign("jumlah_I",$row["JUMLAH"]);
		}
	}
	$tl->assign("available",$available);
	$tl->assign("used",$used);
	
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
