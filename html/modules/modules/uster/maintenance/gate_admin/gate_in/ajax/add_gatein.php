<?php

$db 		= getDB("storage");

$no_cont	= strtoupper($_POST["NO_CONT"]); 
$no_req		= $_POST["NO_REQ"]; 
$no_pol		= $_POST["NO_POL"]; 
$status		= $_POST["STATUS"]; 
$tgl_gate	= $_POST["tgl_gate"]; 

$id_user	= $_SESSION["LOGGED_STORAGE"];
$id_yard    = $_SESSION["IDYARD_STORAGE"];

if($id_user == NULL){
	echo "Session Habis, Harap Login Kembali";
	exit();
}

//Cek apakah container sudah gate in
$qcek_gati = "SELECT COUNT(NO_CONTAINER) AS JUM
			  FROM GATE_IN
			  WHERE NO_CONTAINER = '$no_cont'
			  AND NO_REQUEST = '$no_req'";
$rcek_gati = $db->query($qcek_gati);
$rwc_gati = $rcek_gati->fetchRow();
$jum_gati = $rwc_gati["JUM"];
if($jum_gati > 0){
	echo "EXIST_GATI";
	exit();
}

//Cek apakah container sudah di request receiving
$query_rec = "SELECT COUNT(NO_CONTAINER) AS JUM
			  FROM CONTAINER_RECEIVING
			  WHERE NO_CONTAINER = '$no_cont'
			  AND NO_REQUEST = '$no_req'
				";
$result_rec	= $db->query($query_rec);
$row_rec		= $result_rec->fetchRow();
$jum_req		= $row_rec["JUM"];

//Cek apakah container sudah lunas
$query_lunas = "SELECT CASE
					 WHEN PERALIHAN = 'STUFFING' THEN (SELECT LUNAS FROM NOTA_STUFFING INNER JOIN REQUEST_STUFFING ON NOTA_STUFFING.NO_REQUEST = REQUEST_STUFFING.NO_REQUEST WHERE REQUEST_STUFFING.NO_REQUEST_RECEIVING = '$no_req')
					 WHEN PERALIHAN = 'STRIPPING' THEN (SELECT LUNAS FROM NOTA_STRIPPING INNER JOIN REQUEST_STRIPPING ON NOTA_STRIPPING.NO_REQUEST = REQUEST_STRIPPING.NO_REQUEST WHERE REQUEST_STRIPPING.NO_REQUEST_RECEIVING = '$no_req')
					 WHEN PERALIHAN = 'RELOKASI' THEN 'YES'
					 WHEN PERALIHAN IS NULL THEN (SELECT DISTINCT LUNAS FROM NOTA_RECEIVING WHERE NO_REQUEST = '$no_req')
					 ELSE 'NO'
					 END AS LUNAS
				FROM REQUEST_RECEIVING WHERE NO_REQUEST = '$no_req'
				";
				
$result_lunas	= $db->query($query_lunas);
$row_lunas		= $result_lunas->fetchRow();
$lunas		= $row_lunas["LUNAS"];

//Cek posisi container
$query_gati = "SELECT LOCATION
			  FROM MASTER_CONTAINER
			  WHERE NO_CONTAINER = '$no_cont'
				";
$result_gati	= $db->query($query_gati);
$row_gati		= $result_gati->fetchRow();
$gati		= $row_gati["LOCATION"];

//Cek asal receiving darimana, tpk atau luar
$query_rec_dari = "SELECT RECEIVING_DARI
				FROM REQUEST_RECEIVING
				WHERE NO_REQUEST = '$no_req'
			 ";

$result_rec_dari = $db->query($query_rec_dari);
$row_rec_dari = $result_rec_dari->fetchRow();
$rec_dari = $row_rec_dari["RECEIVING_DARI"];
	
	
	if($rec_dari != "DEPO")
	{	
			if($jum_req <= 0)
			{
				echo "NOT_REQUEST";
			}
			else if($lunas != "YES")
			{
				echo "NOT_PAID";
			}
			else if($no_pol	 == NULL)
			{
				echo "NO_POL";
			}
			else if($jum_req >= 0 && $lunas == "YES" && $no_pol	 != NULL && $gati != "GATI")
			{
				//Insert data cont ke tabel get in
				$query_insert	= "INSERT INTO GATE_IN(NO_CONTAINER, NO_REQUEST,NOPOL, ID_USER, TGL_IN,STATUS, ID_YARD, ENTRY_DATE) VALUES('$no_cont', '$no_req','$no_pol', '$id_user', TO_DATE('$tgl_gate','dd-mm-rrrr'), '$status','$id_yard', SYSDATE)";
				$result_insert	= $db->query($query_insert);
				
				/* $q_getcounter1 = "SELECT NO_BOOKING, COUNTER FROM MASTER_CONTAINER WHERE NO_CONTAINER = '$no_cont' ORDER BY COUNTER DESC";
				$r_getcounter1 = $db->query($q_getcounter1);
				$rw_getcounter1 = $r_getcounter1->fetchRow();
				$cur_booking1  = $rw_getcounter1["NO_BOOKING"];
				$cur_counter1  = $rw_getcounter1["COUNTER"]; */
				
				$qbook = "SELECT NO_BOOKING, COUNTER, TO_CHAR (TGL_UPDATE + interval '10' minute, 'MM/DD/YYYY HH:MI:SS AM') TGL_UPDATE, STATUS_CONT FROM HISTORY_CONTAINER WHERE NO_CONTAINER = '$no_cont' AND NO_REQUEST = '$no_req'";
				$rbook = $db->query($qbook);
				$rwbook = $rbook->fetchRow();
				$cur_booking1 = $rwbook["NO_BOOKING"];
				$cur_counter1 = $rwbook["COUNTER"];
				$tgl_update = $rwbook["TGL_UPDATE"];
				$status_ = $rwbook["STATUS_CONT"];
		
				
				$history  = "INSERT INTO history_container(NO_CONTAINER, NO_REQUEST, KEGIATAN, TGL_UPDATE, ID_USER, ID_YARD, STATUS_CONT, NO_BOOKING, COUNTER) 
                                                      VALUES ('$no_cont','$no_req','GATE IN',TO_DATE ('$tgl_update', 'MM/DD/YYYY HH:MI:SS AM'),'$id_user','$id_yard','$status_','$cur_booking1','$cur_counter1')";
       
                $db->query($history);
				
				//Update status lokasi container, di dalam atau di luar
				$query_upd	= "UPDATE MASTER_CONTAINER SET LOCATION = 'GATI' WHERE NO_CONTAINER = '$no_cont'";
				$db->query($query_upd);
				
				//Select Nopol
				$query_nopol ="SELECT NO_TRUCK
						   FROM TRUCK
						   WHERE NO_TRUCK = '$no_pol'
							";
				if($db->query($query_nopol))
				{	
					$query_insert_nopol = "UPDATE GATE_IN SET TRUCKING ='PELINDO' WHERE NO_CONTAINER = '$no_cont'";
					$result_insert_nopol	= $db->query($query_insert_nopol);							  
				}
				echo "OK";
			}
			else if($gati == "GATI")
			{
				echo "EXIST";
			}
	}
	else if($rec_dari == "TPK")
	{
		if($jum_req <= 0)
			{
				echo "NOT_REQUEST";
			}
			else if($no_pol	 == NULL)
			{
				echo "NO_POL";
			}
			else if($jum_req >= 0  && $no_pol	 != NULL && $gati != "GATI")
			{
				//Insert data cont ke tabel get in
				$query_insert	= "INSERT INTO GATE_IN(NO_CONTAINER, NO_REQUEST,NOPOL, ID_USER, TGL_IN) VALUES('$no_cont', '$no_req','$no_pol', '$id_user', TO_DATE('$tgl_gate','yy-mm-dd'))";
				$result_insert	= $db->query($query_insert);
				
				//Update status lokasi container, di dalam atau di luar
				$query_upd	= "UPDATE MASTER_CONTAINER SET LOCATION = 'GATI' WHERE NO_CONTAINER = '$no_cont'";
				$db->query($query_upd);
				
				//Insert ke handling piutang
				
						
				
				$kegiatan_ = array("LIFT_ON","HAULAGE","LIFT_OFF");	
				foreach($kegiatan_ as $kegiatan )
				{						
					$query_insert = "INSERT INTO HANDLING_PIUTANG
												(NO_CONTAINER,
												 KEGIATAN,
												 STATUS_CONT,
												 TANGGAL,
												 PENAGIHAN,
												 KETERANGAN
												)
										VALUES	('$no_cont',
												 '$kegiatan',
												 (SELECT STATUS 
													FROM CONTAINER_RECEIVING          
													WHERE NO_CONTAINER = '$no_cont'
													AND NO_REQUEST ='$no_req'  ),
												  TO_DATE('$tgl_gate','yy-mm-dd'),
												  'PELAYARAN',
												  'FAKTOR_YOR'
												)";
					$result_query_insert = $db->query($query_insert);
				}
				//Cek Truck Apakh dari pelindo atau tidak
				$query_nopol ="SELECT NO_TRUCK
							   FROM TRUCK
							   WHERE NO_TRUCK = '$no_pol'
							";
				if($db->query($query_nopol))
				{	
					$query_insert_nopol = "UPDATE GATE_IN SET TRUCKING ='PELINDO' WHERE NO_CONTAINER = '$no_cont'";
					$result_insert_nopol	= $db->query($query_insert_nopol);							  
				}
				echo "OK";
			}
			else if($gati == "GATI")
			{
				echo "EXIST";
			}
	}
?>