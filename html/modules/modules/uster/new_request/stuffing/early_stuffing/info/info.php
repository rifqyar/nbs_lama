<?php 
	$tl	=  xliteTemplate('info.htm');
	$db = getDB("storage");
	$qselect = "/* Formatted on 10/3/2012 7:06:07 AM (QP5 v5.163.1008.3004) */
SELECT A.NO,
       A.KEGIATAN,
       A.JUMLAH H,
       B.JUMLAH H1,
       C.JUMLAH H2,
       D.JUMLAH H3
  FROM (SELECT 1 NO, 'KAPASITAS' KEGIATAN, SUM(CAPACITY)JUMLAH FROM BLOCKING_AREA WHERE KETERANGAN = 'STUFFING'
        UNION
        SELECT 2 NO, 'BERJALAN' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STUFFING
         WHERE to_date(sysdate,'dd/mm/yy') BETWEEN to_date(TGL_APPROVE,'dd/mm/yy') AND to_date(TGL_APPROVE,'dd/mm/yy') + 5
        UNION
        SELECT 3 NO, 'RENCANA MULAI' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STUFFING
         WHERE to_date(sysdate,'dd/mm/yy') = TGL_APPROVE + 1
        UNION
        SELECT 4 NO, 'SELESAI' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STUFFING
         WHERE to_date(sysdate,'dd/mm/yy') = to_date(TGL_APPROVE,'dd/mm/yy') + 5
        UNION
        SELECT 5 NO,
               'SISA KAPASITAS' KEGIATAN,
                 (SELECT SUM(CAPACITY)JUMLAH FROM BLOCKING_AREA WHERE KETERANGAN = 'STUFFING')
               - (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STUFFING
                   WHERE to_date(sysdate,'dd/mm/yy') BETWEEN to_date(TGL_APPROVE,'dd/mm/yy') AND to_date(TGL_APPROVE,'dd/mm/yy') + 5)
               - (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STUFFING
                   WHERE to_date(sysdate,'dd/mm/yy') = TGL_APPROVE + 1)
               + (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STUFFING
                   WHERE to_date(sysdate,'dd/mm/yy') = to_date(TGL_APPROVE,'dd/mm/yy') + 5)
          FROM DUAL) A,
       (SELECT 1 NO, 'KAPASITAS' KEGIATAN, SUM(CAPACITY)JUMLAH FROM BLOCKING_AREA WHERE KETERANGAN = 'STUFFING'
        UNION
        SELECT 2 NO, 'BERJALAN' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STUFFING
         WHERE to_date(sysdate,'dd/mm/yy') + 1 BETWEEN to_date(TGL_APPROVE,'dd/mm/yy') AND to_date(TGL_APPROVE,'dd/mm/yy') + 5
        UNION
        SELECT 3 NO, 'RENCANA MULAI' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STUFFING
         WHERE to_date(sysdate,'dd/mm/yy') + 1 = TGL_APPROVE + 1
        UNION
        SELECT 4 NO, 'SELESAI' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STUFFING
         WHERE to_date(sysdate,'dd/mm/yy') + 1 = to_date(TGL_APPROVE,'dd/mm/yy') + 5
        UNION
        SELECT 5 NO,
               'SISA KAPASITAS' KEGIATAN,
                 (SELECT SUM(CAPACITY)JUMLAH FROM BLOCKING_AREA WHERE KETERANGAN = 'STUFFING')
               - (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STUFFING
                   WHERE to_date(sysdate,'dd/mm/yy')+1 BETWEEN to_date(TGL_APPROVE,'dd/mm/yy') AND to_date(TGL_APPROVE,'dd/mm/yy') + 5)
               - (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STUFFING
                   WHERE to_date(sysdate,'dd/mm/yy')+1 = TGL_APPROVE + 1)
               + (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STUFFING
                   WHERE to_date(sysdate,'dd/mm/yy')+1 = to_date(TGL_APPROVE,'dd/mm/yy') + 5)
          FROM DUAL) B,
       (SELECT 1 NO, 'KAPASITAS' KEGIATAN, SUM(CAPACITY)JUMLAH FROM BLOCKING_AREA WHERE KETERANGAN = 'STUFFING'
        UNION
        SELECT 2 NO, 'BERJALAN' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STUFFING
         WHERE to_date(sysdate,'dd/mm/yy') + 2 BETWEEN to_date(TGL_APPROVE,'dd/mm/yy') AND to_date(TGL_APPROVE,'dd/mm/yy') + 5
        UNION
        SELECT 3 NO, 'RENCANA MULAI' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STUFFING
         WHERE to_date(sysdate,'dd/mm/yy') + 2 = TGL_APPROVE + 1
        UNION
        SELECT 4 NO, 'SELESAI' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STUFFING
         WHERE to_date(sysdate,'dd/mm/yy') + 2 = to_date(TGL_APPROVE,'dd/mm/yy') + 5
        UNION
        SELECT 5 NO,
               'SISA KAPASITAS' KEGIATAN,
                 (SELECT SUM(CAPACITY)JUMLAH FROM BLOCKING_AREA WHERE KETERANGAN = 'STUFFING')
               - (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STUFFING
                   WHERE to_date(sysdate,'dd/mm/yy')+2 BETWEEN to_date(TGL_APPROVE,'dd/mm/yy') AND to_date(TGL_APPROVE,'dd/mm/yy') + 5)
               - (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STUFFING
                   WHERE to_date(sysdate,'dd/mm/yy')+2 = TGL_APPROVE + 1)
               + (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STUFFING
                   WHERE to_date(sysdate,'dd/mm/yy')+2 = to_date(TGL_APPROVE,'dd/mm/yy') + 5)
          FROM DUAL) C,
       (SELECT 1 NO, 'KAPASITAS' KEGIATAN, SUM(CAPACITY)JUMLAH FROM BLOCKING_AREA WHERE KETERANGAN = 'STUFFING'
        UNION
        SELECT 2 NO, 'BERJALAN' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STUFFING
         WHERE to_date(sysdate,'dd/mm/yy') + 3 BETWEEN to_date(TGL_APPROVE,'dd/mm/yy') AND to_date(TGL_APPROVE,'dd/mm/yy') + 5
        UNION
        SELECT 3 NO, 'RENCANA MULAI' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STUFFING  WHERE to_date(sysdate,'dd/mm/yy')+3 = TGL_APPROVE+1
                UNION
                SELECT 4 NO, 'SELESAI' KEGIATAN , count(NO_CONTAINER) JUMLAH FROM CONTAINER_STUFFING WHERE to_date(sysdate,'dd/mm/yy')+3 = to_date(TGL_APPROVE,'dd/mm/yy')+5
                UNION
                SELECT 5 NO, 'SISA KAPASITAS' KEGIATAN, 
                (SELECT SUM(CAPACITY)JUMLAH FROM BLOCKING_AREA WHERE KETERANGAN = 'STUFFING')
                -(SELECT count(NO_CONTAINER) JUMLAH FROM CONTAINER_STUFFING WHERE to_date(sysdate,'dd/mm/yy')+3 BETWEEN to_date(TGL_APPROVE,'dd/mm/yy') AND to_date(TGL_APPROVE,'dd/mm/yy')+5)
                -(SELECT count(NO_CONTAINER) JUMLAH FROM CONTAINER_STUFFING WHERE to_date(sysdate,'dd/mm/yy')+3 = TGL_APPROVE+1)
                +(SELECT count(NO_CONTAINER) JUMLAH FROM CONTAINER_STUFFING WHERE to_date(sysdate,'dd/mm/yy')+3 = to_date(TGL_APPROVE,'dd/mm/yy')+5) FROM DUAL) D
                WHERE A.NO = B.NO AND B.NO = C.NO AND C.NO = D.NO";
	$result = $db->query($qselect);
	
	
	$tanggalx = "SELECT TO_CHAR(SYSDATE,'dd/mm/yy') H, TO_CHAR(SYSDATE+1,'dd/mm/yy') H_1, TO_CHAR(SYSDATE+2,'dd/mm/yy') H_2, TO_CHAR(SYSDATE+3,'dd/mm/yy') H_3 FROM DUAL";
	$result_ = $db->query($tanggalx);
	
	$rows = $result->getAll();
	$tanggal_ = $result_->fetchRow();	
	$q_capacity = "SELECT COUNT(PL.NO_CONTAINER) STF
					FROM PLACEMENT PL JOIN BLOCKING_AREA BA
					ON PL.ID_BLOCKING_AREA = BA.ID
					WHERE BA.KETERANGAN = 'STUFFING'";
	$r_cap = $db->query($q_capacity);
	$row_cap = $r_cap->fetchRow();
	
	$tl->assign('row_cap',$row_cap);
	$tl->assign('tanggal', $tanggal_);
	$tl->assign('row', $rows);
	$tl->renderToScreen();
?>