<?php

$invoice			= strtoupper($_GET["term"]);
$terminal			= $_GET["terminal"];

$db 			= getDB();	

if($terminal == 'tpk') {
	$query 			= "select * from ( select no_nota, no_request, emkl from nota_delivery_h a join tth_nota_all2 b on a.id_req = b.no_request 
				where b.date_paid is not null and status_nota2 = '0'
				union
				select no_nota, no_request, emkl from nota_batalmuat_h a left join tth_nota_all2 b on a.id_req = b.no_request
				where  b.date_paid is not null and status_nota2 = '0' and substr(no_nota,8,2) = '66')
				where (no_nota like '$invoice%' or no_request like '$invoice%')";
}
else {
	$query 			= "SELECT trx_number no_nota, o_reqnbs no_request, c.customer_number emkl
				  FROM    uster.itpk_nota_header c
				       JOIN
				          uster.request_stripping d
				       ON c.no_request = d.no_request AND c.tgl_pelunasan IS NOT NULL
				 WHERE  o_reqnbs IS NOT NULL
				       AND c.jenis_nota IN ('UST03', 'UST06')
				       AND c.status <> '5'
				       AND (d.no_request like '$invoice%' or c.trx_number like '$invoice%')";	
}
$result			= $db->query($query);
$row			= $result->getAll();	

echo json_encode($row);


?>