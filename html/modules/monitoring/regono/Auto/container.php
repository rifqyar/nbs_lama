<?php

$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB('pnoadm');

$query 			= "SELECT
											CYY_CONT_CONTNO,
											CYY_CONT_REGONO
							 FROM
											CYY_CONTAINER
							 WHERE
											CYY_CONT_CONTNO = '$no_cont'";

$result			= $db->query($query);
$row			= $result->getAll();


//print_r($row);

echo json_encode($row);

die();
?>
