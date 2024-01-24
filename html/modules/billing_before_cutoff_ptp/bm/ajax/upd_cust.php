<?php
	$custno=$_POST['CUSTNO'];
	$custnm=$_POST['CUSTNM'];
	$custadd=$_POST['CUSTADD'];
	$custtax=$_POST['CUSTTAX'];
	$id=$_POST['IDRPSTV'];
	
	$db=getDb();
	$query="update bil_rpstv_h set cust_no='$custno' , cust_name='$custnm', cust_tax_no='$custtax', cust_addr='$custadd' where id_rpstv='$id'";
	$db->query($query);
	
	echo "Success";

?>