<?php
outputRaw();
$arrPost =$_POST;

$indexrow = $arrPost["indexrow"];

$tl = xliteTemplate("rows.htm");
$tl->assign('indexrow',$indexrow); 
$tl->renderToScreen();

?>