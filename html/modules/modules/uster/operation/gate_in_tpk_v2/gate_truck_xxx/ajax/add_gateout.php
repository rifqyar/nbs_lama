<?php

$db 		= getDB("storage");
$db2 		= getDB("ora");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 
$no_truck	= $_POST["NO_TRUCK"]; 
$no_seal	= $_POST["NO_SEAL"]; 
$status        = $_POST["STATUS"];
$bp_id         = $_POST["BP_ID"];
$no_req_tpk    = $_POST["NO_REQ_TPK"];
//$masa_berlaku	= $_POST["MASA_BERLAKU"]; 
$keterangan	= $_POST["KETERANGAN"]; 
$id_yard	= $_POST["ID_YARD"]; 
$nm_user	= $_SESSION["NAME"];

$id_user	= $_SESSION["LOGGED_STORAGE"];

//debug($_POST);die;

$query_trucking = "SELECT 
						CASE (SELECT NVL2(NO_TRUCK,'PELINDO','n/a')
								FROM TRUCK
								WHERE NO_TRUCK = '$no_truck') 
					WHEN 'PELINDO' THEN 'PELINDO'
					ELSE 'LUAR'
				  END AS NO_TRUCK
				  FROM TRUCK";

$result_trucking = $db->query($query_trucking);
$row_trucking	 = $result_trucking->fetchRow();
$trucking		 = $row_trucking["NO_TRUCK"];

//debug($trucking);die;
					
$query_gato = "SELECT NO_CONTAINER
			  FROM BORDER_GATE_OUT
			  WHERE NO_CONTAINER = '$no_cont'
			  AND NO_REQUEST = '$no_req'
				";
$result_gato	= $db->query($query_gato);
$row_gato		= $result_gato->fetchRow();
$gato			= $row_gato["NO_CONTAINER"];

//=======================================  INTERFACE SIMOP ======================================================//

//tidak perlu interface simop, karena update status simop terjadi sewaktu gate in truck yang membawa container ke uster
//gate out truck tanpa container hanya sebagai data untuk melihat penggunaan head truck
//buat cekpoint status 10 di TPK + buat autocomplete pas gate-in USTER (gate-out TPK).Edo
$sql 	= "UPDATE  PETIKEMAS_CABANG.TTD_BP_CONT SET
		   GATE_IN				= '$nm_user',
		   GATE_IN_DATE			= SYSDATE, 
		   HT_NO				= '$no_truck' 
		   WHERE BP_ID  	    = '$bp_id' 
		   AND CONT_NO_BP		= '$no_cont'
		   ";      
//echo $sql;	
$db2->query($sql);	 

$sql_d 	= "UPDATE PETIKEMAS_CABANG.TTD_BP_CONT SET
           STATUS_CONT		= '10'
		   WHERE CONT_NO_BP = '$no_cont'  AND BP_ID ='$bp_id'";  

//echo $sql_d;	exit;		   
		   
$db2->query($sql_d);





//======================================= END INTERFACE SIMOP ======================================================//

$query_insert	= "INSERT INTO BORDER_GATE_OUT
						( 	NO_REQUEST, 
							NO_CONTAINER, 
							ID_USER, 
							TGL_IN, 
							NOPOL, 
							STATUS, 
							NO_SEAL,
							TRUCKING,
							ID_YARD,
							KETERANGAN) 
				 VALUES(	'$no_req', 
							'$no_cont', 
							'$id_user', 
							SYSDATE, 
							'$no_truck', 
							'$status',
							'$no_seal',
							'$trucking',
							'$id_yard',
							'$keterangan')";
							
							

		
							
							


//echo $query_insert;exit;							
							
	//$db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'GATO' WHERE NO_CONTAINER = '$no_cont'");
    //$db->query("UPDATE CONTAINER_DELIVERY SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
    //$db->query("DELETE FROM PLACEMENT WHERE NO_CONTAINER = '$no_cont'");
    if($no_truck == NULL)
    {
		echo "TRUCK";
	
    }
	else if($gato != NULL)
	{
		echo "EXIST";
	}
	else if($db->query($query_insert))
	{
		
		echo "OK";
	}


?>