<?php 
$tl = xliteTemplate("load_block.htm");
$id_yard = $_POST["ID_YARD"];
$db 		= getDB("storage"); 
$query_get_block = "SELECT * FROM BLOCKING_AREA WHERE ID_YARD_AREA = '$id_yard'"; 
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
$tl->assign('html',$html);
$tl->renderToScreen();
?>