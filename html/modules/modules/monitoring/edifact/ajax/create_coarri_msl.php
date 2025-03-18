<?php
$id_user = $_SESSION["ID_USER"];
$no_ukk	= $_POST['NO_UKK'];

$db = getDB();
//print_r($act);die;	
	
/*
	UNB+UNOA:1+IDJKTOJA+MAEU+101012:1707+10101217071014'
	
	UNH+1+COARRI:D:95B:UN:ITG12'
	BGM+44+0000000000+9'
	TDT+20+1020+1++MAEU:172:166+++:103::6IL'
	NAD+CF+MAEU:160:166'
	EQD+CN+MSKU0308961+0000:102:5+++4'
	DTM+203:201010121708:203'
	LOC+9+IDJKT45:139:6'
	CNT+16:1'
	UNT+9+1'
	
	UNZ+1+10101217071014'
*/
	
	$ves_voy = "SELECT NM_KAPAL,
					   VOYAGE_IN,
					   VOYAGE_OUT
					FROM RBM_H
                    WHERE NO_UKK = '$no_ukk'";
	$vvoy = $db->query($ves_voy);
	$hasil_vv = $vvoy->fetchRow();
	$vessel = $hasil_vv['NM_KAPAL'];
	$voyage_in = $hasil_vv['VOYAGE_IN'];
	$voyage_out = $hasil_vv['VOYAGE_OUT'];	

$dt_tgl = date('Ymd');
$dt_tm = date('Hi');
$dt2 = date('ymd');
$dt_tgl2 = date('ymdHi');

//================================== COARRI DISCH FCL ===================================//

$cek_count = "SELECT MAX(COUNTER) AS MAX_EXP
					FROM TB_EDIFACT_EXP
                    WHERE NO_UKK = '$no_ukk'
						AND STAT_FILE = 'COARRI'
						AND E_I = 'I'
						AND STATUS_CONT = 'FCL'
						AND LINE_OPERATOR = 'MSL'";
	$counter = $db->query($cek_count);
	$hasil_count = $counter->fetchRow();
	
	if($hasil_count['MAX_EXP']=="")
	{
		$counter_exp = 1;
	}
	else
	{
		$counter_exp = $hasil_count['MAX_EXP']+1;
	}

$jml_cont1 = "SELECT COUNT(*) AS JML FROM
                (
                SELECT NO_CONTAINER,
                            ISO_CODE,
                            STATUS,
                            CASE WHEN TRIM(STATUS)='FCL' THEN '5' 
                             ELSE '4' END INDIKATOR,
                            E_I,
                            NVL(CARRIER,0) AS CARRIER,
                            NVL(BOOKING_SL,0) AS BOOKING_SL,
                            NVL(SEAL_ID,0) AS SEAL_ID,
                            TRIM(NVL(NO_TRUCK,0)) AS NO_TRUCK,
                            NVL(ALAT,0) AS ALAT,
                            TRIM(POD) AS POD,
                            TRIM(POL) AS POL,
                            BERAT,
                            CASE WHEN TRIM(E_I)='E' THEN '46' 
                              ELSE '44' END BGM_STAT,
							CASE WHEN TRIM(E_I)='E' THEN '2' 
                              ELSE '3' END EQD_STAT,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MI') AS DATE_CONFIRM,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MISS') AS SEQ
                FROM ISWS_LIST_CONTAINER 
                   WHERE NO_UKK = '$no_ukk'
					AND E_I = 'I'
					AND TRIM(STATUS) = 'FCL' 
                ORDER BY SEQ ASC
                )
                WHERE DATE_CONFIRM IS NOT NULL";
$result_jml1 = $db->query($jml_cont1);
$jml1 = $result_jml1->fetchRow();
$count1 = $jml1['JML'];
	
if($count1>0)
{	
$cont_import = "SELECT * FROM
                (
                SELECT NO_CONTAINER,
                            ISO_CODE,
                            STATUS,
                            CASE WHEN TRIM(STATUS)='FCL' THEN '5' 
                             ELSE '4' END INDIKATOR,
                            E_I,
                            NVL(CARRIER,0) AS CARRIER,
                            NVL(BOOKING_SL,0) AS BOOKING_SL,
                            NVL(SEAL_ID,0) AS SEAL_ID,
                            TRIM(NVL(NO_TRUCK,0)) AS NO_TRUCK,
                            NVL(ALAT,0) AS ALAT,
                            TRIM(POD) AS POD,
                            TRIM(POL) AS POL,
                            BERAT,
                            CASE WHEN TRIM(E_I)='E' THEN '46' 
                              ELSE '44' END BGM_STAT,
							CASE WHEN TRIM(E_I)='E' THEN '2' 
                              ELSE '3' END EQD_STAT,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MI') AS DATE_CONFIRM,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MISS') AS SEQ
                FROM ISWS_LIST_CONTAINER 
                   WHERE NO_UKK = '$no_ukk'
					AND E_I = 'I'
					AND TRIM(STATUS) = 'FCL' 
                ORDER BY SEQ ASC
                )
                WHERE DATE_CONFIRM IS NOT NULL
					AND ROWNUM < 4";
$result_cont = $db->query($cont_import);
$cont_list = $result_cont->getAll();

$file_name = "MSL_DSF_".$no_ukk."_".$counter_exp.".edi";

$fp = fopen('./edifact/'.$file_name, 'w');
	  fwrite($fp, "UNB+UNOA:1+IDTER3+MAEU+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
	  
	  $n = 1;
	  foreach($cont_list as $conts)
	  {
		$nocont = $conts['NO_CONTAINER'];
		$isocode = $conts['ISO_CODE'];
		$st = $conts['INDIKATOR'];
		$pol = $conts['POL'];
		$pod = $conts['POD'];
		$grt = $conts['BERAT'];
		$seal = $conts['SEAL_ID'];
		$confirm_tgl = $conts['DATE_CONFIRM'];
		$crr = $conts['CARRIER'];
		$bgm = $conts['BGM_STAT'];
		$eqd = $conts['EQD_STAT'];
		$bsl = $conts['BOOKING_SL'];
		$trck = $conts['NO_TRUCK'];
		$alat = $conts['ALAT'];
		$ei = $conts['E_I'];
		
			if($ei=='E')
			{
				$voy = $voyage_out;
				$prt = $pol;
			}
			else
			{
				$voy = $voyage_in;
				$prt = $pod;
			}
		
		fwrite($fp, "UNH+".$n."+COARRI:D:95B:UN:ITG12'");
		fwrite($fp, "BGM+".$bgm."+0000000000+9'");
		fwrite($fp, "TDT+20+".$voy."+1++".$crr.":172:166+++:103::".$vessel."'");
		fwrite($fp, "NAD+CF+".$crr.":160:166'");
		fwrite($fp, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
		fwrite($fp, "DTM+7:".$confirm_tgl."'");
		fwrite($fp, "LOC+9+".$prt."45:139:6'");
		fwrite($fp, "MEA+AAE+G+KGM:".$grt."'");
		fwrite($fp, "CNT+16:1'");
		fwrite($fp, "UNT+9+".$n."'");		
		
		$n++;
	  }	  
	  
	  $jml = $n-1;
	  fwrite($fp, "UNZ+".$jml."+".$dt_tgl2."1014'");
	  fclose($fp);
	  
$insert_exp = "INSERT INTO TB_EDIFACT_EXP
				(NO_UKK,
				 STAT_FILE,
				 E_I,
				 USER_UPDATE,
				 FILE_NAME,
				 COUNTER,
				 STATUS_CONT,
				 LINE_OPERATOR
				 )
				VALUES
				('$no_ukk',
				 'COARRI',
				 'I',
				 '$id_user',
				 '$file_name',
				 '$counter_exp',
				 'FCL',
				 'MSL'
				)";
$db->query($insert_exp);
}
//================================== COARRI DISCH FCL ===================================//

//================================== COARRI DISCH MTY ===================================//

$cek_count_mty = "SELECT MAX(COUNTER) AS MAX_EXP
					FROM TB_EDIFACT_EXP
                    WHERE NO_UKK = '$no_ukk'
						AND STAT_FILE = 'COARRI'
						AND E_I = 'I'
						AND STATUS_CONT = 'MTY'
						AND LINE_OPERATOR = 'MSL'";
	$counter_mty = $db->query($cek_count_mty);
	$hasil_count_mty = $counter_mty->fetchRow();
	
	if($hasil_count_mty['MAX_EXP']=="")
	{
		$counter_exp_mty = 1;
	}
	else
	{
		$counter_exp_mty = $hasil_count_mty['MAX_EXP']+1;
	}

$jml_cont2 = "SELECT COUNT(*) AS JML FROM
                (
                SELECT NO_CONTAINER,
                            ISO_CODE,
                            STATUS,
                            CASE WHEN TRIM(STATUS)='FCL' THEN '5' 
                             ELSE '4' END INDIKATOR,
                            E_I,
                            NVL(CARRIER,0) AS CARRIER,
                            NVL(BOOKING_SL,0) AS BOOKING_SL,
                            NVL(SEAL_ID,0) AS SEAL_ID,
                            TRIM(NVL(NO_TRUCK,0)) AS NO_TRUCK,
                            NVL(ALAT,0) AS ALAT,
                            TRIM(POD) AS POD,
                            TRIM(POL) AS POL,
                            BERAT,
                            CASE WHEN TRIM(E_I)='E' THEN '46' 
                              ELSE '44' END BGM_STAT,
							CASE WHEN TRIM(E_I)='E' THEN '2' 
                              ELSE '3' END EQD_STAT,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MI') AS DATE_CONFIRM,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MISS') AS SEQ
                FROM ISWS_LIST_CONTAINER 
                   WHERE NO_UKK = '$no_ukk'
					AND E_I = 'I'
					AND TRIM(STATUS) = 'MTY' 
                ORDER BY SEQ ASC
                )
                WHERE DATE_CONFIRM IS NOT NULL";
$result_jml2 = $db->query($jml_cont2);
$jml2 = $result_jml2->fetchRow();
$count2 = $jml2['JML'];
	
if($count2>0)	
{	
$cont_import_mty = "SELECT * FROM
                (
                SELECT NO_CONTAINER,
                            ISO_CODE,
                            STATUS,
                            CASE WHEN TRIM(STATUS)='FCL' THEN '5' 
                             ELSE '4' END INDIKATOR,
                            E_I,
                            NVL(CARRIER,0) AS CARRIER,
                            NVL(BOOKING_SL,0) AS BOOKING_SL,
                            NVL(SEAL_ID,0) AS SEAL_ID,
                            TRIM(NVL(NO_TRUCK,0)) AS NO_TRUCK,
                            NVL(ALAT,0) AS ALAT,
                            TRIM(POD) AS POD,
                            TRIM(POL) AS POL,
                            BERAT,
                            CASE WHEN TRIM(E_I)='E' THEN '46' 
                              ELSE '44' END BGM_STAT,
							CASE WHEN TRIM(E_I)='E' THEN '2' 
                              ELSE '3' END EQD_STAT,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MI') AS DATE_CONFIRM,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MISS') AS SEQ
                FROM ISWS_LIST_CONTAINER 
                   WHERE NO_UKK = '$no_ukk'
					AND E_I = 'I'
					AND TRIM(STATUS) = 'MTY' 
                ORDER BY SEQ ASC
                )
                WHERE DATE_CONFIRM IS NOT NULL
					AND ROWNUM < 4";
$result_cont_mty = $db->query($cont_import_mty);
$cont_list_mty = $result_cont_mty->getAll();

$file_name_mty = "MSL_DSE_".$no_ukk."_".$counter_exp_mty.".edi";

$fp_mty = fopen('./edifact/'.$file_name_mty, 'w');
	  fwrite($fp_mty, "UNB+UNOA:1+IDTER3+MAEU+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
	  
	  $m = 1;
	  foreach($cont_list_mty as $conts)
	  {
		$nocont = $conts['NO_CONTAINER'];
		$isocode = $conts['ISO_CODE'];
		$st = $conts['INDIKATOR'];
		$pol = $conts['POL'];
		$pod = $conts['POD'];
		$grt = $conts['BERAT'];
		$seal = $conts['SEAL_ID'];
		$confirm_tgl = $conts['DATE_CONFIRM'];
		$crr = $conts['CARRIER'];
		$bgm = $conts['BGM_STAT'];
		$eqd = $conts['EQD_STAT'];
		$bsl = $conts['BOOKING_SL'];
		$trck = $conts['NO_TRUCK'];
		$alat = $conts['ALAT'];
		$ei = $conts['E_I'];
		
			if($ei=='E')
			{
				$voy = $voyage_out;
				$prt = $pol;
			}
			else
			{
				$voy = $voyage_in;
				$prt = $pod;
			}
		
		fwrite($fp_mty, "UNH+".$m."+COARRI:D:95B:UN:ITG12'");
		fwrite($fp_mty, "BGM+".$bgm."+0000000000+9'");
		fwrite($fp_mty, "TDT+20+".$voy."+1++".$crr.":172:166+++:103::".$vessel."'");
		fwrite($fp_mty, "NAD+CF+".$crr.":160:166'");
		fwrite($fp_mty, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
		fwrite($fp_mty, "DTM+7:".$confirm_tgl."'");
		fwrite($fp_mty, "LOC+9+".$prt."45:139:6'");
		fwrite($fp_mty, "MEA+AAE+G+KGM:".$grt."'");
		fwrite($fp_mty, "CNT+16:1'");
		fwrite($fp_mty, "UNT+9+".$m."'");		
		
		$m++;
	  }	  
	  
	  $jml_mty = $m-1;
	  fwrite($fp_mty, "UNZ+".$jml_mty."+".$dt_tgl2."1014'");
	  fclose($fp_mty);
	  
$insert_exp_mty = "INSERT INTO TB_EDIFACT_EXP
				(NO_UKK,
				 STAT_FILE,
				 E_I,
				 USER_UPDATE,
				 FILE_NAME,
				 COUNTER,
				 STATUS_CONT,
				 LINE_OPERATOR
				 )
				VALUES
				('$no_ukk',
				 'COARRI',
				 'I',
				 '$id_user',
				 '$file_name_mty',
				 '$counter_exp_mty',
				 'MTY',
				 'MSL'
				)";
$db->query($insert_exp_mty);
}
//================================== COARRI DISCH MTY ===================================//
	  
//================================== COARRI LOAD FCL ===================================//

$cek_count2 = "SELECT MAX(COUNTER) AS MAX_EXP
					FROM TB_EDIFACT_EXP
                    WHERE NO_UKK = '$no_ukk'
						AND STAT_FILE = 'COARRI'
						AND E_I = 'E'
						AND STATUS_CONT = 'FCL'
						AND LINE_OPERATOR = 'MSL'";
	$counter2 = $db->query($cek_count2);
	$hasil_count2 = $counter2->fetchRow();
	
	if($hasil_count2['MAX_EXP']=="")
	{
		$counter_exp2 = 1;
	}
	else
	{
		$counter_exp2 = $hasil_count2['MAX_EXP']+1;
	}

$jml_cont3 = "SELECT COUNT(*) AS JML FROM
                (
                SELECT NO_CONTAINER,
                            ISO_CODE,
                            STATUS,
                            CASE WHEN TRIM(STATUS)='FCL' THEN '5' 
                             ELSE '4' END INDIKATOR,
                            E_I,
                            NVL(CARRIER,'MCC') AS CARRIER,
							NVL(BOOKING_SL,'Y') AS BOOKING_SL,
							NVL(SEAL_ID,'Y') AS SEAL_ID,
							TRIM(NVL(NO_TRUCK,'JKT888')) AS NO_TRUCK,
                            NVL(ALAT,0) AS ALAT,
                            TRIM(POD) AS POD,
                            TRIM(POL) AS POL,
                            BERAT,
                            CASE WHEN TRIM(E_I)='E' THEN '46' 
                              ELSE '44' END BGM_STAT,
							CASE WHEN TRIM(E_I)='E' THEN '2' 
                              ELSE '3' END EQD_STAT,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MI') AS DATE_CONFIRM,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MISS') AS SEQ
                FROM ISWS_LIST_CONTAINER 
                   WHERE NO_UKK = '$no_ukk'
					AND E_I = 'E'
					AND TRIM(STATUS) = 'FCL' 
                ORDER BY SEQ ASC
                )
                WHERE DATE_CONFIRM IS NOT NULL";
$result_jml3 = $db->query($jml_cont3);
$jml3 = $result_jml3->fetchRow();
$count3 = $jml3['JML'];
	
if($count3>0)	
{	
$cont_import2 = "SELECT * FROM
                (
                SELECT NO_CONTAINER,
                            ISO_CODE,
                            STATUS,
                            CASE WHEN TRIM(STATUS)='FCL' THEN '5' 
                             ELSE '4' END INDIKATOR,
                            E_I,
                            NVL(CARRIER,'MCC') AS CARRIER,
							NVL(BOOKING_SL,'Y') AS BOOKING_SL,
							NVL(SEAL_ID,'Y') AS SEAL_ID,
							TRIM(NVL(NO_TRUCK,'JKT888')) AS NO_TRUCK,
                            NVL(ALAT,0) AS ALAT,
                            TRIM(POD) AS POD,
                            TRIM(POL) AS POL,
                            BERAT,
                            CASE WHEN TRIM(E_I)='E' THEN '46' 
                              ELSE '44' END BGM_STAT,
							CASE WHEN TRIM(E_I)='E' THEN '2' 
                              ELSE '3' END EQD_STAT,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MI') AS DATE_CONFIRM,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MISS') AS SEQ
                FROM ISWS_LIST_CONTAINER 
                   WHERE NO_UKK = '$no_ukk'
					AND E_I = 'E'
					AND TRIM(STATUS) = 'FCL' 
                ORDER BY SEQ ASC
                )
                WHERE DATE_CONFIRM IS NOT NULL
					AND ROWNUM < 4";
$result_cont2 = $db->query($cont_import2);
$cont_list2 = $result_cont2->getAll();

$file_name2 = "MSL_LOF_".$no_ukk."_".$counter_exp2.".edi";

$fp2 = fopen('./edifact/'.$file_name2, 'w');
	  fwrite($fp2, "UNB+UNOA:1+IDTER3+MAEU+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
	  
	  $c = 1;
	  foreach($cont_list2 as $conts)
	  {
		$nocont = $conts['NO_CONTAINER'];
		$isocode = $conts['ISO_CODE'];
		$st = $conts['INDIKATOR'];
		$pol = $conts['POL'];
		$pod = $conts['POD'];
		$grt = $conts['BERAT'];
		$seal = $conts['SEAL_ID'];
		$confirm_tgl = $conts['DATE_CONFIRM'];
		$crr = $conts['CARRIER'];
		$bgm = $conts['BGM_STAT'];
		$eqd = $conts['EQD_STAT'];
		$bsl = $conts['BOOKING_SL'];
		$trck = $conts['NO_TRUCK'];
		$alat = $conts['ALAT'];
		$ei = $conts['E_I'];
		
			if($ei=='E')
			{
				$voy = $voyage_out;
				$prt = $pol;
			}
			else
			{
				$voy = $voyage_in;
				$prt = $pod;
			}
		
		fwrite($fp2, "UNH+".$c."+COARRI:D:95B:UN:ITG12'");
		fwrite($fp2, "BGM+".$bgm."+0000000000+9'");
		fwrite($fp2, "TDT+20+".$voy."+1++".$crr.":172:166+++:103::".$vessel."'");
		fwrite($fp2, "NAD+CF+".$crr.":160:166'");
		fwrite($fp2, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
		fwrite($fp2, "DTM+7:".$confirm_tgl."'");
		fwrite($fp2, "LOC+9+".$pol."45:139:6'");
		fwrite($fp2, "MEA+AAE+G+KGM:".$grt."'");
		fwrite($fp2, "CNT+16:1'");
		fwrite($fp2, "UNT+9+".$c."'");		
		
		$c++;
	  }	  
	  
	  $jml2 = $c-1;
	  fwrite($fp2, "UNZ+".$jml2."+".$dt_tgl2."1014'");
	  fclose($fp2);
	  
$insert_exp2 = "INSERT INTO TB_EDIFACT_EXP
				(NO_UKK,
				 STAT_FILE,
				 E_I,
				 USER_UPDATE,
				 FILE_NAME,
				 COUNTER,
				 STATUS_CONT,
				 LINE_OPERATOR
				 )
				VALUES
				('$no_ukk',
				 'COARRI',
				 'E',
				 '$id_user',
				 '$file_name2',
				 '$counter_exp2',
				 'FCL',
				 'MSL'
				)";
$db->query($insert_exp2);
}
//================================== COARRI LOAD FCL ===================================//

//================================== COARRI LOAD MTY ===================================//

$cek_count_mty3 = "SELECT MAX(COUNTER) AS MAX_EXP
					FROM TB_EDIFACT_EXP
                    WHERE NO_UKK = '$no_ukk'
						AND STAT_FILE = 'COARRI'
						AND E_I = 'E'
						AND STATUS_CONT = 'MTY'
						AND LINE_OPERATOR = 'MSL'";
	$counter_mty3 = $db->query($cek_count_mty3);
	$hasil_count_mty3 = $counter_mty3->fetchRow();
	
	if($hasil_count_mty3['MAX_EXP']=="")
	{
		$counter_exp_mty3 = 1;
	}
	else
	{
		$counter_exp_mty3 = $hasil_count_mty3['MAX_EXP']+1;
	}

$jml_cont4 = "SELECT COUNT(*) AS JML FROM
                (
                SELECT NO_CONTAINER,
                            ISO_CODE,
                            STATUS,
                            CASE WHEN TRIM(STATUS)='FCL' THEN '5' 
                             ELSE '4' END INDIKATOR,
                            E_I,
                            NVL(CARRIER,'MCC') AS CARRIER,
							NVL(BOOKING_SL,'Y') AS BOOKING_SL,
							NVL(SEAL_ID,'Y') AS SEAL_ID,
							TRIM(NVL(NO_TRUCK,'JKT888')) AS NO_TRUCK,
                            NVL(ALAT,0) AS ALAT,
                            TRIM(POD) AS POD,
                            TRIM(POL) AS POL,
                            BERAT,
                            CASE WHEN TRIM(E_I)='E' THEN '46' 
                              ELSE '44' END BGM_STAT,
							CASE WHEN TRIM(E_I)='E' THEN '2' 
                              ELSE '3' END EQD_STAT,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MI') AS DATE_CONFIRM,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MISS') AS SEQ
                FROM ISWS_LIST_CONTAINER 
                   WHERE NO_UKK = '$no_ukk'
					AND E_I = 'E'
					AND TRIM(STATUS) = 'MTY' 
                ORDER BY SEQ ASC
                )
                WHERE DATE_CONFIRM IS NOT NULL";
$result_jml4 = $db->query($jml_cont4);
$jml4 = $result_jml4->fetchRow();
$count4 = $jml4['JML'];
	
if($count4>0)	
{	
$cont_import_mty3 = "SELECT * FROM
                (
                SELECT NO_CONTAINER,
                            ISO_CODE,
                            STATUS,
                            CASE WHEN TRIM(STATUS)='FCL' THEN '5' 
                             ELSE '4' END INDIKATOR,
                            E_I,
                            NVL(CARRIER,'MCC') AS CARRIER,
							NVL(BOOKING_SL,'Y') AS BOOKING_SL,
							NVL(SEAL_ID,'Y') AS SEAL_ID,
							TRIM(NVL(NO_TRUCK,'JKT888')) AS NO_TRUCK,
                            NVL(ALAT,0) AS ALAT,
                            TRIM(POD) AS POD,
                            TRIM(POL) AS POL,
                            BERAT,
                            CASE WHEN TRIM(E_I)='E' THEN '46' 
                              ELSE '44' END BGM_STAT,
							CASE WHEN TRIM(E_I)='E' THEN '2' 
                              ELSE '3' END EQD_STAT,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MI') AS DATE_CONFIRM,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MISS') AS SEQ
                FROM ISWS_LIST_CONTAINER 
                   WHERE NO_UKK = '$no_ukk'
					AND E_I = 'E'
					AND TRIM(STATUS) = 'MTY' 
                ORDER BY SEQ ASC
                )
                WHERE DATE_CONFIRM IS NOT NULL
					AND ROWNUM < 4";
$result_cont_mty3 = $db->query($cont_import_mty3);
$cont_list_mty3 = $result_cont_mty3->getAll();

$file_name_mty3 = "MSL_LOE_".$no_ukk."_".$counter_exp_mty3.".edi";

$fp_mty3 = fopen('./edifact/'.$file_name_mty3, 'w');
	  fwrite($fp_mty3, "UNB+UNOA:1+IDTER3+MAEU+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
	  
	  $d = 1;
	  foreach($cont_list_mty3 as $conts)
	  {
		$nocont = $conts['NO_CONTAINER'];
		$isocode = $conts['ISO_CODE'];
		$st = $conts['INDIKATOR'];
		$pol = $conts['POL'];
		$pod = $conts['POD'];
		$grt = $conts['BERAT'];
		$seal = $conts['SEAL_ID'];
		$confirm_tgl = $conts['DATE_CONFIRM'];
		$crr = $conts['CARRIER'];
		$bgm = $conts['BGM_STAT'];
		$eqd = $conts['EQD_STAT'];
		$bsl = $conts['BOOKING_SL'];
		$trck = $conts['NO_TRUCK'];
		$alat = $conts['ALAT'];
		$ei = $conts['E_I'];
		
			if($ei=='E')
			{
				$voy = $voyage_out;
				$prt = $pol;
			}
			else
			{
				$voy = $voyage_in;
				$prt = $pod;
			}
		
		fwrite($fp_mty3, "UNH+".$d."+COARRI:D:95B:UN:ITG12'");
		fwrite($fp_mty3, "BGM+".$bgm."+0000000000+9'");
		fwrite($fp_mty3, "TDT+20+".$voy."+1++".$crr.":172:166+++:103::".$vessel."'");
		fwrite($fp_mty3, "NAD+CF+".$crr.":160:166'");
		fwrite($fp_mty3, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
		fwrite($fp_mty3, "DTM+7:".$confirm_tgl.":203'");
		fwrite($fp_mty3, "LOC+9+".$pol."45:139:6'");
		fwrite($fp_mty3, "MEA+AAE+G+KGM:".$grt."'");
		fwrite($fp_mty3, "CNT+16:1'");
		fwrite($fp_mty3, "UNT+9+".$d."'");		
		
		$d++;
	  }	  
	  
	  $jml_mty3 = $d-1;
	  fwrite($fp_mty3, "UNZ+".$jml_mty3."+".$dt_tgl2."1014'");
	  fclose($fp_mty3);
	  
$insert_exp_mty = "INSERT INTO TB_EDIFACT_EXP
				(NO_UKK,
				 STAT_FILE,
				 E_I,
				 USER_UPDATE,
				 FILE_NAME,
				 COUNTER,
				 STATUS_CONT,
				 LINE_OPERATOR
				 )
				VALUES
				('$no_ukk',
				 'COARRI',
				 'E',
				 '$id_user',
				 '$file_name_mty3',
				 '$counter_exp_mty3',
				 'MTY',
				 'MSL'
				)";
$db->query($insert_exp_mty);
}
//================================== COARRI LOAD MTY ===================================//	  
	  
echo "sukses";	  

?>




















