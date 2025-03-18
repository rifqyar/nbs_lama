<?php
$db=getDb();
$db->query("begin sync_master_pelanggan(); end;");
			
echo "OK";

?>