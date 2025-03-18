<?php
	$k1=$_POST['KRBM'];
	$k2=$_POST['KSF'];
	$k3=$_POST['KHM'];
	$idvsb=$_POST['ID_VSB'];
	
	$db=getDb();

	$qrbm="select A.ID_VSB_VOYAGE,A.OPERATOR_ID,A.OPERATOR_NAME 
			from bil_stv_list A WHERE A.ID_VSB_VOYAGE='$idvsb' group by A.ID_VSB_VOYAGE,A.OPERATOR_ID,A.OPERATOR_NAME order by A.OPERATOR_ID";

	$rrbm=$db->query($qrbm);
	$grbm=$rrbm->getAll();
		
	$i=0;
	
	foreach($grbm as $row)
	{
		$qr="update bil_stv_list set id_kategori='".$k1[$i]."' where id_vsb_voyage='$idvsb' and operator_id='$row[OPERATOR_ID]'";
		$db->query($qr);
		
		$i++;
	}
	
	$qceksf="select COUNT(1) QTY from bil_stv_sf a join master_iso_code b on a.iso_code=b.iso_code where A.ID_VSB_VOYAGE='$idvsb' ";

	$rceksf=$db->query($qceksf);
	
	$gceksf=$rceksf->fetchRow();

	if($gceksf['QTY']>0) {
		$qsf="select A.ID_VSB_VOYAGE,A.OPERATOR_ID, A.OPERATOR_NAME,B.SIZE_,A.JENIS_SHIFT,COUNT(1) QTY  
		from bil_stv_sf a join master_iso_code b on a.iso_code=b.iso_code where A.ID_VSB_VOYAGE='$idvsb' 
		GROUP BY A.ID_VSB_VOYAGE,A.OPERATOR_ID, A.OPERATOR_NAME,B.SIZE_,A.JENIS_SHIFT order by A.OPERATOR_ID";

		$rsf=$db->query($qsf);
		
		$gsf=$rsf->getAll();
		
		$i=0;
		foreach($gsf as $row)
		{
			$qr="update bil_stv_sf set id_kategori='".$k2[$i]."' where id_vsb_voyage='$idvsb'";
			//echo $qr;
			$db->query($qr);
			$i++;
		}

	}

	$qcekhm="select COUNT(1) QTY from bil_stv_hm where ID_VSB_VOYAGE='$idvsb' ";

	$rcekhm=$db->query($qcekhm);
	
	$gcekhm=$rcekhm->fetchRow();
	if($gcekhm['QTY']>0) {
		$db->query("update bil_stv_hm set id_kategori='".$k3[0]."' where id_vsb_voyage='$idvsb'");
	}
	
	$qrslesai="begin prc_bilstvrpd('$idvsb'); end;";
	$db->query($qrslesai);
	
	echo $k1[0];	
?>