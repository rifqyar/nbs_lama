<?php
	$no_cont = $_GET["NO_CONT"];
	$no_book = $_GET["NO_BOOK"];
	$db = getDB("storage");
	
	/* $q_detail = " SELECT TTD_BP_CONT.CONT_NO_BP NO_CONTAINER,
                     TTD_BP_CONT.KD_SIZE,
                     TTD_BP_CONT.KD_TYPE,
                     TO_CHAR (TTD_BP_CONFIRM.CONFIRM_DATE, 'DD-MM-YYYY') AS TGL_BONGKAR,
                     TTD_BP_CONT.KD_STATUS_CONT,
                     TTD_BP_CONT.BP_ID,     
                     V_PKK_CONT.NO_UKK, 'TPK' ASAL_CONT,                     
                        YARD.ARE_BLOK BLOK_,            
                        YARD.ARE_SLOT SLOT_,  
                        YARD.ARE_ROW ROW_,
                        YARD.ARE_TIER TIER_ ,
                        V_PKK_CONT.VOYAGE_IN,
						V_PKK_CONT.VOYAGE_OUT,
                        V_PKK_CONT.NM_KAPAL,
                        V_PKK_CONT.NM_AGEN,
                        To_Char(TTD_BP_CONT.TGL_STACK,'DD-MM-YYYY') AS TGL_STACK,
                         V_BOOKING_STACK_TPK.NO_BOOKING, 
                        TTD_BP_CONT.STATUS_CONT 
                FROM PETIKEMAS_CABANG.TTM_BP_CONT TTM_BP_CONT,
                     PETIKEMAS_CABANG.TTD_BP_CONT TTD_BP_CONT,
                     PETIKEMAS_CABANG.V_PKK_CONT V_PKK_CONT,
                     PETIKEMAS_CABANG.TTD_BP_CONFIRM TTD_BP_CONFIRM,
                     PETIKEMAS_CABANG.MST_YARD_CONT_LAPANGAN YARD,
                     V_BOOKING_STACK_TPK
               WHERE     TTM_BP_CONT.BP_ID = TTD_BP_CONT.BP_ID
                     AND ROWNUM <= 7
                     AND TTM_BP_CONT.NO_UKK = V_PKK_CONT.NO_UKK
                     AND TTD_BP_CONT.CONT_NO_BP = TTD_BP_CONFIRM.CONT_NO_BP
                     AND TTM_BP_CONT.NO_UKK = TTD_BP_CONFIRM.NO_UKK
                     AND TTD_BP_CONT.BP_ID = TTD_BP_CONFIRM.BP_ID
                     AND TTM_BP_CONT.KD_CABANG = '05'
                     --AND TTD_BP_CONT.STATUS_CONT = '04U'
                     AND YARD.ARE_ID = TTD_BP_CONT.ARE_ID
                     AND V_PKK_CONT.KD_KAPAL = V_BOOKING_STACK_TPK.KD_KAPAL
                     AND V_PKK_CONT.VOYAGE_IN = V_BOOKING_STACK_TPK.VOYAGE_IN
                     AND V_PKK_CONT.VOYAGE_OUT = V_BOOKING_STACK_TPK.VOYAGE_OUT
                     AND TTD_BP_CONT.CONT_NO_BP = '$no_cont'
                     AND V_BOOKING_STACK_TPK.NO_BOOKING LIKE '%$no_book%'
                     ORDER BY ASAL_CONT ASC"; */
	$q_detail = "SELECT A.NO_UKK, A.NM_KAPAL, A.VOYAGE_IN, A.VOYAGE_OUT, A.NO_BOOKING, '' BP_ID FROM v_pkk_cont A
				JOIN PETIKEMAS_CABANG.TTH_CONT_BOOKING TB ON A.NO_UKK = TB.NO_UKK
				WHERE TB.NO_BOOKING = '$no_book'
				UNION
				SELECT  A.NO_UKK, A.NM_KAPAL, A.VOYAGE_IN, A.VOYAGE_OUT, '' NO_BOOKING, TM.BP_ID FROM v_pkk_cont A
				RIGHT JOIN PETIKEMAS_CABANG.TTM_BP_CONT TM ON A.NO_UKK = TM.NO_UKK
				WHERE TM.BP_ID = '$no_book'";
	$res_detail = $db->query($q_detail);
	$r_det = $res_detail->fetchRow();
	echo json_encode($r_det); 
	//exit();

?>