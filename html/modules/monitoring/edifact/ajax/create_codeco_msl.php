<?php
$id_user = $_SESSION["ID_USER"];
$no_ukk	= $_POST['NO_UKK'];

$db = getDB();
//print_r($act);die;	

/*
	UNB+UNOA:1+IDJKTOJA+MAEU+101012:1709+10101217091014'
	
	UNH+1+CODECO:D:95B:UN:ITG12'
	BGM+36+0000000000+9'
	NAD+CF+MAEU:160:166'
	EQD+CN+MSKU6353588+0000:102:5++3+5'
	DTM+7:201010121709:203'
	LOC+165+IDJKT45:139:6'
	HAN+6'
	TDT+1++3++JKT888:172:87'
	CNT+16:1'
	UNT+10+1'
	
	UNZ+1+10101217091014'
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

//============================= CODECO IMPORT FCL ================================//

	$cek_count_fcl = "SELECT MAX(COUNTER) AS MAX_EXP
					FROM TB_EDIFACT_EXP
                    WHERE NO_UKK = '$no_ukk'
						AND STAT_FILE = 'CODECO'
						AND E_I = 'I'
						AND STATUS_CONT = 'FCL'
						AND LINE_OPERATOR = 'MSL'";
	$counter_fcl = $db->query($cek_count_fcl);
	$hasil_count_fcl = $counter_fcl->fetchRow();
	
	if($hasil_count_fcl['MAX_EXP']=="")
	{
		$counter_exp = 1;
	}
	else
	{
		$counter_exp = $hasil_count_fcl['MAX_EXP']+1;
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
						TRIM(POD) AS POD,
						TRIM(POL) AS POL,
						BERAT,
						CASE WHEN TRIM(E_I)='E' THEN '34' 
						 ELSE '36' END BGM_STAT,
						CASE WHEN TRIM(E_I)='E' THEN '2' 
                         ELSE '3' END EQD_STAT,
						CASE WHEN TRIM(E_I)='E' THEN TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MI') 
						 ELSE TO_CHAR(TGL_GATE_OUT, 'YYYYMMDDHH24MI') END TGL_GATE
			FROM ISWS_LIST_CONTAINER 
			   WHERE NO_UKK = '$no_ukk'
					AND E_I = 'I'
					AND TRIM(STATUS) = 'FCL'
					AND TO_CHAR(TGL_GATE_OUT, 'YYYYMMDDHH24MI') IS NOT NULL
					AND ROWNUM < 4
			ORDER BY  TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MISS') ASC
			)";
$result_jml1 = $db->query($jml_cont1);
$jml1 = $result_jml1->fetchRow();
$count1 = $jml1['JML'];			

if($count1>0)
{	
$cont_import_fcl = "SELECT NO_CONTAINER,
						ISO_CODE,
						STATUS,
						CASE WHEN TRIM(STATUS)='FCL' THEN '5' 
						 ELSE '4' END INDIKATOR,
						E_I,
						NVL(CARRIER,0) AS CARRIER,
						NVL(BOOKING_SL,0) AS BOOKING_SL,
						NVL(SEAL_ID,0) AS SEAL_ID,
						TRIM(NVL(NO_TRUCK,0)) AS NO_TRUCK,
						TRIM(POD) AS POD,
						TRIM(POL) AS POL,
						BERAT,
						CASE WHEN TRIM(E_I)='E' THEN '34' 
						 ELSE '36' END BGM_STAT,
						CASE WHEN TRIM(E_I)='E' THEN '2' 
                         ELSE '3' END EQD_STAT,
						CASE WHEN TRIM(E_I)='E' THEN TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MI') 
						 ELSE TO_CHAR(TGL_GATE_OUT, 'YYYYMMDDHH24MI') END TGL_GATE
			FROM ISWS_LIST_CONTAINER 
			   WHERE NO_UKK = '$no_ukk'
					AND E_I = 'I'
					AND TRIM(STATUS) = 'FCL'
					AND TO_CHAR(TGL_GATE_OUT, 'YYYYMMDDHH24MI') IS NOT NULL
					AND ROWNUM < 4
			ORDER BY  TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MISS') ASC";
$result_cont_fcl = $db->query($cont_import_fcl);
$cont_list_fcl = $result_cont_fcl->getAll();

$file_name = "MSL_GOD_".$no_ukk."_".$counter_exp.".edi";

$fp = fopen('./edifact/'.$file_name, 'w');
	  fwrite($fp, "UNB+UNOA:1+IDTER3+MAEU+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
	  
	  $n = 1;
	  foreach($cont_list_fcl as $conts)
	  {
		$nocont = $conts['NO_CONTAINER'];
		$isocode = $conts['ISO_CODE'];
		$st = $conts['INDIKATOR'];
		$pol = $conts['POL'];
		$pod = $conts['POD'];
		$grt = $conts['BERAT'];
		$seal = $conts['SEAL_ID'];
		$gate_tgl = $conts['TGL_GATE'];
		$crr = $conts['CARRIER'];
		$bgm = $conts['BGM_STAT'];
		$eqd = $conts['EQD_STAT'];
		$bsl = $conts['BOOKING_SL'];
		$trck = $conts['NO_TRUCK'];
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
		
		fwrite($fp, "UNH+".$n."+CODECO:D:95B:UN:ITG12'");
		fwrite($fp, "BGM+".$bgm."+0000000000+9'");
		fwrite($fp, "NAD+CF+".$crr.":160:166'");
		fwrite($fp, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
		fwrite($fp, "DTM+7:".$gate_tgl.":203'");
		fwrite($fp, "LOC+165+".$prt."45:139:6'");
		fwrite($fp, "MEA+AAE+G+KGM:".$grt."'");
		fwrite($fp, "HAN+6'");		
		fwrite($fp, "TDT+1++3++JKT888:172:87'");
		fwrite($fp, "CNT+16:1'");
		fwrite($fp, "UNT+10+".$n."'");		
		
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
				 'CODECO',
				 'I',
				 '$id_user',
				 '$file_name',
				 '$counter_exp',
				 'FCL',
				 'MSL'
				)";
$db->query($insert_exp);
}
//============================= CODECO IMPORT FCL ================================//
	  
	  
//============================= CODECO IMPORT MTY ================================//

$cek_count_mty = "SELECT MAX(COUNTER) AS MAX_EXP
					FROM TB_EDIFACT_EXP
                    WHERE NO_UKK = '$no_ukk'
						AND STAT_FILE = 'CODECO'
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
						TRIM(POD) AS POD,
						TRIM(POL) AS POL,
						BERAT,
						CASE WHEN TRIM(E_I)='E' THEN '34' 
						 ELSE '36' END BGM_STAT,
						CASE WHEN TRIM(E_I)='E' THEN '2' 
                         ELSE '3' END EQD_STAT,
						CASE WHEN TRIM(E_I)='E' THEN TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MI') 
						 ELSE TO_CHAR(TGL_GATE_OUT, 'YYYYMMDDHH24MI') END TGL_GATE
			FROM ISWS_LIST_CONTAINER 
			   WHERE NO_UKK = '$no_ukk'
					AND E_I = 'I'
					AND TRIM(STATUS) = 'MTY'
					AND TO_CHAR(TGL_GATE_OUT, 'YYYYMMDDHH24MI') IS NOT NULL
					AND ROWNUM < 4
			ORDER BY  TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MISS') ASC
			)";
$result_jml2 = $db->query($jml_cont2);
$jml2 = $result_jml2->fetchRow();
$count2 = $jml2['JML'];

if($count2>0)		
{	
$cont_import_mty = "SELECT NO_CONTAINER,
						ISO_CODE,
						STATUS,
						CASE WHEN TRIM(STATUS)='FCL' THEN '5' 
						 ELSE '4' END INDIKATOR,
						E_I,
						NVL(CARRIER,0) AS CARRIER,
						NVL(BOOKING_SL,0) AS BOOKING_SL,
						NVL(SEAL_ID,0) AS SEAL_ID,
						TRIM(NVL(NO_TRUCK,0)) AS NO_TRUCK,
						TRIM(POD) AS POD,
						TRIM(POL) AS POL,
						BERAT,
						CASE WHEN TRIM(E_I)='E' THEN '34' 
						 ELSE '36' END BGM_STAT,
						CASE WHEN TRIM(E_I)='E' THEN '2' 
                         ELSE '3' END EQD_STAT,
						CASE WHEN TRIM(E_I)='E' THEN TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MI') 
						 ELSE TO_CHAR(TGL_GATE_OUT, 'YYYYMMDDHH24MI') END TGL_GATE
			FROM ISWS_LIST_CONTAINER 
			   WHERE NO_UKK = '$no_ukk'
					AND E_I = 'I'
					AND TRIM(STATUS) = 'MTY'
					AND TO_CHAR(TGL_GATE_OUT, 'YYYYMMDDHH24MI') IS NOT NULL
					AND ROWNUM < 4
			ORDER BY  TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MISS') ASC";
$result_cont_mty = $db->query($cont_import_mty);
$cont_list_mty = $result_cont_mty->getAll();

$file_name_mty = "MSL_GOP_".$no_ukk."_".$counter_exp_mty.".edi";

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
		$gate_tgl = $conts['TGL_GATE'];
		$crr = $conts['CARRIER'];
		$bgm = $conts['BGM_STAT'];
		$eqd = $conts['EQD_STAT'];
		$bsl = $conts['BOOKING_SL'];
		$trck = TRIM($conts['NO_TRUCK']);
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
		
		fwrite($fp_mty, "UNH+".$m."+CODECO:D:95B:UN:ITG12'");
		fwrite($fp_mty, "BGM+".$bgm."+0000000000+9'");
		fwrite($fp_mty, "NAD+CF+".$crr.":160:166'");
		fwrite($fp_mty, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
		fwrite($fp_mty, "DTM+7:".$gate_tgl.":203'");
		fwrite($fp_mty, "LOC+165+".$prt."45:139:6'");
		fwrite($fp_mty, "MEA+AAE+G+KGM:".$grt."'");
		fwrite($fp_mty, "HAN+6'");		
		fwrite($fp_mty, "TDT+1++3++JKT888:172:87'");
		fwrite($fp_mty, "CNT+16:1'");
		fwrite($fp_mty, "UNT+10+".$m."'");		
		
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
				 'CODECO',
				 'I',
				 '$id_user',
				 '$file_name_mty',
				 '$counter_exp_mty',
				 'MTY',
				 'MSL'
				)";
$db->query($insert_exp_mty);
}
//============================= CODECO IMPORT MTY ================================//	  


//============================= CODECO EXPORT FCL ================================//

	$cek_count_fcl2 = "SELECT MAX(COUNTER) AS MAX_EXP
					FROM TB_EDIFACT_EXP
                    WHERE NO_UKK = '$no_ukk'
						AND STAT_FILE = 'CODECO'
						AND E_I = 'E'
						AND STATUS_CONT = 'FCL'
						AND LINE_OPERATOR = 'MSL'";
	$counter_fcl2 = $db->query($cek_count_fcl2);
	$hasil_count_fcl2 = $counter_fcl2->fetchRow();
	
	if($hasil_count_fcl2['MAX_EXP']=="")
	{
		$counter_exp2 = 1;
	}
	else
	{
		$counter_exp2 = $hasil_count_fcl2['MAX_EXP']+1;
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
						TRIM(POD) AS POD,
						TRIM(POL) AS POL,
						BERAT,
						CASE WHEN TRIM(E_I)='E' THEN '34' 
						 ELSE '36' END BGM_STAT,
						CASE WHEN TRIM(E_I)='E' THEN '2' 
                         ELSE '3' END EQD_STAT,
						CASE WHEN TRIM(E_I)='E' THEN TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MI') 
						 ELSE TO_CHAR(TGL_GATE_OUT, 'YYYYMMDDHH24MI') END TGL_GATE
			FROM ISWS_LIST_CONTAINER 
			   WHERE NO_UKK = '$no_ukk'
					AND E_I = 'E'
					AND TRIM(STATUS) = 'FCL'
					AND TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MI') IS NOT NULL
					AND ROWNUM < 4
			ORDER BY  TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MISS') ASC
			)";
$result_jml3 = $db->query($jml_cont3);
$jml3 = $result_jml3->fetchRow();
$count3 = $jml3['JML'];

if($count3>0)	
{	
$cont_export_fcl = "SELECT NO_CONTAINER,
						ISO_CODE,
						STATUS,
						CASE WHEN TRIM(STATUS)='FCL' THEN '5' 
						 ELSE '4' END INDIKATOR,
						E_I,
						NVL(CARRIER,'MCC') AS CARRIER,
						NVL(BOOKING_SL,'Y') AS BOOKING_SL,
						NVL(SEAL_ID,'Y') AS SEAL_ID,
						TRIM(NVL(NO_TRUCK,'JKT888')) AS NO_TRUCK,
						TRIM(POD) AS POD,
						TRIM(POL) AS POL,
						BERAT,
						CASE WHEN TRIM(E_I)='E' THEN '34' 
						 ELSE '36' END BGM_STAT,
						CASE WHEN TRIM(E_I)='E' THEN '2' 
                         ELSE '3' END EQD_STAT,
						CASE WHEN TRIM(E_I)='E' THEN TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MI') 
						 ELSE TO_CHAR(TGL_GATE_OUT, 'YYYYMMDDHH24MI') END TGL_GATE
			FROM ISWS_LIST_CONTAINER 
			   WHERE NO_UKK = '$no_ukk'
					AND E_I = 'E'
					AND TRIM(STATUS) = 'FCL'
					AND TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MI') IS NOT NULL
					AND ROWNUM < 4
			ORDER BY  TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MISS') ASC";
$result_export_fcl = $db->query($cont_export_fcl);
$cont_export_fcl = $result_export_fcl->getAll();

$file_name_exp = "MSL_GIR_".$no_ukk."_".$counter_exp2.".edi";

$fp_exp_fcl = fopen('./edifact/'.$file_name_exp, 'w');
	  fwrite($fp_exp_fcl, "UNB+UNOA:1+IDTER3+MAEU+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
	  
	  $a = 1;
	  foreach($cont_export_fcl as $conts)
	  {
		$nocont = $conts['NO_CONTAINER'];
		$isocode = $conts['ISO_CODE'];
		$st = $conts['INDIKATOR'];
		$pol = $conts['POL'];
		$pod = $conts['POD'];
		$grt = $conts['BERAT'];
		$seal = $conts['SEAL_ID'];
		$gate_tgl = $conts['TGL_GATE'];
		$crr = $conts['CARRIER'];
		$bgm = $conts['BGM_STAT'];
		$eqd = $conts['EQD_STAT'];
		$bsl = $conts['BOOKING_SL'];
		$trck = $conts['NO_TRUCK'];
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
		
		fwrite($fp_exp_fcl, "UNH+".$a."+CODECO:D:95B:UN:ITG12'");
		fwrite($fp_exp_fcl, "BGM+".$bgm."+0000000000+9'");
		fwrite($fp_exp_fcl, "NAD+CF+".$crr.":160:166'");
		fwrite($fp_exp_fcl, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
		fwrite($fp_exp_fcl, "DTM+7:".$gate_tgl.":203'");
		fwrite($fp_exp_fcl, "LOC+165+".$pol."45:139:6'");
		fwrite($fp_exp_fcl, "MEA+AAE+G+KGM:".$grt."'");
		fwrite($fp_exp_fcl, "HAN+6'");		
		fwrite($fp_exp_fcl, "TDT+1++3++JKT888:172:87'");
		fwrite($fp_exp_fcl, "CNT+16:1'");
		fwrite($fp_exp_fcl, "UNT+10+".$a."'");		
		
		$a++;
	  }	  
	  
	  $jml_exp_fcl = $a-1;
	  fwrite($fp_exp_fcl, "UNZ+".$jml_exp_fcl."+".$dt_tgl2."1014'");
	  fclose($fp_exp_fcl);
	  
$insert_exp_fcl = "INSERT INTO TB_EDIFACT_EXP
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
				 'CODECO',
				 'E',
				 '$id_user',
				 '$file_name_exp',
				 '$counter_exp2',
				 'FCL',
				 'MSL'
				)";
$db->query($insert_exp_fcl);
}
//============================= CODECO EXPORT FCL ================================//
	  
	  
//============================= CODECO EXPORT MTY ================================//

$cek_count_mty2 = "SELECT MAX(COUNTER) AS MAX_EXP
					FROM TB_EDIFACT_EXP
                    WHERE NO_UKK = '$no_ukk'
						AND STAT_FILE = 'CODECO'
						AND E_I = 'E'
						AND STATUS_CONT = 'MTY'
						AND LINE_OPERATOR = 'MSL'";
	$counter_mty2 = $db->query($cek_count_mty2);
	$hasil_count_mty2 = $counter_mty2->fetchRow();
	
	if($hasil_count_mty2['MAX_EXP']=="")
	{
		$counter_exp_mty2 = 1;
	}
	else
	{
		$counter_exp_mty2 = $hasil_count_mty2['MAX_EXP']+1;
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
						TRIM(POD) AS POD,
						TRIM(POL) AS POL,
						BERAT,
						CASE WHEN TRIM(E_I)='E' THEN '34' 
						 ELSE '36' END BGM_STAT,
						CASE WHEN TRIM(E_I)='E' THEN '2' 
                         ELSE '3' END EQD_STAT,
						CASE WHEN TRIM(E_I)='E' THEN TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MI') 
						 ELSE TO_CHAR(TGL_GATE_OUT, 'YYYYMMDDHH24MI') END TGL_GATE
			FROM ISWS_LIST_CONTAINER 
			   WHERE NO_UKK = '$no_ukk'
					AND E_I = 'E'
					AND TRIM(STATUS) = 'MTY'
					AND TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MI') IS NOT NULL
					AND ROWNUM < 4
			ORDER BY  TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MISS') ASC
			)";
			
$result_jml4 = $db->query($jml_cont4);
$jml4 = $result_jml4->fetchRow();
$count4 = $jml4['JML'];

if($count4>0)	
{	
$cont_exp_mty = "SELECT NO_CONTAINER,
						ISO_CODE,
						STATUS,
						CASE WHEN TRIM(STATUS)='FCL' THEN '5' 
						 ELSE '4' END INDIKATOR,
						E_I,
						NVL(CARRIER,'MCC') AS CARRIER,
						NVL(BOOKING_SL,'Y') AS BOOKING_SL,
						NVL(SEAL_ID,'Y') AS SEAL_ID,
						TRIM(NVL(NO_TRUCK,'JKT888')) AS NO_TRUCK,
						TRIM(POD) AS POD,
						TRIM(POL) AS POL,
						BERAT,
						CASE WHEN TRIM(E_I)='E' THEN '34' 
						 ELSE '36' END BGM_STAT,
						CASE WHEN TRIM(E_I)='E' THEN '2' 
                         ELSE '3' END EQD_STAT,
						CASE WHEN TRIM(E_I)='E' THEN TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MI') 
						 ELSE TO_CHAR(TGL_GATE_OUT, 'YYYYMMDDHH24MI') END TGL_GATE
			FROM ISWS_LIST_CONTAINER 
			   WHERE NO_UKK = '$no_ukk'
					AND E_I = 'E'
					AND TRIM(STATUS) = 'MTY'
					AND TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MI') IS NOT NULL
					AND ROWNUM < 4
			ORDER BY  TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MISS') ASC";
$result_exp_mty = $db->query($cont_exp_mty);
$cont_exp_mty = $result_exp_mty->getAll();

$file_name_mty2 = "MSL_GIP_".$no_ukk."_".$counter_exp_mty2.".edi";

$fp_mty2 = fopen('./edifact/'.$file_name_mty2, 'w');
		  fwrite($fp_mty2, "UNB+UNOA:1+IDTER3+MAEU+".$dt2.":".$dt_tm."+".$dt_tgl2."1014'");
	  
	  $c = 1;
	  foreach($cont_exp_mty as $conts)
	  {
		$nocont = $conts['NO_CONTAINER'];
		$isocode = $conts['ISO_CODE'];
		$st = $conts['INDIKATOR'];
		$pol = $conts['POL'];
		$pod = $conts['POD'];
		$grt = $conts['BERAT'];
		$seal = $conts['SEAL_ID'];
		$gate_tgl = $conts['TGL_GATE'];
		$crr = $conts['CARRIER'];
		$bgm = $conts['BGM_STAT'];
		$eqd = $conts['EQD_STAT'];
		$bsl = $conts['BOOKING_SL'];
		$trck = TRIM($conts['NO_TRUCK']);
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
		
		fwrite($fp_mty2, "UNH+".$c."+CODECO:D:95B:UN:ITG12'");
		fwrite($fp_mty2, "BGM+".$bgm."+0000000000+9'");
		fwrite($fp_mty2, "NAD+CF+".$crr.":160:166'");
		fwrite($fp_mty2, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
		fwrite($fp_mty2, "DTM+7:".$gate_tgl.":203'");
		fwrite($fp_mty2, "LOC+165+".$pol."45:139:6'");
		fwrite($fp_mty2, "MEA+AAE+G+KGM:".$grt."'");
		fwrite($fp_mty2, "HAN+6'");		
		fwrite($fp_mty2, "TDT+1++3++JKT888:172:87'");
		fwrite($fp_mty2, "CNT+16:1'");
		fwrite($fp_mty2, "UNT+10+".$c."'");		
		
		$c++;
	  }	  
	  
	  $jml_mty2 = $c-1;
	  fwrite($fp_mty2, "UNZ+".$jml_mty2."+".$dt_tgl2."1014'");
	  fclose($fp_mty2);
	  
$insert_exp_mty2 = "INSERT INTO TB_EDIFACT_EXP
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
				 'CODECO',
				 'E',
				 '$id_user',
				 '$file_name_mty2',
				 '$counter_exp_mty2',
				 'MTY',
				 'MSL'
				)";
$db->query($insert_exp_mty2);
}
//============================= CODECO EXPORT MTY ================================//

//============================= SEND EMAIL===========================//
	  
echo "sukses";	  

?>




















