<?php

$no_cont = strtoupper($_GET["term"]);

$db = getDB('dbint');

$query = "select a.NO_CONTAINER,
         a.ISO_CODE,
         b.VESSEL,
         a.VOY_IN,
         a.VOY_OUT,
         a.STATUS_CONT, 
         a.CUSTOMER_NAME, b.POINT, a.NO_REQUEST, A.NO_TRX
    from m_billing a join m_cyc_container b on A.NO_CONTAINER=B.NO_CONTAINER  and a.vessel=B.VESSEL_CODE 
        and A.VOY_IN=B.VOYAGE_IN and a.voy_out=b.voyage_out
    where a.NO_CONTAINER='$no_cont' and a.FLAG='REC'
";
$result = $db->query($query);
$row = $result->getAll();

//print_r($query);die;

echo json_encode($row);
?>