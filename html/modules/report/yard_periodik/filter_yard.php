<?php

$tgl = $_POST['tgl_awal'];
$per = $_POST['periode'];
$kat = $_POST['kategori'];
$tgl_new = str_replace('/','_',$tgl);

//echo $tgl; echo $per; echo $tgl_new;die;
if ($kat == 1) {
	header('Location: '.HOME.APPID.'.yard/load_layout_allocation?tgl='.$tgl_new.'&per='.$per);	
} else {
	header('Location: '.HOME.APPID.'.yard/load_layout_placement?tgl='.$tgl_new.'&per='.$per);		
}

?>