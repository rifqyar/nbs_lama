<?php
	$db=getDb('dbint');	
	$update_date = $_POST['UPDATE_DATE'];
	$vessel = $_POST['VESSEL'];	
	$voyage_in = $_POST['VOYAGE_IN'];
	$voyage_out = $_POST['VOYAGE_OUT'];
	$no_container = $_POST['NO_CONTAINER'];

	//echo $no_container;
	//echo $vessel;
	//die();	

	$query = "UPDATE 
					M_CYC_CONTAINER
			  SET 
			  		VESSEL_CONFIRM='$update_date'
			  WHERE 
			  		no_container = '$no_container'
			  		and vessel= '$vessel'
			  		and voyage_in = '$voyage_in'
			  		and voyage_out = '$voyage_out'";
			  					  			
	$db->query($query);	

	echo "sukses masukan data";

	die();
?>