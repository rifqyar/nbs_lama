<?php
$q = $_GET['q'];

if(isset($q)) 
{
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:'id_bprp'; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	$db = getDB();
	
	if($q=='nota_bm') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (select id_rpstv from bil_rpstv_h)";
		//$query=OCIparse($conn, "SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM $tb_ntbprph WHERE STATUS!='X')");
	}
	else if($q=='nota_gagal') {		
		$query ="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (select no_nota from tth_nota_all2 where status_nota='F')";
		//$query=OCIparse($conn, "SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM $tb_ntbprph WHERE STATUS!='X')");
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
	
	if($q=='nota_bm') //ambil data
	{
		$query="select a.ID_RPSTV, a.ID_VSB_VOYAGE, a.VESSEL, a.VOY_IN, a.VOY_OUT,TO_CHAR( TO_DATE(a.ATA,'YYYYMMDDHH24MISS'),'DD-MM-YYYY') ATA,
        a.NAME_OPR, a.ID_OPR, a.CUST_NAME, a.STS_RPSTV, a.SV_DATE , b.TRX_NMB
        from bil_rpstv_h a 
            left join bil_ntstv_h b on a.id_rpstv=b.id_rpstv and b.STS_RPSTV<>'X'
        order by a.ID_VSB_VOYAGE desc";
    }
    else if($q=='nota_gagal') //ambil data
    {
		$query="select NO_NOTA, B.DESCRIPTION, NO_REQUEST, NO_FAKTUR_PAJAK, CUST_NAME, to_char(KREDIT,'9,999,999,999,999.00')||' '||SIGN_CURRENCY AS  KREDIT, DATE_CREATED,
DATE_PAID, STATUS_AR, STATUS_ARMSG,STATUS_RECEIPT, STATUS_RECEIPTMSG, ARPROCESS_DATE, RECEIPTPROCESS_DATE  from tth_nota_all2 A JOIN master_modul_simkeu B ON A.KD_MODUL=B.DESCRIPTIVE_FLEX_CONTEXT_CODE where status_nota='F'
";
    }

	$res = $db->query($query);

	
	while ($row = $res->fetchRow()) {
		
		if($q == 'nota_bm') 
		{
			$ves=$row[VOY_IN]." - ".$row[VOY_OUT];
			$opr=$row[NAME_OPR]."<br> [".$row[ID_OPR]."]";
			$vv=$row[VESSEL]." [".$row[ID_VSB_VOYAGE]."]";
			if(($_SESSION["ID_GROUP"]=='3') OR ($_SESSION["ID_GROUP"]=='1'))
			{
				$jk="<a title='transfer' onclick='transfer(\"$row[TRX_NMB]\",\"$row[ID_RPSTV]\")'><img src='images/trflfg2.png'></a>";
			}
			else
			{
				$jk='';
			}
			if ($row[STS_RPSTV]=='I') {
				$rp="<a title='print' onclick='cetak_nota(\"$row[ID_RPSTV]\")'><img src='images/printer.png' ></a>    $jk<br><b><font color=#0378c6>".$row[TRX_NMB]."</FONT></b>";
			}
			else if ($row[STS_RPSTV]=='F') {
				$rp="<a title='edit' onclick='preview(\"$row[ID_RPSTV]\")'><img src='images/cont_edit.gif' ></a>";
			}
			else if ($row[STS_RPSTV]=='T') {
				$rp="<a title='print' onclick='cetak_nota(\"$row[ID_RPSTV]\")'><img src='images/printer.png' ></a><br><b><font color=#0378c6>".$row[TRX_NMB]."</FONT></b>";
			}
			
			$responce->rows[$i]['id']=$i;
			$responce->rows[$i]['cell']=array($rp,$row[ID_RPSTV],$vv,$ves,$row[ATA],$row[SV_DATE],$row[CUST_NAME],$opr);
		}
		else if($q == 'nota_gagal') 
		{
			
			$responce->rows[$i]['id']=$i;
			$responce->rows[$i]['cell']=array($row[NO_NOTA],$row[DESCRIPTION],$row[NO_REQUEST],$row[CUST_NAME],$row[DATE_CREATED],$row[KREDIT],$row[DATE_PAID],$row[STATUS_AR].' '.$row[STATUS_ARMSG], $row[ARPROCESS_DATE], $row[STATUS_ARMSG], $row[STATUS_RECEIPT].' '.$row[STATUS_RECEIPTMSG],$row[RECEIPTPROCESS_DATE]);
		}
		$i++;
	}
	echo json_encode($responce);
}
?>