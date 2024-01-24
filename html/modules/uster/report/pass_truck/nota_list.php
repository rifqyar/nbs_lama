<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('nota_list.htm');
	
	$tgl_awal	= $_POST["tgl_awal"]; 
	$tgl_akhir	= $_POST["tgl_akhir"]; 
	$jenis		= $_POST["jenis"];
	$id_menu2 	= $_POST['id_menu2'];
	
	$jum = count($id_menu2);
	$orderby = 'ORDER BY ';
	for($i=0;$i<count($id_menu2);$i++){
		$orderby .= $id_menu2[$i];
		if($i != $jum-1){
			$orderby .= ",";
		}
		
	}
	//echo $jum; exit();
	if($jum  == 1){
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
				select no_request, no_nota_mti, no_faktur_mti, keterangan, coa, 
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
	
	$tl->assign("jum_list",$jum_list);
	$tl->assign("row_list",$row_list);
	$tl->assign("tgl_awal",$tgl_awal);
	$tl->assign("tgl_akhir",$tgl_akhir);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
