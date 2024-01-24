<?php
$id_user = $_SESSION["ID_USER"];
$no_ukk	= $_POST['NO_UKK'];

$db = getDB();
//print_r($act);die;

/*
	UNB+UNOA:2+JICT+EVG+130409:2204+67100913ECTA'
	UNH+67100913ECTA+COARRI:D:95B:UN'
	BGM+44:::DISCHARGE REPORT+130409220452+9'
	TDT+20+008E+1++EMI+++3ENH8:103::LEO PERDANA'
	LOC+11+IDJKT:139:6+JICT:TER:ZZZ'
	DTM+178:201304091500:203'
	DTM+133:201304100900:203'

	NAD+CA+EMI:160:166'
	EQD+CN+GLDU4117974+4300:102:5++3+5'
	DTM+203:201304092109:203'
	LOC+147+0100112::5'
	LOC+11+IDJKT:139:6'
	LOC+9+TWKSG:139:6'
	MEA+WT+G+KGM:24200'
	SEL+Y'
	
	NAD+CF+EMC:172'
	EQD+CN+YMLU2838640+2200:102:5++3+5'
	DTM+203:201304092110:203'
	LOC+147+0110282::5'
	LOC+11+IDJKT:139:6'
	LOC+9+TWKSG:139:6'
	MEA+WT+G+KGM:23700'
	SEL+Y'
	DGS+IMD++1593'	
	
	CNT+16:1'
	UNT+534+67100913ECTA'
	UNZ+1+67100913ECTA'
*/

	$ves_voy = "SELECT NM_KAPAL,
					   VOYAGE_IN,
					   VOYAGE_OUT,
					   CALL_SIGN
					FROM RBM_H
                    WHERE NO_UKK = '$no_ukk'";
	$vvoy = $db->query($ves_voy);
	$hasil_vv = $vvoy->fetchRow();
	$vessel = $hasil_vv['NM_KAPAL'];
	$voyage_in = $hasil_vv['VOYAGE_IN'];
	$voyage_out = $hasil_vv['VOYAGE_OUT'];
    $cs = $hasil_vv['CALL_SIGN'];	

$dt_tgl = date('Ymd');
$dt_tm = date('Hi');
$dt = date('YmdHi');
$dt_tgl2 = date('ymd');
$dt_tgl3 = date('ymdHis');

//================================= IMPORT ====================================//

$jml_cont_imp ="SELECT COUNT(*) AS JML FROM
                (
                SELECT NO_CONTAINER,
                            ISO_CODE,
                            STATUS,
                            CASE WHEN TRIM(STATUS)='FCL' THEN '5' 
                             ELSE '4' END INDIKATOR,
                            E_I,
                            NVL(CARRIER,0) AS CARRIER,
                            NVL(BOOKING_SL,'Y') AS BOOKING_SL,
                            NVL(SEAL_ID,'Y') AS SEAL_ID,
                            NVL(NO_TRUCK,0) AS NO_TRUCK,
                            NVL(ALAT,0) AS ALAT,
                            TRIM(POD) AS POD,
                            TRIM(POL) AS POL,
                            BERAT,
                            CASE WHEN TRIM(E_I)='E' THEN '46' 
                              ELSE '44' END BGM_STAT,
							CASE WHEN TRIM(E_I)='E' THEN '2' 
                              ELSE '3' END EQD_STAT,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MI') AS DATE_CONFIRM,
							TRIM(LOKASI_BP) AS BAY_PSS,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MISS') AS SEQ
                FROM ISWS_LIST_CONTAINER 
                   WHERE NO_UKK = '$no_ukk'
					  AND E_I = 'I'
                ORDER BY SEQ ASC
                )
                WHERE DATE_CONFIRM IS NOT NULL";
$result_jml_imp = $db->query($jml_cont_imp);
$jml_imp = $result_jml_imp->fetchRow();
$count_imp = $jml_imp['JML'];

if($count_imp>0)
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
                            NVL(BOOKING_SL,'Y') AS BOOKING_SL,
                            NVL(SEAL_ID,'Y') AS SEAL_ID,
                            NVL(NO_TRUCK,0) AS NO_TRUCK,
                            NVL(ALAT,0) AS ALAT,
                            TRIM(POD) AS POD,
                            TRIM(POL) AS POL,
                            BERAT,
                            CASE WHEN TRIM(E_I)='E' THEN '46' 
                              ELSE '44' END BGM_STAT,
							CASE WHEN TRIM(E_I)='E' THEN '2' 
                              ELSE '3' END EQD_STAT,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MI') AS DATE_CONFIRM,
							TRIM(LOKASI_BP) AS BAY_PSS,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MISS') AS SEQ
                FROM ISWS_LIST_CONTAINER 
                   WHERE NO_UKK = '$no_ukk'
					  AND E_I = 'I'
                ORDER BY SEQ ASC
                )
                WHERE DATE_CONFIRM IS NOT NULL";
$result_cont = $db->query($cont_import);
$cont_list = $result_cont->getAll();

$cek_count = "SELECT MAX(COUNTER) AS MAX_EXP
					FROM TB_EDIFACT_EXP
                    WHERE NO_UKK = '$no_ukk'
						AND STAT_FILE = 'COARRI'
						AND E_I = 'I'
						AND LINE_OPERATOR = 'EVG'";
	$counter = $db->query($cek_count);
	$hasil_count = $counter->fetchRow();
	
	if($hasil_count['MAX_EXP']=="")
	{
		$counter_imp = 1;
	}
	else
	{
		$counter_imp = $hasil_count['MAX_EXP']+1;
	}	

$file_name = "EVG_COARRIDISC_".$no_ukk."_".$counter_imp.".txt";

$fp = fopen('./edifact/'.$file_name, 'w');	  
	  fwrite($fp, "UNB+UNOA:2+IDTER3+EVG+".$dt_tgl2.":".$dt_tm."+IDD000895'");
	  fwrite($fp, "UNH+IDD000895+COARRI:D:95B:UN'");
	  fwrite($fp, "BGM+44:::DISCHARGE REPORT+".$dt_tgl3."+9'");
	  fwrite($fp, "TDT+20+".$voy."+1++EMI+++".$cs.":103::".$vessel."'");
	  fwrite($fp, "LOC+11+IDJKT:139:6+IDTER3:TER:ZZZ'");
	  fwrite($fp, "DTM+178:".$dt.":203'");
	  fwrite($fp, "DTM+133:".$dt.":203'");
	  
	  $m = 0;
	  $seg_imp = 0;
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
		$bay_pss = $conts['BAY_PSS'];
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
		
		fwrite($fp, "NAD+CA+".$crr.":160:166'");
		fwrite($fp, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
		fwrite($fp, "DTM+203:".$confirm_tgl.":203'");
		fwrite($fp, "LOC+147+".$bay_pss."::5'");
		fwrite($fp, "LOC+11+IDJKT:139:6'");
		fwrite($fp, "LOC+9+".$pol.":139:6'");		
		fwrite($fp, "MEA+WT+G+KGM:".$grt."'");
		
		if($st=='5')
		{
			fwrite($fp, "SEL+".$seal."'");
			$seg_imp = $seg_imp+8;
		}
		else
		{
			$seg_imp = $seg_imp+7;
		}
			
		$m++;
	  }	
	  
	  //$jml_y = array_sum($seg_imp);
	  $jml_imp = $seg_imp+8;
	  fwrite($fp, "CNT+16:1'");
	  fwrite($fp, "UNT+".$jml_imp."+IDD000895'");
	  fwrite($fp, "UNZ+1+IDD000895'");
	  fclose($fp);
	  
$insert_imp = "INSERT INTO TB_EDIFACT_EXP
				(NO_UKK,
				 STAT_FILE,
				 E_I,
				 USER_UPDATE,
				 FILE_NAME,
				 COUNTER,
				 LINE_OPERATOR
				 )
				VALUES
				('$no_ukk',
				 'COARRI',
				 'I',
				 '$id_user',
				 '$file_name',
				 '$counter_imp',
				 'EVG'
				)";
$db->query($insert_imp);
}
//================================= IMPORT ====================================//
	  

//================================= EXPORT ====================================//

$jml_cont_exp ="SELECT COUNT(*) AS JML FROM
                (
                SELECT NO_CONTAINER,
                            ISO_CODE,
                            STATUS,
                            CASE WHEN TRIM(STATUS)='FCL' THEN '5' 
                             ELSE '4' END INDIKATOR,
                            E_I,
							NVL(NO_REQUEST,'SP213012934') AS NO_REQUEST,
                            NVL(CARRIER,'EMI') AS CARRIER,
                            NVL(BOOKING_SL,'Y') AS BOOKING_SL,
                            NVL(SEAL_ID,'Y') AS SEAL_ID,
                            NVL(NO_TRUCK,0) AS NO_TRUCK,
                            NVL(ALAT,0) AS ALAT,
                            TRIM(POD) AS POD,
                            TRIM(POL) AS POL,
                            BERAT,							
                            CASE WHEN TRIM(E_I)='E' THEN '46' 
                              ELSE '44' END BGM_STAT,
							CASE WHEN TRIM(E_I)='E' THEN '2' 
                              ELSE '3' END EQD_STAT,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MI') AS DATE_CONFIRM,
							TRIM(LOKASI_BP) AS BAY_PSS,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MISS') AS SEQ
                FROM ISWS_LIST_CONTAINER 
                   WHERE NO_UKK = '$no_ukk'
					  AND E_I = 'E'
                ORDER BY SEQ ASC
                )
                WHERE DATE_CONFIRM IS NOT NULL";
$result_jml_exp = $db->query($jml_cont_exp);
$jml_exp = $result_jml_exp->fetchRow();
$count_exp = $jml_exp['JML'];	

if($count_exp>0)
{
$cont_exp = "SELECT * FROM
                (
                SELECT NO_CONTAINER,
                            ISO_CODE,
                            STATUS,
                            CASE WHEN TRIM(STATUS)='FCL' THEN '5' 
                             ELSE '4' END INDIKATOR,
                            E_I,
							NVL(NO_REQUEST,'SP213012934') AS NO_REQUEST,
                            NVL(CARRIER,'EMI') AS CARRIER,
                            NVL(BOOKING_SL,'Y') AS BOOKING_SL,
                            NVL(SEAL_ID,'Y') AS SEAL_ID,
                            NVL(NO_TRUCK,0) AS NO_TRUCK,
                            NVL(ALAT,0) AS ALAT,
                            TRIM(POD) AS POD,
                            TRIM(POL) AS POL,
                            BERAT,							
                            CASE WHEN TRIM(E_I)='E' THEN '46' 
                              ELSE '44' END BGM_STAT,
							CASE WHEN TRIM(E_I)='E' THEN '2' 
                              ELSE '3' END EQD_STAT,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MI') AS DATE_CONFIRM,
							TRIM(LOKASI_BP) AS BAY_PSS,
                            TO_CHAR(DATE_CONFIRM, 'YYYYMMDDHH24MISS') AS SEQ
                FROM ISWS_LIST_CONTAINER 
                   WHERE NO_UKK = '$no_ukk'
					  AND E_I = 'E'
                ORDER BY SEQ ASC
                )
                WHERE DATE_CONFIRM IS NOT NULL";
$result_cont_exp = $db->query($cont_exp);
$cont_list_exp = $result_cont_exp->getAll();

$cek_count_exp = "SELECT MAX(COUNTER) AS MAX_EXP
					FROM TB_EDIFACT_EXP
                    WHERE NO_UKK = '$no_ukk'
						AND STAT_FILE = 'COARRI'
						AND E_I = 'E'
						AND LINE_OPERATOR = 'EVG'";
	$counter_exp2 = $db->query($cek_count_exp);
	$hasil_count_exp = $counter_exp2->fetchRow();
	
	if($hasil_count_exp['MAX_EXP']=="")
	{
		$counter_exp = 1;
	}
	else
	{
		$counter_exp = $hasil_count_exp['MAX_EXP']+1;
	}	

$file_name_exp = "EVG_COARRILOAD_".$no_ukk."_".$counter_exp.".txt";

$fp_exp = fopen('./edifact/'.$file_name_exp, 'w');	  
	  fwrite($fp_exp, "UNB+UNOA:2+IDTER3+EVG+".$dt_tgl2.":".$dt_tm."+IDD000895'");
	  fwrite($fp_exp, "UNH+IDD000895+COARRI:D:95B:UN'");
	  fwrite($fp_exp, "BGM+44:::LOADING REPORT+".$dt_tgl3."+9'");
	  fwrite($fp_exp, "TDT+20+".$voy."+1++EMI+++".$cs.":103::".$vessel."'");
	  fwrite($fp_exp, "LOC+9+IDJKT:139:6+IDTER3:TER:ZZZ'");
	  fwrite($fp_exp, "DTM+178:".$dt.":203'");
	  fwrite($fp_exp, "DTM+133:".$dt.":203'");
	  
	  $n = 0;
	  $seg = 0;
	  foreach($cont_list_exp as $conts)
	  {
		$nocont = $conts['NO_CONTAINER'];
		$isocode = $conts['ISO_CODE'];
		$st = $conts['INDIKATOR'];
		$pol = $conts['POL'];
		$pod = $conts['POD'];
		$grt = $conts['BERAT'];
		$seal = $conts['SEAL_ID'];
		$confirm_tgl = $conts['DATE_CONFIRM'];
		$bay_pss = $conts['BAY_PSS'];
		$crr = $conts['CARRIER'];
		$bgm = $conts['BGM_STAT'];
		$eqd = $conts['EQD_STAT'];
		$bsl = $conts['BOOKING_SL'];
		$req = $conts['NO_REQUEST'];
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
			
		fwrite($fp_exp, "NAD+CA+".$crr.":160:166'");
		fwrite($fp_exp, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
		fwrite($fp_exp, "RFF+BN:".$bsl."'");
		fwrite($fp_exp, "RFF+BM:".$req."'");
		fwrite($fp_exp, "DTM+203:".$confirm_tgl.":203'");
		fwrite($fp_exp, "LOC+147+".$bay_pss."::5'");
		fwrite($fp_exp, "LOC+11+".$pod.":139:6'");
		fwrite($fp_exp, "LOC+9+IDJKT:139:6'");		
		fwrite($fp_exp, "MEA+WT+G+KGM:".$grt."'");
		
		if($st=='5')
		{
			fwrite($fp_exp, "SEL+".$seal."'");
			$seg = $seg+10;
		}
		else
		{
			$seg = $seg+9;
		}
			
		$n++;
	  }	
	  
	  //$jml_x = array_sum($seg)
	  $jml = $seg+8;
	  fwrite($fp_exp, "CNT+16:1'");
	  fwrite($fp_exp, "UNT+".$jml."+IDD000895'");
	  fwrite($fp_exp, "UNZ+1+IDD000895'");
	  fclose($fp_exp);
	  
$insert_exp = "INSERT INTO TB_EDIFACT_EXP
				(NO_UKK,
				 STAT_FILE,
				 E_I,
				 USER_UPDATE,
				 FILE_NAME,
				 COUNTER,
				 LINE_OPERATOR
				 )
				VALUES
				('$no_ukk',
				 'COARRI',
				 'E',
				 '$id_user',
				 '$file_name_exp',
				 '$counter_exp',
				 'EVG'
				)";
$db->query($insert_exp);
}
//================================= EXPORT ====================================//	  
	  
echo "sukses,".$file_name.",".$file_name_exp;	  

?>




















