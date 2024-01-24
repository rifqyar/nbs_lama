<?php
$db2 = getDB("ora");
$sqlx3   = "INSERT INTO PETIKEMAS_CABANG.TTD_CONT_PEB ( KD_PMB_DTL, NO_NPE, TGL_PEB, USER_ID, KD_CABANG, TGL_SIMPAN, KD_PMB, NO_INVOICE, NO_UKK ) VALUES
				(
					PETIKEMAS_CABANG.SEQ_TTD_CONT_EXBSPL.CURRVAL,
					'-',
					SYSDATE, 
					'USTER',
					'05',
					SYSDATE,
					'UD0513000114',
					'',
					'LUSE05130017'
				)";
			$db2->query($sqlx3);

?>