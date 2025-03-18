<?php
	$db=getDb('pnoadm');
	$truck = $_POST['TRUCK'];
	$no_container = $_POST['NO_CONTAINER'];

	//echo $no_container;
	//echo $vessel;
	//die();

	$query = "UPDATE
					CYY_CONTAINER
			  SET
			  		CYY_CONT_REGONO='$truck'
			  WHERE
			  		CYY_CONT_CONTNO = '$no_container'";

	$db->query($query);

	echo "sukses masukan data";

	die();
?>
