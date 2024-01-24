<?php
require_lib('praya.php');

$multiple = $_POST["multiple"];

$terminal_arr = array(
    "orgId" => PRAYA_ITPK_PNK_ORG_ID,
    "terminalId" => PRAYA_ITPK_PNK_TERMINAL_ID
);

if ($multiple == "Y") {
    $data = $_POST["data"];
    $new_data = array();
    foreach ($data as $arr => $value) {
        array_push($new_data, array_merge($terminal_arr, $value));
    }
    $payload = array(
        "multiple" => "Y",
        "data" => $new_data
    );
} else {
    $payload = array(
        "truckType" => $_POST['truckType'],
        "truckNumber" => $_POST['truckNumber'],
        "createdBy" => $_POST['createdBy'],
        "tid" => $_POST['tid'],
        "axle" => $_POST['axle'],
        "type" => $_POST['type'],
        "actionCode" => $_POST['actionCode'],
        "detail" => $_POST['detail']
    );
    $payload = array_merge($terminal_arr, $payload);
}

// echo json_encode($payload);

try {
    $response = sendDataFromUrlTryCatch($payload, PRAYA_API_TOS . '/api/tcaSaveContainerNew', 'POST', getTokenPraya());
    if ($response['httpCode'] < 200 && $response['httpCode'] >= 300) {
        $response_decode = json_decode($response['response'], true);
        $msg = $response_decode['msg'] ? $response_decode['msg'] : $response['response'];
        $res = array(
            "code" => "0",
            "msg" => "Error " . $response['httpCode'] . " : " . $msg
        );
        echo json_encode($res);
        insertPrayaServiceLog(PRAYA_API_TOS . '/api/tcaSaveContainerNew', $payload, $res, "TCA Save Container");
        exit();
    } else {
        echo $response['response'];
        insertPrayaServiceLog(PRAYA_API_TOS . '/api/tcaSaveContainerNew', $payload, $response['response'], "TCA Save Container");
        exit();
    }
} catch (Exception $ex) {
    echo $ex->getMessage();
}
