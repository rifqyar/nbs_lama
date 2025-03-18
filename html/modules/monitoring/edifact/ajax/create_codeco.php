<?php
$id_user = $_SESSION["ID_USER"];
$no_ukk	= $_POST['NO_UKK'];

$db = getDB();
//print_r($act);die;

	$cek_count = "SELECT MAX(COUNTER) AS MAX_EXP
					FROM TB_EDIFACT_EXP
                    WHERE NO_UKK = '$no_ukk'
						AND STAT_FILE = 'CODECO'";
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
	

$file_name = "CODECO_".$no_ukk."_".$counter_exp.".edi";

/*
	UNH+62269+CODECO:D:95B:UN:ITG13'
	BGM+34++9'
	TDT+20+055N+1++OOLV:172:166:OOCL+++A8BI5:146::MAGNAVIA'
	RFF+VON:055N'
	LOC+9+PHMNL:139:6'
	NAD+CF+OOL:160:166'
	EQD+CN+TGHU1643396+22G1:102:5++2+5'
	RFF+BN:3048413690'
	TMD+3'
	DTM+7:201104021259:203'
	LOC+9+PHMNL:139:6'
	LOC+11+HKHKG:139:6'
	LOC+165+PHMNL:139:6:MICT+MICT'
	MEA+AAE+G+KGM:21430'
	SEL+OOLAYJ9379+SH+1'
	SEL+PEZA03340727+CA+1'
	DAM+1+DEN:ZZZ::DENT+LEFT:ZZZ::LEFT+MIN:ZZZ'
	DAM+1+DEN:ZZZ::DENT+RIGH:ZZZ::RIGHT+MIN:ZZZ'
	TDT+1++3+31+T-ORQTR:172+++CXL590'
	CNT+16:1'
	UNT+000021+62269'
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

$cont_import = "SELECT NO_CONTAINER,
						ISO_CODE,
						STATUS,
						CASE WHEN TRIM(STATUS)='FCL' THEN '5' 
						 ELSE '4' END INDIKATOR,
						E_I,
						NVL(CARRIER,0) AS CARRIER,
						NVL(BOOKING_SL,0) AS BOOKING_SL,
						NVL(SEAL_ID,0) AS SEAL_ID,
						NVL(NO_TRUCK,0) AS NO_TRUCK,
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
			ORDER BY  TO_CHAR(TGL_GATE_IN, 'YYYYMMDDHH24MISS') ASC";
$result_cont = $db->query($cont_import);
$cont_list = $result_cont->getAll();

$fp = fopen('./edifact/'.$file_name, 'w');
	  fwrite($fp, "UNB+UNOA:2+ISWSTOS+OOCLIES+".$dt_tgl.":".$dt_tm."+163431'\n");
	  
	  $n = 0;
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
		
		fwrite($fp, "UNH+62269+CODECO:D:95B:UN:ITG13'\n");
		fwrite($fp, "BGM+".$bgm."++9'\n");
		fwrite($fp, "TDT+20+".$voy."+1++".$crr.":172:166:OOCL+++A8BI5:146::".$vessel."'\n");
		fwrite($fp, "RFF+VON:".$voy."'\n");
		fwrite($fp, "LOC+9+".$pol.":139:6'\n");
		fwrite($fp, "NAD+CF+".$crr.":160:166'\n");
		fwrite($fp, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'\n");
		fwrite($fp, "RFF+BN:".$bsl."'\n");
		fwrite($fp, "TMD+3'\n");
		fwrite($fp, "DTM+7:".$gate_tgl."'\n");
		fwrite($fp, "LOC+9+".$pol.":139:6'\n");
		fwrite($fp, "LOC+11+".$pod.":139:6'\n");
		fwrite($fp, "LOC+165+".$prt.":139:6:MICT+MICT'\n");
		fwrite($fp, "MEA+AAE+G+KGM:".$grt."'\n");
		
		if($st=='5')
		{
			fwrite($fp, "SEL+".$seal."+CA+1'\n");
		}
		
		fwrite($fp, "TDT+1++3+31+T-ORQTR:172+++".$trck."'\n");
		fwrite($fp, "CNT+16:1'\n");
		fwrite($fp, "UNT+000021+62269'\n");		
		
		$n++;
	  }	  
	  
	  fwrite($fp, "UNZ+".$n."+163431'");
	  fclose($fp);
	  
$insert_exp = "INSERT INTO TB_EDIFACT_EXP
				(NO_UKK,
				 STAT_FILE,
				 E_I,
				 USER_UPDATE,
				 FILE_NAME,
				 COUNTER
				 )
				VALUES
				('$no_ukk',
				 'CODECO',
				 '',
				 '$id_user',
				 '$file_name',
				 '$counter_exp'
				)";
$db->query($insert_exp);
	  
echo "sukses";	  

?>




















