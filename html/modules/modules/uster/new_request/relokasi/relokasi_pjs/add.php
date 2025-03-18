<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('add.htm');
	
	
	//echo HOME.APPID;
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	$tl->renderToScreen();
	print_r (date());
	
	function get_yard_dest()
	{
		
		$db		= getDB("storage");
		$query	= "SELECT * FROM YARD_AREA WHERE STATUS = 'AKTIF'";
		$result	= $db->query($query);
		$row_	= $result->getAll();
		$html	= "<select name='ID_YARD_DEST' id='YARD_DEST'>";
		
		foreach($row_ as $row)
		{
			$html .= "<option value='".$row["ID"]."' > ".$row["NAMA_YARD"]." </option>";		
		}
		
		$html	.= "</select>";
		
		echo $html;
		
	}
	
	function get_yard_origin()
	{
		
		$db		= getDB("storage");
		$query	= "SELECT * FROM YARD_AREA WHERE STATUS = 'AKTIF'";
		$result	= $db->query($query);
		$row_	= $result->getAll();
		$html	= "<select name='ID_YARD_ORI' id='YARD_ORI'>";
		
		foreach($row_ as $row)
		{
			$html .= "<option value='".$row["ID"]."' > ".$row["NAMA_YARD"]." </option>";		
		}
		
		$html	.= "</select>";
		
		echo $html;
		
	}
?>