<?php
//$tl 	=  xliteTemplate('cont_list.htm');
$db = getDB("storage");
$db4	= getDB("uster_ict");
$nm_user	= $_SESSION["NAME"];
$id_user	= $_SESSION["LOGGED_STORAGE"];
$no_nota 	= $_POST["NO_NOTA"];
$trx_number 	= $_POST["TRX_NUMBER"];
$no_request = $_POST["NO_REQUEST"];
$kegiatan = $_POST["KEGIATAN"];
$keterangan = $_POST["KETERANGAN"]; 

// echo "$nm_user: ".$nm_user."<br />$no_nota :".$no_nota ;die;

//echo $no_nota."-".$no_request."-".$kegiatan; die;

if($kegiatan == 'STRIPPING') {
	$query_add4 = "UPDATE REQUEST_STRIPPING SET NOTA = 'N', NOTA_PNKN='N', KOREKSI = 'Y', TGL_KOREKSI = SYSDATE, ID_USER_KOREKSI = '$id_user' WHERE NO_REQUEST = '$no_request'";
	$query_up4 = "UPDATE NOTA_STRIPPING SET STATUS = 'BATAL', KET_KOREKSI = '$keterangan' WHERE NO_NOTA = '$no_nota'";
	$query_up7 = "UPDATE NOTA_RELOKASI_MTY SET STATUS = 'BATAL', KET_KOREKSI = '$keterangan' WHERE NO_NOTA = '$no_nota'";
	// $query_up7 = "UPDATE NOTA_RELOKASI_MTY SET STATUS = 'BATAL', KET_KOREKSI = '$keterangan' WHERE NO_REQUEST = '$no_request'";
	$db->query($query_add4);
	$db->query($query_up7);
	$db->query($query_up4);
	$db4->query($query_up7);
	$db4->query($query_up4);
}
if($kegiatan == 'STUFFING') {
	$query_add3 = "UPDATE REQUEST_STUFFING SET NOTA = 'N', KOREKSI = 'Y', TGL_KOREKSI = SYSDATE, ID_USER_KOREKSI = '$id_user' WHERE NO_REQUEST = '$no_request'";
	$db->query($query_add3);

} else if ($kegiatan == 'PNKN_STUF') {
	$query_add3_ = "UPDATE REQUEST_STUFFING SET NOTA_PNKN = 'N', KOREKSI_PNKN = 'Y', TGL_KOREKSI = SYSDATE, ID_USER_KOREKSI = '$id_user' WHERE NO_REQUEST = '$no_request'";
	$db->query($query_add3_);
}

if($kegiatan == 'PNKN_DEL'){
	$query_add2 = "UPDATE REQUEST_DELIVERY SET NOTA_PNKN = 'N', KOREKSI_PNKN = 'Y', TGL_KOREKSI = SYSDATE, ID_USER_KOREKSI = '$id_user' WHERE NO_REQUEST = '$no_request'";
	$db->query($query_add2);
} else if($kegiatan == 'DELIVERY'){
	$query_add2 = "UPDATE REQUEST_DELIVERY SET NOTA = 'N', KOREKSI = 'Y', TGL_KOREKSI = SYSDATE, ID_USER_KOREKSI = '$id_user' WHERE NO_REQUEST = '$no_request'";
	$db->query($query_add2);
}
if($kegiatan == 'BATAL_MUAT'){
	$q_batal = "UPDATE REQUEST_BATAL_MUAT SET NOTA = 'N', KOREKSI = 'Y', TGL_KOREKSI = SYSDATE, ID_KOREKSI = '$id_user' WHERE NO_REQUEST = '$no_request'";
	$db->query($q_batal);
	$query_up5_ = "UPDATE NOTA_BATAL_MUAT SET STATUS = 'BATAL', KET_KOREKSI = '$keterangan' WHERE NO_NOTA = '$no_nota'";
	$db->query($query_up5_);
}
$query_add1 = "UPDATE REQUEST_RECEIVING SET NOTA = 'N', KOREKSI = 'Y', TGL_KOREKSI = SYSDATE, ID_USER_KOREKSI = '$id_user' WHERE NO_REQUEST = '$no_request'";

$query_add5 = "UPDATE REQUEST_RELOKASI SET NOTA = 'N', KOREKSI = 'Y', TGL_KOREKSI = SYSDATE, ID_USER_KOREKSI = '$id_user' WHERE NO_REQUEST = '$no_request'";
$query_up1 = "UPDATE NOTA_RECEIVING SET STATUS = 'BATAL', KET_KOREKSI = '$keterangan' WHERE NO_NOTA = '$no_nota'";
$query_up2 = "UPDATE NOTA_DELIVERY SET STATUS = 'BATAL', KET_KOREKSI = '$keterangan' WHERE NO_NOTA = '$no_nota'";
$query_up3 = "UPDATE NOTA_STUFFING SET STATUS = 'BATAL', KET_KOREKSI = '$keterangan' WHERE NO_NOTA = '$no_nota'";

$query_up5 = "UPDATE NOTA_RELOKASI SET STATUS = 'BATAL', KET_KOREKSI = '$keterangan' WHERE NO_NOTA = '$no_nota'";

$cek_itpk 	= "SELECT COUNT(*) JUM FROM NOTA_ALL_H WHERE NO_FAKTUR = '$trx_number'";
$ritpk 		= $db->query($cek_itpk)->fetchRow();
if ($ritpk[JUM] == 0) {
	$query_up6 = "UPDATE ITPK_NOTA_HEADER SET STATUS = '5' WHERE TRX_NUMBER = '$trx_number'";
}
else {
	$query_up6 = "UPDATE NOTA_ALL_H SET STATUS = 'BATAL' WHERE NO_NOTA = '$no_nota'";
}
$query_up8 = "UPDATE NOTA_PNKN_DEL SET STATUS = 'BATAL', KET_KOREKSI = '$keterangan' WHERE NO_NOTA = '$no_nota'";
$query_up9 = "UPDATE NOTA_PNKN_STUF SET STATUS = 'BATAL', KET_KOREKSI = '$keterangan' WHERE NO_NOTA = '$no_nota'";

$query_up4 = "UPDATE NOTA_STRIPPING SET STATUS = 'BATAL', KET_KOREKSI = '$keterangan' WHERE NO_NOTA = '$no_nota'";
$query_up7 = "UPDATE NOTA_RELOKASI_MTY SET STATUS = 'BATAL', KET_KOREKSI = '$keterangan' WHERE NO_NOTA = '$no_nota'";

$db->query($query_up7);
$db->query($query_up4);
$db4->query($query_up7);
$db4->query($query_up4);


$db->query($query_add1);


$db->query($query_add5);
$db->query($query_up1);
$db->query($query_up2);
$db->query($query_up3);
$db->query($query_up5);
$db->query($query_up6);

$db->query($query_up8);
$db->query($query_up9);

$db4->query($query_up1);
$db4->query($query_up2);
$db4->query($query_up3);
$db4->query($query_up5);
$db4->query($query_up6);

$db4->query($query_up8);
$db4->query($query_up9); 

//if (($db->query($query_add1)) OR ($db->query($query_add2)) OR ($db->query($query_add3)) OR ($db->query($query_add4)) OR ($db->query($query_add5))){
echo 'sukses';die;

?>