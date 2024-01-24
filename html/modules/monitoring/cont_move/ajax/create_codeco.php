<?php
$id_user = $_SESSION["ID_USER"];
$no_ukk	= $_POST['NO_UKK'];
$act	= $_POST['ACT'];

if($act=='MUAT')
{
	$voy = $voyage_out;
	$ei = 'E';
}
else
{
	$voy = $voyage_in;
	$ei = 'I';
}

$db = getDB();
//print_r($act);die;

	$cek_count = "SELECT MAX(ID_EXP) AS MAX_EXP
					FROM TB_EDIFACT_EXP
                    WHERE NO_UKK = '$no_ukk'
						AND STAT_FILE = 'CODECO'
						AND E_I = '$ei'";
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
	

$file_name = "CODECO_".$act."_".$no_ukk."_".$counter_exp.".edi";

/*
	UNH+62269+CODECO:D:95B:UN:ITG13'
	BGM+34++9'
	TDT+20+055N+1++OOLV:172:166:OOCL+++A8BI5:146::MAGNAVIA'
	RFF+VON:055N'
	LOC+9+PHMNL:139:6'
	NAD+CF+OOL:160:166'
	EQD+CN+TGHU1643396+22G1:102:5++2+5'
	RFF+BN:3048413690'TMD+3'
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

$cont_import = "SELECT A.NO_CONTAINER,
					   A.ISO_CODE
				FROM ISWS_LIST_CONTAINER A, ISWS_LIST_CONT_HIST B 
				WHERE A.NO_UKK = '$no_ukk' 
					AND A.NO_UKK = B.NO_UKK
					AND A.NO_CONTAINER = B.NO_CONTAINER";
$result_cont = $db->query($cont_import);
$cont_list = $result_cont->getAll();

$fp = fopen('./edifact/'.$file_name, 'w');
	  fwrite($fp, "UNB+UNOA:2+ISWSTOS+OOCLIES+".$dt_tgl.":".$dt_tm."+163431'\n");
	  
	  $n = 1;
	  foreach($cont_list as $conts)
	  {
		$nocont = $conts['NO_CONTAINER'];
		$isocode = $conts['ISO_CODE'];
		
		fwrite($fp, "UNH+62269+CODECO:D:95B:UN:ITG13'\n");
		fwrite($fp, "BGM+34++9'\n");
		fwrite($fp, "TDT+20+".$voy."+1++OOLV:172:166:OOCL+++A8BI5:146::".$vessel."'\n");
		fwrite($fp, "RFF+VON:".$voy."'\n");
		fwrite($fp, "LOC+9+PHMNL:139:6'\n");
		fwrite($fp, "NAD+CF+OOL:160:166'\n");
		fwrite($fp, "EQD+CN+".$nocont."+".$isocode.":102:5++2+5'\n");
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
				 '$ei',
				 '$id_user',
				 '$file_name',
				 '$counter_exp'
				)";
$db->query($insert_exp);
	  
echo "sukses";	  

?>




















