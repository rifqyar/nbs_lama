<?php
    class esbclass {

        function usterAr($rs,$rowdetail){
            /**
             * Populate Data untuk esbrest data header
             */

            if ($rs['CURRENCY_CODE'] == 'USD'){
                $currency_type = "Corporate";
            } else {
                $currency_type = "";
            }

            if ($rs['TANGGAL_TIBA'] != null){
                $interfaceHeaderAttribute6 = date("Y-m-d", strtotime($rs['TANGGAL_TIBA']) );
            } else {
                $interfaceHeaderAttribute6 = "";
            }

            if ($rs['PENUMPUKAN_FROM'] != null){
                $interfaceHeaderAttribute7 = date("Y-m-d", strtotime($rs['PENUMPUKAN_FROM']) );
            } else {
                $interfaceHeaderAttribute7 = "";
            }
            
            if ($rs['PENUMPUKAN_TO'] != null){
                $interfaceHeaderAttribute8 = date("Y-m-d", strtotime($rs['PENUMPUKAN_TO']) );
            } else {
                $interfaceHeaderAttribute8 = "";
            }


            $dataheader = array(
                "billerRequestId" => $rs['TRX_NUMBER'],
                "orgId" => $rs['ORG_ID'],
                "trxNumber" => $rs['TRX_NUMBER'],
                "trxNumberOrig" => "",
                "trxNumberPrev" => $rs['NOTAPREV'],
                "trxTaxNumber" => "",
                "trxDate" => date("Y-m-d", strtotime($rs['TRX_DATE']) ),
                "trxClass" => "INV",
                "trxTypeId" => "-1",
                "paymentReferenceNumber" => "",
                "referenceNumber" => "",
                "currencyCode" => $rs['CURRENCY_CODE'],
                "currencyType" => $currency_type,
                "currencyRate" => null,
                "currencyDate" => null,
                "amount" => $rs['KREDIT'],
                "customerNumber" => $rs['CUSTOMER_NUMBER'],
                "customerClass" => "",
                "billToCustomerId" => "",
                "billToSiteUseId" => "",
                "termId" => null,
                "status" => "P",
                "headerContext" => $rs['JENIS_MODUL'],
                "headerSubContext" => $rs['JENIS_NOTA'],
                "startDate" => null,
                "endDate" => null,
                "terminal" => "",
                "vesselName" =>"",
                "branchCode" => $branch_code,
                "errorMessage" => "",
                "apiMessage" => "",
                "createdBy" => "-1",
                "creationDate" => date('Y-m-d'),
                "lastUpdatedBy" => "-1",
                "lastUpdateDate" => date('Y-m-d'),
                "lastUpdateLogin" => "-1",
                "customerTrxIdOut" => null,
                "processFlag" => "",
                "attribute1" => $rs['JENIS_NOTA'],
                "attribute2" => "",
                "attribute3" => "",
                "attribute4" => "",
                "attribute5" => "",
                "attribute6" => "",
                "attribute7" => "",
                "attribute8" => "",
                "attribute9" => "",
                "attribute10" => "",
                "attribute11" => "",
                "attribute12" => "",
                "attribute13" => "",
                "attribute14" => $rs['NO_FAKTUR_PAJAK'],
                "attribute15" => "",
                "interfaceHeaderAttribute1" => "",
                "interfaceHeaderAttribute2" => strval(substr($rs['NOMOR_BL_PEB'], 0, 29)),
                "interfaceHeaderAttribute3" => "",
                "interfaceHeaderAttribute4" => strval(substr($rs['NAMA_KAPAL'], 0, 29)),
                "interfaceHeaderAttribute5" => strval(substr($rs['VOY'], 0, 29)),
                "interfaceHeaderAttribute6" => $interfaceHeaderAttribute6,
                "interfaceHeaderAttribute7" => $interfaceHeaderAttribute7,
                "interfaceHeaderAttribute8" => $interfaceHeaderAttribute8,
                "interfaceHeaderAttribute9" => "",
                "interfaceHeaderAttribute10" => strval(substr($rs['NO_DO'], 0, 29)),
                "interfaceHeaderAttribute11" => "",
                "interfaceHeaderAttribute12" => "",
                "interfaceHeaderAttribute13" => "",
                "interfaceHeaderAttribute14" => "",
                "interfaceHeaderAttribute15" => "",
                "customerAddress" => "",
                "customerName" => "",
                "sourceSystem" => "USTERPONTIANAK",
                "arStatus" => "N",
                "sourceInvoice" => "UST",
                "arMessage" => "",
                "customerNPWP" => "",
                "perKunjunganFrom" =>null,
                "perKunjunganTo" =>null,
                "jenisPerdagangan" => "",
                "docNum" => "",
                "statusLunas" => "Y",
                "tglPelunasan" => null,
                "amountTerbilang" => "",
                "ppnDipungutSendiri" => "",
                "ppnDipungutPemungut" => "",
                "ppnTidakDipungut" => "",
                "ppnDibebaskan" => "",
                "uangJaminan" => "",
                "piutang" => null,
                "sourceInvoiceType" => "UST",
                "branchAccount" => $rs['BRANCH_ACCOUNT'],
                "statusCetak" => "",
                "statusKirimEmail" => "",
                "amountDasarPenghasilan" => null,
                "amountMaterai" => null,
                "ppn10Persen" => null,
                "statusKoreksi" => "",
                "tanggalKoreksi" => null,
                "keteranganKoreksi" => "",
                "comments" => "",
                "cmsYn" => "N",
                "tanggalTerima" => null,
                "norekKoran" => ""
            );

            /**
             * Populate Data untuk esbrest data lines
             */
            $datalines = array();
            foreach ($rowdetail as $item_detail) {
                $datalines[] = array(
                    "billerRequestId" =>  $item_detail['TRX_NUMBER'],
                    "trxNumber" => $item_detail['TRX_NUMBER'],
                    "lineId" => null,
                    "lineNumber" => $item_detail['LINE_NUMBER'],
                    "description" => $item_detail['LINE_DESCRIPTION'],
                    "memoLineId" => null,
                    "glRevId" => null,
                    "lineContext" => $rs['JENIS_NOTA'],
                    "taxFlag" => $item_detail['TAX_FLAG'],
                    "serviceType" => strtoupper($item_detail['TIPE_LAYANAN']),
                    "eamCode" => "",
                    "locationTerminal" => "",
                    "amount" => $item_detail['AMOUNT'],
                    "taxAmount" => strval(round($item_detail['AMOUNT'] * 0.1 ,2)),
                    "startDate" => null,
                    "endDate" => null,
                    "createdBy" => "-1",
                    "creationDate" => date('Y-m-d'),
                    "lastUpdatedBy" => "-1",
                    "lastUpdatedDate" => date('Y-m-d'),
                    "interfaceLineAttribute1" => $item_detail['TRX_NUMBER'],
                    "interfaceLineAttribute2" => $item_detail['EI'],
                    "interfaceLineAttribute3" => $item_detail['OI'],
                    "interfaceLineAttribute4" => $item_detail['CRANE'],
                    "interfaceLineAttribute5" => $item_detail['SHIFT_BY'],
                    "interfaceLineAttribute6" => $item_detail['SIZE_TYPE_STAT_HAZ'],
                    "interfaceLineAttribute7" => $item_detail['BOXES'],
                    "interfaceLineAttribute8" => "",
                    "interfaceLineAttribute9" => $item_detail['HARI'],
                    "interfaceLineAttribute10" => $item_detail['TON'],
                    "interfaceLineAttribute11" => $item_detail['M3'],
                    "interfaceLineAttribute12" => $item_detail['DISCOUNT'],
                    "interfaceLineAttribute13" => "",
                    "interfaceLineAttribute14" => "",
                    "interfaceLineAttribute15" => "",
                    "lineDoc" => ""
                );
            }
            
            $esbdatarest = $this->esbrestformat($dataheader,$datalines);
            $response = $this->kirimkonsolidasi($esbdatarest);

            return $response;

        }

        function usterReceipt($row){
            /**
             * Populate Data untuk esbrest data header
             */
            date_default_timezone_set('Asia/Jakarta');

            $dataheader = array(
                "orgId" => $row['ORG_ID'],
                "receiptNumber" => $row['TRX_NUMBER'],
                "receiptMethod" => $row['RECEIPT_METHOD'],
                "receiptAccount" => $row['RECEIPT_ACCOUNT'],
                "bankId" => $row['BANK_ID'],
                "customerNumber" => $row['CUSTOMER_NUMBER'],
                "receiptDate" => date("Y-m-d", strtotime($row['TGL_PELUNASAN']) ),
                "currencyCode" => $row['CURRENCY_CODE'],
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
                "creationDate" => date('Y-m-d'),
                "terminal" => "",
                "attribute1" => $row['TRX_NUMBER'],
                "attribute2" => "",
                "attribute3" => "",
                "attribute4" => "",
                "attribute5" => "",
                "attribute6" => "",
                "attribute7" => "",
                "attribute8" => "",
                "attribute9" => "",
                "attribute10" => "",
                "attribute11" => $row['TRX_NUMBER'],
                "attribute12" => "",
                "attribute13" => "",
                "attribute14" => $row['JENIS_NOTA'],
                "attribute15" => "",
                "statusReceipt" => "N",
                "sourceInvoice" => "UST",
                "statusReceiptMsg" => "",
                "invoiceNum" => "",
                "amountOrig" => null,
                "lastUpdateDate" => date('Y-m-d'),
                "lastUpdateBy" => "-1",
                "branchCode" => "PNK",
                "branchAccount" => $row['KD_CABANG_SIMKEU'],
                "sourceInvoiceType" => "USTERPNK",//Perlu Konfirm
                "remarkToBankId" => "",
                "sourceSystem" => "UST",
                "comments" => "",
                "cmsYn" => "N",
                "tanggalTerima" => null,
                "norekKoran" => " "
            );

            $esbdatarestreceipt = $this->esbrestformatreceipt($dataheader);
            $response = $this->kirimreceipt($esbdatarestreceipt);

            return $response;            

        }

        function usterApply($row,$user){
                     
            date_default_timezone_set('Asia/Jakarta');

            $dataheader = array(
                "paymentCode" => $row['TRX_NUMBER'],
                "trxNumber" => $row['TRX_NUMBER'],
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
            $response = $this->kirimapply($esbdatarestapply);

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

        function kirimkonsolidasi($data)
        {
            $username = 'Administrator';
            $password = 'manage';

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "http://10.88.56.40:5556/restv2/accountReceivable/putInvoice2",
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

        function kiriminvoice($data)
        {
            $username = 'Administrator';
            $password = 'manage';

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "http://10.88.56.40:5556/restv2/accountReceivable/createInvoice",
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

        function kirimreceipt($data)
        {
            $username = 'Administrator';
            $password = 'manage';

            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => "http://10.88.56.40:5556/restv2/accountReceivable/putReceipt2",
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

        function kirimapply($data)
        {
            $username = 'Administrator';
            $password = 'manage';

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => "http://10.88.56.40:5556/restv2/accountReceivable/putApply2",
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