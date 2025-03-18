<?php
 $u = $_POST['id_book'];
 
  $db = getDB();
 $query = "DELETE FROM TB_BOOKING_CONT_AREA WHERE ID_BOOK = '$u'";
 
	if ($db->query($query)){
		echo 'sukses';
	} else {
		echo 'gagal';
	}
?>

