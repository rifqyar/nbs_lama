<?php
$db=getDb();
$db->query("begin sync_master_agen(); end;");
			
echo "OK";

?>