<?php

function getInitials($string)
{
    $words = explode(' ', $string);
    $initials = '';
    foreach ($words as $word) {
        if (!empty($word)) {
            $initials .= strtoupper($word[0]);
        }
    }
    return $initials;
}

$db = getDB("storage");


try {
    $db = getDB("storage");
    $nm_pbm = $_POST['NM_PBM'];
    $almt_pbm = $_POST['ALMT_PBM'];
    $no_telp = $_POST['NO_TELP'];
    $no_npwp_pbm = $_POST['NO_NPWP_PBM'];
    $pelanggan_aktif = $_POST['PELANGGAN_AKTIF'];
    $no_npwp_pbm16 = $_POST['NO_NPWP_PBM16'];
    $activity = $_POST['ACTIVITY'];

    if ($activity == 'UPDATE') {
        $sql = "UPDATE MST_PELANGGAN SET 
                ALMT_PBM='$almt_pbm', 
                NO_TELP='$no_telp', 
                NO_NPWP_PBM='$no_npwp_pbm', 
                PELANGGAN_AKTIF='$pelanggan_aktif', 
                NO_NPWP_PBM16='$no_npwp_pbm16'
                WHERE NM_PBM='$nm_pbm'";

        if ($db->query($sql)) {
            $response['status'] = 'success';
            $response['message'] = 'Data updated successfully';
        } else {
            throw new Exception('Error updating record: ' . $db->error);
        }
    } else if ($activity == 'NEW') {
        $kd_pbm = getInitials($nm_pbm);
        // Get the highest NO_ACCOUNT_PBM value
        $result = $db->query("SELECT MAX(NO_ACCOUNT_PBM) as max_no_account FROM MST_PELANGGAN");
        if ($result) {
            $row = $result->fetchRow();
            $max_no_account = $row['max_no_account'] + 1;

            $sql = "INSERT INTO MST_PELANGGAN (KD_PBM, NM_PBM, ALMT_PBM, NO_TELP, NO_ACCOUNT_PBM, KD_PPN_PBM, NO_NPWP_PBM, KD_GUDANG1, KD_GUDANG2, KD_CABANG, PELANGGAN_AKTIF, UPDATE_DATE, NO_NPWP_PBM16) 
                   VALUES ('$kd_pbm', '$nm_pbm', '$almt_pbm', '$no_telp', '$max_no_account', '', '$no_npwp_pbm', '', '', '', '$pelanggan_aktif', SYSDATE, '$no_npwp_pbm16')";

            if ($db->query($sql)) {
                $response['status'] = 'success';
                $response['message'] = 'Data inserted successfully';
            } else {
                throw new Exception('Error inserting record: ' . $db->error);
            }
        } else {
            throw new Exception('Error fetching max account number: ' . $db->error);
        }
    } else {
        throw new Exception('Invalid activity');
    }
} catch (Exception $e) {
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
} 

$db->close();

echo json_encode($response);
