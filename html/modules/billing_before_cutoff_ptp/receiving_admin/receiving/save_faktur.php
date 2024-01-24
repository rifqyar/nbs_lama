<?php

$db			= getDB();
$no_req 	= $_POST["no_request"];
$no_nota 	= $_POST["no_nota"];
$no_faktur 	= $_POST["no_faktur"];


				 $query_update	= "UPDATE tb_nota_receiving_h SET ID_NOTA_ICT = '$no_nota', NO_FAKTUR = '$no_faktur' WHERE ID_REQ = '$no_req'";
             
                if ($db->query($query_update)) {

                    header('Location:'.HOME.'billing.receiving/');	
  
				}

?>