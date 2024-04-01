<?php
error_reporting(0); //set off all error reporting

include('../../lib/xml2array.php');
require_once('../../lib/nusoap/nusoap.php');

/*-------------------------------------------------
| ePaymentInquiry
|--------------------------------------------------
| trx_number : String - NO NOTA
| type       : String
|
| Last Updated At : 26 Nov 2020 by ILCS Team
| -------------------------------------------------
| History
| -------------------------------------------------
| 1. Inisiasi awal - 03 April 2020
| 2. Update Procedure ITPK_NOTA_HEADER - 26 Nov 2020
| 3. Update host from DNS to IP - 14 Feb 2023
*/

function ePaymentInquiry($trx_number)
{
   // Connection to USTER
   $conn = oci_connect('uster', 'uster123', '10.15.42.43/datamti');

   // check connection if fails return error
   if(!$conn) {
    return "^87^ORA - Database problem, cant connect to database.";
   }

   // set variable jenis layanan
   $type = '';

   //query from nota_receiving
   if(empty($type)) {
     $query   = "SELECT * FROM NOTA_RECEIVING WHERE NO_NOTA_MTI='{$trx_number}'";
     $parse   = oci_parse($conn, $query);
     $execute = oci_execute($parse);

     if(!$execute) {
       // close connection
       oci_close($conn);
       return '^87^ORA - Table / View / Column not found';
     }

    $row  = oci_fetch_assoc($parse);

    if(!empty($row)) {
      $type = 'RECEIVING';
    }
   }
   //query from nota_stuffing
   if(empty($type)) {
     $query   = "SELECT * FROM NOTA_STUFFING WHERE NO_NOTA_MTI='{$trx_number}'";
     $parse   = oci_parse($conn, $query);
     $execute = oci_execute($parse);

     if(!$execute) {
       // close connection
       oci_close($conn);
       return '^87^ORA - Table / View / Column not found';
     }

     $row  = oci_fetch_assoc($parse);

     if(!empty($row)) {
       if($row['STATUS'] == 'PERP') {
         $type = 'PERP_PNK';
       } else {
         $type = 'STUFFING';
       }
     }
   }
   //query from nota_pnkn_stuffing / penumpukan stuffing
   if(empty($type)) {
     $query   = "SELECT * FROM NOTA_PNKN_STUF WHERE NO_NOTA_MTI='{$trx_number}'";
     $parse   = oci_parse($conn, $query);
     $execute = oci_execute($parse);

     if(!$execute) {
       // close connection
       oci_close($conn);
       return '^87^ORA - Table / View / Column not found';
     }

     $row  = oci_fetch_assoc($parse);

     if(!empty($row)) {
       $type = 'STUF_PNK';
     }
   }
   //query from nota_stripping
   if(empty($type)) {
     $query   = "SELECT * FROM NOTA_STRIPPING WHERE NO_NOTA_MTI='{$trx_number}'";
     $parse   = oci_parse($conn, $query);
     $execute = oci_execute($parse);

     if(!$execute) {
       // close connection
       oci_close($conn);
       return '^87^ORA - Table / View / Column not found';
     }

     $row  = oci_fetch_assoc($parse);

     if(!empty($row)) {
       if($row['STATUS'] == 'PERP') {
         $type = 'PERP_STRIP';
       } else {
         $type = 'STRIPPING';
       }
     }
   }
   //query from nota_delivery
   if(empty($type)) {
     $query   = "SELECT * FROM NOTA_DELIVERY WHERE NO_NOTA_MTI='{$trx_number}'";
     $parse   = oci_parse($conn, $query);
     $execute = oci_execute($parse);

     if(!$execute) {
       // close connection
       oci_close($conn);
       return '^87^ORA - Table / View / Column not found';
     }

     $row  = oci_fetch_assoc($parse);

     if(!empty($row)) {
       if($row['STATUS'] == 'PERP') {
         $type = 'PERP_DEV';
       } else {
         $type = 'DELIVERY';
       }
     }
   }
   //query from nota_pnkn_delivery / penumpukan delivery
   if(empty($type)) {
     $query   = "SELECT * FROM NOTA_PNKN_DEL WHERE NO_NOTA_MTI='{$trx_number}'";
     $parse   = oci_parse($conn, $query);
     $execute = oci_execute($parse);

     if(!$execute) {
       // close connection
       oci_close($conn);
       return '^87^ORA - Table / View / Column not found';
     }

     $row  = oci_fetch_assoc($parse);

     if(!empty($row)) {
       $type = 'DEL_PNK';
     }
   }
   //query from nota_batal_muat
   if(empty($type)) {
     $query   = "SELECT * FROM NOTA_BATAL_MUAT WHERE NO_NOTA_MTI='{$trx_number}'";
     $parse   = oci_parse($conn, $query);
     $execute = oci_execute($parse);

     if(!$execute) {
       // close connection
       oci_close($conn);
       return '^87^ORA - Table / View / Column not found';
     }

     $row  = oci_fetch_assoc($parse);

     if(!empty($row)) {
       $type = 'BATAL_MUAT';
     }
   }
   // query from nota relokasi mty
   if(empty($type)) {
     $query   = "SELECT * FROM NOTA_RELOKASI_MTY WHERE NO_NOTA_MTI='{$trx_number}'";
     $parse   = oci_parse($conn, $query);
     $execute = oci_execute($parse);

     if(!$execute) {
       // close connection
       oci_close($conn);
       return '^87^ORA - Table / View / Column not found';
     }

     $row  = oci_fetch_assoc($parse);

     if(!empty($row)) {
       $type = 'RELOK_MTY';
     }
   }

   // check if row not empty and running next process
   if(!empty($row)) {

     // if status is batal, return error
     if($row['STATUS'] == 'BATAL') {
       // close connection
       oci_close($conn);
       return '^C0^Bill Suspend';
     }

     // if lunas is not equal NO, return error
     if($row['LUNAS'] != 'NO') {
       // close connection
       oci_close($conn);
       return '^B8^Already Paid';
     }

     // if data found, return success
     $outstatus = '00';
     $outmsg = 'SUCCESS';
     $notanumber = $row['NO_NOTA'];
     $userid = $row['NIPP_USER'];
     $no_faktur = 'USTER';
     $id_req = $type;
     $inv_char 	= array("&", "\"", "'", "<", ">", "^");
     $fix_char	= array(" ", " ", " ", " ", " ", " ");
     $emkl = $row['EMKL'];
     $status = 'S';
     $alamat= $row['ALAMAT'];
     $vessel = '';
     $voyage_in = '';
     $voyage_out = '';
     $tgl_simpan = $row['TANGGAL_SIMPAN'];
     $tgl_payment = $row['TGL_TRANSFER'];
     $payment_via = $row['BAYAR'];
     $total = $row['TOTAL_TAGIHAN'];
     $coa = '';
     $kd_modul = $row['JENIS_MODUL'];
     $ket = $row['KET_KOREKSI'];
     $ket_jenis = '';
     $apptocess_date = $row['ARPROCESS_DATE'];

     // close connection
     oci_close($conn);
     return "^$outstatus^$outmsg^$notanumber^$userid^$no_faktur^$id_req^".str_replace($inv_char,$fix_char,$emkl)."^$status^".str_replace($inv_char,$fix_char,$alamat)."^$vessel^$voyage_in^$voyage_out^$tgl_simpan^$tgl_payment^$payment_via^$total^$coa^$kd_modul^$ket^$ket_jenis^$apptocess_date^";
   }
   else
   {
     // close connection
     oci_close($conn);
     // data not found
     return "^B5^Not Found";
   }
} // end ePaymentInquiry Function

/*-------------------------------------------------
| ePaymentPaid
|--------------------------------------------------
| trx_number : String - NO NOTA
| jenis      : String
| user_id    : String
| bank_id    : String - 'BANK, etc'
| via        : String
| emkl       : String
| jumlah     : String - AMOUNT
|
| Last Updated At : 07 April 2020 by ILCS Team
*/

function ePaymentPaid($trx_number, $user_id, $bank_id, $paid_date, $paid_channel)
{
  // Connection to USTER
  $conn = oci_connect('uster', 'usasdter', '10.15.42.43/datamti');

  // check connection if fails return error
  if(!$conn) {
    // close connection
    oci_close($conn);
   return "^87^ORA - Database problem, cant connect to database.";
  }

  $jenis = '';

  //query from nota_receiving
  if(empty($jenis)) {
    $query   = "SELECT * FROM NOTA_RECEIVING WHERE NO_NOTA_MTI='{$trx_number}'";
    $parse   = oci_parse($conn, $query);
    $execute = oci_execute($parse);

    if(!$execute) {
      // close connection
      oci_close($conn);
      return '^87^ORA - Table / View / Column not found';
    }

    $row  = oci_fetch_assoc($parse);

    if(!empty($row)) {
      $jenis = 'RECEIVING';
    }
  }
  //query from nota_stuffing
  if(empty($jenis)) {
      $query   = "SELECT * FROM NOTA_STUFFING WHERE NO_NOTA_MTI='{$trx_number}'";
      $parse   = oci_parse($conn, $query);
      $execute = oci_execute($parse);

      if(!$execute) {
        // close connection
        oci_close($conn);
        return '^87^ORA - Table / View / Column not found';
      }

      $row  = oci_fetch_assoc($parse);

      if(!empty($row)) {
        if($row['STATUS'] == 'PERP') {
          $jenis = 'PERP_PNK';
        } else {
          $jenis = 'STUFFING';
        }
      }
  }
  // query from pnkn stuffing
  if(empty($jenis)) {
    $query   = "SELECT * FROM NOTA_PNKN_STUF WHERE NO_NOTA_MTI='{$trx_number}'";
    $parse   = oci_parse($conn, $query);
    $execute = oci_execute($parse);

    if(!$execute) {
      // close connection
      oci_close($conn);
      return '^87^ORA - Table / View / Column not found';
    }

    $row  = oci_fetch_assoc($parse);

    if(!empty($row)) {
      $jenis = 'STUF_PNK';
    }
  }
  //query from nota_stripping
  if(empty($jenis)) {
    $query   = "SELECT * FROM NOTA_STRIPPING WHERE NO_NOTA_MTI='{$trx_number}'";
    $parse   = oci_parse($conn, $query);
    $execute = oci_execute($parse);

    if(!$execute) {
      // close connection
      oci_close($conn);
      return '^87^ORA - Table / View / Column not found';
    }

    $row  = oci_fetch_assoc($parse);

    if(!empty($row)) {
      if($row['STATUS'] == 'PERP') {
        $jenis = 'PERP_STRIP';
      } else {
        $jenis = 'STRIPPING';
      }
    }
  }
  //query from nota_delivery
  if(empty($jenis)) {
    $query   = "SELECT * FROM NOTA_DELIVERY WHERE NO_NOTA_MTI='{$trx_number}'";
    $parse   = oci_parse($conn, $query);
    $execute = oci_execute($parse);

    if(!$execute) {
      // close connection
      oci_close($conn);
      return '^87^ORA - Table / View / Column not found';
    }

    $row  = oci_fetch_assoc($parse);

    if(!empty($row)) {
      if($row['STATUS'] == 'PERP') {
        $jenis = 'PERP_DEV';
      } else {
        $jenis = 'DELIVERY';
      }
    }
  }
  // query from pnkn delivery
  if(empty($jenis)) {
    $query   = "SELECT * FROM NOTA_PNKN_DEL WHERE NO_NOTA_MTI='{$trx_number}'";
    $parse   = oci_parse($conn, $query);
    $execute = oci_execute($parse);

    if(!$execute) {
      // close connection
      oci_close($conn);
      return '^87^ORA - Table / View / Column not found';
    }

    $row  = oci_fetch_assoc($parse);

    if(!empty($row)) {
      $jenis = 'DEL_PNK';
    }
  }
  //query from nota_batal_muat
  if(empty($jenis)) {
    $query   = "SELECT * FROM NOTA_BATAL_MUAT WHERE NO_NOTA_MTI='{$trx_number}'";
    $parse   = oci_parse($conn, $query);
    $execute = oci_execute($parse);

    if(!$execute) {
      // close connection
      oci_close($conn);
      return '^87^ORA - Table / View / Column not found';
    }

    $row  = oci_fetch_assoc($parse);

    if(!empty($row)) {
      $jenis = 'BATAL_MUAT';
    }
  }
  // query from relokasi_mty
  if(empty($jenis)) {
    $query   = "SELECT * FROM NOTA_RELOKASI_MTY WHERE NO_NOTA_MTI='{$trx_number}'";
    $parse   = oci_parse($conn, $query);
    $execute = oci_execute($parse);

    if(!$execute) {
      // close connection
      oci_close($conn);
      return '^87^ORA - Table / View / Column not found';
    }

    $row  = oci_fetch_assoc($parse);

    if(!empty($row)) {
      $jenis = 'RELOK_MTY';
    }
  }

  // check if row not empty
  if(!empty($row)) {

    // if status is batal, return error
    if($row['STATUS'] == 'BATAL') {
      // close connection
      oci_close($conn);
      return '^C0^Bill Suspend';
    }

    // if lunas is not equal NO, return error
    if($row['LUNAS'] != 'NO') {
      // close connection
      oci_close($conn);
      return '^B8^Already Paid';
    }

    $query_bank = "SELECT bank_account_name
                   FROM billing.mst_bank_simkeu
                   WHERE bank_id = '{$bank_id}'";

    $parse_bank   = oci_parse($conn, $query_bank);
    $execute_bank = oci_execute($parse_bank);

    if(!$execute_bank) {
     // close connection
     oci_close($conn);
     return '^87^ORA - Table / View / Column not found';
    }

    $row_bank  = oci_fetch_assoc($parse_bank);

    if(empty($row_bank)) {
      oci_close($conn);
      return "^B5^Not Found^";
    }

    $flag_opus = false; //set default

    // query to get asal_cont from container_stuffing to validate from TPK
    if($jenis == 'DELIVERY') {
      // query for get asal cont from request delivery
      $query_cont = "select delivery_ke, no_req_ict from request_delivery where no_request = '{$row['NO_REQUEST']}'";
      $parse_cont = oci_parse($conn, $query_cont);
      $execute_cont = oci_execute($parse_cont);
      $row_cont = oci_fetch_assoc($parse_cont);

      if($row_cont['DELIVERY_KE'] == 'TPK') {
        $flag_opus = true;
        $reqopus = $row_cont['NO_REQ_ICT'];
      }
    } else if($jenis == 'STUFFING' || $jenis == 'PERP_PNK') {
      //query for get asal cont from container_stuffing
      $query_cont = "select asal_cont from container_stuffing WHERE no_request = '{$row['NO_REQUEST']}'";
      $parse_cont = oci_parse($conn, $query_cont);
      $execute_cont = oci_execute($parse_cont);
      $row_cont = oci_fetch_assoc($parse_cont);

      if($row_count['ASAL_CONT'] == 'TPK') {
        //query for get asal cont from request_stuffing
        $query_opus = "select o_reqnbs from request_stuffing where no_request = '{$row['NO_REQUEST']}'";
        $parse_opus = oci_parse($conn, $query_opus);
        $execute_opus = oci_execute($parse_opus);
        $row_opus = oci_fetch_assoc($parse_opus);
        $flag_opus = true;
        $reqopus = $row_opus['O_REQNBS'];
      }
    } else if($jenis == 'STRIPPING' || $jenis == 'PERP_STRIP') {
      //query for get asal cont from container_stripping
      $query_opus = "select o_reqnbs from request_stripping WHERE no_request = '{$row['NO_REQUEST']}'";
      $parse_opus = oci_parse($conn, $query_opus);
      $execute_opus = oci_execute($parse_opus);
      $row_opus = oci_fetch_assoc($parse_opus);
      $flag_opus = true;
      $reqopus = $row_opus['O_REQNBS'];
    } else if($jenis == 'BATAL_MUAT') {
      $query_cont = "select status_gate,o_reqnbs from request_batal_muat where no_request = '{$row['NO_REQUEST']}'";
      $parse_cont = oci_parse($conn, $query_cont);
      $execute_cont = oci_execute($parse_cont);
      $row_cont = oci_fetch_assoc($parse_cont);
      if($row_cont['STATUS_GATE'] == '2'){
          $flag_opus = true;
          $reqopus = $row_cont['O_REQNBS'];
      }
    }

    // check flag opus is true, disabled for development
    if($flag_opus == true){
        // connect to opus_repo, flagging payment to opus
        $conn_opus = oci_connect('opus_repo', 'opus_reasdpo', '10.15.42.43/datamti');
        $out_status = '';
        $outmsg = '';
        //
        // // call procedure for flagging payment opus
        $query_payment = "declare begin payment_opusbill(:ID_REQ,:ID_NOTA,:OUT,:OUT_MSG); end;";
        $parse_payment = oci_parse($conn_opus, $query_payment);
        //
        oci_bind_by_name($parse_payment, "ID_REQ", $reqopus, 30) or die ('Can not bind variable');
        oci_bind_by_name($parse_payment, "ID_NOTA", $row['NO_NOTA'], 50) or die ('Can not bind variable');
        oci_bind_by_name($parse_payment, "OUT", $outstatus, 20) or die ('Can not bind variable');
        oci_bind_by_name($parse_payment, "OUT_MSG", $outmsg, 100) or die ('Can not bind variable');
        //
        $execute_opus = oci_execute($parse_payment);

        if(!$execute_opus) {
          // close connection
          oci_close($conn_opus);
          oci_close($conn);
          return '^01^General Failure - OPUS PAYMENT NOT GENERATED';
        }
    }

    //set for default payment by ILCS
    $user_id = 0;
    $via = 'BANK';
    $bank_name = $row_bank['BANK_ACCOUNT_NAME'];
    $out_trx_number = '';

    // call procedure for flagging payment
    $query_insert_inota = "BEGIN
      	USTER.ITPK_POPULATE_STAGING.INSERT_NOTA_ITPK(
      	  :ID_REQ,
      	  :IN_MODUL,
      	  :IN_PROFORMA,
      	  :IN_NOTA,
      	  :IN_KOREKSI,
      	  :IN_USERID,
      	  :IN_BANKID,
      	  :IN_VIA,
      	  :IN_EMKL,
      	  :IN_JUM,
      	  :IN_MTI,
          :IN_MATERAI,
      	  :OUT_TRX_NUMBER);
      END;";

    $materai = null;

    $parse_insert_inota = oci_parse($conn, $query_insert_inota) or die('oci parse failed');

    oci_bind_by_name($parse_insert_inota, "ID_REQ", $row['NO_REQUEST'], 30) or die ('Can not bind variable id_req');
    oci_bind_by_name($parse_insert_inota, "IN_MODUL", $jenis, 50) or die ('Can not bind variable in_modul');
    oci_bind_by_name($parse_insert_inota, "IN_PROFORMA", $row['NO_NOTA'], 100) or die ('Can not bind variable');
    oci_bind_by_name($parse_insert_inota, "IN_NOTA", $row['NO_NOTA'], 100) or die ('Can not bind variable');
    oci_bind_by_name($parse_insert_inota, "IN_KOREKSI", $row['STATUS'], 100) or die ('Can not bind variable');
    oci_bind_by_name($parse_insert_inota, "IN_USERID", $user_id, 100) or die ('Can not bind variable');
    oci_bind_by_name($parse_insert_inota, "IN_BANKID", $bank_name, 100) or die ('Can not bind variable');
    oci_bind_by_name($parse_insert_inota, "IN_VIA", $via, 100) or die ('Can not bind variable');
    oci_bind_by_name($parse_insert_inota, "IN_EMKL", $row['KD_EMKL'], 100) or die ('Can not bind variable');
    oci_bind_by_name($parse_insert_inota, "IN_JUM", $row['TOTAL_TAGIHAN'], 100) or die ('Can not bind variable');
    oci_bind_by_name($parse_insert_inota, "IN_MTI", $row['NO_NOTA_MTI'], 100) or die ('Can not bind variable');
    oci_bind_by_name($parse_insert_inota, "IN_MATERAI", $materai, 100) or die ('Can not bind variable');
    oci_bind_by_name($parse_insert_inota, "OUT_TRX_NUMBER", $out_trx_number, 100) or die ('Can not bind variable out_trx_number');
    
    $execute_nota_itpk = oci_execute($parse_insert_inota);

    // if fails execute query return error
    if(!$execute_nota_itpk) {
      // close connection
      oci_close($conn);
      return '^01^General Failure - NO FAKTUR NOT GENERATED';
    }

    // get all data from itpk nota header
    $query_itpk = "SELECT * FROM ITPK_NOTA_HEADER WHERE TRX_NUMBER='{$out_trx_number}'";
    $parse_itpk = oci_parse($conn, $query_itpk);
    $execute_itpk = oci_execute($parse_itpk);
    $row_itpk = oci_fetch_assoc($parse_itpk);
    $limit_materai = $row_itpk['KREDIT'] - 3000;

    // check nota have need e-materai
    if($limit_materai > 250000) {
      $peraturan = 'SI-00153/SK/WPJ.19/KP.0403/2020';
      $update_materai = "UPDATE ITPK_NOTA_HEADER SET NO_PERATURAN='{$peraturan}' WHERE TRX_NUMBER='{$out_trx_number}'";
      $parse_update_materai = oci_parse($conn, $update_materai);
      $execute_update_materai = oci_execute($parse_update_materai);
    }

    //query from nota_receiving
    if($jenis == 'RECEIVING') {
      $query   = "SELECT * FROM NOTA_RECEIVING WHERE NO_NOTA_MTI='{$trx_number}'";
      $parse   = oci_parse($conn, $query);
      $execute = oci_execute($parse);

      if(!$execute) {
        // close connection
        oci_close($conn);
        return '^87^ORA - Table / View / Column not found';
      }

      $row  = oci_fetch_assoc($parse);
    }
    //query from nota_stuffing
    if($jenis == 'STUFFING' || $jenis == 'PERP_PNK') {
        $query   = "SELECT * FROM NOTA_STUFFING WHERE NO_NOTA_MTI='{$trx_number}'";
        $parse   = oci_parse($conn, $query);
        $execute = oci_execute($parse);

        if(!$execute) {
          // close connection
          oci_close($conn);
          return '^87^ORA - Table / View / Column not found';
        }

        $row  = oci_fetch_assoc($parse);
    }
    //query from pnkn stuffing
    if($jenis == 'STUF_PNK') {
      $query   = "SELECT * FROM NOTA_PNKN_STUF WHERE NO_NOTA_MTI='{$trx_number}'";
      $parse   = oci_parse($conn, $query);
      $execute = oci_execute($parse);

      if(!$execute) {
        // close connection
        oci_close($conn);
        return '^87^ORA - Table / View / Column not found';
      }

      $row  = oci_fetch_assoc($parse);
    }
    //query from nota_stripping
    if($jenis=='STRIPPING' || $jenis == 'PERP_STRIP') {
      $query   = "SELECT * FROM NOTA_STRIPPING WHERE NO_NOTA_MTI='{$trx_number}'";
      $parse   = oci_parse($conn, $query);
      $execute = oci_execute($parse);

      if(!$execute) {
        // close connection
        oci_close($conn);
        return '^87^ORA - Table / View / Column not found';
      }

      $row  = oci_fetch_assoc($parse);
    }
    //query from nota_delivery
    if($jenis=='DELIVERY' || $jenis=='PERP_DEV') {
      $query   = "SELECT * FROM NOTA_DELIVERY WHERE NO_NOTA_MTI='{$trx_number}'";
      $parse   = oci_parse($conn, $query);
      $execute = oci_execute($parse);

      if(!$execute) {
        // close connection
        oci_close($conn);
        return '^87^ORA - Table / View / Column not found';
      }

      $row  = oci_fetch_assoc($parse);
    }

    // query from pnkn delivery
    if($jenis=='DEL_PNK') {
      $query   = "SELECT * FROM NOTA_PNKN_DEL WHERE NO_NOTA_MTI='{$trx_number}'";
      $parse   = oci_parse($conn, $query);
      $execute = oci_execute($parse);

      if(!$execute) {
        // close connection
        oci_close($conn);
        return '^87^ORA - Table / View / Column not found';
      }

      $row  = oci_fetch_assoc($parse);
    }
    //query from nota_batal_muat
    if($jenis=='BATAL_MUAT') {
      $query   = "SELECT * FROM NOTA_BATAL_MUAT WHERE NO_NOTA_MTI='{$trx_number}'";
      $parse   = oci_parse($conn, $query);
      $execute = oci_execute($parse);

      if(!$execute) {
        // close connection
        oci_close($conn);
        return '^87^ORA - Table / View / Column not found';
      }

      $row  = oci_fetch_assoc($parse);
    }
    //query from relokasi mty
    if($jenis=='RELOK_MTY') {
      $query   = "SELECT * FROM NOTA_RELOKASI_MTY WHERE NO_NOTA_MTI='{$trx_number}'";
      $parse   = oci_parse($conn, $query);
      $execute = oci_execute($parse);

      if(!$execute) {
        // close connection
        oci_close($conn);
        return '^87^ORA - Table / View / Column not found';
      }

      $row  = oci_fetch_assoc($parse);
    }

    $outstatus = '00';
    $outmsg = 'SUCCESS';
    $notanumber = $row['NO_NOTA'];
    $userid = $row['NIPP_USER'];
    $no_faktur = $row['NO_FAKTUR_MTI'];
    $id_req = $row['NO_REQUEST'];
    $inv_char 	= array("&", "\"", "'", "<", ">", "^");
    $fix_char	= array(" ", " ", " ", " ", " ", " ");
    $emkl = $row['EMKL'];
    $status = 'S';
    $alamat= $row['ALAMAT'];
    $vessel = '';
    $voyage_in = '';
    $voyage_out = '';
    $tgl_simpan = $row_itpk['TGL_SIMPAN'];
    $tgl_payment = $row_itpk['TGL_PELUNASAN'];
    $payment_via = $row_itpk['RECEIPT_ACCOUNT'];
    $total = $row['TOTAL_TAGIHAN'];
    $coa = '';
    $kd_modul = $row_itpk['JENIS_NOTA'];
    $ket = $row['KET_KOREKSI'];
    $ket_jenis = '';
    $apptocess_date = '';

    // close connection
    oci_close($conn);
    return "^$outstatus^$outmsg^$notanumber^$userid^$no_faktur^$id_req^".str_replace($inv_char,$fix_char,$emkl)."^$status^".str_replace($inv_char,$fix_char,$alamat)."^$vessel^$voyage_in^$voyage_out^$tgl_simpan^$tgl_payment^$payment_via^$total^$coa^$kd_modul^$ket^$ket_jenis^$apptocess_date^";
  } else {
    // close connection
    oci_close($conn);
    // data not found
    return "^B5^Not Found";
  }
}

$server = new soap_server();
$server->configureWSDL('portalipc2', 'urn:portalipc2');

$server->wsdl->schemaTargetNamespace = 'portalipc2';

$server->register('ePaymentInquiry',
            array('trxnumber' => 'xsd:string'),
            array('return' => 'xsd:string'),
            'urn:portalipc2',
            'urn:portalipc2#pollServer');

$server->register('ePaymentPaid',
            array('trxnumber'  => 'xsd:string',
                  'userid'     => 'xsd:string',
                  'bankid'     => 'xsd:string',
                  'paiddate'   => 'xsd:string',
                  'paidchannel'=> 'xsd:string'),
            array('return' => 'xsd:string'),
            'urn:portalipc2',
            'urn:portalipc2#pollServer');



// $server->service($HTTP_RAW_POST_DATA);
if (!isset($HTTP_RAW_POST_DATA)) {
    $HTTP_RAW_POST_DATA = file_get_contents('php://input');
}

$server->service($HTTP_RAW_POST_DATA);

?>
