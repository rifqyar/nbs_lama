<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 		=  xliteTemplate('home.htm');
	$yard_id	= $_SESSION["IDYARD_STORAGE"];
	
	
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	$tl->assign("yard_id",$yard_id);
	
	$tl->renderToScreen();
	
	function list_block($yard_id)
	{
		$db 		= getDB("storage"); 

		$query_get_block = "SELECT * FROM BLOCKING_AREA WHERE ID_YARD_AREA = '$yard_id'"; 
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
