<?php
	$tl = xliteTemplate('show_cont.htm');
	$db = getDB("storage");
	$no_req = $_POST["NO_REQ"];
	$get_container = "select no_container from container_stripping where no_request = '$no_req' and no_container is not null";
	$r_cont = $db->query($get_container);
	$rw_cont = $r_cont->getAll();
	
	$tl->assign('rw_cont',$rw_cont);
	$tl->assign('no_req', $no_req);
	$tl->renderToScreen();
?>