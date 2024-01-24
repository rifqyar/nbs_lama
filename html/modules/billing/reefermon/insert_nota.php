<?php

$db			= getDB();
$no_req 	= $_GET["no_req"];
$id_user	= $_SESSION["NAMA_PENGGUNA"];

//echo "begin pack_nota_renamecontainer.proc_header_nota_rename('$no_req'); end;";die;
$sql_xpi="begin pack_nota_monreefer.proc_header_nota_monreefer('$no_req','$id_user'); end;";
if($db->query($sql_xpi))
{
	header('Location:'.HOME.APPID.'.print/print_nota_lunas?no_req='.$no_req);	
}
else
{
	echo "<br><br>There is any problem; Contact your IT Support";die;
}

 
?>