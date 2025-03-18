<?php
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=LAP-PENUMPUKAN-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

	$tgl_awal  = $_POST["tgl_awal"]; 
  $tgl_akhir  = $_POST["tgl_akhir"]; 
  $jenis    = $_POST["status"];
  $id_menu2   = $_POST['menu2'];
  
  // echo "TES";die;
  
  $jum = count($id_menu2);
  $orderby = 'ORDER BY ';
  for($i=0;$i<count($id_menu2);$i++){
    $orderby .= $id_menu2[$i];
    if($i != $jum-1){
      $orderby .= ",";
    }
    
  }
  if($orderby  == "ORDER BY n"){
		$orderby= "";
	}
	//echo $tgl_awal;die;
	$db 	= getDB("storage");
	
	if($jenis == "ALL"){
		$f_jenis = "";
	}
	else {
		$f_jenis = "and status = '$jenis'";
	}
	
  $cek_cutoff = "select case when to_date('$tgl_awal', 'DD-MM-RRRR') >= to_date('01-01-2015', 'DD-MM-RRRR') 
  then 'OK' else 'NO' end AS STATUS from dual";

  
  
  $r_cutoff = $db->query($cek_cutoff)->fetchRow();
  
  //print_r($r_cutoff['STATUS']);die;
  
  if($r_cutoff['STATUS'] == 'OK'){
// REMARKED BY YOSUA CHRISTIANOV ITSD (23/07/2020) USTER DOUBLE DATA
 //    $query_list_ = "SELECT * FROM (SELECT itpk_nota_header.no_request,
 //      itpk_nota_header.trx_number id_nota,
 //       mst_tipe_layanan.tipe_layanan keterangan,
 //       itpk_nota_detail.boxes jml_cont,
 //       itpk_nota_detail.tarif,
 //       itpk_nota_detail.size_,
 //       itpk_nota_detail.stat_ status,
 //       itpk_nota_detail.hari jml_hari,
 //       itpk_nota_detail.amount biaya,
 //       mst_tipe_layanan.coa
 //  FROM itpk_nota_header
 //       JOIN itpk_nota_detail
 //          ON itpk_nota_header.trx_number = itpk_nota_detail.trx_number
 //       LEFT JOIN mst_tipe_layanan 
 //          ON itpk_nota_detail.line_description = mst_tipe_layanan.simkeu_memo 
 // WHERE TO_DATE (trx_date, 'dd-mm-rrrr') BETWEEN TO_DATE ('$tgl_awal',
 //                                                         'dd-mm-rrrr')
 //                                            AND TO_DATE ('$tgl_akhir',
 //                                                         'dd-mm-rrrr')
 //       AND  line_description LIKE 'PNP%') where 1=1 ".$f_jenis;
	   //print_r($query_list_);die;
// END REMARK BY YOSUA CHRISTIANOV ITSD (23/07/2020) USTER DOUBLE DATA
// EDITED BY YOSUA CHRISTIANOV ITSD (30/06/2020) USTER DOUBLE DATA
//     $query_list_ = "SELECT * FROM (
// SELECT itpk_nota_header.no_request,
//       itpk_nota_header.trx_number id_nota,
//       NOTA_PNKN_DEL_D.KETERANGAN,
//        itpk_nota_detail.boxes jml_cont,
//        itpk_nota_detail.tarif,
//        itpk_nota_detail.size_,
//        itpk_nota_detail.stat_ status,
//        itpk_nota_detail.hari jml_hari,
//        itpk_nota_detail.amount biaya,
//        NOTA_PNKN_DEL_D.COA
//   FROM USTER.itpk_nota_header
//        JOIN USTER.itpk_nota_detail
//           ON itpk_nota_header.trx_number = itpk_nota_detail.trx_number
//        LEFT JOIN USTER.NOTA_PNKN_DEL
//        ON NOTA_PNKN_DEL.NO_FAKTUR = ITPK_NOTA_HEADER.TRX_NUMBER
//        LEFT JOIN  USTER.NOTA_PNKN_DEL_D
//        ON (NOTA_PNKN_DEL_D.LINE_NUMBER = ITPK_NOTA_DETAIL.LINE_NUMBER AND NOTA_PNKN_DEL_D.ID_NOTA = NOTA_PNKN_DEL.NO_NOTA)
//  WHERE TO_DATE (trx_date, 'dd-mm-rrrr') BETWEEN TO_DATE ('$tgl_awal',
//                                                          'dd-mm-rrrr')
//                                             AND TO_DATE ('$tgl_akhir',
//                                                          'dd-mm-rrrr')
//        AND  line_description LIKE 'PNP%') where 1=1 ".$f_jenis;
    $query_list_ = "SELECT * FROM (
      SELECT itpk_nota_header.no_request,
      itpk_nota_header.trx_number id_nota,
      NVL(COALESCE(
          NOTA_PNKN_DEL_D.KETERANGAN, 
          NOTA_DELIVERY_D.KETERANGAN,
          NOTA_STUFFING_D.KETERANGAN,
          NOTA_STRIPPING_D.KETERANGAN,
          NOTA_PNKN_STUF_D.KETERANGAN,
          NOTA_RELOKASI_MTY_D.KETERANGAN,
          NOTA_RELOKASI_D.KETERANGAN,
          NOTA_BATAL_MUAT_D.KETERANGAN,
          NOTA_RECEIVING_D.KETERANGAN
      ),(SELECT mst_tipe_layanan.tipe_layanan FROM USTER.mst_tipe_layanan WHERE mst_tipe_layanan.simkeu_memo = itpk_nota_detail.line_description AND ROWNUM <= 1)) AS KETERANGAN, 
      itpk_nota_detail.boxes jml_cont,
      itpk_nota_detail.tarif,
      itpk_nota_detail.size_,
      itpk_nota_detail.stat_ status,
      itpk_nota_detail.hari jml_hari,
      itpk_nota_detail.amount biaya,
      trx_date,
      NVL(COALESCE(
          NOTA_PNKN_DEL_D.COA, 
          NOTA_DELIVERY_D.COA,
          NOTA_STUFFING_D.COA,
          NOTA_STRIPPING_D.COA,
          NOTA_PNKN_STUF_D.COA,
          NOTA_RELOKASI_MTY_D.COA,
          NOTA_RELOKASI_D.COA,
          NOTA_BATAL_MUAT_D.COA,
          NOTA_RECEIVING_D.COA
      ),(SELECT mst_tipe_layanan.COA FROM USTER.mst_tipe_layanan WHERE mst_tipe_layanan.simkeu_memo = itpk_nota_detail.line_description AND ROWNUM <= 1)) AS COA, 
      ITPK_NOTA_DETAIL.LINE_NUMBER,
      itpk_nota_detail.line_description
      FROM USTER.itpk_nota_header
      JOIN USTER.itpk_nota_detail
      ON itpk_nota_header.trx_number = itpk_nota_detail.trx_number
      LEFT JOIN USTER.NOTA_PNKN_DEL
      ON NOTA_PNKN_DEL.NO_FAKTUR = ITPK_NOTA_HEADER.TRX_NUMBER
      LEFT JOIN  USTER.NOTA_PNKN_DEL_D
      ON (NOTA_PNKN_DEL_D.LINE_NUMBER = ITPK_NOTA_DETAIL.LINE_NUMBER AND NOTA_PNKN_DEL_D.ID_NOTA = NOTA_PNKN_DEL.NO_NOTA)
      LEFT JOIN USTER.NOTA_DELIVERY
      ON NOTA_DELIVERY.NO_FAKTUR = ITPK_NOTA_HEADER.TRX_NUMBER AND NOTA_DELIVERY.NO_REQUEST = ITPK_NOTA_HEADER.NO_REQUEST
      LEFT JOIN USTER.NOTA_DELIVERY_D
      ON (NOTA_DELIVERY_D.LINE_NUMBER = ITPK_NOTA_DETAIL.LINE_NUMBER AND NOTA_DELIVERY_D.ID_NOTA = NOTA_DELIVERY.NO_NOTA)
      LEFT JOIN USTER.NOTA_STUFFING
      ON NOTA_STUFFING.NO_FAKTUR = ITPK_NOTA_HEADER.TRX_NUMBER AND NOTA_STUFFING.NO_REQUEST = ITPK_NOTA_HEADER.NO_REQUEST
      LEFT JOIN USTER.NOTA_STUFFING_D
      ON (NOTA_STUFFING_D.LINE_NUMBER = ITPK_NOTA_DETAIL.LINE_NUMBER AND NOTA_STUFFING_D.NO_NOTA = NOTA_STUFFING.NO_NOTA)
      LEFT JOIN USTER.NOTA_STRIPPING
      ON NOTA_STRIPPING.NO_FAKTUR = ITPK_NOTA_HEADER.TRX_NUMBER AND NOTA_STRIPPING.NO_REQUEST = ITPK_NOTA_HEADER.NO_REQUEST
      LEFT JOIN USTER.NOTA_STRIPPING_D
      ON (NOTA_STRIPPING_D.LINE_NUMBER = ITPK_NOTA_DETAIL.LINE_NUMBER AND NOTA_STRIPPING_D.NO_NOTA = NOTA_STRIPPING.NO_NOTA)
       LEFT JOIN USTER.NOTA_PNKN_STUF
      ON NOTA_PNKN_STUF.NO_FAKTUR = ITPK_NOTA_HEADER.TRX_NUMBER AND NOTA_PNKN_STUF.NO_REQUEST = ITPK_NOTA_HEADER.NO_REQUEST
      LEFT JOIN USTER.NOTA_PNKN_STUF_D
      ON (NOTA_PNKN_STUF_D.LINE_NUMBER = ITPK_NOTA_DETAIL.LINE_NUMBER AND NOTA_PNKN_STUF_D.NO_NOTA = NOTA_PNKN_STUF.NO_NOTA)
      LEFT JOIN USTER.NOTA_RELOKASI_MTY
      ON NOTA_RELOKASI_MTY.NO_FAKTUR = ITPK_NOTA_HEADER.TRX_NUMBER AND NOTA_RELOKASI_MTY.NO_REQUEST = ITPK_NOTA_HEADER.NO_REQUEST
      LEFT JOIN USTER.NOTA_RELOKASI_MTY_D
      ON (NOTA_RELOKASI_MTY_D.LINE_NUMBER = ITPK_NOTA_DETAIL.LINE_NUMBER AND NOTA_RELOKASI_MTY_D.NO_NOTA = NOTA_RELOKASI_MTY.NO_NOTA)
      LEFT JOIN USTER.NOTA_RELOKASI
      ON NOTA_RELOKASI.NO_FAKTUR = ITPK_NOTA_HEADER.TRX_NUMBER AND NOTA_RELOKASI.NO_REQUEST = ITPK_NOTA_HEADER.NO_REQUEST
      LEFT JOIN USTER.NOTA_RELOKASI_D
      ON (NOTA_RELOKASI_D.LINE_NUMBER = ITPK_NOTA_DETAIL.LINE_NUMBER AND NOTA_RELOKASI_D.NO_NOTA = NOTA_RELOKASI.NO_NOTA)
      LEFT JOIN USTER.NOTA_BATAL_MUAT
      ON NOTA_BATAL_MUAT.NO_FAKTUR = ITPK_NOTA_HEADER.TRX_NUMBER AND NOTA_BATAL_MUAT.NO_REQUEST = ITPK_NOTA_HEADER.NO_REQUEST
      LEFT JOIN USTER.NOTA_BATAL_MUAT_D
      ON (NOTA_BATAL_MUAT_D.LINE_NUMBER = ITPK_NOTA_DETAIL.LINE_NUMBER AND NOTA_BATAL_MUAT_D.ID_NOTA = NOTA_BATAL_MUAT.NO_NOTA)
      LEFT JOIN USTER.NOTA_RECEIVING
      ON NOTA_RECEIVING.NO_FAKTUR = ITPK_NOTA_HEADER.TRX_NUMBER AND NOTA_RECEIVING.NO_REQUEST = ITPK_NOTA_HEADER.NO_REQUEST
      LEFT JOIN USTER.NOTA_RECEIVING_D
      ON (NOTA_RECEIVING_D.LINE_NUMBER = ITPK_NOTA_DETAIL.LINE_NUMBER AND NOTA_RECEIVING_D.NO_NOTA = NOTA_RECEIVING.NO_NOTA)
      WHERE TO_DATE (trx_date, 'dd-mm-rrrr') BETWEEN TO_DATE ('$tgl_awal',
      'dd-mm-rrrr')
      AND TO_DATE ('$tgl_akhir',
      'dd-mm-rrrr')
      AND  line_description LIKE 'PNP%'
  ) where 1=1 AND KETERANGAN NOT LIKE '%BIAYA KARTU%' AND KETERANGAN NOT LIKE '%ADMIN NOTA%' AND KETERANGAN LIKE 'PENUMPUKAN%' ".$f_jenis." ORDER BY NO_REQUEST, LINE_NUMBER ASC ";
// END EDITED BY YOSUA CHRISTIANOV ITSD (30/06/2020) USTER DOUBLE DATA
  }
  else{
	$query_list_ = "SELECT no_request,
       CASE
          WHEN TO_DATE (tgl_nota, 'DD-MM-YYYY') <=
                  TO_DATE ('01-06-2013', 'DD-MM-YYYY')
          THEN
             id_nota
          ELSE
             no_faktur
       END
          AS id_nota,
       keterangan,
       jml_cont,
       tarif,
       iso_code.size_,
       iso_code.status,
       CASE
          WHEN keterangan LIKE '%I.1%'
          THEN
             1
          ELSE
             CASE
                WHEN jml_hari IS NOT NULL THEN jml_hari
                ELSE (end_stack - start_stack + 1)
             END
       END
          jml_hari,
       biaya,
       coa
  FROM nota_all_h
       JOIN nota_all_d
          ON nota_all_h.no_nota = nota_all_d.id_nota
       JOIN iso_code
          ON nota_all_d.id_iso = iso_code.id_iso
 WHERE TO_DATE (tgl_nota, 'dd-mm-rrrr') BETWEEN TO_DATE ('$tgl_awal',
                                                         'dd-mm-rrrr')
                                            AND TO_DATE ('$tgl_akhir',
                                                         'dd-mm-rrrr')
       AND (   keterangan = 'PENUMPUKAN MASA I.1'
            OR keterangan = 'PENUMPUKAN MASA I.1 LAP 1-5'
            OR keterangan = 'PENUMPUKAN MASA I.1 LAP 6-8'
            OR keterangan = 'PENUMPUKAN MASA I.2'
            OR keterangan = 'PENUMPUKAN MASA I.2 LAP 1-5'
            OR keterangan = 'PENUMPUKAN MASA II'
            OR keterangan = 'PENUMPUKAN MASA II LAP'
            OR keterangan = 'PENUMPUKAN MASA II LAP 1-5') ".$f_jenis. " ". $orderby;
  }
  
  // echo $query_list_;
  
	$result_list_	= $db->query($query_list_);
	if($result_list_->RecordCount() > 0){
		$row_list		= $result_list_->getAll(); 
	}
	
	
	/* $q_sum = "select sum(a.jumlah) jml_pass, TO_CHAR(sum(biaya),'999,999,999,999') biaya from (
				select no_request, no_nota, no_faktur, keterangan, coa, 
				case when no_request like 'REC%' then 'RECEIVING'
				when no_request like 'STR%' then 'STRIPPING'
				when no_request like 'DEL%' then 'DELIVERY'
				when no_request like 'STF%' then 'STUFFING'
				END kegiatan,
				tarif, jml_cont jumlah, biaya from nota_all_d join nota_all_h on nota_all_d.id_nota = nota_all_h.no_nota
				where to_date(tgl_nota, 'DD-MM-RRRR') between to_date('$tgl_awal', 'DD-MM-RRRR') and to_date('$tgl_akhir', 'DD-MM-RRRR')
				and coa = 'RUPA') a	".$f_jenis;
				
	$res_list_	= $db->query($q_sum);
	$jum_list		= $res_list_->fetchRow();  */

?>
 
  
 <div id="list">
    <center><h2>LAPORAN PENUMPUKAN <br/> Periode <?=$tgl_awal?> s/d <?=$tgl_akhir?></h2></center>
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
                              <tr style=" font-size:10pt">
                                  <th valign="top" class="grid-header"  style="font-size:8pt">NO</th>                                  
                                  <th valign="top" class="grid-header"  style="font-size:8pt">NO REQUEST</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">NO NOTA</th>
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">KETERANGAN</th> 
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">COA</th> 
                                  <th valign="top" class="grid-header"  style="font-size:8pt">JML CONT</th>                                  
                                  <th valign="top" class="grid-header"  style="font-size:8pt">SIZE</th>                                  
                                  <th valign="top" class="grid-header"  style="font-size:8pt">TARIF</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">STATUS</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">JML HARI</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">BIAYA</th>
                              </tr>
							  <?php $i=1; ?>
							  <?php foreach($row_list as $rows){ ?>
                              
                              <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">
                                  <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$i?> </td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows[NO_REQUEST]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows[ID_NOTA]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows[KETERANGAN]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows[COA]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows[JML_CONT]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows[SIZE_]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[TARIF]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[STATUS]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[JML_HARI]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[BIAYA]?></font></td>
                  <?php $i++; ?>
								</tr>
				<?php } ?>			
        </table>
</div>
<!--<table class="grid-table" border='1' cellpadding="1" cellspacing="1">
<tr>
	<td>TOTAL PASS</td>
	<td>:</td>
	<td><?=$jum_list[JML_PASS]?></td>
</tr> 
<tr>
	<td>TOTAL BIAYA</td>
	<td>:</td>
	<td><?=$jum_list[BIAYA]?></td>
</tr>		
</table>-->