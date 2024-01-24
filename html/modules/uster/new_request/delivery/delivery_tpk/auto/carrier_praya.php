<?php

require_lib('praya.php');

$voyage     = $_GET['voyage'];
$term       = strtoupper($_GET["term"]);

try {

    $json = getDatafromUrl(PRAYA_API_TOS."/api/getOperator?orgId=".PRAYA_ITPK_PNK_ORG_ID."&terminalId=".PRAYA_ITPK_PNK_TERMINAL_ID."&voyage=".$voyage."&search=".$term);

    $json = json_decode($json, true);

    if ($json['code'] == 1) {
        echo json_encode($json['data']);
    } else {
        echo '<script>alert(\'' . $json['msg'] . '\')</script>';
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}

?>