<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl =  xliteTemplate('add.htm');
	
	
	//echo HOME.APPID;
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	$tl->renderToScreen();
	print_r (date());
	
		
	
	function get_yard_asal()
	{	
		$db		= getDB("storage");
		$query	= "SELECT * FROM YARD_AREA WHERE STATUS = 'AKTIF'";
		$result	= $db->query($query);
		$row_	= $result->getAll();
		$html	= "<select name='ID_YARD_ORI' id='ID_YARD_ORI'>";
		foreach($row_ as $row)
		{
			$html .= "<option value='".$row["ID"]."' > ".$row["NAMA_YARD"]." </option>";		
		}
		
		$html	.= "</select>";
		
		echo $html;
		
	}
	function get_yard_tujuan()
	{
		$db		= getDB("storage");
		$query	= "SELECT * FROM YARD_AREA WHERE STATUS = 'AKTIF'";
		$result	= $db->query($query);
		$row_	= $result->getAll();
		$html	= "<select name='ID_YARD_DEST' id='ID_YARD_DEST'>";
		
		foreach($row_ as $row)
		{
			$html .= "<option value='".$row["ID"]."' > ".$row["NAMA_YARD"]." </option>";		
		}
		
		$html	.= "</select>";
		
		echo $html;
		
	}
?>