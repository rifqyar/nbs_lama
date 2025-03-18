<?php
require_lib('praya.php');
$truckid = strtoupper($_GET["term"]);
$row = get_truck_list($truckid);

echo json_encode($row);

function get_truck_list($truck_id)
{
	set_time_limit(360);

	try {
		$payload = array(
			"search" => $truck_id,
			// "tid" => $,
			"orgId" => PRAYA_ITPK_PNK_ORG_ID,
			"terminalId" => PRAYA_ITPK_PNK_TERMINAL_ID
		);

		$response = sendDataFromUrl($payload, PRAYA_API_TOS . "/api/truckList", 'POST', getTokenPraya());
		$response = json_decode($response['response'], true);

		if ($response['code'] == 1 && !empty($response["data"])) {
			return $response['data'];
		}
	} catch (Exception $ex) {
		echo $ex->getMessage();
	}
}
