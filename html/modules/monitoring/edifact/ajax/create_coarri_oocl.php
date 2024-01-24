<?php
$id_user = $_SESSION["ID_USER"];
$no_ukk	= $_POST['NO_UKK'];

$db = getDB();
//print_r($act);die;

/*
	UNB+UNOA:2+ICTSI+OOCLIES+20110402:1234+163402'

	UNH+34396+COARRI:D:95B:UN:ITG10'
	BGM+98++9'
	TDT+20+129W+1++OOLV:172:166+++VREG2:146::OOCL TAICHUNG'
	LOC+11+HKHKG:139:6'
	LOC+9+PHMNL:139:6'
	DTM+132:201104020030:203'
	DTM+133:201104020900:203'
	NAD+CA+OOLV:160:166'
	NAD+CF+OOL:160:166'
	EQD+CN+OOLU7540179+42G1:102:5++3+5'
	RFF+ABT'
	TMD+3'
	DTM+203:201104020234:203'
	LOC+147+0320786:139:5'
	LOC+11+PHMNL:139:6'
	LOC+9+TWKHH:139:6'
	MEA+AAE+G+KGM:6200'
	DAM+1+DEN:ZZZ::DENT+LEFT:ZZZ::LEFT+MIN:ZZZ'
	DAM+1+DEN:ZZZ::DENT+RIGH:ZZZ::RIGHT+MIN:ZZZ'
	TDT+30++3+31'
	UNT+000021+34396'
	
	UNZ+364+163402'
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
                ORDER BY SEQ  ASC
                )
                WHERE DATE_CONFIRM IS NOT NULL
				  AND ROWNUM < 4";
$result_cont = $db->query($cont_import);
$cont_list = $result_cont->getAll();

$cek_count = "SELECT MAX(COUNTER) AS MAX_EXP
					FROM TB_EDIFACT_EXP
                    WHERE NO_UKK = '$no_ukk'
						AND STAT_FILE = 'COARRI'
						AND E_I = 'I'
						AND LINE_OPERATOR = 'OOCL'";
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

$file_name = "OOCL_COARRI_DISCH_".$no_ukk."_".$counter_exp.".edi";

$fp = fopen('./edifact/'.$file_name, 'w');
	  fwrite($fp, "UNB+UNOA:2+IDTER3+OOCLIES+".$dt_tgl.":".$dt_tm."+163431'");
	  
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
		
		fwrite($fp, "UNH+".$n."+COARRI:D:95B:UN:ITG10'");
		fwrite($fp, "BGM+".$bgm."++9'");
		fwrite($fp, "TDT+20+".$voy."+1++".$crr.":172:166:OOCL+++A8BI5:146::".$vessel."'");
		fwrite($fp, "LOC+11+".$pod.":139:6'");
		fwrite($fp, "LOC+9+".$pol.":139:6'");
		fwrite($fp, "NAD+CF+".$crr.":160:166'");
		fwrite($fp, "EQD+CN+".$nocont."+".$isocode.":102:5++".$eqd."+".$st."'");
		fwrite($fp, "RFF+ABT'");
		fwrite($fp, "TMD+3'");
		fwrite($fp, "DTM+7:".$confirm_tgl.":203'");
		fwrite($fp, "LOC+147+".$bay_pss.":139:5'");
		fwrite($fp, "LOC+9+".$pol.":139:6'");
		fwrite($fp, "LOC+11+".$pod.":139:6'");
		fwrite($fp, "MEA+AAE+G+KGM:".$grt."'");
		
		if($st=='5')
		{
			fwrite($fp, "SEL+".$seal."+CA+1'");
			$jml_seg = "18";
		}
		else
		{
			$jml_seg = "17";
		}
		
		fwrite($fp, "TDT+30++3+31'");
		fwrite($fp, "CNT+16:1'");
		fwrite($fp, "UNT+".$jml_seg."+".$n."'");		
		
		$n++;
	  }	  
	  
	  $jml = $n-1;
	  fwrite($fp, "UNZ+".$jml."+163431'");
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
				 'COARRI',
				 'I',
				 '$id_user',
				 '$file_name',
				 '$counter_exp',
				 'OOCL'
				)";
$db->query($insert_exp);
	  
echo "sukses";	  

?>




















