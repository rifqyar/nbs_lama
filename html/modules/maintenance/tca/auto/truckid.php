<?php

$truckid			= strtoupper($_GET["term"]);
$db 	       		= getDB();	


$query 			= "SELECT A.TRUCK_NUMBER TRUCK_ID,
				       A.POLICE_NUMBER TRUCK_NUMBER,
				       D.F_TIDNM COMPANY_NAME
				  FROM M_CDY_TRUCK@dbint_link a
				       LEFT JOIN AUTOGATE.TB_TID C
				          ON a.TRUCK_NUMBER = C.F_TIDTG
				       JOIN AUTOGATE.TB_TID_DETAIL D
				          ON C.F_TIDID = D.F_TIDID   
                    WHERE A.PARTY IS NULL AND (A.TRUCK_NUMBER LIKE '$truckid%' OR D.F_TIDNM LIKE '$truckid%' OR A.POLICE_NUMBER LIKE '$truckid%' )";
$result			= $db->query($query);
$row			= $result->getAll();	

//echo $query;
echo json_encode($row);


?>