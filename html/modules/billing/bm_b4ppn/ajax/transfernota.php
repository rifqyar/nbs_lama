<?php
	$db=getDb();
    $id=$_POST['id_nota'];
	$idrpstv=$_POST['id_rpstv'];
    $userid=$_SESSION["PENGGUNA_ID"];
	$param = array(
                in_nota => $id,
                in_userid => $userid,
                out_msg => '',
                out_status => ''        
    );
    //print_r($param); die();
    $query = "declare begin nbs_transferariptk_bm(:in_nota, :in_userid, :out_msg, :out_status ); end;";
    $db->query($query,$param);
	$messagetransfer=$param["out_msg"];
    $statustransfer=$param["out_status"];
	echo $param["out_status"];
?>