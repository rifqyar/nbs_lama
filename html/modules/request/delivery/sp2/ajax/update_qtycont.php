<?php
$db=getDb();
$id=$_POST['ID'];
$param_temp= array (
				"v_idreq" => "$id",
				"v_out" => ""
			);
$query="declare begin 
                prc_del_sumqtycont(:v_idreq,:v_out);
            end;";
$db->query($query,$param_temp);
echo $param_temp['v_out'];
?>