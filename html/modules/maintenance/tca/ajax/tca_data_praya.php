<?php
require_lib('praya.php');
$voyage  	= $_GET['voyage'];
$destination	= $_GET['terminal'];
$page = $_GET['page'];
$rowNum = $_GET['rowNum'];
if(!isset($page) || $page == "undefined") {$page = 1;}

$payload = array(
	"terminalCode" => PRAYA_ITPK_PNK_TERMINAL_CODE,
	"search" => $voyage,
	"record" => $rowNum,
	"destination" => $destination,
	"page" => $page
);


try {
	$response = sendDataFromUrlTryCatch($payload, PRAYA_API_TOS . '/api/getReportTca', 'POST', getTokenPraya());
	if ($response['httpCode'] < 200 && $response['httpCode'] >= 300) {
		$response_decode = json_decode($response['response'], true);
		$msg = $response_decode['msg'] ? $response_decode['msg'] : $response['response'];
		echo json_encode(array(
			"code" => "0",
			"msg" => "Error " . $response['httpCode'] . " : " . $msg
		));
	} else {
		$response_decode = json_decode($response['response'], true);
		$row = $response_decode['dataRec']['dataReq'];
		$pagination = $response_decode['dataRec']['pagination'];
	}
} catch (Exception $ex) {
	echo $ex->getMessage();
}


$i = 0;
foreach ($row as $rowm) {
	$data->rows[$i]['id'] = $rowm["NO_CONTAINER"];
	$data->rows[$i]['cell'] = array($rowm["NO_CONTAINER"], $rowm["F_TCATN"], $rowm["TRUCK_NUMBER"], $rowm["F_TCAST"], $rowm["ACTIVITY"]);
	// $data-
	$i++;
}

$data->total = $pagination['totalPage'];
$data->records = $pagination['totalRow'];
$data->page = $page;
$data->rowNum = $rowNum;

echo json_encode($data);
