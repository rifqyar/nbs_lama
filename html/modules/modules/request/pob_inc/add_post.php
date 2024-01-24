<?php

outputRaw();
$error = array();

if (validatePostData($error)) {
   normalizePostData();
   
    $no_stok = $_POST['STOK'];
	  $periode = $_POST['PERIODE'];
	  $serix = $_POST['SERIX'];
	  $seriy = $_POST['SERIY'];
	  $lembar = $_POST['LEMBAR'];
	  $tgl = $_POST['TGL'];
	  $keterangan = $_POST['KETERANGAN'];
	  $total = $_POST['TOTAL'];
	  $ppn = $_POST['PPN'];
	  $pendapatan = $_POST['PENDAPATAN'];
    $user_entry = $_SESSION['NAMA_LENGKAP'];
	
	  $db =getDB();
	  $q = "select generate_nota from dual";
	  $result = $db->query($q);
	  $row = $result->fetchRow();
	  $no_nota = $row["GENERATE_NOTA"];
	  //echo "$no_nota";
	  //die();
    
	  $sql = "INSERT INTO pob_pendapatan (no_nota,tgl_nota,no_stok,jml_lembar,seri_x,seri_y,tarif,total,ppn,pendapatan,
            keterangan,flag,user_entry,tgl_entry)
            VALUES ('$no_nota',to_date('$tgl','mm/dd/yyyy'),'$no_stok','$lembar','$serix','$seriy','11000',
                    '$total','$ppn','$pendapatan','$keterangan','C','$user_entry',SYSDATE)";
    //echo "$sql";
    //die();
    $update = "UPDATE pob_request
            SET FLAG='C'
            WHERE NO_STOK = '$no_stok'";
    $result = $db->query($update);
    
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
