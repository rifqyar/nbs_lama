<?php
 $tl = xliteTemplate('print_nota.htm');
 $no_req=$_GET['id'];
 //$decode=json_decode($id);
 //print_r($id);die;
 $db=  getDB();
 /*$query="SELECT A.*,B.*,UPPER(A.TIPE_REQ) TIPE_REQUEST
        from BH_REQUEST A, BH_NOTA B WHERE B.ID_NOTA='$id' AND A.ID_REQUEST=B.ID_REQUEST";
 $result_q=$db->query($query);
 $row=$result_q->getAll();
 $tl->assign("table",$row);
 
 $query2="SELECT * from BH_DETAIL_NOTA A, BH_NOTA B, MASTER_BARANG C WHERE A.ID_NOTA='$id'  AND A.ID_NOTA=B.ID_NOTA AND C.KODE_BARANG=A.ID_BARANG";
 $result2=$db->query($query2);
 $row2=$result2->getAll();
 $tl->assign("table2",$row2);
 
 $query3="SELECT NO_CONTAINER from BH_DETAIL_REQUEST A, BH_NOTA B WHERE B.ID_NOTA='$id'  AND A.ID_REQUEST=B.ID_REQUEST";
 $result3=$db->query($query3);
 $row3=$result3->getAll();
 $tl->assign("table3",$row3);*/
 //print_r($no_req);die;
	$sql_xpi = "DECLARE no_req VARCHAR2(50); BEGIN no_req :='".$no_req."'; nota_exmo(no_req); END;";
                //echo $sql_xpi;die;		
	$db->query($sql_xpi);	
		
	$query_nota	= "SELECT a.EMKL AS EMKL, a.ALAMAT, a.NPWP FROM EXMO_REQUEST a WHERE a.ID_REQUEST = '$no_req'";				   
	$result		= $db->query($query_nota);
	$row_nota	= $result->fetchRow();
	
	$detail_nota  = "SELECT TO_CHAR(SUM(a.TARIF), '999,999,999,999') AS TARIF, TO_CHAR(SUM(a.SUB_TOTAL), '999,999,999,999') AS BIAYA, a.NO_CONTAINER,rtrim(xmlagg (xmlelement (e, KETERANGAN || ',')).extract ('//text()'), ',')  AS KETERANGAN, a.HZ, a.JUMLAH_CONT JML_CONT, b.UKURAN SIZE_, b.TYPE TYPE_, b.STATUS FROM EXMO_DETAIL_TMP a, master_barang b WHERE a.ID_CONT = b.KODE_BARANG and a.ID_REQUEST = '$no_req' GROUP BY  a.NO_CONTAINER, a.HZ, a.JUMLAH_CONT , b.UKURAN , b.TYPE , b.STATUS";
	//  print_r($detail_nota);die;
	$result       = $db->query($detail_nota);
	$row_detail   = $result->getAll();
	
	
	$total_		  = "SELECT SUM(SUB_TOTAL) TOTAL FROM EXMO_DETAIL_TMP WHERE ID_REQUEST = '$no_req'";
				   
	$result		  = $db->query($total_);
	$total2       = $result->fetchRow();
        
    $total = $total2['TOTAL'];
	
	//$no_request = $row_nota["NO_REQUEST"];
	
	//Discount
	$discount =0;
	$query_discount		= "SELECT TO_CHAR($discount , '999,999,999,999') AS DISCOUNT FROM DUAL";
        $result_discount	= $db->query($query_discount);
	$row_discount		= $result_discount->fetchRow();
	//Biaya Administrasi
	$jml_cont	= "SELECT COUNT(NO_CONTAINER) JML FROM EXMO_DETAIL_REQUEST WHERE ID_REQUEST = '$no_req'";
        //echo $total_;die;
        $result		= $db->query($jml_cont);
        $jml     	= $result->fetchRow();
		$jml_cont   = $jml['JML'];

        //$adm   = 10000+($jml_cont*10000);
	//$query_adm		= "SELECT TO_CHAR($adm , '999,999,999,999') AS ADM FROM DUAL";
	//$query_adm		= "SELECT TO_CHAR(TARIF , '999,999,999,999') AS ADM FROM MASTER_TARIF_CONT WHERE JENIS_BIAYA='ADM' AND VAL='IDR'";
	$query_adm		= "SELECT TO_CHAR(SUB_TOTAL , '999,999,999,999') AS ADM FROM EXMO_DETAIL_TMP WHERE ID_REQUEST = '$no_req' AND KETERANGAN='ADM'";
	$result_adm		= $db->query($query_adm);
	$row_adm		= $result_adm->fetchRow();
		
	//Menghitung Total dasar pengenaan pajak
        //$total_ = $total + $adm;	//adm sudah masuk detail nota tmp
        $total_ = $total;
	$query_tot		= "SELECT TO_CHAR('$total_' , '999,999,999,999') AS TOTAL_ALL FROM DUAL";
	$result_tot		= $db->query($query_tot);
	$row_tot		= $result_tot->fetchRow();
		
	//Menghitung Jumlah PPN
	$ppn = $total_/10;
	$query_ppn		= "SELECT TO_CHAR('$ppn' , '999,999,999,999') AS PPN FROM DUAL";
	$result_ppn		= $db->query($query_ppn);
	$row_ppn		= $result_ppn->fetchRow();
		
	//Menghitung Bea Materai
	$bea_materai = 0;
	$query_materai		= "SELECT TO_CHAR('$bea_materai' , '999,999,999,999') AS MATERAI FROM DUAL";
	$result_materai		= $db->query($query_materai);
	$row_materai		= $result_materai->fetchRow();
	
	//Menghitung Jumlah dibayar
	$total_bayar        =  $total_ + $ppn;
	$query_bayar        = "SELECT TO_CHAR('$total_bayar' , '999,999,999,999') AS TOTAL_BAYAR FROM DUAL";
	$result_bayar       = $db->query($query_bayar);
	$row_bayar          = $result_bayar->fetchRow();
	
	//cek sudah jadi pranota atau belum
	$q_cek="select count(*) as JUM from EXMO_NOTA where trim(ID_REQUEST)=trim('".$no_req."') and STATUS='S'";
	//print_r($q_cek);die;
	$rs_cek = $db->query($q_cek);
	$row_cek = $rs_cek->fetchRow();
	$jum_cek=$row_cek['JUM'];	
	
	$tl->assign("row_discount",$row_discount);	
	$tl->assign("row_adm",$row_adm);
	$tl->assign("row_tot",$row_tot);
	$tl->assign("row_ppn",$row_ppn);
	$tl->assign("row_materai",$row_materai);
	$tl->assign("row_bayar",$row_bayar);
	$tl->assign("row_trf_brsh",$row_trf_brsh);
	$tl->assign("row_nota",$row_nota);
	$tl->assign("no_nota",$no_nota);
	$tl->assign("no_req",$no_req);
	$tl->assign("row_detail",$row_detail);
	$tl->assign("jum_cek",$jum_cek);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
 
 $tl->renderToScreen();

?>