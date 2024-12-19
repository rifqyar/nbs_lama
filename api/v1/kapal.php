<?php
// Set the content type to JSON
header('Content-Type: application/json');

try {
    $headers = apache_request_headers();
    if (!isset($headers['Authorization'])) {
        throw new Exception('Authorization header not found');
    }

    // Extract the Bearer token from the Authorization header
    $authHeader = $headers['Authorization'];
    list($tokenType, $token) = explode(' ', $authHeader);

    // Verify that the token type is Bearer and the token matches the expected value
    if ($tokenType !== 'Bearer' || $token !== 'EDII123XX@') {
        throw new Exception('Invalid token');
    }
    
    // Get the raw POST data
    $input = file_get_contents('php://input');

    // Decode the JSON input
    $data = json_decode($input, true);

    // Check if decoding was successful
    if ($data === null) {
        throw new Exception('Invalid JSON');
    }

    // Database connection parameters
    $host = '10.15.42.43';
    $port = '1521';
    $sid  = 'datamti';
    $username = 'OPUS_REPO';
    $password = 'OPUS_REPO';

    // Create connection string
    $conn_string = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = $host)(PORT = $port)))(CONNECT_DATA=(SID=$sid)))";

    // Connect to the Oracle database
    $conn = oci_connect($username, $password, $conn_string);

    if (!$conn) {
        $e = oci_error();
        throw new Exception('Database connection failed: ' . $e['message']);
    }

    // Prepare the SQL statement
    $sql = "INSERT INTO OPUS_REPO.M_VSB_VOYAGE_PALAPA (
        TML_CD, VESSEL, VOYAGE_IN, VOYAGE_OUT, CALL_SIGN, OPERATOR_ID, OPERATOR_NAME,
        ETA, ETB, ETD, ATA, ATB, ATD, ID_POD, POD, ID_POL, POL, OPEN_STACK, CLOSSING_TIME, 
        CLOSSING_DOC, BERTHING_TIME, START_WORK, END_WORK, DATE_SEND, FLAG_SEND, 
        ID_VSB_VOYAGE, VESSEL_CODE, VOYAGE, FIRST_ETD, RBM_DLD, UPD_USER, UPD_DATE, 
        DLT_FLG, CONTAINER_LIMIT, BERTH, NO_PKK, ORIGIN_PORT, LAST_PORT, NEXT_PORT, 
        ACTIVE, VESSEL_CLOSE, ACTWKSL_DATE, ACTWKCL_DATE, ACTWKS_DATE, ACTWKC_DATE, 
        ACTWKSL_TIME, ACTWKCL_TIME, ACTWKS_TIME, ACTWKC_TIME, NOBC11, TGBC11, 
        EARLY_STACK, SERVICE_LANE_IN, SERVICE_LANE_OUT, VESEL_TYPE_CD, SERVICE_TYPE, CANCEL_FLAG
    ) VALUES (
        :TML_CD, :VESSEL, :VOYAGE_IN, :VOYAGE_OUT, :CALL_SIGN, :OPERATOR_ID, :OPERATOR_NAME,
        :ETA, :ETB, :ETD, :ATA, :ATB, :ATD, :ID_POD, :POD, :ID_POL, :POL, :OPEN_STACK, :CLOSSING_TIME, 
        :CLOSSING_DOC, :BERTHING_TIME, :START_WORK, :END_WORK, :DATE_SEND, :FLAG_SEND, 
        :ID_VSB_VOYAGE, :VESSEL_CODE, :VOYAGE, :FIRST_ETD, :RBM_DLD, :UPD_USER, TO_DATE(:UPD_DATE, 'YYYY-MM-DD HH24:MI:SS'), 
        :DLT_FLG, :CONTAINER_LIMIT, :BERTH, :NO_PKK, :ORIGIN_PORT, :LAST_PORT, :NEXT_PORT, 
        :ACTIVE, :VESSEL_CLOSE, :ACTWKSL_DATE, :ACTWKCL_DATE, :ACTWKS_DATE, :ACTWKC_DATE, 
        :ACTWKSL_TIME, :ACTWKCL_TIME, :ACTWKS_TIME, :ACTWKC_TIME, :NOBC11, :TGBC11, 
        :EARLY_STACK, :SERVICE_LANE_IN, :SERVICE_LANE_OUT, :VESEL_TYPE_CD, :SERVICE_TYPE, :CANCEL_FLAG
    )";

    // Prepare the statement
    $stid = oci_parse($conn, $sql);
    if (!$stid) {
        $e = oci_error($conn);
        throw new Exception('Failed to prepare statement: ' . $e['message']);
    }

    // Bind variables to the SQL statement
    oci_bind_by_name($stid, ':TML_CD', $data['TML_CD']);
    oci_bind_by_name($stid, ':VESSEL', $data['VESSEL']);
    oci_bind_by_name($stid, ':VOYAGE_IN', $data['VOYAGE_IN']);
    oci_bind_by_name($stid, ':VOYAGE_OUT', $data['VOYAGE_OUT']);
    oci_bind_by_name($stid, ':CALL_SIGN', $data['CALL_SIGN']);
    oci_bind_by_name($stid, ':OPERATOR_ID', $data['OPERATOR_ID']);
    oci_bind_by_name($stid, ':OPERATOR_NAME', $data['OPERATOR_NAME']);
    oci_bind_by_name($stid, ':ETA', $data['ETA']);
    oci_bind_by_name($stid, ':ETB', $data['ETB']);
    oci_bind_by_name($stid, ':ETD', $data['ETD']);
    oci_bind_by_name($stid, ':ATA', $data['ATA']);
    oci_bind_by_name($stid, ':ATB', $data['ATB']);
    oci_bind_by_name($stid, ':ATD', $data['ATD']);
    oci_bind_by_name($stid, ':ID_POD', $data['ID_POD']);
    oci_bind_by_name($stid, ':POD', $data['POD']);
    oci_bind_by_name($stid, ':ID_POL', $data['ID_POL']);
    oci_bind_by_name($stid, ':POL', $data['POL']);
    oci_bind_by_name($stid, ':OPEN_STACK', $data['OPEN_STACK']);
    oci_bind_by_name($stid, ':CLOSSING_TIME', $data['CLOSSING_TIME']);
    oci_bind_by_name($stid, ':CLOSSING_DOC', $data['CLOSSING_DOC']);
    oci_bind_by_name($stid, ':BERTHING_TIME', $data['BERTHING_TIME']);
    oci_bind_by_name($stid, ':START_WORK', $data['START_WORK']);
    oci_bind_by_name($stid, ':END_WORK', $data['END_WORK']);
    oci_bind_by_name($stid, ':DATE_SEND', $data['DATE_SEND']);
    oci_bind_by_name($stid, ':FLAG_SEND', $data['FLAG_SEND']);
    oci_bind_by_name($stid, ':ID_VSB_VOYAGE', $data['ID_VSB_VOYAGE']);
    oci_bind_by_name($stid, ':VESSEL_CODE', $data['VESSEL_CODE']);
    oci_bind_by_name($stid, ':VOYAGE', $data['VOYAGE']);
    oci_bind_by_name($stid, ':FIRST_ETD', $data['FIRST_ETD']);
    oci_bind_by_name($stid, ':RBM_DLD', $data['RBM_DLD']);
    oci_bind_by_name($stid, ':UPD_USER', $data['UPD_USER']);
    oci_bind_by_name($stid, ':UPD_DATE', $data['UPD_DATE']);
    oci_bind_by_name($stid, ':DLT_FLG', $data['DLT_FLG']);
    oci_bind_by_name($stid, ':CONTAINER_LIMIT', $data['CONTAINER_LIMIT']);
    oci_bind_by_name($stid, ':BERTH', $data['BERTH']);
    oci_bind_by_name($stid, ':NO_PKK', $data['NO_PKK']);
    oci_bind_by_name($stid, ':ORIGIN_PORT', $data['ORIGIN_PORT']);
    oci_bind_by_name($stid, ':LAST_PORT', $data['LAST_PORT']);
    oci_bind_by_name($stid, ':NEXT_PORT', $data['NEXT_PORT']);
    oci_bind_by_name($stid, ':ACTIVE', $data['ACTIVE']);
    oci_bind_by_name($stid, ':VESSEL_CLOSE', $data['VESSEL_CLOSE']);
    oci_bind_by_name($stid, ':ACTWKSL_DATE', $data['ACTWKSL_DATE']);
    oci_bind_by_name($stid, ':ACTWKCL_DATE', $data['ACTWKCL_DATE']);
    oci_bind_by_name($stid, ':ACTWKS_DATE', $data['ACTWKS_DATE']);
    oci_bind_by_name($stid, ':ACTWKC_DATE', $data['ACTWKC_DATE']);
    oci_bind_by_name($stid, ':ACTWKSL_TIME', $data['ACTWKSL_TIME']);
    oci_bind_by_name($stid, ':ACTWKCL_TIME', $data['ACTWKCL_TIME']);
    oci_bind_by_name($stid, ':ACTWKS_TIME', $data['ACTWKS_TIME']);
    oci_bind_by_name($stid, ':ACTWKC_TIME', $data['ACTWKC_TIME']);
    oci_bind_by_name($stid, ':NOBC11', $data['NOBC11']);
    oci_bind_by_name($stid, ':TGBC11', $data['TGBC11']);
    oci_bind_by_name($stid, ':EARLY_STACK', $data['EARLY_STACK']);
    oci_bind_by_name($stid, ':SERVICE_LANE_IN', $data['SERVICE_LANE_IN']);
    oci_bind_by_name($stid, ':SERVICE_LANE_OUT', $data['SERVICE_LANE_OUT']);
    oci_bind_by_name($stid, ':VESEL_TYPE_CD', $data['VESEL_TYPE_CD']);
    oci_bind_by_name($stid, ':SERVICE_TYPE', $data['SERVICE_TYPE']);
    oci_bind_by_name($stid, ':CANCEL_FLAG', $data['CANCEL_FLAG']);

    // Execute the SQL statement
    $result = oci_execute($stid);

    if (!$result) {
        $e = oci_error($stid);
        throw new Exception('Data insertion failed: ' . $e['message']);
    }

    // If everything was successful, return a success message
    echo json_encode(array('success' => 'Data inserted successfully'));
} catch (Exception $e) {
    // Return an error message in case of an exception
    echo json_encode(array('error' => $e->getMessage()));
} 
?>
