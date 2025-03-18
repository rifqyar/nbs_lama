<?php
$q = $_GET['q'];
$idreqx = $_GET['idreqx'];
$list_det_ukk = $_GET['list_det_ukk'];
$no_ukks = $_GET['no_ukks'];
$no_bundle = $_GET['no_bundle'];
$id_group = $_SESSION["ID_GROUP"];
if(isset($q)) {
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:'id_bprp'; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	
	if(!$sidx) $sidx =1;
	$db = getDB();
	
	if($q=='pranota_bprp') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM OG_NOTA_BPRPH WHERE STATUS!='X')";
	}
	else if($q=='kurs')
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TR_KURS)";
	else if($q=='nota_bm')
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM OG_HNOTA WHERE status!='X')";
	else if($q=='nota_dk')
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM OG_HNOTA2 WHERE status!='X')";
	else if($q=='l_delivery')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM REQ_DELIVERY_H WHERE TIPE_REQ = 'NEW' and status<>'P')";
	else if($q=='nota_delivery')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM NOTA_DELIVERY_H )";
	else if($q=='print_delivery')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM REQ_DELIVERY_H )";
	else if($q=='l_delivery_p')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM REQ_DELIVERY_H WHERE TIPE_REQ = 'NEW' OR TIPE_REQ = 'EXT')";
	else if($q=='l_delivery_p2')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM  REQ_DELIVERY_H WHERE ID_REQ='$idreqx'";
	else if($q=='nota_delivery_p')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM NOTA_DELIVERY_H )";
	else if($q=='l_batalmuat_p')		
		$query ="SELECT SUM(NUMBER_OF_ROWS) AS NUMBER_OF_ROWS FROM(
			SELECT COUNT(*) AS NUMBER_OF_ROWS from req_delivery_h where TIPE_REQ='EXT' AND OLD_REQ LIKE 'BM%'
				UNION
			SELECT COUNT(*) AS NUMBER_OF_ROWS FROM TB_BATALMUAT_H WHERE JENIS='D'
		)";
	else if($q=='print_delivery_p')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM REQ_DELIVERY_H )";
	else if($q=='pranota_lolo')
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM OG_NOTA_LOLOH WHERE STATUS!='X')";
	else if($q=='l_receiving')
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM REQ_RECEIVING_H)";
	else if($q=='monitoring_yard')
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM REPORT_YARD_VESSEL_D WHERE TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD_VESSEL_D))";
	else if($q=='nota_receiving')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM NOTA_RECEIVING_H )";
	else if($q=='nota_receiving_tn')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM NOTA_RECEIVING_H )";
	else if($q=='print_stacking_card')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM NOTA_RECEIVING_H)";
	else if($q=='l_behandle')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM BH_REQUEST)";
	else if($q=='nota_behandle')
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM BH_NOTA)";
	else if($q=='nota_transhipment')
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM NOTA_TRANSHIPMENT_H)";
	else if($q=='nota_reexport')
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM NOTA_REEXPORT_H)";
	else if($q=='nota_hicoscan')
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM BH_NOTA)";
	else if($q=='nota_exmo')
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM EXMO_NOTA)";
	else if($q=='diskon_lolo')	
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM DISKON_NOTA_DEL_H )";
	else if($q=='print_behandle')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM BH_NOTA)";
	else if($q=='print_exmo')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM EXMO_NOTA)";
	else if($q=='print_batmuak')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM NOTA_BATALMUAT_H)";
	else if($q=='l_vessel')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TR_VESSEL_SCHEDULE_ICT)";
	else if($q=='booking')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_H)";
	else if($q=='rbm')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM M_VSB_VOYAGE@dbint_link)";
	else if($q=='vs_fix'){
		//$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TR_VESSEL_SCHEDULE_ICT)";
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM m_vsb_voyage)";
		$db = getDB('dbint');
	}
	else if($q=='m_rbm')		
		//$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TR_VESSEL_SCHEDULE_ICT)";
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_H)";
	else if($q=='bm')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_H)";
	else if($q=='hatch')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_H)";
	else if($q=='bapli')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TR_VESSEL_SCHEDULE_ICT)";
	else if($q=='stowage_final')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TR_VESSEL_SCHEDULE_ICT)";
	else if($q=='list_detail_rbm')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_LIST)";
	else if($q=='bundle')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM RBM_LIST WHERE BUNDLE IS NOT NULL AND NO_UKK = '$no_ukks'";
	else if($q=='edit_bundle')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM RBM_LIST WHERE BUNDLE = '$no_bundle' AND NO_UKK = '$no_ukks'";
	else if($q=='shifting_og')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_SHIFT WHERE NO_UKK='$no_ukks')";
	else if($q=='hatch_mv')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_HM WHERE NO_UKK='$no_ukks')";
	else if($q=='vessel_schedule')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM RBM_H WHERE ROWNUM <=20 ORDER BY TGL_JAM_TIBA DESC";
	else if($q=='yor_daily')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM YD_BLOCKING_AREA B WHERE B.ID_YARD_AREA=81 AND B.TIER != 0";
	else if($q=='l_batmuat_alihkapal')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM YD_BLOCKING_AREA B WHERE B.ID_YARD_AREA=81 AND B.TIER != 0";
	else if($q=='l_batmuatak')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TB_BATALMUAT_H WHERE JENIS='A' OR JENIS = 'B')";
	else if($q=='l_batmuatdev')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TB_BATALMUAT_H WHERE JENIS='D')";
	else if($q=='nota_batalmuatak')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TB_BATALMUAT_H WHERE JENIS = 'A' OR JENIS = 'B')";
	else if($q=='nota_batalmuatd')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TB_BATALMUAT_H WHERE JENIS = 'D')";
	else if($q=='l_renamecontainer')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RENAME_CONTAINER)";
	else if($q=='nota_rename_container')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RENAME_CONTAINER WHERE BIAYA = 'Y')";
	else if($q=='l_backdate')		
		$query ="SELECT COUNT(1) AS NUMBER_OF_ROWS FROM TB_KOREKSI_NOTA A LEFT JOIN TB_USER B ON A.ID_USER=B.ID";
	else if($q=='l_tarif')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM MASTER_TARIF_CONT A JOIN MASTER_BARANG B ON A.ID_CONT=B.KODE_BARANG)";
	else if($q=='nota_reekspor')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM REQ_REEKSPOR_H)";
	else if($q=='nota_stackext')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM REQ_STACKEXT_H)";
	//print_r($query);die;
	/*if($q=='rbm') 
	{
		$db=getDB('dbint');;
	}
	*/
	$res = $db->query($query)->fetchRow();
	$count = $res[NUMBER_OF_ROWS];
	
	/*oci_define_by_name($query, 'NUMBER_OF_ROWS', $count);
	oci_execute($query);
	oci_fetch($query);*/

	if( $count >0 ) {
		$total_pages = ceil($count/$limit);
	}
	else { 
		$total_pages = 0;
	}
	if ($page > $total_pages) $page=$total_pages;
	$start = $limit*$page - $limit; // do not put $limit*($page - 1)	

	$responce->page = $page;
	$responce->total = $total_pages;
	$responce->records = $count;
	$i=0;
	if($q=='pranota_bprp') //ambil data header
		$query="SELECT A.*, B.*, OG_GETNAMA.GET_PERDAGANGAN(B.JN_DAGANG) PERDAGANGAN FROM OG_NOTA_BPRPH A, OG_BPRPH B WHERE A.STATUS!='X' AND A.ID_BPRP=B.ID_BPRP ORDER BY ID_NOTA DESC";
	else if($q=='kurs')
		$query="SELECT * FROM TR_KURS ORDER BY START_DATE DESC";
	else if($q=='nota_bm')
		$query="SELECT * FROM OG_HNOTA WHERE status!='X' ORDER BY rta DESC";
	else if($q=='nota_dk')
		$query="SELECT * FROM OG_HNOTA2 WHERE status!='X' ORDER BY rta DESC";
	else if($q=='l_delivery') //ambil data header
		$query="SELECT a.ID_REQ,
                     a.EMKL AS EMKL, A.TGL_REQUEST AS TGL,
                     a.VESSEL,
                     a.VOYAGE_IN ||' - '|| A.VOYAGE_OUT  AS VOYAGE, a.STATUS, a.QTY
                FROM req_delivery_h a where status<>'P' and TIPE_REQ = 'NEW'
                ORDER BY A.TGL_REQUEST DESC";
	else if($q=='nota_delivery') //ambil data header
		$query="SELECT  DISTINCT (a.ID_REQ),
                     a.EMKL AS EMKL, A.TGL_REQUEST AS TGL,
                     a.VESSEL,
                     a.VOYAGE_IN ||' - ' ||a.VOYAGE_OUT AS VOYAGES,
                     NVL((SELECT COUNT (1) JUMLAH_CONT FROM req_delivery_d c WHERE TRIM(c.NO_REQ_DEV) = TRIM(a.ID_REQ)),0) JUMLAH_CONT,
                     NVL((SELECT ID_NOTA FROM nota_delivery_h d WHERE TRIM(d.ID_REQ) = TRIM(a.ID_REQ)),'-') ID_NOTA,
                     d.STATUS,
                     a.TIPE_REQ
                FROM req_delivery_h a left join nota_delivery_h d on a.ID_REQ = d.ID_REQ
                ORDER BY A.TGL_REQUEST DESC";
	else if($q=='l_delivery_p') //ambil data header
		$query="SELECT * FROM (SELECT  A.ID_REQ, OLD_REQ, 
                     a.EMKL AS EMKL, A.TGL_REQUEST AS TGL,
                     a.VESSEL,
                     a.VOYAGE_IN||' - '||a.VOYAGE_OUT AS VOYAGES , a.STATUS,
                     a.QTY JUMLAH_CONT,
                     a.SP2P_KE, a.TIPE_REQ
                FROM req_delivery_h a
				ORDER BY A.TGL_REQUEST DESC)
				WHERE ROWNUM <= 2000";
	else if($q=='l_delivery_p2') //ambil data header
		$query="SELECT * FROM (SELECT  A.ID_REQ, OLD_REQ, 
                     a.EMKL AS EMKL, A.TGL_REQUEST AS TGL,
                     a.VESSEL,
                     a.VOYAGE_IN||' - '||a.VOYAGE_OUT AS VOYAGES , a.STATUS,
                     a.QTY JUMLAH_CONT,
                     a.SP2P_KE, a.TIPE_REQ
                FROM req_delivery_h a where ID_REQ='$idreqx')
				";
			else if($q=='l_batalmuat_p') //ambil data header
		$query="SELECT *
				  FROM (  SELECT *
							FROM (SELECT A.ID_REQ,
										 OLD_REQ,
										 a.EMKL AS EMKL,
										 A.TGL_REQUEST AS TGL,
										 a.VESSEL,
										 a.VOYAGE_IN || ' - ' || a.VOYAGE_OUT AS VOYAGES,
										 a.STATUS,
										 a.QTY JUMLAH_CONT,
										 a.SP2P_KE,
										 a.TIPE_REQ
									FROM req_delivery_h a
								   WHERE TIPE_REQ = 'EXT' AND OLD_REQ LIKE 'BM%'
								  UNION
								  SELECT B.ID_BATALMUAT ID_REQ,
										 '' OLD_REQ,
										 B.EMKL AS EMKL,
										 B.TGL_REQ AS TGL,
										 B.VESSEL,
										 B.VOYAGE || ' - ' || B.VOYAGE_OUT AS VOYAGES,
										 B.STATUS,
										 0 JUMLAH_CONT,
										 0 SP2P_KE,
										 'NEW' TIPE_REQ
									FROM TB_BATALMUAT_H B
								   WHERE B.JENIS = 'D')
						ORDER BY TGL DESC)
				 WHERE ROWNUM <= 250";
	else if($q=='nota_delivery_p') //ambil data header
		$query="SELECT  DISTINCT (a.ID_REQ),
					 a.EMKL AS EMKL, A.TGL_REQUEST AS TGL,
					 c.VESSEL,
					 c.VOYAGE,
					 NVL((SELECT COUNT (1) JUMLAH_CONT FROM req_delivery_d c WHERE TRIM(c.NO_REQ_DEV) = TRIM(a.ID_REQ)),0) JUMLAH_CONT,
					 NVL((SELECT ID_NOTA FROM nota_delivery_h d WHERE TRIM(d.ID_REQ) = TRIM(a.ID_REQ)),'-') ID_NOTA,
					 d.STATUS
				FROM req_delivery_h a left join nota_delivery_h d on a.ID_REQ = d.ID_REQ, req_delivery_d c 
				WHERE TRIM(c.NO_REQ_DEV) = TRIM(a.ID_REQ)
				ORDER BY A.TGL_REQUEST DESC";
	else if($q=='pranota_lolo')	//ambil data header
		$query="SELECT B.ID_BPRP,B.TERMINAL, B.NM_PBM,B.NM_CUST,B.VESSEL,B.VOYAGE,
						B.ETA,B.NM_GUDLAP, A.STATUS,A.ID_NOTA, A.TGL_INVOICE, A.TOTAL, A.NO_JKM FROM OG_NOTA_LOLOH A, OG_BPRPH B WHERE A.STATUS!='X' AND A.ID_BPRP=B.ID_BPRP ORDER BY A.ID_NOTA DESC";
	else if($q=='print_delivery') //ambil data header
	/*	$query="select * from (SELECT A.ID_REQ,C.ID_NOTA,A.EMKL,A.VESSEL,A.VOYAGE_IN ||' - '||A. VOYAGE_OUT AS VOYAGE,
                      a.qty JUMLAH_CONT,C.STATUS 
                FROM REQ_DELIVERY_H A,NOTA_DELIVERY_H C 
                WHERE trim(A.ID_REQ)=trim(C.ID_REQ) 
                ORDER BY A.TGL_REQUEST DESC, A.ID_REQ DESC) where rownum <500";	
	*/
		$query = "SELECT *
						  FROM (  SELECT A.ID_REQ,
										 C.ID_NOTA,
										 A.EMKL,
										 A.VESSEL,
										 A.VOYAGE_IN || ' - ' || A.VOYAGE_OUT AS VOYAGE,
										 (SELECT COUNT(*) FROM REQ_DELIVERY_D WHERE TRIM(NO_REQ_DEV) = TRIM(A.ID_REQ)) JUMLAH_CONT,
										 C.STATUS
									FROM REQ_DELIVERY_H A, NOTA_DELIVERY_H C
								   WHERE TRIM (A.ID_REQ) = TRIM (C.ID_REQ)
								   group by A.ID_REQ,
										 C.ID_NOTA,
										 A.EMKL,
										 A.VESSEL,
										 A.VOYAGE_IN || ' - ' || A.VOYAGE_OUT,
										 C.STATUS,
										 A.TGL_REQUEST
								ORDER BY A.TGL_REQUEST DESC, A.ID_REQ DESC)
						 WHERE ROWNUM < 500";
    else if($q=='print_batmuak') //ambil data header
		$query="SELECT A.ID_BATALMUAT,C.NO_NOTA,A.EMKL,A.VESSEL,A.VOYAGE||' - '||A. VOYAGE_OUT AS VOYAGE,C.STATUS, (SELECT COUNT(1) FROM TB_BATALMUAT_D WHERE ID_BATALMUAT = A.ID_BATALMUAT) JUMLAH_CONT
                FROM TB_BATALMUAT_H A,NOTA_BATALMUAT_H C
                WHERE trim(A.ID_BATALMUAT)=trim(C.ID_BATALMUAT)  AND A.JENIS <> 'D' AND A.STATUS <> 'X'
                ORDER BY A.TGL_REQ DESC, A.ID_BATALMUAT DESC";	
	
	else if($q=='l_receiving') //ambil data header
		$query="select * from (SELECT a.ID_REQ,
                         a.KODE_PBM AS EMKL,
                         a.VESSEL,
                         a.VOYAGE,
						 NVL((SELECT COUNT (1) JUMLAH_CONT FROM req_receiving_d c WHERE c.NO_REQ_ANNE = a.ID_REQ),0) JUMLAH_CONT,
						 a.STATUS, a.PEB,a.NPE,a.TGL_STACK, a.TGL_MUAT,a.NO_UKK,a.IPOD
                    FROM req_receiving_h a
                   order by a.ID_REQ DESC) where rownum <=300";
	else if($q=='monitoring_yard') //ambil data header
		$query="SELECT * FROM REPORT_YARD_VESSEL_D WHERE TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD_VESSEL_D)";
	else if($q=='nota_receiving') //ambil data header
		$query="select * from (SELECT b.ID_NOTA, a.ID_REQ, a.KODE_PBM, a.VESSEL ,a.VOYAGE, (SELECT count(c.NO_CONTAINER) from req_receiving_d c WHERE c.NO_REQ_ANNE= a.ID_REQ) JML_CONT,b.STATUS
                    from req_receiving_h a left join nota_receiving_h b on a.ID_REQ=b.ID_REQ and b.status!='X' order by ID_REQ DESC) where rownum<=300
				";
	else if($q=='nota_receiving_tn') //ambil data header
		$query="select * from (SELECT b.ID_NOTA, a.ID_REQ, a.KODE_PBM, a.VESSEL ,a.VOYAGE, (SELECT count(c.NO_CONTAINER) from req_receiving_d c WHERE c.NO_REQ_ANNE= a.ID_REQ) JML_CONT,b.STATUS
                    from req_receiving_h a left join nota_receiving_h b on a.ID_REQ=b.ID_REQ and b.status!='X' order by ID_REQ DESC) where rownum<=300
				";
	else if($q=='print_stacking_card') //ambil data header
		$query="SELECT *
                  FROM (  SELECT ID_REQ,
                                 ID_NOTA,
                                 EMKL,
                                 a.VESSEL,
                                 a.VOYAGE_IN,
                                 STATUS,
                                 a.VOYAGE_OUT,
                                 a.TGL_REQ,
                                 COUNT (b.no_container) QTY,
                                 SUBSTR(A.ID_NOTA,6) CEK_NOTA
                            FROM nota_receiving_h a left join req_receiving_d b
                            ON  trim(a.id_req) = trim(b.no_req_anne) AND trim(a.id_req) is not null
                            GROUP BY ID_REQ,
                                 ID_NOTA,
                                 EMKL,
                                 a.VESSEL,
                                 a.VOYAGE_IN,
                                 STATUS,
                                 a.VOYAGE_OUT,
                                 a.TGL_REQ
                        ORDER BY SUBSTR(A.ID_NOTA,6) DESC)
                 WHERE TGL_REQ BETWEEN SYSDATE-2 AND SYSDATE  ";
	else if($q=='l_behandle') //ambil data header
		$query="SELECT A.ID_REQUEST,A.EMKL,A.TGL_REQUEST,A.VESSEL,A.VOYAGE,A.NOMOR_INSTRUKSI,(SELECT COUNT(B.NO_CONTAINER) FROM BH_DETAIL_REQUEST B WHERE A.ID_REQUEST=B.ID_REQUEST ) JUMLAH_CONT,A.STATUS FROM BH_REQUEST A ORDER BY A.TGL_REQUEST DESC";
	else if($q=='nota_behandle') //ambil data header
		$query="SELECT A.ID_REQUEST,C.ID_NOTA,A.EMKL,A.VESSEL,A.VOYAGE,(SELECT COUNT(B.NO_CONTAINER) FROM BH_DETAIL_REQUEST B WHERE A.ID_REQUEST=B.ID_REQUEST) JUMLAH_CONT,C.STATUS FROM BH_REQUEST A left join BH_NOTA C on A.ID_REQUEST=C.ID_REQUEST and C.STATUS!='X' ORDER BY A.TGL_REQUEST DESC";
	else if($q=='nota_transhipment') //ambil data header
		$query="SELECT A.ID_REQ,C.ID_NOTA,A.SHIPPING_LINE,A.VESSEL,A.VOYAGE,A.VESSEL_DT,A.VOYAGE_DT,(SELECT COUNT(B.NO_CONTAINER) FROM REQ_TRANSHIPMENT_D B WHERE A.ID_REQ=B.ID_REQ) JUMLAH_CONT,C.STATUS FROM REQ_TRANSHIPMENT_H A left join NOTA_TRANSHIPMENT_H C on A.ID_REQ=C.ID_REQUEST and C.STATUS!='X' ORDER BY A.TGL_REQUEST DESC";
	else if($q=='nota_reexport') //ambil data header
		$query="SELECT A.ID_REQ,C.ID_NOTA,A.SHIPPING_LINE,A.VESSEL,A.VOYAGE,A.VESSEL_DT,A.VOYAGE_DT,(SELECT COUNT(B.NO_CONTAINER) FROM REQ_REEXPORT_D B WHERE A.ID_REQ=B.ID_REQ) JUMLAH_CONT,C.STATUS FROM REQ_REEXPORT_H A left join NOTA_REEXPORT_H C on A.ID_REQ=C.ID_REQUEST and C.STATUS!='X' ORDER BY A.TGL_REQUEST DESC";
	else if($q=='nota_hicoscan') //ambil data header
		$query="SELECT A.ID_REQUEST,NVL(C.ID_NOTA,'nota belum tergenerate') ID_NOTA,A.EMKL,A.VESSEL,A.VOYAGE,(SELECT COUNT(B.NO_CONTAINER) FROM REQ_HICOSCAN_D B WHERE A.ID_REQUEST=B.ID_REQUEST) JUMLAH_CONT,C.STATUS FROM REQ_HICOSCAN A left join NOTA_HICOSCAN_H C  on  A.ID_REQUEST=C.ID_REQUEST ORDER BY A.TGL_REQUEST DESC";
	else if($q=='nota_exmo') //ambil data header
		$query="SELECT A.ID_REQUEST,C.ID_NOTA,C.TGL_NOTA,A.EMKL,(SELECT COUNT(B.NO_CONTAINER) FROM EXMO_DETAIL_REQUEST B WHERE A.ID_REQUEST=B.ID_REQUEST) JUMLAH_CONT,C.STATUS FROM EXMO_REQUEST A left join EXMO_NOTA C on A.ID_REQUEST=C.ID_REQUEST and C.STATUS!='X' ORDER BY A.TGL_REQ DESC";
	else if($q=='print_behandle') //ambil data header
		$query="SELECT A.ID_REQUEST,C.ID_NOTA,A.EMKL,A.VESSEL,A.VOYAGE,(SELECT COUNT(B.NO_CONTAINER) FROM BH_DETAIL_REQUEST B WHERE A.ID_REQUEST=B.ID_REQUEST) JUMLAH_CONT,C.STATUS FROM BH_REQUEST A,BH_NOTA C  WHERE A.ID_REQUEST=C.ID_REQUEST ORDER BY A.TGL_REQUEST DESC";	
	else if($q=='print_batmuak') //ambil data header
		$query="SELECT A.ID_BATALMUAT,
         C.NO_NOTA,
         A.EMKL,
         A.VESSEL,
         A.VOYAGE,
         (SELECT COUNT (B.NO_CONTAINER)
            FROM TB_BATALMUAT_D B
           WHERE A.ID_BATALMUAT = B.ID_BATALMUAT)
            JUMLAH_CONT,
         C.STATUS
    FROM TB_BATALMUAT_H A, NOTA_BATALMUAT_H C
   WHERE A.ID_BATALMUAT = C.ID_BATALMUAT AND A.JENIS <> 'D' AND A.STATUS <> 'X'
ORDER BY A.TGL_REQ DESC";		
	else if($q=='print_exmo') //ambil data header
		$query="SELECT A.ID_REQUEST,C.ID_NOTA,A.EMKL,A.NOMOR_INSTRUKSI,(SELECT COUNT(B.NO_CONTAINER) FROM BH_DETAIL_REQUEST B WHERE A.ID_REQUEST=B.ID_REQUEST) JUMLAH_CONT,C.STATUS FROM EXMO_REQUEST A,EXMO_NOTA C  WHERE A.ID_REQUEST=C.ID_REQUEST ORDER BY A.TGL_REQ DESC";	
	else if($q=='l_vessel') //ambil data header
		$query="SELECT a.ID_VS, b.NAMA_VESSEL , a.VOYAGE, a.ETA, a.ETD, a.RTA, a.RTD, a.STATUS FROM VESSEL_SCHEDULE a, MASTER_VESSEL b WHERE a.ID_VES = b.KODE_KAPAL";
	else if($q=='stowage_final') //ambil data header
		$query="SELECT a.ID_VS, b.NAMA_VESSEL , a.VOYAGE, a.ETA, a.ETD, a.RTA, a.RTD, a.STATUS FROM VESSEL_SCHEDULE a, MASTER_VESSEL b WHERE a.ID_VES = b.KODE_KAPAL";
	else if($q=='booking') //ambil data header
		$query="SELECT NO_UKK ID_VS, NM_KAPAL NAMA_VESSEL , 
VOYAGE_IN ,VOYAGE_OUT, to_char(TGL_JAM_TIBA,'DD MON YYYY HH24:Mi') ETA, 
to_char(TGL_JAM_BERANGKAT,'DD MON YYYY HH24:Mi') ETD, NM_PELABUHAN_ASAL, 
NM_PELABUHAN_TUJUAN, STATUS, EMAIL, to_char(OPEN_STACK,'DD MON YYYY HH24:Mi') OPEN_STACK, to_char(CLOSING_TIME,'DD MON YYYY HH24:Mi') CLOSING_TIME,
to_char(CLOSING_TIME_DOC,'DD MON YYYY HH24:Mi') CLOSING_TIME_DOC FROM RBM_H ORDER BY DATE_INSERT DESC";
	else if($q=='rbm'){ //ambil data header
		/*$query="select ID_VSB_VOYAGE AS ID_VES_SCD,VESSEL, VOYAGE_IN, VOYAGE_OUT, CALL_SIGN, OPERATOR_ID, OPERATOR_NAME, POD, POL, to_char(to_date(ATA,'yyyymmddhh24miss'),'dd-mm-yyyy hh24:mi') ATA,to_char(to_date(ATD,'yyyymmddhh24miss'),'dd-mm-yyyy hh24:mi') ATD,
				(select count(1) from M_STEVEDORING@dbint_link B WHERE A.VESSEL=B.VESSEL AND A.VOYAGE_IN=B.VOYAGE_IN AND B.E_I='E') AS LQ,
				(select count(1) from M_STEVEDORING@dbint_link B WHERE A.VESSEL=B.VESSEL AND A.VOYAGE_IN=B.VOYAGE_IN AND B.E_I='I') AS DQ, RBM_DLD
		from m_vsb_voyage@dbint_link A ORDER BY A.ID_VSB_VOYAGE DESC";
		*/
		// add shifting
		if ($_POST['sidx'] != '') {
			$sort = "ORDER BY ".$_POST['sidx']." ".$_POST['sord'];
			# code...
		}
		else{
			$sort = " ORDER BY A.ID_VSB_VOYAGE DESC";
		}
		
		$query= "  SELECT ID_VSB_VOYAGE AS ID_VES_SCD,
						 VESSEL,
						 VOYAGE_IN,
						 VOYAGE_OUT,
						 CALL_SIGN,
						 OPERATOR_ID,
						 OPERATOR_NAME,
						 POD,
						 POL,
						 TO_DATE (ATA, 'yyyymmddhh24miss') ATA1,
						 TO_DATE (ATD, 'yyyymmddhh24miss') ATD1,
						 TO_CHAR (TO_DATE (ATA, 'yyyymmddhh24miss'), 'dd-mm-yyyy hh24:mi') ATA,
						 TO_CHAR (TO_DATE (ATD, 'yyyymmddhh24miss'), 'dd-mm-yyyy hh24:mi') ATD,
						 (SELECT COUNT (1)
							FROM M_STEVEDORING@dbint_link B
						   WHERE     A.VESSEL = B.VESSEL
								 AND A.VOYAGE_IN = B.VOYAGE_IN
								 AND B.E_I = 'E')
							AS LQ,
						 (SELECT COUNT (1)
							FROM M_STEVEDORING@dbint_link B
						   WHERE     A.VESSEL = B.VESSEL
								 AND A.VOYAGE_IN = B.VOYAGE_IN
								 AND B.E_I = 'I')
							AS DQ,
						 RBM_DLD,
						 	 (SELECT COUNT (1)
							FROM M_STEVEDORING@dbint_link B
						   WHERE     A.VESSEL = B.VESSEL
								 AND A.VOYAGE_IN = B.VOYAGE_IN
								 AND B.E_I = 'T')
							AS TR,
						 (SELECT COUNT(1) FROM M_SHIFTING@dbint_link b WHERE b.VESSEL = A.VESSEL AND b.VOYAGE_IN = A.VOYAGE_IN AND b.VOYAGE_OUT = A.VOYAGE_OUT) SHIFT
					FROM m_vsb_voyage@dbint_link A
				 ".$sort;
				//echo $query; die();
	}
	else if($q=='vs_fix') //ambil data header
		$query="SELECT ID_VSB_VOYAGE ID_VS,(SELECT KETERANGAN FROM MASTER_JENIS_KAPAL WHERE ID_JK=JENIS_KAPAL) AS JENIS_KAPAL2,NM_KAPAL NAMA_VESSEL , VOYAGE_IN ,VOYAGE_OUT, to_char(TGL_JAM_TIBA,'DD MON YYYY HH24:Mi') ETA, to_char(TGL_JAM_BERANGKAT,'DD MON YYYY HH24:Mi') ETD, NM_PELABUHAN_ASAL, NM_PELABUHAN_TUJUAN,to_char(RTA,'DD MON YYYY HH24:Mi') RTA,to_char(RTD,'DD MON YYYY HH24:Mi') RTD, STATUS FROM m_vsb_voyage ORDER BY id_vsb_voyage desc";
	else if($q=='m_rbm') //ambil data header
		$query="
				SELECT A.EMAIL,A.TGL_FINAL,A.NO_UKK ID_VS, A.NM_KAPAL NAMA_VESSEL , A.VOYAGE_IN ,A.VOYAGE_OUT, A.TGL_JAM_TIBA ETA, A.TGL_JAM_BERANGKAT ETD, A.NM_PELABUHAN_ASAL, 
                A.NM_PELABUHAN_TUJUAN, A.STATUS, 
                A.EMAIL, A.JML_BONGKAR, A.JML_MUAT, A.JML_SHIFT, A.JML_HATCH, 
                A.PRANOTA, A.KOREKSI, 
                (SELECT C.TGL_KIRIM from LOG_EMAIL C WHERE C.ID_EMAIL=(SELECT MAX(B.ID_EMAIL) FROM LOG_EMAIL B WHERE B.NO_UKK=A.NO_UKK AND B.MODUL='RBM')
                AND C.NO_UKK=A.NO_UKK
                ) TGL_EMAIL,
                (SELECT COUNT(1) FROM LOG_EMAIL D WHERE D.NO_UKK=A.NO_UKK AND D.MODUL='RBM') JML_EMAIL,
                (SELECT C.TGL_UPDATE from LOG_RBM C WHERE C.ID_LOG_RBM=(SELECT MAX(B.ID_LOG_RBM) FROM LOG_RBM B WHERE B.NO_UKK=A.NO_UKK AND B.KETERANGAN='PRANOTA')
                AND C.NO_UKK=A.NO_UKK
                ) TGL_PRN, A.NOTA, A.TGL_SAVE_NOTA
                FROM RBM_H A
				";
	else if($q=='bm') //ambil data header
		$query="SELECT NO_UKK ID_VS,CASE WHEN (select count(DISTINCT(OI)) from isws_list_container b where trim(b.NO_UKK)=trim(a.NO_UKK))=1 THEN (select MAX(OI) from isws_list_container b where trim(b.NO_UKK)=trim(a.NO_UKK))
when (select count(DISTINCT(OI)) from isws_list_container b where b.NO_UKK=a.NO_UKK )=2 then 'X' end as  OI,
(select NO_PRANOTA from rbm_h_pr where NO_UKK=a.NO_UKK AND OI='O') NO_PR_O,
(select NO_PRANOTA from rbm_h_pr where NO_UKK=a.NO_UKK AND OI='I') NO_PR_I,
(select STATUS from rbm_h_pr where NO_UKK=a.NO_UKK AND OI='I') STATUSI,
(select STATUS from rbm_h_pr where NO_UKK=a.NO_UKK AND OI='O') STATUSO,
(select (select NO_NOTA from nota_bm c where b.NO_PRANOTA=c.NO_PRANOTA and c.STATUS_NOTA<>'X') from rbm_h_pr b where b.NO_UKK=a.NO_UKK AND OI='I') NOTAI,
(select (select NO_NOTA from nota_bm c where b.NO_PRANOTA=c.NO_PRANOTA and c.STATUS_NOTA<>'X') from rbm_h_pr b where b.NO_UKK=a.NO_UKK AND OI='O') NOTAO,
(select (select TGL_RECEIPT from nota_bm c where b.NO_PRANOTA=c.NO_PRANOTA and c.STATUS_NOTA<>'X') from rbm_h_pr b where b.NO_UKK=a.NO_UKK AND OI='I') TGL_RI,
(select (select TGL_RECEIPT from nota_bm c where b.NO_PRANOTA=c.NO_PRANOTA and c.STATUS_NOTA<>'X') from rbm_h_pr b where b.NO_UKK=a.NO_UKK AND OI='O') TGL_RO,
(select (select STATUS_TRANSFER from nota_bm c where b.NO_PRANOTA=c.NO_PRANOTA and c.STATUS_NOTA<>'X') from rbm_h_pr b where b.NO_UKK=a.NO_UKK AND OI='I') ST_RI,
(select (select STATUS_TRANSFER from nota_bm c where b.NO_PRANOTA=c.NO_PRANOTA and c.STATUS_NOTA<>'X') from rbm_h_pr b where b.NO_UKK=a.NO_UKK AND OI='O') ST_RO,
NM_KAPAL NAMA_VESSEL , 
VOYAGE_IN ,VOYAGE_OUT, TGL_JAM_TIBA ETA, TGL_JAM_BERANGKAT ETD,NM_PEMILIK, 
NM_PELABUHAN_ASAL, NM_PELABUHAN_TUJUAN, STATUS, EMAIL FROM RBM_H a ORDER BY DATE_INSERT DESC";
	else if($q=='hatch') //ambil data header
		$query="SELECT NO_UKK ID_VS, NM_KAPAL NAMA_VESSEL , VOYAGE_IN ,VOYAGE_OUT, TGL_JAM_TIBA ETA, TGL_JAM_BERANGKAT ETD, NM_PELABUHAN_ASAL, NM_PELABUHAN_TUJUAN FROM RBM_H";
	///print_r($query);die;
	else if($q=='bapli') //ambil data header
		$query="SELECT NO_UKK ID_VS, NM_KAPAL NAMA_VESSEL , VOYAGE_IN ,VOYAGE_OUT, TGL_JAM_TIBA ETA, TGL_JAM_BERANGKAT ETD, NM_PELABUHAN_ASAL, NM_PELABUHAN_TUJUAN FROM TR_VESSEL_SCHEDULE_ICT";
	else if($q=='list_detail_rbm') //ambil data header
		$query="SELECT NO_UKK , NO_CONTAINER , SIZE_CONT, TYPE_CONT, STATUS_CONT, HEIGHT_CONT, GROSS, E_I, HZ, TRS,
					   CASE WHEN REMARK='BM_AFT_GATI'  THEN 'Batal Muat After Getin' 
							WHEN REMARK='BM_BEF_GATI'  THEN 'Batal Muat Before Getin'
							WHEN REMARK='AIU'  THEN 'ANNE invoice unreleased'
							WHEN REMARK='TRANS'  THEN 'Transhipment'
							WHEN REMARK='RE_EKS'  THEN 'Re-eksport'
							WHEN REMARK='BUND'  THEN 'Bundle'							
					   END REMARKS
					FROM RBM_LIST WHERE NO_UKK= '$list_det_ukk'";
	else if($q=='bundle') //ambil data header
		$query="SELECT NO_UKK , NO_CONTAINER , SIZE_CONT, TYPE_CONT, STATUS_CONT, HEIGHT_CONT, E_I, HZ, BUNDLE,DASAR_BUNDLE FROM RBM_LIST WHERE BUNDLE IS NOT NULL AND NO_UKK = '$no_ukks' ORDER BY BUNDLE DESC, DASAR_BUNDLE ASC";
	else if($q=='edit_bundle') //ambil data header
		$query="SELECT NO_UKK ,NO_CONTAINER, BUNDLE,DASAR_BUNDLE FROM RBM_LIST WHERE BUNDLE ='$no_bundle' AND NO_UKK = '$no_ukks' ORDER BY BUNDLE DESC, DASAR_BUNDLE ASC";
	else if($q=='shifting_og') //ambil data header
		$query="SELECT NO_UKK , NO_CONTAINER , SIZE_C, TYPE_C, STATUS_C, HEIGHT_C, EI, HZ, JENIS_SHIFT,JUMLAH_SHIFT, 
				ALAT_SHIFT
				FROM RBM_SHIFT WHERE NO_UKK= '$no_ukks'";
	else if($q=='hatch_mv') //ambil data header == to_date(MOVE_TIME_OPEN,'MM/DD/YYYY HH24:MI')
		$query="SELECT NO_UKK ,BAY,ALAT, ACTIVITY, TO_CHAR(MOVE_TIME_OPEN,'dd mm rrrr  hh24:ii:ss') MOVE_TIME_OPEN, TO_CHAR(MOVE_TIME_CLOSE,'dd mm rrrr  hh24:ii:ss') MOVE_TIME_CLOSE, OI, OPERATOR, JUMLAH FROM RBM_HM WHERE NO_UKK= '$no_ukks'";
	else if($q=='vessel_schedule')
		$query="select NM_KAPAL, VOYAGE_IN, VOYAGE_OUT, TGL_JAM_TIBA as TGL_JAM_TIBA1, to_char(TGL_JAM_TIBA, 'DD-MM-YYYY HH24:MI') TGL_JAM_TIBA, to_char(TGL_JAM_BERANGKAT, 'DD-MM-YYYY HH24:MI') TGL_JAM_BERANGKAT, to_char(BERTHING, 'DD-MM-YYYY HH24:MI') BERTHING, to_char(END_WORK, 'DD-MM-YYYY HH24:MI') END_WORK, '' AS REMARKS FROM RBM_H WHERE TGL_JAM_TIBA IS NOT NULL ORDER BY TGL_JAM_TIBA1 DESC";
	else if($q=='yor_daily') //ambil data header
		$query="SELECT B.NAME,
         COUNT (DISTINCT (A.SLOT_)) AS SLOT,
         COUNT (DISTINCT (A.ROW_)) AS ROW_CONT,
         B.TIER,
         (COUNT (DISTINCT (A.INDEX_CELL)) * (B.TIER)) AS CAPACITY,
         COUNT (D.ID_CELL) AS USED,
         round(( (COUNT (D.ID_CELL)) / (COUNT (DISTINCT (A.INDEX_CELL)) * B.TIER))
         * 100,2)
            AS YOR,
         COUNT (DISTINCT (D.NO_CONTAINER)) BOX
    FROM YD_BLOCKING_CELL A
         LEFT JOIN YD_PLACEMENT_YARD D
            ON (A.INDEX_CELL = D.ID_CELL)
         INNER JOIN YD_BLOCKING_AREA B
            ON (A.ID_BLOCKING_AREA = B.ID)
         INNER JOIN YD_YARD_AREA C
            ON (B.ID_YARD_AREA = C.ID)
   WHERE NAMA_YARD LIKE 'LAPANGAN 300-305' AND B.TIER != 0
	GROUP BY C.ID,
         B.ID,
         B.NAME,
         B.TIER
	ORDER BY C.ID, B.NAME, B.ID
				";
	else if($q=='l_batmuatak') //ambil data header
		$query="SELECT A.ID_BATALMUAT,
                        A.EMKL,
                        A.TGL_REQ,
                        A.VESSEL,
                        A.VOYAGE,
                        A.JENIS,
                        CASE WHEN TRIM(A.JENIS) = 'A' THEN 'AFTER GATE IN'
                        	 WHEN TRIM(A.JENIS) = 'B' THEN 'BEFORE GATE IN'
                        END JNS_BM,
                        (SELECT COUNT(B.NO_CONTAINER) FROM TB_BATALMUAT_D B WHERE A.ID_BATALMUAT=B.ID_BATALMUAT ) JUMLAH_CONT,
                        A.STATUS 
                FROM TB_BATALMUAT_H A 
                WHERE JENIS = 'A' OR JENIS ='B'
                ORDER BY A.TGL_REQ DESC";
	else if($q=='l_batmuatdev') //ambil data header
		$query="SELECT A.ID_BATALMUAT,
                        A.EMKL,
                        A.TGL_REQ,
                        A.TGL_BERANGKAT2,
                        (SELECT COUNT(B.NO_CONTAINER) FROM TB_BATALMUAT_D B WHERE A.ID_BATALMUAT=B.ID_BATALMUAT ) JUMLAH_CONT,
                        A.STATUS 
                FROM TB_BATALMUAT_H A 
                WHERE JENIS = 'D'
                ORDER BY A.TGL_REQ DESC";
	else if($q=='nota_batalmuatak') //ambil data header
		$query="SELECT A.ID_BATALMUAT,
                        A.EMKL,
                        A.TGL_REQ,
                        A.VESSEL,
                        A.VOYAGE,A.JENIS,
                        case when A.JENIS='A' THEN 'ALIH KAPAL - AFTER GATE IN'
                        when A.JENIS='B' THEN 'ALIH KAPAL - BEFORE GATE IN'
                        when A.JENIS='D' THEN 'DELIVERY'
                        end JENIS_BATMUAT,
                        (SELECT COUNT(B.NO_CONTAINER) FROM TB_BATALMUAT_D B WHERE A.ID_BATALMUAT=B.ID_BATALMUAT ) JUMLAH_CONT,
						NVL((SELECT NO_NOTA FROM nota_batalmuat_h d WHERE TRIM(d.ID_BATALMUAT) = TRIM(a.ID_BATALMUAT) AND STATUS!='X' and rownum = 1),'-') ID_NOTA,
                        A.STATUS 
                FROM TB_BATALMUAT_H A 
                WHERE JENIS = 'A' OR JENIS ='B'
                ORDER BY A.TGL_REQ DESC";
	else if($q=='nota_batalmuatd') //ambil data header
		$query="SELECT A.ID_BATALMUAT,
                        A.EMKL,
                        A.TGL_REQ,
                        (SELECT COUNT(B.NO_CONTAINER) FROM TB_BATALMUAT_D B WHERE A.ID_BATALMUAT=B.ID_BATALMUAT ) JUMLAH_CONT,
						NVL((SELECT NO_NOTA FROM nota_batalmuat_h d WHERE TRIM(d.ID_BATALMUAT) = TRIM(a.ID_BATALMUAT)),'-') ID_NOTA,
                        A.STATUS 
                FROM TB_BATALMUAT_H A 
                WHERE JENIS = 'D'
                ORDER BY A.TGL_REQ DESC";
	else if($q=='l_renamecontainer') //ambil data header
		$query="SELECT * FROM RENAME_CONTAINER ORDER BY TGL_RENAME DESC";
	else if($q=='nota_rename_container') //ambil data header
		$query="SELECT A.NO_RENAME,
                        A.NO_EX_CONTAINER,
						A.NO_CONTAINER,
                        A.VESSEL,
						A.VOYAGE,
						NVL((SELECT ID_NOTA FROM nota_rename_container d WHERE TRIM(d.NO_RENAME) = TRIM(a.NO_RENAME)),'-') ID_NOTA,
                        A.STATUS 
                FROM RENAME_CONTAINER A
                WHERE A.BIAYA = 'Y'
                ORDER BY A.TGL_RENAME DESC";
	else if($q=='l_backdate') //ambil data header
		$query="SELECT A.EX_ID_NOTA, A.ID_REQ, A.TGL_KOREKSI, A.NO_BA, B.NAME, A.KETERANGAN FROM TB_KOREKSI_NOTA A LEFT JOIN TB_USER B ON A.ID_USER=B.ID";
	else if($q=='l_tarif') //ambil data header
		$query="SELECT * FROM MASTER_TARIF_CONT A JOIN MASTER_BARANG B ON A.ID_CONT=B.KODE_BARANG order by JENIS_BIAYA, UKURAN, TYPE, STATUS";
	else if($q=='nota_reekspor') //ambil data header
		$query="/* Formatted on 11/19/2013 1:39:46 PM (QP5 v5.163.1008.3004) */
				SELECT A.NO_UKK,
					   A.ID_REQUEST,
					   A.NM_KAPAL,
					   A.VOYAGE_IN,
					   A.VOYAGE_OUT,
					   A.NM_PEMILIK,
					   A.COA,
					   (SELECT COUNT (1)
						  FROM REQ_REEKSPOR_D B
						 WHERE B.ID_REQUEST = A.ID_REQUEST)
						  JML_CONT,
					   ID_NOTA
				  FROM    REQ_REEKSPOR_H A
					   LEFT JOIN
						  NOTA_REEKSPOR_H C
					   ON A.ID_REQUEST = C.ID_REQUEST
				 WHERE C.STATUS <> 'X'";
	else if($q=='nota_stackext') //ambil data header
		$query="/* Formatted on 11/19/2013 1:39:46 PM (QP5 v5.163.1008.3004) */
				        SELECT b.ID_REQ,
                       b.SHIPPING_LINE,
                       b.VESSEL,
                       b.VOYAGE_IN,
                       b.VOYAGE_OUT,
                       TO_CHAR (b.TGL_REQUEST, 'dd/mm/yyyy') TGL_REQ,
                       b.USER_REQ,
                       (SELECT COUNT(1) FROM REQ_STACKEXT_D WHERE b.ID_REQ = ID_REQ) JML_CONT,
                       c.ID_NOTA
                  FROM REQ_STACKEXT_H b, NOTA_STACKEXT_H c
                  WHERE b.ID_REQ = c.ID_REQUEST(+) ";
	$res = $db->query($query);
	//debug($res);die;
	//ociexecute($query);
	
	while ($row = $res->fetchRow()) {
		$aksi = "";
		if($q == 'pranota_bprp') 
		{
			if($row[STATUS]=="I")
				$aksi = "<a href='nota.penumpukan_ogdk/edit/$row[ID_NOTA]'><img border='0' src='images/edit.png' title='update jkm'></a>&nbsp;&nbsp;<a href='print/print_nota_bprp?p1=$row[ID_NOTA]' target='_blank'><img border='0' src='images/printer.png' width='14' height='14' title='cetak nota'></a>&nbsp;&nbsp;";
			$aksi .= "<a href='print/print_pranota_bprp?p1=$row[ID_NOTA]' target='_blank'><img border='0' src='images/view.png' width='14' height='14' title='cetak pranota BPRP'></a>";	
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_BPRP],$row[ID_NOTA],$row[TGL_INVOICE],$row[TERMINAL],$row[NM_CUST],$row[VESSEL],$row[VOYAGE],$row[PERDAGANGAN],$row[NO_JKM],$row[TOTAL],$row[STATUS]);
		}
		else if($q == 'kurs') {
			$aksi = "<a href='maintenance.master.kurs/edit/".$row[START_DATE]."|".$row[END_DATE]."'><img border='0' src='images/edit.png' title='ubah'></a>";
			$responce->rows[$i]['id']=$row[START_DATE]."|".$row[END_DATE];
			$responce->rows[$i]['cell']=array($row[START_DATE],$row[END_DATE],$row[KURS],$aksi);
		}
		else if($q == 'nota_bm' || $q == 'nota_dk') {
			if($q == 'nota_bm') {
				$file_pranota = "print_rincian_bm";
				$file_nota = "print_nota_bm";
				$title = "Cetak Nota Bongkar Muat";
				$linknya = "nota.bm_ogdk";
			}
			else if($q == 'nota_dk') {
				$file_pranota = "print_pranota_dk";
				$file_nota = "print_nota_dk";
				$title = "Cetak Nota Dermaga & Kebersihan";
				$linknya = "nota.dk_ogdk";
			}
			if($row[STATUS]=="I")
				$aksi .= "<a href='$linknya/edit/$row[ID_NOTA]'><img border='0' src='images/edit.png' title='update jkm'></a>&nbsp;&nbsp;<a href='print/$file_nota?p1=$row[ID_NOTA]' target='_blank'><img border='0' src='images/printer.png' width='14' height='14' title='$title'></a>&nbsp;&nbsp;";
			$aksi .= "<a href='print/$file_pranota?p1=$row[ID_NOTA]' target='_blank'><img border='0' src='images/view.png' width='14' height='14' title='cetak rincian pranota'></a>";
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_ORDER],$row[ID_NOTA],$row[TGL_NOTA],$row[TERMINAL],$row[NM_PEMILIK],$row[VESSEL],$row[VOYAGE],$row[NO_JKM],$row[TOTAL],$row[STATUS]);
		}
		else if($q == 'l_delivery') 
		{
			if(($row[STATUS]=="P")||($row[STATUS]=="T"))
			{
				$aksi = "<blink><font color='red'><b><i>Request Paid</i></b></font></blink>";
			}
			else
			{
				$aksi = "<a href=".HOME."request.delivery.sp2/edit?no_req=".$row[ID_REQ]." ><img src='images/edit.png' title='edit request'></a>";	
				//$aksi = "<a onclick='edit_reqbh(\"".$row[ID_REQ]."\")'><img border='0' src='images/edit.png' title='edit request'></a>";
			}
			$vesv=$row[VESSEL].' '.$row[VOYAGE];
			$responce->rows[$i]['id']=$row[ID_REQ];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_REQ],$row[TGL],$row[EMKL],$vesv,$row[QTY]);
		}
		else if($q == 'nota_delivery') 
		{
			
			if($row[STATUS]=="S")
			{
				$aksi   = "<a href=".HOME."billing.delivery.print/print_nota_lunas?no_req=".$row[ID_REQ]." ><img src='images/printer.png' title='print_nota'></a> <a href='#' onclick='return recalc(\"$row[ID_NOTA]\",\"$row[ID_REQ]\")' title='recalculate'><img src='images/money2.png' ></a>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else if (($row[STATUS]=="P")||($row[STATUS]=="T"))
			{
				$aksi   = "<a href=".HOME."billing.delivery.print/print_nota_lunas?no_req=".$row[ID_REQ]." ><img src='images/printer.png' title='print_nota'></a>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else
			{
				$aksi = "<a href=".HOME."billing.delivery/preview?id=".$row[ID_REQ]."&tipereq=".$row[TIPE_REQ]." ><img src='images/preview.png' title='preview'></a>";
				$status="<b><font color='red'>nota belum disave</font></b>";
			}
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$status,$row[TIPE_REQ],$row[ID_NOTA],$row[ID_REQ],$row[EMKL],$row[VESSEL].' / '.$row[VOYAGES],$row[JUMLAH_CONT]);
		}
		else if($q == 'print_delivery') 
		{
			
			if($row[STATUS]=="P")
			{
				$aksi = "<a href=".HOME."print.sp2.cetak/?pl=".$row[ID_NOTA]." ><img src='images/printer.png' title='print_nota'></a>";
			}
			else
			{
				$aksi = "<b><font color='red'>nota belum disave</font></b>";
				
			}
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_NOTA],$row[ID_REQ],$row[JUMLAH_CONT],$row[EMKL],$row[VESSEL].' '.$row[VOYAGE]);
		}else if($q == 'l_delivery_p') 
		{
			
			if($row[STATUS]=="S")
				{
					$mem="Pre-Invoice Saved";
				}else
					$mem="Request Saved";
					
			if((($row[STATUS]=="S")||($row[STATUS]=="N")) /*&&($row[TIPE_REQ]=="NEW")*/)
			{
				
				$aksi1 = "<blink><b><font color='#51c4fa'><i>$mem</i></FONT><br><font color='#47a9d7'>$row[TIPE_REQ]</font></b></blink>";	
				$aksi2 = "<blink><font color='red'><b><i>Unavailable<br>(Request Delivery<br>Unpaid)<br></i></b></font></blink>";	
			}
			else if (($row[STATUS]=="P") || ($row[STATUS]=="T"))
			{
				$aksi1 = "<blink><b><font color='#51c4fa'><i>$mem</i></FONT><br><font color='#47a9d7'>$row[TIPE_REQ]</font></b></blink>";	
				$aksi2 = "<a href=".HOME."request.delivery.sp2pnew/edit?no_req=".$row[ID_REQ]." ><button>Perpanjang</button></a>";	
			}
			else
			{
				
				$aksi1 = "<blink><b><font color='#51c4fa'><i>$mem</i></FONT><br><font color='#47a9d7'>$row[TIPE_REQ]</font></b></blink>";	
			}
			
			
			$vesv=$row[VESSEL].' - '.$row[VOYAGE];
			$responce->rows[$i]['id']=$row[ID_REQ];
			$responce->rows[$i]['cell']=array($aksi1,$aksi2,$row[ID_REQ],$row[OLD_REQ],$row[JUMLAH_CONT],$row[EMKL],$vesv, $row[SP2P_KE]);
		}
		else if($q == 'l_delivery_p2') 
		{
			
			if($row[STATUS]=="S")
				{
					$mem="Pre-Invoice Saved";
				}else
					$mem="Request Saved";
					
			if((($row[STATUS]=="S")||($row[STATUS]=="N")) &&($row[TIPE_REQ]=="NEW"))
			{
				
				$aksi1 = "<blink><b><font color='#51c4fa'><i>$mem</i></FONT><br><font color='#47a9d7'>$row[TIPE_REQ]</font></b></blink>";	
				$aksi2 = "<blink><font color='red'><b><i>Unavailable<br>(Request Delivery<br>Unpaid)<br></i></b></font></blink>";	
			}
			else if (($row[STATUS]=="P") || ($row[STATUS]=="T"))
			{
				$aksi1 = "<blink><b><font color='#51c4fa'><i>$mem</i></FONT><br><font color='#47a9d7'>$row[TIPE_REQ]</font></b></blink>";	
				$aksi2 = "<a href=".HOME."request.delivery.sp2pnew/edit?no_req=".$row[ID_REQ]." ><button>Perpanjang</button></a>";	
			}
			else
			{
				
				$aksi1 = "<blink><b><font color='#51c4fa'><i>$mem</i></FONT><br><font color='#47a9d7'>$row[TIPE_REQ]</font></b></blink>";	
			}
			
			
			$vesv=$row[VESSEL].' - '.$row[VOYAGE];
			$responce->rows[$i]['id']=$row[ID_REQ];
			$responce->rows[$i]['cell']=array($aksi1,$aksi2,$row[ID_REQ],$row[OLD_REQ],$row[JUMLAH_CONT],$row[EMKL],$vesv, $row[SP2P_KE]);
		}
		else if($q == 'l_batalmuat_p') 
		{
			
			if($row[STATUS]=="S")
				{
					$mem="Pre-Invoice Saved";
				}else
					$mem="Request Saved";
					
			if((($row[STATUS]=="S")||($row[STATUS]=="N")) /*&&($row[TIPE_REQ]=="NEW")*/)
			{
				
				$aksi1 = "<blink><b><font color='#51c4fa'><i>$mem</i></FONT><br><font color='#47a9d7'>$row[TIPE_REQ]</font></b></blink>";	
				$aksi2 = "<blink><font color='red'><b><i>Unavailable<br>(Request Batal Muat<br>Unpaid)<br></i></b></font></blink>";	
			}
			else if (($row[STATUS]=="P") || ($row[STATUS]=="T"))
			{
				$aksi1 = "<blink><b><font color='#51c4fa'><i>$mem</i></FONT><br><font color='#47a9d7'>$row[TIPE_REQ]</font></b></blink>";	
				$aksi2 = "<a href=".HOME."request.batalmuat.delivery_p/edit?no_req=".$row[ID_REQ]." ><button>Perpanjang</button></a>";	
			}
			else
			{
				
				$aksi1 = "<blink><b><font color='#51c4fa'><i>$mem</i></FONT><br><font color='#47a9d7'>$row[TIPE_REQ]</font></b></blink>";	
			}
			
			
			$vesv=$row[VESSEL].' - '.$row[VOYAGES];
			$responce->rows[$i]['id']=$row[ID_REQ];
			$responce->rows[$i]['cell']=array($aksi1,$aksi2,$row[ID_REQ],$row[OLD_REQ],$row[EMKL],$vesv, $row[SP2P_KE]);
		}
		else if($q == 'nota_delivery_p') 
		{
			
			if($row[STATUS]=="S")
			{
				$aksi   = "<a href=".HOME."billing.delivery.print/print_nota_lunas?no_req=".$row[ID_REQ]." ><img src='images/printer.png' title='print_nota'></a>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else if ($row[STATUS]=="P")
			{
				$aksi   = "<a href=".HOME."billing.delivery.print/print_nota_lunas?no_req=".$row[ID_REQ]." ><img src='images/printer.png' title='print_nota'></a>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else
			{
				$aksi = "<a href=".HOME."billing.delivery/preview?id=".$row[ID_REQ]." ><img src='images/preview.png' title='preview'></a>";
				$status="<b><font color='red'>nota belum disave</font></b>";
			}
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$status,$row[ID_NOTA],$row[ID_REQ],$row[EMKL],$row[VESSEL].' / '.$row[VOYAGE],$row[JUMLAH_CONT]);
		}
		else if($q == 'print_delivery_p') 
		{
			
			if($row[STATUS]=="S")
			{
				$aksi = "<a href=".HOME."print.sp2.cetak/?pl=".$row[ID_NOTA]." ><img src='images/printer.png' title='print_nota'></a>";
			}
			else
			{
				$aksi = "<b><font color='red'>nota belum disave</font></b>";
				
			}
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_NOTA],$row[EMKL],$row[VESSEL].' / '.$row[VOYAGE],$row[JUMLAH_CONT]);
		}
		else if($q=='pranota_lolo') 
		{
			if($row[STATUS]=="I")
				$aksi = "<a href='nota.lolo_ogdk/edit/$row[ID_NOTA]'><img border='0' src='images/edit.png' title='update jkm'></a>&nbsp;&nbsp;<a href='print/print_nota_lolo?p1=$row[ID_NOTA]' target='_blank'><img border='0' src='images/printer.png' width='14' height='14' title='cetak nota'></a>&nbsp;&nbsp;";
			$aksi .= "<a href='print/print_pranota_lolo?p1=$row[ID_NOTA]' target='_blank'><img border='0' src='images/view.png' width='14' height='14' title='cetak pranota lolo'></a>";	
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_BPRP],$row[ID_NOTA],$row[TGL_INVOICE],$row[TERMINAL],$row[NM_CUST],$row[VESSEL],$row[VOYAGE],$row[NM_GUDLAP],$row[NO_JKM],$row[TOTAL],$row[STATUS]);
		}
		else if($q == 'l_receiving') 
		{
			if(($row[STATUS]=="P")||($row[STATUS]=="T")) //means Saved Invoice
			{
				$aksi = "<blink><font color='red'><b><i>Request Paid</i></b></font></blink>";	
			}
			else
			{
				$aksi = "<a href=".HOME."request.anne/edit_req?no_req=".$row[ID_REQ]."><img src='images/edit.png' title='edit request'></a>";	
			}
			$pod="<b>$row[IPOD]</b>";	
			$vesv=$row[VESSEL].' - '.$row[VOYAGE];
			$responce->rows[$i]['id']=$row[ID_REQ];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_REQ],$row[JUMLAH_CONT],$pod,$row[PEB],$row[NPE],$row[EMKL],$vesv,$row[TGL_STACK], $row[TGL_MUAT]);
		}
		else if($q == 'monitoring_yard') 
		{
			$responce->rows[$i]['id']=$row[ID];
			$responce->rows[$i]['cell']=array($row[NO_CONTAINER],$row[SIZE_],$row[TYPE_],$row[STATUS],$row[BERAT],$row[VESSEL],$row[VOYAGE],$row[PELABUHAN_ASAL],$row[PELABUHAN_TUJUAN],$row[BLOK_PLANNING].'-'.$row[SLOT_PLANNING].'-'.$row[ROW_PLANNING].'-'.$row[TIER_PLANNING],$row[BLOK_REALISASI].'-'.$row[SLOT_REALISASI].'-'.$row[ROW_REALISASI].'-'.$row[TIER_REALISASI],$row[TGL_GATE_IN],$row[TGL_PLACEMENT], $row[DURASI]);
		}
		else if($q == 'nota_receiving') 
		{
			if($row[STATUS]=="S")
			{
				$aksi   = "<a href=".HOME."billing.receiving.print/print_nota_lunas?no_req=".$row[ID_REQ]." ><img src='images/printer.png' title='print_nota'></a> <a href='#' onclick='return recalc(\"$row[ID_NOTA]\",\"$row[ID_REQ]\")' title='recalculate'><img src='images/money2.png' ></a>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else if (($row[STATUS]=="P")||($row[STATUS]=="T"))
			{
				$aksi   = "<a href=".HOME."billing.receiving.print/print_nota_lunas?no_req=".$row[ID_REQ]." ><img src='images/printer.png' title='print_nota'></a>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else if ($row[STATUS]=="F")
			{
				$aksi   = "<b><font color='green'>Tidak dikenakan biaya</font></b>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else 
			{
				$aksi = "<button title='Preview Nota' onclick=\"return cek_save('".$row[ID_REQ]."');\"><img src='images/preview.png' title='preview'></button>";
				$status="<b><font color='red'><I>nota belum <br>disave</I></font></b>";
			}
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$status,$row[ID_NOTA],$row[ID_REQ],$row[KODE_PBM],$row[VESSEL].' - '.$row[VOYAGE],$row[JML_CONT]);
		}
		else if($q == 'nota_receiving_tn') 
		{
			if($row[STATUS]=="S")
			{
				$aksi   = "<a href=".HOME."billing.receiving.print/print_nota_lunas?no_req=".$row[ID_REQ]." ><img src='images/printer.png' title='print_nota'></a> <a href='#' onclick='return recalc(\"$row[ID_NOTA]\",\"$row[ID_REQ]\")' title='recalculate'><img src='images/money2.png' ></a>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else if (($row[STATUS]=="P")||($row[STATUS]=="T"))
			{
				$aksi   = "<a href=".HOME."billing.receiving.print/print_nota_lunas?no_req=".$row[ID_REQ]." ><img src='images/printer.png' title='print_nota'></a>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else if ($row[STATUS]=="F")
			{
				$aksi   = "<b><font color='green'>Tidak dikenakan biaya</font></b>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else 
			{
				$aksi = "<button title='Preview Nota' onclick=\"return cek_save('".$row[ID_REQ]."');\"><img src='images/preview.png' title='preview'></button><img src='images/no_charge.jpg' onclick=\"confirm_no_charge('".$row[ID_REQ]."')\" title='no charge'>";
				$status="<b><font color='red'><I>nota belum <br>disave</I></font></b>";
			}
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$status,$row[ID_NOTA],$row[ID_REQ],$row[KODE_PBM],$row[VESSEL].' - '.$row[VOYAGE],$row[JML_CONT]);
		}
		else if($q == 'print_stacking_card') 
		{
			if($row[STATUS]=="P")
			{
				$aksi   = "<a href=".HOME."print.stacking_card.cetak/cetak?no_req=".$row[ID_REQ]." ><img src='images/printer.png' title='cetak kartu'></a>";
			}
			else
			{
				//$aksi = "<a href=".HOME."print.stacking_card.cetak/index?no_req=".$row[ID_REQ]." ><img src='images/preview.png' title='preview'></a>";
				$aksi="<b><font color='red'>Not Paid</font></b>";
			}
			$responce->rows[$i]['id']=$row[ID_REQ];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_NOTA],$row[ID_REQ],$row[QTY], $row[EMKL],$row[VESSEL].' '.$row[VOYAGE_IN].' - '.$row[VOYAGE_OUT],$row[JUMLAH_CONT]);
		}
		else if($q == 'l_behandle') 
		{
			if($row[STATUS]=="SAVED")
			{
				$aksi = "<blink><font color='red'><b><i>Request Saved</i></b></font></blink>";	
			}
			else
			{
				//$aksi = "<a href=".HOME."request.behandle/save?id=".$row[ID_REQUEST]." ><img src='images/edit.png' title='edit request'></a>";	
				$aksi = "<a onclick='edit_reqbh(\"".$row[ID_REQUEST]."\")'><img border='0' src='images/edit.png' title='edit request'></a>";	
			}
			$responce->rows[$i]['id']=$row[ID_REQUEST];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_REQUEST],$row[EMKL],$row[TGL_REQUEST],$row[VESSEL].' / '.$row[VOYAGE],$row[NOMOR_INSTRUKSI],$row[JUMLAH_CONT]);
		}
		
	else if($q == 'nota_behandle') 
		{
			
			if($row[STATUS]<>"")
			{
				if($row[STATUS]=='S') {
					$aksi = "<button title='Hitung Ulang' onclick='recalculate(\"".$row[ID_NOTA]."\",\"".$row[ID_REQUEST]."\")'><img src='images/recalculate.jpg' width='14' height='14'></button>&nbsp;&nbsp;";
					$status="<b><font color='green'>SAVED</font></b>";
				}
				else if($row[STATUS]=='P')
					$status="<b><font color='green'>PAID</font></b>";
				else if($row[STATUS]=='T')
					$status="<b><font color='green'>TRANSFER</font></b>";
				$aksi .= "<a href=".HOME."print/print_nota_behandle?pl=".$row[ID_NOTA]." ><img src='images/printer.png' title='print pranota'></a>";
			}
			else
			{
				$aksi = "<a href=".HOME."billing.behandle/preview?id=".$row[ID_REQUEST]." ><img src='images/preview.png' title='preview'></a>";
				//$aksi='cb';
				$status="<b><font color='red'>NEW</font></b>";
			}
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_NOTA],$row[ID_REQUEST],$row[EMKL],$row[VESSEL].' / '.$row[VOYAGE],$row[JUMLAH_CONT],$status);
		}
	
	else if($q == 'nota_transhipment') 
		{
			
			if($row[STATUS]<>"")
			{
				if($row[STATUS]=='S') {
					$aksi = "<button title='Hitung Ulang' onclick='recalculate(\"".$row[ID_NOTA]."\",\"".$row[ID_REQ]."\")'><img src='images/recalculate.jpg' width='14' height='14'></button>&nbsp;&nbsp;";
					$status="<b><font color='green'>SAVED</font></b>";
				}
				else if($row[STATUS]=='P')
					$status="<b><font color='green'>PAID</font></b>";
				else if($row[STATUS]=='T')
					$status="<b><font color='green'>TRANSFER</font></b>";
				//$aksi .= "<a href=".HOME."print/print_nota_transhipment?pl=".$row[ID_NOTA]." ><img src='images/printer.png' title='print pranota'></a>";
				$aksi .= "<a href=".HOME."billing.transhipment.print/print_nota_lunas?pl=".$row[ID_REQ]." ><img src='images/printer.png' title='print pranota'></a>";
			}
			else
			{
				$aksi = "<a href=".HOME."billing.transhipment/preview?id=".$row[ID_REQ]." ><img src='images/preview.png' title='preview'></a>";
				//$aksi='cb';
				$status="<b><font color='red'>NEW</font></b>";
			}
			$info_ves=$row[VESSEL].' / '.$row[VOYAGE]."<br/>".$row[VESSEL_DT].' / '.$row[VOYAGE_DT];
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_NOTA],$row[ID_REQ],$row[SHIPPING_LINE],$info_ves,$row[JUMLAH_CONT],$status);
		}
	else if($q == 'nota_reexport') 
		{
			
			if($row[STATUS]<>"")
			{
				if($row[STATUS]=='S') {
					$aksi = "<button title='Hitung Ulang' onclick='recalculate(\"".$row[ID_NOTA]."\",\"".$row[ID_REQ]."\")'><img src='images/recalculate.jpg' width='14' height='14'></button>&nbsp;&nbsp;";
					$status="<b><font color='green'>SAVED</font></b>";
				}
				else if($row[STATUS]=='P')
					$status="<b><font color='green'>PAID</font></b>";
				else if($row[STATUS]=='T')
					$status="<b><font color='green'>TRANSFER</font></b>";
				$aksi .= "<a href=".HOME."billing.reexport.print/print_nota_lunas2?pl=".$row[ID_REQ]." ><img src='images/printer.png' title='print pranota'></a>";
			}
			else
			{
				$aksi = "<a href=".HOME."billing.reexport/preview?id=".$row[ID_REQ]." ><img src='images/preview.png' title='preview'></a>";
				//$aksi='cb';
				$status="<b><font color='red'>NEW</font></b>";
			}
			$info_ves=$row[VESSEL].' / '.$row[VOYAGE]."<br/>".$row[VESSEL_DT].' / '.$row[VOYAGE_DT];
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_NOTA],$row[ID_REQ],$row[SHIPPING_LINE],$info_ves,$row[JUMLAH_CONT],$status);
		}
	
	else if($q == 'nota_hicoscan') 
	{
		
		if($row[STATUS]=="S")
		{
			$aksi = "<a href=".HOME."print/print_nota_hicoscan?pl=".$row[ID_NOTA]." ><img src='images/printer.png' title='print pranota'></a> <a href='#' onclick='return recalc(\"$row[ID_NOTA]\",\"$row[ID_REQUEST]\")' title='recalculate'><img src='images/money2.png' ></a>";
			$status="<b><font color='green'>".$row[STATUS]."</font></b>";
			$no_nota = "<font color='black'>".$row[ID_NOTA]."</font>";
		}
		else if($row[STATUS]=="P")
		{
			$aksi = "<a href=".HOME."print/print_nota_hicoscan?pl=".$row[ID_NOTA]." ><img src='images/printer.png' title='print pranota'></a>";
			$status="<b><font color='green'>".$row[STATUS]."</font></b>";
			$no_nota = "<font color='black'>".$row[ID_NOTA]."</font>";
		}
		else
		{
			$aksi = "<a href=".HOME."billing.hicoscan/preview?id=".$row[ID_REQUEST]." ><img src='images/preview.png' title='preview'></a>";
			//$aksi='cb';
			$status="<b><font color='red'>nota belum disave</font></b>";
			$no_nota = "<font color='red'>".$row[ID_NOTA]."</font>";
		}
		$responce->rows[$i]['id']=$row[ID_NOTA];
		$responce->rows[$i]['cell']=array($aksi,$no_nota,$row[ID_REQUEST],$row[EMKL],$row[VESSEL].' / '.$row[VOYAGE],$row[JUMLAH_CONT],$status);
	}
	else if($q == 'nota_exmo') 
		{
			
			if($row[STATUS]<>"")
			{
				if($row[STATUS]=='S') {
					$aksi = "<button title='Hitung Ulang' onclick='recalculate(\"".$row[ID_NOTA]."\",\"".$row[ID_REQUEST]."\")'><img src='images/recalculate.jpg' width='14' height='14'></button>&nbsp;&nbsp;";
					$status="<b><font color='green'>SAVED</font></b>";
				}
				else if($row[STATUS]=='P')
					$status="<b><font color='green'>PAID</font></b>";
				else if($row[STATUS]=='T')
					$status="<b><font color='green'>TRANSFER</font></b>";
				$aksi .= "<a href=".HOME."print/print_nota_exmo?pl=".$row[ID_NOTA]." ><img src='images/printer.png' title='print pranota'></a>";
			}
			else
			{
				$aksi = "<a href=".HOME."billing.extramovement/preview?id=".$row[ID_REQUEST]." ><img src='images/preview.png' title='preview'></a>";
				//$aksi='cb';
				$status="<b><font color='red'>NEW</font></b>";
			}
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_NOTA],$row[TGL_NOTA],$row[ID_REQUEST],$row[EMKL],$row[JUMLAH_CONT],$status);
		}
		else if($q == 'print_behandle') 
		{
			
			if($row[STATUS]=="P")
			{
				$aksi = "<a href=".HOME."print.behandle.cetak/?pl=".$row[ID_NOTA]." ><img src='images/printer.png' title='print behandle card'></a>";
				
			}
			else
			{
				$aksi = "<b><font color='red'>nota belum dibayar</font></b>";
			}
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_NOTA],$row[EMKL],$row[VESSEL].'/'.$row[VOYAGE],$row[JUMLAH_CONT], $row[ID_REQUEST]);
		}
		else if($q == 'print_batmuak') 
		{
			
			if($row[STATUS]=="P")
			{
				$aksi = "<a href=".HOME."print.batalmuatalihkapal.cetak/cetak?no_req=".$row[ID_BATALMUAT]." ><img src='images/printer.png' title='print behandle card'></a>";
				
			}
			else
			{
				$aksi = "<b><font color='red'>nota belum dibayar</font></b>";
			}
			$responce->rows[$i]['id']=$row[ID_BATALMUAT];
			$responce->rows[$i]['cell']=array($aksi,$row[NO_NOTA],$row[ID_BATALMUAT],$row[JUMLAH_CONT],$row[EMKL],$row[VESSEL].'/'.$row[VOYAGE]);
		}
		else if($q == 'print_exmo') 
		{
			
			if($row[STATUS]=="P")
			{
				$aksi = "<a href=".HOME."print.extramovement.cetak/?pl=".$row[ID_NOTA]." ><img src='images/printer.png' title='print extra movement card'></a>";
				
			}
			else
			{
				$aksi = "<b><font color='red'>nota belum dibayar</font></b>";
			}
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_NOTA],$row[EMKL],$row[NOMOR_INSTRUKSI],$row[JUMLAH_CONT]);
		}
		
		else if($q == 'l_vessel') 
		{
			
			if($row[STATUS]=="AKTIF")
			{
				$aksi = "<a href=".HOME."planning.vessel_schedule/upload/?id_vessel=".$row[ID_VS]." ><img src='images/upload_excel.png' width=30px height=30px title='upload container format excel'></a>";
				$aksi2 = "<a href=".HOME."planning.vessel_schedule/upload_csv/?id_vessel=".$row[ID_VS]." ><img src='images/upload_excel.png' width=30px height=30px title='upload container format csv'></a>";
				
				
			}
			else
			{
				$aksi = "<b><font color='red'>tidak aktif</font></b>";
			}
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($aksi,$aksi2,$row[NAMA_VESSEL],$row[VOYAGE],$row[ETA],$row[ETD],$row[RTA],$row[RTD],$row[STATUS]);
		}
		else if($q == 'booking') 
		{
			$idvs=$row[ID_VS];
			$aksi2 = "<button onclick='op_st(\"$idvs\")'><img border='0' src='images/booking_open.png' height='20px' width='20px'></button>&nbsp;<button onclick='cl_tm(\"$idvs\")'><img border='0' src='images/booking_closed.png' height='20px' width='20px'></button>";
			$voy=$row[VOYAGE_IN].' - '.$row[VOYAGE_OUT];
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($aksi2,$row[NAMA_VESSEL],$voy, $row[OPEN_STACK],$row[CLOSING_TIME],$row[CLOSING_TIME_DOC],$row[ETA],$row[ETD],$row[NM_PELABUHAN_ASAL],$row[NM_PELABUHAN_TUJUAN]);
		}
		else if($q == 'rbm') 
		{	
			/*if(isset($_POST['page'])){
				$pp = $_POST['page'];
			}else{
				$pp = 1;
			}
			echo $pp; die();
			$item_per_page = 20;
	
			$totalNum = $db->query($query_rbm)->RecordCount();
			$maxPage   = ceil($totalNum / $item_per_page)-1; 
			if ($maxPage<0) $maxPage = 0;
				
			$page   = ( $pp <= $maxPage+1 && $pp > 0 )?$pp-1:0;
			$__offset = $page * $item_per_page;
			$rs 	= $db->selectLimit( $query_rbm,$__offset,$item_per_page );	
			$rows 	= array();
			if ($rs && $rs->RecordCount()>0) {				
				for ($__rowindex = 1 + $__offset; $row=$rs->FetchRow(); $__rowindex++) {
					$row["__no"] = $__rowindex;
					$rows[] = $row;
				}
				$rs->close();
			}
			$row = & $rows;	*/
			$vsx=$row[VESSEL].' '.$row[VOYAGE_IN].' - '.$row[VOYAGE_OUT];
			$vs=$row[VESSEL].' ['.$row[ID_VES_SCD].']';
			$vs2=URLEncode($vsx);
			
			if($row[RBM_DLD]=='1')
			{
				$ct='downloads1.png';
				$ct2='group2.png';
				$ct3='ok_cardexp.png';
				
				$d1="onclick='dnld(\"$row[ID_VES_SCD]\")'";
				$d2="onclick='group(\"$row[ID_VES_SCD]\",\"$row[RBM_DLD]\",\"$vs2\")'";
				$d3="";
			}
			else if($row[RBM_DLD]=='2')
			{
				$ct='downloads1exp.png';
				$ct2='group2exp.png';
				$ct3='ok_card.png';
				
				$d1="";
				$d2="";
				$d3="onclick='final_gr(\"$row[ID_VES_SCD]\")'";
			}
			ELSE
			{
				$ct='downloads2.png';
				$ct2='group2exp.png';
				$ct3='ok_cardexp.png';
				
				$d1="onclick='dnld(\"$row[ID_VES_SCD]\")'";
				$d2="";
				$d3="";
			}
			$link = "";
			if ($_SESSION["ID_GROUP"] == 1) {
				$icd = 'del2.png';
				$ocd = "onclick='canceld(\"$row[ID_VES_SCD]\")'";
				$link ="<a $ocd title='cancel RBM'><img src='images/$icd' width='20'></a>";
			}
			
			$voy	= $row[VOYAGE_IN].' - '.$row[VOYAGE_OUT];
			$aksi ="<a href='".HOME."billing.rbm.cetak/cetak_rbm?no_ukk=".$row[ID_VES_SCD]."' ><img src='images/print.png' width='20'></a> <a $d1 title='download RBM'><img src='images/$ct' width='30'></a> <a $d2 title='Grouping RBM'><img src='images/$ct2' width='20'></a> <a $d3 title='final RBM'><img src='images/$ct3' width='20'></a> $link";
			$responce->rows[$i]['id']=$row[ID_VES_SCD];
			$responce->rows[$i]['cell']=array($aksi,$vs,$voy,$row[OPERATOR_NAME],$row[DQ],$row[LQ],$row[TR],$row[SHIFT],$row[ATA],$row[ATD], $row[POD],$row[POL]);
		}
		else if($q == 'vs_fix') 
		{
			$pw=$row[ID_VS];
			$voy	= $row[VOYAGE_IN].' - '.$row[VOYAGE_OUT];
			if ($row[RTA]=='')
			{
				$rta="<button onclick='rta(\"$pw\")' title='update RTA'>&nbsp;</button>";
			}
			ELSE
				$rta=$row[RTA];
				
			if ($row[RTD]=='')
			{
				$rtd="<button onclick='rtd(\"$pw\")' title='update RTD'>&nbsp;</button>";
			}
			else
				$rtd=$row[RTD];
			$edit="<button onclick='update_vs(\"$pw\")' title='update vessel schedule'><img src='images/cont_edit.gif' width=15px height=15px border='0'></button>";
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($edit, $row[ID_VS],$row[NAMA_VESSEL],$voy,$row[JENIS_KAPAL2], $row[ETA],$row[ETD],$rta,$rtd,$row[NM_PELABUHAN_ASAL],$row[NM_PELABUHAN_TUJUAN]);
		}
		else if($q == 'm_rbm') 
		{
			$pw=$row[ID_VS];
			if ($row[STATUS]=='')
			{
				$sts='-';
				$clr2='#606263';
			}
			else if($row[STATUS]=='CLOSED')
			{
				$sts='OK';
				$clr='#606263';
				$clr2='#47b0fa';
			}
			else
			{
				$sts='OK';
				$clr='#fa2e59';
				$clr2='#47b0fa';
			}
			
			if ($row[EMAIL]=='')
			{
				$sts2='-';
				$clr3='#606263';
				$jml_e='';
			}
			ELSE if ($row[EMAIL]=='N')
			{
				$sts2='-';
				$clr3='#606263';
				$jml_e='';
			}
			else 
			{
				$sts2="Email Sent <a onclick='log_email(\"$pw\")' title='history email'>(".$row[JML_EMAIL].")</a>";
				$clr3='#47b0fa';
			}
			
			if($row[PRANOTA]=='Y')
			{
				$prn="OK";
				$clr3='#47b0fa';
			}
			else
			{
				$prn="-";
			}
			if($row[NOTA]=='Y')
			{
				$nota="OK";
				$clr3='#47b0fa';
			}
			else
			{
				$nota="-";	
			}
			$sc	  = "<button onclick='sync_rbm(\"$pw\")' title='update RBM'><img src='images/Refresh2.png' width=15px height=15px border='0'></button>&nbsp;<button onclick='get_data(\"$pw\")' title='get data ict'><img src='images/Refresh3.png' width=15px height=15px border='0'></button>";
			$voy  = '<div max-height=50px><br>'.$row[NAMA_VESSEL].' '.$row[VOYAGE_IN].' - '.$row[VOYAGE_OUT].'<br><br></div>';
			$cmt  = "<button onclick='comment_add(\"$pw\")' title='add comment' style='text-valign:center;'><img src='images/chat2.png' width=20px height=20px border='0'>wall</button>";
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($cmt,$row[ID_VS],$voy,"<b><font color=$clr2>$sts</font></b><br><font color=$clr><b>$row[STATUS]</b><br><b>$row[TGL_FINAL]</b></font>",
										"<b><font color=$clr3>$sts2</font></b><br><font color=$clr><b>$row[TGL_EMAIL]</b></font>", 
										"<b><font color=$clr3>$prn</font></b><br><font color=$clr><b>$row[TGL_PRN]</b></font>"
										,
										"-",
										"<b><font color=$clr3>$nota</font></b><br><font color=$clr><b>$row[TGL_SAVE_NOTA]</b></font>",
										"-");
		}
		
		else if($q == 'bm') 
		{
			$pro=$row[NO_PR_O];
			$pri=$row[NO_PR_I];
			$pw=$row[ID_VS];
			$notai=$row[NOTAI];
			$notao=$row[NOTAO];
			
			if($row[ST_RO]<>'')
			{
				$stro='<i><b>sudah transfer</b></i>';
			}
			else
				$stro='';
			
			if($row[ST_RI]<>'')
			{
				$stri='<i><b>sudah transfer</b></i>';
			}
			else
				$stri='';
			if($row[TGL_RI]<>'')
			{
				$cri=1;
			}
			else
				$cri=0;
			if($row[TGL_RO]<>'')
			{
				$cro=1;
			}
			else
				$cro=0;
			if($row[STATUSI]=='R') 
			{
				$statusi="<BR><b><i><font color='#6f7071'>Realisasi</font></i></b>";
				$sv2="<a href='#' onclick='return save_nota(\"$pri\")'><img src='images/sv.png'></a>";
			}
			else if($row[STATUSI]=='I') 
			{
				$statusi="<BR><b><i><font color='#2d95ee'>Invoice<br>$notai</font></i></b>";
				$sv2="<a href='#' onclick='return transfer(\"$notai\",\"$cri\")'><img src='images/invo2.png' width=25px height=25px border='0' title='Cetak Nota'></a>";
			}
			else if($row[STATUSI]==null	) 
			{
				$statusi="<BR><blink><b><i><font color='#fe3250'>No Invoice</font></i></b></blink>";
				$sv2="";
			}
			
			if($row[STATUSO]=='R') 
			{
				$statuso="<BR><b><i><font color='#6f7071'>Realisasi</font></i></b>";
				$sv="<a href='#' onclick='return save_nota(\"$pro\")' title='save nota'><img src='images/sv.png'></a>";
			}
			else if($row[STATUSO]=='I') 
			{
				$statuso="<BR><b><i><font color='#2d95ee'>Invoice<br>$notao</font></i></b>";
				$sv="<a href='#' onclick='return transfer(\"$notao\",\"$cro\")'><img src='images/invo2.png' width=25px height=25px border='0' title='Cetak Nota'></a>";
			}
			else if($row[STATUSO]==null) 
			{
				$statuso="<BR><blink><b><i><font color='#fe3250'>No Invoice</font></i></b></blink>";
				$sv="";
			}
			
			if($row[OI]=='I')
			{
				$aksi = "<div><br><a href=".HOME."billing.bm.cetak/print_pranota/?id_vessel=".$row[ID_VS]."&no_pr=".$row[NO_PR_I]." onclick='return cek_pr(\"$pri\")'><img src='images/preinv.png' width=25px height=25px border='0' title='Cetak Pranota'></a>$sv2 $statusi</div>";
				$aksix= "<blink><b><i><font color='#e94959'>not available</font></i></b></blink>";
			}
			else if($row[OI]=='O')
			{
				$aksix = "<div><br><a href=".HOME."billing.bm.cetak/print_pranota/?id_vessel=".$row[ID_VS]."&no_pr=".$row[NO_PR_O]." onclick='return cek_pr(\"$pro\")'><img src='images/preinv.png' width=25px height=25px border='0' title='Cetak Pranota'></a>$sv $statuso</div>";
				$aksi= "<blink><b><i><font color='#e94959'>not available</font></i></b></blink>";
			}
			else if($row[OI]=='X')
			{
				$aksix = "<div><br><a href=".HOME."billing.bm.cetak/print_pranota/?id_vessel=".$row[ID_VS]."&no_pr=".$row[NO_PR_O]." onclick='return cek_pr(\"$pro\")'><img src='images/preinv.png' width=25px height=25px border='0' title='Cetak Pranota'></a>$sv $statuso  </div>";
				$aksi = "<div><a href=".HOME."billing.bm.cetak/print_pranota/?id_vessel=".$row[ID_VS]."&no_pr=".$row[NO_PR_I]." onclick='return cek_pr(\"$pri\")' ><img src='images/preinv.png' width=25px height=25px border='0' title='Cetak Pranota'></a>$sv2 $statusi  </div>";			}
			else
			{
				$aksi= "<blink><b><i><font color='#e94959'>not available</font></i></b></blink>";
				$aksix= "<blink><b><i><font color='#e94959'>not available</font></i></b></blink>";
			}
			
			$aksix=$aksix.''.$stro;
			$aksi=$aksi.''.$stri;
			$voy	= $row[VOYAGE_IN].' - '.$row[VOYAGE_OUT];
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($aksix,$aksi,$row[ID_VS],$row[NAMA_VESSEL],$voy,$row[NM_PEMILIK], $row[ETA],$row[ETD],$row[NM_PELABUHAN_ASAL],$row[NM_PELABUHAN_TUJUAN]);
		}
		else if($q == 'hatch') 
		{
			$aksi = "<a onclick='edit_reqbh(\"".$row[ID_VS]."\")'><img border='0' src='images/add.png' height='50%' width='40%' title='add hatch move'></a>";	
		    $responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($aksi,$row[NAMA_VESSEL],$row[VOYAGE_IN],$row[VOYAGE_OUT], $row[ETA],$row[ETD],$row[NM_PELABUHAN_ASAL],$row[NM_PELABUHAN_TUJUAN]);
		}
		else if($q == 'bapli') 
		{
			$aksi = "<a href=".HOME."planning.upload_bapli/upload/?id_vessel=".$row[ID_VS]." ><img src='images/csv.gif' width=30px height=30px border='0' title='upload container format csv'></a>";
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($aksi,$row[NAMA_VESSEL],$row[VOYAGE_IN],$row[VOYAGE_OUT], $row[ETA],$row[ETD],$row[NM_PELABUHAN_ASAL],$row[NM_PELABUHAN_TUJUAN]);
		}
		else if($q == 'stowage_final') 
		{
			$aksi = "<a href=".HOME."operational.stowage_final.download/download_csv/?id_vessel=".$row[ID_VS]." ><img src='images/download1.png' width=30px height=30px title='download csv'></a>";				
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($aksi,$row[NAMA_VESSEL],$row[VOYAGE],$row[ETA],$row[ETD],$row[RTA],$row[RTD],$row[STATUS]);
		}
		else if($q == 'list_detail_rbm') 
		{
			//$aksi = "<div><a href=".HOME."billing.rbm.cetak/cetak_rbm?id_vessel=".$row[ID_VS]." ><img src='images/editp.png' width=30px height=30px border='0' title='cetak RBM'></a></div>";
			$edit_oog = "<div><a href='javascript:oog(\"$row[NO_UKK]\" , \"$row[NO_CONTAINER]\" , \"$row[SIZE_CONT]\" , \"$row[TYPE_CONT]\" , \"$row[STATUS_CONT]\" , \"$row[HEIGHT_CONT]\" , \"$row[GROSS]\" , \"$row[E_I]\" , \"$row[HZ]\" , \"$row[TRS]\")'><img src='images/editp.png' /></a></div>";
			//$edit_bundle = "<div><a href='javascript:bundle(\"$row[NO_UKK]\" , \"$row[NO_CONTAINER]\" , \"$row[SIZE_CONT]\" , \"$row[TYPE_CONT]\" , \"$row[STATUS_CONT]\" , \"$row[HEIGHT_CONT]\" , \"$row[GROSS]\" , \"$row[E_I]\" , \"$row[HZ]\" , \"$row[TRS]\")'><img src='images/editp.png' /></a></div>";
			$list = "<div><a href=".HOME."billing.rbm.list_detail/list_detail?id_vessel=".$row[ID_VS]." >DETAIL</a></div>";
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($edit_oog,$row[NO_UKK],$row[NO_CONTAINER],$row[SIZE_CONT], $row[TYPE_CONT],$row[STATUS_CONT],$row[HEIGHT_CONT],$row[GROSS],$row[E_I],$row[HZ],$row[TRS],$row[REMARKS]);
		}
		else if($q == 'bundle') 
		{
			//$aksi = "<div><a href=".HOME."billing.rbm.cetak/cetak_rbm?id_vessel=".$row[ID_VS]." ><img src='images/editp.png' width=30px height=30px border='0' title='cetak RBM'></a></div>";
			$nukk=$row[NO_UKK];
			$nct=$row[NO_CONTAINER];
			$no_bundle=$row[BUNDLE];
			
			$bt="<button title='edit bundle' onclick='ed_bundle(\"$no_bundle\",\"$nukk\")'><img src='images/editx.png' width=15px height=15px border='0'></button>&nbsp;<button title='delete bundle' onclick='del_bundle(\"$nct\",\"$no_bundle\")'><img src='images/del2.png' width=15px height=15px border='0'></button>";
			$responce->rows[$i]['id']=$row[NO_UKK];
			//SELECT NO_UKK , NO_CONTAINER , SIZE_CONT, TYPE_CONT, STATUS_CONT, HEIGHT_CONT, E_I, HZ, BUNDLE,DASAR_BUNDLE FROM RBM_LIST WHERE BUNDLE IS NOT NULL
			$responce->rows[$i]['cell']=array($bt,$row[NO_UKK],$row[NO_CONTAINER],$row[SIZE_CONT], $row[TYPE_CONT],$row[STATUS_CONT],$row[HEIGHT_CONT],$row[E_I],$row[HZ],$row[BUNDLE],$row[DASAR_BUNDLE]);
		}
		else if($q == 'edit_bundle') 
		{
			//$aksi = "<div><a href=".HOME."billing.rbm.cetak/cetak_rbm?id_vessel=".$row[ID_VS]." ><img src='images/editp.png' width=30px height=30px border='0' title='cetak RBM'></a></div>";
			$nukk=$row[NO_UKK];
			$nct=$row[NO_CONTAINER];
			$no_bundle=$row[BUNDLE];
			
			$bt="<button title='edit bundle' onclick='ed_bundle(\"$no_bundle\")'><img src='images/editx.png' width=15px height=15px border='0'></button>&nbsp;<button title='delete bundle' onclick='del_bundle(\"$nct\",\"$js\")'><img src='images/del2.png' width=15px height=15px border='0'></button>";
			$radio_bt="<button title='edit bundle' onclick='ed_bundle(\"$no_bundle\")'><img src='images/editx.png' width=15px height=15px border='0'></button>&nbsp;<button title='delete bundle' onclick='del_bundle(\"$nct\",\"$js\")'><img src='images/del2.png' width=15px height=15px border='0'></button>";
			$responce->rows[$i]['id']=$row[NO_CONTAINER];
			//SELECT NO_UKK , NO_CONTAINER , SIZE_CONT, TYPE_CONT, STATUS_CONT, HEIGHT_CONT, E_I, HZ, BUNDLE,DASAR_BUNDLE FROM RBM_LIST WHERE BUNDLE IS NOT NULL
			//SELECT NO_UKK ,NO_CONTAINER, BUNDLE,DASAR_BUNDLE FROM RBM_LIST WHERE BUNDLE ='$no_bundle' ORDER BY BUNDLE DESC, DASAR_BUNDLE ASC
			$responce->rows[$i]['cell']=array($row[NO_UKK],$row[NO_CONTAINER],$row[BUNDLE],$row[DASAR_BUNDLE]);
		}
		else if($q == 'shifting_og') 
		{
			//$aksi = "<div><a href=".HOME."billing.rbm.cetak/cetak_rbm?id_vessel=".$row[ID_VS]." ><img src='images/editp.png' width=30px height=30px border='0' title='cetak RBM'></a></div>";
			$nct=$row[NO_CONTAINER];
			$js=$row[JENIS_SHIFT];
			$alat=$row[ALAT_SHIFT];
			$jml=$row[JUMLAH_SHIFT];
			$size=$row[SIZE_C];
			//<button title='edit shifting' onclick='ed_shift(\"$nct\",\"$js\",\"$alat\")'><img src='images/editx.png' width=15px height=15px border='0'></button>&nbsp;
			$bt="<button title='delete shifting' onclick='del_shift(\"$nct\",\"$js\",\"$alat\",\"$jml\",\"$size\")'><img src='images/del2.png' width=15px height=15px border='0'></button>";
			$responce->rows[$i]['id']=$row[NO_UKK];
			$responce->rows[$i]['cell']=array($bt,$row[NO_UKK],$row[NO_CONTAINER],$row[SIZE_C], $row[TYPE_C],$row[STATUS_C],$row[HZ],$row[HEIGHT_C],$row[ALAT_SHIFT],$row[EI],$row[JENIS_SHIFT],$row[JUMLAH_SHIFT]);
		}
		else if($q == 'hatch_mv') 
		{
			//$aksi = "<div><a href=".HOME."billing.rbm.cetak/cetak_rbm?id_vessel=".$row[ID_VS]." ><img src='images/editp.png' width=30px height=30px border='0' title='cetak RBM'></a></div>";
			$no_ukk=$row[NO_UKK];
			$bay=$row[BAY];
			$bt="<button title='delete hatch' onclick='del_hatch(\"$bay\")'><img src='images/del2.png' width=15px height=15px border='0'></button>";
			$responce->rows[$i]['id']=$row[NO_UKK];
			$responce->rows[$i]['cell']=array($bt,$row[NO_UKK],$row[BAY],$row[ALAT], $row[ACTIVITY],$row[MOVE_TIME_OPEN],$row[MOVE_TIME_CLOSE],$row[OI],$row[OPERATOR],$row[JUMLAH]);
		}
		else if($q == 'vessel_schedule') 
		{
			$responce->rows[$i]['id']=$row[NM_KAPAL];
			$responce->rows[$i]['cell']=array($row[NM_KAPAL],$row[VOYAGE_IN],$row[VOYAGE_OUT],$row[TGL_JAM_TIBA],$row[BERTHING],$row[TGL_JAM_BERANGKAT],$row[END_WORK],$row[REMARK]);
		}
		else if($q == 'yor_daily') 
		{
			$responce->rows[$i]['id']=$row[NAME];
			$responce->rows[$i]['cell']=array($row[NAME],$row[SLOT],$row[ROW_CONT],$row[TIER],$row[CAPACITY],$row[USED],$row[BOX],$row[YOR]);
		}
		else if($q == 'l_batmuatak') 
		{
			if (($row[STATUS]=="P") || ($row[STATUS]=="T"))
			{
				$aksi = "<blink><font color='red'><b><i>Request paid</i></b></font></blink>";			
			}
			else
			{
				//$aksi = "<a href=".HOME."request.behandle/save?id=".$row[ID_REQUEST]." ><img src='images/edit.png' title='edit request'></a>";	
				$aksi = "<a onclick='edit_reqbh(\"".$row[ID_BATALMUAT]."\")'><img border='0' src='images/edit.png' title='edit request'></a>";
			}
			$responce->rows[$i]['id']=$row[ID_BATALMUAT];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_BATALMUAT],$row[EMKL],$row[TGL_REQ],$row[VESSEL].' / '.$row[VOYAGE],$row[JUMLAH_CONT],$row[JNS_BM]);
		}
		else if($q == 'l_batmuatdev') 
		{
			if (($row[STATUS]=="P") || ($row[STATUS]=="T"))
			{
				$aksi = "<blink><font color='red'><b><i>Request paid</i></b></font></blink>";			
			}
			else
			{
				//$aksi = "<a href=".HOME."request.behandle/save?id=".$row[ID_REQUEST]." ><img src='images/edit.png' title='edit request'></a>";	
				$aksi = "<a onclick='edit_reqbh(\"".$row[ID_BATALMUAT]."\")'><img border='0' src='images/edit.png' title='edit request'></a>";
			}
			$responce->rows[$i]['id']=$row[ID_BATALMUAT];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_BATALMUAT],$row[EMKL],$row[TGL_REQ],$row[TGL_BERANGKAT2],$row[JUMLAH_CONT]);
		}
		else if($q == 'nota_batalmuatak') 
		{
			
			if($row[STATUS]=="S")
			{
				$aksi   = "<a href=".HOME."billing.batalmuat.alihkapal.print/print_nota_lunas?no_req=".$row[ID_BATALMUAT]." ><img src='images/printer.png' title='print_nota'></a> <a href='#' onclick='return recalc(\"$row[ID_NOTA]\",\"$row[ID_BATALMUAT]\")' title='recalculate'><img src='images/money2.png' ></a>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else if ($row[STATUS]=="P")
			{
				$aksi   = "<a href=".HOME."billing.batalmuat.alihkapal.print/print_nota_lunas?no_req=".$row[ID_BATALMUAT]." ><img src='images/printer.png' title='print_nota'></a>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else
			{
				$aksi = "<a href=".HOME."billing.batalmuat.alihkapal/preview?id=".$row[ID_BATALMUAT]."&jenis=".$row[JENIS]." ><img src='images/preview.png' title='preview'></a>";
				$status="<b><font color='red'>nota belum disave</font></b>";
			}
			$responce->rows[$i]['id']=$row[NO_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$status,$row[ID_NOTA],$row[ID_BATALMUAT],$row[JENIS_BATMUAT],$row[EMKL],$row[VESSEL].' / '.$row[VOYAGE],$row[JUMLAH_CONT]);
		}
		else if($q == 'nota_batalmuatd') 
		{
			
			if($row[STATUS]=="S")
			{
				$aksi   = "<a href=".HOME."billing.batalmuat.delivery.print/print_nota_lunas?no_req=".$row[ID_BATALMUAT]." ><img src='images/printer.png' title='print_nota'> <a href='#' onclick='return recalc(\"$row[ID_NOTA]\",\"$row[ID_BATALMUAT]\")' title='recalculate'><img src='images/money2.png' ></a></a>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else if ($row[STATUS]=="P")
			{
				$aksi   = "<a href=".HOME."billing.batalmuat.delivery.print/print_nota_lunas?no_req=".$row[ID_BATALMUAT]." ><img src='images/printer.png' title='print_nota'></a>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else
			{
				$aksi = "<a href=".HOME."billing.batalmuat.delivery/preview?id=".$row[ID_BATALMUAT]." ><img src='images/preview.png' title='preview'></a>";
				$status="<b><font color='red'>nota belum disave</font></b>";
			}
			$responce->rows[$i]['id']=$row[NO_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$status,$row[ID_NOTA],$row[ID_BATALMUAT],$row[EMKL],$row[JUMLAH_CONT]);
		}
		else if($q == 'l_renamecontainer') 
		{
			if ($row[STATUS]=="F")
			{
				$aksi = "<blink><font color='red'><b><i>Request Free</i></b></font></blink>";			
			} 
			else if (($row[STATUS]=="P") || ($row[STATUS]=="T"))
			{
				$aksi = "<blink><font color='red'><b><i>Request paid</i></b></font></blink>";			
			}
			else
			{
				//$aksi = "<a href=".HOME."request.behandle/save?id=".$row[ID_REQUEST]." ><img src='images/edit.png' title='edit request'></a>";	
				$aksi = "<a onclick='edit_reqbh(\"".$row[NO_RENAME]."\")'><img border='0' src='images/edit.png' title='edit request'></a>";
			}
			$responce->rows[$i]['id']=$row[NO_RENAME];
			$vesvoy = $row['VESSEL'].'/'.$row['VOYAGE'];
			$responce->rows[$i]['cell']=array($aksi,$row[NO_RENAME],$row[TGL_RENAME],$row[NO_EX_CONTAINER],$row[NO_CONTAINER],$vesvoy);
			
			//No Rename','Tanggal Rename','No Ex Container','No Container New','Vessel / Voyage'
		}
		else if($q == 'nota_rename_container') 
		{
			
			if($row[STATUS]=="S")
			{
				$aksi   = "<a href=".HOME."billing.rename_container.print/print_nota_lunas?no_req=".$row[NO_RENAME]." ><img src='images/printer.png' title='print_nota'> <a href='#' onclick='return recalc(\"$row[ID_NOTA]\",\"$row[ID_BATALMUAT]\")' title='recalculate'><img src='images/money2.png' ></a></a>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else if (($row[STATUS]=="P") || ($row[STATUS]=="T"))
			{
				$aksi   = "<a href=".HOME."billing.rename_container.print/print_nota_lunas?no_req=".$row[NO_RENAME]." ><img src='images/printer.png' title='print_nota'></a>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else
			{
				$aksi = "<a href=".HOME."billing.rename_container/preview?id=".$row[NO_RENAME]." ><img src='images/preview.png' title='preview'></a>";
				$status="<b><font color='red'>nota belum disave</font></b>";
			}
			$vesvoy = $row['VESSEL'].'/'.$row['VOYAGE'];
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_NOTA],$row[NO_RENAME],$row[NO_EX_CONTAINER],$row[NO_CONTAINER],$vesvoy);
		}
		else if($q == 'l_backdate') 
		{
			$responce->rows[$i]['id']=$row[EX_ID_NOTA];
			$responce->rows[$i]['cell']=array($row[EX_ID_NOTA],$row[ID_REQ],$row[TGL_KOREKSI],$row[NO_BA],$row[NAME],  $row[KETERANGAN]);	
			//No Rename','Tanggal Rename','No Ex Container','No Container New','Vessel / Voyage'
		}
		else if($q == 'l_tarif') 
		{
			$aksi = "<a href=".HOME."maintenance.master.tarif/edit?id=".$row[ID_CONT]."&jenis=".urlencode($row[JENIS_BIAYA])."&val=".$row[VAL]."&oi=".$row[OI]." ><img src='images/preview.png'></a>";
			$responce->rows[$i]['id']=$row[NO_NOTA];
			$responce->rows[$i]['cell']=array($aksi, $row[ID_CONT], $row[UKURAN]." ".$row[TYPE]." ".$row[STATUS]." ".$row[HEIGHT_CONT], $row[JENIS_BIAYA], $row[JENIS_BIAYA2], $row[TARIF], $row[VAL], $row[OI], $row[START_PERIOD], $row[END_PERIOD]);	
			//No Rename','Tanggal Rename','No Ex Container','No Container New','Vessel / Voyage'
		}
		else if($q == 'nota_reekspor') 
		{
			$idn=$row[ID_NOTA];
			IF($idn=='')
			{
				$aksi = "<a href=".HOME."billing.reekspor/preview?id=".$row[ID_REQUEST]." ><img src='images/preview.png' title='preview'></a>";
			}
			else
				
				$aksi   = "<a href=".HOME."billing.reekspor.print/print_nota_lunas?pl=".$row[ID_REQUEST]." ><img src='images/printer.png' title='print_nota'></a>";
			$VOY=$row[NM_KAPAL].' '.$row[VOYAGE_IN].'-'.$row[VOYAGE_OUT];
			
			$responce->rows[$i]['id']=$row[ID_REQUEST];
			$responce->rows[$i]['cell']=array($aksi, $idn,$row[ID_REQUEST],$row[NO_UKK],$VOY,$row[NM_PEMILIK],$row[JML_CONT]);	
			//No Rename','Tanggal Rename','No Ex Container','No Container New','Vessel / Voyage'
		}
		else if($q == 'nota_stackext') 
		{
			$idn=$row[ID_NOTA];
			IF($idn=='')
			{
				$aksi = "<a href=".HOME."billing.perp_export/preview?id=".$row[ID_REQ]." ><img src='images/preview.png' title='preview'></a>";
				$idn  = "<font color='red'><i> Nota Belum Cetak </i></font>";
			}
			else
				$aksi   = "<a href=".HOME."billing.perp_export.print/print_nota_lunas?no_req=".$row[ID_REQ]." ><img src='images/printer.png' title='print_nota'></a>";

			$responce->rows[$i]['id']=$row[ID_REQ];
			$responce->rows[$i]['cell']=array($aksi, $idn,$row[ID_REQ],$row[VESSEL],$row[VOYAGE_IN].'/'.$row[VOYAGE_OUT],$row[SHIPPING_LINE],$row[JML_CONT],$row[TGL_REQ]);	
			//No Rename','Tanggal Rename','No Ex Container','No Container New','Vessel / Voyage'
		}

		$i++;
	}
	echo json_encode($responce);
}
?>