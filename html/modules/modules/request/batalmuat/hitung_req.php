<?php

$req=$_GET['id'];
$db=getDB();
$query="DECLARE PID_REQ VARCHAR2(20); BEGIN PID_REQ := '$req'; MANUAL_PETIKEMAS.PERHITUNGAN_SPDUA(PID_REQ);	END;";
$db->query($query);
header('Location: '.HOME.'request.delivery/');
?>