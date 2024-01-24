<?php
$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=LAP-PASSTRUCK-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

	$tgl_awal  = $_POST["tgl_awal"]; 
  $tgl_akhir  = $_POST["tgl_akhir"]; 
  $jenis    = $_POST["jenis"];
  $id_menu2   = $_POST['menu2'];
  
  $jum = count($id_menu2);
  $orderby = 'ORDER BY ';
  for($i=0;$i<count($id_menu2);$i++){
    $orderby .= $id_menu2[$i];
    if($i != $jum-1){
      $orderby .= ",";
    }
    
  }
  if($jum  == 0){
    $orderby= "";
  }
	//echo $tgl_awal;die;
	$db 	= getDB("storage");
	
	if($jenis == "ALL"){
		$f_jenis = "";
	}
	else {
		$f_jenis = "where a.kegiatan = '$jenis'";
	}
	
	$cek_cutoff = "select case when to_date('$tgl_awal', 'DD-MM-RRRR') >= to_date('01-01-2015', 'DD-MM-RRRR') 
	then 'OK' else 'NO' end AS STATUS from dual";

	$r_cutoff = $db->query($cek_cutoff)->fetchRow();
	if($r_cutoff['STATUS'] == 'OK'){
		$query_list_ = "select * from (
                select no_request, itpk_nota_header.no_nota_mti no_nota, itpk_nota_header.no_faktur_mti no_faktur, 'PASS TRUCK' keterangan, 'RUPA' coa, 
                case when no_request like 'REC%' then 'RECEIVING'
                when no_request like 'STR%' then 'STRIPPING'
                when no_request like 'DEL%' then 'DELIVERY'
                when no_request like 'STF%' then 'STUFFING'
                END kegiatan,
                tarif, boxes jumlah, amount biaya from itpk_nota_detail join itpk_nota_header on itpk_nota_detail.trx_number = itpk_nota_header.trx_number
                where to_date(trx_date, 'DD-MM-RRRR') between to_date('$tgl_awal', 'DD-MM-RRRR') and to_date('$tgl_akhir', 'DD-MM-RRRR')
                and line_description = 'PASTR RUPA') a	".$f_jenis. " ". $orderby;
			
		$result_list_	= $db->query($query_list_);
		$row_list		= $result_list_->getAll(); 
		
		$q_sum = "select sum(a.jumlah) jml_pass, TO_CHAR(sum(biaya),'999,999,999,999') biaya from (
                select no_request, itpk_nota_header.no_nota_mti no_nota, itpk_nota_header.no_faktur_mti no_faktur, 'PASS TRUCK' keterangan, line_description, 
                case when no_request like 'REC%' then 'RECEIVING'
                when no_request like 'STR%' then 'STRIPPING'
                when no_request like 'DEL%' then 'DELIVERY'
                when no_request like 'STF%' then 'STUFFING'
                END kegiatan,
                tarif, boxes jumlah, amount biaya from itpk_nota_detail join itpk_nota_header on itpk_nota_detail.trx_number = itpk_nota_header.trx_number
                where to_date(trx_date, 'DD-MM-RRRR') between to_date('$tgl_awal', 'DD-MM-RRRR') and to_date('$tgl_akhir', 'DD-MM-RRRR')
                and line_description = 'PASTR RUPA') a ".$f_jenis;
				
		$res_list_	= $db->query($q_sum);
		$jum_list		= $res_list_->fetchRow(); 
	}
	else {


	$query_list_ = "select * from (
				select no_request, TO_number(no_nota_mti) no_nota_mti, no_faktur_mti, keterangan, coa, 
				case when no_request like 'REC%' then 'RECEIVING'
				when no_request like 'STR%' then 'STRIPPING'
				when no_request like 'DEL%' then 'DELIVERY'
				when no_request like 'STF%' then 'STUFFING'
				END kegiatan,
				tarif, jml_cont jumlah, biaya from nota_all_d join nota_all_h on nota_all_d.id_nota = nota_all_h.no_nota
				where to_date(tgl_nota, 'DD-MM-RRRR') between to_date('$tgl_awal', 'DD-MM-RRRR') and to_date('$tgl_akhir', 'DD-MM-RRRR')
				and coa = 'RUPA') a	".$f_jenis. " ". $orderby;

	$result_list_	= $db->query($query_list_);
	$row_list		= $result_list_->getAll();
	
	$q_sum = "select sum(a.jumlah) jml_pass, TO_CHAR(sum(biaya),'999,999,999,999') biaya from (
				select no_request, no_nota_mti, no_faktur_mti, keterangan, coa, 
				case when no_request like 'REC%' then 'RECEIVING'
				when no_request like 'STR%' then 'STRIPPING'
				when no_request like 'DEL%' then 'DELIVERY'
				when no_request like 'STF%' then 'STUFFING'
				END kegiatan,
				tarif, jml_cont jumlah, biaya from nota_all_d join nota_all_h on nota_all_d.id_nota = nota_all_h.no_nota
				where to_date(tgl_nota, 'DD-MM-RRRR') between to_date('$tgl_awal', 'DD-MM-RRRR') and to_date('$tgl_akhir', 'DD-MM-RRRR')
				and coa = 'RUPA') a	".$f_jenis;
				
	$res_list_	= $db->query($q_sum);
	$jum_list		= $res_list_->fetchRow(); 
	}
?>
 
  
 <div id="list">
    <center><h2>LAPORAN PASS TRUK <br/> Periode <?=$tgl_awal?> s/d <?=$tgl_akhir?></h2></center>
     <table class="grid-table" border='1' cellpadding="1" cellspacing="1"  width="100%" >
                              <tr style=" font-size:10pt">
                                  <th valign="top" class="grid-header"  style="font-size:8pt">NO</th>                                  
                                  <th valign="top" class="grid-header"  style="font-size:8pt">NO REQUEST</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">NO NOTA</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">NO FAKTUR</th>                                  
                                  <th  valign="top" class="grid-header"  style="font-size:8pt">KETERANGAN</th> 
                                  <th valign="top" class="grid-header"  style="font-size:8pt">COA</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">KEGIATAN</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">TARIF</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">JUMLAH PASS</th>
                                  <th valign="top" class="grid-header"  style="font-size:8pt">BIAYA</th>
                              </tr>
							  <?php $i=1; ?>
							  <?php foreach($row_list as $rows){ ?>
                              
                              <tr bgcolor="#f9f9f3" onMouseOver=this.style.backgroundColor="#BAD5FC" onMouseOut=this.style.backgroundColor="">
                                  <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$i?> </td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows[NO_REQUEST]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows[NO_NOTA]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows[NO_FAKTUR]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt"><?=$rows[KETERANGAN]?></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[COA]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[KEGIATAN]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[TARIF]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[JUMLAH]?></font></td>
                                  <td width="15%" align="center" valign="middle" class="grid-cell"  style="font-size:9pt;font-family:Arial;"><font color="#0066CC" style="font-size:10pt"><?=$rows[BIAYA]?></font></td>
                  <?php $i++; } ?>
							
        </table>

<table class="grid-table" border='1' cellpadding="1" cellspacing="1">
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
</table>