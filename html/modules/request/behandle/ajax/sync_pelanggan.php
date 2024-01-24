<?php
//debug ($_POST);die;
$db = getDB();

$db->query('BEGIN SYNC_PELANGGAN();END;');

?>