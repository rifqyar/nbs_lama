<?php
$q = $_GET['q'];
if(isset($q)) {
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:'id_bprp'; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	$db = getDB();
	if($q=='p_timesheet') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM GLC_REQUEST )";
	}
	else if($q=='l_batalmuat') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TB_BATALMUAT_H )";
	}
	else if($q=='nota_bm') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TB_BATALMUAT_H )";
	}
	else if($q=='print_bm') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM TB_BATALMUAT_H )";
	}
	else if($q=='uper_glc') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM GLC_REQUEST )";
	}
	else if($q=='v_profil_kapal') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_H )";
	}
	else if($q=='v_baplie_import') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM m_vsb_voyage@dbint_link )";
	}
	else if($q=='v_bayplan') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_H )";
	}
	else if($q=='v_csl') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_H )";
	}
	else if($q=='r_nota_glc') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM GLC_REQUEST )";
	}
	else if($q=='list_cont_disch') {		
		$query = "SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM ISWS_LIST_CONTAINER WHERE E_I = 'I' AND STATUS_CONFIRM = 'Y' AND ROWNUM <= 10 ORDER BY DATE_CONFIRM DESC)";
	}
	else if($q=='p_nota_glc') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM GLC_REQUEST )";
	}
	else if($q=='m_ba') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM BERITA_ACARA )";
	}	
	else if($q=='v_disch_card') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM RBM_H )";
	}
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
	if($q=='p_timesheet') //ambil data header
	{		
		$query="SELECT A.ID_REQ, 
                           A.NO_UPER_BM,
                           C.NAMA,
                           B.NAMA_VESSEL,
                           A.VOYAGE,
                           A.STATUS,
						   A.REMARK
						   FROM GLC_REQUEST A, MASTER_VESSEL B, MASTER_PBM C
                           WHERE A.KODE_KAPAL = B.KODE_KAPAL
                           AND A.KODE_PBM = C.KODE_PBM
						   ORDER BY A.ID_HEADER DESC";
		$row6 = $db->query($query)->getAll();
		$c=0;
		foreach($row6 as $b)
		{
			$id_req[$c] = $b['ID_REQ'];	 
			$row3[$c] = $db->query("SELECT DISTINCT ID_ALAT FROM GLC_DETAIL_EST_SHIFT WHERE ID_REQ = '$id_req[$c]' ORDER BY ID_ALAT")->getAll();

		  if($row3[$c] != NULL)
		  {
			$n=0;
			foreach($row3[$c] as $r)
			{
			  $a[$n]=$r['ID_ALAT'];
			  $n++;
			}		
			$glcs[$c] = implode(", ",$a);
          }
		  else
		  {
			$glcs[$c] = "-";
		  }
		  
		  $row7[$c] = $db->query("SELECT LUNAS FROM GLC_UPER_ALAT_H WHERE ID_REQ = '$id_req[$c]'")->fetchRow();
		  
		  $lns[$c]=$row7[$c]['LUNAS'];
		  
		  $c++;
		}				   
		
	}
	else if($q=='l_batalmuat') //ambil data header
	{
		$query="SELECT A.ID_BATALMUAT,
		               B.NAMA,					   
					   A.VESSEL,
					   A.VOYAGE,
					   A.JENIS,
					   A.STAT_GATE,
					   A.STATUS
					   FROM TB_BATALMUAT_H A, MASTER_PBM B 
					   WHERE A.KODE_PBM=B.KODE_PBM
					   ORDER BY A.ID_BATALMUAT DESC";
	}
	else if($q=='print_bm') //ambil data header
	{
		$query="SELECT A.ID_BATALMUAT,
		               B.NAMA,					   
					   A.VESSEL,
					   A.VOYAGE,
					   A.JENIS,
					   A.STAT_GATE,
					   A.STATUS,
					   TO_CHAR(A.TGL_REQ, 'dd-mm-yyyy') AS TGL_REQ
					   FROM TB_BATALMUAT_H A, MASTER_PBM B 
					   WHERE A.KODE_PBM=B.KODE_PBM
					   ORDER BY A.ID_BATALMUAT DESC";
		$row8 = $db->query($query)->getAll();
		$c=0;
		foreach($row8 as $b)
		{
			$id_req[$c] = $b['ID_BATALMUAT'];	 
			$row5[$c] = $db->query("SELECT COUNT(NO_CONTAINER) AS JML_CONT FROM TB_BATALMUAT_D WHERE ID_BATALMUAT = '$id_req[$c]' GROUP BY ID_BATALMUAT")->getAll();

          if($row5[$c] != NULL)
          {		  
			$n=0;
			foreach($row5[$c] as $r)
			{
			  $a[$n]=$r['JML_CONT'];
			  $n++;
			}		
			$jml_cont[$c] = $a;
          }
		  else
		  {
			$jml_cont[$c] = 0;
		  }
			$c++;
		}
	}
	else if($q=='nota_bm') //ambil data header
	{
		$query="SELECT A.ID_BATALMUAT,
		               B.NAMA,					   
					   A.VESSEL,
					   A.VOYAGE,
					   A.JENIS,
					   A.STAT_GATE,
					   A.STATUS,
					   TO_CHAR(A.TGL_REQ, 'dd-mm-yyyy') AS TGL_REQ
					   FROM TB_BATALMUAT_H A, MASTER_PBM B 
					   WHERE A.KODE_PBM=B.KODE_PBM
					   ORDER BY A.ID_BATALMUAT DESC";
		$row8 = $db->query($query)->getAll();
		$c=0;
		foreach($row8 as $b)
		{
			$id_req[$c] = $b['ID_BATALMUAT'];	 
			$row5[$c] = $db->query("SELECT COUNT(NO_CONTAINER) AS JML_CONT FROM TB_BATALMUAT_D WHERE ID_BATALMUAT = '$id_req[$c]' GROUP BY ID_BATALMUAT")->getAll();

          if($row5[$c] != NULL)
          {		  
			$n=0;
			foreach($row5[$c] as $r)
			{
			  $a[$n]=$r['JML_CONT'];
			  $n++;
			}		
			$jml_cont[$c] = $a;
          }
		  else
		  {
			$jml_cont[$c] = 0;
		  }
		  
		   $row6[$c] = $db->query("SELECT NOTA_ICT FROM TB_NOTA_BM_H WHERE ID_BATALMUAT = '$id_req[$c]'")->getAll();
		  
		  if($row6[$c] != NULL)
          {		  
			$n=0;
			foreach($row6[$c] as $r)
			{
			  $a[$n]=$r['NOTA_ICT'];
			  $n++;
			}		
			$no_nota[$c] = $a;
          }
		  else
		  {
			$no_nota[$c] = "-";
		  }
			
			$c++;
		}
	}
	else if($q=='v_profil_kapal') //ambil data header
	{		
		$query="SELECT NO_UKK AS ID_VS,
                       NM_KAPAL,
                       VOYAGE_IN,
                       VOYAGE_OUT,
                       TO_CHAR(TGL_JAM_TIBA,'DD-MM-YYYY HH24:MI') AS TGL_JAM_TIBA,
                       TO_CHAR(TGL_JAM_BERANGKAT,'DD-MM-YYYY HH24:MI') AS TGL_JAM_BERANGKAT,
                       FLAG_PROFILE,
                       TO_CHAR(TGL_JAM_TIBA,'YYYYMMDD') AS SEQ,
                       CASE WHEN TGL_JAM_BERANGKAT>SYSDATE
                       THEN 'AKTIF' ELSE 'TIDAK AKTIF' END STATUS
                    FROM RBM_H
                    ORDER BY SEQ DESC";		
	}
	else if($q=='list_cont_disch') //ambil data header
	{		
		$query="SELECT * FROM
					(
					SELECT A.NO_CONTAINER,
										   A.SIZE_,
										   A.TYPE_,
										   A.STATUS,
										   'Bay '||substr(A.LOKASI_BP,2,2)||' Row '||substr(A.LOKASI_BP,4,2)||' Tier '||substr(A.LOKASI_BP,6,2) AS SHIP_PSS,
										   TO_CHAR(A.DATE_CONFIRM,'DD-MM-YYYY HH24:MI:SS') AS DATE_CONFIRM,
										   TO_CHAR(A.DATE_CONFIRM,'YYYYMMDDHH24MI') AS SEQ,
										   B.NM_KAPAL,
										   B.VOYAGE_IN,
										   B.VOYAGE_OUT,
										   A.LOKASI,
										   A.BLOCK,
										   A.SLOT,
										   A.ROW_,
										   A.TIER,
										   TO_CHAR(A.TGL_PLACEMENT,'DD-MM-YYYY HH24:MI') AS TGL_PLACEMENT
										FROM ISWS_LIST_CONTAINER A, RBM_H B
										WHERE trim(A.NO_UKK) = trim(B.NO_UKK)
										  AND A.E_I = 'I'
										  AND trim(A.STATUS_CONFIRM) = 'Y'
										ORDER BY SEQ DESC
					)
					WHERE ROWNUM < 11";		
	}
	else if($q=='v_baplie_import') //ambil data header
	{		
		$query="SELECT A.ID_VSB_VOYAGE AS ID_VS,
                       A.VESSEL NM_KAPAL,
                       A.VOYAGE_IN,
                       A.VOYAGE_OUT,
                       TO_CHAR(TO_DATE(A.ETA,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') AS TGL_JAM_TIBA,
                       TO_CHAR(TO_DATE(A.ETD,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') AS TGL_JAM_BERANGKAT,
                       '' FLAG_PROFILE,
                       '' AS SEQ,
                       '' STATUS,
                       '' FLAG_SYNC_BP,
                       '' FLAG_GR_IM, 
                       '' FLAG_VIP,
                       (SELECT COUNT(1) from m_etv_baplie@dbint_link B WHERE B.VESSEL=A.VESSEL AND B.VOYAGE_IN=A.VOYAGE_IN AND B.VOYAGE_OUT=A.VOYAGE_OUT) AS JML_CONT
                    FROM m_vsb_voyage@dbint_link A
                    ORDER BY ID_VSB_VOYAGE DESC";		
	}
	else if($q=='v_bayplan') //ambil data header
	{		
		$query="SELECT NO_UKK AS ID_VS,
					   NM_KAPAL,
					   VOYAGE_IN,
					   VOYAGE_OUT,
					   TO_CHAR(TGL_JAM_TIBA,'DD-MM-YYYY HH24:MI') AS TGL_JAM_TIBA,
					   TO_CHAR(TGL_JAM_BERANGKAT,'DD-MM-YYYY HH24:MI') AS TGL_JAM_BERANGKAT,
					   FLAG_BAYPLAN,
					   FLAG_PROFILE,
					   TO_CHAR(TGL_JAM_TIBA,'YYYYMMDD') AS SEQ
					FROM RBM_H
					ORDER BY SEQ DESC";		
	}
	else if($q=='v_csl') //ambil data header
	{		
		$query="SELECT NO_UKK AS ID_VS,
					   NM_KAPAL,
					   VOYAGE_IN,
					   VOYAGE_OUT,
					   TO_CHAR(TGL_JAM_TIBA,'DD-MM-YYYY HH24:MI') AS TGL_JAM_TIBA,
					   TO_CHAR(TGL_JAM_BERANGKAT,'DD-MM-YYYY HH24:MI') AS TGL_JAM_BERANGKAT,
					   TO_CHAR(TGL_JAM_BERANGKAT,'YYYYMMDDHH24MI') AS SEQ,
					   FLAG_CSL
					FROM RBM_H
					WHERE TGL_JAM_TIBA IS NOT NULL
					ORDER BY SEQ DESC";		
	}
	else if($q=='m_ba') //ambil data header
	{		
		$query="SELECT A.ID,
					   A.NO_REF_HUMAS,
					   A.TERMINAL,
					   A.SIMOP,
					   B.NAMA_PELANGGAN,
					   A.PIC,
					   A.VERIFIKASI,
					   TO_CHAR(A.TGL_MASUK,'DD-MM-YYYY HH24:MI') AS TGL_MASUK,
					   TO_CHAR(A.TGL_KELUAR,'DD-MM-YYYY HH24:MI') AS TGL_KELUAR,
					   REMARKS
					FROM BERITA_ACARA A, MASTER_PELANGGAN B
					WHERE A.SIMOP IN ('petikemas','barang','rupa-rupa')
					AND A.ACCOUNT_KEU = B.ACCOUNT_KEU
				UNION
				SELECT A.ID,
					   A.NO_REF_HUMAS,
					   A.TERMINAL,
					   A.SIMOP,
					   C.NM_AGEN,
					   A.PIC,
					   A.VERIFIKASI,
					   TO_CHAR(A.TGL_MASUK,'DD-MM-YYYY HH24:MI') AS TGL_MASUK,
					   TO_CHAR(A.TGL_KELUAR,'DD-MM-YYYY HH24:MI') AS TGL_KELUAR,
					   REMARKS
					FROM BERITA_ACARA A, MASTER_AGEN C
					WHERE A.SIMOP = 'kapal' 
					AND A.ACCOUNT_KEU = C.NO_ACCOUNT
					ORDER BY ID DESC
				";		
	}
	else if($q=='uper_glc') //ambil data header
	{		
		$query="SELECT A.ID_REQ, 
                           A.NO_UPER_BM,
                           C.NAMA,
                           B.NAMA_VESSEL,
                           A.VOYAGE,
                           A.STATUS,
						   A.REMARK,
						   A.ETA,
						   A.ETD,
						   A.KADE,
						   A.TERMINAL
						   FROM GLC_REQUEST A, MASTER_VESSEL B, MASTER_PBM C
                           WHERE A.KODE_KAPAL = B.KODE_KAPAL
                           AND A.KODE_PBM = C.KODE_PBM
						   ORDER BY A.ID_HEADER DESC";
		$row6 = $db->query($query)->getAll();
		$c=0;
		foreach($row6 as $b)
		{
			$id_req[$c] = $b['ID_REQ'];
			$row9[$c] = "SELECT LUNAS FROM GLC_UPER_ALAT_H WHERE ID_REQ = '$id_req[$c]' ";
			$result1  = $db->query($row9[$c]);
			$row4	  = $result1->fetchRow();			
			$cek_lunas[$c] = $row4['LUNAS'];
		
			$row3[$c] = $db->query("SELECT DISTINCT ID_ALAT FROM GLC_DETAIL_EST_SHIFT WHERE ID_REQ = '$id_req[$c]' AND STAT_TIME = 'WORKING' ORDER BY ID_ALAT")->getAll();

			if($row3[$c] != NULL)
		  {
			$n=0;
			foreach($row3[$c] as $r)
			{
			  $a[$n]=$r['ID_ALAT'];
			  $n++;
			}		
			$glcs[$c] = implode(", ",$a);
          }
		  else
		  {
			$glcs[$c] = "-";
		  }
			
			$c++;
		}
		
	}
	else if($q=='p_nota_glc') //ambil data header
	{		
		$query="SELECT A.ID_REQ, 
                           A.NO_UPER_BM,
                           C.NAMA,
                           B.NAMA_VESSEL,
                           A.VOYAGE,
                           A.STATUS,
						   A.REMARK,
						   A.RTA,
						   A.RTD
						   FROM GLC_REQUEST A, MASTER_VESSEL B, MASTER_PBM C
                           WHERE A.KODE_KAPAL = B.KODE_KAPAL
                           AND A.KODE_PBM = C.KODE_PBM
						   ORDER BY A.ID_HEADER DESC";
		$row6 = $db->query($query)->getAll();
		$c=0;
		foreach($row6 as $b)
		{
			$id_req[$c] = $b['ID_REQ'];			
			$row3[$c] = $db->query("SELECT DISTINCT ID_ALAT FROM GLC_DETAIL_REAL_SHIFT WHERE ID_REQ = '$id_req[$c]' ORDER BY ID_ALAT")->getAll();

			if($row3[$c] != NULL)
		  {
			$n=0;
			foreach($row3[$c] as $r)
			{
			  $a[$n]=$r['ID_ALAT'];
			  $n++;
			}		
			$glcs[$c] = implode(", ",$a);
          }
		  else
		  {
			$glcs[$c] = "-";
		  }
		  
			$row11[$c] = "SELECT LUNAS FROM GLC_NOTA WHERE ID_REQ = '$id_req[$c]' ";
			$result11  = $db->query($row11[$c]);
			$row11	  = $result11->fetchRow();			
			$cek_lns[$c] = $row11['LUNAS'];
			
			$c++;
		}
		
	}
	else if($q=='perjanjian_plg') //ambil data header
	{		
		$query="SELECT A.ID_PERJANJIAN,
					   B.NAMA_PELANGGAN,
                       A.BERITA_ACARA,
                       A.LOKASI,
                       A.PERIHAL,
                       TO_CHAR(A.PERIODE_AWAL,'DD-MM-YYYY') AS PERIODE_AWAL,
                       TO_CHAR(A.PERIODE_AKHIR,'DD-MM-YYYY') AS PERIODE_AKHIR,
                       A.POLA
                    FROM MNL_PERJANJIAN_PLG A, MASTER_PELANGGAN B
						WHERE TRIM(A.KODE_PELANGGAN)=TRIM(B.KODE_PELANGGAN)
                    ORDER BY ID_PERJANJIAN DESC";		
	}
	else if($q=='v_disch_card') //ambil data header
	{		
		$query="SELECT A.NO_UKK AS ID_VS,
                       A.NM_KAPAL,
                       A.VOYAGE_IN,
                       A.VOYAGE_OUT,
                       TO_CHAR(A.TGL_JAM_TIBA,'DD-MM-YYYY HH24:MI') AS TGL_JAM_TIBA,
                       TO_CHAR(A.TGL_JAM_BERANGKAT,'DD-MM-YYYY HH24:MI') AS TGL_JAM_BERANGKAT,
                       A.FLAG_PROFILE,
                       TO_CHAR(A.TGL_JAM_TIBA,'YYYYMMDD') AS SEQ,
                       CASE WHEN A.TGL_JAM_BERANGKAT>SYSDATE
                       THEN 'AKTIF' ELSE 'TIDAK AKTIF' END STATUS,
					   A.FLAG_SYNC_CARD,A.FLAG_YAI,
					   (select count(1) from ISWS_LIST_CONTAINER B where B.NO_UKK=A.NO_UKK AND E_I='I' AND KODE_STATUS <> 'NA') AS JML_CONT
                    FROM RBM_H A
                    ORDER BY SEQ DESC";		
	}
	$res = $db->query($query);
	//ociexecute($query);
	while ($row = $res->fetchRow()) {
		$aksi = "";
		if($q == 'p_timesheet') 
		{
			$ves_voy = $row[NAMA_VESSEL]." / ".$row[VOYAGE];
			if($glcs[$i]=='-')
			{
				$aksi2 = "<blink><font color='red'>belum request alat</font></blink>";
			}
			else
			{
				if($lns[$i]=='Y')
				{
				   $aksi2 = "<a href='print.time_sheet.cetak/?id=$row[ID_REQ]' target='_blank'><img border='0' src='images/printer.png' width='14' height='14'> Form Realisasi</a>";
				}
				else
				{
				   $aksi2 = "<blink><font color='red'>Uper Alat Belum Lunas</font></blink>";
				}
			}
		  
		  if($glcs[$i]=='-')
			{
				$aksi3 = "<blink><font color='red'>belum request alat</font></blink>";
			}
			else 
			{
				if($row[STATUS]=="I")
				{
					$aksi3 = "<b><i><font color='red'>sudah invoice</font></i></b>";
				}
				else
				{
					if($lns[$i]=='Y')
				    {
					   $aksi3 = "<a href='request.req_glc/realisasi?id_req=$row[ID_REQ]&remark=$row[REMARK]'><img border='0' src='images/insert.png' width='14' height='14' title='cetak timesheet'> Insert Time Sheet</a>";
					}
					else
					{
						$aksi3 = "<blink><font color='red'>Uper Alat Belum Lunas</font></blink>";
					}
				}
			}
			$responce->rows[$i]['id']=$row[ID_REQ];
			$responce->rows[$i]['cell']=array($row[ID_REQ],$row[NAMA],$row[NO_UPER_BM],$ves_voy,$aksi2,$aksi3);
		}
		else if($q == 'l_batalmuat') 
		{
			if($row[STATUS]=="SAVE")
				{
					$aksi3 = "<b><i><font color='red'>nota saved</font></i></b>";
				}
				else
				{
					$aksi3 = "<a href='request.batalmuat/edit?no_req=$row[ID_BATALMUAT]'><img border='0' src='images/editp.png' width='14' height='14' title='cetak timesheet'> Refisi Data</a>"; 
				}
			$ves_voy = $row[VESSEL]." / ".$row[VOYAGE];
			$responce->rows[$i]['id']=$row[ID_BATALMUAT];
			$responce->rows[$i]['cell']=array($row[ID_BATALMUAT],$row[NAMA],$row[LOKASI],$row[LOKASI],$row[STAT_GATE],$aksi3);
		}
		else if($q == 'perjanjian_plg') 
		{
			$action = "<button onclick='detail(\"$row[ID_PERJANJIAN]\")'><img src='images/editx.png'></button>";
		
			$responce->rows[$i]['cell']=array($row[NAMA_PELANGGAN],$row[BERITA_ACARA],$row[LOKASI],$row[PERIHAL],$row[PERIODE_AWAL],$row[PERIODE_AKHIR],$action);
		}
		else if($q == 'm_ba') 
		{
			if($row[TGL_KELUAR]==NULL)
			{	
				if($_SESSION["ID_USER"]=='6')
				{
					$tglkeluar = "<a id='create-user' onclick='confirm_out(\"$row[ID]\")'><img border='0' src='images/out_ba.png' width='14' height='14'> Confirm Out</a>";
				}
				else
				{
					$tglkeluar = "<blink><b><font color='red'>Still In datin</font></b><blink>";
				}				
			}
			else
			{
				$tglkeluar = $row[TGL_KELUAR];
			}
			
			if($row[PIC]==NULL)
			{
				if(($_SESSION["ID_USER"]=='41')||($_SESSION["ID_USER"]=='42'))
				{
					$pic = "<a id='create-user' onclick='pic_ba(\"$row[ID]\")'><img border='0' src='images/user_ba.png' width='14' height='14'> Confirm PIC</a>";
				}
				else
				{
					$pic = "PIC not created";
				}			
			}
			else
			{
				$pc = $row[PIC];
				$cek_pic = "select NAME from TB_USER where ID = '$pc'";
				$hsl_pic = $db->query($cek_pic);
				$pics    = $hsl_pic->fetchRow();
				$pic     = $pics['NAME'];
				
			}
			
			if(($_SESSION["ID_USER"]=='41')||($_SESSION["ID_USER"]=='42'))
			{
				if($row[VERIFIKASI]=='T')
				{
					$del = "<a id='create-user' onclick='del_ba(\"$row[ID]\")' title='delete ba'><img border='0' src='images/ico_delete.gif' width='14' height='14'> Delete</a>&nbsp;&nbsp;&nbsp;<a id='create-user' onclick='ver_ba(\"$row[ID]\")' title='verifikasi ba'><img border='0' src='images/edit.png' width='14' height='14'> Verified</a>&nbsp;&nbsp;&nbsp;<a id='create-user' onclick='insert_remarks(\"$row[ID]\")' title='remarks ba'><img border='0' src='images/remarks.png' width='14' height='14' title='remarks'> Remarks</a>";					
				}
				else
				{
					$del = "<a id='create-user' onclick='del_ba(\"$row[ID]\")' title='delete ba'><img border='0' src='images/ico_delete.gif' width='14' height='14'> Delete</a>&nbsp;&nbsp;&nbsp;Verified <b>OK</b>&nbsp;&nbsp;&nbsp;<a id='create-user' onclick='insert_remarks(\"$row[ID]\")' title='remarks ba'><img border='0' src='images/remarks.png' width='14' height='14'> Remarks</a>";
				}				
			}
			else
			{
				if($row[VERIFIKASI]=='T')
				{
					$del = "<a id='create-user' onclick='ver_ba(\"$row[ID]\")' title='verifikasi ba'><img border='0' src='images/edit.png' width='14' height='14'> Verified</a>&nbsp;&nbsp;&nbsp;<a id='create-user' onclick='insert_remarks(\"$row[ID]\")' title='remarks ba'><img border='0' src='images/remarks.png' width='14' height='14'> Remarks</a>";
				}
				else
				{
					$del = "Verified <b>OK</b>&nbsp;&nbsp;&nbsp;<a id='create-user' onclick='insert_remarks(\"$row[ID]\")' title='remarks ba'><img border='0' src='images/remarks.png' width='14' height='14'> Remarks</a>";
				}
			}		
		
			$responce->rows[$i]['id']=$row[ID];
			$responce->rows[$i]['cell']=array($row[NO_REF_HUMAS],$row[TERMINAL],$row[SIMOP],$row[NAMA_PELANGGAN],$row[TGL_MASUK],$tglkeluar,$pic,$del);
		}
		else if($q == 'v_profil_kapal') 
		{
			$voyage = $row[VOYAGE_IN]."/".$row[VOYAGE_OUT];
			$ves_voy = $row[NM_KAPAL]." [".$voyage."]"; 
			
			
			if($row[STATUS] == "AKTIF")
			{
				if($row[FLAG_PROFILE] == "Y")
				{
					$aksi1 = "<a id='create-user' onclick='tambah(\"$row[ID_VS]\",\"$ves_voy\")'><img border='0' src='images/ico_edit.gif' width='14' height='14'> Re-Create</a>";
				}
				else
				{
					$aksi1 = "<a id='create-user' onclick='tambah(\"$row[ID_VS]\",\"$ves_voy\")'><img border='0' src='images/ico_edit.gif' width='14' height='14'> Insert Profile</a>";				
				}
			}
			else
			{
				$aksi1 = "<b><font color='red'>not active</font></b>";
			}
			
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($row[ID_VS],$row[NM_KAPAL],$voyage,$row[TGL_JAM_TIBA],$row[TGL_JAM_BERANGKAT],$aksi1);
		}
		else if($q == 'list_cont_disch') 
		{
			$voyage = $row[VOYAGE_IN]."/".$row[VOYAGE_OUT];
			$ves_voy = $row[NM_KAPAL]." [".$voyage."]";
			$spec = $row[SIZE_]."/".$row[TYPE_]."/".$row[STATUS];
			$yd_plc = $row[BLOCK]." - ".$row[SLOT]." - ".$row[ROW_]." - ".$row[TIER];
		
			$responce->rows[$i]['cell']=array($row[NO_CONTAINER],$spec,$ves_voy,$row[SHIP_PSS],$row[DATE_CONFIRM],$row[LOKASI],$yd_plc,$row[TGL_PLACEMENT]);
		}
		else if($q == 'v_baplie_import') 
		{
			$voyage = $row[VOYAGE_IN]."/".$row[VOYAGE_OUT];
			$ves_voy = $row[NM_KAPAL]." [".$voyage."]"; 
			$jum_disch = $row[JML_CONT];
			
			if($row[FLAG_PROFILE]=='T')
			{
				$aksi1 = "<b><font color='red'>profile not exist</font></b>";
			}
			else
			{
			if($row[FLAG_GR_IM] == 0)
			{
				$aksi1 = "<button onclick='upload_bapli(\"$row[ID_VS]\")' title='upload baplie'><img src='images/csv.gif' width=15px height=15px border='0'></button>";
				
				//<button onclick='sync_bpi(\"$row[ID_VS]\")' title='sync ICT'><img src='images/Refresh2.png' width=15px height=15px border='0'></button>
			}
			else
			{
				//$aksi1 = "<button onclick='upload_bapli(\"$row[ID_VS]\")' title='update baplie'><img src='images/csv.gif' width=15px height=15px border='0'></button><button onclick='sync_bpi(\"$row[ID_VS]\")' title='update baplie'><img src='images/Refresh2.png' width=15px height=15px border='0'></button>";
				$aksi1 = "<button onclick='upload_bapli(\"$row[ID_VS]\")' disabled='disabled' title='upload baplie'><img src='images/csv.gif' width=15px height=15px border='0'>";
				
				//<button onclick='sync_bpi(\"$row[ID_VS]\")' title='sync ICT' disabled='disabled'><img src='images/Refresh4.png' disabled='disabled' width=15px height=15px border='0'></button>
				
				//$aksi1 = "<button onclick='upload_bapli(\"$row[ID_VS]\")' title='update baplie'><img src='images/csv.gif' width=15px height=15px border='0'></button><button onclick='sync_bpi(\"$row[ID_VS]\")' title='update baplie'><img src='images/Refresh2.png' width=15px height=15px border='0'></button>";
			}
			}
			
			if(($row[FLAG_VIP]==0) and ($row[FLAG_SYNC_BP] == 0))
			{
				$detail = "<button onclick='detail_baplie(\"$row[ID_VS]\")' title='detail data baplie' ><img src='images/page_attach.png' width=15px height=15px border='0'></button>&nbsp;<button disabled='disabled'><img src='images/vip_b.png' width=15px height=15px border='0'></button>&nbsp;<button disabled='disabled'><img src='images/gr_5.png' width=15px height=15px border='0'></button>";
			}
			else if(($row[FLAG_VIP]==0) and ($row[FLAG_SYNC_BP] == 1))
			{
				$detail = "<button onclick='detail_baplie(\"$row[ID_VS]\")' title='detail data baplie' ><img src='images/page_attach.png' width=15px height=15px border='0'></button>&nbsp;<button title='VIP Client Group' onclick='vip_bp(\"$row[ID_VS]\")'><img src='images/vip.png' width=15px height=15px border='0'></button>&nbsp;<button title='Grouping Container' onclick='group_bp(\"$row[ID_VS]\")'><img src='images/gr_4.png' width=15px height=15px border='0'></button>";
			}
			else
			{
				$detail = "<button onclick='detail_baplie(\"$row[ID_VS]\")' title='detail data baplie' ><img src='images/page_attach.png' width=15px height=15px border='0'></button>&nbsp;<button disabled='disabled'><img src='images/vip_b.png' width=15px height=15px border='0'></button>&nbsp;<button disabled='disabled'><img src='images/gr_5.png' width=15px height=15px border='0'></button>";
			}
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($aksi1,$detail,$row[ID_VS],$row[NM_KAPAL],$voyage,$jum_disch,$row[TGL_JAM_TIBA],$row[TGL_JAM_BERANGKAT]);
		}
		else if($q == 'v_bayplan') 
		{
			$voyage = $row[VOYAGE_IN]."/".$row[VOYAGE_OUT];
			$ves_voy = $row[NM_KAPAL]."_".$voyage;
			if($row[FLAG_BAYPLAN]=="Y")
			{
				$aksi1 = "<b><font color='#00B200'>Sudah Disetujui</font></b>";				
			}
			else
			{
				$aksi1 = "<a id='create-user' onclick='bay_acc(\"$row[ID_VS]\",\"$ves_voy\")'><img border='0' src='images/yes.png' width='14' height='14'> Belum Disetujui</a>";
			}
			//$aksi2 = "<a id='create-user' href='report.bayplan.print/print_bayplan?id=$row[ID_VS]' target='_blank'><img border='0' src='images/pdf.png' width='14' height='14'> Allocation</a>&nbsp;&nbsp;&nbsp;<a id='create-user' onclick='tambah(\"$row[ID_VS]\",\"$ves_voy\")' target='_blank'><img border='0' src='images/pdf.png' width='14' height='14'> Planning</a>";
			
			//$aksi3 = "<a id='create-user' href='print.bayplan.allocation/cetak?id=$row[ID_VS]' target='_blank'><img border='0' src='images/printer.png' width='14' height='14'> Allocation </a>&nbsp;&nbsp;&nbsp; <a id='create-user' onclick='print_perbay(\"$row[ID_VS]\",\"$ves_voy\")' target='_blank'><img border='0' src='images/printer.png' width='14' height='14'> PerBay </a>";
			
			if($row['FLAG_PROFILE']=="T")
			{
				$act = "<b><font color='red'>profile not created</font></b>";
				$act2 = "<b><font color='red'>profile not created</font></b>";
			}
			else
			{
				//$act = "<a id='create-user' href='report.bayplan.print/print_bayplan2?id=$row[ID_VS]' target='_blank'><img border='0' src='images/pdf.png' width='14' height='14' title='Cetak Allocation' ></a>&nbsp;&nbsp;&nbsp;<a id='create-user' onclick='vespro_print(\"$row[ID_VS]\",\"$ves_voy\")' target='_blank'><img border='0' src='images/printer.png' width='14' height='14' title='Cetak Planning'></a>&nbsp;&nbsp;&nbsp;<a id='create-user' href='report.bayplan.print/export_excel?no_ukk=$row[ID_VS]&vesvoy=$ves_voy' target='_blank'><img border='0' src='images/ex_excel.png' width='14' height='14' title='Export Excel Baplie'></a>&nbsp;&nbsp;&nbsp;<a id='create-user' href='report.bayplan.print/export_excel?id=$row[ID_VS]' target='_blank'><img border='0' src='images/baplie.png' width='14' height='14' title='Export Baplie File'></a>";
				$act2 = "<a id='create-user' onclick='bayplan_disch(\"$row[ID_VS]\",\"$ves_voy\")'><img border='0' src='images/mpdf2.png' width='20%' title='Cetak Bay Disch' ></a>&nbsp;&nbsp;&nbsp;<a id='create-user' href='print.bayplan.allocation/cetak?id=$row[ID_VS]' target='_blank'><img border='0' src='images/printer.png' width='14' height='14' title='Cetak Planning'></a>&nbsp;&nbsp;&nbsp;<a id='create-user' href='report.bayplan.print/export_excel?no_ukk=$row[ID_VS]&vesvoy=$ves_voy' target='_blank'><img border='0' src='images/ex_excel.png' width='14' height='14' title='Export Excel Baplie'></a>&nbsp;&nbsp;&nbsp;<a id='create-user' href='report.bayplan.print/export_excel?id=$row[ID_VS]' target='_blank'><img border='0' src='images/baplie.png' width='14' height='14' title='Export Baplie File'></a>";
				$act = "<a id='create-user' onclick='bayplan_load(\"$row[ID_VS]\",\"$ves_voy\")'><img border='0' src='images/mpdf2.png' width='20%' title='Cetak Bay Load' ></a>&nbsp;&nbsp;&nbsp;<a id='create-user' href='print.bayplan.allocation/cetak?id=$row[ID_VS]' target='_blank'><img border='0' src='images/printer.png' width='14' height='14' title='Cetak Planning'></a>&nbsp;&nbsp;&nbsp;<a id='create-user' href='report.bayplan.print/export_excel?no_ukk=$row[ID_VS]&vesvoy=$ves_voy' target='_blank'><img border='0' src='images/ex_excel.png' width='14' height='14' title='Export Excel Baplie'></a>&nbsp;&nbsp;&nbsp;<a id='create-user' href='report.bayplan.print/export_excel?id=$row[ID_VS]' target='_blank'><img border='0' src='images/baplie.png' width='14' height='14' title='Export Baplie File'></a>";
				
			}		
			
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($act,$act2,$row[ID_VS],$row[NM_KAPAL],$voyage,$row[TGL_JAM_TIBA],$row[TGL_JAM_BERANGKAT],$aksi1);
		}
		else if($q == 'v_csl') 
		{
			$voyage = $row[VOYAGE_IN]."/".$row[VOYAGE_OUT];
			$ves_voy = $row[NM_KAPAL]." [".$voyage."]";
			if($row[FLAG_BAYPLAN]=="Y")
			{
				$aksi1 = "<b><font color='#00B200'>Sudah Disetujui</font></b>";				
			}
			else
			{
				$aksi1 = "<a id='create-user' onclick='csl_acc(\"$row[ID_VS]\",\"$ves_voy\")'><img border='0' src='images/yes.png' width='14' height='14'> Belum Disetujui</a>";
			}
			$aksi2 = "<a id='create-user' href='report.csl.print/print_csl?id=$row[ID_REQ]&remark=$row[REMARK]' target='_blank'><img border='0' src='images/pdf.png' width='14' height='14'> CSL</a>&nbsp;&nbsp;&nbsp;<a id='create-user' onclick='tambah(\"$row[ID_VS]\",\"$ves_voy\")' target='_blank'><img border='0' src='images/pdf.png' width='14' height='14'> Priority</a>";
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($row[ID_VS],$row[NM_KAPAL],$voyage,$row[TGL_JAM_TIBA],$row[TGL_JAM_BERANGKAT],$aksi1,$aksi2);
		}
		else if($q == 'nota_bm') 
		{
			if($row[STATUS]=="SAVE")
				{
					$aksi3 = "<a href='request.batalmuat/edit?no_req=$row[ID_BATALMUAT]'  target='_blank'><img border='0' src='images/printer.png' width='14' height='14' title='cetak ulang' /><font color='blue'> Cetak Ulang</font></a>"; 
				}
				else
				{
					$aksi3 = "<a href='billing.batalmuat/preview?no_req=$row[ID_BATALMUAT]'  target='_blank'><img border='0' src='images/printer.png' width='14' height='14' title='cetak' /> Print</a>"; 
				}
				
			if($no_nota[$i]=="-")
			{
				$nt = "<b><font color='red'>nota belum save</font></b>";
			}
			else
			{
				$nt = $no_nota[$i];
			}
				
			$responce->rows[$i]['id']=$row[ID_BATALMUAT];
			$responce->rows[$i]['cell']=array($row[ID_BATALMUAT],$nt,$row[TGL_REQ],$row[NAMA],$jml_cont[$i],$row[JENIS],$row[STAT_GATE],$aksi3);
		}
		else if($q == 'print_bm') 
		{
			$ves_voy = $row[VESSEL]." [".$row[VOYAGE]."]";
			if($row[STATUS]=="SAVED")
				{
					$aksi3 = "<a href=".HOME."print.batalmuat.cetak/?pl=".$row[ID_BATALMUAT]." target='blank'><img src='images/printer.png' title='print_nota'></a>"; 
				}
				else
				{
					$aksi3 = "<b><font color='red'>nota belum save</font></b>"; 
				}
				
			$responce->rows[$i]['id']=$row[ID_BATALMUAT];
			$responce->rows[$i]['cell']=array($aksi3,$row[ID_BATALMUAT],$row[NAMA],$ves_voy,$jml_cont[$i],$row[JENIS],$row[STAT_GATE]);
		}
		else if($q == 'r_nota_glc') 
		{
			if($row[RTD]==NULL)
			{
				$tgl_kegiatan = "<blink><font color='red'>belum realisasi keberangkatan kapal</font></blink>";
			}
			else
			{
				$tgl_kegiatan = $row[RTA]." s/d ".$row[RTD];
			}
			$ves_voy = $row[NAMA_VESSEL]." / ".$row[VOYAGE];
			$aksi = "<a href='billing.nota_glc.print/print_pranota?id=$row[ID_REQ]&remark=$row[REMARK]' target='_blank'><img border='0' src='images/print.png' width='14' height='14' title='view'> Cetak Pranota</a>";
			
			if($glcs[$i]=='-')
			{
				$aksi2 = "<blink><font color='red'>belum realisasi alat</font></blink>";
			}
			else
			{
				$aksi2 = $glcs[$i];
			}
			
			$responce->rows[$i]['id']=$row[ID_REQ];
			$responce->rows[$i]['cell']=array($row[ID_REQ],$row[NAMA],$row[NO_UPER_BM],$tgl_kegiatan,$ves_voy,$aksi2,$row[STATUS],$aksi);
		}
		else if($q == 'p_nota_glc') 
		{			
			if($row[RTD]==NULL)
			{
				$tgl_kegiatan = "<blink><font color='red'>belum realisasi keberangkatan kapal</font></blink>";
			}
			else
			{
				$tgl_kegiatan = $row[RTA]." s/d ".$row[RTD];
			}
			
			$ves_voy = $row[NAMA_VESSEL]." / ".$row[VOYAGE];
			
			if($glcs[$i]=='-')
			{
				$aksi2 = "<blink><font color='red'>belum realisasi alat</font></blink>";
			}
			else
			{
				$aksi2 = $glcs[$i];
			}
			
			if($row[STATUS]=="I")
			{	
				if($_SESSION["ID_GROUP"]=='9')
				{
					if($cek_lns[$i]=="T")
					{
						$aksi = "<a href='billing.nota_glc.print/cetak_ulang?id=$row[ID_REQ]&remark=$row[REMARK]' target='_blank'><img border='0' src='images/print.png' width='14' height='14' title='cetak nota'> Cetak Nota</a>&nbsp;&nbsp;&nbsp;<a id='create-user' onclick='tambah(\"$row[ID_REQ]\")'><img border='0' src='images/ico_edit.gif' width='14' height='14'> Insert JKM</a>";
					}
					else
					{
						$aksi = "<a href='billing.nota_glc.print/cetak_ulang?id=$row[ID_REQ]&remark=$row[REMARK]' target='_blank'><img border='0' src='images/print.png' width='14' height='14' title='cetak nota'> Cetak Nota</a>&nbsp;&nbsp;&nbsp;<font color='#00B200'>lunas</font>";
					}
				}
				else
				{
					$aksi = "<b><i><font color='red'>sudah invoice</font></i></b>";
				}
			}
			else
			{
				if($_SESSION["ID_GROUP"]=='9')
				{
					$aksi = "<blink><b><font color='red'>belum invoice</font></b></blink>";
				}
				else
				{
					$aksi = "<a href='billing.nota_glc/v_invoice?id=$row[ID_REQ]&remark=$row[REMARK]' target='_blank'><img border='0' src='images/view2.png' width='14' height='14' title='view'> View</a>";
				}
			}
			$responce->rows[$i]['id']=$row[ID_REQ];
			$responce->rows[$i]['cell']=array($row[ID_REQ],$row[NAMA],$row[NO_UPER_BM],$tgl_kegiatan,$ves_voy,$aksi2,$row[STATUS],$aksi);
		}
		else if($q == 'uper_glc') 
		{			
			$tgl_kegiatan = $row[ETA]." s/d ".$row[ETD];
			$ves_voy = $row[NAMA_VESSEL]." / ".$row[VOYAGE];
			
			if($glcs[$i]=='-')
			{
				$aksi2 = "<blink><font color='red'>belum request alat</font></blink>";
			}
			else
			{
				$aksi2 = $glcs[$i];
			}
			
			if(($cek_lunas[$i]==NULL)||($cek_lunas[$i]=="T"))
			{	$status_lunas = "<blink><font color='red'>belum lunas</font></blink>";
				if($_SESSION["ID_GROUP"]=='9')
				{
					$aksi = "<a id='create-user' onclick='tambah(\"$row[ID_REQ]\")'><img border='0' src='images/ico_edit.gif' width='14' height='14'> Insert JKM</a>";
				}
				else
				{
					$aksi = "<a href='billing.uper_glc.print/print_pranota?id=$row[ID_REQ]&remark=$row[REMARK]' target='_blank'><img border='0' src='images/print.png' width='14' height='14'> Cetak Uper</a>";
				}
			}
			elseif($cek_lunas[$i]=="Y")
			{
				$status_lunas = "<font color='#00B200'>lunas</font>";
				if($_SESSION["ID_GROUP"]=='9')
				{
					$aksi = "<blink><b><font color='red'>sudah invoice</font></b></blink>";
				}
				else
				{
					$aksi = "<a href='billing.uper_glc.print/cetak_ulang?id=$row[ID_REQ]&remark=$row[REMARK]' target='_blank'><img border='0' src='images/print.png' width='14' height='14'> Cetak Ulang</a>";
				}
			}
			
			$responce->rows[$i]['id']=$row[ID_REQ];
			$responce->rows[$i]['cell']=array($row[ID_REQ],$row[NAMA],$row[NO_UPER_BM],$tgl_kegiatan,$row[KADE],$row[TERMINAL],$ves_voy,$aksi2,$status_lunas,$aksi);
		}
		else if($q == 'v_disch_card') 
		{
			$voyage = $row[VOYAGE_IN]."/".$row[VOYAGE_OUT];
			$ves_voy = $row[NM_KAPAL]." [".$voyage."]"; 
			$jum_disch = $row[JML_CONT];
			$vukk=$row[ID_VS];
			
			$queryplan = "select (a.JML+b.JML+c.JML) JML  from
				(SELECT COUNT(1) JML FROM ISWS_LIST_CONTAINER WHERE KODE_STATUS = '01' AND NO_UKK = '$vukk' and size_ = '20') a,
				(SELECT COUNT(1)*2 JML FROM ISWS_LIST_CONTAINER WHERE KODE_STATUS = '01' AND NO_UKK = '$vukk' and size_ = '40') b,
				(SELECT COUNT(1)*2 JML FROM ISWS_LIST_CONTAINER WHERE KODE_STATUS = '01' AND NO_UKK = '$vukk' and size_ = '45') c ";
			$resultplan = $db->query($queryplan);
			$dataplan	= $resultplan->fetchRow();
			$jml_baplie = $dataplan['JML'];
			
			$queryplan = "SELECT COUNT(1) JML FROM YD_YARD_ALLOCATION_PLANNING WHERE ID_VS = '$vukk'";
			$resultplan = $db->query($queryplan);
			$dataplan	= $resultplan->fetchRow();
			$jml_plan = $dataplan['JML'];
			
		
			if(($row[FLAG_SYNC_CARD] == 0)and($row[FLAG_YAI] == 0))
			{
				$aksi1 = "<button disabled='disabled'><img src='images/Refresh4.png' disabled='disabled' width=15px height=15px border='0'></button>";//$detail = "<img src='images/print3.png' width=15px height=15px border='0'>";
				//$detail = "<a href='".HOME."print.disch_card.ajax/print_dc?id=$vukk' target='_blank' title='print disch_card' ><img src='images/print.png' width=15px height=15px border='0'></a>";
				$detail = "<img src='images/print3.png' width=15px height=15px border='0'>";
				$info = "<font color='#f2214d'><i><b>unplanned</b><br>please plan with <br>yard allocation import</i>";
			}
			ELSE if(($row[FLAG_SYNC_CARD] == 0)and($row[FLAG_YAI] == 1))
			{
				IF ($jml_plan < $jml_baplie) {
					$aksi1 = "<button disabled='disabled'><img src='images/Refresh4.png' disabled='disabled' width=15px height=15px border='0'></button>";
					$detail = "<img src='images/print3.png' width=15px height=15px border='0'>";
					$info = "<font color='orange'><i><b>unsync</b><br> there are containers <br>are not allocated yet </i>";
				} else {
					$aksi1 = "<button onclick='sync_yard(\"$row[ID_VS]\")' title='sync yard'><img src='images/Refresh2.png' width=15px height=15px border='0'></button>";
					$detail = "<img src='images/print3.png' width=15px height=15px border='0'>";
					//<a href='".HOME."print.disch_card.ajax/print_dc?id=$vukk' target='_blank' title='print disch_card' ><img src='images/print.png' width=15px height=15px border='0'></a>
					$info = "<font color='#green'><i><b>unsync</b><br>please sync with <br>sync button, data is <br> ready to sync </i>";
				}
			}
			else
			{
				$aksi1 = "<button onclick='sync_yard(\"$row[ID_VS]\")' title='sync yard'><img src='images/Refresh2.png' width=15px height=15px border='0'></button>";
				$detail = "<a href='".HOME."print.disch_card.ajax/print_dc?id=$vukk' target='#_blank' title='print disch_card' ><img src='images/print.png' width=15px height=15px border='0'></a>";
				$info = "<font color='#218df2'><i><b>ready to print</b></i>";
			}
			
			
			
			$responce->rows[$i]['id']=$row[ID_VS];
			$responce->rows[$i]['cell']=array($info,$aksi1,$detail,$row[ID_VS],$row[NM_KAPAL],$voyage,$jum_disch,$row[TGL_JAM_TIBA],$row[TGL_JAM_BERANGKAT]);
		}
		$i++;
	}
	echo json_encode($responce);
}
?>