<?php

require_lib('praya.php');

$ei = $_GET["status"];
$periode_awal = $_GET["periode_awal"];
$periode_akhir = $_GET["periode_akhir"];

try {

    $startDate = date("Y-m-d", strtotime($periode_awal));
    $endDate = date("Y-m-d", strtotime($periode_akhir));
    
    $payload = array(
        "orgId" => PRAYA_ITPK_PNK_ORG_ID,
        "terminalId" => PRAYA_ITPK_PNK_TERMINAL_ID,
        "terminalCode" => PRAYA_ITPK_PNK_TERMINAL_CODE,
        "createdBy" => "uster",
        "exportImport" => $ei,
        "startDate" => $startDate,
        "endDate" => $endDate,
        "fileType" => "pdf"
    );

    // echo var_dump($payload);die;

    $response = sendDataFromUrl($payload, PRAYA_API_PROFORMA . "/api/reportDwellingTime", 'POST', getTokenPraya());
    $response = json_decode($response['response'], true);

    if ($response['code'] == 1 && !empty($response["dataRec"])) {
        // echo json_encode($response['dataRec']['fn']);

        $pdfUrl = $response['dataRec']['fn'];
        header("Location: " . $pdfUrl);
    }

} catch (Exception $ex) {
    echo $ex->getMessage();
}