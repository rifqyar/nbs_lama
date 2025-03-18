<?php
	$sql="declare
	begin
    nbs_transfergagalar;
	end;";

	$db=getDb();
	$rs=$db->query($sql);

	echo "S";

	
?>