<?php

$voyage_in = $_GET["VOYIN"];
$voyage_out = $_GET["VOYOUT"];
$vessel = $_GET["NM_KAPAL"];
$port = $_GET["PORT"];

require_lib('praya.php');

$term = strtoupper($_GET["term"]);

try {
    $payload = array(
        "orgId" => PRAYA_ITPK_PNK_ORG_ID,
        "terminalId" => PRAYA_ITPK_PNK_TERMINAL_ID,
        "portCode" => $port,
        "vesselName" => $vessel,
        "voyageIn" => $voyage_in,
        "voyageOut" => $voyage_out
    );

    $response = sendDataFromUrl($payload, PRAYA_API_TOS . "/api/getPod", 'POST', getTokenPraya());
    $response = json_decode($response['response'], true);

    if ($response['code'] == 1 && !empty($response["data"])) {
        echo json_encode($response['data']);
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}

?>