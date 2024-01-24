<?php
require_lib('praya.php');
$db =  getDB("storage");

$query_cek  = "select NVL(LPAD(MAX(TO_NUMBER(SUBSTR(NO_REQUEST,8,13)))+1,6,0),'000001') AS JUM,
TO_CHAR(SYSDATE, 'MM') AS MONTH, 
TO_CHAR(SYSDATE, 'YY') AS YEAR 
FROM REQUEST_BATAL_MUAT
WHERE TGL_REQUEST BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";

$result_cek  = $db->query($query_cek);
$jum_    = $result_cek->fetchRow();
$jum    = $jum_["JUM"];
$month    = $jum_["MONTH"];
$year    = $jum_["YEAR"];

$no_req_bm	= "BMU".$month.$year.$jum;

$new_id_request   = $no_req_bm;
$payload_batal_muat = $_POST["payload_batal_muat"];

// $payload_batal_muat = array(
//   "ex_noreq" => $ex_noreq,
//   "vesselId" => $kd_kapal,
//   "vesselName" => $nm_kapal,
//   "voyage" => $voyage,
//   "voyageIn" => $voyage_in,
//   "voyageOut" => $voyage_out,
//   "nm_agen" => $nm_agen,
//   "kd_agen" => $kd_agen,
//   "pelabuhan_tujuan" => $kd_pelabuhan_tujuan,
//   "pelabuhan_asal" => $kd_pelabuhan_asal,
//   "cont_list" => $_POST['BM_CONT'],
// );

set_time_limit(360);

try {

  $curl = curl_init();
  /* set configure curl */
  // $authorization = "Authorization: Bearer $token";
  $payload_request = array(
    "ID_REQUEST" => $new_id_request,
    "JENIS" => "BATAL_MUAT",
    "BANK_ACCOUNT_NUMBER" => "",
    "PAYMENT_CODE" => "",
    "PAYLOAD_BATAL_MUAT" => $payload_batal_muat
  );
  // echo json_encode($payload_request) . '<<payload_req';
  $url = HOME . "uster.billing.paymentcash.ajax/save_payment_external";
  // echo var_dump($url);
  // die();
  curl_setopt_array(
    $curl,
    array(
      CURLOPT_URL             => $url,
      CURLOPT_RETURNTRANSFER  => true,
      CURLOPT_ENCODING        => "",
      CURLOPT_MAXREDIRS       => 10,
      CURLOPT_CUSTOMREQUEST   => "POST",
      CURLOPT_POSTFIELDS      => json_encode($payload_request),
      CURLOPT_HTTPHEADER      => array(
        "Content-Type: application/json"
      ),
    )
  );


  $response = curl_exec($curl);
  // $err = curl_error($curl);
  // echo var_dump($response);

  if ($response === false) {
    throw new Exception(curl_error($curl));
  }

  // Get HTTP status code
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

  //Success
  if ($httpCode >= 200 && $httpCode < 300) {
    $response_curl = array(
      'status'   => 'success',
      'httpCode' => $httpCode,
      'response' => $response
    );
  } else if ($httpCode >= 400 && $httpCode < 500) {
    //Client Error
    $response_curl = array(
      'status'   => 'error',
      'httpCode' => $httpCode,
      'response' => $response
    );
  } else {
    //Server Error
    throw new Exception('HTTP Server Error: ' . $httpCode);
  }

  /* execute curl */
  curl_close($curl);

  echo $response_curl['response'];
  exit();
} catch (Exception $e) {
  // echo $e . "<< error-aftercurl";
  $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  $response_curl = array(
    'status'   => 'error',
    'httpCode' => $httpCode,
    'response' => "cURL Error # " . $e->getMessage()
  );

  echo $response_curl;
  exit();
}
