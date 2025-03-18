<?php

$list_cont	= explode('&',$_SERVER["QUERY_STRING"]);
//print_r($list_cont);die;

//array_pop($list_cont);

$jml_cont = count($list_cont);
$user	= $_SESSION["NAMA_PENGGUNA"];
$idvs   = $_GET['noukk'];

$cek = '';
//print_r($jml_cont);die;
$db 	= getDB();

$query6  = "select count(1) JML from yd_yard_allocation_planning where ID_VS = '$idvs' and status_bm = 'Bongkar'";
$result6 = $db->query($query6);
$jml_box = $result6->fetchRow();
$box	 = $jml_box['JML'];

if ($box <= 0){
$query5  = "delete from tb_booking_cont_area_gr where id_vs = '$idvs'";
$db->query($query5);
}

for($i=0; $i<$jml_cont; $i++)
{
	
	$kater 		= substr($list_cont[$i],9);
	//echo $kater;
	$dat 		= str_replace('+','',$kater);
	$data		= explode('_',$dat);
	$kat 		= $data[0];
	$id_book 	= $data[1];
	//echo $i." ";
	//echo $kat;
	//echo " ".$id_book;
	//echo "<br/>";
	$query2	= "SELECT SIZE_CONT,TYPE_CONT, STATUS_CONT, ID_VS, HZ, TYPE_REFFER, ID_KATEGORI, TEUS, BOX FROM tb_booking_cont_area where ID_BOOK = '$id_book'";
	$data	= $db->query($query2);
	$result	= $data->fetchRow();
	
	$size   = $result['SIZE_CONT'];
	$type   = $result['TYPE_CONT'];
	$stat   = $result['STATUS_CONT'];
	$idvs   = $result['ID_VS'];
	$hz   	= $result['HZ'];
	$ref    = $result['TYPE_REFFER'];
	$te   	= $result['TEUS'];
	$bo     = $result['BOX'];
	$katego	= $result['ID_KATEGORI'];

	if (($result['ID_KATEGORI'] <> NULL) OR ($result['ID_KATEGORI'] <>'')){
		if ($kat <> $result['ID_KATEGORI']){
			$query7  = "select count(1) JUM from tb_booking_cont_area_gr where id_vs = '$idvs' and kategori = '$kat'";
			$data7	 = $db->query($query7);
			$result7 = $data7->fetchRow();
			$jum 	 = $result7['JUM'];
			if ($jum <= 0){
				$query3	= "INSERT INTO TB_BOOKING_CONT_AREA_GR
				(ID_VS, KATEGORI, SIZE_CONT, TYPE_CONT, STATUS_CONT, ID_USER,CREATE_DATE, E_I, BOX, TEUS, ALLOCATED_LEFT) 
				VALUES ('$idvs','$kat','','','','$user',SYSDATE,'I','$bo','$te','$te')";
				$db->query($query3);
				$query67  = "select ID_KATEGORI from tb_booking_cont_area_gr WHERE ID_VS = '$idvs' and KATEGORI = '$kat'";
				$data67	  = $db->query($query67);
				$result67 = $data67->fetchRow();
				$jum7 	  = $result67['ID_KATEGORI'];
				
				$query8	  = "UPDATE TB_BOOKING_CONT_AREA SET ID_KATEGORI = '$jum7', KATEGORI_GROUP = '$kat', USER_GROUP = '$user' where ID_BOOK = '$id_book'";
				$db->query($query8);
				$query9	  = "UPDATE ISWS_LIST_CONTAINER SET ID_BOOK = '$jum7' WHERE ID_BOOK = '$katego' AND NO_UKK = '$no_ukk' AND E_I = 'I'";
				$db->query($query9);
			} else {
				$query67  = "select ID_KATEGORI from tb_booking_cont_area_gr WHERE ID_VS = '$idvs' and KATEGORI = '$kat'";
				$data67	  = $db->query($query67);
				$result67 = $data67->fetchRow();
				$jum7 	  = $result67['ID_KATEGORI'];
				
				$query7	  = "UPDATE TB_BOOKING_CONT_AREA SET ID_KATEGORI = '$jum7', KATEGORI_GROUP = '$kat', USER_GROUP = '$user' where ID_BOOK = '$id_book'";
				$db->query($query7);
				
				$query2	= "UPDATE TB_BOOKING_CONT_AREA_GR SET BOX = BOX+$bo, TEUS= TEUS+$te, ALLOCATED_LEFT = ALLOCATED_LEFT+$te where KATEGORI = '$kat' AND ID_VS = '$idvs'";
				$db->query($query2);
				$query9	  = "UPDATE ISWS_LIST_CONTAINER SET ID_BOOK = '$jum7' WHERE ID_BOOK = '$katego' AND NO_UKK = '$no_ukk' AND E_I = 'I'";
				$db->query($query9);
			}
		}
	} else {

		$query4  = "select count(1) JUM from tb_booking_cont_area_gr where id_vs = '$idvs' and kategori = '$kat'";
		$data4	 = $db->query($query4);
		$result4 = $data4->fetchRow();
		$jum 	 = $result4['JUM'];
		if ($jum <= 0){
		$query3	= "INSERT INTO TB_BOOKING_CONT_AREA_GR
		(ID_VS, KATEGORI, SIZE_CONT, TYPE_CONT, STATUS_CONT, ID_USER,CREATE_DATE, E_I) 
		VALUES ('$idvs','$kat','','','','$user',SYSDATE,'I')";
		$db->query($query3);
		}
		
		$query6  = "select ID_KATEGORI from tb_booking_cont_area_gr WHERE ID_VS = '$idvs' and KATEGORI = '$kat'";
		$data6	 = $db->query($query6);
		$result6 = $data6->fetchRow();
		$jum6 	 = $result6['ID_KATEGORI'];

		
		//echo "UPDATE TB_BOOKING_CONT_AREA SET KATEGORI_GROUP = '$kat' where ID_BOOK = '$id_book'";
		$query2	= "UPDATE TB_BOOKING_CONT_AREA SET ID_KATEGORI = '$jum6', KATEGORI_GROUP = '$kat', USER_GROUP = '$user' where ID_BOOK = '$id_book'";
		$db->query($query2);
		
		$query3  = "select sum(box) box, sum(teus) teus, kategori_group from tb_booking_cont_area where id_vs = '$idvs' group by kategori_group";
		$data3	 = $db->query($query3);
		$result3 = $data3->getAll();
		foreach($result3 as $row){
				$box    = $row['BOX'];
				$teus   = $row['TEUS'];
				$kate   = $row['KATEGORI_GROUP'];
				$query2	= "UPDATE TB_BOOKING_CONT_AREA_GR SET BOX = '$box', TEUS = '$teus', ALLOCATED_LEFT = '$teus' where KATEGORI = '$kate' AND ID_VS = '$idvs'";
				$db->query($query2);
	}	
		
	}

}

$query9  = "select ID_KATEGORI from tb_booking_cont_area_gr WHERE ID_VS = '$idvs'";
$data9	 = $db->query($query9);
$result9 = $data9->getAll();
foreach($result9 as $row){
	$jum9     = $row['ID_KATEGORI'];
	$query21  = "select COUNT(1) JUM from tb_booking_cont_area WHERE ID_VS = '$idvs' AND ID_KATEGORI ='$jum9'";
	$data21	 = $db->query($query21);
	$result21 = $data21->fetchRow();
	$jum21 	 = $result21['JUM'];
	if ($jum21 <= 0){
		$db->query("DELETE FROM tb_booking_cont_area_gr WHERE ID_KATEGORI = '$jum9' AND ID_VS = '$idvs'");
		$db->query("DELETE FROM yd_yard_allocation_planning WHERE ID_KATEGORI = '$jum9' AND ID_VS = '$idvs'");
	}
}
	
 header('Location:'.HOME.'planning.yard_allocation_import');

?>