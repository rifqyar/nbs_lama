<?php
 $kategori = $_POST['kategori'];
 
 if ($kategori == 'kapal') {
	header('Location: '.HOME.'maintenance.param/kapal');	
 }
 else if ($kategori == 'consignee') {
	header('Location: '.HOME.'maintenance.param/consignee');	
 } 
 else if ($kategori == 'size'){
	header('Location: '.HOME.'maintenance.param/size');		
 }
?>