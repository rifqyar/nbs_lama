<?php

$db 		= getDB("storage");

$no_cont	= $_POST["NO_CONT"]; 
$no_req		= $_POST["NO_REQ"]; 
$no_truck	= $_POST["NO_TRUCK"]; 
$no_req_tpk		= $_POST["NO_REQ_TPK"];
$bp_id		= $_POST["BP_ID"];

$no_seal	= $_POST["NO_SEAL"]; 
$status         = $_POST["STATUS"];
//$masa_berlaku	= $_POST["MASA_BERLAKU"]; 
$keterangan	= $_POST["KETERANGAN"]; 
$id_yard	= $_POST["ID_YARD"]; 

$id_user	= $_SESSION["LOGGED_STORAGE"];

//echo $no_req;die; 
//debug($_POST);die;
//Cek posisi container
$query_gati = "SELECT LOCATION
			  FROM MASTER_CONTAINER
			  WHERE NO_CONTAINER = '$no_cont'
				";
$result_gati	= $db->query($query_gati);
$row_gati		= $result_gati->fetchRow();
$gati		= $row_gati["LOCATION"];

//=======================================  INTERFACE SIMOP ======================================================//

$sql 	= "UPDATE  PETIKEMAS_CABANG.TTD_BP_CONT SET
		   GATE_OUT				= '$nm_user',
		   GATE_OUT_DATE			= SYSDATE, 
		   HT_NO				= '$no_truck' 
		   WHERE BP_ID  	    = '$bp_id' 
		   AND CONT_NO_BP		= '$no_cont'
		   AND NO_REQ			= '$no_req_tpk'
		   ";      
//echo $sql;	
$db->query($sql);	 

$sql_d 	= "UPDATE PETIKEMAS_CABANG.TTD_BP_CONT SET
           STATUS_CONT		= '09U'
		   WHERE CONT_NO_BP = '$no_cont'  AND BP_ID ='$bp_id' AND NO_REQ = '$no_req_tpk'";  
//echo $sql_d;	exit;	   
$db->query($sql_d);



//======================================= END INTERFACE SIMOP ======================================================//


$query_insert	= "INSERT INTO BORDER_GATE_IN
						( 	NO_REQUEST, 
							NO_CONTAINER, 
							ID_USER, 
							TGL_IN, 
							NOPOL, 
							STATUS, 
							NO_SEAL, 
							ID_YARD,
							KETERANGAN) 
				 VALUES(	'$no_req', 
							'$no_cont', 
							'$id_user', 
							SYSDATE, 
							'$no_truck', 
							'$status',
							'$no_seal',
							'$id_yard',
							'$keterangan')";
							
//Insert ke handling piutang
/*					
$query_insert_piutang = "INSERT INTO HANDLING_PIUTANG(NO_CONTAINER,
													  KEGIATAN,
													  STATUS_CONT,
													  TANGGAL,
													  KETERANGAN,
													  NO_REQUEST,
													  ID_YARD
													  )
											   VALUES('$no_cont',
													  'RECEIVING',
													  '$status',
													  SYSDATE,
													  '',
													  '$no_req',
													  '$depo_tujuan'
													  )";
*/

		
							
							


//echo $query_insert;exit;							
							
	
    //$db->query("DELETE FROM PLACEMENT WHERE NO_CONTAINER = '$no_cont'");
    if($no_truck == NULL)
    {
		echo "TRUCK";
	
    }
	else if($gati == "GATI")
	{
		echo "EXIST";
	}
	else if( ($db->query($query_insert)) /* && ($db->query($query_insert_piutang)) */ )
	{
		$db->query("UPDATE MASTER_CONTAINER SET LOCATION = 'GATI' WHERE NO_CONTAINER = '$no_cont'");
		
		$id_yard_	= $_SESSION["IDYARD_STORAGE"];
		$history        = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, STATUS_CONT) 
                           VALUES ('$no_cont','$no_req','BORDER GATE IN',SYSDATE,'$id_user','$id_yard_','$status')";
       
        $db->query($history);
		
		//$db->query("UPDATE CONTAINER_RECEIVING SET AKTIF = 'T' WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'");
		echo "OK";
	}
	else 
	{	
		echo "GAGAL";
	}


?>