<?php

// --TAMBAH CONTAINER REQUEST STRIPPING
// --Model Dokumentasi
// -- Daftar Isi

// [1] - Insert ke tabel PLAN_CONTAINER_STRIPPING
// [2] - Insert ke tabel MASTER_CONTAINER 
// [3] - Insert ke tabel HISTORY_CONTAINER
// [4] - Update SUB_COUNTER ke tabel HISTORY_CONTAINER	

$db 			= getDB("storage");

$nm_user	= $_SESSION["NAME"];
$id_user	= $_SESSION["LOGGED_STORAGE"];
$id_yard	= $_SESSION["IDYARD_STORAGE"];
$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 
$no_req2	= $_POST["NO_REQ2"]; 
$status		= $_POST["STATUS"]; 
$berbahaya	= $_POST["BERBAHAYA"];
$no_do		= $_POST["NO_DO"];
$no_bl		= $_POST["NO_BL"];
$size		= $_POST["SIZE"];
$type		= $_POST["TYPE"];
$voyage		= $_POST["VOYAGE"];
$vessel		= $_POST["VESSEL"];
$tgl_stack	= $_POST["TGL_STACK"];
$no_ukk		= $_POST["NO_UKK"];
$nm_agen	= $_POST["NM_AGEN"];
$komoditi	= $_POST["KOMODITI"];
$tgl_bongkar	= $_POST["TGL_BONGKAR"];
$bp_id			= $_POST["BP_ID"];
$kd_consignee	= $_POST["KD_CONSIGNEE"];
$depo_tujuan	= $_POST["DEPO_TUJUAN"];
$sp2			= $_POST["SP2"];
$after_strip	= $_POST["AFTER_STRIP"];
$asal_cont	= $_POST["ASAL_CONT"];
$no_booking	= $_POST["NO_BOOKING"];
$blok_	= $_POST["BLOK"];
$slot_	= $_POST["SLOT"];
$row_	= $_POST["ROW"];
$tier_	= $_POST["TIER"];
$lokasi = $blok_."/".$row_."-".$slot_."-".$tier_;
// debug($_POST);die;

$cek_gato = "SELECT AKTIF
                FROM CONTAINER_DELIVERY
               WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y' ORDER BY AKTIF DESC";
$d_gato = $db->query($cek_gato);
$r_gato = $d_gato->fetchRow();
$l_gato = $r_gato["AKTIF"];
if($l_gato == 'Y'){
	echo "EXIST_DEL";
	exit();
}

$cek_gati = "SELECT AKTIF
                FROM CONTAINER_RECEIVING
               WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y' ORDER BY AKTIF DESC";
$d_gati = $db->query($cek_gati);
$r_gati = $d_gati->fetchRow();
$l_gati = $r_gati["AKTIF"];
if($l_gati == 'Y'){
	echo "EXIST_REC";
	exit();
}

$cek_stuf = "SELECT AKTIF
                FROM CONTAINER_STUFFING
               WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'";
$d_stuf = $db->query($cek_stuf);
$r_stuf = $d_stuf->fetchRow();
$l_stuf = $r_stuf["AKTIF"];
if($l_stuf == 'Y'){
	echo "EXIST_STUF";
	exit();
}

//added 26.04.14 06:44 pm - frenda
$cek_plan_strip = "SELECT AKTIF
                FROM CONTAINER_STRIPPING
               WHERE NO_CONTAINER = '$no_cont' AND AKTIF = 'Y'";
$r_plan_strip = $db->query($cek_plan_strip);
$rwplan_strip = $r_plan_strip->fetchRow();
$l_strip = $rwplan_strip["AKTIF"];
if ($l_strip == 'Y') {
	echo "EXIST_STRIPPING";
	exit();
}

 
if($tgl_bongkar == NULL){
	echo 'TGL_BONGKAR';
	exit();
}
if($no_booking == NULL){
	$no_booking = "VESSEL_NOTHING";
}
$tgl_mulai		= $_POST["tgl_mulai"];
$tgl_selesai		= $_POST["tgl_selesai"];

$no_req_rec	= substr($no_req2,4);	
$no_req_rec	= "REC".$no_req_rec;

//HANYA YANG GATO YANG BISA STRIPPING
$flag = 1;
if($asal_cont != "DEPO" ) {
	$cek_locate = "SELECT LOCATION, MLO, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont'";
	$r_locate = $db->query($cek_locate);
	$rw_locate = $r_locate->fetchRow();
	$count_hist = $db->query("SELECT COUNT(*) JUM FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont'");
	$r_counthi = $count_hist->fetchRow();
	if ($r_counthi["JUM"] > 1) {
		if ($rw_locate["LOCATION"] != "GATO") { 
			echo "EXIST_YARD";
			exit();
		}
	}
	$flag = 0;
}

$param = array(
                    "in_nocont" => $no_cont,
                    "in_planreq"    => $no_req,
                    "in_size" => $size,
                    "in_type" => $type,
                    "in_status" => $status,
                    "in_hz" => $berbahaya,       
                    "in_commodity" => $komoditi,
                    "in_voyin" => $voyage,
                    "in_after_strip" => $after_strip,
                    "in_asalcont" => $asal_cont,
                    "in_datedisch" => $tgl_bongkar,
                    "in_tglmulai" => $tgl_mulai,
                    "in_tglselesai" => $tgl_selesai,
                    "in_blok" => $blok_,
                    "in_slot" => $slot_,
                    "in_row"=> $row_,
                    "in_tier" => $tier_,
                    "in_nobooking"=> $no_booking,
                    "in_iduser" => $id_user,
                    "p_ErrMsg" => ""
                );
    $query_ins = "declare 
                begin pack_create_req_stripping.create_detail_strip(:in_nocont,:in_planreq,:in_size,:in_type,:in_status,:in_hz,:in_commodity,:in_voyin,:in_after_strip,:in_asalcont,:in_datedisch,:in_tglmulai,:in_tglselesai,:in_blok,:in_slot,:in_row,:in_tier,:in_nobooking,:in_iduser,:p_ErrMsg); end;";
    //print_r($param); die();
    $db->query($query_ins,$param);
    $msg = $param["p_ErrMsg"];
    
    echo $msg;
    exit();

?>