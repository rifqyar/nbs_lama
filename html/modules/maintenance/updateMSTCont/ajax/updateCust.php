<?php
	$db=getDb();
	$id=$_POST['ID'];
	$qry="begin sync_pelanggan_upd('$id'); end;";
	if($db->query($qry))
	{
		echo "Success";
	}
	else
		echo "Failed..";
	
	
	
?>