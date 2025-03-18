<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 		=  xliteTemplate('home.htm');
	$yard_id	= $_SESSION["IDYARD_STORAGE"];
	
	
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	$tl->assign("yard_id",$yard_id);
	
	$tl->renderToScreen();
	
	function list_yard(){
		$db 		= getDB("storage"); 
		
		$query_get_yard = "SELECT * FROM YARD_AREA WHERE STATUS = 'AKTIF'";
		$result_yard = $db->query($query_get_yard);
		$row_yard = $result_yard->getAll();
		
		$htmla = "<select name='yard' id='yard' style='width: 80px;' onchange='change_yard()'>";
		foreach($row_yard as $row_)
		{	
			$yard	= $row_["NAMA_YARD"];
			//$ket	= $row_["KETERANGAN"];
			$id		= $row_["ID"];
			$htmla	.= "<option value='".$id."'>".$yard."</option>";
		}
		$htmla .= "</select>";
		echo $htmla;
	}
	
	function list_block($yard_id)
	{
		$db 		= getDB("storage"); 

		$query_get_block = "SELECT * FROM BLOCKING_AREA WHERE ID_YARD_AREA = '46'"; 
		$result_block	 = $db->query($query_get_block);
		$row_block		 = $result_block->getAll();
		
		$html	= "<select name='block' id='blocking' style='width: 80px; padding-top: 10px;'>";
		foreach($row_block as $row_)
		{	
			$block	= $row_["NAME"];
			$ket	= $row_["KETERANGAN"];
			$id		= $row_["ID"];
			$html	.= "<option value='".$id."'>".$block." [".$ket."]</option>";			
		}
		$html		.= "</select>";
		echo $html;
	}
?>
