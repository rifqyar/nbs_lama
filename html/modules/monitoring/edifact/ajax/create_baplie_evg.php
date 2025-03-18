<?php
$id_user = $_SESSION["ID_USER"];
$no_ukk	= $_POST['NO_UKK'];

$db = getDB();	

/*
UNB+UNOA:1+0+0+130409:1118+MAG13040911183+++++0'
UNH+MAG13040911183+BAPLIE:1:911:UN:SMDG15'
BGM++GNCBAPLIE15MAG+9'
DTM+137:1304091118:201'
TDT+20+0457-009W++A8BI5:103::MAGNAVIA++0:172:20'
LOC+5+IDJKT'
LOC+61+SGSIN'
DTM+178:1304090000:201'
DTM+136:1304090000:201'
DTM+132:130409:101'
RFF+VON:0'

==== STANDARD CONTAINER ===
LOC+147+0381184::5'
MEA+WT++KGM:16100'
LOC+6+HKHKG'
LOC+12+IDSRG'
LOC+83+IDSRG'
RFF+BM:1'
EQD+CN+EMCU 9751057+4510+++5'
NAD+CA+EMS:172:ZZZ'
==== STANDARD CONTAINER ===



UNT+5569+MAG13040911183'
UNZ+1+MAG13040911183'
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
$dt_tgl2 = date('ymd');
$dt_tm = date('Hi');

$cont_bpe = "SELECT A.NO_CONTAINER,
					A.ISO_CODE,
					NVL(A.LOKASI_BP,A.BAYPLAN) AS STOWAGE_CELL,
                    A.BERAT,
					NVL(TRIM(A.CARRIER),'EMC') AS CRR,
					TRIM(A.TYPE_) AS TIPE,
					TRIM(A.TEMP)||':CEL' AS TEMP,
					TRIM(A.POL) AS POL,
					TRIM(A.POD) AS POD,
					TRIM(A.IMO) AS IMO,
					A.HZ,
					A.HEIGHT,
					A.OVER_LENGTH,
					A.OVER_WIDTH,
					A.OVER_HEIGHT,
					A.HANDLING_INSTRUCTION AS HI,
					TRIM(A.UN_NUMBER) AS UN_NUMBER,
					CASE WHEN TRIM(A.STATUS)='FCL' THEN '5' 
					 ELSE '4' END ST_CONT
				FROM ISWS_LIST_CONTAINER A
				WHERE TRIM(A.NO_UKK) = '$no_ukk' 
					AND A.E_I = 'E'
			 UNION
			 SELECT A.NO_CONTAINER,
					A.ISO_CODE,
					NVL(A.BAYPLAN,A.LOKASI_BP) AS STOWAGE_CELL,
                    A.BERAT,
					TRIM(A.CARRIER) AS CRR,
					TRIM(A.TYPE_) AS TIPE,
					TRIM(A.TEMP) AS TEMP,
					TRIM(A.POL) AS POL,
					TRIM(A.POD) AS POD,
					TRIM(A.IMO) AS IMO,
					A.HZ,
					A.HEIGHT,
					A.OVER_LENGTH,
					A.OVER_WIDTH,
					A.OVER_HEIGHT,
					A.HANDLING_INSTRUCTION AS HI,
					TRIM(A.UN_NUMBER) AS UN_NUMBER,
					CASE WHEN TRIM(A.STATUS)='FCL' THEN '5' 
					 ELSE '4' END ST_CONT
				FROM ISWS_LIST_CONTAINER A
				WHERE TRIM(A.NO_UKK) = '$no_ukk' 
					AND A.E_I = 'I'
					AND TRIM(KODE_STATUS) = 'NA'";
$result_cont = $db->query($cont_bpe);
$cont_list = $result_cont->getAll();

$cek_count = "SELECT MAX(COUNTER) AS MAX_EXP
					FROM TB_EDIFACT_EXP
                    WHERE NO_UKK = '$no_ukk'
						AND STAT_FILE = 'BAPLIE'
						AND E_I = 'E'
						AND LINE_OPERATOR = 'EVG'";
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
	

$file_name = "BPE_".$no_ukk."_".$counter_exp.".txt";

$fp = fopen('./edifact/'.$file_name, 'w');
	  fwrite($fp, "UNB+UNOA:1+IDTER3+EVG+".$dt_tgl2.":".$dt_tm."+MAG13040911183+++++0'");
	  fwrite($fp, "UNH+MAG13040911183+BAPLIE:1:911:UN:SMDG15'");
	  
	  //---header---//
	  fwrite($fp, "BGM++GNCBAPLIE15MAG+9'");
	  fwrite($fp, "DTM+137:".$dt_tgl2.$dt_tm.":201'");	  
	  fwrite($fp, "TDT+20+".$voyage_out."++".$cs.":103::".$vessel."++0:172:20'");
	  fwrite($fp, "LOC+5+IDJKT:139:6'");	  
	  fwrite($fp, "LOC+61+TWKHH:139:6'");
	  fwrite($fp, "DTM+178:".$dt_tgl2."0000:201'");
	  fwrite($fp, "DTM+136:".$dt_tgl2."0000:201'");
	  fwrite($fp, "DTM+132:".$dt_tgl2.":101'");
	  fwrite($fp, "RFF+VON:".$voyage_out."'");
	  //---header---//
	  
	  $n = 0;
	  foreach($cont_list as $conts)
	  {
		$nocont = $conts['NO_CONTAINER'];
		$isocode = $conts['ISO_CODE'];
		$stw_cell = $conts['STOWAGE_CELL'];  
		$gross = $conts['BERAT'];
		$pol = $conts['POL'];
		$pod = $conts['POD'];  
		$st = $conts['ST_CONT'];
		$crr = $conts['CRR'];
		$ty_cont = $conts['TIPE'];
		$temp = $conts['TEMP'];
		$imo = $conts['IMO'];
		$un = $conts['UN_NUMBER'];
		$hz = $conts['HZ'];
		$hgh = $conts['HEIGHT'];
		$ol = $conts['OVER_LENGTH'];
		$ow = $conts['OVER_WIDTH'];
		$oh = $conts['OVER_HEIGHT'];
		$hi = $conts['HI'];
		
		fwrite($fp, "LOC+147+".$stw_cell."::5'");
		
		if($hi<>'')
		{			
			fwrite($fp, "FTX+HAN+++".$hi."'");
			$n=$n+1;
		}
		
		fwrite($fp, "MEA+WT++KGM:".$gross."'");
		
		if($ty_cont=='RFR')
		{			
			fwrite($fp, "TMP+2+".$temp."'");
			$n=$n+1;
		}
		
		if(TRIM($hgh)=='OOG')
		{			
			fwrite($fp, "DIM+9+CM:".$ol.":".$ow.":".$oh."'");
			$n=$n+1;
		}
		
		fwrite($fp, "LOC+6+".$pol."'");
		fwrite($fp, "LOC+12+".$pod."'");		
		fwrite($fp, "LOC+83+".$pod."'");
		fwrite($fp, "RFF+BM:1'");
		fwrite($fp, "EQD+CN+".$nocont."+".$isocode."+++".$st."'");
		fwrite($fp, "NAD+CA+".$crr.":172:ZZZ'");
		
		if($hz=='Y')
		{
			fwrite($fp, "DGS+IMD+".$imo."+".$un."'");
			$n=$n+1;
		}
		
		$n=$n+8;
	  }	  
	  
	  $jml_n = $n;
	  
	  fwrite($fp, "UNT+".$jml_n."+MAG13040911183'");
	  fwrite($fp, "UNZ+1+MAG13040911183'");
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
				 'BAPLIE',
				 'E',
				 '$id_user',
				 '$file_name',
				 '$counter_exp',
				 'EVG'
				)";
$db->query($insert_exp);
	  
echo "sukses";	  

?>




















