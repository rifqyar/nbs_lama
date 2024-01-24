<?php
	$id=$_POST['IDRPSTV'];
	
	$db=getDb();
	$query="select cust_no, cust_tax_no from bil_rpstv_h where id_rpstv='$id'";
	$rw = $db->query($query);
	$rs = $rw->fetchRow();
    if($rs['CUST_NO'] == null){
        echo "NO";    
    }
    else if($rs['CUST_NO'] != null && $rs['CUST_TAX_NO'] == null){
        echo "NPWP";  
    }
    else {
        echo "OK";
    }
	
    die();
?>