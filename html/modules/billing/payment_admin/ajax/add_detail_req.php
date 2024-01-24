<?php

//debug ($_POST);die;
$db = getDB();

$no_cont = strtoupper($_POST["NC"]);
$no_req = $_POST["REQ"];
$status = $_POST["STC"];
$hz = $_POST["HC"];
$size = $_POST["SC"];
$tipe = $_POST["TC"];
$comm = $_POST["COMM"];
$imo = $_POST["IMO"];
$iso = $_POST["ISO"];
$book = $_POST["BOOK"];
$hgc = $_POST['HGC'];
$ship = $_POST['SHIP'];
$car = $_POST['CAR'];
$tmp = $_POST['TEMP'];
$oh = $_POST['OH'];
$ow = $_POST['OW'];
$ol = $_POST['OL'];
$unnumber = $_POST['UNNUMBER'];
$nor = $_POST['NOR'];
$ukk = $_GET['ukk'];
$vessel = $_GET['vessel'];
$voyin = $_GET['voyin'];
$voyout = $_GET['voyout'];
$weight_npe = $_POST['WEIGHT_NPE'];

//validasi Cek Apakah container ada di delivery Uster
$sqlx = "SELECT COUNT(*) CEK
									 FROM USTER.NOTA_DELIVERY
									 WHERE NO_REQUEST =
									 (SELECT NO_REQUEST FROM	
										(SELECT NO_REQUEST FROM USTER.history_container WHERE no_container = '".$no_cont."' ORDER BY TGL_UPDATE DESC)
									  WHERE ROWNUM <= 1) AND LUNAS != 'YES' AND STATUS IN ('NEW','KOREKSI','PERP') AND SUBSTR (no_request, 0, 3) = 'DEL'";

							$rsx = $db->query($sqlx);
							$rowx = $rsx->fetchRow();
							if($rowx['CEK']==1) {
								echo "Belum Lunas di Uster"; exit;
							}
							else {
								echo "Tidak Ada di Uster  ";
							}

							//echo $sqlx;
							//die();


//query cek tabel master container

$param_b_var = array(
    "v_nc" => "$no_cont",
    "v_req" => "$no_req",
    "v_stc" => "$status",
    "v_hc" => "$hz",
    "v_sc" => "$size",
    "v_tc" => "$tipe",
    "v_comm" => "$comm",
    "v_imo" => "$imo",
    "v_iso" => "$iso",
    "v_book" => "$book",
    "v_hgc" => "$hgc",
    "v_ship" => "$ship",
    "v_car" => "$car",
    "v_tmp" => "$tmp",
    "v_oh" => "$oh",
    "v_ow" => "$ow",
    "v_ol" => "$ol",
    "v_un" => "$unnumber",
	"v_nor" => "$nor",
    "v_weightnpe" => "$weight_npe",
    "v_msg" => ""
);

// print_r($param_b_var); die();

$cek_limit = "select CONTAINER_LIMIT from M_VSB_VOYAGE@dbint_link where id_vsb_voyage=$ukk";
$res = $db->query($cek_limit)->fetchRow();
//echo $cek_limit . "<br/>";

$cont_limit = $res['CONTAINER_LIMIT'];
echo "container limit: " . $cont_limit;


$cek_row = "SELECT 
                    count(1) JUMLAH
            FROM 
                    M_CYC_CONTAINER@dbint_link a, M_VSB_VOYAGE@dbint_link b
            WHERE 
                    a.VESSEL = b.VESSEL
                    and a.VOYAGE_IN = b.VOYAGE_IN
                    and a.VOYAGE_OUT = b.VOYAGE_OUT
                    and a.VESSEL = '$vessel'
                    and a.VOYAGE_IN = '$voyin'
                    and a.VOYAGE_OUT = '$voyout'";

//echo $cek_row;
$hasil_cek = $db->query($cek_row)->fetchRow();
$jumlah_row = $hasil_cek['JUMLAH'];

//echo "jumlah row" . $jumlah_row;


if(($cont_limit > $jumlah_row) or empty($cont_limit)){

$query = "declare begin proc_add_cont_anne(:v_nc,:v_req,:v_sc,:v_tc,:v_stc,:v_hc,:v_comm,:v_imo,:v_iso,:v_book,:v_hgc,:v_ship,:v_car,:v_tmp,:v_oh, :v_ow, :v_ol,:v_un,:v_nor,:v_weightnpe,:v_msg); end;";

$db->query($query, $param_b_var);
$msg = $param_b_var['v_msg'];
//echo ($query);
echo $msg;
}

else{
    
    echo "container sudah mencapai limit booking";
    return false;      
    
}



?>