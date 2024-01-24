<?php
$tl =  xliteTemplate('preview_nota.htm');
$db  =  getDB();

$id_pranota = $_GET['id_pranota'];

$query_header  = "SELECT A.ID_BHD,
                        A.NO_PRANOTA,
						TO_CHAR (A.TGL_PRANOTA, 'DD/MM/RRRR HH24:MI') TGL_PRANOTA,
						A.CUST_NAME,
						A.CUST_TAX_NO,
						A.CUST_ADDR,
						A.PERIODE,
						TO_CHAR (A.TAGIHAN, '999,999,999,999') TAGIHAN,
						TO_CHAR (A.PPN, '999,999,999,999') PPN,
						TO_CHAR (A.TOTAL_TAGIHAN, '999,999,999,999') TOTAL_TAGIHAN,
						 (SELECT COUNT (1)
							  FROM BIL_BHD_CONT_H
							 WHERE ID_PRANOTA = a.ID_BHD)
							  JML_CONT
				  FROM REQ_BHD_H A
				 WHERE ID_BHD = '$id_pranota'";
$result_header = $db->query($query_header);
$row_header = $result_header->fetchRow(); 

$query_detail  = "SELECT ROWNUM NO,
						   NO_CONTAINER,
						   SIZE_CONTAINER,
						   TYPE_CONTAINER,
						   STS_CONTAINER,
						   HZ,
						   VESSEL,
						   VOYAGE,
						   TO_CHAR (ATA, 'DD/MM/RRRR') START_DATE,
						   TO_CHAR (TGL_GATE_OUT, 'DD/MM/RRRR') END_DATE,
						   (TGL_GATE_OUT - ATA) DURASI,
						   TO_CHAR(TAGIHAN,'999,999,999,999') TAGIHAN
					  FROM BIL_BHD_CONT_H
					 WHERE ID_PRANOTA = '$id_pranota'";
$result_detail = $db->query($query_detail);
$row_detail = $result_detail->getAll(); 


$tl->assign("row_header",$row_header);
$tl->assign("row_detail",$row_detail);
$tl->assign("HOME",HOME);
$tl->assign("APPID",APPID);
$tl->renderToScreen();
?>