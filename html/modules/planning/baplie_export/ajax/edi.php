<?php
$db = getDB('dbint');
$id_vsb_voyage = $_GET['id_vsb_voyage'];

//echo $id_vsb_voyage;
//die();

//======================== create header baplie ==============================//
		$queryhdr 	= "SELECT   
                                            ID_VSB_VOYAGE,
											VESSEL_CODE,
                                            VOYAGE_IN,
                                            VOYAGE_OUT,
                                            VESSEL,       
                                            TO_CHAR(TO_DATE (ETA, 'YYYYMMDDHH24MISS'), 'YYYYMMDD') ETA_DATE,
                                            TO_CHAR(TO_DATE (ETA, 'YYYYMMDDHH24MISS'),'HH24MISS') ETA_HR,
                                            BERTHING_TIME AS ETB,
                                            TO_CHAR(TO_DATE (ETD, 'YYYYMMDDHH24MISS'), 'YYYYMMDD') ETD_DATE,
                                            TO_CHAR(TO_DATE (ETD, 'YYYYMMDDHH24MISS'),'HH24MISS') ETD_HR
                             FROM 
                                            M_VSB_VOYAGE 
                             WHERE 
                                            TRIM(ID_VSB_VOYAGE) = UPPER('$id_vsb_voyage')";               
                
		$hdr 		= $db->query($queryhdr);
		$row_hdr	= $hdr->getAll();

		$idves = $row_hdr[0]['ID_VSB_VOYAGE'];
		$vessel_code = $row_hdr[0]['VESSEL_CODE']; 		
		$voyin = $row_hdr[0]['VOYAGE_IN'];
		$voyout = $row_hdr[0]['VOYAGE_OUT'];
		$vesnm = $row_hdr[0]['VESSEL'];
		$eta_date = $row_hdr[0]['ETA_DATE'];
		$eta_hr = $row_hdr[0]['ETA_HR'];
		$etb = $row_hdr[0]['ETB'];
		$etd_date = $row_hdr[0]['ETD_DATE'];
		$etd_hr = $row_hdr[0]['ETD_HR'];              

		$file_name = $vessel_code."-".date('ymdhis').".edi";		
		
		
		$fp = fopen('./edifact/'.$file_name, 'w');
		//$fp2 = fopen('./html/modules/planning/baplie_export/edifact/'.$file_name, 'w');		
				
			  fwrite($fp, "UNB+UNOA:1+IDTER2+EVG+".$eta_date.":".$eta_hr."+MAG13040911183+++++0'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "UNH+MAG13040911183+BAPLIE:1:911:UN:SMDG15'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "BGM++GNCBAPLIE15MAG+9'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "DTM+137:".$eta_date.$eta_hr.":201'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "TDT+20+".$voyout."++".$cs.":103::".$vesnm."++0:172:20'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "LOC+5+IDJKT:139:6'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "LOC+61+TWKHH:139:6'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "DTM+178:".$eta_date."0000:201'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "DTM+136:".$eta_date."0000:201'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "DTM+132:".$eta_date.":101'");
			  fwrite($fp, PHP_EOL);
			  fwrite($fp, "RFF+VON:".$voyout."'");
			  fwrite($fp, PHP_EOL);                        
        
		
		//======================== create detail baplie ==============================//
		$querydtl 	= "SELECT 
							  NO_CONTAINER,
							  ISO_CODE,
							  CASE WHEN STATUS='MTY' then '4'
								   WHEN STATUS = 'FCL' then '5'
                              END STATUS,
							  POD,
							  POL,
							  CARRIER,
							  WEIGHT,
							  HZ,
							  SEAL_ID,
							  LOKASI_BP,
							  TEMP,
							  IMO,
							  UN_NUMBER,
							  HANDLING_INST,
							  OVER_FRONT,
							  OVER_REAR,
							  OVER_LEFT,
							  OVER_RIGHT,
							  OVER_TOP,
							  FPOD          
					   FROM 
							  M_ETV_COLDLTCONT   
					   WHERE 
							  VESSEL = '$vesnm'
							  AND VOYAGE_IN = '$voyin'
							  AND VOYAGE_OUT = '$voyout'";	
		
		$dtl 		= $db->query($querydtl);
		$data_dtl	= $dtl->getAll();

		//print_r($data_dtl);
		//die();

		$n = 0;
		foreach ($data_dtl as $row_dtl)
		{
			$nocont = $row_dtl['NO_CONTAINER'];
			$isocode = $row_dtl['ISO_CODE'];
			$status = $row_dtl['STATUS'];
			$idpod = $row_dtl['POD'];
			$idpol = $row_dtl['POL'];
			$opr = $row_dtl['CARRIER'];
			$wgt = $row_dtl['WEIGHT'];
			$hz = $row_dtl['HZ'];
			$slnumb = $row_dtl['SEAL_ID'];
			$locbp = $row_dtl['LOKASI_BP'];
			$temp = $row_dtl['TEMP'];
			$imo = $row_dtl['IMO'];
			$un = $row_dtl['UN_NUMBER'];
			$hi = $row_dtl['HANDLING_INST'];
			$hgh = $row_dtl['HEIGHT'];
			$ot = $row_dtl['OVER_TOP'];
			$ol = $row_dtl['OVER_LEFT'];
			$or = $row_dtl['OVER_RIGHT'];			
			$of = $row_dtl['OVER_FRONT'];
			$ob = $row_dtl['OVER_REAR'];

			fwrite($fp, "LOC+147+".$locbp."::5'");
			fwrite($fp, PHP_EOL);
			
			if($hi<>'')
			{
				fwrite($fp, "FTX+HAN+++".$hi."'");
				fwrite($fp, PHP_EOL);
				$n=$n+1;
			}
			
			fwrite($fp, "MEA+WT++KGM:".$wgt."'");
			fwrite($fp, PHP_EOL);
			
			if($ty_cont=='RFR')
			{
				fwrite($fp, "TMP+2+".$temp."'");
				fwrite($fp, PHP_EOL);
				$n=$n+1;
			}
			
			//Over TOP/Height(9)
			if($ot)
			{
				fwrite($fp, "DIM+9+CM:::".$ot."'");
				fwrite($fp, PHP_EOL);
				$n=$n+1;
			}
			
			
			//Over Right (7)
			if($or)
			{
				fwrite($fp, "DIM+7+CM::".$or."'");
				fwrite($fp, PHP_EOL);
				$n=$n+1;
			}			
			
			//Over Left(8)
			if($ol)
			{
				fwrite($fp, "DIM+8+CM::".$ol."'");
				fwrite($fp, PHP_EOL);
				$n=$n+1;
			}
			
			//Over Front(5)
			if($of)
			{
				fwrite($fp, "DIM+5+CM:".$of."'");
				fwrite($fp, PHP_EOL);
				$n=$n+1;
			}

			//Over Front(5)
			if($ob)
			{
				fwrite($fp, "DIM+6+CM:".$ob."'");
				fwrite($fp, PHP_EOL);
				$n=$n+1;
			}
			
			
			fwrite($fp, "LOC+6+".$idpol."'");
			fwrite($fp, PHP_EOL);
			fwrite($fp, "LOC+12+".$idpod."'");
			fwrite($fp, PHP_EOL);
			fwrite($fp, "LOC+83+".$idpod."'");
			fwrite($fp, PHP_EOL);
			fwrite($fp, "RFF+BM:1'");
			fwrite($fp, PHP_EOL);
			fwrite($fp, "EQD+CN+".$nocont."+".$isocode."+++".$status."'");
			fwrite($fp, PHP_EOL);
			fwrite($fp, "NAD+CA+".$opr.":172:ZZZ'");
			fwrite($fp, PHP_EOL);
			
			if($hz=='Y')
			{
				fwrite($fp, "DGS+IMD+".$imo."+".$un."'");
				fwrite($fp, PHP_EOL);
				$n=$n+1;
			}
			
			$n=$n+8;

		}
		//======================== create detail baplie ==============================//
			
				
		//header('Location:'.'../planning.baplie_export');
		//die(); 
?>

<a href="../edifact/<?=$file_name;?>"><font color="red"><i><b><?=$file_name; ?></b></i></font></a>




