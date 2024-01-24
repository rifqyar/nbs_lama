<?php
	$tl 	=  xliteTemplate('home.htm');	
	$db 	= getDB();	
	
	$query="SELECT B.NAME,E.E_I AS EX_OR_IM,
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
             LEFT JOIN ISWS_LIST_CONTAINER E
                ON(D.NO_CONTAINER = E.NO_CONTAINER AND D.ID_VS = E.NO_UKK)
             INNER JOIN YD_BLOCKING_AREA B
                ON (A.ID_BLOCKING_AREA = B.ID)
             INNER JOIN YD_YARD_AREA C
                ON (B.ID_YARD_AREA = C.ID)
       WHERE NAMA_YARD LIKE 'LAPANGAN 300-305' AND B.TIER != 0
    GROUP BY C.ID,
            E.E_I,
             B.ID,
             B.NAME,
             B.TIER
    ORDER BY C.ID, B.NAME, B.ID";

	//query
	$used_export = 0;
	$used_import = 0;
	$capacity=0;
	$boxExport=0;
	$boxImport=0;
	$blokExport;
	$blokImport;
	$arrayBlok;
	$res = $db->query($query);
	while ($row = $res->fetchRow()) {
		if(trim($row[EX_OR_IM])=='E'){
			$boxExport = $boxExport + $row["BOX"];
			$used_export = $used_export + $row["USED"];
			$capacity = $capacity + $row["CAPACITY"];
			$blokExport[$row["NAME"]]=$blokExport[$row["NAME"]]+$row["USED"];
			$arrayBlok[$row["NAME"]]["E"]=$arrayBlok[$row["NAME"]]["E"]+$row["USED"];
		} else if(trim($row[EX_OR_IM])=='I'){
			$boxImport = $boxImport + $row["BOX"];
			$used_import = $used_import + $row["USED"];
			$capacity = $capacity + $row["CAPACITY"];
			$blokImport[$row["NAME"]]=$blokImport[$row["NAME"]]+$row["USED"];
			$arrayBlok[$row["NAME"]]["I"]=$arrayBlok[$row["NAME"]]["I"]+$row["USED"];
		} else {
			$capacity = $capacity + $row["CAPACITY"];
		}
	}
	$available = $capacity - $used_export - $used_import;
	
	$query_EI="SELECT E_I, COUNT (E_I) AS JUMLAH
    FROM YD_PLACEMENT_YARD A
         INNER JOIN ISWS_LIST_CONTAINER B
            ON (A.NO_CONTAINER = B.NO_CONTAINER AND A.ID_VS = B.NO_UKK)
         INNER JOIN YD_BLOCKING_AREA C
            ON (A.ID_BLOCKING_AREA = C.ID)
         INNER JOIN YD_YARD_AREA D
            ON (C.ID_YARD_AREA = D.ID)
	WHERE NAMA_YARD LIKE 'LAPANGAN 300-305'
	GROUP BY E_I";
	$resEI = $db->query($query_EI);
	while ($row = $resEI->fetchRow()) {
		if(trim($row["E_I"])=="E"){
			$tl->assign("jumlah_E",$row["JUMLAH"]);
		} else {
			$tl->assign("jumlah_I",$row["JUMLAH"]);
		}
	}
	
	$query_BlokEI="SELECT C.NAME, B.E_I, B.SIZE_, B.TYPE_, B.STATUS, COUNT (*) AS JUMLAH
    FROM YD_PLACEMENT_YARD A
         INNER JOIN ISWS_LIST_CONTAINER B
            ON (A.NO_CONTAINER = B.NO_CONTAINER AND A.ID_VS = B.NO_UKK)
         INNER JOIN YD_BLOCKING_AREA C
            ON (A.ID_BLOCKING_AREA = C.ID)
         INNER JOIN YD_YARD_AREA D
            ON (C.ID_YARD_AREA = D.ID)
    WHERE NAMA_YARD LIKE 'LAPANGAN 300-305'
    GROUP BY C.NAME, B.E_I, B.SIZE_, B.TYPE_, B.STATUS
    ORDER BY NAME";
	$res_BlokEI = $db->query($query_BlokEI);
	while ($row = $res_BlokEI->fetchRow()) {
		$blokEIType[$row["NAME"]][$row["E_I"]][$row["SIZE_"]."-".$row["TYPE_"]."-".$row["STATUS"]]=$row["JUMLAH"];
	}
	
	ksort($blokExport);
	ksort($blokImport);
	$tl->assign("boxExport",$boxExport);
	$tl->assign("boxImport",$boxImport);
	$tl->assign("available",$available);
	$tl->assign("blokEIType",$blokEIType);
	$tl->assign("used_export",$used_export);
	$tl->assign("used_import",$used_import);
	$tl->assign("blokExport",$blokExport);
	$tl->assign("blokImport",$blokImport);
	$tl->assign("arrayBlok",$arrayBlok);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
