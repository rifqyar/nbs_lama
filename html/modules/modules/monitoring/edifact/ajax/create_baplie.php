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

	$cek_count = "SELECT MAX(COUNTER) AS MAX_EXP
					FROM TB_EDIFACT_EXP
                    WHERE NO_UKK = '$no_ukk'
						AND STAT_FILE = 'BAPLIE'
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
	

$file_name = "BPE_".$no_ukk."_".$counter_exp.".edi";

/*
UNB+UNOA:2+PSA+OCL+130131:1437+(EBP)001+++++OCL'
UNH+(EBP)0001+BAPLIE:D:95B:UN:SMDG20'

BGM++(EBP)001+5'
DTM+137:1301311437:201'
TDT+20+005E+++OCL:172:20+++5BSQ2:103::MEDFRISIA'
LOC+5+SGSIN:139:6'
LOC+61+IDJKT:139:6'
DTM+132:1302012300:201'
DTM+178:1301310915:201'
DTM+133:1301312300:201'
RFF+VON:005E'

LOC+147+0300710::5'
FTX+ZZZ++CF+OR'
MEA+WT++KGM:11300'
LOC+9+SGSIN'
LOC+11+IDMRK'
RFF+BM:1'
EQD+CN+OOLU8615328+45G1+++5'
NAD+CA+OR:172:ZZZ'

LOC+147+0300810::5'
FTX+ZZZ++CF+OR'
MEA+WT++KGM:10500'
LOC+9+SGSIN'
LOC+11+IDMRK'
RFF+BM:1'
EQD+CN+DFSU6444705+45G1+++5'
NAD+CA+OR:172:ZZZ'

UNT+4070+(EBP)0001'
UNZ+1+(EBP)001'
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

$cont_bpe = "SELECT A.NO_CONTAINER,
					A.ISO_CODE,
					A.LOKASI_BP,
                    A.BERAT,
					TRIM(A.POL) AS POL,
					TRIM(A.POD) AS POD,
					CASE WHEN TRIM(A.STATUS)='FCL' THEN '5' 
					 ELSE '4' END ST_CONT
				FROM ISWS_LIST_CONTAINER A
				WHERE TRIM(A.NO_UKK) = '$no_ukk' 
					AND A.E_I = 'E'";
$result_cont = $db->query($cont_bpe);
$cont_list = $result_cont->getAll();

$fp = fopen('./edifact/'.$file_name, 'w');
	  fwrite($fp, "UNB+UNOA:2+ISWSTOS+OOCLIES+".$dt_tgl.":".$dt_tm."+(EBP)001+++++OCL'\n");
	  fwrite($fp, "UNH+(EBP)0001+BAPLIE:D:95B:UN:SMDG20'\n");
	  //---header---//
	  fwrite($fp, "BGM++(EBP)001+5'\n");
	  fwrite($fp, "DTM+137:".$dt_tgl.$dt_tm.":201'\n");	  
	  fwrite($fp, "TDT+20+".$voyage_out."+++OCL:172:20+++5BSQ2:103::".$vessel."'\n");
	  fwrite($fp, "LOC+5+IDJKT:139:6'\n");	  
	  fwrite($fp, "LOC+61+SGSIN:139:6'\n");
	  fwrite($fp, "DTM+132:1302012300:201'\n");
	  fwrite($fp, "DTM+178:1301310915:201'\n");
	  fwrite($fp, "DTM+133:1301312300:201'\n");
	  fwrite($fp, "RFF+VON:".$voyage_out."'\n");
	  //---header---//
	  
	  $n = 0;
	  foreach($cont_list as $conts)
	  {
		$nocont = $conts['NO_CONTAINER'];
		$isocode = $conts['ISO_CODE'];
		$stw_cell = $conts['LOKASI_BP'];  
		$gross = $conts['BERAT'];
		$pol = $conts['POL'];
		$pod = $conts['POD'];  
		$st = $conts['ST_CONT'];
		
		fwrite($fp, "LOC+147+".$stw_cell."::5'\n");
		fwrite($fp, "FTX+ZZZ++CF+OR'\n"); 
		fwrite($fp, "MEA+WT++KGM:".$gross."'\n");
		fwrite($fp, "LOC+9+".$pol."'\n");
		fwrite($fp, "LOC+11+".$pod."'\n");
		fwrite($fp, "RFF+BM:1'\n");
		fwrite($fp, "EQD+CN+".$nocont."+".$isocode."+++".$st."'\n");
		fwrite($fp, "NAD+CA+OR:172:ZZZ'\n");
		
		$n++;
	  }	  
	  
	  $jml_n = $n*7;
	  
	  fwrite($fp, "UNT+".$jml_n."+(EBP)0001'\n");
	  fwrite($fp, "UNZ+".$n."+(EBP)0001'");
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
				 'BAPLIE',
				 '$ei',
				 '$id_user',
				 '$file_name',
				 '$counter_exp'
				)";
$db->query($insert_exp);
	  
echo "sukses";	  

?>




















