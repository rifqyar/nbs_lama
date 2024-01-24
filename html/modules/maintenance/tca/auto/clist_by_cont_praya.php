<?php
require_lib('praya.php');

$id_req = $_GET["NO_REQ"];
$cancel = $_GET["CANCEL"];

try {
  $payload = array(
    "idRequest" => $id_req,
    "orgId" => PRAYA_ITPK_PNK_ORG_ID,
    "terminalId" => PRAYA_ITPK_PNK_TERMINAL_ID
  );

  if($cancel == "Y"){
    $payload["tcaCancelation"] = true;
  }

  $response = sendDataFromUrl($payload, PRAYA_API_TOS . "/api/getRequestTca", 'POST', getTokenPraya());
  $response = json_decode($response['response'], true);

  if ($response['code'] == 1 && !empty($response["dataRec"])) {
    echo json_encode($response['dataRec']);
  }
} catch (Exception $ex) {
  echo $ex->getMessage();
}

?>