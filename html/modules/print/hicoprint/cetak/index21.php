<?php

	$tl=xliteTemplate("print.html");

	$filter = array( array('name'=>'KD_BHD_REQS') );

	_map($filter);





require_lib('acl.php');	

		$acl = new ACL();

		$acl->load(); 

		$aclist = $acl->getLogin()->info;	

		//echo "<pre>";print_r($list);exit;

		$KD_CABANG = ($aclist['KD_CABANG'] == '')?'00':$aclist['KD_CABANG'];

$userid = $aclist["USERID"];

$tl->assign('userid',$userid);



	$db = getDB('ora');

	$tanggal_sekarang=date ("j M Y H:i")  ;



	$sql="SELECT 

			  DISTINCT

			  TTD_BHD.BHD_ID,

			  TTD_BHD.TGL_BHD,

			  TTD_BHD.TGL_OUT,

			  TTD_BHD.REMARKS,

			  TTD_BHD.NO_BL,

			  TTD_BHD_REQS.KD_BHD_REQS,

			  TTD_BHD_REQS.TGL_REQ,

			  TTD_BHD_REQS.KD_PBM,

			  V_MST_PBM.NM_PBM,

			  V_CONT_CYNEW.CONT_NO_BP,

			  TTD_BHD_REQS.KETERANGAN,

			  V_CONT_CYNEW.KD_SIZE,

			  V_CONT_CYNEW.KD_TYPE,

			  V_CONT_CYNEW.KD_STATUS_CONT,

			  V_CONT_CYNEW.TGL_JAM_TIBA,

			  V_CONT_CYNEW.VOYAGE_IN,

			  V_CONT_CYNEW.VOYAGE_OUT,

			  V_CONT_CYNEW.NM_AGEN,

			  V_CONT_CYNEW.KD_AGEN,

			  V_CONT_CYNEW.NM_KAPAL,

			  V_CONT_CYNEW.ARE_BLOK,

			  V_CONT_CYNEW.ARE_ROW,

			  V_CONT_CYNEW.ARE_TIER,

			  V_CONT_CYNEW.ARE_SLOT,

			  TTH_NOTA_ALL.KD_UPER_LUNAS

			FROM

			  TTD_BHD,

			  TTD_BHD_REQS,

			  V_MST_PBM,

			  V_CONT_CYNEW,

			  TTH_NOTA_ALL

			WHERE

			  TTD_BHD_REQS.KD_BHD_REQS = '".addslashes($_q['KD_BHD_REQS'])."' AND

			  V_CONT_CYNEW.CONT_NO_BP = TTD_BHD.KD_BP_CONT AND

			  TTD_BHD.KD_BHD_REQS = TTD_BHD_REQS.KD_BHD_REQS AND

			  V_MST_PBM.KD_PBM = TTD_BHD_REQS.KD_PBM AND
			  V_MST_PBM.KD_CABANG = TTD_BHD_REQS.KD_CABANG AND

              TTH_NOTA_ALL.KD_PERMINTAAN=TTD_BHD_REQS.KD_BHD_REQS AND TTH_NOTA_ALL.STATUS_NOTA='0' AND		  

			  V_CONT_CYNEW.NO_UKK = TTD_BHD_REQS.NO_UKK ";

			  



		  //echo $sql;



    $rs = $db->query($sql);

	$row = $rs->GetAll();



	outputRaw();

	

	$tl->assign('row', $row);

	$tl->assign('tanggal_sekarang', $tanggal_sekarang);



	$tl->renderToScreen();

?>