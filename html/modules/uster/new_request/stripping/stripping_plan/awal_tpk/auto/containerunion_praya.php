<?php
$no_cont = strtoupper($_GET["NO_CONTAINER"]);

$voy = $_GET["VOYAGE"];
$voyage_in = $_GET["VOYIN"];
$voyage_out = $_GET["VOYOUT"];
$vessel = $_GET["VESSEL"];
$idvsb = $_GET["ID_VSB"];
$vessel_code = $_GET["VESCODE"];

require_lib('praya.php');

$term = strtoupper($_GET["term"]);

try {
    $payload = array(
        "orgId" => PRAYA_ITPK_PNK_ORG_ID,
        "terminalId" => PRAYA_ITPK_PNK_TERMINAL_ID,
        "vesselId" => $vessel_code,
        "voyageIn" => $voyage_in,
        "voyageOut" => $voyage_out,
        "voyage" => $voy,
        "portCode" => PRAYA_ITPK_PNK_PORT_CODE,
        "ei" => "I",
        "containerNo" => $no_cont,
        "serviceCode" => "DEL"
    );

    $response = sendDataFromUrl($payload, PRAYA_API_TOS . "/api/containerList", 'POST', getTokenPraya());
    $response = json_decode($response['response'], true);



    if ($response['code'] == 1 && !empty($response["data"])) {
        echo json_encode($response['data']);
    }

} catch (Exception $ex) {
    echo $ex->getMessage();
}