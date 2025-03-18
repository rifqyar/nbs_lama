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
    $user_invoice = $_SESSION['NAMA_LENGKAP'];
    
    $db  = getDB();
    $q = "SELECT GENERATE_TAX FROM DUAL";	
    $result = $db->query($q);
	  $row = $result->fetchRow();
	  $tax_no = $row["GENERATE_TAX"];
    
    $sql = "UPDATE POB_PENDAPATAN SET 
			     FLAG = 'I',
			     USER_INVOICE = '$user_invoice',
           TGL_INVOICE = SYSDATE,
           TAX_NO = '$tax_no'               
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
