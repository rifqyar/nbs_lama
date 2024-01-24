<?php
//echo "tes";
//die();
outputRaw();
$error = array();

if (validatePostData($error)) {
   normalizePostData();
   
    $tgl = $_POST['tgl'];
    $periode = $_POST['periode'];
    $seri_awal = $_POST['seri_awal'];
    $seri_akhir = $_POST['seri_akhir'];
    $lembar = $_POST['lembar'];
    $keterangan = $_POST['keterangan'];
    $user_entry = $_SESSION['NAMA_LENGKAP'];
	
	  $db =getDB();
	  $q = "select generate_stok from dual";
	  $result = $db->query($q);
	  $row = $result->fetchRow();
	  $no_stok = $row["GENERATE_STOK"];
	  //echo "$no_stok";
	  //die();
    
	  $sql = "INSERT INTO pob_request 
            VALUES ('$no_stok',to_date('$tgl','mm/dd/yyyy'),'$periode','$seri_awal','$seri_akhir',
            '$lembar','$keterangan','N','$user_entry',SYSDATE)";
    //echo "$sql";
    //die();
    //$result = $db->query($query);
	  // echo "tes";
	  // die();
	  if ($db->query($sql)) {
	   //echo "tes";
	   //die();
        $idGen = $db->generatedId();
        header('Location: ' . _link(array(
            'sub' => 'add_ok',
            'id' => $idGen
        )));
        ob_end_flush();
        die();
    }
}
## error..
$_SESSION['__postback'][APPID] = $_POST;
header('Location: ' . _link(array(
    'sub' => 'add',
    'error' => 'error'
)));
?>
