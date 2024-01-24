<?php
$db         = getDB("storage");
$no_req_s   = $_POST["NO_REQ"];
$no_req     = $_POST["NO_REQOLD"];
$ID_USER    = $_SESSION["LOGGED_STORAGE"];
$keterangan = 'OK';


$param = array('in_req_old'=>$no_req,
                      'in_req_new'=>$no_req_s,
                      'in_asalcont'=>'TPK',
                      'in_iduser'=>$ID_USER,
                      'in_ket'=>$keterangan,
                      'p_ErrMsg'=>''
                     );
//debug($param);
//die();
$qparam = "declare begin pack_create_req_stripping.create_perpanjangan_strip(:in_req_old,:in_req_new,:in_asalcont,:in_iduser,:in_ket,:p_ErrMsg); end;";
$db->query($qparam,$param); 
$msg = $param['p_ErrMsg'];
echo $msg; die();
//header('Location: '.HOME.APPID.'/edit?no_req='.$no_req);
//echo $msg;
//die();


?>