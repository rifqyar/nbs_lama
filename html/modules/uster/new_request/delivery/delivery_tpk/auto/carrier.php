<?php   
$nama			= strtoupper($_GET["term"]);
$vessel         = $_GET['ves'];
$db 			= getDB('dbint');	
//$query 			= "SELECT CDG_OPER_CODE AS CODE, CDG_OPER_NAME AS LINE_OPERATOR FROM M_CDG_OPERATOR WHERE UPPER(CDG_OPER_CODE) LIKE '%$nama%' OR CDG_OPER_NAME LIKE '%$nama%'";
$query			="SELECT CDG_OPER_CODE AS CODE, CDG_OPER_NAME AS LINE_OPERATOR
  FROM M_CDG_OPERATOR
 WHERE (UPPER (CDG_OPER_CODE) LIKE '%$nama%' OR UPPER(CDG_OPER_NAME) LIKE '%$nama%')
 AND CDG_OPER_CODE IN (SELECT VSB_VOYO_OPER 
                               FROM    M_VSB_VOYAGE_OPER A
                                    INNER JOIN
                                       M_VSB_VOYAGE B
                                    ON (A.VSB_VOYO_VESSEL = B.VESSEL_CODE)
                              WHERE B.VESSEL_CODE = '$vessel' or B.VESSEL = '$vessel')";
$result			= $db->query($query);
$row			= $result->getAll();	
//echo $query;
//die();
echo json_encode($row);
?>