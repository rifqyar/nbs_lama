<?php

	outputRaw();

	$tl = xliteTemplate("detail.htm");


	$db 		= getDB("storage"); 
	$tgl_awal 	= $_POST['TGL_AWAL'];
	$tgl_akhir 	= $_POST['TGL_AKHIR'];
	$kegiatan 	= $_POST['KEGIATAN'];
	$status 	= $_POST['STATUS'];
	$id_menu2 	= $_POST['id_menu2'];
	
	$jum = count($id_menu2);
	$orderby = 'ORDER BY ';
	for($i=0;$i<count($id_menu2);$i++){
		$orderby .= $id_menu2[$i];
		if($i != $jum-1){
			$orderby .= ",";
		}
		//echo $jum;
	}
	if($orderby  == 'ORDER BY n'){ 
		$orderby= ""; 
	}
	//print_r($orderby);die;
	
	if ($kegiatan == 'receiving_luar'){
		if ($status == 'req'){
			$query_status = 'and tgl_request is not null';
		} else if ($status == 'gati'){
			$query_status = 'and tgl_in is not null';
		} else if ($status == 'plac'){
			$query_status = 'and tgl_placement is not null';
		}
		
		$query_list = "SELECT V_STATUS_REC_LUAR.*,  TO_CHAR(TGL_PLACEMENT,'dd/mm/rrrr') TGL_PLACEMENT_  FROM V_STATUS_REC_LUAR WHERE TGL_REQUEST BETWEEN TO_DATE('$tgl_awal','dd/mm/yyyy') AND TO_DATE('$tgl_akhir','dd/mm/yyyy') " . $query_status." ".$orderby;
		//echo $query_list;
	} else if ($kegiatan == 'receiving_tpk'){
		if ($status == 'req'){
			$query_status = 'and tgl_request is not null';
		} else if ($status == 'gati'){
			$query_status = 'and tgl_in is not null';
		} else if ($status == 'plac'){
			$query_status = 'and tgl_placement is not null';
		}
		
		$query_list = "SELECT V_STATUS_REC_TPK.* ,  TO_CHAR(TGL_PLACEMENT,'dd/mm/rrrr') TGL_PLACEMENT_ FROM V_STATUS_REC_TPK WHERE TGL_REQUEST BETWEEN TO_DATE('$tgl_awal','dd/mm/yyyy') AND TO_DATE('$tgl_akhir','dd/mm/yyyy')" . $query_status;
		
	
	} else if ($kegiatan == 'stripping_tpk'){
		if ($status == 'tgl_app'){
			$query_list = "SELECT V_STATUS_STRIPPING_TPK_PLAN.* FROM V_STATUS_STRIPPING_TPK_PLAN WHERE TGL_REQUEST BETWEEN TO_DATE('$tgl_awal','dd/mm/yyyy') AND TO_DATE('$tgl_akhir','dd/mm/yyyy')" . $query_status . " ". $orderby;
			
		} else {
			 if ($status == 'req'){
				$query_status = 'and tgl_request is not null';
			} else if ($status == 'gati'){
				$query_status = 'and tgl_in is not null';
			} else if ($status == 'plac'){
				$query_status = 'and tgl_placement is not null';
			} else if ($status == 'real'){
				$query_status = 'and tgl_realisasi is not null';
			} else if ($status == 'plac_mty'){
				$query_status = 'and tgl_relokasi is not null';
			}
		
			$query_list = "SELECT V_STATUS_STRIPPING_TPK.*,  TGL_PLACEMENT TGL_PLACEMENT_  FROM V_STATUS_STRIPPING_TPK WHERE TGL_REQUEST BETWEEN TO_DATE('$tgl_awal','dd/mm/yyyy') AND TO_DATE('$tgl_akhir','dd/mm/yyyy')" . $query_status . " ". $orderby;
		}
		
	} else if ($kegiatan == 'stripping_depo'){
	   if ($status == 'req'){
			$query_status = 'and tgl_request is not null';
		} else if ($status == 'gati'){
			$query_status = 'and tgl_gate is not null';
		} else if ($status == 'plac'){
			$query_status = 'and tgl_placement is not null';
		} else if ($status == 'tgl_app'){
			$query_status = 'and tgl_approve is not null';
		} else if ($status == 'real'){
			$query_status = 'and tgl_realisasi is not null';
		} else if ($status == 'plac_mty'){
			$query_status = 'and tgl_placement is not null';
		}
		
		$query_list = "SELECT V_STATUS_STRIPPING_DEPO.*,  TO_CHAR(TGL_PLACEMENT,'dd/mm/rrrr') TGL_PLACEMENT_ FROM V_STATUS_STRIPPING_DEPO WHERE TGL_REQUEST BETWEEN TO_DATE('$tgl_awal','dd/mm/yyyy') AND TO_DATE('$tgl_akhir','dd/mm/yyyy')" . $query_status;
		
	
	} else if ($kegiatan == 'stuffing_tpk'){
		   if ($status == 'req'){
			$query_status = 'and tgl_request is not null';
		} else if ($status == 'gati'){
			$query_status = 'and tgl_gate is not null';
		} else if ($status == 'plac'){
			$query_status = 'and tgl_placement is not null';
		} else if ($status == 'tgl_app'){
			$query_status = 'and tgl_approve is not null';
		} else if ($status == 'real'){
			$query_status = 'and tgl_realisasi is not null';
		} else if ($status == 'gato'){
			$query_status = 'and tgl_placement is not null';
		}
		
		$query_list = "SELECT *, TO_CHAR(TGL_PLACEMENT,'dd/mm/rrrr') TGL_PLACEMENT_  FROM V_STATUS_STUFFING_DEPO WHERE TGL_REQUEST BETWEEN TO_DATE('$tgl_awal','dd/mm/yyyy') AND TO_DATE('$tgl_akhir','dd/mm/yyyy')" . $query_status;
		
	
	} else if ($kegiatan == 'stuffing_depo'){
		if ($status == 'req'){
			$query_status = 'and tgl_request is not null';
		} else if ($status == 'real'){
			$query_status = 'and tgl_realisasi is not null';
		} else if ($status == 'req_del'){
			$query_status = 'and tgl_req_delivery is not null';
		} else if ($status == 'gato'){
			$query_status = 'and tgl_gate is not null';
		}
		
		$query_list = "SELECT V_STATUS_STUFFING_DEPO.* FROM V_STATUS_STUFFING_DEPO WHERE TGL_REQUEST BETWEEN TO_DATE('$tgl_awal','dd/mm/yyyy') AND TO_DATE('$tgl_akhir','dd/mm/yyyy')" . $query_status." ".$orderby;

		
	} else if ($kegiatan == 'delivery_tpk_mty'){
		if ($status == 'req'){
			$query_status = 'and tgl_request is not null';
		} else if ($status == 'gato'){
			$query_status = 'and tgl_gate is not null';
		}
		
		$query_list = "SELECT V_STATUS_REPO_MTY.* FROM V_STATUS_REPO_MTY WHERE TGL_REQUEST BETWEEN TO_DATE('$tgl_awal','dd/mm/yyyy') AND TO_DATE('$tgl_akhir','dd/mm/yyyy') " . $query_status." ".$orderby;
	
	} else if ($kegiatan == 'delivery_luar'){
		if ($status == 'req'){
			$query_status = 'and tgl_request is not null';
		} else if ($status == 'gato'){
			$query_status = 'and tgl_gate is not null';
		}
		
		$query_list = "SELECT V_DELIVERY_SP2.* FROM V_DELIVERY_SP2 WHERE TGL_REQUEST BETWEEN TO_DATE('$tgl_awal','dd/mm/yyyy') AND TO_DATE('$tgl_akhir','dd/mm/yyyy') " . $query_status." ".$orderby;
	
	} else if ($kegiatan == 'relokasi'){
	
	}
	//echo $query_list; die();
	
	$query = $db->query($query_list);
	$row_list = $query->getAll();
	
	$tl->assign("row_list",$row_list);
	$tl->assign("kegiatan",$kegiatan);
	$tl->assign("status",$status);
	$tl->assign("HOME",HOME);
	$tl->assign("APPID",APPID);
	
	$tl->renderToScreen();
?>
