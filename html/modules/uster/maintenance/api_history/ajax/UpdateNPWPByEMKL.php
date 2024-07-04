<?php
$db         = getDB("storage");
// Set content type to JSON
header('Content-Type: application/json');

$EMKL = isset($_POST['EMKL']) ? $_POST['EMKL'] : '';

$query = $db->query("
    SELECT
        NM_PBM AS CONSIGNEE,
        NO_NPWP_PBM16 AS NPWP_CONSIGNEE16,
        NO_NPWP_PBM AS NPWP_CONSIGNEE
    FROM
        MST_PELANGGAN
    WHERE NM_PBM = '$EMKL'");

$result = $query->fetchRow();
$CONSIGNEE = $result["CONSIGNEE"];
$NPWP_CONSIGNEE = $result["NPWP_CONSIGNEE16"];

$NPWP_DEFAULT = $result["NPWP_CONSIGNEE"];



try {
    if ($NPWP_CONSIGNEE == null) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://ibs-unicorn.pelindo.co.id/api/ApiBupot/ValidasiNpwpV3?NPWP=' . $NPWP_DEFAULT,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '', // Add this line if the POST request requires a body
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json' // Ensure the content type is set correctly
            ),
        ));

        $response_data = curl_exec($curl);

        // Check for cURL errors
        if ($response_data === false) {
            throw new Exception(curl_error($curl));
        }

        // Check HTTP response code
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($http_code != 200) {
            throw new Exception("HTTP Error: " . $http_code . " Response: " . $response_data);
        }

        // Close cURL session
        curl_close($curl);

        $response = json_decode($response_data, true);
        if ($response['status'] == 1) {

            $NPWP16 =  $response['data']['npwp16'];

            $query = "
            UPDATE
                MST_PELANGGAN
            SET
                NO_NPWP_PBM16 =  '$NPWP16'
            WHERE
                NO_NPWP_PBM = '$NPWP_DEFAULT' ";

            $updateNPWP = $db->query($query);

            if ($updateNPWP) {
                $response = array(
                    "status" => "1",
                    "message" => "DATA Sudah Melakukan Pengkinian WPWP, Silakan Klik Tombol Simpan Kembali",
                    "NPWP16" => $NPWP16,
                    "activity" => "update"
                );
            } else {
                $response = array(
                    "status" => "0",
                    "message" => "DATA Gagal Melakukan Pengkinian WPWP"
                );
            }
        } elseif ($response['status'] == 0) {
            $response = array(
                "status" => "0",
                "message" => "DATA Belum Melakukan Pengkinian WPWP"
            );
        } else {
            $response = array(
                "status" => $response['status'],
                "message" => $response['message']
            );
        }
    } else if ($NPWP_CONSIGNEE != null) {
        // Create response array
        $response = array(
            "status" => "1",
            "message" => "DATA IDENTITAS BERHASIL MELAKUKAN PENGKINIAN NPWP",
            "NPWP16" => $NPWP_CONSIGNEE,
            "activity" => "pass"
        );
    } else {
        $response = array(
            "status" => "0",
            "message" => "Invalid NPWP_CONSIGNEE length"
        );
    }
} catch (Exception $e) {
    // Handle exceptions and send error response as JSON
    $response = array(
        "status" => "0",
        "message" => "Error: " . $e->getMessage()
    );
}

echo json_encode($response);
