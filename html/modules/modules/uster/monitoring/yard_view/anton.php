<?
$db 		= getDB("storage");
		
		$query_block	= "SELECT PLACEMENT.SLOT_ , 
							PLACEMENT.ID_BLOCKING_AREA, 
							blocking_area.NAME, 
							PLACEMENT.ROW_, 
							concat(concat(blocking_area.NAME,PLACEMENT.SLOT_),PLACEMENT.ROW_) LOKASI
							FROM PLACEMENT, blocking_area 
							WHERE PLACEMENT.ID_BLOCKING_AREA = blocking_area.ID 
							AND blocking_area.ID_YARD_AREA ='46'";
												
		$result_block 	= $db->query($query_block);
		$data   		= $result_block->getAll();
		
		// print_r ($data);
		// debug($data);
		// $block			= $row_block["NAME"];
		
		
		
		foreach($data as $detail)
		{
			$isi = NULL;
			
			$id_blocking_area = $detail['ID_BLOCKING_AREA'];
			$slot			  = $detail['SLOT_'];
			$row2			  = $detail['ROW_'];
			$blok			  = $detail['NAME'];
			
			$query_		= "SELECT c.NAME, a.NO_CONTAINER,
								b.SIZE_, b.TYPE_ , d.STATUS_CONT STATUS, a.TIER_,
								a.SLOT_,a.ROW_
												FROM PLACEMENT a, MASTER_CONTAINER b, BLOCKING_AREA c, HISTORY_CONTAINER d WHERE 
												a.NO_CONTAINER = b.NO_CONTAINER
												AND b.NO_CONTAINER = d.NO_CONTAINER
												AND d.TGL_UPDATE = (SELECT MAX(TGL_UPDATE) FROM HISTORY_CONTAINER WHERE NO_CONTAINER = d.NO_CONTAINER)
												AND a.ID_BLOCKING_AREA = c.ID 
												AND a.ID_BLOCKING_AREA = '$id_blocking_area'
												AND a.SLOT_ = '$slot'
												AND a.ROW_ = '$row2'";
			$result__	= $db->query($query_);
			$data_		= $result__->getAll();
			
			// debug($data_);
			// debug($blok);
			
			if (count($data_) == 0)
			{
				$isi .="<table><tr><td colspan=\"2\" align=\"center\">Ra Ono Container</td></tr></table>";
			}
			else
			{
				$isi .="<table><tr><td colspan=\"2\" align=\"center\"><b>BLOK ".$blok."</b></td></tr>";
				
				foreach($data_ as $row_)
				{
					$isi .="<tr><td><img src=\"images/row_cont.png\" width=\"40\" height=\"40\"><br/>TIER 1</td><td>".$row_['NO_CONTAINER']."<br>40 | DRY | FCL</td></tr>";
				}
				
				$isi .="</table>";
				
			}
			
			// echo($isi);
			
			//echo "<script>tip('".$blok.$slot.$row2."','".$isi."')</script>";
			
			// $urut=$urut+1;
		}
?>