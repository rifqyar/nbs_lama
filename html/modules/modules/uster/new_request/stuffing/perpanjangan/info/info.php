<?php 
	$tl	=  xliteTemplate('info.htm');
	$db = getDB("storage");
	$qselect = "/* Formatted on 10/3/2012 7:09:23 AM (QP5 v5.163.1008.3004) */
SELECT A.NO,
       A.KEGIATAN,
       A.JUMLAH H,
       B.JUMLAH H1,
       C.JUMLAH H2,
       D.JUMLAH H3
  FROM (SELECT 1 NO, 'KAPASITAS' KEGIATAN, SUM(CAPACITY)JUMLAH FROM BLOCKING_AREA WHERE KETERANGAN = 'STRIPING'
  UNION
        SELECT 2 NO, 'BERJALAN' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STRIPPING
         WHERE to_date(sysdate,'dd/mm/yy') BETWEEN to_date(tgl_approve,'dd/mm/yy') AND to_date(tgl_app_selesai,'dd/mm/yy')
        UNION
        SELECT 3 NO, 'RENCANA MULAI' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STRIPPING
         WHERE to_date(sysdate,'dd/mm/yy') = to_date(tgl_approve,'dd/mm/yy') + 1
        UNION
        SELECT 4 NO, 'SELESAI' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STRIPPING
         WHERE to_date(sysdate,'dd/mm/yy') = to_date(tgl_app_selesai,'dd/mm/yy') 
        UNION
        SELECT 5 NO,
               'SISA KAPASITAS' KEGIATAN,
                  (SELECT SUM(CAPACITY) FROM BLOCKING_AREA WHERE KETERANGAN = 'STRIPING')
               - (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STRIPPING
                   WHERE to_date(sysdate,'dd/mm/yy') BETWEEN to_date(tgl_approve,'dd/mm/yy') AND to_date(tgl_app_selesai,'dd/mm/yy'))
               - (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STRIPPING
                   WHERE to_date(sysdate,'dd/mm/yy') = to_date(tgl_approve,'dd/mm/yy') + 1)
               + (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STRIPPING
                   WHERE to_date(sysdate,'dd/mm/yy') = to_date(tgl_app_selesai,'dd/mm/yy'))
          FROM DUAL) A,
       (SELECT 1 NO, 'KAPASITAS' KEGIATAN, SUM(CAPACITY)JUMLAH FROM BLOCKING_AREA WHERE KETERANGAN = 'STRIPING'
        UNION
        SELECT 2 NO, 'BERJALAN' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STRIPPING
         WHERE to_date(sysdate,'dd/mm/yy') + 1 BETWEEN to_date(tgl_approve,'dd/mm/yy') AND to_date(tgl_app_selesai,'dd/mm/yy')
        UNION
        SELECT 3 NO, 'RENCANA MULAI' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STRIPPING
         WHERE to_date(sysdate,'dd/mm/yy') + 1 = to_date(tgl_approve,'dd/mm/yy') + 1
        UNION
        SELECT 4 NO, 'SELESAI' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STRIPPING
         WHERE to_date(sysdate,'dd/mm/yy') + 1 = to_date(tgl_app_selesai,'dd/mm/yy')
        UNION
        SELECT 5 NO,
               'SISA KAPASITAS' KEGIATAN,
               (SELECT SUM(CAPACITY)JUMLAH FROM BLOCKING_AREA WHERE KETERANGAN = 'STRIPING')
               - (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STRIPPING
                   WHERE to_date(sysdate,'dd/mm/yy')+1 BETWEEN to_date(tgl_approve,'dd/mm/yy') AND to_date(tgl_app_selesai,'dd/mm/yy'))
               - (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STRIPPING
                   WHERE to_date(sysdate,'dd/mm/yy')+1 = to_date(tgl_approve,'dd/mm/yy') + 1)
               + (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STRIPPING
                   WHERE to_date(sysdate,'dd/mm/yy')+1 = to_date(tgl_app_selesai,'dd/mm/yy'))
          FROM DUAL) B,
       (SELECT 1 NO, 'KAPASITAS' KEGIATAN, SUM(CAPACITY)JUMLAH FROM BLOCKING_AREA WHERE KETERANGAN = 'STRIPING'
        UNION
        SELECT 2 NO, 'BERJALAN' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STRIPPING
         WHERE to_date(sysdate,'dd/mm/yy') + 2 BETWEEN to_date(tgl_approve,'dd/mm/yy') AND to_date(tgl_app_selesai,'dd/mm/yy')
        UNION
        SELECT 3 NO, 'RENCANA MULAI' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STRIPPING
         WHERE to_date(sysdate,'dd/mm/yy') + 2 = to_date(tgl_approve,'dd/mm/yy') + 1
        UNION
        SELECT 4 NO, 'SELESAI' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STRIPPING
         WHERE to_date(sysdate,'dd/mm/yy') + 2 = to_date(tgl_app_selesai,'dd/mm/yy')
        UNION
        SELECT 5 NO,
               'SISA KAPASITAS' KEGIATAN,
                (SELECT SUM(CAPACITY)JUMLAH FROM BLOCKING_AREA WHERE KETERANGAN = 'STRIPING')
               - (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STRIPPING
                   WHERE to_date(sysdate,'dd/mm/yy')+2 BETWEEN to_date(tgl_approve,'dd/mm/yy') AND to_date(tgl_app_selesai,'dd/mm/yy'))
               - (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STRIPPING
                   WHERE to_date(sysdate,'dd/mm/yy')+2 = to_date(tgl_approve,'dd/mm/yy') + 1)
               + (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STRIPPING
                   WHERE to_date(sysdate,'dd/mm/yy')+2 = to_date(tgl_app_selesai,'dd/mm/yy'))
          FROM DUAL) C,
       (SELECT 1 NO, 'KAPASITAS' KEGIATAN, SUM(CAPACITY)JUMLAH FROM BLOCKING_AREA WHERE KETERANGAN = 'STRIPING'
        UNION
        SELECT 2 NO, 'BERJALAN' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STRIPPING
         WHERE to_date(sysdate,'dd/mm/yy') + 3 BETWEEN to_date(tgl_approve,'dd/mm/yy') AND to_date(tgl_app_selesai,'dd/mm/yy')
        UNION
        SELECT 3 NO, 'RENCANA MULAI' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STRIPPING
         WHERE to_date(sysdate,'dd/mm/yy') + 3 = to_date(tgl_approve,'dd/mm/yy') + 1
        UNION
        SELECT 4 NO, 'SELESAI' KEGIATAN, COUNT (NO_CONTAINER) JUMLAH
          FROM CONTAINER_STRIPPING
         WHERE to_date(sysdate,'dd/mm/yy') + 3 = to_date(tgl_app_selesai,'dd/mm/yy')
        UNION
        SELECT 5 NO,
               'SISA KAPASITAS' KEGIATAN,
                (SELECT SUM(CAPACITY)JUMLAH FROM BLOCKING_AREA WHERE KETERANGAN = 'STRIPING')
               - (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STRIPPING
                   WHERE to_date(sysdate,'dd/mm/yy')+3 BETWEEN to_date(tgl_approve,'dd/mm/yy') AND to_date(tgl_app_selesai,'dd/mm/yy'))
               - (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STRIPPING
                   WHERE to_date(sysdate,'dd/mm/yy')+3 = to_date(tgl_approve,'dd/mm/yy') + 1)
               + (SELECT COUNT (NO_CONTAINER) JUMLAH
                    FROM CONTAINER_STRIPPING
                   WHERE to_date(sysdate,'dd/mm/yy')+3 = to_date(tgl_app_selesai,'dd/mm/yy'))
          FROM DUAL) D
 WHERE A.NO = B.NO AND B.NO = C.NO AND C.NO = D.NO";
	$result = $db->query($qselect);
	
	
	$tanggalx = "SELECT TO_CHAR(SYSDATE,'dd/mm/yyyy') H, TO_CHAR(SYSDATE+1,'dd/mm/yyyy') H_1, TO_CHAR(SYSDATE+2,'dd/mm/yyyy') H_2, TO_CHAR(SYSDATE+3,'dd/mm/yyyy') H_3 FROM DUAL";
	$result_ = $db->query($tanggalx);
	
	$rows = $result->getAll();
	$tanggal_ = $result_->fetchRow();	
	$tl->assign('tanggal', $tanggal_);
	$tl->assign('row', $rows);
	$tl->renderToScreen();
?>