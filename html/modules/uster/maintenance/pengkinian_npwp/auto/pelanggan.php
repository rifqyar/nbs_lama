<?php
$search		= strtoupper($_GET["term"]);

// Function to convert NPWP to integer
function convert_npwp_to_integer($npwp) {
    // Remove dots and dashes from the NPWP
    $npwp = preg_replace("/[^0-9]/", "", $npwp);
    // Convert to integer
    return $npwp;
}

$db 			= getDB("storage");

$search = convert_npwp_to_integer($search);

$query 			= "
SELECT
	*
FROM
	MST_PELANGGAN mp
WHERE
	REPLACE(REPLACE(NO_NPWP_PBM, '.', ''), '-', '') = '$search'
    OR REPLACE(REPLACE(NO_NPWP_PBM16, '.', ''), '-', '') = '$search'";

$result			= $db->query($query);
$row			= $result->getAll();	



echo json_encode($row);
