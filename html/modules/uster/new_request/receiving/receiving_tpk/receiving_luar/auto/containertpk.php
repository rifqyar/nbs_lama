<?php
$no_cont		= strtoupper($_GET["term"]);

$db 			= getDB("storage");

	
//-------------- Kalo Receive dari TPK ----- Ambil No_Container Yang Siap Didelivery dari TPK -----------//	
	$result  = "select  
			TTD_BP_CONT.CONT_NO_BP  , 
			TTD_BP_CONT.KD_SIZE,
			TTD_BP_CONT.KD_TYPE,
			TTD_BP_CONT.KD_STATUS_CONT,
			TTD_BP_CONT.GROSS,
			TTD_BP_CONT.CLASS,            
			NULL AS ARE_BLOK,
			NULL AS ARE_SLOT,
			NULL AS ARE_ROW,
			NULL AS ARE_TIER,    
			V_PKK_CONT.NM_KAPAL,
			V_PKK_CONT.NM_AGEN,
			V_PKK_CONT.NO_UKK,
			V_PKK_CONT.VOYAGE_IN,
			V_PKK_CONT.VOYAGE_OUT,
			TTD_BP_CONT.DISC_PORT,
			TTD_BP_CONT.BP_ID,
			TTD_BP_CONT.ARE_ID,
			To_Char(V_PKK_CONT.TGL_JAM_BERANGKAT,'DD-MM-YYYY') AS TGL_JAM_BERANGKAT,
			To_Char(TTD_BP_CONFIRM.CONFIRM_DATE,'DD-MM-YYYY') AS TGL_BONGKAR,
			TTD_BP_CONT.STATUS_CONT, 
			To_Char(TTD_BP_CONT.TGL_STACK,'DD-MM-YYYY') AS TGL_STACK,
			To_Char(TTD_BP_CONFIRM.CONFIRM_DATE+4,'DD-MM-YYYY') AS TGL_BERLAKU   
			from 
			PETIKEMAS_CABANG.TTM_BP_CONT TTM_BP_CONT,PETIKEMAS_CABANG.TTD_BP_CONT TTD_BP_CONT,   
			PETIKEMAS_CABANG.V_PKK_CONT V_PKK_CONT,PETIKEMAS_CABANG.TTD_BP_CONFIRM TTD_BP_CONFIRM
			WHERE TTM_BP_CONT.BP_ID = TTD_BP_CONT.BP_ID 
			AND TTM_BP_CONT.NO_UKK = V_PKK_CONT.NO_UKK 
			AND TTD_BP_CONT.CONT_NO_BP = TTD_BP_CONFIRM.CONT_NO_BP 
			AND TTM_BP_CONT.NO_UKK = TTD_BP_CONFIRM.NO_UKK 
			AND TTD_BP_CONT.BP_ID = TTD_BP_CONFIRM.BP_ID 
			AND TTM_BP_CONT.KD_CABANG ='05'       
			AND TTD_BP_CONT.STATUS_CONT IN ('03')  
			AND TTD_BP_CONT.CONT_NO_BP LIKE '%$no_cont%'
			order by TTD_BP_CONT.CONT_NO_BP asc  
";      
//echo $result;
//print_r($result); exit();
$rs  = $db->query($result);
$rowxcont=$rs->getAll();

// End Receive DARI TPK //

echo json_encode($rowxcont);


?>