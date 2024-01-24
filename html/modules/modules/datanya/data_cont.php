<?php
$q = $_GET['q'];
$no_req = $_GET['r'];
if(isset($q)) {
	$page = isset($_POST['page'])?$_POST['page']:1;  // get the requested page
	$limit = isset($_POST['rows'])?$_POST['rows']:10; // get how many rows we want to have into the grid
	$sidx = isset($_POST['sidx'])?$_POST['sidx']:1; // get index row - i.e. user click to sort
	//$sord = $_GET['sord']; // get the direction
	if(!$sidx) $sidx =1;
	$id = $_GET['id'];
	
	$db = getDB();
	if($q=='rec') {
		//$query=OCIparse($conn, "SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM $tb_ntbprpd WHERE ID_NOTA='$id')");
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM req_receiving_d WHERE TRIM(NO_REQ_ANNE)='$no_req')";
	}
	else if($q=='nota_delivery')		
		$query ="SELECT COUNT(1) AS NUMBER_OF_ROWS FROM 
				(SELECT 1 FROM REQ_DELIVERY_H A LEFT JOIN NOTA_DELIVERY_H B ON A.ID_REQ=B.ID_REQ AND B.STATUS NOT IN ('X','P'))";
	else if($q=='nota_delivery_tn')		
		$query ="SELECT COUNT(1) AS NUMBER_OF_ROWS FROM 
				(SELECT 1 FROM REQ_DELIVERY_H A LEFT JOIN NOTA_DELIVERY_H B ON A.ID_REQ=B.ID_REQ AND B.STATUS NOT IN ('X','P'))";
	else if($q=='del') {
		//$query=OCIparse($conn, "SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM $tb_ntbprpd WHERE ID_NOTA='$id')");
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM req_delivery_d WHERE TRIM(NO_REQ_DEV)='$no_req' )";
	}
	else if($q=='del2') {
		//$query=OCIparse($conn, "SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM $tb_ntbprpd WHERE ID_NOTA='$id')");
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM req_delivery_d WHERE TRIM(NO_REQ_DEV)='$no_req')";
	}
	else if($q=='trans') {
		$query="SELECT COUNT(*) AS NUMBER_OF_ROWS FROM (SELECT * FROM REQ_TRANSHIPMENT_D WHERE TRIM(ID_REQ)='$no_req')";
	}
	else if($q=='printRenameContainer') {
		$query="SELECT COUNT(1) AS NUMBER_OF_ROWS FROM RENAME_CONTAINER";
	}
	$res = $db->query($query)->fetchRow();
	$count = $res[NUMBER_OF_ROWS];	
		
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
	if($q=='rec') 
	{
		$query="SELECT b.NO_CONTAINER,
       b.SIZE_CONT,
       (SELECT a.NM_COMMODITY
          FROM master_commodity a
         WHERE a.KD_COMMODITY = b.KD_COMODITY)
          COMMD,
       b.TYPE_CONT,
       b.STATUS_CONT,
       b.HZ,
       b.ISO_CODE,
       b.HEIGHT,
       b.CARRIER,
       b.OG,
       c.VESSEL_CODE,
       c.VOYAGE
  FROM    req_receiving_d b
       INNER JOIN
          M_VSB_VOYAGE@DBINT_LINK c
       ON (TRIM(b.VOYAGE_IN) = TRIM(c.VOYAGE_IN)
           AND TRIM(b.VOYAGE_OUT) = TRIM(c.VOYAGE_OUT)
           AND TRIM (b.vessel) = TRIM (c.vessel)) 
		   where b.NO_REQ_ANNE='$no_req'";
	}
	else if($q=='nota_delivery') { //ambil data header
			$query="SELECT a.ID_REQ,
	                     a.EMKL AS EMKL, A.TGL_REQUEST AS TGL,
	                     a.VESSEL,
	                     a.VOYAGE_IN ||' - ' ||a.VOYAGE_OUT AS VOYAGES,
	                     d.ID_NOTA,
	                     a.STATUS,
	                     a.TIPE_REQ,
	                     a.QTY, to_char(a.TGL_REQUEST, 'yyyy/mm/dd HH24:Mi:ss') AS TGL_REQUEST
	                FROM req_delivery_h a left join nota_delivery_h d on a.ID_REQ = d.ID_REQ and d.status!='X'
	                WHERE a.STATUS !='P'
	                --AND a.id_req = '$no_req'
	                --ORDER BY A.TGL_REQUEST DESC";
        //echo $query; die();
    }
	else if($q=='nota_delivery_tn') { //ambil data header
			$query="SELECT a.ID_REQ,
	                     a.EMKL AS EMKL, A.TGL_REQUEST AS TGL,
	                     a.VESSEL,
	                     a.VOYAGE_IN ||' - ' ||a.VOYAGE_OUT AS VOYAGES,
	                     d.ID_NOTA,
	                     a.STATUS,
	                     a.TIPE_REQ,
	                     a.QTY, to_char(a.TGL_REQUEST, 'yyyy/mm/dd HH24:Mi:ss') AS TGL_REQUEST
	                FROM req_delivery_h a left join nota_delivery_h d on a.ID_REQ = d.ID_REQ and d.status!='X'
	                WHERE a.STATUS !='P'
	                --AND a.id_req = '$no_req'
	                --ORDER BY A.TGL_REQUEST DESC";
        //echo $query; die();
    }
	else if($q=='del') 
	
	{
		$query="SELECT b.NO_CONTAINER,
       b.SIZE_CONT,
       '' COMMD,
       b.TYPE_CONT,
       b.STATUS_CONT,
       b.HZ,
       b.VESSEL,
       b.VOYAGE,
       b.NO_REQ_DEV,
       b.CARRIER,
       c.VESSEL_CODE,
       c.VOYAGE as VESVOY       
  FROM    req_delivery_d b
       INNER JOIN
          M_VSB_VOYAGE@DBINT_LINK c
       ON (TRIM (b.NO_UKK) = TRIM (c.ID_VSB_VOYAGE)
           AND TRIM (b.vessel) = TRIM (c.vessel)) 
		   where b.NO_REQ_DEV='$no_req'";
	}
	else if($q=='del2') 
	{
		$query="SELECT b.NO_CONTAINER, b.SIZE_CONT,'' COMMD, 
				b.TYPE_CONT, b.STATUS_CONT, b.HZ, b.VESSEL, b.VOYAGE, b.NO_REQ_DEV, to_char(b.PLUG_IN,'dd-mm-yyyy hh24:mi') PLUG_IN, to_char(b.PLUG_OUT,'dd-mm-yyyy hh24:mi') PLUG_OUT, to_char(b.PLUG_OUT_EXT,'dd-mm-yyyy hh24:mi') PLUG_OUT_EXT from req_delivery_d b where b.NO_REQ_DEV='$no_req'";
	}
	else if($q=='trans') 
	{
		$query="SELECT b.NO_CONTAINER, b.SIZE_, b.TYPE_, b.STATUS_, b.HZ, b.HEIGHT, b.NO_UKK_OLD, b.NO_UKK_NEW from REQ_TRANSHIPMENT_D b where b.ID_REQ='$no_req' ORDER BY URUT";
	}
	else if($q=='printRenameContainer') 
	{
		$query=" SELECT * FROM (select NO_RENAME, EI, VESSEL, VOYAGE, PBM, NO_CONTAINER, NO_EX_CONTAINER,1 as QTY, ID_NOTA, NO_FAKTUR, TGL_RENAME
            from nota_rename_container where STATUS IN ('P','T')
            union
            select no_rename, EI, VESSEL, VOYAGE, PBM, NO_CONTAINER, NO_EX_CONTAINER, 1 as QTY, 'TANPA BIAYA' ID_NOTA, 'TANPA BIAYA' NO_FAKTUR, TGL_RENAME
            from rename_container where BIAYA = 'N') ORDER BY TGL_RENAME DESC";
	}
	
	$res = $db->query($query);								
	while ($row = $res->fetchRow()) 
	{
		$aksi = "";
		if($q=='rec'){
			$ctx=$row[NO_CONTAINER];
			$ves=$row[VESSEL_CODE];
			$voy=$row[VOYAGE];
			$carrier=$row[CARRIER];
			$aksi="<button onclick='del(\"$no_req\",\"$ctx\",\"$ves\",\"$voy\",\"$carrier\")'><img src=".HOME."images/del2.png></button>";
			$ct=$row[SIZE_CONT].' '.$row[TYPE_CONT].' '.$row[STATUS_CONT];
			$responce->rows[$i]['id']=$row[NO_CONTAINER];
			$responce->rows[$i]['cell']=array($aksi,$row[NO_CONTAINER],$ct,$row[HZ],$row[ISO_CODE],$row[HEIGHT],$row[CARRIER],$row[OG], $row[COMMD]);
		}else if($q=='del'){
			$ctx=$row[NO_CONTAINER];
			$no_req = $row[NO_REQ_DEV];
			$ves=$row[VESSEL_CODE];
			$voy=$row[VOYAGE];
			$carrier=$row[CARRIER];
			$aksi="<button onclick='del(\"$ctx\",\"$no_req\",\"$ves\",\"$voy\",\"$carrier\")'><img src=".HOME."images/del2.png></button>";
			$ct=$row[SIZE_CONT].' '.$row[TYPE_CONT].' '.$row[STATUS_CONT];
			$responce->rows[$i]['id']=$row[NO_CONTAINER];
			$responce->rows[$i]['cell']=array($aksi,$row[NO_CONTAINER],$ct,$row[HZ],$row[VESSEL],$row[VOYAGE],$row[COMMD]);
		}
		else if($q=='del2'){
			$ctx=$row[NO_CONTAINER];
			$no_req = $row[NO_REQ_DEV];
			/*$aksi="<button onclick='del(\"$ctx\",\"$no_req\")'><img src=".HOME."images/del2.png></button>";*/
			$aksi="<input type='checkbox' name='choosen[]' value='".$row[NO_CONTAINER]."'/>";
			$ct=$row[SIZE_CONT].' '.$row[TYPE_CONT].' '.$row[STATUS_CONT];
			$responce->rows[$i]['id']=$row[NO_CONTAINER];
			$responce->rows[$i]['cell']=array("",$aksi,$row[NO_CONTAINER],$ct,$row[HZ],$row[VESSEL],$row[VOYAGE],$row[PLUG_IN],$row[PLUG_OUT],$row[PLUG_OUT],$row[PLUG_OUT_EXT]);
		}
		/*else if($q=='del'){
			$ctx=$row[NO_CONTAINER];
			$no_req = $row[NO_REQ_DEV];
			$aksi="<button onclick='del(\"$ctx\",\"$no_req\")'><img src=".HOME."images/del2.png></button>";
			$ct=$row[SIZE_].' '.$row[TYPE_].' '.$row[STATUS_];
			$responce->rows[$i]['id']=$row[NO_CONTAINER];
			$responce->rows[$i]['cell']=array($aksi,$row[NO_CONTAINER],$ct,$row[HZ],$row[HEIGHT],$row[NO_UKK_OLD],$row[NO_UKK_NEW]);
		}*/
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
			else if ($row[STATUS]=="F")
			{
				$aksi   = "<b><font color='green'>Tidak dikenakan biaya</font></b>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else
			{
				$aksi = "<button title='Preview Nota' onclick=\"return cek_save('".$row[TIPE_REQ]."','".$row[ID_REQ]."');\"><img src='images/preview.png' title='preview'></button>";
				$status="<b><font color='red'>nota belum disave</font></b>";
			}
			$responce->rows[$i]['id']=$row[TGL_REQUEST];
			$responce->rows[$i]['cell']=array($row[TGL_REQUEST],$aksi,$status,$row[TIPE_REQ],$row[ID_NOTA],$row[ID_REQ],$row[QTY],$row[EMKL],$row[VESSEL].' / '.$row[VOYAGES]);
		}
		else if($q == 'nota_delivery_tn') 
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
			else if ($row[STATUS]=="F")
			{
				$aksi   = "<b><font color='green'>Tidak dikenakan biaya</font></b>";
				
				$status = "<b><font color='green'>".$row[STATUS]."</font></b>";
			}
			else
			{
				$aksi = "<button title='Preview Nota' onclick=\"return cek_save('".$row[TIPE_REQ]."','".$row[ID_REQ]."');\"><img src='images/preview.png' title='preview'></button><img src='images/no_charge.jpg' onclick=\"confirm_no_charge('".$row[ID_REQ]."')\" title='no charge'>";
				$status="<b><font color='red'>nota belum disave</font></b>";
			}
			$responce->rows[$i]['id']=$row[TGL_REQUEST];
			$responce->rows[$i]['cell']=array($row[TGL_REQUEST],$aksi,$status,$row[TIPE_REQ],$row[ID_NOTA],$row[ID_REQ],$row[QTY],$row[EMKL],$row[VESSEL].' / '.$row[VOYAGES]);
		}
		else if($q == 'printRenameContainer') 
		{
			$aksi   = "<a href=".HOME."print.kartuRenameContainer.print/printKartu?no_req=".$row[NO_RENAME]." ><img src='images/printer.png' title='print_nota'></a>";
			$responce->rows[$i]['id']=$row[TGL_PAYMENT];
			$responce->rows[$i]['cell']=array($aksi,$row[ID_NOTA],$row[NO_RENAME],$row[EI],$row[QTY],$row[PBM],$row[VESSEL].' / '.$row[VOYAGE], $row[NO_CONTAINER], $row[NO_EX_CONTAINER]);
		}
		$i++;
	}
	echo json_encode($responce);
}
?>