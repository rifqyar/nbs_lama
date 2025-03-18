<?php
	$tl = xliteTemplate('show_cont.htm');
	$db = getDB("storage");
	$no_req = $_POST["NO_REQ"];
	$get_container = "select no_container from container_stuffing where no_request = '$no_req'";
	$r_cont = $db->query($get_container);
	$rw_cont = $r_cont->getAll();
	
	$tl->assign('rw_cont',$rw_cont);
	$tl->assign('no_req', $no_req);
	$tl->renderToScreen();
?>