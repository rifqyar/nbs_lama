<?php
$q = $_GET['q'];
$list_det_ukk = $_GET['list_det_ukk'];
$no_ukks = $_GET['no_ukks'];
//$no_ukk = $_GET['no_ukk'];
//$no_cont = $_GET['no_cont'];
$no_bundle = $_GET['no_bundle'];
//PRINT_r($q);die;
//debug($no_bundle);die;
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
		//$query=OCIparse($conn, "SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM $tb_ntbprph WHERE STATUS!='X')");
	}
	else if($q=='kurs')
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM OG_TR_KURS)";
	else if($q=='nota_bm')
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM OG_HNOTA WHERE status!='X')";
	else if($q=='nota_dk')
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM OG_HNOTA2 WHERE status!='X')";
	else if($q=='l_delivery')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TB_REQ_DELIVERY_H )";
	else if($q=='nota_delivery')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TB_NOTA_DELIVERY_H )";
	else if($q=='print_delivery')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TB_REQ_DELIVERY_H )";
	else if($q=='pranota_lolo')
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM OG_NOTA_LOLOH WHERE STATUS!='X')";
	else if($q=='l_receiving')
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TB_REQ_RECEIVING_H)";
	else if($q=='monitoring_yard')
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM REPORT_YARD_VESSEL_D WHERE TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD_VESSEL_D))";
	else if($q=='nota_receiving')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TB_NOTA_RECEIVING_H )";
	else if($q=='print_stacking_card')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TB_REQ_RECEIVING_H )";
	else if($q=='l_behandle')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM BH_REQUEST)";
	else if($q=='nota_behandle')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM BH_NOTA)";
	else if($q=='print_behandle')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM BH_NOTA)";
	else if($q=='l_vessel')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TR_VESSEL_SCHEDULE_ICT)";
	else if($q=='booking')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TR_VESSEL_SCHEDULE_ICT)";
	else if($q=='rbm')		
		//$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TR_VESSEL_SCHEDULE_ICT)";
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_H)";
	else if($q=='m_rbm')		
		//$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TR_VESSEL_SCHEDULE_ICT)";
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_H)";
	else if($q=='bm')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_H)";
	else if($q=='hatch')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TR_VESSEL_SCHEDULE_ICT)";
	else if($q=='bapli')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TR_VESSEL_SCHEDULE_ICT)";
	else if($q=='stowage_final')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TR_VESSEL_SCHEDULE_ICT)";
	else if($q=='list_detail_rbm')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_LIST)";
	else if($q=='bundle')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM RBM_LIST WHERE BUNDLE IS NOT NULL";
	else if($q=='edit_bundle')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM RBM_LIST WHERE BUNDLE = '$no_bundle'";
	else if($q=='shifting_og')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_SHIFT WHERE NO_UKK='$no_ukks')";
	else if($q=='hatch_mv')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_HM WHERE NO_UKK='$no_ukks')";
	else if($q=='cont_move')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM MX_RBM_HEADER)";	
	else if($q=='list_detail_cont_move')		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM MX_RBM_DETAIL)";	
	//print_r($query);die;
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
		$query="SELECT * FROM OG_TR_KURS ORDER BY START_DATE DESC";
	else if($q=='nota_bm')
		$query="SELECT * FROM OG_HNOTA WHERE status!='X' ORDER BY rta DESC";
	else if($q=='nota_dk')
		$query="SELECT * FROM OG_HNOTA2 WHERE status!='X' ORDER BY rta DESC";
	else if($q=='l_delivery') //ambil data header
		$query="SELECT A.ID_REQ,A.EMKL,A.VESSEL,A.VOYAGE,(SELECT SUM(B.JUMLAH) FROM TB_REQ_DELIVERY_D B WHERE B.ID_REQ=A.ID_REQ) JUMLAH_CONT,A.STATUS FROM TB_REQ_DELIVERY_H A ORDER BY A.ID_REQ DESC";
	else if($q=='nota_delivery') //ambil data header
		$query="SELECT A.ID_REQ,C.ID_NOTA,A.EMKL,A.VESSEL,A.VOYAGE,(SELECT SUM(B.JUMLAH) FROM TB_REQ_DELIVERY_D B WHERE B.ID_REQ=A.ID_REQ) JUMLAH_CONT,C.STATUS FROM TB_REQ_DELIVERY_H A,TB_NOTA_DELIVERY_H C  WHERE A.ID_REQ=C.ID_REQ ORDER BY A.ID_REQ DESC";
	else if($q=='pranota_lolo')	//ambil data header
		$query="SELECT B.ID_BPRP,B.TERMINAL, B.NM_PBM,B.NM_CUST,B.VESSEL,B.VOYAGE,
						B.ETA,B.NM_GUDLAP, A.STATUS,A.ID_NOTA, A.TGL_INVOICE, A.TOTAL, A.NO_JKM FROM OG_NOTA_LOLOH A, OG_BPRPH B WHERE A.STATUS!='X' AND A.ID_BPRP=B.ID_BPRP ORDER BY A.ID_NOTA DESC";
	else if($q=='print_delivery') //ambil data header
		$query="SELECT A.ID_REQ,C.ID_NOTA,A.EMKL,A.VESSEL,A.VOYAGE,(SELECT COUNT(*) FROM TB_REQ_DELIVERY_CONT D WHERE D.ID_REQ=A.ID_REQ) JUMLAH_CONT,C.STATUS FROM TB_REQ_DELIVERY_H A,TB_NOTA_DELIVERY_H C WHERE A.ID_REQ=C.ID_REQ AND (SELECT COUNT(*) FROM TB_REQ_DELIVERY_CONT D WHERE D.ID_REQ=A.ID_REQ)<>0 ORDER BY A.ID_REQ DESC";	
	
	else if($q=='l_receiving') //ambil data header
		$query="SELECT a.ID_REQ,
                         b.NAMA EMKL,
                         a.VESSEL,
                         a.VOYAGE,
                         NVL((SELECT COUNT (c.NO_CONTAINER)JUMLAH_CONT FROM tb_req_receiving_d c WHERE c.ID_REQ = a.ID_REQ),0) JUMLAH_CONT,
						 a.STATUS
                    FROM tb_req_receiving_h a, master_pbm b
                   WHERE a.KODE_PBM = b.KODE_PBM
                GROUP BY a.ID_REQ,
                         b.NAMA,
                         a.VESSEL,
                         a.VOYAGE,
                           a.STATUS";
	else if($q=='monitoring_yard') //ambil data header
		$query="SELECT * FROM REPORT_YARD_VESSEL_D WHERE TGL_UPDATE = (SELECT(MAX(TGL_UPDATE)) FROM REPORT_YARD_VESSEL_D)";
	else if($q=='nota_receiving') //ambil data header
		$query="SELECT a.ID_REQ, NVL((SELECT NVL(d.ID_NOTA_ICT,d.ID_NOTA) FROM tb_nota_receiving_h d WHERE d.ID_REQ = a.ID_REQ),'-') ID_NOTA,
                         b.NAMA EMKL,
                         a.VESSEL,
                         a.VOYAGE,
                         NVL((SELECT COUNT (c.NO_CONTAINER)JUMLAH_CONT FROM tb_req_receiving_d c WHERE c.ID_REQ = a.ID_REQ),0) JUMLAH_CONT,
						 NVL((SELECT d.STATUS FROM tb_nota_receiving_h d WHERE d.ID_REQ = a.ID_REQ),'NOTSAVED') STATUS,
						 NVL((SELECT d.ID_NOTA_ICT FROM tb_nota_receiving_h d WHERE d.ID_REQ = a.ID_REQ),'') ID_NOTA_ICT,
						 NVL((SELECT d.NO_FAKTUR FROM tb_nota_receiving_h d WHERE d.ID_REQ = a.ID_REQ),'') NO_FAKTUR
                    FROM tb_req_receiving_h a, master_pbm b
                   WHERE a.KODE_PBM = b.KODE_PBM
                GROUP BY a.ID_REQ,
                         b.NAMA,
                         a.VESSEL,
                         a.VOYAGE";
	else if($q=='print_stacking_card') //ambil data header
		$query="SELECT a.ID_REQ,
                         b.NAMA EMKL,
                         a.VESSEL,
                         a.VOYAGE,
                         NVL((SELECT COUNT (c.NO_CONTAINER)JUMLAH_CONT FROM tb_req_receiving_d c WHERE c.ID_REQ = a.ID_REQ),0) JUMLAH_CONT,
						 NVL((SELECT d.STATUS FROM tb_nota_receiving_h d WHERE d.ID_REQ = a.ID_REQ),'NOT_SAVED') STATUS
                    FROM tb_req_receiving_h a, master_pbm b
                   WHERE a.KODE_PBM = b.KODE_PBM
                GROUP BY a.ID_REQ,
                         b.NAMA,
                         a.VESSEL,
                         a.VOYAGE";
	else if($q=='l_behandle') //ambil data header
		$query="SELECT A.ID_REQUEST,A.EMKL,A.TGL_REQUEST,A.VESSEL,A.VOYAGE,A.NOMOR_INSTRUKSI,(SELECT COUNT(B.NO_CONTAINER) FROM BH_DETAIL_REQUEST B WHERE A.ID_REQUEST=B.ID_REQUEST ) JUMLAH_CONT,A.STATUS FROM BH_REQUEST A ORDER BY A.TGL_REQUEST DESC";
	else if($q=='nota_behandle') //ambil data header
		$query="SELECT A.ID_REQUEST,C.ID_NOTA,A.EMKL,A.VESSEL,A.VOYAGE,(SELECT COUNT(B.NO_CONTAINER) FROM BH_DETAIL_REQUEST B WHERE A.ID_REQUEST=B.ID_REQUEST) JUMLAH_CONT,C.STATUS FROM BH_REQUEST A,BH_NOTA C  WHERE A.ID_REQUEST=C.ID_REQUEST ORDER BY A.TGL_REQUEST DESC";	
	else if($q=='print_behandle') //ambil data header
		$query="SELECT A.ID_REQUEST,C.ID_NOTA,A.EMKL,A.VESSEL,A.VOYAGE,(SELECT COUNT(B.NO_CONTAINER) FROM BH_DETAIL_REQUEST B WHERE A.ID_REQUEST=B.ID_REQUEST) JUMLAH_CONT,C.STATUS FROM BH_REQUEST A,BH_NOTA C  WHERE A.ID_REQUEST=C.ID_REQUEST ORDER BY A.TGL_REQUEST DESC";	
	else if($q=='l_vessel') //ambil data header
		$query="SELECT a.ID_VS, b.NAMA_VESSEL , a.VOYAGE, a.ETA, a.ETD, a.RTA, a.RTD, a.STATUS FROM VESSEL_SCHEDULE a, MASTER_VESSEL b WHERE a.ID_VES = b.KODE_KAPAL";
	else if($q=='stowage_final') //ambil data header
		$query="SELECT a.ID_VS, b.NAMA_VESSEL , a.VOYAGE, a.ETA, a.ETD, a.RTA, a.RTD, a.STATUS FROM VESSEL_SCHEDULE a, MASTER_VESSEL b WHERE a.ID_VES = b.KODE_KAPAL";
	else if($q=='booking') //ambil data header
		$query="SELECT NO_UKK ID_VS, NM_KAPAL NAMA_VESSEL , VOYAGE_IN ,VOYAGE_OUT, TGL_JAM_TIBA ETA, TGL_JAM_BERANGKAT ETD, NM_PELABUHAN_ASAL, NM_PELABUHAN_TUJUAN FROM TR_VESSEL_SCHEDULE_ICT";
	else if($q=='rbm') //ambil data header
		$query="SELECT NO_UKK ID_VS, NM_KAPAL NAMA_VESSEL , VOYAGE_IN ,VOYAGE_OUT, TGL_JAM_TIBA ETA, TGL_JAM_BERANGKAT ETD, NM_PELABUHAN_ASAL, NM_PELABUHAN_TUJUAN, STATUS, EMAIL, JML_BONGKAR, JML_MUAT, JML_SHIFT, JML_HATCH FROM RBM_H";
	else if($q=='m_rbm') //ambil data header
		$query="SELECT A.EMAIL,A.TGL_FINAL,A.NO_UKK ID_VS, A.NM_KAPAL NAMA_VESSEL , A.VOYAGE_IN ,A.VOYAGE_OUT, A.TGL_JAM_TIBA ETA, A.TGL_JAM_BERANGKAT ETD, A.NM_PELABUHAN_ASAL, 
                A.NM_PELABUHAN_TUJUAN, A.STATUS, 
                A.EMAIL, A.JML_BONGKAR, A.JML_MUAT, A.JML_SHIFT, A.JML_HATCH, 
                A.PRANOTA, A.KOREKSI, 
                (SELECT C.TGL_KIRIM from LOG_EMAIL C WHERE C.ID_EMAIL=(SELECT MAX(B.ID_EMAIL) FROM LOG_EMAIL B WHERE B.NO_UKK=A.NO_UKK AND B.MODUL='RBM')
                AND C.NO_UKK=A.NO_UKK
                ) TGL_EMAIL,
				(SELECT COUNT(1) FROM LOG_EMAIL D WHERE D.NO_UKK=A.NO_UKK AND D.MODUL='RBM') JML_EMAIL
                FROM RBM_H A";
	else if($q=='bm') //ambil data header
		$query="SELECT NOTA, NO_UKK ID_VS, NM_KAPAL NAMA_VESSEL , NM_PEMILIK NAMA_PEMILIK, VOYAGE_IN ,VOYAGE_OUT, TGL_JAM_TIBA ETA, TGL_JAM_BERANGKAT ETD, NM_PELABUHAN_ASAL, NM_PELABUHAN_TUJUAN, PRANOTA FROM RBM_H";
	else if($q=='hatch') //ambil data header
		$query="SELECT NO_UKK ID_VS, NM_KAPAL NAMA_VESSEL , VOYAGE_IN ,VOYAGE_OUT, TGL_JAM_TIBA ETA, TGL_JAM_BERANGKAT ETD, NM_PELABUHAN_ASAL, NM_PELABUHAN_TUJUAN FROM TR_VESSEL_SCHEDULE_ICT";
	///print_r($query);die;
	else if($q=='bapli') //ambil data header
		$query="SELECT NO_UKK ID_VS, NM_KAPAL NAMA_VESSEL , VOYAGE_IN ,VOYAGE_OUT, TGL_JAM_TIBA ETA, TGL_JAM_BERANGKAT ETD, NM_PELABUHAN_ASAL, NM_PELABUHAN_TUJUAN FROM TR_VESSEL_SCHEDULE_ICT";
	else if($q=='list_detail_rbm') //ambil data header
		$query="SELECT NO_UKK , NO_CONTAINER , SIZE_CONT, TYPE_CONT, STATUS_CONT, HEIGHT_CONT, GROSS, E_I, HZ, TRS FROM RBM_LIST WHERE NO_UKK= '$list_det_ukk'";
	else if($q=='bundle') //ambil data header
		$query="SELECT NO_UKK , NO_CONTAINER , SIZE_CONT, TYPE_CONT, STATUS_CONT, HEIGHT_CONT, E_I, HZ, BUNDLE,DASAR_BUNDLE FROM RBM_LIST WHERE BUNDLE IS NOT NULL ORDER BY BUNDLE DESC, DASAR_BUNDLE ASC";
	else if($q=='edit_bundle') //ambil data header
		$query="SELECT NO_UKK ,NO_CONTAINER, BUNDLE,DASAR_BUNDLE FROM RBM_LIST WHERE BUNDLE ='$no_bundle' ORDER BY BUNDLE DESC, DASAR_BUNDLE ASC";
	else if($q=='shifting_og') //ambil data header
		$query="SELECT NO_UKK , NO_CONTAINER , SIZE_C, TYPE_C, STATUS_C, HEIGHT_C, EI, HZ, JENIS_SHIFT,JUMLAH_SHIFT, 
				ALAT_SHIFT
				FROM RBM_SHIFT WHERE NO_UKK= '$no_ukks'";
	else if($q=='hatch_mv') //ambil data header
		$query="SELECT NO_UKK ,BAY,ALAT, ACTIVITY, MOVE_TIME, OI, OPERATOR, JUMLAH FROM RBM_HM WHERE NO_UKK= '$no_ukks'";
	else if($q=='cont_move') //ambil data header
		$query="SELECT NO_UKK ID_VS, 
            ID_VESSEL, 
            VESSEL, 
            VOYAGE_IN ,
            VOYAGE_OUT, 
            RTA, 
            RTD, 
            PEL_ASAL, 
            PEL_TUJUAN, 
            PEL_BERIKUTNYA,
            TO_CHAR(RTA,'YYYYMMDD') AS SEQ 
 FROM MX_RBM_HEADER
 ORDER BY SEQ DESC";
	else if($q=='list_detail_cont_move') //ambil data header
		$query="SELECT 
						NO_UKK, ID_CONT , 
						SIZE_, 
						TYPE_, 
						STATUS, 
						WEIGHT, 
						CLASS_, 
						TEMP, 
						CASE POD
							 WHEN 'TGP' THEN 'MYTPP'
							 WHEN 'ALBA' THEN 'AUALH'
							 WHEN 'ESP' THEN 'AUEPR'
							 WHEN 'SHA' THEN 'CNSHA'
							 WHEN 'SHKO' THEN 'SHEKOU'
							 WHEN 'HKG' THEN 'HKHKG'
							 WHEN 'AKI' THEN 'JPAXT'
							 WHEN 'KLP' THEN 'MYPKG'
							 WHEN 'POM' THEN 'PGPOM'
							 WHEN 'RAB' THEN 'PGRAB'
							 WHEN 'SIN' THEN 'SGSIN'
							 WHEN 'LAC' THEN 'THLCH'
							 WHEN 'KEE' THEN 'TWKEL'
							 WHEN 'KHH' THEN 'TWKHH'
							 END AS POD, 
						ID_STATUS, 
						CASE ID_STATUS 
							WHEN '49' THEN '-'
							WHEN '01' THEN '-'
							WHEN '02' THEN '-'
							WHEN '03' THEN '-'
							ELSE to_char(GATE_IN,'DD-MON-YYYY:HH24:MI') END as GATE_IN, 
                        to_char(GATE_OUT,'DD-MON-YYYY:HH24:MI') as GATE_OUT, 
						    
						CASE ID_STATUS 
							WHEN '49' THEN '-'
							WHEN '50' THEN '-'
							WHEN '01' THEN '-'
							WHEN '03' THEN '-'
							WHEN '02' THEN '-'
							ELSE to_char(PLACEMENT,'DD-MON-YYYY:HH24:MI') 
							END AS PLACEMENT,
							
						CASE PLUG_IN 
							WHEN NULL THEN '-'
							ELSE to_char(PLUG_IN,'DD-MON-YYYY:HH24:MI')  
							END as PLUG_IN,  
                        
						CASE PLUG_OUT 
							WHEN NULL THEN '-'
							ELSE to_char(PLUG_OUT,'DD-MON-YYYY:HH24:MI') 
							END as PLUG_OUT,
						STAT_SEGEL, 
						CONSIGNEE, 
						EMKL, 
						POS_CY, 
						NPE, 
						NO_SEAL, 
						OPERATOR_SHIP, 
						REMARK_CONTAINER, 
						BLOK, 
						SLOT, 
						ROW_,
						TIER 
				FROM MX_RBM_DETAIL 
				WHERE NO_UKK= '$list_det_ukk'
				ORDER BY ID_STATUS";		
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
			if($row[STATUS]=="SAVED")
			{
				$aksi = "<blink><font color='red'><b><i>Request Saved</i></b></font></blink>";	
			}
			else
			{
				$aksi = "<a href=".HOME."request.delivery/edit?id=".$row[ID_REQ]." target='blank'><img src='images/edit.png' title='edit request'></a>&nbsp;<a href=".HOME."request.delivery/add_cont_print?id=".$row[ID_REQ]." target='blank'><img src='images/vedit.png' title='add container'></a>";	
			}
			$responce->rows[$i]['id']=$row[ID_REQ];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_REQ],$row[EMKL],$row[VESSEL],$row[VOYAGE],$row[JUMLAH_CONT]);
		}
		else if($q == 'nota_delivery') 
		{
			
			if($row[STATUS]=="SAVED")
			{
				$aksi = "<a href=".HOME."print/print_nota_sp2_manual?pl=".$row[ID_NOTA]." target='blank'><img src='images/printer.png' title='print_nota'></a>";
				$status="<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else
			{
				$aksi = "<a href=".HOME."billing.delivery/preview?id=".$row[ID_NOTA]." target='blank'><img src='images/preview.png' title='preview'></a>";
				$status="<b><font color='red'>nota belum disave</font></b>";
			}
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_NOTA],$row[ID_REQ],$row[EMKL],$row[VESSEL].' / '.$row[VOYAGE],$row[JUMLAH_CONT],$status);
		}
		else if($q == 'print_delivery') 
		{
			
			if($row[STATUS]=="SAVED")
			{
				$aksi = "<a href=".HOME."print.sp2.cetak/?pl=".$row[ID_NOTA]." target='blank'><img src='images/printer.png' title='print_nota'></a>";
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
			if($row[STATUS]=="SAVED")
			{
				$aksi = "<blink><font color='red'><b><i>Request Saved</i></b></font></blink>";	
			}
			else
			{
				$aksi = "<a href=".HOME."request.anne/edit?no_req=".$row[ID_REQ]." target='blank'><img src='images/edit.png' title='edit request'></a>";	
			}
			$responce->rows[$i]['id']=$row[ID_REQ];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_REQ],$row[EMKL],$row[VESSEL],$row[VOYAGE],$row[JUMLAH_CONT]);
		}
		else if($q == 'monitoring_yard') 
		{
			$responce->rows[$i]['id']=$row[ID];
			$responce->rows[$i]['cell']=array($row[NO_CONTAINER],$row[SIZE_],$row[TYPE_],$row[STATUS],$row[BERAT],$row[VESSEL],$row[VOYAGE],$row[PELABUHAN_ASAL],$row[PELABUHAN_TUJUAN],$row[BLOK_PLANNING].'-'.$row[SLOT_PLANNING].'-'.$row[ROW_PLANNING].'-'.$row[TIER_PLANNING],$row[BLOK_REALISASI].'-'.$row[SLOT_REALISASI].'-'.$row[ROW_REALISASI].'-'.$row[TIER_REALISASI],$row[TGL_GATE_IN],$row[TGL_PLACEMENT], $row[DURASI]);
		}
		else if($q == 'nota_receiving') 
		{
			if($row[STATUS]=="SAVED")
			{
				if (($row[ID_NOTA_ICT] == NULL) && ($row[NO_FAKTUR] == NULL ))
				{
					IF ($id_group == 1)
					{
						$aksi   = "<a href=".HOME."billing.receiving.print/print_nota_lunas?no_req=".$row[ID_REQ]." target='blank'><img src='images/printer.png' title='print_nota'></a>  |  <a href=".HOME."billing.receiving/edit_nota?no_req=".$row[ID_REQ]." target='blank'><img src='images/edit.png' title='insert nota dan faktur'></a>";
					} 
					ELSE 
					{
						$aksi   = "<a href=".HOME."billing.receiving.print/print_nota_lunas?no_req=".$row[ID_REQ]." target='blank'><img src='images/printer.png' title='print_nota_dama'></a>";
					}
				} 
				else 
				{
					$aksi   = "<a href=".HOME."billing.receiving.print/print_nota_lunas?no_req=".$row[ID_REQ]." target='blank'><img src='images/printer.png' title='print_nota'></a>";
				}
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else
			{
				$aksi = "<a href=".HOME."billing.receiving/preview?id=".$row[ID_REQ]." target='blank'><img src='images/preview.png' title='preview'></a>";
				$status="<b><font color='red'>nota belum disave</font></b>";
			}
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_NOTA],$row[ID_REQ],$row[EMKL],$row[VESSEL].'/'.$row[VOYAGE],$row[JUMLAH_CONT],$status);
		}
		else if($q == 'print_stacking_card') 
		{
			if($row[STATUS]=="SAVED")
			{
				$aksi   = "<a href=".HOME."print.stacking_card.cetak/cetak?no_req=".$row[ID_REQ]." target='blank'><img src='images/printer.png' title='cetak kartu'></a>";
			}
			else
			{
				//$aksi = "<a href=".HOME."print.stacking_card.cetak/index?no_req=".$row[ID_REQ]." target='blank'><img src='images/preview.png' title='preview'></a>";
				$aksi="<b><font color='red'>nota belum disave</font></b>";
			}
			$responce->rows[$i]['id']=$row[ID_REQ];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_REQ],$row[EMKL],$row[VESSEL].'/'.$row[VOYAGE],$row[JUMLAH_CONT]);
		}
		else if($q == 'l_behandle') 
		{
			if($row[STATUS]=="SAVED")
			{
				$aksi = "<blink><font color='red'><b><i>Request Saved</i></b></font></blink>";	
			}
			else
			{
				$aksi = "<a href=".HOME."request.behandle/save?id=".$row[ID_REQUEST]." target='blank'><img src='images/edit.png' title='edit request'></a>";	
			}
			$responce->rows[$i]['id']=$row[ID_REQUEST];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_REQUEST],$row[EMKL],$row[TGL_REQUEST],$row[VESSEL].' / '.$row[VOYAGE],$row[NOMOR_INSTRUKSI],$row[JUMLAH_CONT]);
		}
		
	else if($q == 'nota_behandle') 
		{
			
			if($row[STATUS]=="SAVED")
			{
				$aksi = "<a href=".HOME."print/print_nota_behandle?pl=".$row[ID_NOTA]." target='blank'><img src='images/printer.png' title='print_nota'></a>";
				$status="<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else
			{
				$aksi = "<a href=".HOME."billing.behandle/preview?id=".$row[ID_NOTA]." target='blank'><img src='images/preview.png' title='preview'></a>";
				//$aksi='cb';
				$status="<b><font color='red'>nota belum disave</font></b>";
			}
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_NOTA],$row[ID_REQUEST],$row[EMKL],$row[VESSEL].' / '.$row[VOYAGE],$row[JUMLAH_CONT],$status);
		}
		else if($q == 'print_behandle') 
		{
			
			if($row[STATUS]=="SAVED")
			{
				$aksi = "<a href=".HOME."print.behandle.cetak/?pl=".$row[ID_NOTA]." target='blank'><img src='images/printer.png' title='print_nota'></a>";
				
			}
			else
			{
				$aksi = "<b><font color='red'>nota belum disave</font></b>";
			}
			$responce->rows[$i]['id']=$row[ID_NOTA];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_NOTA],$row[EMKL],$row[VESSEL].'/'.$row[VOYAGE],$row[JUMLAH_CONT]);
		}
		
		else if($q == 'l_vessel') 
		{
			
			if($row[STATUS]=="AKTIF")
			{
				$aksi = "<a href=".HOME."planning.vessel_schedule/upload/?id_vessel=".$row[ID_VS]." target='blank'><img src='images/upload_excel.png' width=30px height=30px title='upload container format excel'></a>";
				$aksi2 = "<a href=".HOME."planning.vessel_schedule/upload_csv/?id_vessel=".$row[ID_VS]." target='blank'><img src='images/upload_excel.png' width=30px height=30px title='upload container format csv'></a>";
				
				
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
			$aksi = "<a href=".HOME."planning.booking/upload/?id_vessel=".$row[ID_VS]." target='blank'><img src='images/upload_excel.png' width=30px height=30px border='0' title='upload container format excel'></a>";
			$aksi2 = "<a href=".HOME."planning.booking/insert_booking/?id_vessel=".$row[ID_VS]."><img border='0' src='images/add.png' width='20' height='20'></a>";
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($aksi,$aksi2,$row[NAMA_VESSEL],$row[VOYAGE_IN],$row[VOYAGE_OUT], $row[ETA],$row[ETD],$row[NM_PELABUHAN_ASAL],$row[NM_PELABUHAN_TUJUAN]);
		}
		else if($q == 'rbm') 
		{
			$pw=$row[ID_VS];
			if ($row[STATUS] == "CLOSED"){
				if ($row[EMAIL] == "Y"){
					$status = "<img src='images/gmail.png' width=25px height=25px border='0' title='Email Sent'> | <a onclick='final_rbm(\"$pw\")' target='blank'><img src='images/save2.png' width=20px height=20px border='0' title='Final RBM'></a> | <a onclick='koreksi_rbm(\"$pw\")' target='blank'><img src='images/koreksi.jpg' width=20px height=20px border='0' title='Koreksi RBM'></a>";
				} else {
					$status = "<a href=".HOME."billing.rbm/sent_email/?id_vessel=".$row[ID_VS]." target='blank'><img src='images/sent.jpg' width=20px height=20px border='0' title='Send Email'></a>";
				}
			} else if ($row[STATUS] == "FINAL"){
					$status = "<font color='red'><i><b>FINAL RBM</b></i></font>";
			}
			else {
				$status = "<a onclick='closed_rbm(\"$pw\")' target='blank'><img src='images/save.png' width=20px height=20px border='0' title='Save RBM'></a>";
			}
			//$status = 'dama';
			$aksi = "<div><a href=".HOME."billing.rbm.cetak/cetak_rbm?id_vessel=".$row[ID_VS]." target='blank'><img src='images/print.png' width=20px height=20px border='0' title='cetak RBM'></a>&nbsp;<a href=".HOME."billing.rbm.cetak/cetak_rbm2?id_vessel=".$row[ID_VS]." target='blank'><img src='".HOME."images/print_2.png' width=20px height=20px border='0' title='cetak RBM format baru'></a></div>";
			$list = "<div><a href=".HOME."billing.rbm.list_detail/list_detail?id_vessel=".$row[ID_VS]." target='blank'>DETAIL</a></div>";
			$sc		= "<button onclick='sync_rbm(\"$pw\")' title='update RBM'><img src='images/Refresh2.png' width=15px height=15px border='0'></button>&nbsp;<button onclick='get_data(\"$pw\")' title='get data ict'><img src='images/Refresh3.png' width=15px height=15px border='0'></button>";
			$voy	= $row[VOYAGE_IN].' - '.$row[VOYAGE_OUT];
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($status,$sc,$aksi,$row[ID_VS],$row[NAMA_VESSEL],$voy,$row[JML_BONGKAR],$row[JML_MUAT], $row[ETA],$row[ETD],$row[NM_PELABUHAN_ASAL],$row[NM_PELABUHAN_TUJUAN],$list);
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
			$sc	  = "<button onclick='sync_rbm(\"$pw\")' title='update RBM'><img src='images/Refresh2.png' width=15px height=15px border='0'></button>&nbsp;<button onclick='get_data(\"$pw\")' title='get data ict'><img src='images/Refresh3.png' width=15px height=15px border='0'></button>";
			$voy  = '<div max-height=50px><br>'.$row[NAMA_VESSEL].' '.$row[VOYAGE_IN].' - '.$row[VOYAGE_OUT].'<br><br></div>';
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($row[ID_VS],$voy,"<b><font color=$clr2>$sts</font></b><br><font color=$clr><b>$row[STATUS]</b><br><b>$row[TGL_FINAL]</b></font>",
										"<b><font color=$clr3>$sts2</font></b><br><font color=$clr><b>$row[TGL_EMAIL]</b></font>", 
										"-","-","-","-");
		}
		
		else if($q == 'bm') 
		{
			$pw=$row[ID_VS];
			if ($row[NOTA] == "N"){
				if ($row[PRANOTA] == "Y"){
					$aksi = "<a onclick='save_pranota(\"$pw\")' target='blank'><img src='images/save2.png' width=25px height=25px border='0' title='Save Pranota'></a> | <a href=".HOME."billing.bm.cetak/print_pranota/?id_vessel=".$row[ID_VS]." target='blank'><img src='images/print.png' width=25px height=25px border='0' title='Cetak Pranota'></a>  | <a href=".HOME."billing.bm/sent_email/?id_vessel=".$row[ID_VS]." target='blank'><img src='images/email.jpg'  width=25px height=25px border='0' title='Sent Email'></a> ";
				} else {
					$aksi = "<a href=".HOME."billing.bm/preview/?id_vessel=".$row[ID_VS]." target='blank'><img src='images/preview.jpg' width=25px height=25px border='0' title='Preview Nota'></a> ";
				}	
			} else {
				$aksi = "<a href=".HOME."billing.bm/sent_email/?id_vessel=".$row[ID_VS]." target='blank'><img src='images/email.jpg' width=25px height=25px border='0' title='Sent Email'></a> | <a href=".HOME."billing.bm.cetak/cetak_nota/?id_vessel=".$row[ID_VS]." target='blank'><img src='images/print.png' width=25px height=25px border='0' title='cetak Nota Bongkar Muat'></a>";
			}	
			
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($aksi,$row[NAMA_VESSEL],$row[VOYAGE_IN].'/'.$row[VOYAGE_OUT],$row[NAMA_PEMILIK],$row[ETA],$row[ETD],$row[NM_PELABUHAN_ASAL],$row[NM_PELABUHAN_TUJUAN]);
		}
		else if($q == 'hatch') 
		{
			$aksi = "<a href=".HOME."operational.hatch_move/add_hatch/?id_vessel=".$row[ID_VS]." target='blank'><img border='0' src='images/add.png' width='20' height='20'></a>";
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($aksi,$row[NAMA_VESSEL],$row[VOYAGE_IN],$row[VOYAGE_OUT], $row[ETA],$row[ETD],$row[NM_PELABUHAN_ASAL],$row[NM_PELABUHAN_TUJUAN]);
		}
		else if($q == 'bapli') 
		{
			$aksi = "<a href=".HOME."planning.upload_bapli/upload/?id_vessel=".$row[ID_VS]." target='blank'><img src='images/csv.gif' width=30px height=30px border='0' title='upload container format csv'></a>";
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($aksi,$row[NAMA_VESSEL],$row[VOYAGE_IN],$row[VOYAGE_OUT], $row[ETA],$row[ETD],$row[NM_PELABUHAN_ASAL],$row[NM_PELABUHAN_TUJUAN]);
		}
		else if($q == 'stowage_final') 
		{
			$aksi = "<a href=".HOME."operational.stowage_final.download/download_csv/?id_vessel=".$row[ID_VS]." target='blank'><img src='images/download1.png' width=30px height=30px title='download csv'></a>";				
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($aksi,$row[NAMA_VESSEL],$row[VOYAGE],$row[ETA],$row[ETD],$row[RTA],$row[RTD],$row[STATUS]);
		}
		else if($q == 'list_detail_rbm') 
		{
			//$aksi = "<div><a href=".HOME."billing.rbm.cetak/cetak_rbm?id_vessel=".$row[ID_VS]." target='blank'><img src='images/editp.png' width=30px height=30px border='0' title='cetak RBM'></a></div>";
			$edit_oog = "<div><a href='javascript:oog(\"$row[NO_UKK]\" , \"$row[NO_CONTAINER]\" , \"$row[SIZE_CONT]\" , \"$row[TYPE_CONT]\" , \"$row[STATUS_CONT]\" , \"$row[HEIGHT_CONT]\" , \"$row[GROSS]\" , \"$row[E_I]\" , \"$row[HZ]\" , \"$row[TRS]\")'><img src='images/editp.png' /></a></div>";
			//$edit_bundle = "<div><a href='javascript:bundle(\"$row[NO_UKK]\" , \"$row[NO_CONTAINER]\" , \"$row[SIZE_CONT]\" , \"$row[TYPE_CONT]\" , \"$row[STATUS_CONT]\" , \"$row[HEIGHT_CONT]\" , \"$row[GROSS]\" , \"$row[E_I]\" , \"$row[HZ]\" , \"$row[TRS]\")'><img src='images/editp.png' /></a></div>";
			$list = "<div><a href=".HOME."billing.rbm.list_detail/list_detail?id_vessel=".$row[ID_VS]." target='blank'>DETAIL</a></div>";
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($edit_oog,$row[NO_UKK],$row[NO_CONTAINER],$row[SIZE_CONT], $row[TYPE_CONT],$row[STATUS_CONT],$row[HEIGHT_CONT],$row[GROSS],$row[E_I],$row[HZ],$row[TRS]);
		}
		else if($q == 'bundle') 
		{
			//$aksi = "<div><a href=".HOME."billing.rbm.cetak/cetak_rbm?id_vessel=".$row[ID_VS]." target='blank'><img src='images/editp.png' width=30px height=30px border='0' title='cetak RBM'></a></div>";
			$nukk=$row[NO_UKK];
			$nct=$row[NO_CONTAINER];
			$no_bundle=$row[BUNDLE];
			
			$bt="<button title='edit bundle' onclick='ed_bundle(\"$no_bundle\")'><img src='images/editx.png' width=15px height=15px border='0'></button>&nbsp;<button title='delete bundle' onclick='del_bundle(\"$nct\",\"$js\")'><img src='images/del2.png' width=15px height=15px border='0'></button>";
			$responce->rows[$i]['id']=$row[NO_UKK];
			//SELECT NO_UKK , NO_CONTAINER , SIZE_CONT, TYPE_CONT, STATUS_CONT, HEIGHT_CONT, E_I, HZ, BUNDLE,DASAR_BUNDLE FROM RBM_LIST WHERE BUNDLE IS NOT NULL
			$responce->rows[$i]['cell']=array($bt,$row[NO_UKK],$row[NO_CONTAINER],$row[SIZE_CONT], $row[TYPE_CONT],$row[STATUS_CONT],$row[HEIGHT_CONT],$row[E_I],$row[HZ],$row[BUNDLE],$row[DASAR_BUNDLE]);
		}
		else if($q == 'edit_bundle') 
		{
			//$aksi = "<div><a href=".HOME."billing.rbm.cetak/cetak_rbm?id_vessel=".$row[ID_VS]." target='blank'><img src='images/editp.png' width=30px height=30px border='0' title='cetak RBM'></a></div>";
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
			//$aksi = "<div><a href=".HOME."billing.rbm.cetak/cetak_rbm?id_vessel=".$row[ID_VS]." target='blank'><img src='images/editp.png' width=30px height=30px border='0' title='cetak RBM'></a></div>";
			$nct=$row[NO_CONTAINER];
			$js=$row[JENIS_SHIFT];
			
			$bt="<button title='edit shifting' onclick='ed_shift(\"$nct\",\"$js\")'><img src='images/editx.png' width=15px height=15px border='0'></button>&nbsp;<button title='delete shifting' onclick='del_shift(\"$nct\",\"$js\")'><img src='images/del2.png' width=15px height=15px border='0'></button>";
			$responce->rows[$i]['id']=$row[NO_UKK];
			$responce->rows[$i]['cell']=array($bt,$row[NO_UKK],$row[NO_CONTAINER],$row[SIZE_C], $row[TYPE_C],$row[STATUS_C],$row[HZ],$row[HEIGHT_C],$row[ALAT_SHIFT],$row[EI],$row[JENIS_SHIFT],$row[JUMLAH_SHIFT]);
		}
		else if($q == 'hatch_mv') 
		{
			//$aksi = "<div><a href=".HOME."billing.rbm.cetak/cetak_rbm?id_vessel=".$row[ID_VS]." target='blank'><img src='images/editp.png' width=30px height=30px border='0' title='cetak RBM'></a></div>";
			
			$bt="<button title='edit hatch' onclick='ed_hatch(\"$nct\",\"$js\")'><img src='images/editx.png' width=15px height=15px border='0'></button>&nbsp;<button title='delete hatch' onclick='del_hatch(\"$nct\",\"$js\")'><img src='images/del2.png' width=15px height=15px border='0'></button>";
			$responce->rows[$i]['id']=$row[NO_UKK];
			$responce->rows[$i]['cell']=array($bt,$row[NO_UKK],$row[BAY],$row[ALAT], $row[ACTIVITY],$row[MOVE_TIME],$row[OI],$row[OPERATOR],$row[JUMLAH]);
		}
		//NO_UKK , ID_CONTAINER , SIZE_, TYPE_, STATUS_, WEIGHT, CLASS, TEMP, POD, ID_STATUS, GATE_IN, GATE_OUT, PLACEMENT, PLUG_IN, PLUG_OUT, STAT_SEGEL, CONSIGNEE, EMKL, POS_CY, NPE, NO_SEAL, OPERATOR_SHIP
		else if($q == 'list_detail_cont_move') 
		{
			//$aksi = "<div><a href=".HOME."billing.rbm.cetak/cetak_rbm?id_vessel=".$row[ID_VS]." target='blank'><img src='images/editp.png' width=30px height=30px border='0' title='cetak RBM'></a></div>";
			//$edit_oog = "<div><a href='javascript:oog(\"$row[NO_UKK]\" , \"$row[ID_CONTAINER]\" , \"$row[SIZE_]\" , \"$row[TYPE_]\" , \"$row[STATUS_]\" , \"$row[WEIGHT]\" , \"$row[CLASS_]\" , \"$row[TEMP]\" , \"$row[POD]\" , \"$row[ID_STATUS]\", \"$row[GATE_IN]\", \"$row[GATE_OUT]\", \"$row[PLACEMENT]\", \"$row[PLUG_IN]\", \"$row[PLUG_OUT]\", \"$row[STAT_SEGEL]\", \"$row[CONSIGNEE]\", \"$row[EMKL]\", \"$row[POS_CY]\", \"$row[NPE]\", \"$row[NO_SEAL]\", \"$row[OPERATOR_SHIP]\")'><img src='images/editp.png' /></a></div>";
			//$edit_bundle = "<div><a href='javascript:bundle(\"$row[NO_UKK]\" , \"$row[NO_CONTAINER]\" , \"$row[SIZE_CONT]\" , \"$row[TYPE_CONT]\" , \"$row[STATUS_CONT]\" , \"$row[HEIGHT_CONT]\" , \"$row[GROSS]\" , \"$row[E_I]\" , \"$row[HZ]\" , \"$row[TRS]\")'><img src='images/editp.png' /></a></div>";
			$list = "<div><a href=".HOME."monitoring.cont_move.list_detail/list_detail?id_vessel=".$row[ID_VS]." target='blank'>DETAIL</a></div>";
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($row[NO_UKK],$row[ID_CONT],$row[SIZE_], $row[TYPE_],$row[STATUS],$row[WEIGHT],$row[CLASS_],$row[TEMP],$row[POD],$row[ID_STATUS],$row[GATE_IN],$row[GATE_OUT],$row[PLACEMENT],$row[PLUG_IN],$row[PLUG_OUT],$row[STAT_SEGEL],$row[CONSIGNEE],$row[EMKL],$row[POS_CY],$row[NPE],$row[NO_SEAL],$row[OPERTOR_SHIP],$row[REMARK_CONTAINER],$row[BLOK],$row[SLOT],$row[ROW_],$row[TIER]);
		}
		
		
		//CONT MOVEMENT
		else if($q == 'cont_move') 
		{
			$pw=$row[ID_VS];
			if ($row[STATUS] == "CLOSED"){
				if ($row[EMAIL] == "Y"){
					$status = "<img src='images/gmail.png' width=25px height=25px border='0' title='Email Sent'> | <a onclick='final_rbm(\"$pw\")' target='blank'><img src='images/save2.png' width=20px height=20px border='0' title='Final RBM'></a> | <a onclick='koreksi_rbm(\"$pw\")' target='blank'><img src='images/koreksi.jpg' width=20px height=20px border='0' title='Koreksi RBM'></a>";
				} else {
					$status = "<a href=".HOME."billing.rbm/sent_email/?id_vessel=".$row[ID_VS]." target='blank'><img src='images/sent.jpg' width=20px height=20px border='0' title='Send Email'></a>";
				}
			} else if ($row[STATUS] == "FINAL"){
					$status = "<font color='red'><i><b>FINAL RBM</b></i></font>";
			}
			else {
				$status = "<a onclick='closed_rbm(\"$pw\")' target='blank'><img src='images/save.png' width=20px height=20px border='0' title='Save RBM'></a>";
			}
			//$status = "<a href=".HOME."monitoring.cont_move/sent_email/?id_vessel=".$row[ID_VS]." target='blank'><img src='images/sent.jpg' width=20px height=20px border='0' title='Send Email'></a>";
			//$status = 'dama';
			$aksi = "<div><a href=".HOME."billing.rbm.cetak/cetak_rbm?id_vessel=".$row[ID_VS]." target='blank'><img src='images/print.png' width=20px height=20px border='0' title='cetak RBM'></a>&nbsp;<a href=".HOME."billing.rbm.cetak/cetak_rbm2?id_vessel=".$row[ID_VS]." target='blank'><img src='".HOME."images/print_2.png' width=20px height=20px border='0' title='cetak RBM format baru'></a></div>";
			$aksi1 = "<a href=".HOME."monitoring.cont_move.excel.download/download_excel?id_vessel=".$row[ID_VS]." target='blank'><img src='images/excel_icon5.png' width=24px height=24px title='download movement Export'></a>&nbsp;&nbsp;<a href=".HOME."monitoring.cont_move.excel.download/download_excel_import?id_vessel=".$row[ID_VS]." target='blank'><img src='images/excel_icon5.png' width=24px height=24px title='download movement Import'></a>&nbsp;&nbsp;<a onclick='codeco_select(\"$row[ID_VS]\")'><img src='images/edi_file.png' width=24px height=24px title='codeco'></a>&nbsp;&nbsp;<a href=".HOME."monitoring.cont_move.excel.download/download_excel_import?id_vessel=".$row[ID_VS]." target='blank'><img src='images/edi_file.png' width=24px height=24px title='coarri'></a>";
			//$aksi1 = "<a href=".HOME."monitoring.cont_move.excel.download/export_excel?id_vessel=".$row[ID_VS]." target='blank'><img src='images/download1.png' width=30px height=30px title='download excel'></a>";
			$list = "<div><a href=".HOME."monitoring.cont_move.list_detail/list_detail?id_vessel=".$row[ID_VS]." target='_blank' title='Klik Here for DETAIL'><img src='images/cari2.png' width=24px height=24px border='0'></a></div>";
			$sc		= "<button onclick='sync_cont_move(\"$pw\")' title='update Detail Container'><img src='images/Refresh2.png' width=15px height=15px border='0'></button>&nbsp;<button onclick='get_data_header(\"$pw\")' title='get data ict'><img src='images/Refresh3.png' width=15px height=15px border='0'></button>";
			$voy	= $row[VOYAGE_IN].' - '.$row[VOYAGE_OUT];
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($sc,$aksi1,$row[ID_VS],$list,$row[ID_VESSEL],$row[VESSEL],$voy,$row[RTA],$row[RTD],$row[PEL_ASAL],$row[PEL_TUJUAN],$row[PEL_BERIKUTNYA]);
		}
		
		$i++;
	}
	echo json_encode($responce);
}
?>