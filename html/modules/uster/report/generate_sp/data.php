<?php
$list_det_ukk = $_GET['list_det_ukk'];
$no_ukks = $_GET['no_ukks'];
$no_bundle = $_GET['no_bundle'];
$id_group = $_SESSION["ID_GROUP"];
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:'id_bprp'; // get index row - i.e. user click to sort
	
	if(!$sidx) $sidx =1;
	$db = getDB("storage");

		$query ="SELECT * FROM (
    SELECT A.NO_REQUEST, A.KREDIT, A.NO_NOTA_MTI  || '<br>' || A.TGL_SIMPAN AS NO_NOTA_MTI, A.NO_FAKTUR_MTI || '<br>' || A.TGL_SIMPAN AS NO_FAKTUR_MTI, A.TGL_SIMPAN, B.NM_PBM || '<br>' || B.NO_NPWP_PBM AS NM_PBM, RANK() OVER (ORDER BY TGL_SIMPAN DESC , ROWNUM DESC )  r
    FROM ITPK_NOTA_HEADER A INNER JOIN  MST_PELANGGAN B ON A.CUSTOMER_NUMBER = B.NO_ACCOUNT_PBM   ORDER BY A.TGL_SIMPAN DESC
    )  WHERE r < 500";

    if(isset($_GET['idreq']) && $_GET['idreq'] != NULL){
        $idreq = $_GET['idreq'];
        $query .= " AND NO_REQUEST = '$idreq'";
    }    
	
	if(isset($_GET['idtime']) && $_GET['idtime'] != NULL){
        $idtime = $_GET['idtime'];
        $query .= " AND TO_CHAR(TGL_SIMPAN,'DD-MM-YYYY') = '$idtime'";
    }
	
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
	
		$query="SELECT * FROM (
    SELECT A.NO_REQUEST, A.KREDIT, A.NO_NOTA_MTI  || '<br>' || A.TGL_SIMPAN AS NO_NOTA_MTI, A.NO_FAKTUR_MTI || '<br>' || A.TGL_SIMPAN AS NO_FAKTUR_MTI, A.TGL_SIMPAN, A.SP_MTI,B.NM_PBM || '<br>' || B.NO_NPWP_PBM AS NM_PBM, RANK() OVER (ORDER BY TGL_SIMPAN DESC , ROWNUM DESC )  r
    FROM ITPK_NOTA_HEADER A INNER JOIN  MST_PELANGGAN B ON A.CUSTOMER_NUMBER = B.NO_ACCOUNT_PBM ORDER BY A.TGL_SIMPAN DESC
    )  WHERE r < 500";
					
	if(isset($_GET['idreq']) && $_GET['idreq'] != NULL ){
        $idreq = $_GET['idreq'];
        $query .= " AND NO_REQUEST = '$idreq'";
    }
	
	if(isset($_GET['idtime']) && $_GET['idtime'] != NULL){
        $idtime = $_GET['idtime'];
        $query .= " AND TO_CHAR(TGL_SIMPAN,'DD-MM-YYYY') = '$idtime'";
    }
	
	$res = $db->query($query);
	
	while ($row = $res->fetchRow()) 
	{
			$cek_nota = "select count(*) cek from itpk_nota_header where trx_number='".$row[NO_FAKTUR]."'";
			$rnota 	  = $db->query($cek_nota)->fetchRow();
			if($rnota['CEK'] == 0)
			{
				if($_SESSION["ID_GROUP"]=='L' || $_SESSION["ID_GROUP"]=='J' || $_SESSION["ID_GROUP"]=='P' || $_SESSION["ID_GROUP"]=='K')
				{
					$act="<button title='Payment cash' onclick='pay(\"".$row[NO_NOTA]."\",\"".$row[NO_REQUEST]."\",\"".$row[SP_MTI]."\",\"".$row[TOTAL_TAGIHAN]."\",\"".$row[KD_EMKL]."\",\"".$row[STATUS]."\")'><img src='images/invo.png'></button>";			
				}
				else
				{	
					$act="<font color='red'><i>not yet paid</i></font>";
				}
			}
			else
			{
				$act="<a href='#' onclick='return print(\"".$row[NO_REQUEST]."\",\"".$row[SP_MTI]."\")' title='cetak nota'><img src='images/document-excel.png'></A>";
				$act.="<button title='Sync Payment' onclick='sync_payment(\"".$row[NO_REQUEST]."\",\"".$row[NO_NOTA]."\",\"".$row[SP_MTI]."\")'><img width=\"15\" height=\"15\" src='images/sync.png'></button>";
				//ESB Implementasi Add Button
				$act.="<button title='Resend ESB' onclick='esb_resend(\"".$row[NO_FAKTUR]."\",\"".$row[NO_NOTA]."\",\"".$row[SP_MTI]."\")'><img width=\"15\" height=\"15\" src='images/sync_ict.png'></button>";
			}	
			$total = "Rp. ".number_format($row[KREDIT]);
			
			$responce->rows[$i]['id']=$row[NO_NOTA];
			$responce->rows[$i]['cell']=array($row[NO_REQUEST],$row[NO_NOTA_MTI],$row[NO_FAKTUR_MTI],$total,$row[NM_PBM],$row[SP_MTI]);
		
			
		$i++;
	}
	echo json_encode($responce);
	die();

?>