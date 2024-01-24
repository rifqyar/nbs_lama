<?php
$q = $_GET['q'];
$kg = $_GET['kg'];
$ves = $_GET['vessel'];
$vin = $_GET['vin'];
$vot = $_GET['vot'];
$list_det_ukk = $_GET['list_det_ukk'];
$no_ukks = $_GET['no_ukks'];
$no_cont = $_GET['no_container'];

$id_group = $_SESSION["ID_GROUP"];
if(isset($q)) 
{

	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:'id_bprp'; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	if($q=='list_cont_st')
	{
		$db = getDB('dbint');
	}
	else
		$db = getDB();
	
	if($q=='list_cont_gate') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM ( SELECT DISTINCT
         (A.ID_JOB_SLIP),
         A.VESSEL,
         A.VOYAGE,
         A.NO_CONT,
         A.KATEGORI_BERAT,
         A.SIZE_,
         A.TYPE_,
         A.STATUS_,
         CASE WHEN A.HZ = 'Y' THEN 'DG' ELSE NULL END HAZ,
         A.BERAT,
         A.ID_PEL_TUJ,
         TO_CHAR (A.TGL_GATEIN, 'DD MON YYYY HH24:Mi') TGL_GATEIN,
         A.NAMA_BLOCK,
         CASE
            WHEN A.SIZE_ = 40 THEN CONCAT (CONCAT (A.SLOT_, '/'), A.SLOT_ + 1)
            WHEN A.SIZE_ = 45 THEN CONCAT (CONCAT (A.SLOT_, '/'), A.SLOT_ + 1)
            ELSE CONCAT (A.SLOT_, '')
         END
            SLOT_,
         B.NAMA_BLOCK AS NAMA_BLOCK_YARD,
          CASE
            WHEN A.SIZE_ = 40 THEN CONCAT (CONCAT (B.SLOT_YARD, '/'), B.SLOT_YARD + 1)
            WHEN A.SIZE_ = 45 THEN CONCAT (CONCAT (B.SLOT_YARD, '/'), B.SLOT_YARD + 1)
            ELSE CONCAT (B.SLOT_YARD, '')
         END
            SLOT_YARD,
         B.ROW_YARD,
         B.TIER_YARD,
         TO_CHAR (B.PLACEMENT_DATE, 'DD MON YYYY HH24:Mi') PLACEMENT_DATE
    FROM tb_cont_jobslip A,
         (SELECT *
            FROM yd_placement_yard D
           WHERE D.SLOT_YARD = (SELECT MIN (c.SLOT_YARD)
                                  FROM yd_placement_yard c
                                 WHERE c.NO_CONTAINER = D.NO_CONTAINER)) B
   WHERE A.ID_JOB_SLIP = B.ID_JOBSLIP(+)
ORDER BY A.ID_JOB_SLIP DESC)";
		//$query=OCIparse($conn, "SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM $tb_ntbprph WHERE STATUS!='X')");
	}
	else if ($q=='list_kategori'){
		$query ="select ab.SIZE_,ab.TYPE_,ab.STATUS_CONT, ab.HZ,ab.ID_PEL_TUJ, ab.ID_VS, ab.STATUS_BM,ab.KATEGORI, COUNT(ab.ID) as jumlah_counter,ab.ID_BLOCKING_AREA,
 (SELECT COUNT(bb.ID) FROM yd_yard_allocation_planning bb where 
 bb.SIZE_=ab.SIZE_ and bb.TYPE_=ab.TYPE_ AND bb.STATUS_CONT=ab.STATUS_CONT AND ab.KATEGORI=bb.KATEGORI
 AND bb.STATUS_BM=ab.STATUS_BM and bb.ID_VS=ab.ID_VS and nvl(bb.HZ,0)=nvl(ab.HZ,0) AND ab.ID_BLOCKING_AREA=bb.ID_BLOCKING_AREA
 AND bb.ID_PEL_TUJ=ab.ID_PEL_TUJ
 and bb.FLAG_STATUS=0) as JML_COUNTER_TERSISA,
  (SELECT COUNT(bb.ID) FROM yd_yard_allocation_planning bb where 
 bb.SIZE_=ab.SIZE_ and bb.TYPE_=ab.TYPE_ AND bb.STATUS_CONT=ab.STATUS_CONT AND ab.KATEGORI=bb.KATEGORI
 AND bb.STATUS_BM=ab.STATUS_BM and bb.ID_VS=ab.ID_VS and nvl(bb.HZ,0)=nvl(ab.HZ,0) and ab.ID_BLOCKING_AREA=bb.ID_BLOCKING_AREA
 AND bb.ID_PEL_TUJ=ab.ID_PEL_TUJ
 and bb.FLAG_STATUS=1) JML_COUNTER_IN_USE, BC.NM_KAPAL, BC.VOYAGE_IN, BC.VOYAGE_OUT,CD.NAME, 
 (SELECT ID_USER FROM YD_YARD_ALLOCATION_PLANNING gg WHERE 
 gg.SIZE_=ab.SIZE_ and gg.TYPE_=ab.TYPE_ AND gg.STATUS_CONT=ab.STATUS_CONT AND gg.KATEGORI=ab.KATEGORI
 AND gg.STATUS_BM=ab.STATUS_BM and gg.ID_VS=ab.ID_VS and nvl(gg.HZ,0)=nvl(ab.HZ,0) and gg.ID_BLOCKING_AREA=gg.ID_BLOCKING_AREA
 AND gg.ID_PEL_TUJ=ab.ID_PEL_TUJ and rownum=1
 ) AS ID_USER ,
 (SELECT ALOCATION_DATE FROM YD_YARD_ALLOCATION_PLANNING gg WHERE 
 gg.SIZE_=ab.SIZE_ and gg.TYPE_=ab.TYPE_ AND gg.STATUS_CONT=ab.STATUS_CONT AND gg.KATEGORI=ab.KATEGORI
 AND gg.STATUS_BM=ab.STATUS_BM and gg.ID_VS=ab.ID_VS and nvl(gg.HZ,0)=nvl(ab.HZ,0) and gg.ID_BLOCKING_AREA=gg.ID_BLOCKING_AREA
 AND gg.ID_PEL_TUJ=ab.ID_PEL_TUJ and rownum=1
 ) AS ALOCATION_DATE
 
 
from yd_yard_allocation_planning ab, rbm_h bc, YD_BLOCKING_AREA cd where 
ab.SIZE_ is not null and ab.TYPE_ is not null  and ab.ID_VS is not null and ab.ID_VS = bc.NO_UKK and ab.ID_BLOCKING_AREA=cd.ID  
group by ab.SIZE_,ab.TYPE_,ab.STATUS_CONT,ab.ID_PEL_TUJ,ab.ID_BLOCKING_AREA, ab.ID_BLOCKING_AREA,ab.ID_VS, ab.STATUS_BM,ab.KATEGORI,ab.HZ, bc.NM_KAPAL, bc.VOYAGE_IN, bc.VOYAGE_OUT,cd.NAME    
ORDER BY cd.NAME,ab.ID_VS,ab.ID_PEL_TUJ,ab.SIZE_,ab.TYPE_  DESC";
	}
	else if($q=='list_cont_st') 
	{
		if($kg=='I')
		{
			$query ="SELECT a.NO_CONTAINER, TRIM(a.SIZE_CONT) AS SIZE_, TRIM(a.TYPE_CONT) AS TYPE_, TRIM(a.STATUS) AS STATUS, NVL(TRIM(a.HZ),'N') AS HZ, a.WEIGHT AS BERAT ,TRIM(a.POD) AS POD ,TRIM(a.POL) AS POL,
                    b.NO_REQUEST,
                    CASE
                        WHEN a.E_I='I' AND ACTIVITY=NULL THEN '01'
                        WHEN a.E_I='I' AND ACTIVITY='DISCHARGE' THEN '02'
                        WHEN a.E_I='I' AND ACTIVITY='PLACEMENT IMPORT' THEN '03'
                        WHEN a.E_I='I' AND ACTIVITY='GATE OUT DELIVERY' THEN '10'
                        WHEN a.E_I='I' AND ACTIVITY='GATE IN DELIVERY (TRUCK IN)' THEN '09'
                    END AS KODE_STATUS, 
                    TO_CHAR(TO_DATE(a.GATE_IN_DATE,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') TGL_GATE_IN,
                    TO_CHAR(TO_DATE(a.GATE_OUT_DATE,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') TGL_GATE_OUT,
                    TO_CHAR(TO_DATE(a.YARD_CONFIRM,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI')  TGL_PLACEMENT, 
                    a.YD_BLOCK BLOCK,
                    a.YD_SLOT SLOT , 
                    a.YD_ROW ROW_,
                    a.YD_TIER TIER, 
                    a.BAYPLAN_POSITION AS BAY,
                    a.BAYPLAN_POSITION AS BAY_REAL, 
                    TO_CHAR(TO_DATE(a.vessel_CONFIRM,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') DATE_CONFIRM
                     FROM m_cyc_container a left join m_billing b
                     on a.billing_request_id = b.id_interface
                    WHERE a.vessel='$ves' 
                    and a.voyage_in='$vin' and a.voyage_out= '$vot'
                    and a.E_I='$kg'
                    ORDER BY KODE_STATUS DESC";
		}
		else
		{
			$query="SELECT 
                                        a.NO_CONTAINER, 
                                        TRIM(a.SIZE_CONT) AS SIZE_, 
                                        TRIM(a.TYPE_CONT) AS TYPE_, 
                                        TRIM(a.STATUS) AS STATUS, 
                                        NVL(TRIM(a.HZ),'N') AS HZ, 
                                        a.WEIGHT AS BERAT ,TRIM(POD) AS POD ,
                                        TRIM(a.POL) AS POL,
                                        b.NO_REQUEST,
                    CASE
                        WHEN a.E_I='E' AND ACTIVITY='INSPECTION RECEIVING' THEN '50'
                        WHEN a.E_I='E' AND ACTIVITY='PLACEMENT IMPORT' THEN '51'
                        WHEN a.E_I='E' AND ACTIVITY='GATE IN RECEIVING' THEN '50'
                        WHEN a.E_I='E' AND ACTIVITY='GATE OUT RECEIVING' THEN '50'
                        WHEN a.E_I='E' AND ACTIVITY='LOADING' THEN '56'
                    END AS KODE_STATUS, 
                    TO_CHAR(TO_DATE(a.GATE_IN_DATE,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') TGL_GATE_IN,
                    TO_CHAR(TO_DATE(a.GATE_OUT_DATE,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') TGL_GATE_OUT,
                    TO_CHAR(TO_DATE(a.YARD_CONFIRM,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI')  TGL_PLACEMENT, 
                    a.YD_BLOCK BLOCK,
                    a.YD_SLOT SLOT , 
                    a.YD_ROW ROW_,
                    a.YD_TIER TIER, 
                    a.BAYPLAN_POSITION AS BAY,
                    a.STW_POSITION AS BAY_REAL, 
                    TO_CHAR(TO_DATE(a.vessel_CONFIRM,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') DATE_CONFIRM
                    FROM 
                        m_cyc_container a left join m_billing b
                        on a.billing_request_id = b.id_interface
                    WHERE 
                        a.vessel='$ves' 
                        and a.voyage_in='$vin' and a.voyage_out= '$vot'
                    and a.E_I='$kg'
                    ORDER BY KODE_STATUS ASC";
		}
		//print_r($query);die;
		
	}
	else if($q=='list_cont_sti') 
	{
		
			$query="SELECT NO_CONTAINER, 
						   SIZE_, 
						   TYPE_, 
						   STATUS,
						   HZ,
						   BERAT,
						   KODE_STATUS, 
						   LOKASI,
						   TO_CHAR(TGL_PLACEMENT,'DD-MM-YYYY HH24:MI') TGL_PLACEMENT, 
						   BLOCK, 
						   SLOT, 
						   ROW_,
						   TIER, 
						   LOKASI_BP AS BAY, 
						   TO_CHAR(DATE_CONFIRM,'DD-MM-YYYY HH24:MI') DATE_CONFIRM, 
						   TO_CHAR(TGL_GATE_IN,'DD-MM-YYYY HH24:MI') TGL_GATE_IN, 
						   TO_CHAR(TGL_PICKUP,'DD-MM-YYYY HH24:MI') TGL_PICKUP,
						   TO_CHAR(TGL_GATE_OUT,'DD-MM-YYYY HH24:MI') TGL_GATE_OUT,
						   KETERANGAN
				    FROM ISWS_LIST_CONTAINER 
					WHERE NO_UKK='$no_ukks' 
						and E_I='$kg'
						and kode_status <> 'NA'
					ORDER BY TGL_PLACEMENT DESC";
	}
	else if($q=='list_cont_im')
	{
		$query="SELECT NO_CONTAINER, SIZE_, TYPE_, STATUS,HZ, ISO_CODE,HEIGHT, CARRIER,BAY, LOKASI_BP, BERAT, POD, POL FROM  	
				ISWS_LIST_CONTAINER
				WHERE NO_UKK='$no_ukks' and E_I='$kg' ORDER BY BAY ASC";
	}
	else if($q=='list_disch') {
		$query = "select A.NO_UKK , A.NO_CONTAINER, A.SIZE_, A.TYPE_, A.STATUS, A.HZ, A.ISO_CODE, A.CARRIER, B.NM_KAPAL, B.VOYAGE_IN, B.VOYAGE_OUT, 	
				B.NM_PELABUHAN_ASAL, B.NM_PELABUHAN_TUJUAN, A.BERAT, A.SEAL_ID 
				from ISWS_LIST_CONTAINER A, RBM_H B 
				WHERE A.NO_UKK=B.NO_UKK AND A.E_I='I' AND A.NO_CONTAINER='$no_cont' AND A.DISCHARGE_CONFIRM IS NULL";
	}
	else if($q=='info_group') {
		$query = "SELECT COUNT(ID_BOOK) JML
						FROM TB_BOOKING_CONT_AREA a WHERE ID_VS='$no_ukks' and E_I='I'";
	}
	else if($q=='master_grouping') {
		$query = "SELECT COUNT(KATEGORI_GROUP) JML
                        FROM TB_BOOKING_CONT_AREA a WHERE ID_VS='$no_ukks' and E_I='I' GROUP BY KATEGORI_GROUP";
	}
	else if($q=='log_alokasi') {
		$query = "SELECT COUNT(1) JML
                        FROM YD_LOG_ALLOCATION a ORDER BY TGL_UPDATE DESC";
	} else if ($q=='list_kategori_import'){
		$query="select count(1) JML from tb_booking_cont_area_gr a, rbm_h b where a.id_vs = b.no_ukk and a.E_I = 'I' and b.tgl_jam_berangkat >= SYSDATE";
	}else if ($q=='list_kategori_im'){
		$query ="/* Formatted on 2/22/2013 1:27:51 AM (QP5 v5.163.1008.3004) */
					 SELECT ab.SIZE_,
         ab.TYPE_,
         ab.STATUS_CONT,
         ab.HZ,
         ab.ID_PEL_TUJ,
         ab.ID_VS,
         ab.STATUS_BM,
         ab.KATEGORI,
         cd.NAME,
         COUNT (ab.ID) AS jumlah_counter,
         ab.ID_BLOCKING_AREA,
         (SELECT COUNT (bb.ID)
            FROM yd_yard_allocation_planning bb
           WHERE     bb.SIZE_ = ab.SIZE_
                 AND bb.TYPE_ = ab.TYPE_
                 AND bb.STATUS_CONT = ab.STATUS_CONT
                 AND ab.KATEGORI = bb.KATEGORI
                 AND bb.STATUS_BM = ab.STATUS_BM
                 AND bb.ID_VS = ab.ID_VS
                 AND NVL (bb.HZ, 0) = NVL (ab.HZ, 0)
                 AND ab.ID_BLOCKING_AREA = bb.ID_BLOCKING_AREA
                 AND bb.ID_PEL_TUJ = ab.ID_PEL_TUJ
                 AND bb.FLAG_STATUS = 0)
            AS JML_COUNTER_TERSISA,
         (SELECT COUNT (bb.ID)
            FROM yd_yard_allocation_planning bb
           WHERE     bb.SIZE_ = ab.SIZE_
                 AND bb.TYPE_ = ab.TYPE_
                 AND bb.STATUS_CONT = ab.STATUS_CONT
                 AND ab.KATEGORI = bb.KATEGORI
                 AND bb.STATUS_BM = ab.STATUS_BM
                 AND bb.ID_VS = ab.ID_VS
                 AND NVL (bb.HZ, 0) = NVL (ab.HZ, 0)
                 AND ab.ID_BLOCKING_AREA = bb.ID_BLOCKING_AREA
                 AND bb.ID_PEL_TUJ = ab.ID_PEL_TUJ
                 AND bb.FLAG_STATUS = 1)
            JML_COUNTER_IN_USE,
         BC.NM_KAPAL,
         BC.VOYAGE_IN,
         BC.VOYAGE_OUT,
         ab.ID_USER,
            ab.ALOCATION_DATE
    FROM yd_yard_allocation_planning ab, rbm_h bc, YD_BLOCKING_AREA cd
   WHERE ab.ID_VS = bc.NO_UKK
         AND ab.ID_BLOCKING_AREA = cd.ID
         AND ab.STATUS_BM = 'Bongkar'
GROUP BY ab.SIZE_,
         ab.TYPE_,
         ab.STATUS_CONT,
         ab.ID_PEL_TUJ,
         ab.ID_BLOCKING_AREA,
          ab.ALOCATION_DATE,
         ab.ID_BLOCKING_AREA,
         ab.ID_VS,
         cd.NAME,
           ab.ID_USER,
         ab.STATUS_BM,
         ab.KATEGORI,
         ab.HZ,
         bc.NM_KAPAL,
         bc.VOYAGE_IN,
         bc.VOYAGE_OUT,
         cd.NAME,
         ab.STATUS_BM
ORDER BY cd.NAME,
         ab.ID_VS,
         ab.ID_PEL_TUJ,
         ab.SIZE_,
         ab.TYPE_ DESC";
	}
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
	if($q=='list_cont_gate') //ambil data
		$query="  SELECT DISTINCT
         (A.ID_JOB_SLIP),
         A.VESSEL,
         A.VOYAGE,
         A.NO_CONT,
         A.KATEGORI_BERAT,
         A.SIZE_,
         A.TYPE_,
         A.STATUS_,
         CASE WHEN A.HZ = 'Y' THEN 'DG' ELSE NULL END HAZ,
         A.BERAT,
         A.ID_PEL_TUJ,
         TO_CHAR (A.TGL_GATEIN, 'DD MON YYYY HH24:Mi') TGL_GATEIN,
         A.NAMA_BLOCK,
         CASE
            WHEN A.SIZE_ = 40 THEN CONCAT (CONCAT (A.SLOT_, '/'), A.SLOT_ + 1)
            WHEN A.SIZE_ = 45 THEN CONCAT (CONCAT (A.SLOT_, '/'), A.SLOT_ + 1)
            ELSE CONCAT (A.SLOT_, '')
         END
            SLOT_,
         B.NAMA_BLOCK AS NAMA_BLOCK_YARD,
          CASE
            WHEN A.SIZE_ = 40 THEN CONCAT (CONCAT (B.SLOT_YARD, '/'), B.SLOT_YARD + 1)
            WHEN A.SIZE_ = 45 THEN CONCAT (CONCAT (B.SLOT_YARD, '/'), B.SLOT_YARD + 1)
            ELSE CONCAT (B.SLOT_YARD, '')
         END
            SLOT_YARD,
         B.ROW_YARD,
         B.TIER_YARD,
         TO_CHAR (B.PLACEMENT_DATE, 'DD MON YYYY HH24:Mi') PLACEMENT_DATE
    FROM tb_cont_jobslip A,
         (SELECT *
            FROM yd_placement_yard D
           WHERE D.SLOT_YARD = (SELECT MIN (c.SLOT_YARD)
                                  FROM yd_placement_yard c
                                 WHERE c.NO_CONTAINER = D.NO_CONTAINER)) B
   WHERE A.ID_JOB_SLIP = B.ID_JOBSLIP(+)
ORDER BY A.ID_JOB_SLIP DESC";
	else if ($q=='list_kategori'){
		$query="select ab.SIZE_,ab.TYPE_,ab.STATUS_CONT, ab.HZ,ab.ID_PEL_TUJ, ab.ID_VS, ab.STATUS_BM,ab.KATEGORI, COUNT(ab.ID) as jumlah_counter,ab.ID_BLOCKING_AREA,
 (SELECT COUNT(bb.ID) FROM yd_yard_allocation_planning bb where 
 bb.SIZE_=ab.SIZE_ and bb.TYPE_=ab.TYPE_ AND bb.STATUS_CONT=ab.STATUS_CONT AND ab.KATEGORI=bb.KATEGORI
 AND bb.STATUS_BM=ab.STATUS_BM and bb.ID_VS=ab.ID_VS and nvl(bb.HZ,0)=nvl(ab.HZ,0) AND ab.ID_BLOCKING_AREA=bb.ID_BLOCKING_AREA
 AND bb.ID_PEL_TUJ=ab.ID_PEL_TUJ
 and bb.FLAG_STATUS=0) as JML_COUNTER_TERSISA,
  (SELECT COUNT(bb.ID) FROM yd_yard_allocation_planning bb where 
 bb.SIZE_=ab.SIZE_ and bb.TYPE_=ab.TYPE_ AND bb.STATUS_CONT=ab.STATUS_CONT AND ab.KATEGORI=bb.KATEGORI
 AND bb.STATUS_BM=ab.STATUS_BM and bb.ID_VS=ab.ID_VS and nvl(bb.HZ,0)=nvl(ab.HZ,0) and ab.ID_BLOCKING_AREA=bb.ID_BLOCKING_AREA
 AND bb.ID_PEL_TUJ=ab.ID_PEL_TUJ
 and bb.FLAG_STATUS=1) JML_COUNTER_IN_USE, BC.NM_KAPAL, BC.VOYAGE_IN, BC.VOYAGE_OUT,CD.NAME, 
 (SELECT ID_USER FROM YD_YARD_ALLOCATION_PLANNING gg WHERE 
 gg.SIZE_=ab.SIZE_ and gg.TYPE_=ab.TYPE_ AND gg.STATUS_CONT=ab.STATUS_CONT AND gg.KATEGORI=ab.KATEGORI
 AND gg.STATUS_BM=ab.STATUS_BM and gg.ID_VS=ab.ID_VS and nvl(gg.HZ,0)=nvl(ab.HZ,0) and gg.ID_BLOCKING_AREA=gg.ID_BLOCKING_AREA
 AND gg.ID_PEL_TUJ=ab.ID_PEL_TUJ and rownum=1
 ) AS ID_USER ,
 (SELECT ALOCATION_DATE FROM YD_YARD_ALLOCATION_PLANNING gg WHERE 
 gg.SIZE_=ab.SIZE_ and gg.TYPE_=ab.TYPE_ AND gg.STATUS_CONT=ab.STATUS_CONT AND gg.KATEGORI=ab.KATEGORI
 AND gg.STATUS_BM=ab.STATUS_BM and gg.ID_VS=ab.ID_VS and nvl(gg.HZ,0)=nvl(ab.HZ,0) and gg.ID_BLOCKING_AREA=gg.ID_BLOCKING_AREA
 AND gg.ID_PEL_TUJ=ab.ID_PEL_TUJ and rownum=1
 ) AS ALOCATION_DATE
 
 
from yd_yard_allocation_planning ab, rbm_h bc, YD_BLOCKING_AREA cd where 
ab.SIZE_ is not null and ab.TYPE_ is not null  and ab.ID_VS is not null and ab.ID_VS = bc.NO_UKK and ab.ID_BLOCKING_AREA=cd.ID  
group by ab.SIZE_,ab.TYPE_,ab.STATUS_CONT,ab.ID_PEL_TUJ,ab.ID_BLOCKING_AREA, ab.ID_BLOCKING_AREA,ab.ID_VS, ab.STATUS_BM,ab.KATEGORI,ab.HZ, bc.NM_KAPAL, bc.VOYAGE_IN, bc.VOYAGE_OUT,cd.NAME    
ORDER BY cd.NAME,ab.ID_VS,ab.ID_PEL_TUJ,ab.SIZE_,ab.TYPE_  DESC";
	}
	else if($q=='list_cont_st') //ambil data
	{
		if($kg=='I')
		{
			$query ="SELECT a.NO_CONTAINER, TRIM(a.SIZE_CONT) AS SIZE_, TRIM(a.TYPE_CONT) AS TYPE_, TRIM(a.STATUS) AS STATUS, NVL(TRIM(a.HZ),'N') AS HZ, a.WEIGHT AS BERAT ,TRIM(a.POD) AS POD ,TRIM(a.POL) AS POL,
                    b.NO_REQUEST,
                    CASE
                        WHEN a.E_I='I' AND ACTIVITY=NULL THEN '01'
                        WHEN a.E_I='I' AND ACTIVITY='DISCHARGE' THEN '02'
                        WHEN a.E_I='I' AND ACTIVITY='PLACEMENT IMPORT' THEN '03'
                        WHEN a.E_I='I' AND ACTIVITY='GATE OUT DELIVERY' THEN '10'
                        WHEN a.E_I='I' AND ACTIVITY='GATE IN DELIVERY (TRUCK IN)' THEN '09'
                    END AS KODE_STATUS, 
                    TO_CHAR(TO_DATE(a.GATE_IN_DATE,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') TGL_GATE_IN,
                    TO_CHAR(TO_DATE(a.GATE_OUT_DATE,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') TGL_GATE_OUT,
                    TO_CHAR(TO_DATE(a.YARD_CONFIRM,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI')  TGL_PLACEMENT, 
                    a.YD_BLOCK BLOCK,
                    a.YD_SLOT SLOT , 
                    a.YD_ROW ROW_,
                    a.YD_TIER TIER, 
                    a.BAYPLAN_POSITION AS BAY,
                    a.BAYPLAN_POSITION AS BAY_REAL, 
                    TO_CHAR(TO_DATE(a.vessel_CONFIRM,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') DATE_CONFIRM
                     FROM m_cyc_container a left join m_billing b
                     on a.billing_request_id = b.id_interface
                    WHERE a.vessel='$ves' 
                    and a.voyage_in='$vin' and a.voyage_out= '$vot'
                    and a.E_I='$kg'
                    ORDER BY KODE_STATUS DESC ";
		}
		else
		{
			$query="SELECT 
                                        a.NO_CONTAINER, 
                                        TRIM(a.SIZE_CONT) AS SIZE_, 
                                        TRIM(a.TYPE_CONT) AS TYPE_, 
                                        TRIM(a.STATUS) AS STATUS, 
                                        NVL(TRIM(a.HZ),'N') AS HZ, 
                                        a.WEIGHT AS BERAT ,TRIM(POD) AS POD ,
                                        TRIM(a.POL) AS POL,
                                        b.NO_REQUEST,
                                        
                    CASE
                        WHEN a.E_I='E' AND ACTIVITY='INSPECTION RECEIVING' THEN '50'
                        WHEN a.E_I='E' AND ACTIVITY='PLACEMENT IMPORT' THEN '51'
                        WHEN a.E_I='E' AND ACTIVITY='GATE IN RECEIVING' THEN '50'
                        WHEN a.E_I='E' AND ACTIVITY='GATE OUT RECEIVING' THEN '50'
                        WHEN a.E_I='E' AND ACTIVITY='LOADING' THEN '56'
                    END AS KODE_STATUS, 
                    TO_CHAR(TO_DATE(a.GATE_IN_DATE,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') TGL_GATE_IN,
                    TO_CHAR(TO_DATE(a.GATE_OUT_DATE,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') TGL_GATE_OUT,
                    TO_CHAR(TO_DATE(a.YARD_CONFIRM,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI')  TGL_PLACEMENT, 
                    a.YD_BLOCK BLOCK,
                    a.YD_SLOT SLOT, 
                    a.YD_ROW ROW_,
                    a.YD_TIER TIER, 
                    a.BAYPLAN_POSITION AS BAY,
                    a.STW_POSITION AS BAY_REAL, 
                    TO_CHAR(TO_DATE(a.vessel_CONFIRM,'YYYYMMDDHH24MISS'),'DD-MM-YYYY HH24:MI') DATE_CONFIRM
                    FROM 
                        m_cyc_container a left join m_billing b
                        on a.billing_request_id = b.id_interface
                    WHERE 
                        a.vessel='$ves' 
                        and a.voyage_in='$vin' and a.voyage_out= '$vot'
                    and a.E_I='$kg'
                    ORDER BY KODE_STATUS ASC";
		}
	}
	else if($q=='list_cont_sti') //ambil data
	{
			$query="SELECT NO_CONTAINER, 
						   SIZE_, 
						   TYPE_, 
						   STATUS,
						   HZ,
						   BERAT,
						   KODE_STATUS, 
						   LOKASI,
						   TO_CHAR(TGL_PLACEMENT,'DD-MM-YYYY HH24:MI') TGL_PLACEMENT, 
						   BLOCK, 
						   SLOT, 
						   ROW_,
						   TIER, 
						   LOKASI_BP AS BAY, 
						   TO_CHAR(DATE_CONFIRM,'DD-MM-YYYY HH24:MI') DATE_CONFIRM, 
						   TO_CHAR(TGL_GATE_IN,'DD-MM-YYYY HH24:MI') TGL_GATE_IN, 
						   TO_CHAR(TGL_PICKUP,'DD-MM-YYYY HH24:MI') TGL_PICKUP, 
						   TO_CHAR(TGL_GATE_OUT,'DD-MM-YYYY HH24:MI') TGL_GATE_OUT,
						   KETERANGAN
				    FROM ISWS_LIST_CONTAINER 
					WHERE NO_UKK='$no_ukks' 
						and E_I='$kg'
						and kode_status <> 'NA'
					ORDER BY TGL_PLACEMENT DESC";
	}
	else if($q=='list_cont_im') //ambil data
	{
		$query="SELECT NO_CONTAINER, SIZE_, TYPE_, STATUS,HZ, ISO_CODE,HEIGHT, CARRIER,BAY, LOKASI_BP, BERAT, POD, POL FROM  ISWS_LIST_CONTAINER
				WHERE NO_UKK='$no_ukks' and E_I='$kg' ORDER BY BAY ASC";
	}
	else if ($q=='list_kategori_im'){
		$query ="/* Formatted on 2/22/2013 1:27:51 AM (QP5 v5.163.1008.3004) */
					 SELECT ab.SIZE_,
         ab.TYPE_,
         ab.STATUS_CONT,
         ab.HZ,
         ab.ID_PEL_TUJ,
         ab.ID_VS,
         ab.STATUS_BM,
         ab.KATEGORI,
         cd.NAME,
         COUNT (ab.ID) AS jumlah_counter,
         ab.ID_BLOCKING_AREA,
         (SELECT COUNT (bb.ID)
            FROM yd_yard_allocation_planning bb
           WHERE     bb.SIZE_ = ab.SIZE_
                 AND bb.TYPE_ = ab.TYPE_
                 AND bb.STATUS_CONT = ab.STATUS_CONT
                 AND ab.KATEGORI = bb.KATEGORI
                 AND bb.STATUS_BM = ab.STATUS_BM
                 AND bb.ID_VS = ab.ID_VS
                 AND NVL (bb.HZ, 0) = NVL (ab.HZ, 0)
                 AND ab.ID_BLOCKING_AREA = bb.ID_BLOCKING_AREA
                 AND bb.ID_PEL_TUJ = ab.ID_PEL_TUJ
                 AND bb.FLAG_STATUS = 0)
            AS JML_COUNTER_TERSISA,
         (SELECT COUNT (bb.ID)
            FROM yd_yard_allocation_planning bb
           WHERE     bb.SIZE_ = ab.SIZE_
                 AND bb.TYPE_ = ab.TYPE_
                 AND bb.STATUS_CONT = ab.STATUS_CONT
                 AND ab.KATEGORI = bb.KATEGORI
                 AND bb.STATUS_BM = ab.STATUS_BM
                 AND bb.ID_VS = ab.ID_VS
                 AND NVL (bb.HZ, 0) = NVL (ab.HZ, 0)
                 AND ab.ID_BLOCKING_AREA = bb.ID_BLOCKING_AREA
                 AND bb.ID_PEL_TUJ = ab.ID_PEL_TUJ
                 AND bb.FLAG_STATUS = 1)
            JML_COUNTER_IN_USE,
         BC.NM_KAPAL,
         BC.VOYAGE_IN,
         BC.VOYAGE_OUT,
         ab.ID_USER,
            ab.ALOCATION_DATE
    FROM yd_yard_allocation_planning ab, rbm_h bc, YD_BLOCKING_AREA cd
   WHERE ab.ID_VS = bc.NO_UKK
         AND ab.ID_BLOCKING_AREA = cd.ID
         AND ab.STATUS_BM = 'Bongkar'
GROUP BY ab.SIZE_,
         ab.TYPE_,
         ab.STATUS_CONT,
         ab.ID_PEL_TUJ,
         ab.ID_BLOCKING_AREA,
          ab.ALOCATION_DATE,
         ab.ID_BLOCKING_AREA,
         ab.ID_VS,
         cd.NAME,
           ab.ID_USER,
         ab.STATUS_BM,
         ab.KATEGORI,
         ab.HZ,
         bc.NM_KAPAL,
         bc.VOYAGE_IN,
         bc.VOYAGE_OUT,
         cd.NAME,
         ab.STATUS_BM
ORDER BY cd.NAME,
         ab.ID_VS,
         ab.ID_PEL_TUJ,
         ab.SIZE_,
         ab.TYPE_ DESC";
	}else if($q=='info_group') {
		$query = "/* Formatted on 2/21/2013 6:10:42 PM (QP5 v5.163.1008.3004) */
						SELECT a.ID_BOOK,
							a.TYPE_CONT,
							   a.SIZE_CONT,
							   a.STATUS_CONT,
							   a.BOX,
							   a.TEUS,
							   a.HZ,
							   NVL (a.HEIGHT, '8.6') HEIGHT,
							   a.ID_USER,
							   a.CREATE_DATE,
							   a.HZ,
							   a.PLAN_STATUS,
							   CASE
								  WHEN ( (a.TYPE_CONT = 'RFR') AND (a.TYPE_REFFER = 'PLUG'))
								  THEN
									 a.TYPE_REFFER
								  ELSE
									 '-'
							   END
								  TYPE_REFFER,
							a.ID_VS,
							CONCAT('Kategori ',a.KATEGORI_GROUP) KATEGORI_GROUP
						  FROM TB_BOOKING_CONT_AREA a
						 WHERE a.ID_VS = '$no_ukks' AND a.E_I = 'I'
						 ORDER BY a.KATEGORI_GROUP ASC";
	} 	else if($q=='master_grouping') {
		$query = "/* Formatted on 2/26/2013 3:34:36 PM (QP5 v5.163.1008.3004) */
					SELECT CONCAT ('Kategori ', KATEGORI) KATEGORI,
							ID_KATEGORI KATE,
						   ID_VS,
						   SIZE_CONT,
						   TYPE_CONT,
						   STATUS_CONT,
						   BOX,
						   TEUS,
						   ID_USER,
						   HZ,
						   TYPE_REFFER,
						   ALLOCATED_LEFT,
						   ALLOCATED
					  FROM tb_booking_cont_area_gr 
					  where id_vs = '$no_ukks'
					  ORDER BY KATEGORI ASC";
	} else if($q=='log_alokasi') {
		$query = "SELECT ID_LOG,NAMA_BLOCK,ID_KATEGORI, KATEGORI, ID_USER,SLOT_AWAL,SLOT_AKHIR,ROW_AWAL,ROW_AKHIR,TGL_UPDATE, ACTIVITY
                        FROM YD_LOG_ALLOCATION ORDER BY TGL_UPDATE DESC";
	}
	else if ($q=='list_kategori_import'){
		$query="select a.kategori, a.box, a.teus, a.allocated, a.id_kategori, CONCAT(CONCAT(CONCAT(CONCAT(b.nm_kapal, ' '),b.voyage_in), ' - '),b.voyage_out) KAPAL, a.BLOCK, a.CREATE_DATE, a.ID_USER, a.ID_VS from tb_booking_cont_area_gr a, rbm_h b where a.id_vs = b.no_ukk and a.E_I = 'I' and b.tgl_jam_berangkat >= SYSDATE order by a.CREATE_DATE desc";
	}
	$res = $db->query($query);
	//debug($res);die;
	//ociexecute($query);
	
	while ($row = $res->fetchRow()) {
		
		if($q == 'list_cont_gate') 
		{
			$kategori = $row[SIZE_]." ".$row[TYPE_]." ".$row[STATUS_]." ".$row[HAZ]." Wg:".$row[BERAT]."kgs ( ".$row[KATEGORI_BERAT]." ) ".$row[ID_PEL_TUJ] ;
			$ves=$row[VESSEL]." ".$row[VOYAGE];
			$allo_plan=$row[NAMA_BLOCK]." - ".$row[SLOT_];
			$idjs=$row[ID_JOB_SLIP];
			$plc=$row[NAMA_BLOCK_YARD]." - ".$row[SLOT_YARD]." - ".$row[ROW_YARD]." - ".$row[TIER_YARD];
			if (($row[NAMA_BLOCK_YARD] <> NULL) OR ($row[NAMA_BLOCK_YARD] <> '')){
				$alert = '<font color="blue"><b>done</b></font>';
			} else {
				$alert = '<font color="red"><blink><b>blm placement</b></blink></font>';
			}
			$rp="<button title='reprint' onclick='reprint(\"$idjs\")'><img src='images/printer.png' height='15px' width='15px'></button>";
			$responce->rows[$i]['id']=$i;
			$responce->rows[$i]['cell']=array($alert,$rp,$row[NO_CONT],$kategori,$ves,$row[TGL_GATEIN],$allo_plan,$plc,$row[PLACEMENT_DATE]);
		}
		else if ($q=='list_kategori'){
			$kategori = $row[SIZE_]." ".$row[TYPE_]." ".$row[STATUS_CONT]." ".$row[HZ]." Wg Ctg: ( ".$row[KATEGORI]." ) ".$row[ID_PEL_TUJ] ;
			$ves=$row[NM_KAPAL]." ".$row[VOYAGE_IN] ." - ".$row[VOYAGE_OUT];
			$a=$row[JML_COUNTER_IN_USE];
			$b=$row[JUMLAH_COUNTER];
			$c=$a/$b;
			
			if($c>0.8)
			{
				$warn="<blink><font color='red'><b><i>Counter almost full</i></b></font></blink>";
			}
			else
			{
				$warn="<font color='#88888'><b>good position</b></font>";
			}
			//abu2.png
			if ($row[STATUS_CONT] =='MTY')
			{
				$bt="<img src='yard/src/css/excite-bike/images/empty.png' height='15px' width='15px'>";
			}
			else if (($row[SIZE_] == '40')&&($row[TYPE_] == 'DRY') &&($row[STATUS_CONT] =='FCL'))
			{
				$bt="<img src='yard/src/css/excite-bike/images/ui-bg_diagonals-thick_95_ffdc2e_40x40.png' height='15px' width='15px'>";
			}
			else if (($row[SIZE_] == '45')&&($row[TYPE_] == 'HQ') &&($row[STATUS_CONT] =='FCL'))
			{
				$bt="<img src='yard/src/css/excite-bike/images/ui-bg_diagonals-thick_18_b81900_40x40green.png' height='15px' width='15px'>";
			}
			else if (($row[SIZE_] == '20')&&($row[TYPE_] == 'DRY') &&($row[STATUS_CONT] =='FCL'))
			{
				$bt="<img src='yard/src/css/excite-bike/images/ui-bg_diagonals-thick_22_1484e6_40x40.png' height='15px' width='15px'>";
			}
			ELSE if (($row[TYPE_] == 'TNK') &&($row[STATUS_CONT] =='FCL'))
			{
				$bt="<img src='yard/src/css/excite-bike/images/40rfr.png' height='15px' width='15px'>";
			}
			ELSE if (($row[TYPE_] == 'OT') &&($row[STATUS_CONT] =='FCL'))
			{
				$bt="<img src='yard/src/css/excite-bike/images/abu2.png' height='15px' width='15px'>";
			}
			ELSE if (($row[TYPE_] == 'HQ') &&($row[SIZE_] == '40'))
			{
				$bt="<img src='yard/src/css/excite-bike/images/ungu.png' height='15px' width='15px'>";
			}
		
			
			$responce->rows[$i]['id']=$i;
			$responce->rows[$i]['cell']=array($warn,$row[NAME],$kategori,$bt,$ves,$row[JUMLAH_COUNTER],
			$row[JML_COUNTER_IN_USE],
			$row[JML_COUNTER_TERSISA],$row[ALOCATION_DATE],$row[ID_USER]);
		}
		else if($q == 'list_cont_st') 
		{
			if($kg=='I')
			{
				$kategori = $row[SIZE_]." ".$row[TYPE_]." ".$row[STATUS]." ".$row[HZ];
				$yd=$row[BLOCK]." - ".$row[SLOT]." - ".$row[ROW_]." - ".$row[TIER];
				$responce->rows[$i]['id']=$i;
				$responce->rows[$i]['cell']=array($row[NO_CONTAINER],$row[NO_REQUEST],$kategori,$row[BERAT],$row[POL],$row[POD],$row[KODE_STATUS],$row[BAY],$row[DATE_CONFIRM],$row[TGL_PLACEMENT],$yd,$row[TGL_GATE_IN],$row[TGL_GATE_OUT]);
			}
			else
			{
				$kategori = $row[SIZE_]." ".$row[TYPE_]." ".$row[STATUS]." ".$row[HZ];
				$yd=$row[BLOCK]." - ".$row[SLOT]." - ".$row[ROW_]." - ".$row[TIER];
				$responce->rows[$i]['id']=$i;
				$responce->rows[$i]['cell']=array($row[NO_CONTAINER],$row[NO_REQUEST],$kategori,$row[BERAT],$row[POL],$row[POD],$row[KODE_STATUS],$row[TGL_GATE_IN],$row[TGL_PLACEMENT],$yd,$row[BAY],$row[BAY_REAL],$row[DATE_CONFIRM]);
			}
		}
		else if($q == 'list_cont_sti') 
		{
				$kategori = TRIM($row[SIZE_])." ".TRIM($row[TYPE_])." ".TRIM($row[STATUS])." ".TRIM($row[HZ]);
				$stat = "<blink><font color='red'>".$row[KETERANGAN]."</font></blink>";
				$gi=$row[TGL_GATE_IN];
				$go=$row[TGL_GATE_OUT];
				$yd=$row[BLOCK]." - ".$row[SLOT]." - ".$row[ROW_]." - ".$row[TIER];
				$responce->rows[$i]['id']=$i;
				$responce->rows[$i]['cell']=array($row[NO_CONTAINER],$kategori,$row[BERAT],$row[KODE_STATUS],$row[BAY],$row[DATE_CONFIRM],$row[LOKASI],$row[TGL_PLACEMENT],$yd,$gi,$row[TGL_PICKUP],$go,$stat);
		}
		else if($q == 'list_cont_im') 
		{
			$responce->rows[$i]['id']=$i;
			$responce->rows[$i]['cell']=array($row[BAY],$row[LOKASI_BP], $row[NO_CONTAINER],$row[SIZE_],$row[TYPE_],$row[STATUS],$row[HEIGHT],$row[HZ],$row[ISO_CODE],$row[CARRIER],$row[POD],$row[POL]);
		}
		else if($q=='info_group') {	
			$ukuran = $row[SIZE_CONT];
			$tipe   = TRIM($row[TYPE_CONT]);
			$hz     = $row[HZ];
			$tes    = TRIM($row[ID_BOOK]);
			$height = $row[HEIGHT];
					
			if ($tipe == ''){
				$query_tier	   = "SELECT MAX(TIER_) TIER FROM YD_YARD_ALLOCATION_PLANNING WHERE SIZE_='$ukuran'  AND HZ = '$hz' AND ID_VS = '$no_ukks'";	
			} else {
				$query_tier	   = "SELECT MAX(TIER_) TIER FROM YD_YARD_ALLOCATION_PLANNING WHERE SIZE_='$ukuran' AND TYPE_='$tipe' AND HZ = '$hz' AND ID_VS = '$no_ukks'";	
			}
			//print_R($query_slot_row.'<br>');
			$result_tier   = $db->query($query_tier);
			$hasil_tier    = $result_tier->fetchRow();
			$tier          = $hasil_tier['TIER'];
			
			//$allo = "<font color='blue'><b><blink>".$row[ALLOCATED]."</blink></b></font>";
			//$left = "<font color='red'><b><blink>".$row[LEFT]."</blink></b></font>";
			$_SESSION['NO_UKK'] = $row[ID_VS];
		//	$rp="<button title='reprint' onclick=''><img src='images/printer.png' height='15px' width='15px'></button>";
			$responce->rows[$i]['id']=$i;
			$responce->rows[$i]['cell']=array($row[KATEGORI_GROUP],$row[SIZE_CONT], $row[TYPE_CONT], $row[STATUS_CONT],$row[HEIGHT],$row[HZ],$row[TYPE_REFFER],$row[BOX].' BOX '.$row[TEUS].' TEUS',$row[ID_USER],$row[CREATE_DATE]);
			
		} 	else if($q=='master_grouping') {	
			$kategori =TRIM($row[KATE]);
			if ($row[ALLOCATED_LEFT] <= 0)
				{
					$rp = "<button onclick='plan_t(\"$kategori\")'><img src='images/editx.png'></button>";	
				} else {
					//$rp = "<button onclick='plan_t()'><img src='images/plth.png'></button>	";	
					$rp = "<button onclick='plan_t(\"$kategori\")'><img src='images/plth.png'></button>	";						
				}
			$allo = "<font color='blue'><b><blink>".$row[ALLOCATED]." TEUS</blink></b></font>";
			$left = "<font color='red'><b><blink>".$row[ALLOCATED_LEFT]." TEUS</blink></b></font>";	
			$responce->rows[$i]['id']=$i;
			$responce->rows[$i]['cell']=array($rp,$row[KATEGORI],$row[BOX],$row[TEUS],$allo,$left,$row[ID_USER]);
		} 	else if($q=='log_alokasi') {	
			
			$kat = 'Kategori '.$row[KATEGORI];
			$slot = $row[SLOT_AWAL].' s/d '.$row[SLOT_AKHIR];
			$rew = $row[ROW_AWAL].' s/d '.$row[ROW_AKHIR];
			$responce->rows[$i]['id']=$i;
			$responce->rows[$i]['cell']=array($kat, $row[NAMA_BLOCK],$slot,$rew,$row[TGL_UPDATE],$row[ACTIVITY],$row[ID_USER]);
		}
		else if ($q=='list_kategori_import'){
			$kategori = $row[ID_KATEGORI];
			$idvs     = $row[ID_VS];
			$query3 = "select  a.kategori_group, a.size_cont, a.type_cont, a.status_cont, a.height, a.hz, a.type_reffer, create_date, id_user from tb_booking_cont_area a where a.id_vs = '$idvs'  and a.e_i = 'I' and a.id_kategori = '$kategori' ";
			$result3 = $db->query($query3);
			$data3	= $result3->getAll();
			$detail = '';
			foreach ($data3 as $de){
				$detail .= $de[SIZE_CONT]." ".$de[TYPE_CONT]." ".$de[STATUS_CONT]." ".$de[HZ]." ".$de[HEIGHT]." ".$de[TYPE_REFFER]."<br>";
			}
			
			$query2 = "select a.jml BOX, (b.JML+c.JML+d.JML) TEUS from (select count(1) JML from isws_list_container where no_ukk = '$idvs' and E_I = 'I' and status_placement = 'Y' and id_book = '$kategori') a,
(select count(1) JML from isws_list_container where no_ukk = '$idvs' and  E_I = 'I' and status_placement = 'Y' and size_ = 20 and id_book = '$kategori') b,
(select count(1)*2 JML from isws_list_container where no_ukk = '$idvs' and  E_I = 'I' and status_placement = 'Y' and size_ = 40 and id_book = '$kategori') c,
(select count(1)*2 JML from isws_list_container where no_ukk = '$idvs' and  E_I = 'I' and status_placement = 'Y' and size_ = 45 and id_book = '$kategori') d";
			$result2 = $db->query($query2);
			$data2	= $result2->fetchRow();
			$avabox		= $data2['BOX'];
			$avateus	= $data2['TEUS'];
			
			$c = $row[BOX] * 0.8;
			
			if($avabox >= $row[BOX])
			{
				$warn="<blink><font color='red'><b><i>Counter full</i></b></font></blink>";
			}
			else if ($box >= $c )
			{
				$warn="<blink><font color='red'><b><i>Counter almost full</i></b></font></blink>";
			} else {
				$warn="<font color='#88888'><b>good position</b></font>";
			}
			//abu2.png
			if ($row[KATEGORI] ==1)
			{
				$bt="<img src='yard/src/css/excite-bike/images/abu2.png' height='15px' width='15px'>";
			}
			else if ($row[KATEGORI] ==2)
			{
				$bt="<img src='yard/src/css/excite-bike/images/40rfr.png' height='15px' width='15px'>";
			}
			else if ($row[KATEGORI] ==3)
			{
				$bt="<img src='yard/src/css/excite-bike/images/20flt.png' height='15px' width='15px'>";
			}
			else if ($row[KATEGORI] ==4)
			{
				$bt="<img src='yard/src/css/excite-bike/images/ui-bg_diagonals-thick_35_d5858b_40x40.png' height='15px' width='15px'>";
			}
			ELSE if ($row[KATEGORI] ==5)
			{
				$bt="<img src='yard/src/css/excite-bike/images/ui-bg_diagonals-thick_18_b81900_40x40green.png' height='15px' width='15px'>";
			}
			ELSE if ($row[KATEGORI] ==6)
			{
				$bt="<img src='yard/src/css/excite-bike/images/ungu.png' height='15px' width='15px'>";
			}ELSE if ($row[KATEGORI] ==7)
			{
				$bt="<img src='yard/src/css/excite-bike/images/OVD.png' height='15px' width='15px'>";
			}ELSE if ($row[KATEGORI] ==8)
			{
				$bt="<img src='yard/src/css/excite-bike/images/empty.png' height='15px' width='15px'>";
			}ELSE if ($row[KATEGORI] ==9)
			{
				$bt="<img src='yard/src/css/excite-bike/images/ui-bg_diagonals-thick_95_ffdc2e_40x40.png' height='15px' width='15px'>";
			}
			ELSE if ($row[KATEGORI] ==10)
			{
				$bt="<img src='yard/src/css/excite-bike/images/ui-bg_diagonals-thick_22_1484e6_40x40.png' height='15px' width='15px'>";
			}
			
		
			$bo	 = $row[BOX]-$avabox;
			$teu = $row[TEUS]-$avateus;
			$responce->rows[$i]['id']=$kategori;
			$responce->rows[$i]['cell']=array($warn,'Kategori '. $row[KATEGORI], $row[BLOCK],$detail,$bt,$row[KAPAL],$row[BOX].' BOX '.$row[TEUS].' TEUS',
			$avabox . ' BOX '. $avateus. ' TEUS', $bo.' BOX '.  $teu. ' TEUS' ,$row[CREATE_DATE],$row[ID_USER]);
		}
		else if ($q=='list_kategori_im'){
			//$kategori = $row[SIZE_]." ".$row[TYPE_]." ".$row[STATUS_CONT]." ".$row[HZ]." Wg Ctg: ( ".$row[KATEGORI]." ) ".$row[ID_PEL_TUJ] ;
			$ves=$row[NM_KAPAL]." ".$row[VOYAGE_IN] ." - ".$row[VOYAGE_OUT];
			$warn ='Good position';
			$responce->rows[$i]['id']=$i;
			$responce->rows[$i]['cell']=array($row[NAME],'Kategori '.$row[KATEGORI],$ves,$row[JUMLAH_COUNTER],$row[ALOCATION_DATE],$row[ID_USER]);
		}
		$i++;
	}
	echo json_encode($responce);
}
?>