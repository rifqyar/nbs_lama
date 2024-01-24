<?php

$tanggal=date("dmY");
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=LAP-CONT-BY-STATUS-".$tanggal.".xls");
header("Pragma: no-cache");
header("Expires: 0");

	$db 	= getDB("storage");
	$tgl_awal  = $_POST['tgl_awal'];
    $tgl_akhir  = $_POST['tgl_akhir'];
    $kegiatan   = $_POST['kegiatan'];
    $status     = $_POST['STATUS'];
    $id_menu2   = $_POST['menu2'];
	
	$jum = count($id_menu2); 
    $orderby = 'ORDER BY ';
    for($i=0;$i<count($id_menu2);$i++){
        $orderby .= $id_menu2[$i];
        if($i != $jum-1){
            $orderby .= ",";
        }
        
    }
    if($jum  == 0){
        $orderby= "";
    }
	
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
		
			$query_list = "SELECT V_STATUS_STRIPPING_TPK.*,  TO_CHAR(TGL_PLACEMENT,'dd/mm/rrrr') TGL_PLACEMENT_  FROM V_STATUS_STRIPPING_TPK WHERE TGL_REQUEST BETWEEN TO_DATE('$tgl_awal','dd/mm/yyyy') AND TO_DATE('$tgl_akhir','dd/mm/yyyy')" . $query_status . " ". $orderby;
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
    
    $query = $db->query($query_list);
    $row_list = $query->getAll();
    if ($kegiatan == 'receiving_luar') {
        $kegiatan_ = 'Receiving dari Luar / MTY';
    }
    else if ($kegiatan == 'delivery_luar') {
        $kegiatan_ = 'Delivery ke Luar / SP2';
    }
    else if ($kegiatan == 'delivery_tpk_mty') {
        $kegiatan_ = 'Repo MTY';
    }
    else if ($kegiatan == 'stuffing_depo') {
        $kegiatan_ = 'Stuffing';
    }
    else if ($kegiatan == 'stripping_tpk') {
        $kegiatan_ = 'Stripping';
    }


?>
			<center>	<h2> Laporan Container By Status untuk kegiatan <?=$kegiatan_?><br/> Periode <?=$tgl_awal?> s/d <?=$tgl_akhir?></h2> </center>
           <? if (($kegiatan == 'receiving_tpk') OR ($kegiatan == 'receiving_luar')){ ?>
  <table class="grid-table" border="1" cellpadding="1" cellspacing="1"  width="100%" >
    <tr>
         <th valign="top" class="grid-header"  style="font-size:8pt">No</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">No Container</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Size</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Type</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Status</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Request</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">No Request</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">EMKL</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Peralihan</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Tgl GATE IN</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">NoPoL</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Placement</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Lokasi</th>
    </tr>
    <? $no= 1;
    foreach ($row_list as $row){?>
    <tr>
        <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$no?></td>
         <td width="5%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NO_CONTAINER']?></td>
         <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['SIZE_']?></td>
         <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TYPE_']?></td>
         <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['STATUS']?></td>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_REQUEST']?></td>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NO_REQUEST']?></td>
         <td width="8%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NM_PBM']?></td>
         <td width="8%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['PERALIHAN']?></td>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_IN']?></td>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NOPOL']?></td>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_PLACEMENT_']?></td>
         <td width="8%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NAMA_BLOK']?>-<?=$row['SLOT_']?>-<?=$row['ROW_']?>-<?=$row['TIER_']?></td>
    </tr>
    <? $no++;}?>
</table>
<? } else if ($kegiatan == 'stripping_tpk'){ ?>
    <table class="grid-table" border='0' cellpadding="1" cellspacing="1"  width="100%" >
    <tr>
         <th valign="top" class="grid-header"  style="font-size:8pt">No</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">No Container</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Size</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Type</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Status</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Request</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">No Request</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">EMKL</th>
         <?php if ($status != 'tgl_app'){ ?>
         <th valign="top" class="grid-header"  style="font-size:8pt">TGL IN</th>
         <!-- <th valign="top" class="grid-header"  style="font-size:8pt">NoPoL</th> -->
         <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Placement</th>      
         <th valign="top" class="grid-header"  style="font-size:8pt">Lokasi</th>
         <?php } ?>
         <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Approve</th>
         <?php if ($status != 'tgl_app'){ ?>
         <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Realisasi</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Relokasi</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Lokasi</th>
         <?php } ?>
         
    </tr>
    <? $no= 1;
    foreach ($row_list as $row){?>
    <tr>
        <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$no?></td>
         <td width="5%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NO_CONTAINER']?></td>
         <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['SIZE_']?></td>
         <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TYPE_']?></td>
         <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['STATUS']?></td>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_REQUEST']?></td>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NO_REQUEST']?></td>
         <td width="8%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NM_PBM']?></td>
         <?php if ($status != 'tgl_app'){ ?>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_IN']?></td>
         <!-- <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NOPOL']?></td> -->
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_PLACEMENT_']?></td>
         <td width="8%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NAMA_BLOK']?>-<?=$row['SLOT_']?>-<?=$row['ROW_']?>-<?=$row['TIER_']?></td>
         <?php } ?>
          <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_APPROVE']?></td>
          <?php if ($status != 'tgl_app'){ ?>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_REALISASI']?></td>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_RELOKASI']?></td>
         <td width="8%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NAMA_BLOK_MTY']?>-<?=$row['SLOT_MTY']?>-<?=$row['ROW_MTY']?>-<?=$row['TIER_MTY']?></td>
         <?php } ?>
    </tr>
    <? $no++;}?>
</table>
<?php } else if ($kegiatan == 'stuffing_depo' || $kegiatan == 'stuffing_tpk'){ ?>
    <table class="grid-table" border='0' cellpadding="1" cellspacing="1"  width="100%" >
    <tr>
         <th valign="top" class="grid-header"  style="font-size:8pt">No</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">No Container</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Size</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Type</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Status</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Request</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">No Request</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">EMKL</th>
         <?php if ( $kegiatan == 'stuffing_tpk') { ?>
         <th valign="top" class="grid-header"  style="font-size:8pt">Tgl In</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Placement</th>
         <?php }?>
         <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Realisasi</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Request Delivery</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">No Request Delivery</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Nm Kapal</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Gate Out</th>
    </tr>
    <?php $no= 1;
    foreach ($row_list as $row){?>
    <tr>
        <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$no?></td>
         <td width="5%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NO_CONTAINER']?></td>
         <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['SIZE_']?></td>
         <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TYPE_']?></td>
         <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['STATUS']?></td>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_REQUEST']?></td>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NO_REQUEST']?></td>
         <td width="8%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NM_PBM']?></td>
         <?php if ( $kegiatan == 'stuffing_tpk') { ?>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_IN']?></td>       
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_PLACEMENT_']?></td>
         <td width="8%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NAMA_BLOK']?>-<?=$row['SLOT_']?>-<?=$row['ROW_']?>-<?=$row['TIER_']?></td>
         <?php } ?>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_REALISASI']?></td>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_REQ_DELIVERY']?></td>
<!--         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NO_REQ_DELIVERY']?></td>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NM_KAPAL']?></td> -->
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_GATE']?></td>
    </tr>
    <? $no++;}?>
</table>
<?php } else if ($kegiatan == 'delivery_tpk_mty' || $kegiatan == 'delivery_luar'){ ?>
    <table class="grid-table" border='0' cellpadding="1" cellspacing="1"  width="100%" >
    <tr>
         <th valign="top" class="grid-header"  style="font-size:8pt">No</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">No Container</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Size</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Type</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Status</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Komoditi</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Berat</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Request</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">No Request</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">EMKL</th>
         <th valign="top" class="grid-header"  style="font-size:8pt">Active</th>
         <?php if ( $kegiatan == 'delivery_tpk_mty') { ?>
         <th valign="top" class="grid-header"  style="font-size:8pt">Nm Kapal</th>
         <?php } ?>
         <th valign="top" class="grid-header"  style="font-size:8pt">Tgl Gate Out</th>
    </tr>
    <?php $no= 1;
    foreach ($row_list as $row){?>
    <tr>
        <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$no?></td>
         <td width="5%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NO_CONTAINER']?></td>
         <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['SIZE_']?></td>
         <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TYPE_']?></td>
         <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['STATUS']?></td>
         <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['COMMODITY']?></td>
         <td width="2%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['BERAT']?></td>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_REQUEST']?></td>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NO_REQUEST']?></td>
         <td width="8%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NM_PBM']?></td>
         <td width="8%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_DELIVERY']?></td>
         <?php if ( $kegiatan == 'delivery_tpk_mty') { ?>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['NM_KAPAL']?> (<?=$row['VOYAGE_IN']?>)</td>  
         <?php } ?>
         <td width="4%" align="center" valign="middle" class="grid-cell" style="color:#000; font-family:Arial; font-size:9pt"><?=$row['TGL_GATE']?></td>
    </tr>
    <? $no++;}?>
</table> 
<?php }?>