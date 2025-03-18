<?php
	$db=getDb();
	$nc=$_POST['NC'];
	$ves=$_POST['VES'];
	$vin=$_POST['VIN'];
	$vot=$_POST['VOT'];
	$qry="select a.TPL2 from obx_approval_h a join obx_approval_d b on a.id_plp=b.id_plp where trim(a.VESSEL) like trim('%$ves%') and 
	(trim(a.VOYAGE_IN)=trim('$vin') or trim(a.VOYAGE_IN)=trim('$vot')) AND (trim(a.VOYAGE_OUT)=trim('$vot') or trim(a.VOYAGE_OUT)=trim('$vin')) 
	AND trim(b.ID_BARANG)=trim('$nc')";
	//echo $qry;die;
	if($row=$db->query($qry))
	{
		$hs=$row->fetchRow();
		$hs2=$hs['TPL2'];
		
		if($hs2!='')
		{
			echo "Container disetujui untuk OBX ke ".$hs2;
		}
		else
			echo "";
		
	}
	
?>