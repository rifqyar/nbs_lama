<?php
	//header('Location: '.HOME .'static/error.htm');		
	$tl 	=  xliteTemplate('req_list.htm');
	
	if(isset($_GET["cari"]))
	{
		$req   = $_POST['no_req'];
                $from  = $_POST['from'];
                $to    = $_POST['to'];
                if (($req <> NULL) && ($from <> NULL) && ($to <> NULL)){
		$query_list = "SELECT REQUEST_DELIVERY.NO_REQUEST,TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, emkl.NAMA AS NAMA_EMKL 
                    FROM REQUEST_DELIVERY 
                    INNER JOIN MASTER_PBM emkl ON REQUEST_DELIVERY.ID_EMKL = emkl.ID 
                    WHERE 
                    REQUEST_DELIVERY.NO_REQUEST LIKE '%".$req."%' 
                    AND REQUEST_DELIVERY.TGL_REQUEST 
                    BETWEEN TO_DATE('".$from."', 'dd/mm/yyyy') AND 
                    TO_DATE('".$to."', 'dd/mm/yyyy') 
                    ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";
                } else if ((($req == NULL) && ($from <> NULL) && ($to <> NULL))){
                    $query_list = "SELECT REQUEST_DELIVERY.NO_REQUEST,TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, emkl.NAMA AS NAMA_EMKL 
                    FROM REQUEST_DELIVERY 
                    INNER JOIN MASTER_PBM emkl ON REQUEST_DELIVERY.ID_EMKL = emkl.ID 
                    WHERE 
                    REQUEST_DELIVERY.TGL_REQUEST 
                    BETWEEN TO_DATE('".$from."', 'dd/mm/yyyy') AND 
                    TO_DATE('".$to."', 'dd/mm/yyyy') 
                    ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";
                } else if ((($req <> NULL) && ($from == NULL) && ($to == NULL))){
                    $query_list = "SELECT REQUEST_DELIVERY.NO_REQUEST,TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST_DELIVERY,'dd/mm/yyyy') TGL_REQUEST_DELIVERY, TO_CHAR( REQUEST_DELIVERY.TGL_REQUEST,'dd/mm/yyyy') TGL_REQUEST, emkl.NAMA AS NAMA_EMKL 
                    FROM REQUEST_DELIVERY 
                    INNER JOIN MASTER_PBM emkl ON REQUEST_DELIVERY.ID_EMKL = emkl.ID 
                    WHERE REQUEST_DELIVERY LIKE '%".$req."'
                    ORDER BY REQUEST_DELIVERY.NO_REQUEST DESC";
                }
	}
	else
	{
		header('Location: '.HOME.APPID);		
	}
	$db = getDB("storage");
	
        
        
        
	$result_request	= $db->query($query_list);
	$row_request	= $result_request->fetchRow();
	
	//debug($row_request);
	
	$tl->assign("row_request", $row_request);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
