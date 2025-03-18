<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('cont_list.htm');

	
	$db = getDB("storage");
	
	$cari	= $_POST["cari"]; 
	$to		= $_POST["no_peraturan"];
	$from	= $_POST["from"];

	if(isset($_POST["cari"]) ) 
	{
		if(($to != NULL) && ($from == NULL))
		{
			$query_list		="SELECT a.id,a.no_peraturan AS NO_PERATURAN,a.tgl_peraturan AS TGL_PERATURAN ,a.NOMINAL AS NOMINAL,a.STATUS from master_materai a where A.NO_PERATURAN ='$to'";
		}
		else if(($from != NULL) && ($to == NULL))
		{
			$query_list		="SELECT a.id,a.no_peraturan AS NO_PERATURAN,a.tgl_peraturan AS TGL_PERATURAN ,a.NOMINAL AS NOMINAL,a.STATUS from master_materai a where TO_CHAR(A.TGL_PERATURAN,'YYYY-MM-DD') ='$from'";
			//print_r($query_list);die();
		}
		else if((isset($_POST["from"])) && (isset($_POST["no_peraturan"])))
		{
			$query_list		="SELECT a.id,a.no_peraturan AS NO_PERATURAN,a.tgl_peraturan AS TGL_PERATURAN ,a.NOMINAL AS NOMINAL,a.STATUS from master_materai a where NO_PERATURAN='$to' AND TO_CHAR(TGL_PERATURAN,'YYYY-MM-DD') ='$from'";
		}
	}
	else
	{
		
		$query_list		="SELECT a.id,a.no_peraturan AS NO_PERATURAN,a.tgl_peraturan AS TGL_PERATURAN ,a.NOMINAL AS NOMINAL,a.STATUS from master_materai a order by id desc";
		
	}
	
	$result_list	= $db->query($query_list);
	$row_list		= $result_list->getAll(); 
	//debug($row_list);
	//debug($row_trf);
	
	$tl->assign("row_list",$row_list);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
	
	

?>
