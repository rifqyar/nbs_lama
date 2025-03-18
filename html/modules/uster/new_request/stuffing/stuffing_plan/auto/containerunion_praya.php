<?php
$no_cont = strtoupper($_GET["NO_CONTAINER"]);

require_lib('praya.php');

$term = strtoupper($_GET["term"]);

try {
    $payload = array(
        "orgId" => PRAYA_ITPK_PNK_ORG_ID,
        "terminalId" => PRAYA_ITPK_PNK_TERMINAL_ID,
        "containerNo" => $no_cont,
    );

    $response = sendDataFromUrl($payload, PRAYA_API_TOS . "/api/stuffingContainerList", 'POST', getTokenPraya());
    $response = json_decode($response['response'], true);

    if ($response['code'] == 1 && !empty($response["dataRec"])) {
        echo json_encode($response['dataRec']);
    }

} catch (Exception $ex) {
    echo $ex->getMessage();
}