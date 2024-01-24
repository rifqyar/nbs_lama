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
	//debug($_q);die;
	$arr_key = explode('|',$_q['KEY']);
    $sql = "UPDATE TR_KURS SET 
               START_DATE = TO_DATE('" . $_POST['START_DATE'] . "','DD-MM-YYYY'),
               END_DATE = TO_DATE('" . $_POST['END_DATE'] . "','DD-MM-YYYY'),
               KURS = '" . $_POST['KURS'] . "'
            WHERE START_DATE='$arr_key[0]' AND END_DATE='$arr_key[1]'";
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