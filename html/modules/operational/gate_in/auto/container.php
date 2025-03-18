<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB();
	
$query 			= "SELECT a.NO_REQ_ANNE,
						  a.NO_CONTAINER, 
						  a.SIZE_CONT,
						  a.ISO_CODE,
						  a.HEIGHT H_ISO, 
						  a.TYPE_CONT, 
						  a.STATUS_CONT, 
						  a.HZ, 
						  a.PEL_TUJ, 
						  a.VESSEL, 
						  a.VOYAGE_OUT, 
						  a.PEL_ASAL 
				   FROM req_receiving_d a left join req_receiving_h b on trim(a.NO_REQ_ANNE)=trim(b.ID_REQ) where a.FLAG_USE=0 
				   and b.STATUS='P'
				   AND a.NO_CONTAINER LIKE '$no_cont%'";
//print_r($query);die;
$result			= $db->query($query);
$row			= $result->getAll();	

//print_r($query);die;

echo json_encode($row);


?>