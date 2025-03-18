<?php
$q = $_GET['q'];
$list_det_ukk = $_GET['list_det_ukk'];
$no_ukks = $_GET['no_ukks'];
$no_bundle = $_GET['no_bundle'];
$id_group = $_SESSION["ID_GROUP"];
if(isset($q)) 
{
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:'id_bprp'; // get index row - i.e. user click to sort
	
	if(!$sidx) $sidx =1;
	$db = getDB();
	if($q=='pranota_bprp') 
	{		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM OG_NOTA_BPRPH WHERE STATUS!='X')";
	
	}
	else if($q=='diskon_lolo')
	{
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM DISKON_NOTA_DEL_H )";
	}
	else if($q=='data_pyma')
	{	
		$db = getDB('pyma');
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM rka_tab_exist )";
	}
	else if($q=='payment_cash')
	{	
        if(isset($_GET['idreq'])){
            $id_req =$_GET['idreq'];
            $filternoreq = " WHERE z.ID_REQ = '$id_req'";
            
        }
		$db = getDB();
		$query ="SELECT 
                                z.ID_PROFORMA,z.ID_NOTA,z.NO_FAKTUR, z.ID_REQ, z.EMKL, z.STATUS,
                                z.ALAMAT, z.VESSEL, z.VOYAGE_IN, z.VOYAGE_OUT, 
                                z.TGL_SIMPAN, z.TGL_PAYMENT, z.PAYMENT_VIA,z.TOTAL,
                                z.COA,z.KD_MODUL,z.KET, y.ARPROCESS_DATE TANGGAL_RECEIPT
                         FROM 
                                (SELECT 
                                        ID_PROFORMA ID_PROFORMA, ID_PROFORMA AS ID_NOTA,NO_FAKTUR, ID_REQ, EMKL, STATUS,ALAMAT, 
                                        VESSEL, VOYAGE_IN, VOYAGE_OUT, TGL_SIMPAN, 
                                        TGL_PAYMENT, PAYMENT_VIA,TOTAL,COA,KD_MODUL,'ANNE' KET 
                                 FROM 
                                        nota_receiving_h
                                 UNION
                                 SELECT 
                                        ID_PROFORMA,ID_PROFORMA AS ID_NOTA,NO_FAKTUR, ID_REQ, EMKL, STATUS,ALAMAT, 
                                        VESSEL, VOYAGE_IN, VOYAGE_OUT, TGL_NOTA AS TGL_SIMPAN, 
                                        TGL_PAYMENT, PAYMENT_VIA, TOTAL,COA,KD_MODUL, 'SP2' KET 
                                 FROM 
                                        nota_delivery_h
								 UNION
								 SELECT 
                                        ID_PROFORMA,ID_PROFORMA AS ID_NOTA,NO_FAKTUR, ID_REQ, EMKL, STATUS,ALAMAT, 
                                        VESSEL, VOYAGE_IN, VOYAGE_OUT, TGL_NOTA AS TGL_SIMPAN, 
                                        TGL_PAYMENT, PAYMENT_VIA, TOTAL,COA,KD_MODUL, 'SPEP' KET 
                                 FROM 
                                        nota_delivery_h_pen										
                                 UNION
                                 SELECT 
                                        ID_PROFORMA ID_PROFORMA, ID_PROFORMA AS ID_NOTA, NO_FAKTUR, ID_REQ AS ID_REQ, 
                                        EMKL, STATUS, ALAMAT, VESSEL , VOYAGE_IN AS VOYAGE_IN, 
                                        VOYAGE_OUT AS VOYAGE_OUT, TGL_NOTA AS TGL_SIMPAN,TGL_PAYMENT, 
                                        PAYMENT_VIA, BAYAR TOTAL, COA, JENIS AS KD_MODUL,'BM' KET 
                                 FROM 
                                        nota_batalmuat_h 
                                 UNION
                                SELECT 
                                        ID_PROFORMA ID_PROFORMA, ID_PROFORMA AS ID_NOTA, NO_FAKTUR, ID_REQ AS ID_REQ, 
                                        EMKL, STATUS, ALAMAT, VESSEL , VOYAGE_IN AS VOYAGE_IN, 
                                        VOYAGE_OUT AS VOYAGE_OUT, TGL_NOTA AS TGL_SIMPAN,TGL_PAYMENT, 
                                        PAYMENT_VIA, BAYAR TOTAL, COA, JENIS AS KD_MODUL,'BMP' KET 
                                 FROM 
                                        nota_batalmuat_h_penumpukan 
                                 UNION 
                                 SELECT 
                                        ID_PROFORMA ID_PROFORMA, ID_PROFORMA AS ID_NOTA, NO_FAKTUR, ID_REQ AS ID_REQ, EMKL, STATUS, 
                                        ALAMAT_EMKL AS ALAMAT, VESSEL , VOYAGE_IN AS VOYAGE_IN, 
                                        VOYAGE_OUT AS VOYAGE_OUT, TGL_CETAK AS TGL_SIMPAN,TGL_PAYMENT, 
                                        PAYMENT_VIA, TOTAL, COA, '' AS KD_MODUL, 'BH' KET 
                                 FROM 
                                        nota_behandle_h
                                 UNION
                                 SELECT 
                                        '' ID_PROFORMA,ID_NOTA, NO_FAKTUR, ID_REQUEST AS ID_REQ, EMKL, STATUS, ALAMAT, 
                                        '' AS VESSEL , ' ' AS VOYAGE_IN, ' ' AS VOYAGE_OUT, 
                                        TGL_CETAK_NOTA AS TGL_SIMPAN,TGL_PAYMENT, PAYMENT_VIA, TOTAL, COA, 
                                        '' AS KD_MODUL, 'EXMO' KET 
                                 FROM 
                                        EXMO_NOTA
                                union
                                select 
                                        ID_PROFORMA ID_PROFORMA, ID_PROFORMA AS ID_NOTA, A.NO_FAKTUR, A.ID_REQ AS ID_REQ, A.CUSTOMER AS EMKL, 
                                        A.STATUS, A.ALAMAT, B.VESSEL_NEW AS VESSEL , ' ' AS VOYAGE_IN, 
                                        B.VOYAGE_OUT_NEW AS VOYAGE_OUT, A.TGL_CETAK AS TGL_SIMPAN,A.TGL_PAYMENT, 
                                        A.PAYMENT_VIA, A.TOTAL, A.COA, '' AS KD_MODUL, 'TRANS' KET 
                                from 
                                        NOTA_TRANSHIPMENT_H A LEFT JOIN REQ_TRANSHIPMENT_H B 
                                        ON B.ID_REQ=A.ID_REQ
                                union
                                select 
                                        ID_PROFORMA ID_PROFORMA, ID_PROFORMA AS ID_NOTA, A.NO_FAKTUR, A.ID_REQ AS ID_REQ, A.CUSTOMER AS EMKL, 
                                        A.STATUS, A.ALAMAT, B.VESSEL_NEW AS VESSEL , ' ' AS VOYAGE_IN, 
                                        B.VOYAGE_OUT_NEW AS VOYAGE_OUT, A.TGL_CETAK AS TGL_SIMPAN,
                                        A.TGL_PAYMENT, A.PAYMENT_VIA, A.TOTAL, A.COA, '' AS KD_MODUL, 
                                        'RXP' KET 
                                from 
                                        NOTA_REEXPORT_H A LEFT JOIN REQ_REEXPORT_H B 
                                        ON B.ID_REQ=A.ID_REQ
                                union
                                select 
                                        '' ID_PROFORMA,ID_NOTA, NO_FAKTUR, ID_REQUEST AS ID_REQ, CUSTOMER NM_PEMILIK, 
                                        STATUS, ALAMAT, VESSEL ,VOYAGE VOYAGE_IN, VOYAGE VOYAGE_OUT, 
                                        TGL_CETAK TGL_SIMPAN, TGL_PAYMENT, PAYMENT_VIA, TOTAL, COA,
                                        '' AS KD_MODUL, 'STACKEXT' KET 
                                from 
                                        NOTA_STACKEXT_H
                                union
                                select 
                                        trim(ID_PROFORMA) id_proforma, ID_PROFORMA ID_NOTA, NO_FAKTUR, ID_REQ AS ID_REQ, PELANGGAN AS NM_PEMILIK, 
                                        STATUS, ALAMAT, VESSEL ,VOYAGE_IN AS  VOYAGE_IN, VOYAGE_OUT AS VOYAGE_OUT, 
                                        TGL_SIMPAN, TGL_PAYMENT, PAYMENT_VIA, TOTAL, KD_PELANGGAN AS COA, '' AS KD_MODUL, 
                                        'RNM' KET 
                                from 
                                        NOTA_RENAME_H
                                UNION
                                select 
                                        ID_PROFORMA, ID_NOTA, NO_FAKTUR, ID_REQ, PELANGGAN AS NM_PEMILIK,
                                        STATUS, ALAMAT, VESSEL,VOYAGE_IN, VOYAGE_OUT, TGL_SIMPAN, TGL_PAYMENT, PAYMENT_VIA, TOTAL, KD_PELANGGAN AS COA, '' KD_MODUL,
                                        'MONREEF' KET
                                from
                                        NOTA_MONREEFER_H
                                UNION
                                select 
                                        '' ID_PROFORMA,ID_NOTA,ID_NOTA AS NO_FAKTUR ,ID_REQUEST AS ID_REQ, 
                                        EMKL AS NM_PEMILIK, STATUS, ALAMAT_EMKL AS ALAMAT, VESSEL ,
                                        VOYAGE AS VOYAGE_IN, NULL AS VOYAGE_OUT, TGL_CETAK AS TGL_SIMPAN, 
                                        TGL_PAYMENT, PAYMENT_VIA, TOTAL, COA, '' AS KD_MODUL, 
                                        'HICO' KET 
                                from 
                                        NOTA_HICOSCAN_H
                                ) z 
                          left join 
                                tth_nota_all2 y on z.ID_NOTA=y.NO_NOTA $filternoreq
                          order 
                                by z.TGL_SIMPAN DESC
";
		//echo($query); die();
	}
	else if($q=='l_transhipment')
	{
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM REQ_TRANSHIPMENT_H )";
	}
	else if($q=='l_reexport')
	{
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM REQ_REEXPORT_H)";
	}
	else if($q=='print_hico')
	{
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM REQ_HICOSCAN )";
	}
	else if($q=='p_rec')
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM REQ_RECEIVING_H)";
	
	$res = $db->query($query)->fetchRow();
	$count = $res[NUMBER_OF_ROWS];
	
	

	if( $count >0 ) 
	{
		$total_pages = ceil($count/$limit);
	}
	else 
	{ 
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)	

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	if($q=='pranota_bprp') 
	{
		$query="SELECT A.*, B.*, OG_GETNAMA.GET_PERDAGANGAN(B.JN_DAGANG) PERDAGANGAN FROM OG_NOTA_BPRPH A, OG_BPRPH B WHERE A.STATUS!='X' AND A.ID_BPRP=B.ID_BPRP ORDER BY ID_NOTA DESC";
	}
	else if($q=='diskon_lolo')
	{
		$query="SELECT NO_REQ_DEV,
					   TGL_REQ,
					   TGL_DISCHARGE,
					   TGL_BERLAKU,
					   JUMLAH_HARI,
					   NO_NOTA,
					   NO_FAKTUR,
					   FLAG_BA,
					   FLAG_REKAP
					FROM DISKON_NOTA_DEL_H
					ORDER BY TGL_REQ DESC";
	}
	else if($q=='data_pyma')
	{
		$query="SELECT USER_ID,
                       NO_NOTA,
                       TGL_PRANOTA,
                       TGL_INPUT,
                       AWAL_KEG,
                       AKHIR_KEG,
                       NO_UPER,
					   TO_CHAR(nvl(AMOUNT_UPER,0),'999,999,999,999,999.00') AMOUNT_UPER,
                       STATUS_AKHIR,
                       TO_CHAR(nvl(JUMLAH,0),'999,999,999,999,999.00') AMOUNT,
                       KETERANGAN,
                       CASE WHEN TERMINAL='9' THEN
                       'NON TERMINAL' ELSE TERMINAL END AS TERMINAL_
                       , DATE_CREATED, DATE_UPER
                    FROM rka_tab_exist WHERE STATUS_AKHIR <>'X' and USER_ID NOT IN('SUWARTONO','admin')
                    ORDER BY TERMINAL , KETERANGAN ASC";
	}
	else if($q=='payment_cash')
	{
        if(isset($_GET['idreq'])){
            $id_req =$_GET['idreq'];
            $filternoreq = " WHERE z.ID_REQ = '$id_req'";
            
        }
		$query="select 
                                * 
                        from 
                                (select 
                                        z.ID_PROFORMA,z.ID_NOTA,z.NO_FAKTUR, z.ID_REQ, z.EMKL, z.STATUS,
                                        z.ALAMAT, z.VESSEL, z.VOYAGE_IN, z.VOYAGE_OUT, 
                                        z.TGL_SIMPAN, z.TGL_PAYMENT, z.PAYMENT_VIA,z.TOTAL,
                                        z.COA,z.KD_MODUL,z.KET, y.ARPROCESS_DATE TANGGAL_RECEIPT
                                from 
                                        (select 
                                                ID_PROFORMA,NO_FAKTUR AS ID_NOTA,NO_FAKTUR, ID_REQ, EMKL, STATUS,ALAMAT, 
                                                VESSEL, VOYAGE_IN, VOYAGE_OUT, TGL_SIMPAN, TGL_PAYMENT, 
                                                PAYMENT_VIA,TOTAL,COA,KD_MODUL,'ANNE' KET 
                                        from 
                                                nota_receiving_h
                                        union
										select 
                                                ID_PROFORMA,NO_FAKTUR AS ID_NOTA,NO_FAKTUR, ID_REQ, EMKL, STATUS,ALAMAT, 
                                                VESSEL, VOYAGE_IN, VOYAGE_OUT, TGL_SIMPAN, TGL_PAYMENT, 
                                                PAYMENT_VIA,TOTAL,COA,KD_MODUL,'ANNE' KET 
                                        from 
                                                nota_receiving_h_pen
                                        union
                                        select 
                                                ID_PROFORMA,NO_FAKTUR AS ID_NOTA,NO_FAKTUR, ID_REQ, EMKL, STATUS,ALAMAT, VESSEL, 
                                                VOYAGE_IN, VOYAGE_OUT, TGL_NOTA AS TGL_SIMPAN, TGL_PAYMENT, 
                                                PAYMENT_VIA, TOTAL,COA,KD_MODUL, 'SP2' KET 
                                        from 
                                                nota_delivery_h
										UNION
										select 
                                                ID_PROFORMA,NO_FAKTUR AS ID_NOTA,NO_FAKTUR, ID_REQ, EMKL, STATUS,ALAMAT, VESSEL, 
                                                VOYAGE_IN, VOYAGE_OUT, TGL_NOTA AS TGL_SIMPAN, TGL_PAYMENT, 
                                                PAYMENT_VIA, TOTAL,COA,KD_MODUL, 'SPEP' KET 
                                        from 
                                                nota_delivery_h_pen												
                                        union
                                        select 
                                                ID_PROFORMA ID_PROFORMA, NO_FAKTUR AS ID_NOTA, NO_FAKTUR, ID_REQ AS ID_REQ, 
                                                EMKL, STATUS, ALAMAT, VESSEL , VOYAGE_IN AS VOYAGE_IN, 
                                                VOYAGE_OUT AS VOYAGE_OUT, TGL_NOTA AS TGL_SIMPAN,TGL_PAYMENT, 
                                                PAYMENT_VIA, BAYAR TOTAL, COA, JENIS AS KD_MODUL,'BM' KET 
                                        from 
                                                nota_batalmuat_h 
                                        union
                                        select 
                                                ID_PROFORMA ID_PROFORMA, NO_FAKTUR AS ID_NOTA, NO_FAKTUR, ID_REQ AS ID_REQ, 
                                                EMKL, STATUS, ALAMAT, VESSEL , VOYAGE_IN AS VOYAGE_IN, 
                                                VOYAGE_OUT AS VOYAGE_OUT, TGL_NOTA AS TGL_SIMPAN,TGL_PAYMENT, 
                                                PAYMENT_VIA, BAYAR TOTAL, COA, JENIS AS KD_MODUL,'BMP' KET 
                                        from 
                                                nota_batalmuat_h_penumpukan 
                                        union
                                        select 
                                                ID_PROFORMA ID_PROFORMA, NO_FAKTUR AS ID_NOTA, NO_FAKTUR, ID_REQ AS ID_REQ, EMKL, STATUS, 
                                                ALAMAT_EMKL AS ALAMAT, VESSEL , VOYAGE_IN AS VOYAGE_IN, 
                                                VOYAGE_OUT AS VOYAGE_OUT, TGL_CETAK AS TGL_SIMPAN,
                                                TGL_PAYMENT, PAYMENT_VIA, TOTAL, COA, '' AS KD_MODUL, 
                                                'BH' KET 
                                        from 
                                                NOTA_BEHANDLE_H
                                        union
                                        select 
                                                '' ID_PROFORMA,ID_NOTA, NO_FAKTUR, ID_REQUEST AS ID_REQ, EMKL, STATUS, 
                                                ALAMAT, '' AS VESSEL , ' ' AS VOYAGE_IN, ' ' AS VOYAGE_OUT, 
                                                TGL_CETAK_NOTA AS TGL_SIMPAN,TGL_PAYMENT, PAYMENT_VIA, 
                                                TOTAL, COA, '' AS KD_MODUL, 'EXMO' KET 
                                        from 
                                                EXMO_NOTA
                                        union
                                        select 
                                                ID_PROFORMA ID_PROFORMA, NO_FAKTUR AS ID_NOTA, A.NO_FAKTUR, A.ID_REQ AS ID_REQ, A.CUSTOMER AS EMKL, 
                                                A.STATUS, A.ALAMAT, B.VESSEL_NEW AS VESSEL , ' ' AS VOYAGE_IN, 
                                                B.VOYAGE_OUT_NEW AS VOYAGE_OUT, A.TGL_CETAK AS TGL_SIMPAN,
                                                A.TGL_PAYMENT, A.PAYMENT_VIA, A.TOTAL, A.COA, '' AS KD_MODUL, 
                                                'TRANS' KET 
                                        from 
                                                NOTA_TRANSHIPMENT_H A LEFT JOIN REQ_TRANSHIPMENT_H B 
                                                ON B.ID_REQ=A.ID_REQ
                                        union
                                        select 
                                                ID_PROFORMA ID_PROFORMA, NO_FAKTUR AS ID_NOTA, A.NO_FAKTUR, A.ID_REQ AS ID_REQ, A.CUSTOMER AS EMKL, 
                                                A.STATUS, A.ALAMAT, B.VESSEL_NEW AS VESSEL , ' ' AS VOYAGE_IN, 
                                                B.VOYAGE_OUT_NEW AS VOYAGE_OUT, A.TGL_CETAK AS TGL_SIMPAN,
                                                A.TGL_PAYMENT, A.PAYMENT_VIA, A.TOTAL, A.COA, '' AS KD_MODUL, 
                                                'RXP' KET 
                                        from 
                                                NOTA_REEXPORT_H A LEFT JOIN REQ_REEXPORT_H B 
                                                ON B.ID_REQ=A.ID_REQ
                                        union
                                        select 
                                                '' ID_PROFORMA,ID_NOTA, NO_FAKTUR, ID_REQUEST AS ID_REQ, CUSTOMER NM_PEMILIK, 
                                                STATUS, ALAMAT, VESSEL ,VOYAGE VOYAGE_IN, VOYAGE VOYAGE_OUT, 
                                                TGL_CETAK TGL_SIMPAN, TGL_PAYMENT, PAYMENT_VIA, TOTAL, COA, 
                                                '' AS KD_MODUL, 'REEX' KET 
                                        from 
                                                NOTA_STACKEXT_H
                                        union
                                        select 
                                                trim(ID_PROFORMA) id_proforma,NO_FAKTUR ID_NOTA, NO_FAKTUR, ID_REQ AS ID_REQ, PELANGGAN AS NM_PEMILIK, 
                                                STATUS, ALAMAT, VESSEL ,VOYAGE_IN AS  VOYAGE_IN, VOYAGE_OUT AS VOYAGE_OUT, 
                                                TGL_SIMPAN, TGL_PAYMENT, PAYMENT_VIA, TOTAL, KD_PELANGGAN COA, '' AS KD_MODUL, 
                                                'RNM' KET 
                                        from 
                                                NOTA_RENAME_H
                                        UNION
                                                select 
                                                ID_PROFORMA, ID_NOTA, NO_FAKTUR, ID_REQ, PELANGGAN AS NM_PEMILIK,
                                                STATUS, ALAMAT,VESSEL, VOYAGE_IN, VOYAGE_OUT, TGL_SIMPAN, TGL_PAYMENT, PAYMENT_VIA, TOTAL, KD_PELANGGAN AS COA, '' KD_MODUL,
                                                'MONREEF' KET
                                        from
                                                NOTA_MONREEFER_H
                                        UNION
                                        select 
                                                '' ID_PROFORMA,ID_NOTA,ID_NOTA AS NO_FAKTUR ,ID_REQUEST AS ID_REQ, EMKL AS NM_PEMILIK, 
                                                STATUS, ALAMAT_EMKL AS ALAMAT, VESSEL ,VOYAGE AS VOYAGE_IN, 
                                                NULL AS VOYAGE_OUT, TGL_CETAK AS TGL_SIMPAN, TGL_PAYMENT, 
                                                PAYMENT_VIA, TOTAL, COA, '' AS KD_MODUL, 'HICO' KET 
                                        from 
                                                NOTA_HICOSCAN_H
                                        ) z 
                                left join 
                                        tth_nota_all2 y 
                                        on z.ID_NOTA=y.NO_NOTA $filternoreq
                                order by 
                                        z.TGL_SIMPAN DESC) 
                                where rownum < 100";
	}
	else if($q=='l_transhipment')
	{
		$query="select ID_REQ, SHIPPING_LINE, VESSEL_OLD, VOYAGE_IN_OLD, VOYAGE_OUT_OLD, NO_UKK_OLD, NO_BC_12, NO_SURAT_TRS, VESSEL_NEW, VOYAGE_IN_NEW, VOYAGE_OUT_NEW, NO_UKK_NEW, OI, 	STATUS,
				(select count(1) from req_transhipment_d b where b.ID_REQ=a.ID_REQ ) JML_CONT
				from req_transhipment_h a
				order by TGL_REQUEST DESC";
	}
	else if($q=='l_reexport')
	{
		$query="select ID_REQ, SHIPPING_LINE, VESSEL_OLD, VOYAGE_IN_OLD, VOYAGE_OUT_OLD, NO_UKK_OLD, NO_BC_12, NO_SURAT_TRS, VESSEL_NEW, VOYAGE_IN_NEW, VOYAGE_OUT_NEW, NO_UKK_NEW, OI, 	STATUS,
				(select count(1) from req_reexport_d b where b.ID_REQ=a.ID_REQ ) JML_CONT
				from req_reexport_h a
				order by TGL_REQUEST DESC";
	}
	ELSE IF($q=='print_hico')
	{
		$query="SELECT A.ID_REQUEST,C.ID_NOTA,A.EMKL,A.VESSEL,A.VOYAGE,(SELECT COUNT(B.NO_CONTAINER) FROM REQ_HICOSCAN_D B WHERE A.ID_REQUEST=B.ID_REQUEST) JUMLAH_CONT,C.STATUS FROM REQ_HICOSCAN A,NOTA_HICOSCAN_H C  WHERE A.ID_REQUEST=C.ID_REQUEST ORDER BY A.TGL_REQUEST DESC";
	}
	else if($q=='p_rec') //ambil data header
		$query="SELECT a.ID_REQ,
                         a.KODE_PBM AS EMKL,
                         a.VESSEL,
                         a.VOYAGE,
						 NVL((SELECT COUNT (1) JUMLAH_CONT FROM req_receiving_d c WHERE c.NO_REQ_ANNE = a.ID_REQ),0) JUMLAH_CONT,
						 a.STATUS, a.PEB,a.NPE,a.TGL_STACK, a.TGL_MUAT,a.NO_UKK
                    FROM req_receiving_h a order by a.id_req desc";
	
	$res = $db->query($query);
	
	while ($row = $res->fetchRow()) 
	{
		$aksi = "";
		if($q == 'pranota_bprp') 
		{
			if($row[STATUS]=="I")
			{
				$aksi = "<a href='nota.penumpukan_ogdk/edit/$row[ID_NOTA]'><img border='0' src='images/edit.png' title='update jkm'></a>&nbsp;&nbsp;<a href='print/print_nota_bprp?p1=$row[ID_NOTA]' target='_blank'><img border='0' src='images/printer.png' width='14' height='14' title='cetak nota'></a>&nbsp;&nbsp;";
			}
			$aksi .= "<a href='print/print_pranota_bprp?p1=$row[ID_NOTA]' target='_blank'><img border='0' src='images/view.png' width='14' height='14' title='cetak pranota BPRP'></a>";	
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_BPRP],$row[ID_NOTA],$row[TGL_INVOICE],$row[TERMINAL],$row[NM_CUST],$row[VESSEL],$row[VOYAGE],$row[PERDAGANGAN],$row[NO_JKM],$row[TOTAL],$row[STATUS]);
		}
		else if($q == 'diskon_lolo') 
		{
			if($_SESSION["ID_GROUP"]=='9')
			{
				if($row[FLAG_REKAP]=='T')
				{
					if($row[FLAG_BA]=='T')
					{
						$aksi1 = "<font color='red'>BA not create</font>";
					}
					else
					{
						$aksi1 = "<a href=".HOME."billing.diskon_lolo/preview/?no_req=".$row[NO_REQ_DEV]."&status=REKAP target='_blank'><img src='images/cont_search.png' width=20px height=20px border='0' title='Preview Rekap'></a>";
					}					
				}
				else if($row[FLAG_REKAP]=='Y')
				{
					$aksi1 = "<a href=".HOME."billing.diskon_lolo.cetak/cetak_ulang_rekap2/?no_req=".$row[NO_REQ_DEV]." target='_blank'><img src='images/print.png' width=20px height=20px border='0' title='Cetak Ulang Rekap' /></a>";
				}
			}
			else
			{
				if($row[FLAG_BA]=='T')
				{
					$aksi1 = "<a href=".HOME."billing.diskon_lolo/preview/?no_req=".$row[NO_REQ_DEV]."&status=BA target='_blank'><img src='images/cont_search.png' width=20px height=20px border='0' title='Preview BA'></a>";		
				}
				else if($row[FLAG_BA]=='Y')
				{
					$aksi1 = "<a href=".HOME."billing.diskon_lolo.cetak/cetak_ulang_ba2/?no_req=".$row[NO_REQ_DEV]." target='_blank'><img src='images/print.png' width=20px height=20px border='0' title='Cetak Ulang Berita Acara' /></a>";
				}
			}
						
			$responce->rows[$i]['id']=$row[NO_REQ_DEV];
			$responce->rows[$i]['cell']=array($aksi1,$row[NO_REQ_DEV],$row[TGL_REQ],$row[TGL_DISCHARGE],$row[TGL_BERLAKU],$row[JUMLAH_HARI],$row[NO_NOTA],$row[NO_FAKTUR]);
		}
		else if($q == 'data_pyma') 
		{
								
			$responce->rows[$i]['id']=$row[TERMINAL_];
			$responce->rows[$i]['cell']=array($row[TERMINAL_],$row[NO_NOTA],$row[NO_UPER],$row[KETERANGAN],$row[TGL_INPUT],$row[AWAL_KEG],$row[AKHIR_KEG],$row[TGL_PRANOTA],$row[STATUS_AKHIR],$row[AMOUNT_UPER],$row[AMOUNT],$row[DATE_CREATED],$row[USER_ID]);
		}
		else if($q == 'payment_cash') 
		{
			//echo("ivan");
			$remark=$row[KET];
			$idn=$row[ID_PROFORMA];
			$idn2=$row[NO_FAKTUR];			
			$req=$row[ID_REQ];
			if($row[TANGGAL_RECEIPT]<>'')
			{
				$tglct=1;
			}
			else
				$tglct=0;
			if($row[STATUS]=='S')
			{
				if($_SESSION["ID_GROUP"]=='A' || $_SESSION["ID_GROUP"]=='D')
				{
					$act="<button title='Payment cash' onclick='pay(\"$idn\",\"$req\",\"$remark\",\"".$row[VESSEL]."\",\"".$row[VOYAGE_IN]."\",\"".$row[VOYAGE_OUT]."\",\"".$row[TOTAL]."\")'><img src='images/invo.png'></button>";			
				}
				else
				{	
					$act="<font color='red'><i>not yet paid</i></font>";
				}
			}
			else
			{
				$act="<a href='#' onclick='return print(\"$idn2\",\"$req\",\"$remark\",\"$tglct\")' title='cetak nota'><img src='images/document-excel.png'></A>";
				$act.="<button title='Sync Payment' onclick='sync_payment(\"$req\",\"$idn2\")'><img width=\"15\" height=\"15\" src='images/sync.png'></button>";
			}	
			$vv=$row[VESSEL].' '.$row[VOYAGE_IN].' - '.$row[VOYAGE_OUT];
			$responce->rows[$i]['id']=$row[ID_PROFORMA];
			$responce->rows[$i]['cell']=array($act,$row[ID_PROFORMA],$row[ID_NOTA],$row[NO_FAKTUR],$row[ID_REQ],$row[EMKL],$row[KET],$vv,$row[TGL_SIMPAN],$row[TGL_PAYMENT],$row[PAYMENT_VIA],$row[TOTAL]);
		}
		else if($q == 'l_transhipment') 
		{
			$vv=$row[VESSEL_OLD].' / '.$row[VOYAGE_IN_OLD].'-'.$row[VOYAGE_OUT_OLD].'<br>'.$row[NO_UKK_OLD];
			$vv2=$row[VESSEL_NEW].' - '.$row[VOYAGE_IN_NEW].'-'.$row[VOYAGE_OUT_NEW].'<br>'.$row[NO_UKK_NEW];
			if($row[STATUS]=='N' || $row[STATUS]=='S')
			{
				//belum disave
				$act = "<a href=".HOME."request.transhipment/edit_req?no_req=".$row[ID_REQ]."><img src='images/edit.png' title='edit request'></a>&nbsp;&nbsp;<a onclick='confirm_no_charge(\"".$row[ID_REQ]."\")'><img border='0' height='15' src='images/save.png' title='save request'></a>";
			}
			else
			{
				//save pre performance
				$act ="<font color='red'><blink><b>sudah disave</b></blink></font>";
			}
			
			$responce->rows[$i]['id']=$row[ID_REQ];			
			$responce->rows[$i]['cell']=array($act,$row[ID_REQ],$row[NO_BC_12],$row[NO_SURAT_TRS],$row[SHIPPING_LINE],$vv,$vv2,$row[JML_CONT]);
		}
		else if($q == 'l_reexport') 
		{	
			$vv=$row[VESSEL_OLD].' / '.$row[VOYAGE_IN_OLD].' - '.$row[VOYAGE_OUT_OLD].'<br>'.$row[NO_UKK_OLD];
			$vv2=$row[VESSEL_NEW].' / '.$row[VOYAGE_IN_NEW].' - '.$row[VOYAGE_OUT_NEW].'<br>'.$row[NO_UKK_NEW];
			if($row[STATUS]=='N' || $row[STATUS]=='S')
			{
				//belum disave
				$act = "<a href=".HOME."request.reexport/edit_req?no_req=".$row[ID_REQ]."><img src='images/edit.png' title='edit request'></a>&nbsp;&nbsp;<a onclick='confirm_no_charge(\"".$row[ID_REQ]."\")'><img border='0' height='15' src='images/save.png' title='save request'></a>";
			}
			else
			{
				//save pre performance
				$act ="<font color='red'><blink><b>sudah disave</b></blink></font>";
			}
			
			$responce->rows[$i]['id']=$row[ID_REQ];			
			$responce->rows[$i]['cell']=array($act,$row[ID_REQ],$row[NO_BC_12],$row[NO_SURAT_TRS],$row[SHIPPING_LINE],$vv,$vv2,$row[JML_CONT]);
		}
		else if($q == 'print_hico') 
		{
			
			if($row[STATUS]=="P")
			{
				$aksi = "<a href=".HOME."print.hicoprint.cetak/?pl=".$row[ID_NOTA]." target='blank'><img src='images/printer.png' title='print behandle card'></a>";
				
			}
			else
			{
				$aksi = "<b><font color='red'>nota belum dibayar</font></b>";
			}
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_REQUEST],$row[ID_NOTA],$row[EMKL],$row[VESSEL].'/'.$row[VOYAGE],$row[JUMLAH_CONT]);
		}
		else if ($q=='p_rec')
		{
			if(($row[STATUS]=="P")||($row[STATUS]=="T")) //means Saved Invoice
			{
				$aksi = "<a href=".HOME."print.receiving_card.print_rec/print?no_req=".$row[ID_REQ]."><img src='images/printer.png' title='edit request'></a>";	
				
			}
			else
			{
				$aksi = "<blink><font color='red'><b><i>Request <br>belum dibayar</i></b></font></blink>";	
			}
			$vesv=$row[VESSEL].' - '.$row[VOYAGE];
			$responce->rows[$i]['id']=$row[ID_REQ];
			$responce->rows[$i]['cell']=array($aksi,$row[STATUS],$row[ID_REQ],$row[PEB],$row[NPE],$row[NO_UKK], $row[TGL_STACK], $row[TGL_MUAT],$row[EMKL],$vesv,$row[JUMLAH_CONT]);
		}
			
		$i++;
	}
	echo json_encode($responce);
}
?>