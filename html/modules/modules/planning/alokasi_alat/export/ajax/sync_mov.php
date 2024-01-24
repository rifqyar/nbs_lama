<?
$no_ukk = $_POST['ID_VS'];
$alat = $_POST['ALAT'];
$bay_area = $_POST['BAY_AREA'];
$posisi = $_POST['POSISI'];
$seq_alat = $_POST['SEQ_ALAT'];
$sz = $_POST['SZ_CONT'];
$act = $_POST['ACT'];
$id_user = $_SESSION["ID_USER"];

$db = getDB();
$count_mov = "select count(*) JML_MOV from STW_PLACEMENT_BAY A, STW_BAY_CELL B 
			 where A.ID_CELL = B.ID
				AND A.ID_VS = '$no_ukk'
				AND A.ACTIVITY = 'BONGKAR' 
				AND A.SIZE_ = TRIM('$sz') 
				AND B.ID_BAY_AREA = '$bay_area'
				AND B.POSISI_STACK = '$posisi'";
$jml15 = $db->query($count_mov);
$jml_hsl = $jml15->fetchRow();
$jumlah_count = $jml_hsl['JML_MOV'];

$cek_list_container = "select ID_VS, NO_CONTAINER from STW_PLACEMENT_BAY A, STW_BAY_CELL B 
					where A.ID_CELL = B.ID
						AND A.ID_VS = '$no_ukk'
						AND A.ACTIVITY = 'BONGKAR' 
						AND A.SIZE_ = TRIM('$sz') 
						AND B.ID_BAY_AREA = '$bay_area'
						AND B.POSISI_STACK = '$posisi'
						GROUP BY A.ID_VS, A.NO_CONTAINER";
$contlist = $db->query($cek_list_container);
$listcont = $contlist->getAll();

foreach($listcont as $cont)
{
	$no_ukk = $cont['ID_VS'];
	$no_cont = $cont['NO_CONTAINER'];
	
	$update_plc = "update STW_PLACEMENT_BAY set ID_ALAT = '$alat' where ID_VS = '$no_ukk' and NO_CONTAINER = '$no_cont'";
	$db->query($update_plc);

	$update_list_container = "update ISWS_LIST_CONTAINER set ALAT = '$alat' where NO_UKK = '$no_ukk' and NO_CONTAINER = '$no_cont'";
	$db->query($update_list_container);
}

$update_mov = "update STW_BAY_CSL set JML_MOV = '$jumlah_count' 
			   where trim(ID_VS) = trim('$no_ukk') and trim(ID_ALAT) = trim('$alat') and SEQ_ALAT = '$seq_alat' and ACTIVITY = 'I'";
$db->query($update_mov);

$hist_mov = "insert into STW_HISTORY_CSL
                 (ID_VS,
                 ID_BAY_AREA,
                 POSISI_STACK,
                 ID_ALAT,
                 SEQ_ALAT,
                 ACTIVITY,
                 SZ_PLAN,
                 USER_UPDATE,
                 JML_MOV) 
                 values 
                 ('$no_ukk',
                 '$bay_area',
                 upper('$posisi'),
                 '$alat',
                 '$seq_alat',
                 '$act',
                 '$sz',
                 '$id_user',
                 '$jumlah_count')";
$db->query($hist_mov);

echo "OK";

?>