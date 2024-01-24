<?php
/**prepare gagat feb 2020 **/
	class esbclass {

       function nbsOPUSArInvoice($rs,$beamaterai,$rowdetail,$rsamount){
           
            /**PTKM02 RBM Piutang **/ 
			if($rs['KD_MODUL']=='PTKM02' || $rs['KD_MODUL']=='PTKM06' ){
				$status_lunas="";
				$tgl_pelunasan=NULL;	
			}else{
				$status_lunas="Y";
				$tgl_pelunasan=$rs['TGL_PELUNASAN'];				
			}	
    
           
		   
            $dataheader = array(
                "billerRequestId" => $rs['NO_REQUEST'],
                "orgId" => $rs['ORG_ID'],
                "trxNumber" => $rs['NO_NOTA'],
                "trxNumberOrig" => "",
                "trxNumberPrev" => "",
                "trxTaxNumber" => "",
                "trxDate" => date('Y-m-d H:i:s'),//date("Y-m-d", strtotime($rs['TRX_DATE']) ),
                "trxClass" => "INV",
                "trxTypeId" => "-1",
                "paymentReferenceNumber" => "",
                "referenceNumber" => " ",
                "currencyCode" => $rs['SIGN_CURRENCY'],
                "currencyType" => "",
                "currencyRate" => null,
                "currencyDate" => null,
                "amount" => strval($rs['KREDIT']),
                "customerNumber" => $rs['CUST_NO'],
                "customerClass" => "",
                "billToCustomerId" => "",
                "billToSiteUseId" => "",
                "termId" => null,
                "status" => "P",
                "headerContext" => $rs['JENIS_MODUL'],
                "headerSubContext" => $rs['KD_MODUL'],
                "startDate" => null,
                "endDate" => null,
                "terminal" => "-",
                "vesselName" => $rs['VESSEL'],
                "branchCode" => 'PTK',///$rs['BRANCH_CODE'],
                "errorMessage" => "",
                "apiMessage" => "",
                "createdBy" => "-1",
                "creationDate" => date('Y-m-d H:i:s'),
                "lastUpdatedBy" => "-1",
                "lastUpdateDate" => date('Y-m-d H:i:s'),
                "lastUpdateLogin" => "-1",
                "customerTrxIdOut" => null,
                "processFlag" => "",
                "attribute1" => $rs['KD_MODUL'],
                "attribute2" => $rs['CUST_NO'],
                "attribute3" => substr($rs['CUST_NAME'],0,30),
                "attribute4" => strval(substr($rs['CUST_ADDR'],0,30)),
                "attribute5" => $rs['CUST_NPWP'],
                "attribute6" => $attribute6,
                "attribute7" => $rs['KEGIATAN'],
                "attribute8" => "",
                "attribute9" => "",
                "attribute10" => "",
                "attribute11" => "",
                "attribute12" => "",
                "attribute13" => "",
                "attribute14" => $rs['NO_FAKTUR_PAJAK'],
                "attribute15" => "",
                "interfaceHeaderAttribute1" => $rs['NO_DO'],
                "interfaceHeaderAttribute2" => $rs['NOMOR_BL_PEB'],
                "interfaceHeaderAttribute3" => $rs['BONGKAR_MUAT'],
                "interfaceHeaderAttribute4" => $rs['VESSEL'],
                "interfaceHeaderAttribute5" => $rs['VOYAGE_IN_OUT'],//voyage in out 
                "interfaceHeaderAttribute6" => $rs['DAYA'],
                "interfaceHeaderAttribute7" => $rs['LUAS_TANAH'],
                "interfaceHeaderAttribute8" => $rs['LUAS_BANGUNAN'],
                "interfaceHeaderAttribute9" => strval($interface_header_attribute9),
                "interfaceHeaderAttribute10" => $rs['LOKASI_KEGIATAN'],
                "interfaceHeaderAttribute11" => $rs['NO_KONTRAK'],
                "interfaceHeaderAttribute12" => $rs['CUSTOMER_NUMBER'],
                "interfaceHeaderAttribute13" => "",
                "interfaceHeaderAttribute14" => "",
                "interfaceHeaderAttribute15" => "",
                "customerAddress" => $rs['CUST_ADDR'],
                "customerName" => $rs['CUST_NAME'],
                "sourceSystem" => "NBSOPUSPNK",
                "arStatus" => "N",
                "sourceInvoice" => "NBS_OPUS",
                "arMessage" => "",
                "customerNPWP" => $rs['CUST_NPWP'],
                "perKunjunganFrom" =>$rs['TANGGAL_TIBA'],
                "perKunjunganTo" =>$rs['TANGGAL_BERANGKAT'],
                "jenisPerdagangan" => "",
                "docNum" => "",
                "statusLunas" => $status_lunas,
                "tglPelunasan" =>$tgl_pelunasan,
                "amountTerbilang" => "",
                "ppnDipungutSendiri" =>$rs['PPN_DIPUNGUT_SENDIRI'],/**modif by gagat 21 sep 2020 */
                "ppnDipungutPemungut" =>$rs['PPN_DIPUNGUT_PEMUNGUT'],/**modif by gagat 21 sep 2020 */
                "ppnTidakDipungut" => $rs['PPN_TIDAK_DIPUNGUT'],/*modif by gagat 21 sep 2020*/
                "ppnDibebaskan" => $rs['PPN_DIBEBASKAN'],/*modif by gagat 21 sep 2020*/
                "uangJaminan" => "",
                "piutang" => strval($rs['KREDIT']),
                "sourceInvoiceType" => "NBS",
                "branchAccount" =>$rs['KD_CABANG_SIMKEU'],
                "statusCetak" => "",
                "statusKirimEmail" => "",
                "amountDasarPenghasilan" =>$rs['TOTAL'],
                "amountMaterai" =>$beamaterai,
                "ppn10Persen" => $rs['PPN'],
                "statusKoreksi" => "",
                "tanggalKoreksi" => null,
                "keteranganKoreksi" => ""
            );
			//var_dump($dataheader);
            $datalines = array();

            foreach ($rowdetail as $item_detail) {
				if($rs['JENIS_MODUL']=='PTKM'){                              
                        $interface_line_attribute1="";
                        $interface_line_attribute2=$item_detail['EI'];//E/I
                        $interface_line_attribute3=$item_detail['OI'];//O/I
                        $interface_line_attribute4=$item_detail['CRANE'];//Crane
                        $interface_line_attribute5=$item_detail['SHIFT'];//Shift By
                        $interface_line_attribute6=$item_detail['SIZE_TYPE_STAT_HAZ'];//Size Type Stat Haz
                        $interface_line_attribute7=$item_detail['QTY'];//boxes
                        $interface_line_attribute8=$item_detail['TARIF'];///tarif
                        $interface_line_attribute9=$item_detail['TOTHARI'];//Hari
                        $interface_line_attribute10="";
                        $interface_line_attribute11="";
                        $interface_line_attribute12="";
				}elseif($rs['JENIS_MODUL']=='UST'){
						$interface_line_attribute1="";
                        $interface_line_attribute2=$item_detail['START_DATE'];//tgl masuk
                        $interface_line_attribute3=$item_detail['END_DATE'];//tgl keluar
                        $interface_line_attribute4=$item_detail['CRANE'];//boxes
                        $interface_line_attribute5=$item_detail['SIZE_TYPE_STAT_HAZ'];//SIZE_TYPE_STAT_HAZ
                        $interface_line_attribute6=$item_detail['HARI'];//hari
                        $interface_line_attribute7=$item_detail['TARIF'];//TARIF
                        $interface_line_attribute8=$item_detail['DISCOUNT'];///diskon
                        $interface_line_attribute9=$item_detail['HARI'];//Hari
                        $interface_line_attribute10="";
                        $interface_line_attribute11="";
                        $interface_line_attribute12="";
						$interface_line_attribute13=$item_detail['CRANE'];//hz
				}		
                    
                
                $datalines[] = array(
                    "billerRequestId" =>  $item_detail['KD_PERMINTAAN'],
                    "trxNumber" =>  $item_detail['KD_UPER'],
                    "lineId" => null,
                    "lineNumber" => $item_detail['LINE_NUMBER'],
                    "description" =>$item_detail['URAIAN'],
                    "memoLineId" => "-1",
                    "glRevId" => null,
                    "lineContext" => $item_detail['KD_MODUL'],
                    "taxFlag" => $item_detail['TAX_FLAG'],
                    "serviceType" => $item_detail['TIPE_LAYANAN'],
                    "eamCode" => "",
                    "locationTerminal" => "",
                    "amount" =>$item_detail['TOTTARIF'],
                    "taxAmount" => $item_detail['TAX_AMOUNT'],
                    "startDate" =>$item_detail['TGL_AWAL'],
                    "endDate" =>$item_detail['TGL_AKHIR'],
                    "createdBy" => "-1",
                    "creationDate" =>date('Y-m-d'),
                    "lastUpdatedBy" => "-1",
                    "lastUpdatedDate" => date('Y-m-d'),
                    "interfaceLineAttribute1" => strval($interface_line_attribute1),
                    "interfaceLineAttribute2" => strval($interface_line_attribute2),
                    "interfaceLineAttribute3" => strval($interface_line_attribute3),
                    "interfaceLineAttribute4" => strval($interface_line_attribute4),/// TTH ?
                    "interfaceLineAttribute5" => strval($interface_line_attribute5),
                    "interfaceLineAttribute6" => strval($interface_line_attribute6),
                    "interfaceLineAttribute7" => strval($interface_line_attribute7),
                    "interfaceLineAttribute8" => strval($interface_line_attribute8),
                    "interfaceLineAttribute9" => strval($interface_line_attribute9),
                    "interfaceLineAttribute10" => strval($interface_line_attribute10),
                    "interfaceLineAttribute11" => strval($interface_line_attribute11),
                    "interfaceLineAttribute12" => strval($interface_line_attribute12),
                    "interfaceLineAttribute13" => "",
                    "interfaceLineAttribute14" => "",
                    "interfaceLineAttribute15" => "",
                    "lineDoc" => ""
                );
								
            }
			
            $esbdatarest = $this->esbrestformat($dataheader,$datalines);
            $response = $this->kirimEsb($esbdatarest,'putInvoice');
			
            return $response;

       }
	   
	   
	   function NBSOpusReceipt($row){
            /**
             * Populate Data untuk esbrest data header
             */
			///print_r("testing NBS OPUS Receipt ".$row['NO_NOTA']);
            date_default_timezone_set('Asia/Jakarta');

            $dataheader = array(
                "orgId" => $row['ORG_ID'],
                "receiptNumber" => $row['NO_NOTA'],
                "receiptMethod" => $row['RECEIPT_METHOD'],
                "receiptAccount" => $row['RECEIPT_ACCOUNT'],
                "bankId" => $row['BANK_ACCOUNT_ID'],
                "customerNumber" => $row['CUST_NO'],
                "receiptDate" => date('Y-m-d H:i:s'),
                "currencyCode" => $row['SIGN_CURRENCY'],
                "status" => "P",
                "amount" => $row['KREDIT'],
                "processFlag" => "N",
                "errorMessage" => "",
                "apiMessage" => "",
                "attributeCategory" => "",
                "referenceNum" => "",
                "receiptType" => "",
                "receiptSubType" => "",
                "createdBy" => "-1",
                "creationDate" => date('Y-m-d H:i:s'),
                "terminal" => "",
                "attribute1" => $row['NO_NOTA'],
                "attribute2" => "",
                "attribute3" => "",
                "attribute4" => "",
                "attribute5" => "",
                "attribute6" => "",
                "attribute7" => "",
                "attribute8" => "",
                "attribute9" => "",
                "attribute10" => "",
                "attribute11" => $row['NO_NOTA'],
                "attribute12" => "",
                "attribute13" => "",
                "attribute14" => $row['JENIS_NOTA'],
                "attribute15" => "",
                "statusReceipt" => "N",
                "sourceInvoice" => "NBSOPUSPNK",
                "statusReceiptMsg" => "",
                "invoiceNum" => "",
                "amountOrig" => null,
                "lastUpdateDate" => date('Y-m-d H:i:s'),
                "lastUpdateBy" => "-1",
                "branchCode" => "PTK",
                "branchAccount" => $row['BRANCH_ACCOUNT'],
                "sourceInvoiceType" => "NBSPNK",//Perlu Konfirm
                "remarkToBankId" => "BANK_ACCOUNT_ID",
                "sourceSystem" => "NBSOPUSPNK",
                "comments" => "",
                "cmsYn" => "N",
                "tanggalTerima" => null,
                "norekKoran" => ""
            );

            $esbdatarestreceipt = $this->esbrestformatreceipt($dataheader);
            $response = $this->kirimEsb($esbdatarestreceipt,'putReceipt');
			////print_r($dataheader);
            return $response;        

			//var_dump($dataheader);
			//die();

        }
	   
	   function nbsOPUSApply($row,$user){
                     
            date_default_timezone_set('Asia/Jakarta');

            $dataheader = array(
                "paymentCode" => $row['NO_NOTA'],
                "trxNumber" => $row['NO_NOTA'],
                "orgId" => $row['ORG_ID'],
                "amountApplied" => $row['KREDIT'],
                "cashReceiptId" => null,
                "customerTrxId" => null,
                "paymentScheduleId" => null,
                "bankId" => $row['BANK_ACCOUNT_ID'],
                "receiptSource" => "CMS",//perlu konfrim
                "legacySystem" => "INVOICE",
                "statusTransfer" => "N",
                "errorMessage" => null,
                "requestIdApply" => null,
                "createdBy" => "-1",//$user,
                "creationDate" => date('Y-m-d'),
                "lastUpdateBy" => "-1",//$user,
                "lastUpdateDate" => date('Y-m-d'),
                "amountPaid" => $row['KREDIT'],
                "epay" => "N",
                
            );

            $esbdatarestapply = $this->esbrestformatreceipt($dataheader);
            $response = $this->kirimEsb($esbdatarestapply,"putApply");

            return $response;            

        }

        function esbrestformat($dataheader,$datalines){
            //milisecond    
            $micro_date = microtime();
            $date_array = explode(" ",$micro_date);
            $date = date("Y-m-d H:i:s",$date_array[1]);
            $date_now =  $date . $date_array[0];

            $header = $dataheader;
            $lines = $datalines;
            
            $esbBody = array();
            $esbBody[] = array("header" =>  $header, "lines" => $lines);

            $esbHeader = array(
                "internalId" => "",
                "externalId" => "",
                "timestamp" => $date_now,
                "responseTimestamp" => "",
                "responseCode" => "",
                "responseMessage" => ""
            );

            $esbSecurity = array(
                "orgId" => $dataheader['orgId'],
                "batchSourceId" => "",
                "lastUpdateLogin" => "",
                "userId" => "",
                "respId" => "",
                "ledgerId" => "",
                "respApplId" => "",
                "batchSourceName" => ""
            );

            $arRequestDoc = array("esbHeader" => $esbHeader, "esbBody" => $esbBody, "esbSecurity" => $esbSecurity);
            $data = array("arRequestDoc" => $arRequestDoc);
            
            $jsondata = json_encode($data);

            return $jsondata;

        }
		
		 function esbrestformatreceipt($dataheader){
            
            //milisecond    
            $micro_date = microtime();
            $date_array = explode(" ",$micro_date);
            $date = date("Y-m-d H:i:s",$date_array[1]);
            $date_now =  $date . $date_array[0];

            $header = $dataheader;
            
            $esbBody = array();
            $esbBody[] = array("header" =>  $header);

            $esbHeader = array(
                "internalId" => "",
                "externalId" => "",
                "timestamp" => $date_now,
                "responseTimestamp" => "",
                "responseCode" => "",
                "responseMessage" => ""
            );

            $esbSecurity = array(
                "orgId" => $dataheader['orgId'],
                "batchSourceId" => "",
                "lastUpdateLogin" => "",
                "userId" => "",
                "respId" => "",
                "ledgerId" => "",
                "respApplId" => "",
                "batchSourceName" => ""
            );

            $arRequestDoc = array("esbHeader" => $esbHeader, "esbBody" => $esbBody, "esbSecurity" => $esbSecurity);
            $data = array("arRequestDoc" => $arRequestDoc);
            
            $jsondata = json_encode($data);

            return $jsondata;

        }
		
		function esbGetMateraiformat($kd_cabang,$jenis_nota,$amount)
        {
            
            //milisecond    
            $micro_date = microtime();
            $date_array = explode(" ",$micro_date);
            $date = date("Y-m-d H:i:s",$date_array[1]);
            $date_now =  $date . $date_array[0];
			$amount = strval($amount);
    
            
            $esbBody = array("pCabang" =>  $kd_cabang, "pTipeLayanan" =>  $jenis_nota, "pAmount" =>  $amount );
    
            $esbHeader = array(
                "externalId" => "23948273",
                "timestamp" => $date_now,
            );
    
            $arRequestDoc = array("esbHeader" => $esbHeader, "esbBody" => $esbBody);
            $data = array("getAmountMateraiRequest" => $arRequestDoc);
            
            $jsondata = json_encode($data);
            $response = $this->esbGetMaterai($jsondata);
            return $response;
			
        }

        function esbGetMaterai($data)
        {
    
            $username = ESB_USERNAME;
            $password = ESB_PASSWORD;
    
            //$curl = curl_init(ESB_URL_MATERAI);
			$curl = curl_init();
			
            curl_setopt_array($curl, array(
               ///CURLOPT_URL => ESB_URL_MATERAI,
				///  CURLOPT_URL => "http://10.88.48.57:5555/restv2/inquiryData/materai/",
                CURLOPT_URL => ESB_URL.'inquiryData/materai/',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "$data",
                CURLOPT_HTTPAUTH => CURLAUTH_ANY,
                CURLOPT_USERPWD => "$username:$password",
                CURLOPT_HTTPHEADER => array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json"
                    ),
            ));
    
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            
            if ($err) {
                $responesnya = "cURL Error #:" . $err;
            } else {
                $responesnya = $response;
            }
           
			
            return $responesnya;
        }

        function kirimEsb($data,$url){
        
            $username = ESB_USERNAME;
            $password = ESB_PASSWORD;
    
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                ///CURLOPT_URL => ESB_URL.$url,
				CURLOPT_URL => ESB_URL.'accountReceivable/'.$url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => "$data",
                CURLOPT_HTTPAUTH => CURLAUTH_ANY,
                CURLOPT_USERPWD => "$username:$password",
                CURLOPT_HTTPHEADER => array(
                    "Cache-Control: no-cache",
                    "Content-Type: application/json"
                    ),
            ));
    
            $response = curl_exec($curl);
            $err = curl_error($curl);
    
            curl_close($curl);
    
            if ($err) {
                $responesnya = "cURL Error #:" . $err;
            } else {
                $responesnya = $response;
            }
    
            return $responesnya;
        }

    }
?>