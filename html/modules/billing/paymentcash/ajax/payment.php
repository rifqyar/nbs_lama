<?php
	$db=getDb();
	$nota=$_GET['idn'];
	$req=$_GET['req'];
	$jenis=$_GET['ket'];
	$vessel=$_GET['vessel'];
	$voyin=$_GET['voyin'];
	$voyout=$_GET['voyout'];
	$total=number_format ( $_GET['total'] , 0 , "." ,"," );
	
	$query="select
                        count(*) as JUM 
                from 
                        TTH_NOTA_ALL2 
                where 
                        trim(NO_NOTA)=trim('".$nota."')
                        and trim(NO_REQUEST)=trim('".$req."')";
	//print_r($query);die;
	$rs = $db->query($query);
	$row = $rs->fetchRow();
	$hasil=$row['JUM'];
	
	if($hasil>0) {	//sudah payment
		echo "<script>
				alert('Request / Nota ini sudah payment');
				ReloadPage();
			  </script>";
		
	}
	
	/*$query_sama = "
		select * from (
        select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
        SELECT  
            a.id_proforma, a.total, 
            case when keterangan <> 'MATERAI' then sub_total * 1.1
                    else sub_total *1
            end jum_detail
          FROM nota_receiving_h a 
          join nota_receiving_d b on a.id_proforma = b.id_proforma
         WHERE a.id_req = '$req') c
         group by c.id_proforma, c.total
         union all
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
        SELECT  
            a.id_proforma, a.total, 
            case when keterangan <> 'MATERAI' then sub_total * 1.1
                    else sub_total *1
            end jum_detail
          FROM nota_receiving_h_pen a 
          join nota_receiving_d b on a.id_proforma = b.id_proforma and b.KETERANGAN <> 'ADM'
         WHERE a.id_req = '$req') c
         group by c.id_proforma, c.total
         union all
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
        SELECT  
            a.id_proforma, a.total, 
            case when b.keterangan <> 'MATERAI' then sub_total * 1.1
                    else sub_total *1
            end jum_detail
          FROM nota_delivery_h a 
          join nota_delivery_d b on a.id_proforma = b.id_proforma
         WHERE a.id_req = '$req') c
         group by c.id_proforma, c.total
          union all         
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
        SELECT  
            a.id_proforma, a.total, 
            case when b.keterangan <> 'MATERAI' then sub_total * 1.1
                    else sub_total *1
            end jum_detail
          FROM nota_delivery_h_pen a 
          join nota_delivery_d b on a.id_proforma = b.id_proforma
         WHERE a.id_req = '$req') c
         group by c.id_proforma, c.total
          union all
         select c.id_proforma, c.bayar total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.id_proforma, a.bayar, 
            case when b.keterangan <> 'MATERAI' then b.total * 1.1
                    else b.total *1
            end jum_detail
          FROM nota_batalmuat_h a 
          join nota_batalmuat_d b on a.id_proforma = b.id_proforma
         WHERE a.id_req = '$req'
         ) c
         group by c.id_proforma, c.bayar
         union all       
         select c.id_proforma, c.bayar total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.id_proforma, a.bayar, 
            case when b.keterangan <> 'MATERAI' then b.total * 1.1
                    else b.total *1
            end jum_detail
          FROM nota_batalmuat_h_penumpukan a 
          join nota_batalmuat_d_penumpukan b on a.id_proforma = b.id_proforma
         WHERE a.id_req = '$req'
         ) c
         group by c.id_proforma, c.bayar        
         union all 
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.id_proforma, a.total, 
            case when b.keterangan <> 'MATERAI' then b.sub_total * 1.1
                    else b.sub_total *1
            end jum_detail
          FROM nota_behandle_h a 
          join nota_behandle_d b on a.id_proforma = b.id_proforma
         WHERE a.id_req = '$req'
         ) c
         group by c.id_proforma, c.total
         union all
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.ID_NOTA id_proforma, a.total, 
            case when b.keterangan <> 'MATERAI' then b.sub_total * 1.1
                    else b.sub_total *1
            end jum_detail
          FROM EXMO_NOTA a 
          join EXMO_DETAIL_NOTA b on a.ID_NOTA = b.ID_NOTA
         WHERE a.id_request = '$req'
         ) c
         group by c.id_proforma, c.total
         union all
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.id_proforma, a.total, 
            case when b.keterangan <> 'MATERAI' then b.sub_total * 1.1
                    else b.sub_total *1
            end jum_detail
          FROM NOTA_TRANSHIPMENT_H a 
          join NOTA_TRANSHIPMENT_d b on a.id_proforma = b.id_proforma
         WHERE a.id_req = '$req'
         ) c
         group by c.id_proforma, c.total
        union all
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.id_proforma, a.total, 
            case when b.keterangan <> 'MATERAI' then b.sub_total * 1.1
                    else b.sub_total *1
            end jum_detail
          FROM NOTA_REEXPORT_H a 
          join NOTA_REEXPORT_d b on a.id_proforma = b.id_proforma
         WHERE a.id_req = '$req'
         ) c
         group by c.id_proforma, c.total
         union all
          select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.ID_NOTA id_proforma, a.total, 
            case when b.keterangan <> 'MATERAI' then b.sub_total * 1.1
                    else b.sub_total *1
            end jum_detail
          FROM NOTA_STACKEXT_H a 
          join NOTA_STACKEXT_d b on a.ID_NOTA = b.ID_NOTA
         WHERE a.id_request = '$req'
         ) c
         group by c.id_proforma, c.total
         union all
          select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.id_proforma, a.total, 
            case when b.keterangan <> 'MATERAI' then b.sub_total * 1.1
                    else b.sub_total *1
            end jum_detail
          FROM NOTA_RENAME_H a 
          join NOTA_RENAME_d b on a.id_proforma = b.id_proforma
         WHERE a.id_req = '$req'
         ) c
         group by c.id_proforma, c.total
         union all
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.id_proforma, a.total, 
            case when b.keterangan <> 'MATERAI' then b.sub_total * 1.1
                    else b.sub_total *1
            end jum_detail
          FROM NOTA_MONREEFER_H a 
          join NOTA_MONREEFER_d b on a.id_proforma = b.id_proforma
         WHERE a.id_req = '$req'
         ) c
         group by c.id_proforma, c.total
         union all
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.id_nota id_proforma, a.total, 
            case when b.keterangan <> 'MATERAI' then b.sub_total * 1.1
                    else b.sub_total *1
            end jum_detail
          FROM NOTA_HICOSCAN_H a 
          join NOTA_HICOSCAN_d b on a.id_nota  = b.id_nota
         WHERE a.id_request = '$req'
         ) c
         group by c.id_proforma, c.total
         )
	";*/
	
	$query_sama = "
		select * from (
        select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
        SELECT  
            a.id_proforma, a.total, 
            case when keterangan <> 'MATERAI' and var <= 0 then sub_total * 1.1
                     when keterangan <> 'MATERAI' and var >  0 then NVL((a.sub_total * var /100) + a.sub_total,0)
                    else sub_total *1
            end jum_detail
          FROM(
        SELECT a.total,a.coa,b.sub_total,b.keterangan,a.id_proforma
            ,nvl((select nvl(variable,0) from master_cut_off zz where zz.JENIS_CUT_OFF ='CUT_OFF_PPN11' AND ( to_char(a.TGL_REQ,'yyyymmdd') >= to_char(start_date,'yyyymmdd') ) AND ( to_char(a.TGL_REQ,'yyyymmdd') <= to_char(end_date,'yyyymmdd') or end_date is null)),0) var
          FROM nota_receiving_h a 
          join nota_receiving_d b on a.id_proforma = b.id_proforma
         WHERE a.id_req = '$req') a     
         ) c
         group by c.id_proforma, c.total
         union all
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
        SELECT  
            a.id_proforma, a.total, 
            case --when keterangan <> 'MATERAI' then sub_total * 1.1
                    when keterangan <> 'MATERAI' and var <= 0 then sub_total * 1.1
                     when keterangan <> 'MATERAI' and var >  0 then NVL((a.sub_total * var /100) + a.sub_total,0)
                    else sub_total *1
            end jum_detail
          from(
          SELECT a.total,a.coa,b.sub_total,b.keterangan,a.id_proforma
            ,nvl((select nvl(variable,0) from master_cut_off zz where zz.JENIS_CUT_OFF ='CUT_OFF_PPN11' AND ( to_char(a.TGL_REQ,'yyyymmdd') >= to_char(start_date,'yyyymmdd') ) AND ( to_char(a.TGL_REQ,'yyyymmdd') <= to_char(end_date,'yyyymmdd') or end_date is null)),0) var
          FROM nota_receiving_h_pen a 
          join nota_receiving_d b on a.id_proforma = b.id_proforma and b.KETERANGAN <> 'ADM'
         WHERE a.id_req = '$req') a) c
         group by c.id_proforma, c.total
         union all         
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
        SELECT  
            a.id_proforma, a.total, 
            case --when b.keterangan <> 'MATERAI' then sub_total * 1.1
                    when keterangan <> 'MATERAI' and var <= 0 then sub_total * 1.1
                     when keterangan <> 'MATERAI' and var >  0 then NVL((a.sub_total * var /100) + a.sub_total,0)
                    else sub_total *1
            end jum_detail
            from(
          SELECT a.total,a.coa,b.sub_total,b.keterangan,a.id_proforma
            ,nvl((select nvl(variable,0) from master_cut_off zz where zz.JENIS_CUT_OFF ='CUT_OFF_PPN11' AND ( to_char(a.TGL_REQuest,'yyyymmdd') >= to_char(start_date,'yyyymmdd') ) AND ( to_char(a.TGL_REQuest,'yyyymmdd') <= to_char(end_date,'yyyymmdd') or end_date is null)),0) var
          FROM nota_delivery_h a 
          join nota_delivery_d b on a.id_proforma = b.id_proforma
         WHERE a.id_req = '$req')a) c
         group by c.id_proforma, c.total         
          union all        
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
        SELECT  
            a.id_proforma, a.total, 
            case --when b.keterangan <> 'MATERAI' then sub_total * 1.1
                     when keterangan <> 'MATERAI' and var <= 0 then sub_total * 1.1
                     when keterangan <> 'MATERAI' and var >  0 then NVL((a.sub_total * var /100) + a.sub_total,0)
                    else sub_total *1
            end jum_detail
          from(
          SELECT a.total,a.coa,b.sub_total,b.keterangan,a.id_proforma
            ,nvl((select nvl(variable,0) from master_cut_off zz where zz.JENIS_CUT_OFF ='CUT_OFF_PPN11' AND ( to_char(a.TGL_REQuest,'yyyymmdd') >= to_char(start_date,'yyyymmdd') ) AND ( to_char(a.TGL_REQuest,'yyyymmdd') <= to_char(end_date,'yyyymmdd') or end_date is null)),0) var
          FROM nota_delivery_h_pen a 
          join nota_delivery_d b on a.id_proforma = b.id_proforma
         WHERE a.id_req = '$req')a) c
         group by c.id_proforma, c.total
          union all
         select c.id_proforma, c.bayar total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.id_proforma, a.bayar, 
            case --when b.keterangan <> 'MATERAI' then b.total * 1.1
                    when keterangan <> 'MATERAI' and var <= 0 then sub_total * 1.1
                     when keterangan <> 'MATERAI' and var >  0 then NVL((a.sub_total * var /100) + a.sub_total,0)
                    else a.sub_total *1
            end jum_detail
           from(
          SELECT a.bayar,a.coa,b.total sub_total,b.keterangan,a.id_proforma
            ,nvl((select nvl(variable,0) from master_cut_off zz where zz.JENIS_CUT_OFF ='CUT_OFF_PPN11' AND ( to_char(a.TGL_REQ,'yyyymmdd') >= to_char(start_date,'yyyymmdd') ) AND ( to_char(a.TGL_REQ,'yyyymmdd') <= to_char(end_date,'yyyymmdd') or end_date is null)),0) var
          FROM nota_batalmuat_h a 
          join nota_batalmuat_d b on a.id_proforma = b.id_proforma
         WHERE a.id_req = '$req') a
         ) c
         group by c.id_proforma, c.bayar         
         union all       
         select c.id_proforma, c.bayar total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.id_proforma, a.bayar, 
            case --when b.keterangan <> 'MATERAI' then b.total * 1.1
                    --else b.total *1
                    when keterangan <> 'MATERAI' and var <= 0 then sub_total * 1.1
                    when keterangan <> 'MATERAI' and var >  0 then NVL((a.sub_total * var /100) + a.sub_total,0)
                    else a.sub_total *1
            end jum_detail
         from(
          SELECT a.bayar,a.coa,b.total sub_total,b.keterangan,a.id_proforma
            ,nvl((select nvl(variable,0) from master_cut_off zz where zz.JENIS_CUT_OFF ='CUT_OFF_PPN11' AND ( to_char(a.TGL_REQ,'yyyymmdd') >= to_char(start_date,'yyyymmdd') ) AND ( to_char(a.TGL_REQ,'yyyymmdd') <= to_char(end_date,'yyyymmdd') or end_date is null)),0) var
          FROM nota_batalmuat_h_penumpukan a 
          join nota_batalmuat_d_penumpukan b on a.id_proforma = b.id_proforma
         WHERE a.id_req = '$req' )a
         ) c
         group by c.id_proforma, c.bayar     
         union all          
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.id_proforma, a.total, 
            case --when b.keterangan <> 'MATERAI' then b.sub_total * 1.1
                    when keterangan <> 'MATERAI' and var <= 0 then sub_total * 1.1
                    when keterangan <> 'MATERAI' and var >  0 then NVL((a.sub_total * var /100) + a.sub_total,0)
                    else a.sub_total *1
            end jum_detail
          from(
          SELECT a.total,a.coa,b.sub_total,b.keterangan,a.id_proforma
            ,nvl((select nvl(variable,0) from master_cut_off zz where zz.JENIS_CUT_OFF ='CUT_OFF_PPN11' AND ( to_char(a.TGL_REQuest,'yyyymmdd') >= to_char(start_date,'yyyymmdd') ) AND ( to_char(a.TGL_REQuest,'yyyymmdd') <= to_char(end_date,'yyyymmdd') or end_date is null)),0) var
          FROM nota_behandle_h a 
          join nota_behandle_d b on a.id_proforma = b.id_proforma
         WHERE a.id_req = '$req') a
         ) c
         group by c.id_proforma, c.total         
         union all         
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.ID_NOTA id_proforma, a.total, 
            case --when b.keterangan <> 'MATERAI' then b.sub_total * 1.1
                    when keterangan <> 'MATERAI' and var <= 0 then sub_total * 1.1
                    when keterangan <> 'MATERAI' and var >  0 then NVL((a.sub_total * var /100) + a.sub_total,0)
                    else a.sub_total *1
            end jum_detail
          from(
          SELECT a.total,a.coa,b.sub_total,b.keterangan,a.ID_NOTA
            ,nvl((select nvl(variable,0) from master_cut_off zz where zz.JENIS_CUT_OFF ='CUT_OFF_PPN11' AND ( to_char(d.TGL_REQ,'yyyymmdd') >= to_char(start_date,'yyyymmdd') ) AND ( to_char(d.TGL_REQ,'yyyymmdd') <= to_char(end_date,'yyyymmdd') or end_date is null)),0) var
          FROM EXMO_NOTA a 
          join EXMO_DETAIL_NOTA b on a.ID_NOTA = b.ID_NOTA
          join exmo_request d on trim(d.ID_REQUEST) = trim(a.id_request) 
         WHERE a.id_request = '$req') a
         ) c
         group by c.id_proforma, c.total         
         union all         
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.id_proforma, a.total, 
            case --when b.keterangan <> 'MATERAI' then b.sub_total * 1.1
                    when keterangan <> 'MATERAI' and var <= 0 then sub_total * 1.1
                    when keterangan <> 'MATERAI' and var >  0 then NVL((a.sub_total * var /100) + a.sub_total,0)
                    else a.sub_total *1
            end jum_detail
          from(
          SELECT a.total,a.coa,b.sub_total,b.keterangan,a.id_proforma
            ,nvl((select nvl(variable,0) from master_cut_off zz where zz.JENIS_CUT_OFF ='CUT_OFF_PPN11' AND ( to_char(d.TGL_REQuest,'yyyymmdd') >= to_char(start_date,'yyyymmdd') ) AND ( to_char(d.TGL_REQuest,'yyyymmdd') <= to_char(end_date,'yyyymmdd') or end_date is null)),0) var
          FROM NOTA_TRANSHIPMENT_H a 
          join NOTA_TRANSHIPMENT_d b on a.id_proforma = b.id_proforma
          join req_transhipment_h d on trim(d.ID_REQ) = trim(a.id_req) 
         WHERE a.id_req = '$req') a
         ) c
         group by c.id_proforma, c.total         
        union all        
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.id_proforma, a.total, 
            case --when b.keterangan <> 'MATERAI' then b.sub_total * 1.1
                    when keterangan <> 'MATERAI' and var <= 0 then sub_total * 1.1
                    when keterangan <> 'MATERAI' and var >  0 then NVL((a.sub_total * var /100) + a.sub_total,0)
                    else sub_total *1
            end jum_detail
         from(
          SELECT a.total,a.coa,b.sub_total,b.keterangan,a.id_proforma
            ,nvl((select nvl(variable,0) from master_cut_off zz where zz.JENIS_CUT_OFF ='CUT_OFF_PPN11' AND ( to_char(d.TGL_REQuest,'yyyymmdd') >= to_char(start_date,'yyyymmdd') ) AND ( to_char(d.TGL_REQuest,'yyyymmdd') <= to_char(end_date,'yyyymmdd') or end_date is null)),0) var
          FROM NOTA_REEXPORT_H a 
          join NOTA_REEXPORT_d b on a.id_proforma = b.id_proforma
           join req_REEXPORT_H d on trim(d.ID_REQ) = trim(a.id_req) 
         WHERE a.id_req = '$req' ) a
         ) c
         group by c.id_proforma, c.total         
         union all         
        select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.ID_NOTA id_proforma, a.total, 
            case --when b.keterangan <> 'MATERAI' then b.sub_total * 1.1
                    when keterangan <> 'MATERAI' and var <= 0 then sub_total * 1.1
                    when keterangan <> 'MATERAI' and var >  0 then NVL((a.sub_total * var /100) + a.sub_total,0)
                    else a.sub_total *1
            end jum_detail
         from(
          SELECT a.total,a.coa,b.sub_total,b.keterangan,a.ID_NOTA
            ,nvl((select nvl(variable,0) from master_cut_off zz where zz.JENIS_CUT_OFF ='CUT_OFF_PPN11' AND ( to_char(a.TGL_REQuest,'yyyymmdd') >= to_char(start_date,'yyyymmdd') ) AND ( to_char(a.TGL_REQuest,'yyyymmdd') <= to_char(end_date,'yyyymmdd') or end_date is null)),0) var
          FROM NOTA_STACKEXT_H a 
          join NOTA_STACKEXT_d b on a.ID_NOTA = b.ID_NOTA
         WHERE a.id_request = '$req' ) a
         ) c
         group by c.id_proforma, c.total       
         union all         
          select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.id_proforma, a.total, 
            case --when b.keterangan <> 'MATERAI' then b.sub_total * 1.1
                    when keterangan <> 'MATERAI' and var <= 0 then sub_total * 1.1
                    when keterangan <> 'MATERAI' and var >  0 then NVL((a.sub_total * var /100) + a.sub_total,0)
                    else sub_total *1
            end jum_detail
         from(
          SELECT a.total,b.sub_total,b.keterangan,a.id_proforma
            ,nvl((select nvl(variable,0) from master_cut_off zz where zz.JENIS_CUT_OFF ='CUT_OFF_PPN11' AND ( to_char(a.TGL_RENAME,'yyyymmdd') >= to_char(start_date,'yyyymmdd') ) AND ( to_char(a.TGL_RENAME,'yyyymmdd') <= to_char(end_date,'yyyymmdd') or end_date is null)),0) var
          FROM NOTA_RENAME_H a 
          join NOTA_RENAME_d b on a.id_proforma = b.id_proforma
          join REQ_RENAME d on trim(d.ID_REQ) = trim(a.ID_REQ)
         WHERE a.id_req = '$req' ) a
         ) c
         group by c.id_proforma, c.total         
         union all         
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.id_proforma, a.total, 
            case --when b.keterangan <> 'MATERAI' then b.sub_total * 1.1
                    when keterangan <> 'MATERAI' and var <= 0 then sub_total * 1.1
                    when keterangan <> 'MATERAI' and var >  0 then NVL((a.sub_total * var /100) + a.sub_total,0)
                    else a.sub_total *1
            end jum_detail
         from(
          SELECT a.total,b.sub_total,b.keterangan,a.id_proforma
            ,nvl((select nvl(variable,0) from master_cut_off zz where zz.JENIS_CUT_OFF ='CUT_OFF_PPN11' AND ( to_char(a.TGL_REQ,'yyyymmdd') >= to_char(start_date,'yyyymmdd') ) AND ( to_char(a.TGL_REQ,'yyyymmdd') <= to_char(end_date,'yyyymmdd') or end_date is null)),0) var
          FROM NOTA_MONREEFER_H a 
          join NOTA_MONREEFER_d b on a.id_proforma = b.id_proforma
         WHERE a.id_req = '$req' ) a
         ) c
         group by c.id_proforma, c.total         
         union all         
         select c.id_proforma, c.total, round(sum(jum_detail)) jumlah
        from (
         SELECT  
            a.id_nota id_proforma, a.total, 
            case --when b.keterangan <> 'MATERAI' then b.sub_total * 1.1
                    when keterangan <> 'MATERAI' and var <= 0 then sub_total * 1.1
                    when keterangan <> 'MATERAI' and var >  0 then NVL((a.sub_total * var /100) + a.sub_total,0)
                    else a.sub_total *1
            end jum_detail
          from(
          SELECT a.total,b.sub_total,b.keterangan,a.id_nota
            ,nvl((select nvl(variable,0) from master_cut_off zz where zz.JENIS_CUT_OFF ='CUT_OFF_PPN11' AND ( to_char(a.TGL_REQUEST,'yyyymmdd') >= to_char(start_date,'yyyymmdd') ) AND ( to_char(a.TGL_REQUEST,'yyyymmdd') <= to_char(end_date,'yyyymmdd') or end_date is null)),0) var
          FROM NOTA_HICOSCAN_H a 
          join NOTA_HICOSCAN_d b on a.id_nota  = b.id_nota
         WHERE a.id_request = '$req' ) a
         ) c
         group by c.id_proforma, c.total         
         )
	";
	
	//print_r($query_sama);die;
	$rs_sama = $db->query($query_sama);
	$row_sama = $rs_sama->fetchRow();
	//print_r($row_sama);die;
	$total_sama =$row_sama['TOTAL'];
	$jumlah_sama =$row_sama['JUMLAH'];
	$selisih = $total_sama - $jumlah_sama;
	//print_r($selisih);die;
	
	if($selisih != 0) {	//sudah payment
		echo "<script>
				alert('Nota ini jumlah header dan detail tidak sama!! silahkan hubungi admin! ');
				ReloadPage();
			  </script>";
		
	}
	
?>
<script>
function via()
{
	$('#vias').load("<?=HOME?>billing.paymentcash.ajax/via").dialog({modal:true, height:120,width:220, title : "Via Payment"});
}
function set_via(i)
{
	$('#via').val(i);
	$('#vias').dialog('destroy').remove();
	$('#mainform2').append('<div id="vias"></div>');
}
</script>
<table>
<tr>
	<td>No. Nota</td>
	<td> : </td>
	<td><b><?=$nota?></b></td>
</tr>
<tr>
	<td>No. Request</td>
	<td> : </td>
	<td><b><?=$req?></b></td>
</tr>
<tr>
	<td>Jenis Nota</td>
	<td> : </td>
	<td><b><?=$jenis?></b></td>
</tr>
<tr>
	<td>Vessel</td>
	<td> : </td>
	<td><b><?=$vessel?></b></td>
</tr>
<tr>
	<td>Voy In</td>
	<td> : </td>
	<td><b><?=$voyin?></b></td>
</tr>
<tr>
	<td>Voy Out</td>
	<td> : </td>
	<td><b><?=$voyout?></b></td>
</tr>
<tr>
	<td>CMS</td>
	<td> : </td>
	<td><select id='cms'>
		<option value='0'>Non CMS</option>
		<option value='1'>CMS</option>
		</select></td>
</tr>
<tr>
	<td>Bayar Melalui</td>
	<td> : </td>
	<td><select id='kd_pelunasan' onchange="get_paid_via()">
		<!-- <option selected="selected" value="0">--- PILIH ---</option> -->
		<option value="1">BANK</option>
		<!-- <option value="2">CASH</option> -->
		<option value="3">AUTODB</option>
		<option value="4">DEPOSIT</option>
		</select>
	</td>
</tr>
<tr>
	<td>Paid Via</td>
	<td> : </td>
	<td>
		<div id="paid_via"></div>
	</td>
</tr>
<tr>
	<td>Total</td>
	<td> : </td>
	<td>
		Rp. <?=$total?>
	</td>
</tr>

<tr>
	<td>&nbsp;
	</td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>
		<button onclick="save_payment('<?=$nota?>','<?=$req?>','<?=$jenis?>','<?=$vessel?>','<?=$voyin?>','<?=$voyout?>')"> P A I D</button>
	</td>
</tr>

</table>
<form id="mainform2">
	<div id='vias'></div>
	
	</form>