<?php
	$nocont= $_POST['NO_CONT'];
	$vessel= $_POST['VESSEL'];
	$voyagein= $_POST['VOYAGE_IN'];
	$voyageout= $_POST['VOYAGE_OUT'];
	$shift_real = $_POST['SHIFT_REAL'];
	//echo $param;die;
	$query="SELECT shift_reefer,
       CASE
          WHEN shift_reefer < $shift_real THEN 'KURANG BAYAR'
          WHEN shift_reefer > $shift_real THEN 'LEBIH BAYAR'
       END
          STATUS_PAYMENT,
          $shift_real-shift_reefer selisih,
          ($shift_real-shift_reefer)*tarif nominal
  	FROM req_receiving_h a, req_receiving_d b, master_tarif_cont c
 	WHERE a.id_req = b.id_req and b.ID_CONT = c.ID_CONT and  jenis_biaya = 'PLUGIN_REFFER' and b.no_container = '$nocont' and a.vessel = '$vessel' and a.voyage_in='$voyagein' and a.voyage_out = '$voyageout'";
	$db=getDb();
	$result	= $db->query($query);
	$row= $result->fetchRow();
	echo json_encode($row);
	die();

?>