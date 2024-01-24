<?php
outputRaw();
$error = array();

if (validatePostData($error)) {
    normalizePostData();
	
    $db  = getDB();
	$sql = "INSERT INTO TR_KURS (START_DATE, END_DATE, KURS) 
			VALUES(
				   TO_DATE('" . $_POST['START_DATE'] . "','DD-MM-YYYY'),
				   TO_DATE('" . $_POST['END_DATE'] . "','DD-MM-YYYY'),
				   '" . $_POST['KURS'] . "')";
    if ($db->query($sql)) {
	
        $idGen = $db->generatedId();
        header('Location: ' . _link(array(
            'sub' => 'add_ok',
            'id' => $idGen
        )));
        ob_end_flush();
        die();
    }
}
## error..
$_SESSION['__postback'][APPID] = $_POST;
header('Location: ' . _link(array(
    'sub' => 'add',
    'error' => 'error'
)));
?>
