<?php

$db = getDB();
$id_user = $_SESSION["ID_USER"];
$id_vs = $_POST["NO_UKK"];
$nocont = $_POST["NO_CONT"];
$alat = $_POST["ALAT"];
$op_alat = $_POST["OP_ALAT"];
$no_truck = $_POST["NO_TRUCK"];
$remark = $_POST["REMARK"];
$seal_status = $_POST["SEAL_STATUS"];
$eir = $_POST["EIR"];

//===== Patch ISWS =====//
/*$update_list_container = "UPDATE ISWS_LIST_CONTAINER SET DISCHARGE_CONFIRM = SYSDATE, 
					STATUS_CONFIRM = 'Y', 
					DATE_CONFIRM = SYSDATE, 
					KODE_STATUS = '02', 
					USER_CONFIRM = '$id_user' 
		  WHERE NO_UKK = '$id_vs' 
			AND NO_CONTAINER = '$nocont'";

$db->query($update_list_container);

$delete_plc_bay = "DELETE STW_PLACEMENT_BAY 
					WHERE ID_VS = '$id_vs'
						AND ACTIVITY = 'BONGKAR'
						AND NO_CONTAINER = '$nocont'";

$db->query($delete_plc_bay);*/


$cek2 = "BEGIN discharge_confirm('$nocont','$id_vs','$id_user','$op_alat','$alat','$no_truck'); END;"; 
//print_r($cek2);die;
$db->query($cek2);

$query_list_cont = "UPDATE isws_list_container set remark = '$remark' where NO_CONTAINER = '$nocont' AND NO_UKK = '$id_vs'";
$db->query($query_list_cont);
		
$query_confirm_disc = "UPDATE confirm_disc set remark = '$remark', status_seal = '$seal_status', damage = '$damage', eir = '$eir' where NO_CONTAINER = '$nocont' AND NO_UKK = '$id_vs'";
$db->query($query_confirm_disc);
//===== Patch ISWS =====//



//===== Patch ICT =====//
/*
$cek = " BEGIN discharge_confirm_ict('$nocont','$id_vs'); END;";
$db->query($cek);
*/
//===== Patch ICT =====//

echo "OK";

?>