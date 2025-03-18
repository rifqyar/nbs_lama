<?php
$id_user = $_SESSION["ID_USER"];
$no_ukk	= $_POST['NO_UKK'];

$db = getDB();
//print_r($act);die;

/*
	UNB+UNOA:2+MAL+EVG+130408:0357+78335'
	
	UNH+78335+CODECO:D:95B:UN:ITG13'
	BGM+34+1+9'
	TDT+20+001W+1++EVG:172:166+++HSGG2:103::VIRA BHUM'
	DTM+178:201304081900:203'
	NAD+CA+EMC:160:166'
	EQD+CN+EMCU5205000+45R1:102:5++2+5'
	RFF+BN:080300090976'
	RFF+AAE:20130405040300205519'
	DTM+7:201304080253:203'
	LOC+165+IDJKT:139:6+JKT05:TER:ZZZ'
	LOC+11+TWKHH:139:6'
	LOC+83+IDJKT:139:6'
	MEA+AAE+G+KGM:33859'
	SEL+EMCBWE3782+CA'
	TDT+1++3++EMC:172+++4JJ:146'
	NAD+CF+EMC:172:20'
	CNT+16:3'
	UNT+18+78335'
	
	UNZ+1+78335'
*/
	
	$ves_voy = "SELECT NM_KAPAL,
					   VOYAGE_IN,
					   VOYAGE_OUT,
					   CALL_SIGN
					FROM RBM_H
                    WHERE TRIM(NO_UKK) = '$no_ukk'";
	$vvoy = $db->query($ves_voy);
	$hasil_vv = $vvoy->fetchRow();
	$vessel = $hasil_vv['NM_KAPAL'];
	$voyage_in = $hasil_vv['VOYAGE_IN'];
	$voyage_out = $hasil_vv['VOYAGE_OUT'];
    $cs = $hasil_vv['CALL_SIGN'];	

$dt_tgl = date('Ymd');
$dt_tgl2 = date('ymd');
$dt_tm = date('Hi');

//================================== EXPORT ==================================//
$jml_cont_exp ="SELECT COUNT(*) AS JML FROM
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
						NO_REQUEST,
						CASE WHEN TRIM(E_I)='E' THEN '34' 
						 ELSE '36' END BGM_STAT,
						CASE WHEN TRIM(E_I)='E' THEN '2' 
                         ELSE '3' END EQD_STAT,
						CASE WHEN TRIM(E_I)='E' THEN TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MI') 
						 ELSE TO_CHAR(TGL_GATE_OUT, 'YYYYMMDDHH24MI') END TGL_GATE
				FROM ISWS_LIST_CONTAINER 
				   WHERE NO_UKK = '$no_ukk'
					  AND E_I = 'E'
				ORDER BY  TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MISS') ASC
				)
				WHERE TGL_GATE IS NOT NULL";
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
						NVL(CARRIER,'EMC') AS CARRIER,
						NVL(BOOKING_SL,'Y') AS BOOKING_SL,
						NVL(SEAL_ID,'Y') AS SEAL_ID,
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
					  AND E_I = 'E'
				ORDER BY  TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MISS') ASC
				)
				WHERE TGL_GATE IS NOT NULL";
$result_cont = $db->query($cont_exp);
$cont_list = $result_cont->getAll();

$cek_count = "SELECT MAX(COUNTER) AS MAX_EXP
					FROM TB_EDIFACT_EXP
                    WHERE NO_UKK = '$no_ukk'
						AND STAT_FILE = 'CODECO'
						AND LINE_OPERATOR = 'EVG'
						AND E_I = 'E'";
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

$file_name = "EVG_CODECOEXP_".$no_ukk."_".$counter_exp.".txt";

$fp = fopen('./edifact/'.$file_name, 'w');
	  fwrite($fp, "UNB+UNOA:2+IDTER3+EVG+".$dt_tgl2.":".$dt_tm."+IDD000895'");
	  
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
		
		fwrite($fp, "UNH+IDD000895+CODECO:D:95B:UN:ITG13'");
		fwrite($fp, "BGM+".$bgm."+1+9'");
		fwrite($fp, "TDT+20+".$voy."+1++EVG:172:166+++".$cs.":146::".$vessel."'");
		fwrite($fp, "DTM+178:".$gate_tgl.":203'");
		fwrite($fp, "NAD+CA+".$crr.":160:166'");		
		fwrite($fp, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
		fwrite($fp, "RFF+BN:".$bsl."'");
		fwrite($fp, "DTM+7:".$gate_tgl.":203'");
		fwrite($fp, "LOC+165+IDJKT:139:6+JKT05:TER:ZZZ'");
		fwrite($fp, "LOC+11+".$pod.":139:6'");
		fwrite($fp, "LOC+83+".$pol.":139:6'");		
		fwrite($fp, "MEA+AAE+G+KGM:".$grt."'");
		
		if($st=='5')
		{
			fwrite($fp, "SEL+".$seal."+CA'");
			$jml_seg = "17";
		}
		else
		{
			$jml_seg = "16";
		}
		
		fwrite($fp, "TDT+1++3++EMC:172+++".$trck.":146'");
		fwrite($fp, "NAD+CF+EMC:172:20'");
		fwrite($fp, "CNT+16:3'");
		fwrite($fp, "UNT+".$jml_seg."+IDD000895'");		
		
		$n++;
	  }
	  
	  $jml = $n-1;
	  fwrite($fp, "UNZ+".$jml."+IDD000895'");
	  fclose($fp);
	  
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
				 'CODECO',
				 'E',
				 '$id_user',
				 '$file_name',
				 '$counter_exp',
				 'EVG'
				)";
$db->query($insert_exp);
}
//================================== EXPORT ==================================//

/*
UNB+UNOA:2+MAL+EVG+130410:1257+78481'
UNH+78481+CODECO:D:95B:UN:ITG13'
BGM+36+1+9'
TDT+20+008W+1++EVG:172:166+++3ENH8:103::LEO PERDANA'
DTM+178:201303221600:203'
NAD+CA+EMC:160:166'
EQD+CN+EISU1755887+4300:102:5++3+5'
RFF+BM:EGLV142351883926'
DTM+7:201304101208:203'
LOC+165+IDJKT:139:6+JKT05:TER:ZZZ'
MEA+AAE+G+KGM:8500'
SEL+EMCAMK7962+CA'
TDT+1++3++EMC:172+++4JJ:146'
CNT+16:3'
UNT+14+78481'
UNZ+1+78481'
*/

//================================== IMPORT ==================================//


$jml_cont_imp ="SELECT COUNT(*) AS JML FROM
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
						NO_REQUEST,
						CASE WHEN TRIM(E_I)='E' THEN '34' 
						 ELSE '36' END BGM_STAT,
						CASE WHEN TRIM(E_I)='E' THEN '2' 
                         ELSE '3' END EQD_STAT,
						CASE WHEN TRIM(E_I)='E' THEN TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MI') 
						 ELSE TO_CHAR(TGL_GATE_OUT, 'YYYYMMDDHH24MI') END TGL_GATE
				FROM ISWS_LIST_CONTAINER 
				   WHERE NO_UKK = '$no_ukk'
					  AND E_I = 'I'
				ORDER BY  TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MISS') ASC
				)
				WHERE TGL_GATE IS NOT NULL";
$result_jml_imp = $db->query($jml_cont_imp);
$jml_imp = $result_jml_imp->fetchRow();
$count_imp = $jml_imp['JML'];				

if($count_imp>0)
{
$cont_imp = "SELECT * FROM
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
						TRIM(NVL(NO_TRUCK,0)) AS NO_TRUCK,
						TRIM(POD) AS POD,
						TRIM(POL) AS POL,
						BERAT,
						NO_REQUEST,
						CASE WHEN TRIM(E_I)='E' THEN '34' 
						 ELSE '36' END BGM_STAT,
						CASE WHEN TRIM(E_I)='E' THEN '2' 
                         ELSE '3' END EQD_STAT,
						CASE WHEN TRIM(E_I)='E' THEN TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MI') 
						 ELSE TO_CHAR(TGL_GATE_OUT, 'YYYYMMDDHH24MI') END TGL_GATE
				FROM ISWS_LIST_CONTAINER 
				   WHERE NO_UKK = '$no_ukk'
					  AND E_I = 'I'
				ORDER BY  TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MISS') ASC
				)
				WHERE TGL_GATE IS NOT NULL";
$result_imp = $db->query($cont_imp);
$cont_list_imp = $result_imp->getAll();

$cek_count_imp = "SELECT MAX(COUNTER) AS MAX_EXP
					FROM TB_EDIFACT_EXP
                    WHERE NO_UKK = '$no_ukk'
						AND STAT_FILE = 'CODECO'
						AND LINE_OPERATOR = 'EVG'
						AND E_I = 'I'";
	$counter_imp = $db->query($cek_count_imp);
	$hasil_count_imp = $counter_imp->fetchRow();
	
	if($hasil_count_imp['MAX_EXP']=="")
	{
		$counter_imp = 1;
	}
	else
	{
		$counter_imp = $hasil_count_imp['MAX_EXP']+1;
	}	

$file_name_imp = "EVG_CODECOIMP_".$no_ukk."_".$counter_imp.".txt";

$fp_imp = fopen('./edifact/'.$file_name_imp, 'w');
	  fwrite($fp_imp, "UNB+UNOA:2+IDTER3+EVG+".$dt_tgl2.":".$dt_tm."+IDD000895'");
	  
	  $m = 1;
	  foreach($cont_list_imp as $conts)
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
		$req = $conts['NO_REQUEST'];
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
		
		fwrite($fp_imp, "UNH+IDD000895+CODECO:D:95B:UN:ITG13'");
		fwrite($fp_imp, "BGM+".$bgm."+1+9'");
		fwrite($fp_imp, "TDT+20+".$voy."+1++EVG:172:166+++".$cs.":146::".$vessel."'");
		fwrite($fp_imp, "DTM+178:".$gate_tgl.":203'");
		fwrite($fp_imp, "NAD+CA+EMC:160:166'");		
		fwrite($fp_imp, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
		fwrite($fp_imp, "RFF+BM:".$req."'");
		fwrite($fp_imp, "DTM+7:".$gate_tgl.":203'");
		fwrite($fp_imp, "LOC+165+IDJKT:139:6+JKT05:TER:ZZZ'");
		fwrite($fp_imp, "MEA+AAE+G+KGM:".$grt."'");
		
		if($st=='5')
		{
			fwrite($fp_imp, "SEL+".$seal."+CA'");
			$jml_seg = "14";
		}
		else
		{
			$jml_seg = "13";
		}
		
		fwrite($fp_imp, "TDT+1++3++EMC:172+++".$trck.":146'");
		fwrite($fp_imp, "CNT+16:3'");
		fwrite($fp_imp, "UNT+".$jml_seg."+IDD000895'");		
		
		$m++;
	  }
	  
	  $jml_imp = $m-1;
	  fwrite($fp_imp, "UNZ+".$jml_imp."+IDD000895'");
	  fclose($fp_imp);
	  
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
				 'CODECO',
				 'I',
				 '$id_user',
				 '$file_name_imp',
				 '$counter_imp',
				 'EVG'
				)";
$db->query($insert_imp);
}
//================================== IMPORT ==================================//
	  
echo "sukses,".$file_name.",".$file_name_imp;	  

?>




















