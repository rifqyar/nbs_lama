<?php
		$nama		= strtoupper($_GET["term"]);
		$terminalid = $_GET['TERMINAL_ID'];
		$db			= getDB('dbint');

		$query		= "SELECT 
								VESSEL,
								ID_VSB_VOYAGE,
								VOYAGE_IN,
								VOYAGE_OUT
						FROM 
								OPUS_REPO.M_VSB_VOYAGE
						WHERE 
								(UPPER(VESSEL) LIKE '%$nama%' OR VOYAGE_IN LIKE '%$nama%')
						";
		$result		= $db->query($query);
		$row		= $result->getAll();

		echo json_encode($row);
		die();

?>