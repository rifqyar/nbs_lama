<?php
$db = getDB();

$tid = $_POST['TID'];
$query = "BEGIN DELETE FROM TID_REPO WHERE TID='$tid'; COMMIT;END;";

$db->query($query);
//echo ($query);
echo "OK";


?>