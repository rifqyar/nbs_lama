<?php
$tl = xliteTemplate('add.htm');

//edited by mustadio
/*
if($_GET['id'] != NULL)
{
	//echo "tes";die;
	$no_req	= $_GET['id'];
	$db=getDB();
	$query="SELECT TIPE_REQ,
				   EMKL,
				   ALAMAT_EMKL,
				   NPWP,
				   SHIPPING_LINE,
				   VESSEL, 
				   VOYAGE,
				   NOMOR_INSTRUKSI,
				   KET
				FROM BH_REQUEST
				WHERE ID_REQUEST = '$no_req'";
	//echo $query;die;			
	$result_q	= $db->query($query);
	//echo $result_q;die;
	$row_q	= $result_q->getAll();
	echo $row_q[0];die;
	/*
	$result_rec_dari = $db->query($query_rec_dari);
	$row_rec_dari = $result_rec_dari->fetchRow();
	$rec_dari = $row_rec_dari["RECEIVING_DARI"];
	
	
	echo $row_q["TIPE_REQ"];die;
	$tipe_req	= $row_q[0]["EMKL"];
	echo $tipe_req;die;
	
	$emkl		=$_POST['emkl'];
	$alm		=$_POST['alamat'];
	$npwp		=$_POST['npwp'];
	$ship_line	=$_POST['ship_line'];
	$vessel		=$_POST['vessel'];
	$voyage		=$_POST['voy'];
	$no_bc		=$_POST['no_bc_i'];
	$no_req		=$_POST['no_req'];
	$ket		=$_POST['ket'];
	$id_user	=$_SESSION["PENGGUNA_ID"];
	
}
*/
//else
//{
	//
	$tipe_req=$_POST['tipe_req'];
	$emkl=$_POST['emkl'];
	$alm=$_POST['alamat'];
	$npwp=$_POST['npwp'];
	$ship_line=$_POST['ship_line'];
	$vessel=$_POST['vessel'];
	$voyage=$_POST['voy'];
	$no_bc=$_POST['no_bc_i'];
	//$no_req=$_POST['no_req'];
	$ket=$_POST['ket'];
	$ukk=$_POST['ukk'];
	$id_user=$_SESSION["PENGGUNA_ID"];
	
	if($tipe_req=="Export")
		$ket_id = "BHEX";
	else
		$ket_id = "BHIM";

	$db=getDB();
	$q_max = "SELECT NVL(MAX(SUBSTR(ID_REQUEST,11,6)),0)+1 AS NO FROM BH_REQUEST WHERE SUBSTR(ID_REQUEST,5,4)=TO_CHAR(SYSDATE,'YYYY') AND SUBSTR(ID_REQUEST,1,4)='".$ket_id."'";
	$row = $db->query($q_max)->fetchRow();
	$no_req = $ket_id.date("Ym").str_pad($row['NO'],6,0,STR_PAD_LEFT);
	
	$query="INSERT INTO BH_REQUEST (ID_REQUEST,TGL_REQUEST,NOMOR_INSTRUKSI,ID_USER,VOYAGE,VESSEL,EMKL,ALAMAT_EMKL,NPWP,TIPE_REQ,SHIPPING_LINE,KET,NO_UKK) 
			VALUES ('$no_req',SYSDATE,'$no_bc','$id_user','$voyage','$vessel','$emkl','$alm','$npwp','$tipe_req','$ship_line','$ket','$ukk')";
	$result_q=$db->query($query); 
//}
$tl->assign("var","save2");
$tl->assign("no_req",$no_req);
$tl->assign("tipe_req",$tipe_req);
$tl->assign("emkl",$emkl);
$tl->assign("npwp",$npwp);
$tl->assign("ves",$vessel);
$tl->assign("voy",$voyage);
$tl->assign("alm",$alm);
$tl->assign("ship_line",$ship_line);
$tl->assign("ket",$ket);
$tl->assign("no_bc",$no_bc);
$tl->assign("ukk",$ukk);


$tl->renderToScreen();
?>