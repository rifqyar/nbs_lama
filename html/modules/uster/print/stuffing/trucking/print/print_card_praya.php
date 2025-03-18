<?php

require_lib('praya.php');

$requestId = $_GET["no_req"];
$containerNo = $_GET["no_cont"];

try {
    $payload = array(
        "orgId" => PRAYA_ITPK_PNK_ORG_ID,
        "terminalId" => PRAYA_ITPK_PNK_TERMINAL_ID,
        "requestId" => $requestId,
        "changeBy" => "uster",
        "changeDate" => date('Y-m-d'),
        "password" => PRAYA_ITPK_PNK_PASS_PRINT,
        "containerNo" => $containerNo
    );

    // echo var_dump($payload);die;

    $response = sendDataFromUrl($payload, PRAYA_API_PROFORMA . "/api/printCard", 'POST', getTokenPraya());
    $response = json_decode($response['response'], true);

    if ($response['code'] == 1 && !empty($response["dataRec"])) {
        // echo json_encode($response['dataRec']['fn']);

        $pdfUrl = $response['dataRec']['fn'];
        header("Location: " . $pdfUrl);
    }

} catch (Exception $ex) {
    echo $ex->getMessage();
}