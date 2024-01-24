<?php

$db		= getDB("storage");

$nota	 = $_GET["n"];
$nipp	 = $_SESSION["NIPP"];
$no_req	 = $_GET["no_req"];
$no_nota = $_GET["no_nota"];

if($nota == 999)
{
	
	$no_req         = $_GET["no_req"];
	$query_cek	= "SELECT lpad((COUNT(1)+1),6,0) AS JUM, TO_CHAR(SYSDATE, 'MM') AS MONTH, TO_CHAR(SYSDATE, 'YY') AS YEAR FROM NOTA_DELIVERY WHERE TGL_NOTA BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$month		= $jum_["MONTH"];
	$year		= $jum_["YEAR"];
        
        $no_nota	= 'DEL05'.$month.$year.$jum;
	
        $query_cek	= "SELECT lpad(COUNT(1),6,0) AS JUM,  TO_CHAR(SYSDATE, 'YY') AS YEAR FROM NOTA_DELIVERY WHERE TGL_NOTA BETWEEN TRUNC(SYSDATE,'MONTH') AND LAST_DAY(SYSDATE) ";
	$result_cek	= $db->query($query_cek);
	$jum_		= $result_cek->fetchRow();
	$jum		= $jum_["JUM"];
	$year		= $jum_["YEAR"];
	$no_faktur	= '010.010.-'.$year.'.'.$jum;

        $cek_delivery   = "SELECT a.DELIVERY_KE, a.PERALIHAN, b.* FROM request_delivery a, v_mst_pbm b WHERE a.KD_EMKL = b.KD_PBM AND a.NO_REQUEST = '$no_req'";
        $result_cek	= $db->query($cek_delivery);
	$cek		= $result_cek->fetchRow();
	$delivery_ke	= $cek["DELIVERY_KE"];
	$peralihan	= $cek["PERALIHAN"];
        $nama   	= $cek["NM_PBM"];
	$npwp   	= $cek["NO_NPWP_PBM"];
        $alamat 	= $cek["ALMT_PBM"];

        
//        $cek_receiving  = "SELECT NO_CONTAINER FROM request_delivery WHERE   ";
//        $result_cek	= $db->query($cek_receiving);
//	$cek		= $result_cek->fetchRow();
//	$delivery_ke	= $cek["DELIVERY_KE"];
//	$peralihan	= $cek["PERALIHAN"];
        
//      $cek_receiving  = "SELECT RECEIVING_DARI FROM request_receiving WHERE ";
//      $result_cek	= $db->query($cek_receiving);
//	$cek		= $result_cek->fetchRow();
//	$delivery_ke	= $cek["DELIVERY_KE"];
//	$peralihan	= $cek["PERALIHAN"];

        //nota delivery ke TPK hanya karena faktor YOR di create di nota kirim per vessel voyage
//       if (($delivery_ke == 'TPK') && ($peralihan == 'T')){
//            $query_insert_nota	= "INSERT INTO NOTA_DELIVERY(NO_NOTA, NO_FAKTUR, NO_REQUEST, NIPP_USER, LUNAS, CETAK_NOTA, TGL_NOTA) VALUES ('$no_nota', '$no_faktur', '$no_req', '$nipp', 'PIUTANG', 1, SYSDATE)";	
//        //    echo $query_insert_nota;die;
//       } else {   
            $query_insert_nota	= "INSERT INTO NOTA_DELIVERY(NO_NOTA, NO_FAKTUR, NO_REQUEST, NIPP_USER, LUNAS, CETAK_NOTA, TGL_NOTA, EMKL, ALAMAT, NPWP) VALUES ('$no_nota', '$no_faktur', '$no_req', '$nipp', 'NO', 1, SYSDATE,'$nama','$alamat','$npwp')";	
//       } 
            $tagihan = 0;
        
	if($db->query($query_insert_nota))
	{
             
		header('Location:'.HOME.APPID.'/print_nota?no_nota='.$no_nota);
				
	}
}
else
{
	$no_nota		= $_GET["no_nota"];
	$nota_			= $nota + 1;
	$query_update           = "UPDATE NOTA_DELIVERY SET CETAK_NOTA = '$nota_' WHERE NO_NOTA = '$no_nota'";
	//echo $query_update;die;
	if($db->query($query_update))
	{
		//echo HOME;
		header('Location:'.HOME.APPID.'/print_nota?no_nota='.$no_nota);		
	}	
}






?>