<?php

$db = getDB();

$query = "DELETE FROM YD_TEMP_CELL";

$db->query($query);


echo 'allocation canceled';


?>