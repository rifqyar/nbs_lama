<?php

outputRaw();
$filter = array(
    array(
        'name' => 'ID_DUMMY'
    )
);
_map($filter);
$error = array();
//if (validatePostData($error)) {
//    normalizePostData();
    $db  = getDB();
	$sql = "UPDATE dummy SET 
               status_dummy='N'
            WHERE id_dummy=" . $_q['ID_DUMMY'] . "";
    if ($db->query($sql)) {
        // update ok
        header('Location: ' . _link(array(
            'sub' => 'delete_ok'
        ), $_q));
        ob_end_flush();
        die();
    }
//}
## error..
$_SESSION['__postback'][APPID] = $_POST;
header('Location: ' . _link(array(
    'sub' => 'delete',
    'error' => 'error'
), $_q));

?>