<?php	$ves=$_POST['VES'];	$idves=$_POST['IDVES'];	$vin=$_POST['VIN'];	$vout=$_POST['VOUT'];	$sl=$_POST['SL'];	$asl=$_POST['ASL'];	$isl=$_POST['ISL'];	$nsl=$_POST['NSL'];	$pol=$_POST['POL'];	$pod=$_POST['POD'];	$ipod=$_POST['IPOD'];	$ipol=$_POST['IPOL'];	$eta=$_POST['ETA'];	$etd=$_POST['ETD'];	$ship=$_POST['SHIP'];	$user=$_SESSION['PENGGUNA_ID'];			$db=getDb();	$query="begin proc_create_vs('$ves', '$idves', '$vin', '$vout', '$sl', '$asl', '$isl', '$nsl', '$pol', '$pod' , '$ipol', '$ipod', '$eta', '$etd','$ship','$user');end;";		//print_r($query);die;	$exec=$db->query($query);?>