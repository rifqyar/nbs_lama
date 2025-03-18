<?php
outputRaw();
$filter = array(
    array(
        'name' => 'KEY'
    )
);
_map($filter);
$error = array();

if (validatePostData($error)) {
    normalizePostData();
    $receipt_no = $_POST['RECEIPT'];
    $jkm_no = $_POST['JKM'];
    $jkm_date = $_POST['TGL'];
        
    $db  = getDB();	
    $sql = "UPDATE POB_PENDAPATAN SET 
			     RECEIPT_NO = '$receipt_no',
			     JKM_NO = '$jkm_no',
           JKM_DATE = TO_DATE('$jkm_date','MM/DD/YYYY')               
           WHERE NO_NOTA='".$_q['KEY']."'";
	//echo $sql;die;
    if ($db->query($sql)) {
        // update ok
        header('Location: ' . _link(array(
            'sub' => 'edit_ok'
        ), $_q));
        ob_end_flush();
        die();
    }
}
## error..
$_SESSION['__postback'][APPID] = $_POST;
header('Location: ' . _link(array(
    'sub' => 'edit',
    'error' => 'error'
), $_q));
?>
