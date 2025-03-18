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
    $db  = getDB();	
    $sql = "UPDATE OG_HNOTA2 SET 
			   NO_JKM = '" . $_POST['NO_JKM'] . "',
			   TGL_JKM = TO_DATE('" . $_POST['TGL_JKM'] . "','DD-MM-YYYY')               
            WHERE ID_NOTA='".$_q['KEY']."'";
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